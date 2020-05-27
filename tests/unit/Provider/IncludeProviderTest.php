<?php

namespace SubstitutionPlugin\Provider;

use SubstitutionPlugin\BaseUnitTestCase;

class IncludeProviderTest extends BaseUnitTestCase
{
    /**
     * @return void
     */
    public function testWithInvalidPath()
    {
        $path = __DIR__ . '/invalid/path.php';
        $provider = new IncludeProvider($path);
        $this->setExpectedException(
            '\\InvalidArgumentException',
            'Cannot include file ' . $path
        );
        $provider->getValue();
    }

    /**
     * @return void
     */
    public function testWithValidPath()
    {
        $path = self::getFixturesDir() . '/return-foo.php';
        $provider = new IncludeProvider($path);
        self::assertEquals('foo', $provider->getValue());
        self::assertTrue($provider->mustAutoload());
    }

    /**
     * @return void
     */
    public function testScope()
    {
        $path = self::getFixturesDir() . '/is-in-class.php';
        $provider = new IncludeProvider($path);
        self::assertEquals('false', $provider->getValue());
    }
}
