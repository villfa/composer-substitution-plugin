<?php

namespace SubstitutionPlugin\EventHandler;

use SubstitutionPlugin\Config\PluginConfiguration;

final class EventHandlerFactory implements EventHandlerFactoryInterface
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
    public function getEventHandler()
    {
        switch (true) {
            case !$this->configuration->isEnabled():
                return new NullEventHandler();
            case defined('Composer\\Plugin\\PluginEvents::PRE_COMMAND_RUN'):
                return new PreCommandRunHandler($this->callback, $this->configuration);
            default:
                return new LegacyEventHandler($this->callback);
        }
    }
}
