<?php

include_once 'controllers/home.class.php';
include_once 'controllers/draft.class.php';
include_once 'controllers/admin.class.php';
include_once 'controllers/admin-ajax.class.php';
include_once 'lib/constants.class.php';
include_once 'controllers/maintenance.php';

//autostart session
getSession();

// get language preference
if (isset($_GET["lang"])) {
    $language = $_GET["lang"];
}
else if (isset($_SESSION["lang"])) {
    $language  = $_SESSION["lang"];
}
else {
    $language = "en_US";
}

// save language preference for future page requests
$_SESSION["lang"]  = $language;

$folder = "locale";
$domain = "aoe2cm";
$encoding = "UTF-8";

setlocale(LC_ALL, $language);

bindtextdomain($domain, $folder); 
bind_textdomain_codeset($domain, $encoding);

textdomain($domain);

getRoute()->get('/', array('HomeController', 'display'));
getRoute()->get('/draft', array('DraftController', 'display'));
getRoute()->get('/draft-state', array('DraftController', 'get_state'));
getRoute()->post('/draft-ready', array('DraftController', 'draft_ready'));
getRoute()->post('/draft-start', array('DraftController', 'draft_start'));
getRoute()->post('/draft-choose', array('DraftController', 'post_choice'));
getRoute()->post('/set-name', array('DraftController', 'set_name'));
getRoute()->get('/demo', array('DraftController', 'demo'));
getRoute()->get('/spectate', array('DraftController', 'spectate'));
getRoute()->get('/create', array('DraftController', 'create'));
getRoute()->get('/join', array('DraftController', 'join'));
getRoute()->get('/_join', array('DraftController', '_join'));
getRoute()->get('/test', array('DraftController', 'test'));

//admin part
getRoute()->get('/login', array('AdminController', 'login'));
getRoute()->post('/login', array('AdminController', 'processLogin'));
getRoute()->get('/logout', array('AdminController', 'processLogout'));
getRoute()->get('/admin', array('AdminController', 'display'));
getRoute()->get('/maintenance', 'maintenance');
getRoute()->get('/preset-new', array('AdminController', 'newPreset'));
getRoute()->get('/preset-enable', array('AdminController', 'enablePreset'));
getRoute()->get('/preset-delete', array('AdminController', 'deletePreset'));
getRoute()->get('/preset-edit', array('AdminController', 'editPreset'));
getRoute()->get('/preset-history', array('AdminController', 'presetHistory'));
getRoute()->post('/tournament-new', array('AdminController', 'newTournament'));
getRoute()->get('/tournament-delete', array('AdminController', 'deleteTournament'));
getRoute()->get('/export-finished', 'export_finished');

//admin ajax part
getRoute()->post('/admin-ajax/set-preset-type', array('AdminAjaxController', 'preset_set_type'));
getRoute()->post('/admin-ajax/add-turn', array('AdminAjaxController', 'add_turn'));
getRoute()->post('/admin-ajax/delete-turn', array('AdminAjaxController', 'del_turn'));
getRoute()->post('/admin-ajax/change-turn', array('AdminAjaxController', 'change_turn'));
getRoute()->post('/admin-ajax/add-pre-turn', array('AdminAjaxController', 'add_pre_turn'));
getRoute()->post('/admin-ajax/delete-pre-turn', array('AdminAjaxController', 'del_pre_turn'));
getRoute()->post('/admin-ajax/change-pre-turn', array('AdminAjaxController', 'change_pre_turn'));
getRoute()->post('/admin-ajax/set-preset-name', array('AdminAjaxController', 'set_preset_name'));
getRoute()->post('/admin-ajax/set-preset-aoe-version', array('AdminAjaxController', 'preset_set_aoe_version'));
getRoute()->post('/admin-ajax/set-preset-description', array('AdminAjaxController', 'set_preset_description'));

//custom stuff
getRoute()->get('/mastersofarabia2', array('HomeController', 'moara2'));
getRoute()->get('/cmmonthly', array('HomeController', 'cmmonthly'));
getRoute()->get('/aoak-showcase', array('HomeController', 'aoak_showcase'));
getRoute()->get('/casuals-to-war', array('HomeController', 'casuals_to_war'));
getRoute()->get('/into-the-darkness', array('HomeController', 'into_the_darkness'));
getRoute()->get('/thelegacycup', array('HomeController', 'the_legacy_cup'));
getRoute()->get('/escapemasters', array('HomeController', 'escape_masters'));
getRoute()->get('/escapemasters2', array('HomeController', 'escape_masters2'));
getRoute()->get('/returnofthekings', array('HomeController', 'return_of_the_kings'));
getRoute()->get('/allstars', array('HomeController', 'allstars'));

getRoute()->run();

?>