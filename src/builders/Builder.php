<?hh // strict

namespace FredEmmott\AutoloadMap;

interface Builder {
  public function getAutoloadMap(): AutoloadMap;
  public function getFiles(): ImmVector<string>;
}
