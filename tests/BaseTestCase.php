<?php

namespace SubstitutionPlugin;

use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected static function getFixturesDir()
    {
        return __DIR__ . '/Fixtures';
    }

    /**
     * @param string $exception Exception class name
     * @param string $message
     */
    public function setExpectedException($exception, $message = '')
    {
        if (method_exists(get_parent_class(), 'setExpectedException')) {
            parent::setExpectedException($exception, $message);
        } else {
            $this->expectException($exception);
            $this->expectExceptionMessage($message);
        }
    }
}
