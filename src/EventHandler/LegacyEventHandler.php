<?php

namespace SubstitutionPlugin\EventHandler;

use SubstitutionPlugin\Config\PluginConfiguration;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

final class LegacyEventHandler implements EventHandlerInterface
{
    /** @var callable */
    private $callback;

    /** @var PluginConfiguration */
    private $configuration;

    /**
     * @param callable $callback
     * @param PluginConfiguration $configuration
     */
    public function __construct($callback, PluginConfiguration $configuration)
    {
        $this->callback = $callback;
        $this->configuration = $configuration;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents()
    {
        return array();
    }

    public function activate()
    {
        try {
            $scriptNames = $this->getScripts();
        } catch (\Exception $e) {
            $scriptNames = array();
        }

        call_user_func($this->callback, $scriptNames);
    }

    /**
     * @return array
     */
    private function getScripts()
    {
        $definition = new InputDefinition(array(
            // default definition
            new InputArgument('command', InputArgument::REQUIRED, 'The command to execute'),

            new InputOption('--help', '-h', InputOption::VALUE_NONE, 'Display this help message'),
            new InputOption('--quiet', '-q', InputOption::VALUE_NONE, 'Do not output any message'),
            new InputOption('--verbose', '-v|vv|vvv', InputOption::VALUE_NONE, 'Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug'),
            new InputOption('--version', '-V', InputOption::VALUE_NONE, 'Display this application version'),
            new InputOption('--ansi', '', InputOption::VALUE_NONE, 'Force ANSI output'),
            new InputOption('--no-ansi', '', InputOption::VALUE_NONE, 'Disable ANSI output'),
            new InputOption('--no-interaction', '-n', InputOption::VALUE_NONE, 'Do not ask any interactive question'),

            // custom Composer definition
            new InputOption('--profile', null, InputOption::VALUE_NONE),
            new InputOption('--no-plugins', null, InputOption::VALUE_NONE),
            new InputOption('--working-dir', '-d', InputOption::VALUE_REQUIRED),
            new InputOption('--no-cache', null, InputOption::VALUE_NONE),

            // run-script
            new InputArgument('script', InputArgument::OPTIONAL),
            new InputOption('list', 'l', InputOption::VALUE_NONE),
        ));

        $input = new ArgvInput(null, $definition);
        $cmd = $input->getArgument('command');

        if (
            $input->getOption('version')
            || $input->getOption('help')
            || empty($cmd)
        ) {
            return array();
        }

        if ($cmd === 'run-script' || $cmd === 'run') {
            if ($input->getOption('list')) {
                return array();
            }

            $scriptNames = array($input->getArgument('script'));
        } else {
            $scriptByCmd = array(
                'install' => array(
                    'pre-install-cmd',
                    'post-install-cmd',
                    'pre-autoload-dump',
                    'post-autoload-dump',
                    'pre-dependencies-solving',
                    'post-dependencies-solving',
                    'pre-package-install',
                    'post-package-install',
                ),
                'update' => array(
                    'pre-update-cmd',
                    'post-update-cmd',
                    'pre-autoload-dump',
                    'post-autoload-dump',
                    'pre-package-update',
                    'post-package-update',
                    'pre-package-uninstall',
                    'post-package-uninstall',
                ),
                'remove' => array(
                    'pre-package-uninstall',
                    'post-package-uninstall',
                ),
                'dump-autoload' => array(
                    'pre-autoload-dump',
                    'post-autoload-dump'
                ),
                'status' => array(
                    'pre-status-cmd',
                    'post-status-cmd',
                ),
                'archive' => array(
                    'pre-archive-cmd',
                    'post-archive-cmd',
                ),
            );

            if (isset($scriptByCmd[$cmd])) {
                $scriptNames = $scriptByCmd[$cmd];
            } else {
                $scriptNames = array($cmd);
            }
        }

        $scriptNames = array_filter($scriptNames);
        $scriptNames[] = 'command';

        return $scriptNames;
    }

    public function deactivate()
    {
        // Nothing to do here
    }

    public function uninstall()
    {
        // Nothing to do here
    }
}
