<?php

namespace SubstitutionPlugin\Transformer;

use Psr\Log\LoggerInterface;
use SubstitutionPlugin\Config\PluginConfigurationInterface;

final class TransformerManager
{
    /** @var TransformerFactoryInterface */
    private $transformerFactory;

    /** @var PluginConfigurationInterface */
    private $config;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        TransformerFactoryInterface $transformerFactory,
        PluginConfigurationInterface $config,
        LoggerInterface $logger
    ) {
        $this->transformerFactory = $transformerFactory;
        $this->config = $config;
        $this->logger = $logger;
    }

    public function applySubstitutions(array $scripts, $scriptName)
    {
        $transformer = $this->transformerFactory->getTransformer($this->config);
        $transformedScripts = array($scriptName => false);

        do {
            foreach ($transformedScripts as $scriptName => &$transformed) {
                if ($transformed || !isset($scripts[$scriptName])) {
                    $transformed = true;
                    continue;
                }

                $this->logger->debug('Apply substitution on script ' . $scriptName);
                $transformed = true;
                $listeners = &$scripts[$scriptName];
                foreach ($listeners as &$listener) {
                    $listener = $transformer->transform($listener);

                    if (self::tryExtractScript($listener, $script)) {
                        $transformedScripts[$script] = false;
                    }
                }
            }

            $needTransformation = false;
            foreach ($transformedScripts as $transformed) {
                if (!$transformed) {
                    $needTransformation = true;
                    break;
                }
            }
        } while ($needTransformation);

        return $scripts;
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
