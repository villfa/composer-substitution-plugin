<?php

namespace SubstitutionPlugin\Process\Basic;

use SubstitutionPlugin\BaseEndToEndTestCase;

class ProcessBasicTest extends BaseEndToEndTestCase
{
    public static function doSetUpBeforeClass()
    {
        parent::doSetUpBeforeClass();
        self::install(__DIR__);
    }

    public function testProcess()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'test-process');

        self::assertEquals(0, $exitCode);
        self::assertEquals('FOO', array_pop($output));
    }

    public static function doTearDownAfterClass()
    {
        parent::doTearDownAfterClass();
        self::safeCleanDir(__DIR__);
    }
}
