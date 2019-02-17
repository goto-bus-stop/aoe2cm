<?php
define("ROOTDIR", __DIR__);

if (!ini_get('date.timezone')) {
    date_default_timezone_set('GMT');
}

require_once 'index-common.php';

putenv("LANG=" . $language);
