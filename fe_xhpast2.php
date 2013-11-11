<?hh

class FE_XHPAST2 {
  const int ALLOW_SHORT_TAGS = 0x01; // < ? (without the space)
  const int ALLOW_ASP_TAGS = 0x02; // < %     % > (without the spaces)
  const int RETURN_ALL_TOKENS = 0x04; // comments and whitespace
  const int ALLOW_XHP_SYNTAX = 0x08;
  const int ALLOW_HIPHOP_SYNTAX = 0x18; // HipHop-specific syntax (including XHP)
}

class FE_DefinitionParser {
  private string $path;
  private Set<string> $functions;

  public function __construct(string $path, int $flags = 0) {
    $this->path = $path;
    $results = fe_xhpast2_definitions($path, $flags | FE_XHPAST2::RETURN_ALL_TOKENS);
    $this->functions = new Set(
      hphp_array_idx('functions', $results, array())
    );
  }

  public function getFunctions(): Set<string> {
    return $this->functions();
  }
}

<<__Native>>
function fe_xhpast2_definitions(string $path, int $flags): array;
