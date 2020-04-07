<?php

use SubstitutionPlugin\Provider\ProviderInterface;

class DummyProvider implements ProviderInterface
{
    /** @var string */
    private $value;

    /** @var int */
    private $count = 0;

    /**
     * @param string $value
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
        $this->count++;
        return $this->value;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }
}
