<?php

namespace SubstitutionPlugin\Provider;

use SubstitutionPlugin\BaseUnitTestCase;

class CallbackProviderTest extends BaseUnitTestCase
{
    public function testWithNativeFunction()
    {
        $provider = new CallbackProvider('phpversion');
        self::assertEquals(PHP_VERSION, $provider->getValue());
    }

    public function testWithUserDefinedFunction()
    {
        require_once self::getFixturesDir() . '/function-foo.php';
        $provider = new CallbackProvider('foo');
        self::assertEquals('foo', $provider->getValue());
    }

    public function testWithAutoloading()
    {
        $path = self::getFixturesDir() . '/DummyCallback.php';
        spl_autoload_register(function ($class) use ($path) {
            if ($class === 'DummyCallback') {
                require_once $path;
            }
        });

        $provider = new CallbackProvider('DummyCallback::foo');
        self::assertEquals('foo', $provider->getValue());
    }

    public function testInvalidCallback()
    {
        $callback = 'not_a_valid_callback';
        $provider = new CallbackProvider($callback);
        $this->setExpectedException('\\InvalidArgumentException', "Value is not callable: $callback");
        $provider->getValue();
    }

    /**
     * @dataProvider provideMustAutoload
     * @param string $callback
     * @param bool $expectedResult
     */
    public function testMustAutoload($callback, $expectedResult)
    {
        $provider = new CallbackProvider($callback);
        self::assertEquals($expectedResult, $provider->mustAutoload());
    }

    public function provideMustAutoload()
    {
        return array(
            array('phpversion', false),
            array('PHPVERSION', false),
            array('\\SubstitutionPlugin\\isInternalFunction', true),
        );
    }
}
