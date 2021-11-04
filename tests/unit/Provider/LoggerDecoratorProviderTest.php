<?php

namespace SubstitutionPlugin\Provider;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SubstitutionPlugin\BaseUnitTestCase;
use SubstitutionPlugin\Config\SubstitutionConfiguration;

class LoggerDecoratorProviderTest extends BaseUnitTestCase
{
    /** @var LoggerInterface */
    private static $logger;

    /** @var SubstitutionConfiguration */
    private static $configuration;

    /**
     * @return void
     */
    public static function doSetUpBeforeClass()
    {
        parent::doSetUpBeforeClass();
        self::$logger = new NullLogger();
        self::$configuration = new SubstitutionConfiguration(
            '{placeholder}',
            ProviderType::LITERAL,
            'foo',
            false
        );
    }

    /**
     * @return void
     */
    public function testWithString()
    {
        $innerProvider = new LiteralProvider('foo');
        $provider = new LoggerDecoratorProvider(
            self::$logger,
            self::$configuration,
            $innerProvider
        );

        self::assertEquals('foo', $provider->getValue());
    }

    /**
     * @dataProvider provideInvalidValues
     * @param mixed $value
     * @return void
     */
    public function testWithInvalidValues($value)
    {
        $innerProvider = new DummyProvider($value);
        $provider = new LoggerDecoratorProvider(
            self::$logger,
            self::$configuration,
            $innerProvider
        );

        self::assertEquals('', $provider->getValue());
    }

    /**
     * @return array<array>
     */
    public function provideInvalidValues()
    {
        return array(
            array(null),
            array(array()),
        );
    }
}
