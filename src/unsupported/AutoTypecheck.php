<?php

namespace FredEmmott\AutoloadMap\__UNSUPPORTED__;

use \__SystemLib\HH\Client\CacheKeys;
use \HH\Client\TypecheckResult;
use \HH\Client\TypecheckStatus;

/***********
 * WARNING *
 ***********
 *
 * Both the concept of this class and the way it's implemented:
 *  - kill kittens
 *  - may break at any time
 *  - are not supported for any user, ever
 */
final class AutoTypecheck {
  public static function disable(): \HH\void {
    /* Theses APC sets and the '<?php' are because of
     * auto-typecheck being over-eager:
     *
     * https://github.com/facebook/hhvm/issues/6666
     */
    $stamp = '/tmp/hh_server/stamp';
    if (file_exists($stamp)) {
      $time = filemtime($stamp);
    } else {
      $time = 0;
    }
    apc_store(CacheKeys::TIME_CACHE_KEY, $time);
    apc_store(
      CacheKeys::RESULT_CACHE_KEY,
      new TypecheckResult(TypecheckStatus::SUCCESS, /* error = */ null)
    );
  }
}
