<?hh

class FE_AutoloadMapGenerator {
  const int ALLOW_DEFAULT = 0x00;
  const int ALLOW_SHORT_TAGS = 0x01; // < ? (without the space)
  const int ALLOW_ASP_TAGS = 0x02; // < %     % > (without the spaces)
  const int ALLOW_XHP_SYNTAX = 0x08;
  const int ALLOW_HIPHOP_SYNTAX = 0x18; // HipHop-specific syntax (including XHP)

  public static function getDefinitionsForFile(
    string $path,
    int $flags = self::ALLOW_DEFAULT,
  ): array {
    return fe_autoload_map_definitions($path, $flags);
  }

  public static function getMapForTree(
    string $root,
    int $flags = self::ALLOW_DEFAULT,
    ?string $prefix = null
  ): array {
    //$root = realpath($root);
    $combined = array(
      'class' => array(),
      'function' => array(),
      'constant' => array(),
    );
    printf("%s\n", $root);

    for (
      $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($root),
        RecursiveIteratorIterator::CHILD_FIRST,
      );
      $it->valid();
      $it->next()
    ) {
      $path = $it->key();
      printf("Looking at %s\n", $path);
      $relative = $prefix.substr($path, strlen($root) + 1);
      $definitions = self::getDefinitionsForFile($path, $flags);
      foreach ($definitions['class'] as $def) {
        $combined['class'][$def] = $relative;
      }
      foreach ($definitions['function'] as $def) {
        $combined['function'][$def] = $relative;
      }
      foreach ($definitions['constant'] as $def) {
        $combined['constant'][$def] = $relative;
      }
    }

    return $combined;
  }
}

<<__Native>>
function fe_autoload_map_definitions(string $path, int $flags): array;
