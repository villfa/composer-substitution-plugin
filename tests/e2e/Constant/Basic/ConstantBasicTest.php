<?php

namespace SubstitutionPlugin\Constant\Basic;

use SubstitutionPlugin\BaseEndToEndTestCase;

class ConstantBasicTest extends BaseEndToEndTestCase
{
    public static function doSetUpBeforeClass()
    {
        parent::doSetUpBeforeClass();
        self::install(__DIR__);
    }

    public function testClassConstant()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'class-const');

        self::assertEquals(0, $exitCode);
        self::assertEquals(\Composer\Composer::VERSION, array_pop($output));
    }

    public function testAutoload()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'autoload-const');

        self::assertEquals(0, $exitCode);
        self::assertEquals('foo', array_pop($output));
    }

    public static function doTearDownAfterClass()
    {
        parent::doTearDownAfterClass();
        self::safeCleanDir(__DIR__);
    }
}
