<?php
define("ROOTDIR", "");

include_once './epiphany/Epi.php';

Epi::setSetting('exceptions', true);
Epi::setPath('base', './epiphany');
Epi::setPath('view', './views');
Epi::init('route','template','session', 'database');
EpiDatabase::employ('mysql','aoecm','db','aoecm','pass4aoe'); // type = mysql, database = mysql, host = localhost, user = root, password = [empty]
EpiSession::employ(array(EpiSession::PHP));

if( ! ini_get('date.timezone') )
{
    date_default_timezone_set('GMT');
}

include_once 'index-common.php';

putenv("LANG=" . $language); 

?>