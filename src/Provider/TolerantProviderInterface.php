<?php

namespace SubstitutionPlugin\Provider;

interface TolerantProviderInterface
{
    /**
     * The value should be a string.
     * The value will be replaced by an empty string if the provider returns something else.
     *
     * @return mixed
     */
    public function getValue();
}
