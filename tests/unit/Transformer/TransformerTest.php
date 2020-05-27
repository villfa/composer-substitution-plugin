<?php

namespace SubstitutionPlugin\Transformer;

use SubstitutionPlugin\BaseUnitTestCase;
use SubstitutionPlugin\Provider\LiteralProvider;

class TransformerTest extends BaseUnitTestCase
{
    /**
     * @return void
     */
    public function testWhenNoMatch()
    {
        $transformer = new Transformer('{placeholder}', new LiteralProvider('foo'));
        self::assertEquals('bar', $transformer->transform('bar'));
    }

    /**
     * @return void
     */
    public function testWithMatch()
    {
        $transformer = new Transformer('{placeholder}', new LiteralProvider('foo'));
        self::assertEquals('foo bar', $transformer->transform('{placeholder} bar'));
    }

    /**
     * @return void
     */
    public function testWithSeveralMatches()
    {
        $transformer = new Transformer('{placeholder}', new LiteralProvider('foo'));
        self::assertEquals('foo bar foo', $transformer->transform('{placeholder} bar {placeholder}'));
    }

    /**
     * @return void
     */
    public function testEmptyPlaceholder()
    {
        $transformer = new Transformer('', new LiteralProvider('foo'));
        self::assertEquals('bar', $transformer->transform('bar'));
    }
}
