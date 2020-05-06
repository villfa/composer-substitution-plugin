<?php

namespace SubstitutionPlugin;

use Composer\Util\Filesystem;
use PHPUnit\Runner\Version;

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

        // literal
        $output = self::runComposer($dir, 'test-foo');
        self::assertEquals('foo', array_pop($output));

        // env
        $output = self::runComposer($dir, 'test-bar', 'BAR=bar-value');
        self::assertEquals('bar-value', array_pop($output));

        // constant
        $output = self::runComposer($dir, 'test-atom');
        self::assertEquals(\DateTime::ATOM, array_pop($output));

        // callback
        $output = self::runComposer($dir, 'test-phpversion');
        self::assertEquals(PHP_VERSION, array_pop($output));

        // process
        $output = self::runComposer($dir, 'test-md5');
        self::assertEquals(md5('test'), array_pop($output));

        // include
        copy(__DIR__ . '/return-baz.php', $this->currentTestDir . '/return-baz.php');
        $output = self::runComposer($dir, 'test-baz');
        self::assertEquals('baz', array_pop($output));

        // cache + escape
        $output = self::runComposer($dir, 'test-cache');
        $output = array_pop($output);
        self::assertStringContainsString('/', $output);
        self::assertTrue(strlen($output) > 3);
        list($a, $b) = explode('/', $output);
        self::assertEquals($a, $b);

        // composer command
        $output = self::runComposer($dir, 'status');
        self::assertContains('PRE STATUS SUBSTITUTION', $output);

        // composer command abbreviation
        $output = self::runComposer($dir, 'st');
        self::assertContains('PRE STATUS SUBSTITUTION', $output);
    }

    public function provideComposerVersions()
    {
        $versions = array(
            array('snapshot'),
            array('1.10.6'),
            array('1.9.3'),
            array('1.8.6'),
            array('1.7.3'),
            array('1.6.5'),
        );

        if (version_compare(PHP_VERSION, '7.3') < 0) {
            $versions[] = '1.5.6';
            $versions[] = '1.4.3';
            $versions[] = '1.3.3';
            $versions[] = '1.2.4';
            $versions[] = '1.1.3';
            $versions[] = '1.0.3';
        }

        return $versions;
    }

    private static function runComposer($dir, $args, $envVars = '')
    {
        chdir($dir);
        $command = 'php '. $dir . '/composer.phar --no-ansi --no-interaction ' . $args;
        if (!empty($envVars)) {
            $command = $envVars . ' ' . $command;
        }

        exec($command, $output, $exitCode);

        if ($exitCode > 0) {
            echo implode(PHP_EOL, $output), PHP_EOL;
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
