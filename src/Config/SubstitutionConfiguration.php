<?php

namespace SubstitutionPlugin\Config;

use Psr\Log\LoggerInterface;
use SubstitutionPlugin\Provider\ProviderType;

final class SubstitutionConfiguration extends AbstractConfiguration implements SubstitutionConfigurationInterface
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
     * Callback to a function/method to escape the value
     *
     * @var string|null
     */
    private $escapeCallback;

    /**
     * @param string $placeholder
     * @param string $type
     * @param string $value
     * @param bool $cached
     * @param string|null $escapeCallback
     */
    public function __construct($placeholder, $type, $value, $cached, $escapeCallback = null)
    {
        $this->placeholder = $placeholder;
        $this->type = $type;
        $this->value = $value;
        $this->cached = $cached;
        $this->escapeCallback = $escapeCallback;
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

        if ($placeholder === '') {
            self::$logger->warning("Configuration extra.substitution.mapping doesn't accept empty keys.");
            return null;
        }

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
        $escape = isset($conf['escape']) ? self::parseString("mapping.$placeholder.escape", $conf['escape']) : null;

        return new SubstitutionConfiguration($placeholder, $type, $value, $cached, $escape);
    }

    /**
     * @inheritDoc
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @inheritDoc
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function isCached()
    {
        return $this->cached;
    }

    /**
     * @inheritDoc
     */
    public function getEscapeCallback()
    {
        return $this->escapeCallback;
    }
}
