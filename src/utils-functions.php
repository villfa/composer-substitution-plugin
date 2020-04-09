<?php

namespace SubstitutionPlugin;

/**
 * @param string $functionName
 * @return bool
 */
function isInternalFunction($functionName)
{
    if (!is_string($functionName) || !function_exists($functionName)) {
        return false;
    }

    $functions = get_defined_functions();

    return in_array(strtolower($functionName), $functions['internal']);
}
