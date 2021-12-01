<?php

namespace SubstitutionPlugin\Transformer;

use Psr\Log\LoggerInterface;
use SubstitutionPlugin\Config\PluginConfigurationInterface;
use SubstitutionPlugin\Utils\NonRewindableIterator;

final class TransformerManager
{
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

    /**
     * @param array $scripts
     * @param string[] $scriptNames
     * @return array
     */
    public function applySubstitutions(array $scripts, array $scriptNames)
    {
        $scriptsToTransform = new NonRewindableIterator($scriptNames);

        foreach ($scriptsToTransform as $scriptName) {
            if (!isset($scripts[$scriptName])) {
                continue;
            }

            $this->logger->debug('Apply substitution on script ' . $scriptName);
            $listeners = &$scripts[$scriptName];
            foreach ($listeners as &$listener) {
                $listener = $this->transformer->transform($listener);

                if (self::tryExtractScript($listener, $script)) {
                    $scriptsToTransform->add($script);
                }
            }
        }

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
