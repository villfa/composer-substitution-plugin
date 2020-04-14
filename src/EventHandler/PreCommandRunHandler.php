<?php

namespace SubstitutionPlugin\EventHandler;

use Composer\Plugin\PluginEvents;
use Composer\Plugin\PreCommandRunEvent;
use SubstitutionPlugin\Config\PluginConfiguration;

final class PreCommandRunHandler implements EventHandlerInterface
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
        return array(
            PluginEvents::PRE_COMMAND_RUN => array(
                array('onPreCommandRun', $this->configuration->getPriority()),
            ),
        );
    }

    public function onPreCommandRun(PreCommandRunEvent $event)
    {
        call_user_func($this->callback, $this->getScripts($event));
    }

    /**
     * @param PreCommandRunEvent $event
     * @return array
     */
    private function getScripts(PreCommandRunEvent $event)
    {
        if ($event->getCommand() === 'run-script' || $event->getCommand() === 'run') {
            if ($event->getInput()->getOption('list')) {
                return array();
            }

            $scriptNames = array($event->getInput()->getArgument('script'));
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

            if (isset($scriptByCmd[$event->getCommand()])) {
                $scriptNames = $scriptByCmd[$event->getCommand()];
            } else {
                $scriptNames = array($event->getCommand());
            }
        }

        $scriptNames = array_filter($scriptNames);
        $scriptNames[] = 'command';

        return $scriptNames;
    }

    /**
     * @inheritDoc
     */
    public function activate()
    {
        // Nothing to do here
    }

    /**
     * @inheritDoc
     */
    public function deactivate()
    {
        // Nothing to do here
    }

    /**
     * @inheritDoc
     */
    public function uninstall()
    {
        // Nothing to do here
    }
}
