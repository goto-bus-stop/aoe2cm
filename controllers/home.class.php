<?php
namespace Aoe2CM;

use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;

class HomeController
{
    public static function display(Request $request, Response $response, ServiceProvider $service)
    {
        // disable caching due to unique code generation
        $response->noCache();

        $service->render(__DIR__.'/../views/home.php', [
            'title' => _('AoE2 Captains mode'),
            'home' => 'home',
            'presets' => Preset::findAllEnabled(),
            'last_drafts' => Draft::getLast(10),
        ]);
    }

    public static function moara2(Request $request, Response $response, ServiceProvider $service)
    {
        $response->noCache();

        $preset = Preset::findWithName("Masters of Arabia2");
        $service->render(__DIR__.'/../views/tournaments/moara2.php', [
            'title' => 'Masters of Arabia 2 '._('Captains mode'),
            'goUp' => true,
            'preset' => $preset,
            'last_drafts' => Draft::getLastWithPreset($preset, 10),
        ]);
    }

    public static function cmmonthly(Request $request, Response $response, ServiceProvider $service)
    {
        $response->noCache();

        $preset = Preset::findWithName("CM Monthly DM");
        $preset_hidden = Preset::findWithName("CM Monthly DM Hidden");
        $service->render(__DIR__.'/../views/tournaments/cmmonthly.php', [
            'title' => 'CM Monthly DM edition',
            'goUp' => true,
            'preset_hidden' => $preset_hidden,
            'preset' => $preset,
            'last_drafts' => array_merge(
                Draft::getLastWithPreset($preset, 5),
                Draft::getLastWithPreset($preset_hidden, 5)
            ),
        ]);
    }

    public static function eventAoakShowcase(Request $request, Response $response, ServiceProvider $service)
    {
        $response->noCache();

        $preset_g1 = Preset::findWithName("AoAK Showcase G1-2 (Hidden)");
        $preset_g3 = Preset::findWithName("AoAK Showcase G3-5 (Hidden)");
        $service->render(__DIR__.'/../views/tournaments/eventAoakShowcase.php', [
            'title' => 'AoAK 3v3 showcase',
            'goUp' => true,
            'preset_g1' => $preset_g1,
            'preset_g3' => $preset_g3,
            'last_drafts' => array_merge(
                Draft::getLastWithPreset($preset_g1, 5),
                Draft::getLastWithPreset($preset_g3, 5)
            ),
        ]);
    }

    public static function eventCasualsToWar(Request $request, Response $response, ServiceProvider $service)
    {
        $response->noCache();

        $preset = Preset::findWithName("Casuals to War");
        $service->render(__DIR__.'/../views/tournaments/casuals.php', [
            'title' => 'Casual to War',
            'goUp' => true,
            'preset' => $preset,
            'last_drafts' => Draft::getLastWithPreset($preset, 10),
        ]);
    }

    public static function eventIntoTheDarkness(Request $request, Response $response, ServiceProvider $service)
    {
        $response->noCache();

        $preset_g1 = Preset::findWithName("Into the Darkness 3v3 G1");
        $preset_g2 = Preset::findWithName("Into the Darkness 3v3 G2-5");
        $service->render(__DIR__.'/../views/tournaments/itd.php', [
            'title' => 'Into the Darkness',
            'goUp' => true,
            'preset_g1' => $preset_g1,
            'preset_g2' => $preset_g2,
            'last_drafts' => array_merge(
                Draft::getLastWithPreset($preset_g1, 5),
                Draft::getLastWithPreset($preset_g2, 5)
            ),
            'theme' => 'material-dark',
        ]);
    }

    public static function eventTheLegacyCup(Request $request, Response $response, ServiceProvider $service)
    {
        $service->render(__DIR__.'/../views/tournaments/eventTheLegacyCup.php', [
            'title' => 'The Legacy Cup',
            'goUp' => true,
            'preset' => Preset::findWithName("Hidden 2v2"),
        ]);
    }

    public static function eventEscapeMasters(Request $request, Response $response, ServiceProvider $service)
    {
        $preset = Preset::findWithName("Escape Gaming Masters");
        $service->render(__DIR__.'/../views/tournaments/eventEscapeMasters.php', [
            'title' => 'Escape Gaming Masters',
            'goUp' => true,
            'preset' => $preset,
            'last_drafts' => Draft::getLastWithPreset($preset, 10),
        ]);
    }

    public static function eventEscapeMasters2(Request $request, Response $response, ServiceProvider $service)
    {
        $service->render(__DIR__.'/../views/tournaments/eventEscapeMasters2.php', [
            'title' => 'Escape Gaming Masters 2',
            'goUp' => true,
            'preset' => Preset::findWithName("Escape Gaming Masters 2"),
        ]);
    }

    public static function eventReturnOfTheKings(Request $request, Response $response, ServiceProvider $service)
    {
        $service->render(__DIR__.'/../views/tournaments/returnofthekings.php', [
            'title' => 'Return of the Kings',
            'goUp' => true,
            'preset' => Preset::findWithName("Return of the Kings G1-2"),
            'preset2' => Preset::findWithName("Return of the Kings G3"),
            'theme' => 'material-dark',
        ]);
    }

    public static function allstars(Request $request, Response $response, ServiceProvider $service)
    {
        $service->render(__DIR__.'/../views/tournaments/allstars.php', [
            'title' => 'All-Stars',
            'goUp' => true,
            'preset' => Preset::findWithName("AoE All-Stars g2-3"),
            'theme' => 'material-dark',
        ]);
    }
}
