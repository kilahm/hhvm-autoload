<?hh // strict

namespace FredEmmott\AutoloadMap;

abstract final class Merger {
  public static function merge(
    \ConstVector<AutoloadMap> $maps,
  ): AutoloadMap {
    return shape(
      'class' =>
        self::mergeImpl($maps->map($map ==> $map['class'])),
      'function' =>
        self::mergeImpl($maps->map($map ==> $map['function'])),
      'type' =>
        self::mergeImpl($maps->map($map ==> $map['type'])),
      'constant' =>
        self::mergeImpl($maps->map($map ==> $map['constant'])),
    );
  }

  private static function mergeImpl(
    Iterable<array<string, string>> $maps,
  ): array<string, string> {
    $out = [];
    foreach ($maps as $map) {
      foreach ($map as $def => $file) {
        $out[$def] = $file;
      }
    }
    return $out;
  }
}
