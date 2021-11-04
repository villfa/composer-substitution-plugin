<?php

namespace SubstitutionPlugin\Provider;

class DummyProvider implements TolerantProviderInterface
{
    /** @var mixed */
    private $value = '';

    /**
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return $this->value;
    }
}
