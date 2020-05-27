<?php

namespace SubstitutionPlugin\Provider;

use SubstitutionPlugin\BaseUnitTestCase;

class ProcessProviderTest extends BaseUnitTestCase
{
    /**
     * @return void
     */
    public function testValidCommand()
    {
        $command = 'echo TEST';
        $provider = new ProcessProvider($command);
        self::assertEquals('TEST', rtrim($provider->getValue()));
    }

    /**
     * @return void
     */
    public function testInvalidCommand()
    {
        $command = 'echoechoecho';
        $provider = new ProcessProvider($command);
        self::setExpectedException('\\RuntimeException', "Error executing command \"$command\"");
        $provider->getValue();
    }
}
