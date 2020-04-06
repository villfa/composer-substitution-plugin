<?php

namespace SubstitutionPlugin\Provider;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SubstitutionPlugin\BaseUnitTestCase;
use SubstitutionPlugin\Config\SubstitutionConfiguration;

class ProviderProxyLoggerTest extends BaseUnitTestCase
{
    /** @var LoggerInterface */
    private static $logger;

    /** @var SubstitutionConfiguration */
    private static $configuration;

    public static function doSetUpBeforeClass()
    {
        self::$logger = new NullLogger();
        self::$configuration = new SubstitutionConfiguration(
            '{placeholder}',
            ProviderType::LITERAL,
            'foo',
            false
        );
    }

    public function testWithString()
    {
        $innerProvider = new LiteralProvider('foo');
        $provider = new ProviderProxyLogger(
            self::$logger,
            self::$configuration,
            $innerProvider
        );

        self::assertEquals('foo', $provider->getValue());
    }

    /**
     * @dataProvider provideInvalidValues
     * @param mixed $value
     */
    public function testWithInvalidValues($value)
    {
        $innerProvider = new LiteralProvider($value);
        $provider = new ProviderProxyLogger(
            self::$logger,
            self::$configuration,
            $innerProvider
        );

        self::assertEquals('', $provider->getValue());
    }

    public function provideInvalidValues()
    {
        return array(
            array(null),
            array(array()),
        );
    }
}
