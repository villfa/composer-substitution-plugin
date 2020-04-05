<?php

namespace SubstitutionPlugin\Config;

use Psr\Log\LoggerInterface;
use SubstitutionPlugin\Provider\ProviderType;

class SubstitutionConfiguration extends AbstractConfiguration
{
    /** @var string */
    private $placeholder;

    /** @var string */
    private $type;

    /** @var string */
    private $value;

    /** @var bool */
    private $cached = false;

    /**
     * @param string $placeholder
     * @param string $type
     * @param string $value
     * @param bool $cached
     */
    public function __construct($placeholder, $type, $value, $cached)
    {
        $this->placeholder = $placeholder;
        $this->type = $type;
        $this->value = $value;
        $this->cached = $cached;
    }

    /**
     * @param string $placeholder
     * @param mixed $conf
     * @param LoggerInterface $logger
     * @return SubstitutionConfiguration|null
     */
    public static function parseConfiguration($placeholder, $conf, LoggerInterface $logger)
    {
        self::setLogger($logger);

        if (!is_array($conf)) {
            self::$logger->warning("Configuration extra.substitution.mapping.$placeholder must be an object.");
            return null;
        }

        if (!isset($conf['value'])) {
            self::$logger->warning("Configuration extra.substitution.mapping.$placeholder.value is missing.");
            return null;
        }

        if (!isset($conf['type'])) {
            self::$logger->warning("Configuration extra.substitution.mapping.$placeholder.type is missing.");
            return null;
        }

        if (
            null === ($value = self::parseString("mapping.$placeholder.value", $conf['value']))
            || null === ($type = self::parseEnum("mapping.$placeholder.type", $conf['type'], ProviderType::all()))
        ) {
            return null;
        }

        $cached = isset($conf['cached']) && self::parseBool("mapping.$placeholder.cached", $conf['cached']);

        return new SubstitutionConfiguration($placeholder, $type, $value, $cached);
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isCached()
    {
        return $this->cached;
    }
}
