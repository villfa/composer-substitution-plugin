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

    public function testPostUpdateCmd()
    {
        $this->markTestSkipped('Substitutions don\'t apply on Composer`s commands');
        list($output, $exitCode) = self::runComposer(__DIR__, '-vvv update --no-dev');

        echo PHP_EOL, implode(PHP_EOL, $output), PHP_EOL;

        self::assertEquals(0, $exitCode);
        self::assertEquals('POST UPDATE SUBSTITUTION', array_pop($output));
    }

    public static function doTearDownAfterClass()
    {
        parent::doTearDownAfterClass();
        self::safeCleanDir(__DIR__);
    }
}
