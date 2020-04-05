<?php

namespace SubstitutionPlugin\Logger;

use Composer\IO\IOInterface;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    /**
     * @param IOInterface $io
     * @return LoggerInterface
     */
    public static function getLogger(IOInterface $io)
    {
        switch (true) {
            case $io->isDebug():
            case $io->isVeryVerbose():
                return new VeryVerboseLogger($io);
            case $io->isVerbose():
                return new VerboseLogger($io);
            default:
                return new DefaultLogger($io);
        }
    }
}
