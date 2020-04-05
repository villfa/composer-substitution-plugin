<?php

namespace SubstitutionPlugin\Provider;

class CallbackProvider implements ProviderInterface
{
    /** @var callable */
    private $callback;

    public function __construct($callback)
    {
        if (is_callable($callback)) {
            $this->callback = $callback;
            return;
        }

        $pos = strpos($callback, '::');
        if ($pos !== false) {
            $class = substr($callback, 0, $pos);
            $method = substr($callback, $pos + 2);
            // triggers autoloading
            if (method_exists($class, $method)) {
                $this->callback = array($class, $method);
                return;
            }
        }

        throw new \InvalidArgumentException("Value is not callable: $callback");
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return call_user_func($this->callback);
    }
}
