<?hh // strict

namespace FredEmmott\AutoloadMap;

final class BootstrapTest extends \PHPUnit_Framework_TestCase {
  public function testBootstrap(): void {

    $cmd = (Vector {
      PHP_BINARY,
      '-v', 'Eval.Jit=0',
      __DIR__.'/fixtures/bootstrap_test.php',
      __DIR__.'/../bootstrap.php',
    })->map($x ==> escapeshellarg($x));
    $cmd = implode(' ', $cmd);

    $output = [];
    $exit_code = null;
    $result = exec($cmd, $output, $exit_code);
    $output = implode("\n", $output);

    $this->assertSame(0, $exit_code, $output);
    $this->assertSame($result, 'scan', $output);

  }
}
