<?php

namespace SubstitutionPlugin\Transformer;

class NullTransformer implements TransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        return $value;
    }
}
