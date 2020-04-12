<?php

namespace SubstitutionPlugin\Common\ScriptEvents;

use SubstitutionPlugin\BaseEndToEndTestCase;

class CommonScriptEventsTest extends BaseEndToEndTestCase
{
    public static function doSetUpBeforeClass()
    {
        parent::doSetUpBeforeClass();
        self::install(__DIR__);
    }

    public function testUpdateCmd()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'update --no-dev');

        self::assertEquals(0, $exitCode);
        self::assertEquals('POST UPDATE SUBSTITUTION', array_pop($output));
        self::assertContains('PRE UPDATE SUBSTITUTION', $output);
    }

    public function testStatusCmd()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'status');

        self::assertEquals(0, $exitCode);
        self::assertContains('PRE STATUS SUBSTITUTION', $output);
        /**
         * Doesn't work because of problem with Composer
         * See: https://github.com/composer/composer/issues/8771
         *
         * self::assertEquals('POST STATUS SUBSTITUTION', array_pop($output));
         */
    }

    public static function doTearDownAfterClass()
    {
        parent::doTearDownAfterClass();
        self::safeCleanDir(__DIR__);
    }
}
