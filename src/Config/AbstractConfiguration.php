<?php

namespace SubstitutionPlugin\Config;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class AbstractConfiguration
{
    /** @var LoggerInterface */
    protected static $logger;

    protected static function setLogger(LoggerInterface $logger = null)
    {
        self::$logger = $logger === null ? new NullLogger() : $logger;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param bool $defaultValue
     * @return bool
     */
    protected static function parseBool($key, $value, $defaultValue = false)
    {
        if (is_bool($value)) {
            return $value;
        }
        if ($value === 1 || in_array(strtolower($value), array('true', 'on', '1'))) {
            self::$logger->notice("Configuration extra.substitution.$key should be a boolean.");
            return true;
        }
        if ($value === 0 || in_array(strtolower($value), array('false', 'off', '0'))) {
            self::$logger->notice("Configuration extra.substitution.$key should be a boolean.");
            return false;
        }

        self::$logger->warning("Invalid value for configuration extra.substitution.$key.");
        return $defaultValue;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param string|null $defaultValue
     * @return string|null
     */
    protected static function parseString($key, $value, $defaultValue = null)
    {
        if (!is_scalar($value)) {
            self::$logger->warning("Configuration extra.substitution.$key must be a string.");
            return $defaultValue;
        }
        if (!is_string($value)) {
            self::$logger->notice("Configuration extra.substitution.$key should be a string.");
        }

        return (string) $value;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param string[] $acceptedValues
     * @param string|null $defaultValue
     * @return string|null
     */
    protected static function parseEnum($key, $value, array $acceptedValues, $defaultValue = null)
    {
        if (is_string($value)) {
            if (in_array($value, $acceptedValues)) {
                return $value;
            }
            if (in_array(strtolower($value), $acceptedValues)) {
                self::$logger->notice("Configuration extra.substitution.$key ($value) should be in lowercase.");
                return strtolower($value);
            }
        }

        self::$logger->warning(
            "Invalid value for configuration extra.substitution.$key. Accepted values: {values}. {default}",
            array(
                'values' => function () use ($acceptedValues) {
                    return implode(', ', $acceptedValues);
                },
                'default' => function () use ($defaultValue) {
                    return $defaultValue === null ? '' : "Default to '$defaultValue'.";
                },
            )
        );

        return $defaultValue;
    }

    protected static function parseInt($key, $value, $defaultValue = null)
    {
        if (is_int($value)) {
            return $value;
        }
        if (is_float($value)) {
            if ($value != (int) $value) {
                self::$logger->notice("Configuration extra.substitution.$key must be an integer.");
            }
            return (int) $value;
        }
        if (is_string($value) && is_numeric($value)) {
            if (!ctype_digit($value)) {
                self::$logger->notice("Configuration extra.substitution.$key must be an integer.");
            }
            return (int) $value;
        }

        self::$logger->warning(
            "Invalid value for configuration extra.substitution.$key. It must be an integer. {default}",
            array(
                'default' => function () use ($defaultValue) {
                    return $defaultValue === null ? '' : "Default to $defaultValue.";
                },
            )
        );

        return $defaultValue;
    }
}
