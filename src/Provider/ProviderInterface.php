<?php

namespace SubstitutionPlugin\Provider;

interface ProviderInterface
{
    /**
     * @return string|null
     */
    public function getValue();
}
