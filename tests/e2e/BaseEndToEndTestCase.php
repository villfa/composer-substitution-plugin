<?php

namespace SubstitutionPlugin;

use Composer\Util\Filesystem;

class BaseEndToEndTestCase extends BaseTestCase
{
    protected function install($dir)
    {
        $this->cleanDir($dir);
        $args = $this->getArgInstall();

        list($output, $exitCode) = $this->runComposer($dir, $args);

        if ($exitCode > 0) {
            echo $output, PHP_EOL;
            throw new \RuntimeException('Cannot install in ' . $dir, $exitCode);
        }
    }

    /**
     * @param string $dir
     * @param string $args
     * @return array
     */
    protected function runComposer($dir, $args)
    {
        chdir(self::getProjectDir());
        $command = getenv('COMPOSER_PATH')
            . ' --no-ansi '
            . $this->getArgWorkingDir($dir)
            . $args;

        exec($command, $output, $exitCode);
        return array($output, $exitCode);
    }

    /**
     * @param string $dir
     */
    protected function cleanDir($dir)
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
    protected function getArgWorkingDir($dir)
    {
        return sprintf('--working-dir=%s ', escapeshellarg($dir));
    }

    protected function getArgInstall()
    {
        return 'install --no-interaction --no-progress --no-suggest --no-dev';
    }
}
