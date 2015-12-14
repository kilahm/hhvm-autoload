<?hh // strict

namespace FredEmmott\AutoloadMap;

class Exception extends \Exception {
  public function __construct(
    \HH\FormatString<\PlainSprintf> $format,
    /* HH_FIXME[4033] expected type constraint */
    ...$args 
  ) {
    /* HH_FIXME[4027] - the typechecker's printf support doesn't allow
     * passing it along to something else that has validated format
     * strings */
    parent::__construct(sprintf($format, ...$args));
  }
}
