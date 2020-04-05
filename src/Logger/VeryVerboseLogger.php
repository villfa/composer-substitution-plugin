<?php

namespace SubstitutionPlugin\Logger;

use Composer\IO\IOInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

class VeryVerboseLogger implements LoggerInterface
{
    const LOG_PREFIX = '[SubstitutionPlugin] ';

    /** @var IOInterface */
    protected $io;

    public function __construct(IOInterface $io)
    {
        $this->io = $io;
    }

    /**
     * @inheritDoc
     */
    public function log($level, $message, array $context = array())
    {
        $log = self::LOG_PREFIX . $this->interpolate($message, $context);

        switch ($level) {
            case LogLevel::DEBUG:
                $this->io->write($log);
                break;
            case LogLevel::INFO:
                $this->io->write('<info>' . $log . '</info>');
                break;
            case LogLevel::NOTICE:
            case LogLevel::WARNING:
                $this->io->write('<warning>' . $log . '</warning>');
                break;
            default:
                $this->io->writeError('<error>' . $log . '</error>');
                break;
        }
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @author PHP Framework Interoperability Group
     *
     * @param string $message
     * @param array $context
     * @return string
     */
    private function interpolate($message, array $context)
    {
        if (false === strpos($message, '{')) {
            return $message;
        }

        $replacements = array();
        foreach ($context as $key => $val) {
            if (is_callable($val)) {
                $val = call_user_func($val);
            }
            if (null === $val || is_scalar($val) || (\is_object($val) && method_exists($val, '__toString'))) {
                $replacements["{{$key}}"] = $val;
            } elseif ($val instanceof \DateTimeInterface) {
                $replacements["{{$key}}"] = $val->format(\DateTime::RFC3339);
            } elseif (\is_object($val)) {
                $replacements["{{$key}}"] = '[object '.\get_class($val).']';
            } else {
                $replacements["{{$key}}"] = '['.\gettype($val).']';
            }
        }

        return strtr($message, $replacements);
    }
    /**
     * System is unusable.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function emergency($message, array $context = array())
    {
        $this->log(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function alert($message, array $context = array())
    {
        $this->log(LogLevel::ALERT, $message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function critical($message, array $context = array())
    {
        $this->log(LogLevel::CRITICAL, $message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function error($message, array $context = array())
    {
        $this->log(LogLevel::ERROR, $message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function warning($message, array $context = array())
    {
        $this->log(LogLevel::WARNING, $message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function notice($message, array $context = array())
    {
        $this->log(LogLevel::NOTICE, $message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function info($message, array $context = array())
    {
        $this->log(LogLevel::INFO, $message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param string $message
     * @param array  $context
     *
     * @return void
     */
    public function debug($message, array $context = array())
    {
        $this->log(LogLevel::DEBUG, $message, $context);
    }
}
