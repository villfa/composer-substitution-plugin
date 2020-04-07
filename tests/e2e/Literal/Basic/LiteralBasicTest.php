<?php

namespace SubstitutionPlugin\Literal\Basic;

use SubstitutionPlugin\BaseEndToEndTestCase;

class LiteralBasicTest extends BaseEndToEndTestCase
{
    public static function doSetUpBeforeClass()
    {
        parent::doSetUpBeforeClass();
        self::install(__DIR__);
    }

    public function testCallRunScript()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'run-script test');

        self::assertEquals(0, $exitCode);
        self::assertEquals('foo', array_pop($output));
    }

    public function testCallRun()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'run test');

        self::assertEquals(0, $exitCode);
        self::assertEquals('foo', array_pop($output));
    }

    public function testCallScriptName()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'test');

        self::assertEquals(0, $exitCode);
        self::assertEquals('foo', array_pop($output));
    }

    public function testMaxVerbosity()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, '-vvv test');

        self::assertEquals(0, $exitCode);
        self::assertEquals('foo', array_pop($output));
    }

    public static function doTearDownAfterClass()
    {
        parent::doTearDownAfterClass();
        self::safeCleanDir(__DIR__);
    }
}
