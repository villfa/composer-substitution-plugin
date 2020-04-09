<?php

namespace SubstitutionPlugin\Config;

interface SubstitutionConfigurationInterface
{
    /**
     * @return string
     */
    public function getPlaceholder();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getValue();

    /**
     * @return bool
     */
    public function isCached();

    /**
     * @return string|null
     */
    public function getEscapeCallback();
}
