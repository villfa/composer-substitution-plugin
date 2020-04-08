<?php

namespace SubstitutionPlugin\Callback\UnusedSubstitution;

class CountCallback
{
    /**
     * @return string
     */
    public static function getFilePath()
    {
        return __DIR__ . '/count.txt';
    }

    /**
     * @return int
     */
    public static function getCount()
    {
        $file = self::getFilePath();

        if (file_exists($file)) {
            return (int) file_get_contents($file);
        }

        return 0;
    }

    /**
     * @return int
     */
    public static function inc()
    {
        $count = self::getCount();
        $count++;
        self::save($count);

        return $count;
    }

    /**
     * @param int $count
     */
    private static function save($count)
    {
        $file = self::getFilePath();

        if (file_put_contents($file, $count) === false) {
            throw new \RuntimeException("Cannot write $count in file $file");
        }
    }
}
