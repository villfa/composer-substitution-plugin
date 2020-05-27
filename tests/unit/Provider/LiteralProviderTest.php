<?php

namespace SubstitutionPlugin\Provider;

use SubstitutionPlugin\BaseUnitTestCase;

class LiteralProviderTest extends BaseUnitTestCase
{
    /**
     * @return void
     */
    public function testGetValue()
    {
        $value = 'foo bar';
        $provider = new LiteralProvider($value);
        self::assertEquals($value, $provider->getValue());
    }
}
