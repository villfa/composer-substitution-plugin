<?php

use SubstitutionPlugin\Config\PluginConfigurationInterface;
use SubstitutionPlugin\Transformer\TransformerFactoryInterface;
use SubstitutionPlugin\Transformer\TransformerInterface;

class DummyTransformerFactory implements TransformerFactoryInterface
{
    /** @var TransformerInterface */
    private $transformer;

    public function __construct(TransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @inheritDoc
     */
    public function getTransformer(PluginConfigurationInterface $configuration)
    {
        return $this->transformer;
    }

    public function setTransformer(TransformerInterface $transformer)
    {
        $this->transformer = $transformer;
    }
}
