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
        return returnInclude($this->path);
    }
}

/**
 * Scope isolated include.
 *
 * Prevents access to $this/self from included files.
 *
 * @return mixed
 */
function returnInclude($file)
{
    return include $file;
}
