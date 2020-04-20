<?php

namespace SubstitutionPlugin\Transformer;

interface TransformerInterface
{
    /**
     * @param string $value
     * @return string
     */
    public function transform($value);
}
