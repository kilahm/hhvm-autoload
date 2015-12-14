<?hh // strict

namespace FredEmmott\AutoloadMap;

final class PSR4Filter extends BasePSRFilter {
  protected static function getExpectedPath(
    string $class_name,
    string $prefix,
    string $root,
  ): string {
    $local_part = str_replace($prefix, '', $class_name);
    $expected = $root.strtr($local_part, "\\", '/').'.php';
    return $expected;
  }
}
