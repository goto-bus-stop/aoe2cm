<?php
define("ROOTDIR", "");

include 'vendor/autoload.php';

if( ! ini_get('date.timezone') )
{
    date_default_timezone_set('GMT');
}

require_once 'index-common.php';

putenv("LANG=" . $language);
