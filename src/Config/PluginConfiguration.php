<?php

namespace SubstitutionPlugin\Config;

use Composer\Composer;
use Psr\Log\LoggerInterface;

final class PluginConfiguration extends AbstractConfiguration implements PluginConfigurationInterface
{
    /** @var bool */
    private $enabled = false;

    /** @var int */
    private $priority = 0;

    /** @var SubstitutionConfigurationInterface[] */
    private $mapping = array();

    public function __construct(array $extra, LoggerInterface $logger = null)
    {
        // Change the default value to true if Composer >= 2.2
        $this->enabled = version_compare(Composer::VERSION, '2.2', '>=');
        self::setLogger($logger);

        if (!isset($extra['substitution'])) {
            self::$logger->debug('Configuration extra.substitution is missing.');
            $this->enabled = false;
        } elseif (!is_array($extra['substitution'])) {
            self::$logger->warning('Configuration extra.substitution must be an object.');
            $this->enabled = false;
        } else {
            $this->parseConfiguration($extra['substitution']);
        }
    }

    /**
     * @param array $conf
     * @return void
     */
    private function parseConfiguration(array $conf)
    {
        if (isset($conf['enable'])) {
            $this->enabled = self::parseBool('enable', $conf['enable'], $this->enabled);
        }
        if (!$this->enabled) {
            // no need to go further
            return;
        }
        if (!isset($conf['mapping'])) {
            $this->enabled = false;
            self::$logger->notice('Configuration extra.substitution.mapping missing. Plugin disabled.');
            return;
        } elseif (!is_array($conf['mapping'])) {
            $this->enabled = false;
            self::$logger->warning('Configuration extra.substitution.mapping must be an object. Plugin disabled.');
            return;
        } else {
            $this->mapping = $this->parseMapping($conf['mapping']);
        }

        if (count($this->mapping) === 0) {
            $this->enabled = false;
            self::$logger->notice('Configuration extra.substitution.mapping empty. Plugin disabled.');
            return;
        }

        if (isset($conf['priority'])) {
            $this->priority = (int) self::parseInt('priority', $conf['priority'], $this->priority);
        }
    }

    /**
     * @param array $conf
     * @return SubstitutionConfiguration[]
     */
    private function parseMapping(array $conf)
    {
        $mapping = array();

        foreach ($conf as $placeholder => $value) {
            $substitution = SubstitutionConfiguration::parseConfiguration((string)$placeholder, $value, self::$logger);
            if ($substitution !== null) {
                $mapping[] = $substitution;
            }
        }

        return $mapping;
    }

    /**
     * @inheritDoc
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @inheritDoc
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @inheritDoc
     */
    public function getMapping()
    {
        return $this->mapping;
    }
}
