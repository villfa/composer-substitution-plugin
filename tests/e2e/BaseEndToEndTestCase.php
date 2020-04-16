<?php

namespace SubstitutionPlugin;

use Composer\Util\Filesystem;

class BaseEndToEndTestCase extends BaseTestCase
{
    const PACKAGE = 'villfa/composer-substitution-plugin';

    /**
     * @param string $dir
     * @param bool $dev
     */
    protected static function install($dir, $dev = false)
    {
        self::cleanDir($dir);
        $args = 'install --no-progress';
        $args .= $dev ? '' : ' --no-dev';

        list($output, $exitCode) = self::runComposer($dir, $args);

        if ($exitCode > 0) {
            echo implode(PHP_EOL, $output), PHP_EOL;
            throw new \RuntimeException("Cannot install in $dir", $exitCode);
        }
    }

    /**
     * @param string|null $dir
     * @param string $args
     * @param string $envVars
     * @return array
     */
    protected static function runComposer($dir, $args, $envVars = '')
    {
        chdir(self::getProjectDir());
        $command = (empty($envVars) ? '' : "$envVars ")
            . self::getVendorBinDir()
            . '/composer --no-ansi --no-interaction '
            . ($dir === null ? '' : self::getArgWorkingDir($dir))
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
}
