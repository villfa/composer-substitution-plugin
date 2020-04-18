<?php

use SubstitutionPlugin\Transformer\TransformerInterface;

class DummyTransformer implements TransformerInterface
{
    /** @var string|null */
    private $value;

    /** @var int */
    private $count = 0;

    /**
     * @param string|null $value
     */
    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        $this->count++;

        if ($this->value !== null) {
            if (is_callable($this->value)) {
                return call_user_func($this->value, $value);
            }

            return $this->value;
        }

        return $value;
    }

    public function getCount()
    {
        return $this->count;
    }
}
