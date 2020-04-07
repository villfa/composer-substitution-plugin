<?php

namespace SubstitutionPlugin\Transformer;

use SubstitutionPlugin\BaseUnitTestCase;
use SubstitutionPlugin\Provider\LiteralProvider;

class TransformerTest extends BaseUnitTestCase
{
    public function testWhenNoMatch()
    {
        $transformer = new Transformer('{placeholder}', new LiteralProvider('foo'));
        self::assertEquals('bar', $transformer->transform('bar'));
    }

    public function testWithMatch()
    {
        $transformer = new Transformer('{placeholder}', new LiteralProvider('foo'));
        self::assertEquals('foo bar', $transformer->transform('{placeholder} bar'));
    }

    public function testWithSeveralMatches()
    {
        $transformer = new Transformer('{placeholder}', new LiteralProvider('foo'));
        self::assertEquals('foo bar foo', $transformer->transform('{placeholder} bar {placeholder}'));
    }
    public function testEmptyPlaceholder()
    {
        $transformer = new Transformer('', new LiteralProvider('foo'));
        self::assertEquals('bar', $transformer->transform('bar'));
    }
}
