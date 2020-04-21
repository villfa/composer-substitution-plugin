<?php

namespace SubstitutionPlugin\Provider;

final class IncludeProvider implements AutoloadDependentProviderInterface
{
    /** @var string */
    private $path;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        if (stream_resolve_include_path($this->path) === false) {
            throw new \InvalidArgumentException('Cannot include file ' . $this->path);
        }

        return returnInclude($this->path);
    }

    /**
     * @inheritDoc
     */
    public function mustAutoload()
    {
        return true;
    }
}

/**
 * Scope isolated include.
 *
 * Prevents access to $this/self from included files.
 *
 * @param string $file
 * @return mixed
 */
function returnInclude($file)
{
    return include $file;
}
