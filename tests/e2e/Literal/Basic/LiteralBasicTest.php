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

    public function testIndirectScript()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'indirect-test');

        self::assertEquals(0, $exitCode);
        self::assertEquals('foo', array_pop($output));
    }

    public function testMaxVerbosity()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, '-vvv test');

        self::assertEquals(0, $exitCode);
        self::assertEquals('foo', array_pop($output));
    }

    public function testComposedSubstitution()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'composed');

        self::assertEquals(0, $exitCode);
        self::assertEquals('_success_', array_pop($output));
    }

    public function testMultiSubstitutions01()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'test_multi_01');

        self::assertEquals(0, $exitCode);
        self::assertEquals('_MULTI_01', array_pop($output));
    }

    public function testMultiSubstitutions02()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'test_multi_02');

        self::assertEquals(0, $exitCode);
        self::assertEquals('_MULTI_02', array_pop($output));
    }

    public function testListScript()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'test_list');

        self::assertEquals(0, $exitCode);
        self::assertEquals('LIST', array_pop($output));
    }

    public function testEscapeCallback()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'test_escape');

        $output = array_pop($output);
        if (self::isWindows()) {
            $output = trim($output, '"');
        }

        self::assertEquals(0, $exitCode);
        self::assertEquals('test #not a comment', $output);
    }

    public function testRecursion()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'test_recursion');

        self::assertEquals(0, $exitCode);
        self::assertEquals('foo', array_pop($output));
    }

    public function testCommandAbbreviation()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'test_abbr');

        self::assertEquals(0, $exitCode);
        self::assertEquals('ABBREVIATION', array_pop($output));
    }

    public static function doTearDownAfterClass()
    {
        parent::doTearDownAfterClass();
        self::safeCleanDir(__DIR__);
    }
}
