<?php

namespace SubstitutionPlugin\Utils;

use SubstitutionPlugin\BaseUnitTestCase;

class CommandHelperTest extends BaseUnitTestCase
{
    /**
     * @dataProvider provideValidCommands
     * @param string $command
     */
    public function testTryGetScriptsFromCommandWithValidCommands($command)
    {
        $cmdHelper = new CommandHelper();
        self::assertTrue($cmdHelper->tryGetScriptsFromCommand($command, $scriptNames));
        self::assertGreaterThan(0, count($scriptNames));
    }

    public function provideValidCommands()
    {
        return array(
            array('install'),
            array('update'),
            array('remove'),
            array('dump-autoload'),
            array('status'),
            array('archive'),
        );
    }

    public function testTryGetScriptsFromCommandWithInvalidCommand()
    {
        $cmdHelper = new CommandHelper();
        self::assertFalse($cmdHelper->tryGetScriptsFromCommand('unknown composer command'));
    }
}
