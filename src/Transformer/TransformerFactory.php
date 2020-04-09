<?php

namespace SubstitutionPlugin\Transformer;

use Psr\Log\LoggerInterface;
use SubstitutionPlugin\Config\PluginConfigurationInterface;
use SubstitutionPlugin\Config\SubstitutionConfigurationInterface;
use SubstitutionPlugin\Provider\ProviderFactoryInterface;

final class TransformerFactory implements TransformerFactoryInterface
{
    /** @var ProviderFactoryInterface */
    private $providerFactory;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(
        ProviderFactoryInterface $providerFactory,
        LoggerInterface $logger
    ) {
        $this->providerFactory = $providerFactory;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function getTransformer(PluginConfigurationInterface $configuration)
    {
        $nbSubstitutions = count($configuration->getMapping());
        if ($nbSubstitutions > 1) {
            $transformer = new TransformerCollection();
            foreach ($configuration->getMapping() as $conf) {
                $transformer->addTransformer($this->buildTransformer($conf));
            }
        } elseif ($nbSubstitutions === 1) {
            $conf = current($configuration->getMapping());
            $transformer = $this->buildTransformer($conf);
        } else {
            // not supposed to happen
            $this->logger->error('At least one substitution expected');
            $transformer = new NullTransformer();
        }

        return $transformer;
    }

    /**
     * @param SubstitutionConfigurationInterface $configuration
     * @return TransformerInterface
     */
    private function buildTransformer(SubstitutionConfigurationInterface $configuration)
    {
        $provider = $this->providerFactory->getProvider($configuration);

        if ($provider === null) {
            return new NullTransformer();
        }

        return new Transformer($configuration->getPlaceholder(), $provider);
    }
}
