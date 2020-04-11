<?php

namespace SubstitutionPlugin\Transformer;

use Psr\Log\LoggerInterface;
use SubstitutionPlugin\Config\PluginConfigurationInterface;

final class TransformerManager
{
    /** @var array<string, bool> */
    private static $transformedScripts = array();

    /** @var TransformerInterface */
    private $transformer;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        TransformerFactoryInterface $transformerFactory,
        PluginConfigurationInterface $config,
        LoggerInterface $logger
    ) {
        $this->transformer = $transformerFactory->getTransformer($config);
        $this->logger = $logger;
    }

    public function applySubstitutions(array $scripts, $scriptName)
    {
        $this->toTransform($scriptName);

        do {
            foreach (self::$transformedScripts as $scriptName => &$transformed) {
                if ($transformed || !isset($scripts[$scriptName])) {
                    $transformed = true;
                    continue;
                }

                $this->logger->debug('Apply substitution on script ' . $scriptName);
                $transformed = true;
                $listeners = &$scripts[$scriptName];
                foreach ($listeners as &$listener) {
                    $listener = $this->transformer->transform($listener);

                    if (self::tryExtractScript($listener, $script)) {
                        $this->toTransform($script);
                    }
                }
            }
        } while ($this->hasPendingTransformations());

        return $scripts;
    }

    /**
     * @param string $scriptName
     */
    private function toTransform($scriptName)
    {
        if (!isset(self::$transformedScripts[$scriptName])) {
            self::$transformedScripts[$scriptName] = false;
        }
    }

    /**
     * @return bool
     */
    private function hasPendingTransformations()
    {
        foreach (self::$transformedScripts as $transformed) {
            if (!$transformed) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $listener
     * @param string $script
     * @return bool
     */
    private static function tryExtractScript($listener, &$script = '')
    {
        if (!isset($listener[0]) || $listener[0] !== '@') {
            return false;
        }

        // split on white-spaces
        $parts = preg_split('/\s+/', substr($listener, 1), -1, PREG_SPLIT_NO_EMPTY);

        if (empty($parts)) {
            return false;
        }

        $script = current($parts);

        return true;
    }
}
