<?hh // strict

namespace FredEmmott\AutoloadMap;

final class Writer {
  const string DS = '/';
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
    $this->root = realpath($root);
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
      throw new Exception('Call setAutoloadMap() before writeToFile()');
    }

    $requires = implode(
      "\n",
      $files->map(
        $file ==> 'require_once($root."'.$this->relativePath($file).'");',
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
    $root = $this->relativeRoot(dirname($destination_file));
    $code = <<<EOF
<?hh

/// Generated file, do not edit by hand ///
\$root = $root;
$requires

HH\autoload_set_paths($map, \$root);
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

  private function relativeRoot(string $destination_dir): string {
    $from = realpath($destination_dir);
    $to = (string) $this->root;

    $from_parts = explode(self::DS, $from);
    $to_parts = explode(self::DS, $to);

    // Remove common paths from the front
    while (count($from_parts) &&
           count($to_parts) &&
           $from_parts[0] === $to_parts[0]) {
      array_shift($from_parts);
      array_shift($to_parts);
    }

    $up_count = count($from_parts);

    if ($up_count === 0) {
      return var_export($to);
    }

    $open = str_repeat('dirname(', $up_count);
    $close = str_repeat(')', $up_count);
    $relative_to =
      count($to_parts) > 0
        ? self::DS.implode(self::DS, $to_parts).self::DS
        : self::DS;

    return $open.'__DIR__'.$close.'.'.var_export($relative_to, true);
  }
}
