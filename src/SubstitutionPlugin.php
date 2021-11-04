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

    /** @var TransformerManager */
    private $transformerManager;

    /**
     * @param Composer $composer
     * @param IOInterface $io
     * @return void
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->includeRequiredFiles();
        $this->composer = $composer;
        $this->logger = $logger = LoggerFactory::getLogger($io);
        $config = new PluginConfiguration($composer->getPackage()->getExtra(), $logger);

        $this->logger->info(
            'Plugin ' . ($config->isEnabled() ? 'enabled. {priority}' : 'disabled.'),
            array(
                'priority' => function () use ($config) {
                    return 'Priority set to ' . strval($config->getPriority()) . '.';
                },
            )
        );
        $providerFactory = new ProviderFactory($composer, $logger);
        $transformerFactory = new TransformerFactory($providerFactory, $logger);
        $this->transformerManager = new TransformerManager($transformerFactory, $config, $logger);
        $eventHandlerFactory = new EventHandlerFactory(array($this, 'execute'), $config);
        self::$eventHandler = $eventHandlerFactory->getEventHandler();
        self::$eventHandler->activate();
    }

    /**
     * @param Composer $composer
     * @param IOInterface $io
     * @return void
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {
        self::$eventHandler->deactivate();
    }

    /**
     * @param Composer $composer
     * @param IOInterface $io
     * @return void
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
        /** @var callable $callback */
        $callback = array(self::$eventHandler, $name);

        return call_user_func_array($callback, $args);
    }

    /**
     * @param string[] $scriptNames
     * @return void
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

        return $this->transformerManager->applySubstitutions($scripts, $scriptNames);
    }

    /**
     * @return void
     */
    private function includeRequiredFiles()
    {
        require_once __DIR__ . '/utils-functions.php';
    }
}
