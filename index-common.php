<?php
namespace Aoe2CM;

require_once 'vendor/autoload.php';
require_once __DIR__.'/models/draft.class.php';
require_once __DIR__.'/models/player.class.php';
require_once __DIR__.'/models/preset.class.php';
require_once __DIR__.'/models/tournament.class.php';
require_once __DIR__.'/models/turn.class.php';
require_once __DIR__.'/controllers/admin-ajax.class.php';
require_once __DIR__.'/controllers/admin.class.php';
require_once __DIR__.'/controllers/draft.class.php';
require_once __DIR__.'/controllers/home.class.php';
require_once __DIR__.'/controllers/logout.class.php';
require_once __DIR__.'/controllers/maintenance.php';
require_once __DIR__.'/lib/TurnsGrid.class.php';
require_once __DIR__.'/lib/civgrid.class.php';
require_once __DIR__.'/lib/constants.class.php';
require_once __DIR__.'/lib/name_generator.php';
require_once __DIR__.'/lib/uniqid.php';

use Klein\Klein;
use Medoo\Medoo;

$app = new Klein();
function service()
{
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
    } elseif (!empty($request->cookie('lang'))) {
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

    $service->theme = $request->cookies()->theme ?? 'material';

    $service->db = new Medoo([
        'database_type' => 'mysql',
        'database_name' => getenv('MYSQL_DATABASE'),
        'server' => getenv('MYSQL_HOST') ?: 'localhost',
        'username' => getenv('MYSQL_USER'),
        'password' => getenv('MYSQL_PASSWORD'),
    ]);

    $service->layout(__DIR__.'/views/baseplate.php');
});

$app->respond('GET', '/', [HomeController::class, 'display']);
$app->respond('GET', '/draft', [DraftController::class, 'display']);
$app->respond('GET', '/draft-state', [DraftController::class, 'getState']);
$app->respond('POST', '/draft-ready', [DraftController::class, 'draftReady']);
$app->respond('POST', '/draft-start', [DraftController::class, 'draftStart']);
$app->respond('POST', '/draft-choose', [DraftController::class, 'postChoice']);
$app->respond('POST', '/set-name', [DraftController::class, 'setName']);
$app->respond('GET', '/demo', [DraftController::class, 'demo']);
$app->respond('GET', '/spectate', [DraftController::class, 'spectate']);
$app->respond('GET', '/create', [DraftController::class, 'create']);
$app->respond('GET', '/join', [DraftController::class, 'join']);
$app->respond('GET', '/_join', [DraftController::class, 'doJoin']);
$app->respond('GET', '/test', [DraftController::class, 'test']);

//admin part
$app->respond('GET', '/login', [AdminController::class, 'login']);
$app->respond('POST', '/login', [AdminController::class, 'processLogin']);
$app->respond('GET', '/logout', [AdminController::class, 'processLogout']);
$app->respond('GET', '/admin', [AdminController::class, 'display']);
$app->respond('GET', '/maintenance', 'Aoe2CM\maintenance');
$app->respond('GET', '/preset-new', [AdminController::class, 'newPreset']);
$app->respond('GET', '/preset-enable', [AdminController::class, 'enablePreset']);
$app->respond('GET', '/preset-delete', [AdminController::class, 'deletePreset']);
$app->respond('GET', '/preset-edit', [AdminController::class, 'editPreset']);
$app->respond('GET', '/preset-history', [AdminController::class, 'presetHistory']);
$app->respond('POST', '/tournament-new', [AdminController::class, 'newTournament']);
$app->respond('GET', '/tournament-delete', [AdminController::class, 'deleteTournament']);
$app->respond('GET', '/export-finished', 'Aoe2CM\export_finished');

//admin ajax part
$app->respond('POST', '/admin-ajax/set-preset-type', [AdminAjaxController::class, 'setPresetType']);
$app->respond('POST', '/admin-ajax/add-turn', [AdminAjaxController::class, 'addTurn']);
$app->respond('POST', '/admin-ajax/delete-turn', [AdminAjaxController::class, 'deleteTurn']);
$app->respond('POST', '/admin-ajax/change-turn', [AdminAjaxController::class, 'changeTurn']);
$app->respond('POST', '/admin-ajax/add-pre-turn', [AdminAjaxController::class, 'addPreTurn']);
$app->respond('POST', '/admin-ajax/delete-pre-turn', [AdminAjaxController::class, 'delPreTurn']);
$app->respond('POST', '/admin-ajax/change-pre-turn', [AdminAjaxController::class, 'changePreTurn']);
$app->respond('POST', '/admin-ajax/set-preset-name', [AdminAjaxController::class, 'setPresetName']);
$app->respond('POST', '/admin-ajax/set-preset-aoe-version', [AdminAjaxController::class, 'setPresetAoeVersion']);
$app->respond('POST', '/admin-ajax/set-preset-description', [AdminAjaxController::class, 'setPresetDescription']);

//custom stuff
$app->respond('GET', '/mastersofarabia2', [HomeController::class, 'moara2']);
$app->respond('GET', '/cmmonthly', [HomeController::class, 'cmmonthly']);
$app->respond('GET', '/aoak-showcase', [HomeController::class, 'eventAoakShowcase']);
$app->respond('GET', '/casuals-to-war', [HomeController::class, 'eventCasualsToWar']);
$app->respond('GET', '/into-the-darkness', [HomeController::class, 'eventIntoTheDarkness']);
$app->respond('GET', '/thelegacycup', [HomeController::class, 'eventTheLegacyCup']);
$app->respond('GET', '/escapemasters', [HomeController::class, 'eventEscapeMasters']);
$app->respond('GET', '/escapemasters2', [HomeController::class, 'eventEscapeMasters2']);
$app->respond('GET', '/returnofthekings', [HomeController::class, 'eventReturnOfTheKings']);
$app->respond('GET', '/allstars', [HomeController::class, 'allstars']);

$app->dispatch();
