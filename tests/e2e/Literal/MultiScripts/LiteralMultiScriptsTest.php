<?php

namespace SubstitutionPlugin\Literal\MultiScripts;

use SubstitutionPlugin\BaseEndToEndTestCase;

class LiteralMultiScriptsTest extends BaseEndToEndTestCase
{
    public static function doSetUpBeforeClass()
    {
        parent::doSetUpBeforeClass();
        self::install(__DIR__);
    }

    public function testScriptFoo01()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'test01');

        self::assertEquals(0, $exitCode);
        self::assertEquals('_foo_01', array_pop($output));
    }

    public function testScriptFoo02()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'test02');

        self::assertEquals(0, $exitCode);
        self::assertEquals('_foo_02', array_pop($output));
    }

    public static function doTearDownAfterClass()
    {
        parent::doTearDownAfterClass();
        self::safeCleanDir(__DIR__);
    }
}
