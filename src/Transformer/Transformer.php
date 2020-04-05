<?php

namespace SubstitutionPlugin\Transformer;

use SubstitutionPlugin\Provider\ProviderInterface;

class Transformer implements TransformerInterface
{
    /** @var string */
    private $placeholder;

    /** @var ProviderInterface */
    private $provider;

    /**
     * @param string $placeholder
     * @param ProviderInterface $provider
     */
    public function __construct($placeholder, ProviderInterface $provider)
    {
        $this->placeholder = $placeholder;
        $this->provider = $provider;
    }

    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        if (strpos($value, $this->placeholder) === false) {
            return $value;
        }

        return str_replace($this->placeholder, $this->provider->getValue(), $value);
    }
}
