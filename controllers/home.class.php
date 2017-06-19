<?php

include_once 'models/preset.class.php';
include_once 'models/draft.class.php';

class HomeController
{
    public static function display()
    {
        $params = array();
        $params['body'] = 'home.php';
        $params['title'] = _('AoE2 Captains mode');
        $params['home'] = 'home';
        $params['presets'] = Preset::find_all_enabled();
        $params['last_drafts'] = Draft::get_last(10);

        //disable caching due to unique code generation
        header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        getTemplate()->display('baseplate.php', $params);
    }

    public static function moara2() {
        $params = array();
        $params['body'] = 'tournaments/moara2.php';
        $params['title'] = 'Masters of Arabia 2 '+_('Captains mode');
        $params['goUp'] = true;
        $params['preset'] = Preset::find_with_name("Masters of Arabia2");
        $params['last_drafts'] = Draft::get_last_with_preset($params['preset'], 10);

        //disable caching due to unique code generation
        header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        getTemplate()->display('baseplate.php', $params);
    }

    public static function cmmonthly() {
        $params = array();
        $params['body'] = 'tournaments/cmmonthly.php';
        $params['title'] = 'CM Monthly DM edition';
        $params['goUp'] = true;
        $params['preset_hidden'] = Preset::find_with_name("CM Monthly DM Hidden");
        $params['preset'] = Preset::find_with_name("CM Monthly DM");
        $params['last_drafts'] = array_merge(Draft::get_last_with_preset($params['preset'], 5), Draft::get_last_with_preset($params['preset_hidden'], 5)) ;


        //disable caching due to unique code generation
        header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        getTemplate()->display('baseplate.php', $params);
    }

    public static function aoak_showcase() {
        $params = array();
        $params['body'] = 'tournaments/aoak_showcase.php';
        $params['title'] = 'AoAK 3v3 showcase';
        $params['goUp'] = true;
        $params['preset_g1'] = Preset::find_with_name("AoAK Showcase G1-2 (Hidden)");
        $params['preset_g3'] = Preset::find_with_name("AoAK Showcase G3-5 (Hidden)");
        $params['last_drafts'] = array_merge(Draft::get_last_with_preset($params['preset_g1'], 5), Draft::get_last_with_preset($params['preset_g3'], 5)) ;

        //disable caching due to unique code generation
        header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        getTemplate()->display('baseplate.php', $params);
    }

    public static function casuals_to_war() {
        $params = array();
        $params['body'] = 'tournaments/casuals.php';
        $params['title'] = 'Casual to War';
        $params['goUp'] = true;
        $params['preset'] = Preset::find_with_name("Casuals to War");
        $params['last_drafts'] = Draft::get_last_with_preset($params['preset'], 10);

        //disable caching due to unique code generation
        header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        getTemplate()->display('baseplate.php', $params);
    }

    public static function into_the_darkness() {
        $params = array();
        $params['body'] = 'tournaments/itd.php';
        $params['title'] = 'Into the Darkness';
        $params['goUp'] = true;
        $params['preset_g1'] = Preset::find_with_name("Into the Darkness 3v3 G1");
        $params['preset_g2'] = Preset::find_with_name("Into the Darkness 3v3 G2-5");
        $params['last_drafts'] = array_merge(Draft::get_last_with_preset($params['preset_g1'], 5), Draft::get_last_with_preset($params['preset_g2'], 5)) ;
        $params['theme'] = 'material-dark';

        //disable caching due to unique code generation
        header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past

        getTemplate()->display('baseplate.php', $params);
    }

    public static function the_legacy_cup() {
        $params = array();
        $params['body'] = 'tournaments/the_legacy_cup.php';
        $params['title'] = 'The Legacy Cup';
        $params['goUp'] = true;
        $params['preset'] = Preset::find_with_name("Hidden 2v2");
        //$params['last_drafts'] = Draft::get_last_with_preset($params['preset'], 10);

        getTemplate()->display('baseplate.php', $params);
    }

    public static function escape_masters() {
        $params = array();
        $params['body'] = 'tournaments/escape_masters.php';
        $params['title'] = 'Escape Gaming Masters';
        $params['goUp'] = true;
        $params['preset'] = Preset::find_with_name("Escape Gaming Masters");
        $params['last_drafts'] = Draft::get_last_with_preset($params['preset'], 10);

        getTemplate()->display('baseplate.php', $params);
    }

    public static function escape_masters2() {
        $params = array();
        $params['body'] = 'tournaments/escape_masters2.php';
        $params['title'] = 'Escape Gaming Masters 2';
        $params['goUp'] = true;
        $params['preset'] = Preset::find_with_name("Escape Gaming Masters 2");
        //$params['last_drafts'] = Draft::get_last_with_preset($params['preset'], 10);

        getTemplate()->display('baseplate.php', $params);
    }

    public static function return_of_the_kings() {
        $params = array();
        $params['body'] = 'tournaments/returnofthekings.php';
        $params['title'] = 'Return of the Kings';
        $params['goUp'] = true;
        $params['preset'] = Preset::find_with_name("Return of the Kings G1-2");
        $params['preset2'] = Preset::find_with_name("Return of the Kings G3");
        $params['theme'] = 'material-dark';

        getTemplate()->display('baseplate.php', $params);
    }
    
    public static function allstars() {
        $params = array();
        $params['body'] = 'tournaments/allstars.php';
        $params['title'] = 'All-Stars';
        $params['goUp'] = true;
        $params['preset'] = Preset::find_with_name("AoE All-Stars g2-3");
        $params['theme'] = 'material-dark';

        getTemplate()->display('baseplate.php', $params);
    }
}
