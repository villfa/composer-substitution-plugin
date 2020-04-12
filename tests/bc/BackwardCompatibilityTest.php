<?php

namespace SubstitutionPlugin;

use Composer\Util\Filesystem;
use Symfony\Component\Process\PhpExecutableFinder;

class BackwardCompatibilityTest extends BaseTestCase
{
    /** @var Filesystem */
    private static $fs;

    /** @var string */
    private static $tmpDir;

    /** @var string */
    private $currentTestDir;

    public static function doSetUpBeforeClass()
    {
        parent::doSetUpBeforeClass();
        self::$tmpDir = sys_get_temp_dir()
            . DIRECTORY_SEPARATOR
            . uniqid('substitution_test_dir_', true);

        self::$fs = new Filesystem();
        self::$fs->remove(self::$tmpDir);
        self::$fs->ensureDirectoryExists(self::$tmpDir);
    }

    public static function doTearDownAfterClass()
    {
        parent::doTearDownAfterClass();
        self::$fs->remove(self::$tmpDir);
    }

    protected function doSetUp()
    {
        parent::doSetUp();
        $this->currentTestDir = null;
    }

    protected function doTearDown()
    {
        parent::doTearDown();
        self::$fs->remove($this->currentTestDir);
    }

    /**
     * @dataProvider provideComposerVersions
     * @param string $version
     */
    public function testComposerBackwardCompatibility($version)
    {
        echo "\nTest with Composer $version\n";
        $dir = $this->setupRepo($version);
        self::runComposer($dir, 'install --no-progress --no-dev');

        // callback
        $output = self::runComposer($dir, 'test-phpversion');
        self::assertEquals(PHP_VERSION, array_pop($output));
    }

    public function provideComposerVersions()
    {
        return array(
            //array('snapshot'),
            array('1.10.5'),
            array('1.9.3'),
            array('1.8.6'),
            array('1.7.3'),
            // PRE_COMMAND_RUN doesn't exist with Composer < 1.7.0
        );
    }

    /**
     * @dataProvider provideComposerNonSupportedVersions
     * @param string $version
     */
    public function testComposerNonSupportedVersion($version)
    {
        echo "\nTest with Composer $version\n";
        $dir = $this->setupRepo($version);
        $output = self::runComposer($dir, '-vv install --no-progress --no-dev');
        $output = implode(PHP_EOL, $output);

        self::assertStringContainsString('Plugin disabled.', $output);
        self::assertStringContainsString('Your version of Composer is not supported by the plugin.', $output);
    }

    public function provideComposerNonSupportedVersions()
    {
        return array(
            array('1.6.5'),
        );
    }

    private static function runComposer($dir, $args)
    {
        chdir($dir);
        $command = 'php '. $dir . '/composer.phar --no-ansi --no-interaction ' . $args;
        exec($command, $output, $exitCode);

        if ($exitCode > 0) {
            echo implode(PHP_EOL, $output), PHP_EOL;
            throw new \RuntimeException("Exec error: $command", $exitCode);
        }

        return $output;
    }

    private function setupRepo($version)
    {
        $this->currentTestDir = self::$tmpDir . DIRECTORY_SEPARATOR . str_replace('.', '_', $version);
        self::$fs->ensureDirectoryExists($this->currentTestDir);

        $this->createComposerJson(realpath(__DIR__ . '/../..'), $this->currentTestDir);

        if ($version === 'snapshot') {
            $url = 'https://getcomposer.org/composer.phar';
        } else {
            $url = "https://getcomposer.org/download/$version/composer.phar";
        }

        file_put_contents($this->currentTestDir . '/composer.phar', fopen($url, 'r'));

        return $this->currentTestDir;
    }

    private function createComposerJson($pluginPath, $destinationDir)
    {
        $content = file_get_contents(__DIR__ . '/composer-tpl.json');
        $content = str_replace('{{PLUGIN_PATH}}', addslashes($pluginPath), $content);
        $destination = realpath($destinationDir) . '/composer.json';
        if (file_put_contents($destination, $content) === false) {
            throw new \RuntimeException('Cannot write file: ' . $destination);
        }
    }
}
