<?php
define("ROOTDIR", "");

include_once './epiphany/Epi.php';

Epi::setSetting('exceptions', true);
Epi::setPath('base', './epiphany');
Epi::setPath('view', './views');
Epi::init('route','template','session', 'database');
EpiDatabase::employ('mysql','aoecm','localhost','aoecm','pass4aoe'); // type = mysql, database = mysql, host = localhost, user = root, password = [empty]
EpiSession::employ(array(EpiSession::PHP));

include_once 'index-common.php';

?>