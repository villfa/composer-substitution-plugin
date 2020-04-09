<?php

namespace SubstitutionPlugin\Provider;

use Composer\Composer;
use Psr\Log\LoggerInterface;

final class AutoloaderDecoratorProvider implements ProviderInterface
{
    private static $autoload = false;

    /** @var Composer */
    private $composer;

    /** @var LoggerInterface */
    private $logger;

    /** @var ProviderInterface */
    private $provider;

    public function __construct(
        Composer $composer,
        LoggerInterface $logger,
        ProviderInterface $provider
    ) {
        $this->composer = $composer;
        $this->logger = $logger;
        $this->provider = $provider;
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        if (!self::$autoload) {
            $this->autoload();
            self::$autoload = true;
        }

        return $this->provider->getValue();
    }

    private function autoload()
    {
        $files = array();
        if ($this->composer->getConfig()->has('vendor-dir')) {
            $files[] = $this->composer->getConfig()->get('vendor-dir') . '/autoload.php';
        }

        foreach ($files as $file) {
            $this->logger->debug('Try including autoloader at: ' . $file);
            if (stream_resolve_include_path($file) !== false) {
                includeFile($file);
                return;
            }
        }

        $this->logger->warning('Cannot include autoloader');
    }
}

/**
 * Scope isolated include.
 *
 * Prevents access to $this/self from included files.
 */
function includeFile($file)
{
    include $file;
}
