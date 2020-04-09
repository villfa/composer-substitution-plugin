<?php

namespace SubstitutionPlugin\Provider;

use SubstitutionPlugin\Config\SubstitutionConfigurationInterface;

interface ProviderFactoryInterface
{
    /**
     * @param SubstitutionConfigurationInterface $configuration
     * @return ProviderInterface|null
     */
    public function getProvider(SubstitutionConfigurationInterface $configuration);
}
