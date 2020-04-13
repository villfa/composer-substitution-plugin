<?php

namespace SubstitutionPlugin\Env\Basic;

use SubstitutionPlugin\BaseEndToEndTestCase;

class EnvBasicTest extends BaseEndToEndTestCase
{
    public static function doSetUpBeforeClass()
    {
        parent::doSetUpBeforeClass();
        self::install(__DIR__);
    }

    public function testEnvVariable()
    {
        if (self::isWindows()) {
            $envVars = 'SET FOO=foo &&';
        } else {
            $envVars = 'FOO=foo';
        }

        list($output, $exitCode) = self::runComposer(__DIR__, 'test', $envVars);

        self::assertEquals(0, $exitCode);
        self::assertEquals('foo', array_pop($output));
    }

    public static function doTearDownAfterClass()
    {
        parent::doTearDownAfterClass();
        self::safeCleanDir(__DIR__);
    }
}
