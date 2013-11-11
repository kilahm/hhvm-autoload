#include "hphp/runtime/base/base-includes.h"
#include "hphp/compiler/parser/parser.h"
#include "hphp/compiler/analysis/file_scope.h"
#include "hphp/compiler/analysis/function_scope.h"
#include "hphp/compiler/expression/expression.h"
#include "hphp/compiler/expression/simple_function_call.h"
#include "hphp/compiler/statement/exp_statement.h"
#include "hphp/compiler/statement/statement.h"

#include <queue>

namespace HPHP {

static Array HHVM_FUNCTION(
  fe_xhpast2_definitions,
  const String& path,
  int64_t type
) {
  std::ifstream stream(path.c_str());
  AnalysisResultPtr ar(new AnalysisResult());
  Scanner scanner(stream, type);
  Compiler::Parser parser(scanner, path.c_str(), ar);
  parser.parse();
  auto file_scope = ar->findFileScope(path.c_str());
  if (!file_scope) {
    ArrayInit empty(0);
    return empty.create();
  }

  auto classes = file_scope->getClasses();
  ArrayInit class_names(classes.size());
  for (auto& kv: classes) {
    class_names.set(kv.first);
  }

  auto functions = file_scope->getFunctions();
  ArrayInit function_names(functions.size());
  for (auto& kv: functions) {
    if (kv.second->inPseudoMain()) {
      // 'function' that represents the entire file
      continue;
    }
    function_names.set(kv.first);
  }

  std::vector<string> constants;
  // Global constants aren't available through getConstants() - only class
  // constants. We need to look through all function calls for define().
  //
  // "const FOO = 'bar';" is automatically mapped to define('FOO', 'bar'),
  // so this still works for those.
  auto root = parser.getTree();
  std::queue<StatementPtr> to_visit;
  to_visit.push(root);
  while (!to_visit.empty()) {
    auto stmt = to_visit.front(); to_visit.pop();

    if (stmt->is(Statement::KindOfExpStatement)) {
      auto exp = static_pointer_cast<ExpStatement>(stmt)->getExpression();
      if (exp->is(Expression::KindOfSimpleFunctionCall)) {
        auto sfc = static_pointer_cast<SimpleFunctionCall>(exp);
        if (sfc->getName() == "define") {
          auto params = sfc->getParams();
          if (params->getCount() != 2) {
            continue;
          }
          auto first = (*params)[0];
          if (!first->isLiteralString()) {
            continue;
          }
          constants.push_back(first->getLiteralString());
        }
      }
    } else {
      for (int i = 0; i < stmt->getKidCount(); ++i) {
        auto kid = stmt->getNthStmt(i);
        if (kid) {
          to_visit.push(kid);
        } // else: Kid i is not a statement
      }
    }
  }
  ArrayInit constant_names(constants.size());
  for (auto constant: constants) {
    constant_names.set(constant);
  }

  ArrayInit return_data(3);
  return_data.set(makeStaticString("class"), class_names.create());
  return_data.set(makeStaticString("function"), function_names.create());
  return_data.set(makeStaticString("constant"), constant_names.create());
  return return_data.create();
}

static class FE_XHPast2Extension : public Extension {
 public:
  FE_XHPast2Extension() : Extension("fe_xhpast2") {}
  virtual void moduleInit() {
    HHVM_FE(fe_xhpast2_definitions);
    loadSystemlib();
  }
} s_fe_xhpast2_extension;

HHVM_GET_MODULE(fe_xhpast2)

} // namespace HPHP
