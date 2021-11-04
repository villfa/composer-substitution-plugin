<?php

namespace SubstitutionPlugin\Provider;

interface ProviderInterface extends TolerantProviderInterface
{
    /**
     * @return string
     */
    public function getValue();
}
