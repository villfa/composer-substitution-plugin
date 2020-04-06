<?php

namespace SubstitutionPlugin\Provider;

class IncludeProvider implements ProviderInterface
{
    /** @var string */
    private $path;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        if (stream_resolve_include_path($path) === false) {
            throw new \InvalidArgumentException('Cannot include file ' . $path);
        }

        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return self::returnInclude($this->path);
    }

    /**
     * @param string $path
     * @return mixed
     */
    private static function returnInclude($path)
    {
        return include $path;
    }
}
