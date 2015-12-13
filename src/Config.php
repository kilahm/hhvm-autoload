<?hh // strict

namespace FredEmmott\AutoloadMap;

type Config = shape(
  'autoloadFilesBehavior' => AutoloadFilesBehavior,
  'composerJsonFallback' => bool,
  'includeVendor' => bool,
  'roots' => ImmVector<string>,
);
