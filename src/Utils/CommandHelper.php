<?php

namespace SubstitutionPlugin\Utils;

use Composer\Console\Application;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Input\InputInterface;

final class CommandHelper
{
    /**
     * @param string $command
     * @param InputInterface $input
     * @return string[]
     */
    public function getScripts($command, InputInterface $input)
    {
        $command = $this->normalizeName($command);

        if ($command === 'run-script') {
            if ($input->getOption('list')) {
                return array();
            }

            $scriptNames = array($input->getArgument('script'));
        } elseif (!$this->tryGetScriptsFromCommand($command, $scriptNames)) {
            $scriptNames = array($command);
        }

        $scriptNames = array_filter($scriptNames);
        $scriptNames[] = 'command';

        return $scriptNames;
    }

    /**
     * @param string|null $commandName
     * @return string
     */
    private function normalizeName($commandName)
    {
        global $application;
        $app = $application === null ? new Application() : $application;

        try {
            $cmd = $app->find($commandName);
            $name = $cmd->getName();
            $getDefaultNameFunc = array($cmd, 'getDefaultName');
            if ($name === null && is_callable($getDefaultNameFunc)) {
                $name = call_user_func($getDefaultNameFunc);
            }
            if ($name !== null) {
                return $name;
            }
        } catch (CommandNotFoundException $e) {
        }

        return (string) $commandName;
    }

    /**
     * @param string $command
     * @param array $scriptNames
     * @return bool
     */
    private function tryGetScriptsFromCommand($command, &$scriptNames = array())
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
                    'pre-operations-exec',
                    'pre-pool-create',
                    'post-file-download',
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
                    'pre-operations-exec',
                    'pre-pool-create',
                    'post-file-download',
                );
                break;
            case 'remove':
                $scriptNames = array(
                    'pre-package-uninstall',
                    'post-package-uninstall',
                    'pre-operations-exec',
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
