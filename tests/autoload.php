<?php

class html
{
    public static function escapeHTML($str)
    {
        return htmlspecialchars($str, ENT_COMPAT, 'UTF-8');
    }
}

function testAutoloader($className) {
    require_once '../inc/fields/class.majordome.' . $className . '.php';
}

spl_autoload_register('testAutoloader');

// Emulate the gettext support
function __($str)
{
    return $str;
}
