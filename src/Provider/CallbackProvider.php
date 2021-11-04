<?php

namespace SubstitutionPlugin\Provider;

final class CallbackProvider implements AutoloadDependentProviderInterface, TolerantProviderInterface
{
    /** @var string */
    private $callback;

    /**
     * @param string $callback
     */
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        if (!is_callable($this->callback)) {
            throw new \InvalidArgumentException('Value is not callable: ' . $this->callback);
        }

        return call_user_func($this->callback);
    }

    /**
     * @inheritDoc
     */
    public function mustAutoload()
    {
        return !\SubstitutionPlugin\isInternalCallback($this->callback);
    }
}
