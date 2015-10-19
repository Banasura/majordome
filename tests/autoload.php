<?php

// TODO Improve this preload script
class html
{
    public static function escapeHTML($str)
    {
        return htmlspecialchars($str, ENT_COMPAT, 'UTF-8');
    }
}

require_once '../inc/fields/class.majordome.formField.php';

foreach (glob('../inc/fields/*.php') as $module ) {
    require_once $module;
}

// Emulate the gettext support
function __($str)
{
    return $str;
}
