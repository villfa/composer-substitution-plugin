<?php

namespace SubstitutionPlugin\Transformer;

final class NullTransformer implements TransformerInterface
{
    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        return $value;
    }
}
