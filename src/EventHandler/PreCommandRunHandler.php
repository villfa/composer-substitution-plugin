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

    /** @var CommandHelper */
    private $cmdHelper;

    /**
     * @param callable $callback
     * @param PluginConfiguration $configuration
     */
    public function __construct($callback, PluginConfiguration $configuration)
    {
        $this->callback = $callback;
        $this->configuration = $configuration;
        $this->cmdHelper = new CommandHelper();
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

    /**
     * @param PreCommandRunEvent $event
     * @return void
     */
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
        return $this->cmdHelper->getScripts($event->getCommand(), $event->getInput());
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
