<?php

namespace SubstitutionPlugin;

use PHPUnit\Framework\TestCase;

/**
 * @method static self assertStringContainsString($needle, $haystack, $message = '')
 */
class LegacyBaseTestCase extends TestCase
{
    public static function __callStatic($name, $arguments)
    {
        if ($name === 'assertStringContainsString') {
            return call_user_func_array('self::assertContains', $arguments);
        }

        throw new \BadMethodCallException(sprintf(
            'Call to undefined method %s::%s()',
            get_called_class(),
            $name
        ));
    }

    public static function setUpBeforeClass()
    {
        static::doSetUpBeforeClass();
    }

    public static function tearDownAfterClass()
    {
        static::doTearDownAfterClass();
    }

    protected function setUp()
    {
        static::doSetUp();
    }

    protected function tearDown()
    {
        static::doTearDown();
    }

    protected function assertPreConditions()
    {
        static::doAssertPreConditions();
    }

    public static function doSetUpBeforeClass()
    {
    }

    public static function doTearDownAfterClass()
    {
    }

    protected function doSetUp()
    {
    }

    protected function doTearDown()
    {
    }

    protected function doAssertPreConditions()
    {
    }
}
