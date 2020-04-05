<?php

namespace SubstitutionPlugin\Logger;

use Psr\Log\LogLevel;

class VerboseLogger extends VeryVerboseLogger
{
    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = array())
    {
        if ($level === LogLevel::DEBUG) {
            return;
        }

        parent::log($level, $message, $context);
    }
}
