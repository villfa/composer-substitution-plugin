<?php

namespace SubstitutionPlugin;

use PHPUnit\Runner\Version;

if (class_exists('\\PHPUnit\\Runner\\Version') && version_compare(Version::series(), '8.0') >= 0) {
    require_once __DIR__ . '/ModernBaseTestCase.php';
    class_alias('SubstitutionPlugin\\ModernBaseTestCase', 'SubstitutionPlugin\\TestCase');
} else {
    require_once __DIR__ . '/LegacyBaseTestCase.php';
    class_alias('SubstitutionPlugin\\LegacyBaseTestCase', 'SubstitutionPlugin\\TestCase');
}

class BaseTestCase extends TestCase
{
    protected static function isWindows()
    {
        return substr(PHP_OS, 0, 3) == 'WIN';
    }

    protected static function getProjectDir()
    {
        return realpath(__DIR__ . '/../');
    }

    protected static function getVendorBinDir()
    {
        return self::getProjectDir() . '/vendor/bin';
    }

    protected static function getFixturesDir()
    {
        return __DIR__ . '/Fixtures';
    }

    /**
     * @param string $exception Exception class name
     * @param null|string $message
     * @param int|string $code
     */
    public function setExpectedException($exception, $message = null, $code = null)
    {
        if (method_exists(get_parent_class(__CLASS__), 'setExpectedException')) {
            parent::setExpectedException($exception, $message, $code);
        } else {
            $this->expectException($exception);
            if ($message !== null) {
                $this->expectExceptionMessage($message);
            }
            if ($code !== null) {
                $this->expectExceptionCode($code);
            }
        }
    }
}
