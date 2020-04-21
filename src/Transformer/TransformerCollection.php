<?php

namespace SubstitutionPlugin\Transformer;

final class TransformerCollection implements TransformerInterface
{
    /** @var TransformerInterface[] */
    private $transformers;

    /**
     * @param TransformerInterface[] $transformers
     */
    public function __construct(array $transformers = array())
    {
        $this->transformers = $transformers;
    }

    /**
     * @param TransformerInterface $transformer
     * @return void
     */
    public function addTransformer(TransformerInterface $transformer)
    {
        $this->transformers[] = $transformer;
    }

    /**
     * @inheritDoc
     */
    public function transform($value)
    {
        foreach ($this->transformers as $transformer) {
            $value = $transformer->transform($value);
        }

        return $value;
    }
}
