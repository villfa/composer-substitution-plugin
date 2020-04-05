<?php

namespace SubstitutionPlugin\Logger;

use Psr\Log\LogLevel;

class DefaultLogger extends VeryVerboseLogger
{
    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = array())
    {
        if ($level === LogLevel::DEBUG || $level === LogLevel::INFO || $level === LogLevel::NOTICE) {
            return;
        }

        parent::log($level, $message, $context);
    }
}
