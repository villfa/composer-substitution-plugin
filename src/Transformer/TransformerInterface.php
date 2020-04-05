<?php

namespace SubstitutionPlugin\Transformer;

interface TransformerInterface
{
    /**
     * @param string
     * @return string
     */
    public function transform($value);
}
