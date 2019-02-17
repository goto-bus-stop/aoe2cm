<?php
require_once 'vendor/autoload.php';

use Klein\Klein;
use Medoo\Medoo;

require_once 'controllers/home.class.php';
require_once 'controllers/draft.class.php';
require_once 'controllers/admin.class.php';
require_once 'controllers/admin-ajax.class.php';
require_once 'lib/constants.class.php';
require_once 'controllers/maintenance.php';

$app = new Klein();
function service() {
    global $app;
    return $app->service();
}

$app->respond(function ($request, $response, $service) {
    $service->startSession();
    $service->session = function ($key, $value = null) {
        if (func_num_args() === 2) {
            if ($value !== null) {
                $_SESSION[$key] = $value;
            } else {
                unset($_SESSION[$key]);
            }
            return;
        }
        return $_SESSION[$key] ?? null;
    };

    // Configure language preference
    if ($request->query('lang')) {
        $service->language = $request->query('lang');
        // save language preference for future page requests
        $response->cookie('lang', $language);
    } else if (!empty($request->cookie('lang'))) {
        $service->language = $request->cookie('lang');
    } else {
        $service->language = "en_US";
    }

    $folder = "locale";
    $domain = "aoe2cm";
    $encoding = "UTF-8";
    setlocale(LC_ALL, $language);
    bindtextdomain($domain, $folder);
    bind_textdomain_codeset($domain, $encoding);
    textdomain($domain);

    $service->db = new Medoo([
        'database_type' => 'mysql',
        'database_name' => getenv('MYSQL_DATABASE'),
        'server' => getenv('MYSQL_HOST') ?: 'localhost',
        'username' => getenv('MYSQL_USER'),
        'password' => getenv('MYSQL_PASSWORD'),
    ]);

    $service->layout(__DIR__.'/views/baseplate.php');
});

$app->respond('GET', '/', ['HomeController', 'display']);
$app->respond('GET', '/draft', ['DraftController', 'display']);
$app->respond('GET', '/draft-state', ['DraftController', 'get_state']);
$app->respond('POST', '/draft-ready', ['DraftController', 'draft_ready']);
$app->respond('POST', '/draft-start', ['DraftController', 'draft_start']);
$app->respond('POST', '/draft-choose', ['DraftController', 'post_choice']);
$app->respond('POST', '/set-name', ['DraftController', 'set_name']);
$app->respond('GET', '/demo', ['DraftController', 'demo']);
$app->respond('GET', '/spectate', ['DraftController', 'spectate']);
$app->respond('GET', '/create', ['DraftController', 'create']);
$app->respond('GET', '/join', ['DraftController', 'join']);
$app->respond('GET', '/_join', ['DraftController', '_join']);
$app->respond('GET', '/test', ['DraftController', 'test']);

//admin part
$app->respond('GET', '/login', ['AdminController', 'login']);
$app->respond('POST', '/login', ['AdminController', 'processLogin']);
$app->respond('GET', '/logout', ['AdminController', 'processLogout']);
$app->respond('GET', '/admin', ['AdminController', 'display']);
$app->respond('GET', '/maintenance', 'maintenance');
$app->respond('GET', '/preset-new', ['AdminController', 'newPreset']);
$app->respond('GET', '/preset-enable', ['AdminController', 'enablePreset']);
$app->respond('GET', '/preset-delete', ['AdminController', 'deletePreset']);
$app->respond('GET', '/preset-edit', ['AdminController', 'editPreset']);
$app->respond('GET', '/preset-history', ['AdminController', 'presetHistory']);
$app->respond('POST', '/tournament-new', ['AdminController', 'newTournament']);
$app->respond('GET', '/tournament-delete', ['AdminController', 'deleteTournament']);
$app->respond('GET', '/export-finished', 'export_finished');

//admin ajax part
$app->respond('POST', '/admin-ajax/set-preset-type', ['AdminAjaxController', 'preset_set_type']);
$app->respond('POST', '/admin-ajax/add-turn', ['AdminAjaxController', 'add_turn']);
$app->respond('POST', '/admin-ajax/delete-turn', ['AdminAjaxController', 'del_turn']);
$app->respond('POST', '/admin-ajax/change-turn', ['AdminAjaxController', 'change_turn']);
$app->respond('POST', '/admin-ajax/add-pre-turn', ['AdminAjaxController', 'add_pre_turn']);
$app->respond('POST', '/admin-ajax/delete-pre-turn', ['AdminAjaxController', 'del_pre_turn']);
$app->respond('POST', '/admin-ajax/change-pre-turn', ['AdminAjaxController', 'change_pre_turn']);
$app->respond('POST', '/admin-ajax/set-preset-name', ['AdminAjaxController', 'set_preset_name']);
$app->respond('POST', '/admin-ajax/set-preset-aoe-version', ['AdminAjaxController', 'preset_set_aoe_version']);
$app->respond('POST', '/admin-ajax/set-preset-description', ['AdminAjaxController', 'set_preset_description']);

//custom stuff
$app->respond('GET', '/mastersofarabia2', ['HomeController', 'moara2']);
$app->respond('GET', '/cmmonthly', ['HomeController', 'cmmonthly']);
$app->respond('GET', '/aoak-showcase', ['HomeController', 'aoak_showcase']);
$app->respond('GET', '/casuals-to-war', ['HomeController', 'casuals_to_war']);
$app->respond('GET', '/into-the-darkness', ['HomeController', 'into_the_darkness']);
$app->respond('GET', '/thelegacycup', ['HomeController', 'the_legacy_cup']);
$app->respond('GET', '/escapemasters', ['HomeController', 'escape_masters']);
$app->respond('GET', '/escapemasters2', ['HomeController', 'escape_masters2']);
$app->respond('GET', '/returnofthekings', ['HomeController', 'return_of_the_kings']);
$app->respond('GET', '/allstars', ['HomeController', 'allstars']);

$app->dispatch();
