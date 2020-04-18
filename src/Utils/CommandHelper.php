<?php

namespace SubstitutionPlugin\Utils;

use Composer\Console\Application;
use Symfony\Component\Console\Exception\CommandNotFoundException;

final class CommandHelper
{
    public function normalizeCommand($command)
    {
        global $application;
        if ($application === null) {
            $application = new Application();
        }

        try {
            return $application->find($command)->getName();
        } catch (CommandNotFoundException $e) {
            return $command;
        }
    }

    /**
     * @param string $command
     * @param array $scriptNames
     * @return bool
     */
    public function tryGetScriptsFromCommand($command, &$scriptNames = array())
    {
        switch ($command) {
            case 'install':
                $scriptNames = array(
                    'pre-install-cmd',
                    'post-install-cmd',
                    'pre-autoload-dump',
                    'post-autoload-dump',
                    'pre-dependencies-solving',
                    'post-dependencies-solving',
                    'pre-package-install',
                    'post-package-install',
                );
                break;
            case 'update':
                $scriptNames = array(
                    'pre-update-cmd',
                    'post-update-cmd',
                    'pre-autoload-dump',
                    'post-autoload-dump',
                    'pre-package-update',
                    'post-package-update',
                    'pre-package-uninstall',
                    'post-package-uninstall',
                );
                break;
            case 'remove':
                $scriptNames = array(
                    'pre-package-uninstall',
                    'post-package-uninstall',
                );
                break;
            case 'dump-autoload':
                $scriptNames = array(
                    'pre-autoload-dump',
                    'post-autoload-dump'
                );
                break;
            case 'status':
                $scriptNames = array(
                    'pre-status-cmd',
                    'post-status-cmd',
                );
                break;
            case 'archive':
                $scriptNames = array(
                    'pre-archive-cmd',
                    'post-archive-cmd',
                );
                break;
            default:
                return false;
        }

        return true;
    }
}
