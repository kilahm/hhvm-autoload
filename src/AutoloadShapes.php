<?hh // strict

namespace FredEmmott\AutoloadMap;

type AutoloadMap = shape(
  'class' => ?array<string, string>,
  'function' => ?array<string, string>,
  'type' => ?array<string, string>,
  'failure' => ?(function(string, string):void),
);

// subtype of AutoloadMap
type AutoloadData = shape(
  'root' => string,
  'exec_files' => array<string>,
  'class' => array<string, string>,
  'function' => array<string, string>,
  'type' => array<string, string>,
  'failure' => ?(function(string, string):void),
);
