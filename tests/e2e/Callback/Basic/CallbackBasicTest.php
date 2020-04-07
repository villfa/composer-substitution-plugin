<?php

namespace SubstitutionPlugin\Callback\Basic;

use SubstitutionPlugin\BaseEndToEndTestCase;

class CallbackBasicTest extends BaseEndToEndTestCase
{
    public static function doSetUpBeforeClass()
    {
        parent::doSetUpBeforeClass();
        self::install(__DIR__);
    }

    public function testNativeFunction()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'test-native-func');

        self::assertEquals(0, $exitCode);
        self::assertEquals(PHP_VERSION, array_pop($output));
    }

    public function testUserDefinedFunction()
    {
        self::markTestSkipped('Files are not yet autoloaded when the plugin runs');
        list($output, $exitCode) = self::runComposer(__DIR__, 'test-ud-func');

        self::assertEquals(0, $exitCode);
        self::assertEquals('bar', array_pop($output));
    }

    public static function doTearDownAfterClass()
    {
        parent::doTearDownAfterClass();
        self::safeCleanDir(__DIR__);
    }
}
