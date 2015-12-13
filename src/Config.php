<?hh // strict

namespace FredEmmott\AutoloadMap;

type Config = shape(
  'autoloadFilesBehavior' => AutoloadFilesBehavior,
  'includeVendor' => bool,
  'roots' => ImmVector<string>,
);
