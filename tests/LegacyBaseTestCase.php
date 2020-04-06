<?php

namespace SubstitutionPlugin;

use PHPUnit\Framework\TestCase;

class LegacyBaseTestCase extends TestCase
{
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

    protected function assertPostConditions()
    {
        static::doAssertPostConditions();
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

    protected function doAssertPostConditions()
    {
    }
}
