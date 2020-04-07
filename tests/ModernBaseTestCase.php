<?php

namespace SubstitutionPlugin;

use PHPUnit\Framework\TestCase;

class ModernBaseTestCase extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        static::doSetUpBeforeClass();
    }

    public static function tearDownAfterClass(): void
    {
        static::doTearDownAfterClass();
    }

    protected function setUp(): void
    {
        static::doSetUp();
    }

    protected function tearDown(): void
    {
        static::doTearDown();
    }

    protected function assertPreConditions(): void
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
