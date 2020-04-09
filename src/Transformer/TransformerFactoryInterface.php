<?php

namespace SubstitutionPlugin\Transformer;

use SubstitutionPlugin\Config\PluginConfigurationInterface;

interface TransformerFactoryInterface
{
    /**
     * @param PluginConfigurationInterface $configuration
     * @return TransformerInterface
     */
    public function getTransformer(PluginConfigurationInterface $configuration);
}
