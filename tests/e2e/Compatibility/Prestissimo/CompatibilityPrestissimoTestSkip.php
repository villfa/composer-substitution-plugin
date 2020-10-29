<?php

namespace SubstitutionPlugin\Compatibility\Prestissimo;

use SubstitutionPlugin\BaseEndToEndTestCase;

class CompatibilityPrestissimoTestSkip extends BaseEndToEndTestCase
{
    public static function doSetUpBeforeClass()
    {
        parent::doSetUpBeforeClass();
        self::install(__DIR__);
    }

    public function testPluginCompatibility()
    {
        // install substitution after Prestissimo
        list($output, $exitCode) = self::runComposer(__DIR__, 'require ' . self::PACKAGE . ':*');
        self::assertEquals(0, $exitCode);

        list($output, $exitCode) = self::runComposer(__DIR__, 'test');

        self::assertEquals(0, $exitCode);
        self::assertEquals('foo', array_pop($output));
    }

    public static function doTearDownAfterClass()
    {
        parent::doTearDownAfterClass();
        self::runComposer(__DIR__, 'remove ' . self::PACKAGE);
        self::safeCleanDir(__DIR__);
    }
}
