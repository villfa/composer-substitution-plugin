<?php

namespace SubstitutionPlugin\Compatibility\ScriptsDev;

use SubstitutionPlugin\BaseEndToEndTestCase;

class CompatibilityScriptsDevTest extends BaseEndToEndTestCase
{
    public static function doSetUpBeforeClass()
    {
        parent::doSetUpBeforeClass();
        self::install(__DIR__, true);
    }

    public function testPluginCompatibility()
    {
        self::markTestSkipped('See: https://github.com/neronmoon/scriptsdev/pull/17');
        list($output, $exitCode) = self::runComposer(__DIR__, 'test');

        self::assertEquals(0, $exitCode);
        self::assertEquals('foo', array_pop($output));
    }

    public static function doTearDownAfterClass()
    {
        parent::doTearDownAfterClass();
        self::safeCleanDir(__DIR__);
    }
}
