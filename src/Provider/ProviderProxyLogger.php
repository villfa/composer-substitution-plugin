<?php

namespace SubstitutionPlugin\Provider;

use Psr\Log\LoggerInterface;
use SubstitutionPlugin\Config\SubstitutionConfiguration;

class ProviderProxyLogger implements ProviderInterface
{
    /** @var LoggerInterface */
    private $logger;

    /** @var SubstitutionConfiguration */
    private $configuration;

    /** @var ProviderInterface */
    private $provider;

    public function __construct(
        LoggerInterface $logger,
        SubstitutionConfiguration $configuration,
        ProviderInterface $provider
    ) {
        $this->logger = $logger;
        $this->configuration = $configuration;
        $this->provider = $provider;
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        $value = $this->provider->getValue();

        if ($value === null) {
            $this->logger->debug(sprintf(
                'The value replacing "%s" is null.',
                $this->configuration->getPlaceholder()
            ));
            return '';
        }

        if (!is_scalar($value)) {
            $this->logger->error(sprintf(
                'The value replacing "%s" must be a string. "%s" received.',
                $this->configuration->getPlaceholder(),
                gettype($value)
            ));
            return '';
        }

        $this->logger->debug(sprintf(
            'The value replacing "%s" is: %s',
            $this->configuration->getPlaceholder(),
            $value
        ));
        return $value;
    }
}
