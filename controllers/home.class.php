<?php

require_once 'models/preset.class.php';
require_once 'models/draft.class.php';

class HomeController
{
    public static function display($request, $response, $service)
    {
        // disable caching due to unique code generation
        $response->noCache();

        $service->render(__DIR__.'/../views/home.php', [
            'title' => _('AoE2 Captains mode'),
            'home' => 'home',
            'presets' => Preset::find_all_enabled(),
            'last_drafts' => Draft::get_last(10),
        ]);
    }

    public static function moara2($request, $response, $service)
    {
        $response->noCache();

        $preset = Preset::find_with_name("Masters of Arabia2");
        $service->render(__DIR__.'/../views/tournaments/moara2.php', [
            'title' => 'Masters of Arabia 2 '._('Captains mode'),
            'goUp' => true,
            'preset' => $preset,
            'last_drafts' => Draft::get_last_with_preset($preset, 10),
        ]);
    }

    public static function cmmonthly($request, $response, $service)
    {
        $response->noCache();

        $preset = Preset::find_with_name("CM Monthly DM");
        $preset_hidden = Preset::find_with_name("CM Monthly DM Hidden");
        $service->render(__DIR__.'/../views/tournaments/cmmonthly.php', [
            'title' => 'CM Monthly DM edition',
            'goUp' => true,
            'preset_hidden' => $preset_hidden,
            'preset' => $preset,
            'last_drafts' => array_merge(
                Draft::get_last_with_preset($preset, 5),
                Draft::get_last_with_preset($preset_hidden, 5)
            ),
        ]);
    }

    public static function aoak_showcase($request, $response, $service)
    {
        $response->noCache();

        $preset_g1 = Preset::find_with_name("AoAK Showcase G1-2 (Hidden)");
        $preset_g3 = Preset::find_with_name("AoAK Showcase G3-5 (Hidden)");
        $service->render(__DIR__.'/../views/tournaments/aoak_showcase.php', [
            'title' => 'AoAK 3v3 showcase',
            'goUp' => true,
            'preset_g1' => $preset_g1,
            'preset_g3' => $preset_g3,
            'last_drafts' => array_merge(
                Draft::get_last_with_preset($preset_g1, 5),
                Draft::get_last_with_preset($preset_g3, 5)
            ),
        ]);
    }

    public static function casuals_to_war($request, $response, $service)
    {
        $response->noCache();

        $preset = Preset::find_with_name("Casuals to War");
        $service->render(__DIR__.'/../views/tournaments/casuals.php', [
            'title' => 'Casual to War',
            'goUp' => true,
            'preset' => $preset,
            'last_drafts' => Draft::get_last_with_preset($preset, 10),
        ]);
    }

    public static function into_the_darkness($request, $response, $service)
    {
        $response->noCache();

        $preset_g1 = Preset::find_with_name("Into the Darkness 3v3 G1");
        $preset_g2 = Preset::find_with_name("Into the Darkness 3v3 G2-5");
        $service->render(__DIR__.'/../views/tournaments/itd.php', [
            'title' => 'Into the Darkness',
            'goUp' => true,
            'preset_g1' => $preset_g1,
            'preset_g2' => $preset_g2,
            'last_drafts' => array_merge(
                Draft::get_last_with_preset($preset_g1, 5),
                Draft::get_last_with_preset($preset_g2, 5)
            ),
            'theme' => 'material-dark',
        ]);
    }

    public static function the_legacy_cup($request, $response, $service)
    {
        $service->render(__DIR__.'/../views/tournaments/the_legacy_cup.php', [
            'title' => 'The Legacy Cup',
            'goUp' => true,
            'preset' => Preset::find_with_name("Hidden 2v2"),
        ]);
    }

    public static function escape_masters($request, $response, $service)
    {
        $preset = Preset::find_with_name("Escape Gaming Masters");
        $service->render(__DIR__.'/../views/tournaments/escape_masters.php', [
            'title' => 'Escape Gaming Masters',
            'goUp' => true,
            'preset' => $preset,
            'last_drafts' => Draft::get_last_with_preset($preset, 10),
        ]);
    }

    public static function escape_masters2($request, $response, $service)
    {
        $service->render(__DIR__.'/../views/tournaments/escape_masters2.php', [
            'title' => 'Escape Gaming Masters 2',
            'goUp' => true,
            'preset' => Preset::find_with_name("Escape Gaming Masters 2"),
        ]);
    }

    public static function return_of_the_kings($request, $response, $service)
    {
        $service->render(__DIR__.'/../views/tournaments/returnofthekings.php', [
            'title' => 'Return of the Kings',
            'goUp' => true,
            'preset' => Preset::find_with_name("Return of the Kings G1-2"),
            'preset2' => Preset::find_with_name("Return of the Kings G3"),
            'theme' => 'material-dark',
        ]);
    }

    public static function allstars($request, $response, $service)
    {
        $service->render(__DIR__.'/../views/tournaments/allstars.php', [
            'title' => 'All-Stars',
            'goUp' => true,
            'preset' => Preset::find_with_name("AoE All-Stars g2-3"),
            'theme' => 'material-dark',
        ]);
    }
}
