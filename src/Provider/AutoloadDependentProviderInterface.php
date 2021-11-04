<?php

namespace SubstitutionPlugin\Provider;

interface AutoloadDependentProviderInterface
{
    /**
     * @return bool
     */
    public function mustAutoload();
}
