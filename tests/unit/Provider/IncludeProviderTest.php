<?php

namespace SubstitutionPlugin\Provider;

use SubstitutionPlugin\BaseUnitTestCase;

class IncludeProviderTest extends BaseUnitTestCase
{
    public function testWithInvalidPath()
    {
        $path = __DIR__ . '/invalid/path.php';
        $this->setExpectedException(
            '\\InvalidArgumentException',
            'Cannot include file ' . $path
        );
        new IncludeProvider($path);
    }

    public function testWithValidPath()
    {
        $path = self::getFixturesDir() . '/return-foo.php';
        $provider = new IncludeProvider($path);
        self::assertEquals('foo', $provider->getValue());
    }
}
