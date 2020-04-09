<?php

namespace SubstitutionPlugin\Callback\UnusedSubstitution;

use SubstitutionPlugin\BaseEndToEndTestCase;

class CallbackUnusedSubstitutionTest extends BaseEndToEndTestCase
{
    public static function doSetUpBeforeClass()
    {
        parent::doSetUpBeforeClass();
        self::install(__DIR__);
    }

    protected function doSetUp()
    {
        parent::doSetUp();
        self::cleanCountFile();
    }

    private static function cleanCountFile()
    {
        if (file_exists(CountCallback::getFilePath())) {
            unlink(CountCallback::getFilePath());
        }
    }

    public function testUnusedSubstitutionNotCalled()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'test');

        self::assertEquals(0, $exitCode);
        self::assertEquals('count: 1', array_pop($output));
        self::assertEquals(1, CountCallback::getCount());
    }

    public function testSubstitutionOnRedirection()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'redirect-test');

        self::assertEquals(0, $exitCode);
        self::assertEquals('count: 1', array_pop($output));
        self::assertEquals(1, CountCallback::getCount());
    }

    public static function doTearDownAfterClass()
    {
        parent::doTearDownAfterClass();
        self::cleanCountFile();
        self::safeCleanDir(__DIR__);
    }
}
