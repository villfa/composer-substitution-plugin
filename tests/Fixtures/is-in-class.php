<?php

$dbt = debug_backtrace();
$scope = array_shift($dbt);

if (
    is_array($scope)
    && isset($scope['function'])
    && in_array($scope['function'], array('include', 'include_once', 'require', 'require_once'))
) {
    $scope = array_shift($dbt);
}

$hasClass = is_array($scope) && isset($scope['class']) && strlen($scope['class']) > 0;

return $hasClass ? 'true' : 'false';
