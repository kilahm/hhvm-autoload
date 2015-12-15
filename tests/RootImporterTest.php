<?hh // strict

namespace FredEmmott\AutoloadMap;

final class RootImporterTest extends \PHPUnit_Framework_TestCase {
  public function testFullImport(): void {
    $root = realpath(__DIR__.'/../');
    $importer = new RootImporter(
      $root,
      shape(
        'autoloadFilesBehavior' => AutoloadFilesBehavior::FIND_DEFINITIONS,
        'includeVendor' => true,
        'roots' => ImmVector { $root },
      ),
    );
    $map = $importer->getAutoloadMap();
    $this->assertContains(
      'FredEmmott\AutoloadMap\Exception',
      array_keys($map['class']),
    );
    $this->assertContains(
      'PHPUnit_Framework_TestCase',
      array_keys($map['class']),
    );
    $this->assertEmpty($importer->getFiles());
  }
}
