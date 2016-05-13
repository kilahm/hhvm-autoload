<?hh // strict

namespace FredEmmott\AutoloadMap;

final class Writer {
  private ?ImmVector<string> $files;
  private ?AutoloadMap $map;
  private ?string $root;

  public function setFiles(ImmVector<string> $files): this {
    $this->files = $files;
    return $this;
  }

  public function setAutoloadMap(AutoloadMap $map): this {
    $this->map = $map;
    return $this;
  }

  public function setBuilder(Builder $builder): this {
    $this->files = $builder->getFiles();
    $this->map = $builder->getAutoloadMap();
    return $this;
  }

  public function setRoot(string $root): this {
    $this->root = $root;
    return $this;
  }

  public function writeToFile(
    string $destination_file,
  ): this {
    $files = $this->files;
    $map = $this->map;

    if ($files === null) {
      throw new Exception('Call setFiles() before writeToFile()');
    }
    if ($map === null) {
      throw new Exception('Call setAutoloadMap(0 before writeToFile()');
    }

    $requires = implode(
      "\n",
      $files->map(
        $file ==> 'require_once("'.$file.'");'
      ),
    );

    $map = array_map(
      function ($sub_map): array<string, string> {
        assert(is_array($sub_map));
        return array_map(
          $path ==> $this->relativePath($path),
          $sub_map,
        );
      },
      Shapes::toArray($map),
    );
    $map = var_export($map, true);
    $root = var_export($this->root.'/', true);
    $code = <<<EOF
<?hh

/// Generated file, do not edit by hand ///

$requires

HH\autoload_set_paths($map, $root);
EOF;
    file_put_contents(
      $destination_file,
      $code,
    );

    return $this;
  }

  <<__Memoize>>
  private function relativePath(
    string $path,
  ): string {
    $root = $this->root;
    if ($root === null) {
      throw new Exception('Call setRoot() before writeToFile()');
    }
    $path = realpath($path);
    if (strpos($path, $root) !== 0) {
      throw new Exception(
        "%s is outside root %s",
        $path,
        $root,
      );
    }
    return substr($path, strlen($root) + 1);
  }
}
