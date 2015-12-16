<?hh

namespace FredEmmott\AutoloadMap\TestFixtures;

require($argv[1]);

$x = new ExampleClass();
example_function();
$x = FREDEMMOTT_AUTOLOAD_MAP_TEST_FIXTURES_EXAMPLE_CONSTANT;
$x = (ExampleType $x) ==> null;
$x = (ExampleNewtype $x) ==> null;
$x = ExampleEnum::HERP;

print("OK!");
