<?php

namespace FredEmmott\AutoloadMap;

use \__SystemLib\HH\Client\CacheKeys;
use \HH\Client\TypecheckResult;
use \HH\Client\TypecheckStatus;

final class Bootstrap {
  public static function build(): \HH\void {
    require_once(__DIR__.'/unsupported/AutoTypecheck.php');
    __UNSUPPORTED__\AutoTypecheck::disable();
    require_once(__DIR__.'/../vendor/autoload.php');

    $builder = Scanner::fromTree(realpath(__DIR__));
    (new Writer())
      ->setRoot(__DIR__.'/..')
      ->setBuilder($builder)
      ->writeToFile(__DIR__.'/../bootstrap.php');
  }
}
