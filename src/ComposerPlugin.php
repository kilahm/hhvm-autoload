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
      ->setRoot($this->root)
      ->writeToFile($this->vendor.'/hh_autoload.php');
  }

  private function debugMessage(\HH\string $message) {
    if ($this->io->isDebug()) {
      $this->io->write('hhvm-autoload: '.$message);
    }
  }
}
