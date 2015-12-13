<?hh // strict

namespace FredEmmott\AutoloadMap;

final class ClassesOnlyFilter implements Builder {
  public function __construct(
    private Builder $source,
  ) {
  }

  public function getFiles(): ImmVector<string> {
    return ImmVector { };
  }

  public function getAutoloadMap(): AutoloadMap {
    return shape(
      'class' => $this->source->getAutoloadMap()['class'],
      'function' => [],
      'type' => [],
      'constant' => [],
    );
  }
}
