<?php

namespace SubstitutionPlugin\EventHandler;

use Psr\Log\LoggerInterface;
use SubstitutionPlugin\Config\PluginConfiguration;

final class EventHandlerFactory implements EventHandlerFactoryInterface
{
    /** @var callable */
    private $callback;

    /** @var PluginConfiguration */
    private $configuration;

    /** @var LoggerInterface */
    private $logger;

    /**
     * @param callable $callback
     * @param PluginConfiguration $configuration
     * @param LoggerInterface $logger
     */
    public function __construct($callback, PluginConfiguration $configuration, $logger)
    {
        $this->callback = $callback;
        $this->configuration = $configuration;
        $this->logger = $logger;
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
            case !interface_exists('Psr\\Log\\LoggerInterface', true):
                $this->logger->warning('Your version of Composer is not supported by the plugin.');
                return new NullEventHandler();
            default:
                return new LegacyEventHandler($this->callback, $this->configuration);
        }
    }
}
