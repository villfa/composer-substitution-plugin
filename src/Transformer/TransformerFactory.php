<?php

namespace SubstitutionPlugin\Transformer;

use Psr\Log\LoggerInterface;
use SubstitutionPlugin\Config\PluginConfiguration;
use SubstitutionPlugin\Config\SubstitutionConfiguration;
use SubstitutionPlugin\Provider\ProviderFactory;

class TransformerFactory
{
    /** @var ProviderFactory */
    private $providerFactory;

    /** @var LoggerInterface */
    private $logger;

    public function __construct(ProviderFactory $providerFactory, LoggerInterface $logger)
    {
        $this->providerFactory = $providerFactory;
        $this->logger = $logger;
    }

    /**
     * @param PluginConfiguration $configuration
     * @return TransformerInterface
     */
    public function getTransformer(PluginConfiguration $configuration)
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
     * @param SubstitutionConfiguration $configuration
     * @return TransformerInterface
     */
    private function buildTransformer(SubstitutionConfiguration $configuration)
    {
        $provider = $this->providerFactory->getProvider($configuration);

        if ($provider === null) {
            return new NullTransformer();
        }

        return new Transformer($configuration->getPlaceholder(), $provider);
    }
}
