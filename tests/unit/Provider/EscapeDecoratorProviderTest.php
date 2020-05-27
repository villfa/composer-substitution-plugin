<?php

namespace SubstitutionPlugin\Provider;

use SubstitutionPlugin\BaseUnitTestCase;

class EscapeDecoratorProviderTest extends BaseUnitTestCase
{
    /**
     * @return void
     */
    public static function doSetUpBeforeClass()
    {
        parent::doSetUpBeforeClass();
        require_once self::getFixturesDir() . '/DummyProvider.php';
    }

    /**
     * @return void
     */
    public function testGetValue()
    {
        $innerProvider = new \DummyProvider('   trimmed   ');
        $provider = new EscapeDecoratorProvider('trim', $innerProvider);
        self::assertEquals('trimmed', $provider->getValue());
    }

    /**
     * @return void
     */
    public function testInvalidCallback()
    {
        $callback = 'not_a_valid_callback';
        $innerProvider = new \DummyProvider('foo');
        $provider = new EscapeDecoratorProvider($callback, $innerProvider);
        $this->setExpectedException('\\InvalidArgumentException', "The escape callback is not callable: $callback");
        $provider->getValue();
    }

    /**
     * @dataProvider provideMustAutoload
     * @param string $callback
     * @param bool $expectedResult
     * @return void
     */
    public function testMustAutoload($callback, $expectedResult)
    {
        $innerProvider = new \DummyProvider('foo');
        $provider = new EscapeDecoratorProvider($callback, $innerProvider);
        self::assertEquals($expectedResult, $provider->mustAutoload());
    }

    /**
     * @return array<array>
     */
    public function provideMustAutoload()
    {
        return array(
            array('trim', false),
            array('my_own_escape_function', true),
        );
    }
}
