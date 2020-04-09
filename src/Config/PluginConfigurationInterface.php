<?php

namespace SubstitutionPlugin\Config;

interface PluginConfigurationInterface
{
    /**
     * @return bool
     */
    public function isEnabled();

    /**
     * @return int
     */
    public function getPriority();

    /**
     * @return SubstitutionConfigurationInterface[]
     */
    public function getMapping();
}
