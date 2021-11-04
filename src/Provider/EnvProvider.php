<?php

namespace SubstitutionPlugin\Provider;

final class EnvProvider implements TolerantProviderInterface
{
    /** @var string */
    private $envVarName;

    /**
     * @param string $envVarName
     */
    public function __construct($envVarName)
    {
        $this->envVarName = $envVarName;
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return getenv($this->envVarName);
    }
}
