<?hh // strict

namespace FredEmmott\AutoloadMap;

final class ComposerImporterTest extends \PHPUnit_Framework_TestCase {
  public function testRootImportWithScannedFiles(): void {
    $root = realpath(__DIR__.'/../');
    $importer = new ComposerImporter(
      $root.'/composer.json',
      shape(
        'autoloadFilesBehavior' => AutoloadFilesBehavior::FIND_DEFINITIONS,
        'includeVendor' => false,
        'roots' => ImmVector { $root },
      ),
    );
    $this->assertEmpty($importer->getFiles());

    $map = $importer->getAutoloadMap();
    $this->assertSame(
      $root.'/src/Exception.php',
      idx($map['class'], 'FredEmmott\AutoloadMap\Exception'),
    );
    $this->assertSame(
      $root.'/src/Config.php',
      idx($map['type'], 'FredEmmott\AutoloadMap\Config'),
    );
  }

  public function testRootImportWithRequiredFiles(): void {
    $root = realpath(__DIR__.'/../');
    $importer = new ComposerImporter(
      $root.'/composer.json',
      shape(
        'autoloadFilesBehavior' => AutoloadFilesBehavior::EXEC_FILES,
        'includeVendor' => false,
        'roots' => ImmVector { $root },
      ),
    );

    $map = $importer->getAutoloadMap();
    $this->assertEmpty($map['type']);
    $this->assertContains(
      $root.'/src/AutoloadMap.php',
      $importer->getFiles(),
    );
  }
}
