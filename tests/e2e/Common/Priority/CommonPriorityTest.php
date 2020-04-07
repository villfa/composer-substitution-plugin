<?php

namespace SubstitutionPlugin\Common\Priority;

use SubstitutionPlugin\BaseEndToEndTestCase;

class CommonPriorityTest extends BaseEndToEndTestCase
{
    public static function doSetUpBeforeClass()
    {
        parent::doSetUpBeforeClass();
        self::install(__DIR__);
    }

    public function testMaxVerbosity()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, '-vvv test');
        $output = implode(PHP_EOL, $output);

        self::assertEquals(0, $exitCode);
        self::assertTrue(strpos($output, 'Priority set to 42') !== false);
    }

    public static function doTearDownAfterClass()
    {
        parent::doTearDownAfterClass();
        self::safeCleanDir(__DIR__);
    }
}
