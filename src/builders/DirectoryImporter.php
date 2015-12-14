<?hh // strict

namespace FredEmmott\AutoloadMap;

final class DirectoryImporter implements Builder {
  private Vector<Builder> $builders = Vector { };

  public function __construct(
    string $root,
    Config $config,
  ) {
    $composer = $root.'/composer.json';
    if (file_exists($composer)) {
      $this->builders[] = new ComposerImporter($composer, $config);
    }

    if (!$config['includeVendor']) {
      return;
    }

    foreach (glob($root.'/vendor/*/*/composer.json') as $dependency) {
      $dir = dirname($dependency);
      $this->builders[] = new DirectoryImporter($dir, $config);
    }
  }

  public function getAutoloadMap(): AutoloadMap {
    return Merger::merge(
      $this->builders->map($builder ==> $builder->getAutoloadMap())
    );
  }

  public function getFiles(): ImmVector<string> {
    $files = Vector { };
    foreach ($this->builders as $builder) {
      $files->addAll($builder->getFiles());
    }
    return $files->toImmVector();
  }
}
