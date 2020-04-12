<?php

namespace SubstitutionPlugin;

if (!function_exists('SubstitutionPlugin\\isInternalCallback')) {
    /**
     * @param string $callback
     * @return bool
     */
    function isInternalCallback($callback)
    {
        if (!is_callable($callback)) {
            return false;
        }

        try {
            if (strpos($callback, '::', 1) !== false) {
                $r = new \ReflectionMethod($callback);
            } else {
                $r = new \ReflectionFunction($callback);
            }

            return $r->isInternal();
        } catch (\ReflectionException $e) {
            return false;
        }
    }
}
