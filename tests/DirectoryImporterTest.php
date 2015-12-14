<?hh // strict

namespace FredEmmott\AutoloadMap;

final class DirectoryImporterTest extends \PHPUnit_Framework_TestCase {
  public function testFullImport(): void {
    $this->markTestSkipped(
      "Can't yet load full tree",
    );

    $root = realpath(__DIR__.'/../');
    $importer = new DirectoryImporter(
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
