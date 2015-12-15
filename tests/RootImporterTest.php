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
        'roots' => ImmVector { $root.'/src' },
      ),
    );
    $map = $importer->getAutoloadMap();
    $this->assertContains(
      'fredemmott\autoloadmap\exception',
      array_keys($map['class']),
    );

    $this->assertContains(
      'phpunit_framework_testcase',
      array_keys($map['class']),
    );
    $this->assertEmpty($importer->getFiles());
  }

  public function testImportWithoutVendor(): void {
    $root = realpath(__DIR__.'/../');
    $importer = new RootImporter(
      $root,
      shape(
        'autoloadFilesBehavior' => AutoloadFilesBehavior::FIND_DEFINITIONS,
        'includeVendor' => false,
        'roots' => ImmVector { $root.'/src' },
      ),
    );

    $map = $importer->getAutoloadMap();
    $this->assertContains(
      'fredemmott\autoloadmap\exception',
      array_keys($map['class']),
    );
    $this->assertNotContains(
      'phpunit_framework_testcase',
      array_keys($map['class']),
    );
    $this->assertEmpty($importer->getFiles());
  }
}
