<?php

namespace SubstitutionPlugin\IncludePhp\AutoloadFiles;

use SubstitutionPlugin\BaseEndToEndTestCase;

class IncludePhpAutoloadFilesTest extends BaseEndToEndTestCase
{
    public static function doSetUpBeforeClass()
    {
        parent::doSetUpBeforeClass();
        self::install(__DIR__);
    }

    /**
     * @see https://github.com/villfa/composer-substitution-plugin/issues/1
     */
    public function testScriptFoo()
    {
        list($output, $exitCode) = self::runComposer(__DIR__, 'test');

        self::assertEquals(0, $exitCode);
        self::assertEquals('baz', array_pop($output));
    }

    public static function doTearDownAfterClass()
    {
        parent::doTearDownAfterClass();
        self::safeCleanDir(__DIR__);
    }
}
