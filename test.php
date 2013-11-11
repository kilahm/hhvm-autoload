<?hh

define('EXAMPLE_CONSTANT_1', 'herp');
const EXAMPLE_CONSTANT_2 = 'derp';
function example_function() {
}
class ExampleClass {
  /* Should not be in function map */
  const EXAMPLE_MEMBER_CONSTANT = 'how do I shot web?';
  function exampleMemberFunction() {
  }
}

$native = fe_autoload_map_definitions(__FILE__, 0x18);
$wrapped = FE_AutoloadMapGenerator::getDefinitionsForFile(
  __FILE__,
  FE_AutoloadMapGenerator::ALLOW_HIPHOP_SYNTAX,
);

if (array_diff($native, $wrapped) || array_diff($wrapped, $native)) {
  throw "Mismatch between native and wrapped\n";
}
printf("From %s:\n\n%s\n\n", __FILE__, var_export($native, true));
