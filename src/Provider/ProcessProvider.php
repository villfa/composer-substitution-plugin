<?php

namespace SubstitutionPlugin\Provider;

use Composer\Util\ProcessExecutor;

final class ProcessProvider implements ProviderInterface
{
    /** @var string */
    private $command;

    /**
     * @param string $command
     */
    public function __construct($command)
    {
        $this->command = $command;
    }

    /**
     * @inheritDoc
     */
    public function getValue()
    {
        $processExecutor = new ProcessExecutor();
        $output = '';
        $exitCode = $processExecutor->execute($this->command, $output);
        $output = is_array($output) ? implode(PHP_EOL, $output) : (string) $output;
        if ($exitCode > 0) {
            $message = sprintf('Error executing command "%s"', $this->command);
            if (!empty($output)) {
                $message .= ': ' . $output;
            }
            throw new \RuntimeException($message, $exitCode);
        }

        return is_array($output) ? implode(PHP_EOL, $output) : (string) $output;
    }
}
