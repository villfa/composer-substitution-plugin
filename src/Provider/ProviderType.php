<?php

namespace SubstitutionPlugin\Provider;

/**
 * Describes substitution types.
 */
class ProviderType
{
    const LITERAL = 'literal';
    const CALLBACK = 'callback';
    const INCLUDE_PHP = 'include';
    const ENV = 'env';
    const CONSTANT = 'constant';

    public static function all()
    {
        return array(
            self::LITERAL,
            self::CALLBACK,
            self::INCLUDE_PHP,
            self::ENV,
            self::CONSTANT,
        );
    }
}
