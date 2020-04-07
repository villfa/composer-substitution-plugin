<?php

namespace SubstitutionPlugin\Provider;

class CallbackProvider implements ProviderInterface
{
    /** @var callable */
    private $callback;

    /**
     * @param string $callback
     */
    public function __construct($callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException("Value is not callable: $callback");
        }

        $this->callback = $callback;
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return call_user_func($this->callback);
    }
}
