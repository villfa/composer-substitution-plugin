<?php

namespace SubstitutionPlugin\Provider;

use SubstitutionPlugin\BaseUnitTestCase;

class ProviderProxyEscapeTest extends BaseUnitTestCase
{
    public static function doSetUpBeforeClass()
    {
        parent::doSetUpBeforeClass();
        require_once self::getFixturesDir() . '/DummyProvider.php';
    }

    public function testGetValue()
    {
        $innerProvider = new \DummyProvider('   trimmed   ');
        $provider = new ProviderProxyEscape('trim', $innerProvider);
        self::assertEquals('trimmed', $provider->getValue());
    }

    public function testInvalidCallback()
    {
        $callback = 'not_a_valid_callback';
        $innerProvider = new \DummyProvider('foo');
        $provider = new ProviderProxyEscape($callback, $innerProvider);
        $this->setExpectedException('\\InvalidArgumentException', "The escape callback is not callable: $callback");
        $provider->getValue();
    }
}
