<?hh

class FE_AutoloadMapGenerator {
  const int ALLOW_SHORT_TAGS = 0x01; // < ? (without the space)
  const int ALLOW_ASP_TAGS = 0x02; // < %     % > (without the spaces)
  const int ALLOW_XHP_SYNTAX = 0x08;
  const int ALLOW_HIPHOP_SYNTAX = 0x18; // HipHop-specific syntax (including XHP)

  public static function getDefinitionsForFile(
    string $path,
    int $flags,
  ): array {
    return fe_autoload_map_definitions($path, $flags);
  }
}

<<__Native>>
function fe_autoload_map_definitions(string $path, int $flags): array;
