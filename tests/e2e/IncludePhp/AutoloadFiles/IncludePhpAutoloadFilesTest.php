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
        self::markTestSkipped(
            'Files are not yet autoloaded when the plugin runs. '
            . 'See: https://github.com/villfa/composer-substitution-plugin/issues/1'
        );
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
