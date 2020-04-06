<?php

namespace SubstitutionPlugin\Provider;

use SubstitutionPlugin\BaseUnitTestCase;

class LiteralProviderTest extends BaseUnitTestCase
{
    public function testGetValue()
    {
        $value = 'foo bar';
        $provider = new LiteralProvider($value);
        self::assertEquals($value, $provider->getValue());
    }
}
