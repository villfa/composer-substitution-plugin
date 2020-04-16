<?php

namespace SubstitutionPlugin\Provider;

use SubstitutionPlugin\BaseUnitTestCase;

class ProcessProviderTest extends BaseUnitTestCase
{
    public function testValidCommand()
    {
        $command = 'echo TEST';
        $provider = new ProcessProvider($command);
        self::assertEquals('TEST', rtrim($provider->getValue()));
    }

    public function testInvalidCommand()
    {
        $command = 'echoechoecho';
        $provider = new ProcessProvider($command);
        self::setExpectedException('\\RuntimeException', "Error executing command \"$command\"");
        $provider->getValue();
    }
}
