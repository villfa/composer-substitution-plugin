<?php

namespace SubstitutionPlugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Package\AliasPackage;
use Composer\Package\CompletePackage;
use Composer\Plugin\PluginInterface;
use Psr\Log\LoggerInterface;
use SubstitutionPlugin\Config\PluginConfiguration;
use SubstitutionPlugin\EventHandler\EventHandlerFactory;
use SubstitutionPlugin\EventHandler\EventHandlerInterface;
use SubstitutionPlugin\Logger\LoggerFactory;
use SubstitutionPlugin\Provider\ProviderFactory;
use SubstitutionPlugin\Transformer\TransformerFactory;
use SubstitutionPlugin\Transformer\TransformerManager;

final class SubstitutionPlugin implements PluginInterface, EventSubscriberInterface
{
    /** @var EventHandlerInterface */
    private static $eventHandler;

    /** @var Composer */
    private $composer;

    /** @var LoggerInterface */
    private $logger;

    /** @var PluginConfiguration */
    private $config;

    /**
     * @inheritDoc
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->includeRequiredFiles();
        $this->composer = $composer;
        $this->logger = $logger = LoggerFactory::getLogger($io);
        $this->config = $config = new PluginConfiguration($composer->getPackage()->getExtra(), $logger);

        $this->logger->info(
            'Plugin ' . ($config->isEnabled() ? 'enabled. {priority}' : 'disabled.'),
            array(
                'priority' => function () use ($config) {
                    return 'Priority set to ' . strval($config->getPriority()) . '.';
                },
            )
        );

        $eventHandlerFactory = new EventHandlerFactory(array($this, 'execute'), $config, $logger);
        self::$eventHandler = $eventHandlerFactory->getEventHandler();
        self::$eventHandler->activate();
    }

    /**
     * @inheritDoc
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {
        self::$eventHandler->deactivate();
    }

    /**
     * @inheritDoc
     */
    public function uninstall(Composer $composer, IOInterface $io)
    {
        self::$eventHandler->uninstall();
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return self::$eventHandler->getSubscribedEvents();
    }

    /**
     * This is needed because getSubscribedEvents() can not return callbacks pointing to other classes.
     *
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public function __call($name, array $args)
    {
        return call_user_func_array(array(self::$eventHandler, $name), $args);
    }

    /**
     * @param string[] $scriptNames
     */
    public function execute(array $scriptNames) {
        if (empty($scriptNames)) {
            return;
        }

        $package = $this->composer->getPackage();
        if ($package instanceof AliasPackage) {
            $package = $package->getAliasOf();
        }

        if ($package instanceof CompletePackage) {
            $package->setScripts($this->applySubstitutions($package->getScripts(), $scriptNames));
        }
    }

    /**
     * @param array $scripts
     * @param string[] $scriptNames
     * @return array
     */
    private function applySubstitutions(array $scripts, array $scriptNames)
    {
        $this->logger->info('Start applying substitutions on scripts: ' . implode(', ', $scriptNames));
        $providerFactory = new ProviderFactory($this->composer, $this->logger);
        $transformerFactory = new TransformerFactory($providerFactory, $this->logger);
        $transformerManager = new TransformerManager($transformerFactory, $this->config, $this->logger);

        return $transformerManager->applySubstitutions($scripts, $scriptNames);
    }

    private function includeRequiredFiles()
    {
        $files = array(
            __DIR__ . '/utils-functions.php',
        );

        foreach ($files as $file) {
            require_once $file;
        }
    }
}
