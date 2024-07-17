<?php

namespace App\Helpers;

if (! function_exists('caseMatchTable')) {
    function caseMatchTable($tableName) {
        return env('DB_IS_CAPS') == 1 ? $tableName : strtolower($tableName);
    }
}