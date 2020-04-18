<?php

namespace SubstitutionPlugin\EventHandler;

use Composer\Plugin\PluginEvents;
use Composer\Plugin\PreCommandRunEvent;
use SubstitutionPlugin\Config\PluginConfiguration;
use SubstitutionPlugin\Utils\CommandHelper;

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
        $cmdHelper = new CommandHelper();
        $cmd = $cmdHelper->normalizeCommand($event->getCommand());
        if ($cmd === 'run-script') {
            if ($event->getInput()->getOption('list')) {
                return array();
            }

            $scriptNames = array($event->getInput()->getArgument('script'));
        } else {
            if (!$cmdHelper->tryGetScriptsFromCommand($cmd, $scriptNames)) {
                $scriptNames = array($cmd);
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
