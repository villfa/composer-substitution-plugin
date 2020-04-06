<?php

namespace SubstitutionPlugin;

use Composer\Util\Filesystem;

class BaseEndToEndTestCase extends BaseTestCase
{
    protected static function install($dir)
    {
        self::cleanDir($dir);
        $args = self::getArgInstall();

        list($output, $exitCode) = self::runComposer($dir, $args);

        if ($exitCode > 0) {
            echo implode(PHP_EOL, $output), PHP_EOL;
            throw new \RuntimeException('Cannot install in ' . $dir, $exitCode);
        }
    }

    /**
     * @param string $dir
     * @param string $args
     * @return array
     */
    protected static function runComposer($dir, $args)
    {
        chdir(self::getProjectDir());
        $command = getenv('COMPOSER_PATH')
            . ' --no-ansi '
            . self::getArgWorkingDir($dir)
            . $args;

        exec($command, $output, $exitCode);
        return array($output, $exitCode);
    }

    /**
     * @param string $dir
     */
    protected static function safeCleanDir($dir)
    {
        try {
            @self::cleanDir($dir);
        } catch (\Exception $e) {
        } catch (\Throwable $e) {
        }
    }

    /**
     * @param string $dir
     */
    protected static function cleanDir($dir)
    {
        $fs = new Filesystem();
        foreach (array('composer.lock', 'vendor') as $toDelete) {
            $path = $dir . DIRECTORY_SEPARATOR . $toDelete;
            $fs->remove($path);
        }
    }

    /**
     * @param string $dir
     * @return string
     */
    protected static function getArgWorkingDir($dir)
    {
        return sprintf('--working-dir=%s ', escapeshellarg($dir));
    }

    protected static function getArgInstall()
    {
        return 'install --no-interaction --no-progress --no-suggest --no-dev';
    }
}
