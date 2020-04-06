<?php

namespace SubstitutionPlugin\Literal\Basic;

use SubstitutionPlugin\BaseEndToEndTestCase;

class LiteralBasicTest extends BaseEndToEndTestCase
{
    public function testScriptFoo()
    {
        $this->install(__DIR__);
        $args = 'test';
        list($output, $exitCode) = $this->runComposer(__DIR__, $args);

        self::assertEquals(0, $exitCode);
        self::assertEquals('foo', array_pop($output));
    }
}
