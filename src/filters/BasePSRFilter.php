<?hh // strict

namespace FredEmmott\AutoloadMap;

abstract class BasePSRFilter implements Builder {

  abstract protected static function getExpectedPath(
    string $classname,
    string $prefix,
    string $root,
  ): string ;

  final public function __construct(
    private string $prefix,
    private string $root,
    private Builder $source,
  ) {
  }

  public function getFiles(): ImmVector<string> {
    return ImmVector { };
  }

  public function getAutoloadMap(): AutoloadMap {
    $classes =
      (new Map($this->source->getAutoloadMap()['class']))
      ->filterWithKey(
        function(string $class_name, string $file): bool {
          if (stripos($class_name, $this->prefix) !== 0) {
            return false;
          }
          $expected = static::getExpectedPath(
            $class_name,
            $this->prefix,
            $this->root,
          );
          return strtolower($expected) === strtolower($file);
        }
      );

    return shape(
      'class' => $classes->toArray(),
      'function' => [],
      'type' => [],
      'constant' => [],
    );
  }
}
