<?hh // strict

namespace FredEmmott\AutoloadMap;

type Config = shape(
  'autoloadFilesBehavior' => AutoloadFilesBehavior,
  'composerJsonFallback' => bool,
  'roots' => ImmVector<string>,
);
