<?php

namespace SubstitutionPlugin;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Package\AliasPackage;
use Composer\Package\CompletePackage;
use Composer\Plugin\PluginEvents;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\PreCommandRunEvent;
use Psr\Log\LoggerInterface;
use SubstitutionPlugin\Config\PluginConfiguration;
use SubstitutionPlugin\Logger\LoggerFactory;
use SubstitutionPlugin\Provider\ProviderFactory;
use SubstitutionPlugin\Transformer\TransformerFactory;

final class SubstitutionPlugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * @var int PreCommandRunEvent priority
     */
    private static $priority = 0;

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
        $this->composer = $composer;
        $this->logger = LoggerFactory::getLogger($io);
        $this->config = $config = new PluginConfiguration($this->composer->getPackage()->getExtra(), $this->logger);
        self::$priority = $this->config->getPriority();
        $this->logger->info(
            'Plugin ' . ($this->config->isEnabled() ? 'enabled. {priority}' : 'disabled.'),
            array(
                'priority' => function () use ($config) {
                    return 'Priority set to ' . strval($config->getPriority()) . '.';
                },
            )
        );
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            PluginEvents::PRE_COMMAND_RUN => array(
                array('onPreCommandRun', self::$priority),
            ),
        );
    }

    public function onPreCommandRun(PreCommandRunEvent $event)
    {
        if (!$this->config->isEnabled()) {
            return;
        }

        $package = $this->composer->getPackage();
        if ($package instanceof AliasPackage) {
            $package = $package->getAliasOf();
        }

        $scripts = $package->getScripts();
        $name = $event->getCommand();

        if ($name !== 'run' && $name !== 'run-script' && !isset($scripts[$name])) {
            // Not a script so no substitution
            return;
        }

        if ($package instanceof CompletePackage) {
            $package->setScripts($this->applySubstitutions($scripts));
        }
    }

    private function applySubstitutions(array $scripts)
    {
        $providerFactory = new ProviderFactory($this->composer, $this->logger);
        $transformerFactory = new TransformerFactory($providerFactory, $this->logger);
        $transformer = $transformerFactory->getTransformer($this->config);

        foreach ($scripts as &$script) {
            foreach ($script as &$listener) {
                $listener = $transformer->transform($listener);
            }
        }

        return $scripts;
    }
}
