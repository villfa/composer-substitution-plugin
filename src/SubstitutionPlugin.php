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
use SubstitutionPlugin\Transformer\TransformerManager;

final class SubstitutionPlugin implements PluginInterface, EventSubscriberInterface
{
    /** @var bool */
    private static $enabled = false;

    /** @var int events priority */
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
        self::$enabled = $this->config->isEnabled();
        $this->logger->info(
            'Plugin ' . (self::$enabled ? 'enabled. {priority}' : 'disabled.'),
            array(
                'priority' => function () use ($config) {
                    return 'Priority set to ' . strval($config->getPriority()) . '.';
                },
            )
        );
        self::$enabled && $this->includeRequiredFiles();
    }

    /**
     * @inheritDoc
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {
        // nothing to do
    }

    /**
     * @inheritDoc
     */
    public function uninstall(Composer $composer, IOInterface $io)
    {
        // nothing to do
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        if (!self::$enabled) {
            return array();
        }

        return array(
            PluginEvents::PRE_COMMAND_RUN => array(
                array('onPreCommandRun', self::$priority),
            ),
        );
    }

    public function onPreCommandRun(PreCommandRunEvent $event)
    {
        if ($event->getCommand() === 'run-script' || $event->getCommand() === 'run') {
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

        $this->execute($scriptNames);
    }

    /**
     * @param string[] $scriptNames
     */
    private function execute($scriptNames) {
        $package = $this->composer->getPackage();
        if ($package instanceof AliasPackage) {
            $package = $package->getAliasOf();
        }

        if ($package instanceof CompletePackage) {
            $package->setScripts($this->applySubstitutions($package->getScripts(), $scriptNames));
        }
    }

    private function includeRequiredFiles()
    {
        $files = array(
            __DIR__ . '/utils-functions.php',
        );

        foreach ($files as $file) {
            $this->logger->debug('Include file: ' . $file);
            require_once $file;
        }
    }

    /**
     * @param array $scripts
     * @param string[] $scriptNames
     * @return array
     */
    private function applySubstitutions(array $scripts, array $scriptNames)
    {
        $this->logger->info('Substitutions triggered by ' . implode(', ', $scriptNames));
        $providerFactory = new ProviderFactory($this->composer, $this->logger);
        $transformerFactory = new TransformerFactory($providerFactory, $this->logger);
        $transformerManager = new TransformerManager($transformerFactory, $this->config, $this->logger);

        return $transformerManager->applySubstitutions($scripts, $scriptNames);
    }
}
