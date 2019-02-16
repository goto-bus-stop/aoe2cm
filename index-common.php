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

getRoute()->get('/', ['HomeController', 'display']);
getRoute()->get('/draft', ['DraftController', 'display']);
getRoute()->get('/draft-state', ['DraftController', 'get_state']);
getRoute()->post('/draft-ready', ['DraftController', 'draft_ready']);
getRoute()->post('/draft-start', ['DraftController', 'draft_start']);
getRoute()->post('/draft-choose', ['DraftController', 'post_choice']);
getRoute()->post('/set-name', ['DraftController', 'set_name']);
getRoute()->get('/demo', ['DraftController', 'demo']);
getRoute()->get('/spectate', ['DraftController', 'spectate']);
getRoute()->get('/create', ['DraftController', 'create']);
getRoute()->get('/join', ['DraftController', 'join']);
getRoute()->get('/_join', ['DraftController', '_join']);
getRoute()->get('/test', ['DraftController', 'test']);

//admin part
getRoute()->get('/login', ['AdminController', 'login']);
getRoute()->post('/login', ['AdminController', 'processLogin']);
getRoute()->get('/logout', ['AdminController', 'processLogout']);
getRoute()->get('/admin', ['AdminController', 'display']);
getRoute()->get('/maintenance', 'maintenance');
getRoute()->get('/preset-new', ['AdminController', 'newPreset']);
getRoute()->get('/preset-enable', ['AdminController', 'enablePreset']);
getRoute()->get('/preset-delete', ['AdminController', 'deletePreset']);
getRoute()->get('/preset-edit', ['AdminController', 'editPreset']);
getRoute()->get('/preset-history', ['AdminController', 'presetHistory']);
getRoute()->post('/tournament-new', ['AdminController', 'newTournament']);
getRoute()->get('/tournament-delete', ['AdminController', 'deleteTournament']);
getRoute()->get('/export-finished', 'export_finished');

//admin ajax part
getRoute()->post('/admin-ajax/set-preset-type', ['AdminAjaxController', 'preset_set_type']);
getRoute()->post('/admin-ajax/add-turn', ['AdminAjaxController', 'add_turn']);
getRoute()->post('/admin-ajax/delete-turn', ['AdminAjaxController', 'del_turn']);
getRoute()->post('/admin-ajax/change-turn', ['AdminAjaxController', 'change_turn']);
getRoute()->post('/admin-ajax/add-pre-turn', ['AdminAjaxController', 'add_pre_turn']);
getRoute()->post('/admin-ajax/delete-pre-turn', ['AdminAjaxController', 'del_pre_turn']);
getRoute()->post('/admin-ajax/change-pre-turn', ['AdminAjaxController', 'change_pre_turn']);
getRoute()->post('/admin-ajax/set-preset-name', ['AdminAjaxController', 'set_preset_name']);
getRoute()->post('/admin-ajax/set-preset-aoe-version', ['AdminAjaxController', 'preset_set_aoe_version']);
getRoute()->post('/admin-ajax/set-preset-description', ['AdminAjaxController', 'set_preset_description']);

//custom stuff
getRoute()->get('/mastersofarabia2', ['HomeController', 'moara2']);
getRoute()->get('/cmmonthly', ['HomeController', 'cmmonthly']);
getRoute()->get('/aoak-showcase', ['HomeController', 'aoak_showcase']);
getRoute()->get('/casuals-to-war', ['HomeController', 'casuals_to_war']);
getRoute()->get('/into-the-darkness', ['HomeController', 'into_the_darkness']);
getRoute()->get('/thelegacycup', ['HomeController', 'the_legacy_cup']);
getRoute()->get('/escapemasters', ['HomeController', 'escape_masters']);
getRoute()->get('/escapemasters2', ['HomeController', 'escape_masters2']);
getRoute()->get('/returnofthekings', ['HomeController', 'return_of_the_kings']);
getRoute()->get('/allstars', ['HomeController', 'allstars']);

getRoute()->run();
