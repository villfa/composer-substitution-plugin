<?php

namespace SubstitutionPlugin\Provider;

interface AutoloadDependentProviderInterface extends ProviderInterface
{
    /**
     * @return bool
     */
    public function mustAutoload();
}
