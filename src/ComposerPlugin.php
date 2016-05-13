<?php

namespace FredEmmott\AutoloadMap;

use Composer\Composer;
use Composer\Config;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

final class ComposerPlugin
  implements PluginInterface, EventSubscriberInterface {

  private $vendor;
  private $root;
  private $io;

  public function activate(Composer $composer, IOInterface $io) {
    $this->io = $io;
    $this->vendor = $composer->getConfig()->get('vendor-dir');
    $this->root = getcwd();
  }

  public static function getSubscribedEvents() {
    return [
      ScriptEvents::POST_AUTOLOAD_DUMP => [
        ['onPostAutoloadDump', 0],
      ],
    ];
  }

  public function onPostAutoloadDump(Event $event) {
    $this->debugMessage("Disabling AutoTypecheck");
    require_once($this->vendor.'/fredemmott/hhvm-autoload/src/unsupported/AutoTypecheckGuard.php');
    $typechecker_guard = new __UNSUPPORTED__\AutoTypecheckGuard();
    $this->debugMessage("Loading composer autoload");
    require_once($this->vendor.'/autoload.php');

    $this->debugMessage("Parsing tree");
    $importer = new RootImporter($this->root);

    $this->debugMessage("Writing hh_autoload.php");
    (new Writer())
      ->setBuilder($importer)
      ->setRoot($this->pathDifference($this->vendor, $this->root));
      ->writeToFile($this->vendor.'/hh_autoload.php');
  }

  private function debugMessage(\HH\string $message) {
    if ($this->io->isDebug()) {
      $this->io->write('hhvm-autoload: '.$message);
    }
  }

  private function pathDifference(\HH\string $from, \HH\string $to) {
    $realFrom = realpath($from);
    $realTo = realpath($to);

    // Check if on different Windows drives
    if(substr($realFrom, 0, 1) !== substr($realTo, 0, 1)) {
      throw new \RuntimeException('Cannot find difference in path for separate drives.');
    }

    $fromParts = explode(DIRECTORY_SEPERATOR, realpath($from));
    $toParts = explode(DIRECTORY_SEPERATOR, realpath($to));

    // Remove common paths from the front
    while(count($fromParts) && count($toParts) && $fromParts[0] === $toParts[0]) {
      array_shift($fromParts);
      array_shift($toParts);
    }

    // Trasform the left over from parts to ..
    $upParts = array_map(function($part) { return '..'; }, $fromParts);

    return implode(DIRECTORY_SEPERATOR, array_merge($upParts, $toParts));
  }
}
