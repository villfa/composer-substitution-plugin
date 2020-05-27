<?php

namespace SubstitutionPlugin\Provider;

use SubstitutionPlugin\BaseUnitTestCase;

class ConstantProviderTest extends BaseUnitTestCase
{
    const CLASS_CONSTANT = 'constant value';

    /**
     * @return void
     */
    public function testWithNativeConstant()
    {
        $provider = new ConstantProvider('PHP_VERSION');
        self::assertEquals(PHP_VERSION, $provider->getValue());
    }

    /**
     * @return void
     */
    public function testWithUserDefinedConstant()
    {
        define('MY_CONSTANT', 'any value');
        $provider = new ConstantProvider('MY_CONSTANT');
        self::assertEquals(MY_CONSTANT, $provider->getValue());
    }

    /**
     * @return void
     */
    public function testWithClassConstant()
    {
        $provider = new ConstantProvider(__CLASS__ . '::CLASS_CONSTANT');
        self::assertEquals(self::CLASS_CONSTANT, $provider->getValue());
    }

    /**
     * @return void
     */
    public function testWithAutoloadedConstant()
    {
        $path = self::getFixturesDir() . '/ClassConstant.php';
        spl_autoload_register(function ($class) use ($path) {
            if ($class === 'ClassConstant') {
                require_once $path;
            }
        });

        $provider = new ConstantProvider('ClassConstant::FOO');
        self::assertEquals('foo', $provider->getValue());
    }

    /**
     * @return void
     */
    public function testInvalidCallback()
    {
        $constantName = 'not_a_valid_constant';
        $provider = new ConstantProvider($constantName);
        $this->setExpectedException('\\InvalidArgumentException', "Value is not a constant: $constantName");
        $provider->getValue();
    }

    /**
     * @dataProvider provideMustAutoload
     * @param string $constantName
     * @param bool $expectedResult
     * @return void
     */
    public function testMustAutoload($constantName, $expectedResult)
    {
        $provider = new ConstantProvider($constantName);
        self::assertEquals($expectedResult, $provider->mustAutoload());
    }

    /**
     * @return array<array>
     */
    public function provideMustAutoload()
    {
        return array(
            array('PHP_VERSION', false),
            array('\\My\\Own::CONSTANT', true),
        );
    }
}
