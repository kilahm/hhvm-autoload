<?hh // strict

namespace FredEmmott\AutoloadMap;

final class PSR0Filter extends BasePSRFilter {
  protected static function getExpectedPath(
    string $class_name,
    string $prefix,
    string $root,
  ): string {
    return $root.strtr($class_name, "\\", '/').'.php';
  }
}
