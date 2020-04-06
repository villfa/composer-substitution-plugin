<?php

namespace SubstitutionPlugin\Provider;

use SubstitutionPlugin\BaseUnitTestCase;

class EnvProviderTest extends BaseUnitTestCase
{
    public function testGetValue()
    {
        putenv('FOO=BAR');
        $provider = new EnvProvider('FOO');
        self::assertEquals('BAR', $provider->getValue());
    }
}
