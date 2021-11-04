<?php

namespace SubstitutionPlugin\Provider;

final class EscapeDecoratorProvider implements AutoloadDependentProviderInterface, TolerantProviderInterface
{
    /** @var string */
    private $callback;

    /** @var TolerantProviderInterface */
    private $provider;

    /**
     * @param string $callback
     * @param TolerantProviderInterface $provider
     */
    public function __construct($callback, TolerantProviderInterface $provider)
    {
        $this->callback = $callback;
        $this->provider = $provider;
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        if (!is_callable($this->callback)) {
            throw new \InvalidArgumentException('The escape callback is not callable: ' . $this->callback);
        }

        return call_user_func($this->callback, $this->provider->getValue());
    }

    /**
     * @inheritDoc
     */
    public function mustAutoload()
    {
        return !\SubstitutionPlugin\isInternalCallback($this->callback);
    }
}
