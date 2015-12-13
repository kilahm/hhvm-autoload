<?hh // strict

namespace FredEmmott\AutoloadMap;

type AutoloadMap = shape(
  'class' => array<string, string>,
  'function' => array<string, string>,
  'type' => array<string, string>,
  'constant' => array<string, string>,
);
