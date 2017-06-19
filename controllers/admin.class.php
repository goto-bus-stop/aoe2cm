<?php

include_once 'models/tournament.class.php';
include_once 'models/preset.class.php';

class AdminController
{
    public static function display()
    {
        if (!getSession()->get(Constants::LOGGED_IN)) {
            getRoute()->redirect(ROOTDIR.'/login');

            return;
        }
        $params = array();
        $params['body'] = 'admin_home.php';
        $params['title'] = 'Admin';
        $params['tournaments'] = Tournament::find_all();
        $params['presets'] = Preset::find_all();

        getTemplate()->display('baseplate.php', $params);
    }

    public static function newPreset()
    {
        if (!getSession()->get(Constants::LOGGED_IN)) {
            getRoute()->redirect(ROOTDIR.'/login');

            return;
        }

        if (!isset($_REQUEST['name'])) {
            getRoute()->redirect(ROOTDIR.'/admin');

            return;
        }

        $preset = new Preset();
        $preset->name = $_REQUEST['name'];
        $preset->save();

        getRoute()->redirect(ROOTDIR.'/preset-edit?p='.$preset->id);
    }

    public static function editPreset()
    {
        if (!getSession()->get(Constants::LOGGED_IN)) {
            getRoute()->redirect(ROOTDIR.'/login');

            return;
        }

        $params = array();
        $params['body'] = 'admin_edit.php';
        $params['title'] = 'Admin - edit preset';

        //load preset
        if(!isset($_REQUEST['p']) || !is_numeric($_REQUEST['p'])) {
            header('HTTP/1.0 404 Not Found');
            return;
        }

        $preset = new Preset($_REQUEST['p']);
        if(!$preset->exists()) {
            header('HTTP/1.0 404 Not Found');
            return;
        }

        $params['preset'] = $preset;
        $params['home'] = false;

        getTemplate()->display('baseplate.php', $params);
    }

    public static function presetHistory()
    {
         if (!getSession()->get(Constants::LOGGED_IN)) {
            getRoute()->redirect(ROOTDIR.'/login');

            return;
        }
        
        $params = array();
        $params['body'] = 'preset_history.php';
        $params['title'] = 'Preset - History';

        //load preset
        if(!isset($_REQUEST['p']) || !is_numeric($_REQUEST['p'])) {
            header('HTTP/1.0 404 Not Found');
            return;
        }

        $preset = new Preset($_REQUEST['p']);
        if(!$preset->exists()) {
            header('HTTP/1.0 404 Not Found');
            return;
        }
        
        $params['preset'] = $preset;
        $params['home'] = false;
        
        $params['last_drafts'] = Draft::get_last_with_preset($preset, 150);

        
        getTemplate()->display('baseplate.php', $params);
    }

    public static function enablePreset()
    {
        if (!getSession()->get(Constants::LOGGED_IN)) {
            getRoute()->redirect(ROOTDIR.'/login');

            return;
        }

        if (isset($_REQUEST['e']) && isset($_REQUEST['p'])) {
            $preset = new Preset($_REQUEST['p']);
            $preset->set_state($_REQUEST['e']);
        }

        getRoute()->redirect(ROOTDIR.'/admin', null, false, true);
    }

    public static function deletePreset()
    {
        if (!getSession()->get(Constants::LOGGED_IN)) {
            getRoute()->redirect(ROOTDIR.'/login');

            return;
        }

        if (isset($_REQUEST['p'])) {
           $preset = new Preset($_REQUEST['p']);
           $preset->delete();
        }

        getRoute()->redirect(ROOTDIR.'/admin', null, false, true);
    }

    public static function newTournament()
    {
        if (!getSession()->get(Constants::LOGGED_IN)) {
            getRoute()->redirect(ROOTDIR.'/login');

            return;
        }

        if (empty($_REQUEST['name']) || empty($_REQUEST['description'])) {
            getRoute()->redirect(ROOTDIR.'/admin');

            return;
        }

        $tournament = new Tournament();
        $tournament->name = $_REQUEST['name'];
        $tournament->description = $_REQUEST['description'];
        $tournament->save();

        getRoute()->redirect(ROOTDIR.'/admin', null, false, true);
    }

    public static function deleteTournament()
    {
        if (!getSession()->get(Constants::LOGGED_IN)) {
            getRoute()->redirect(ROOTDIR.'/login');

            return;
        }

        if (isset($_REQUEST['id'])) {
             $tournament = new Tournament($_REQUEST['id']);
             $tournament->delete();
        }

        getRoute()->redirect(ROOTDIR.'/admin', null, false, true);
    }


    public static function login()
    {
        $params = array();
        $params['body'] = 'admin_login.php';
        $params['title'] = 'Admin login';
        if (isset($_REQUEST['msg'])) {
            $params['message'] = $_REQUEST['msg'];
        } else {
            $params['message'] = '';
        }

        getTemplate()->display('baseplate.php', $params);
    }

    const ADMINS = array(
        'user' => 'passwordhash'
    );

    public static function processLogin()
    {
        // Confirm the password is correct
        $user = $_REQUEST['user'];
        $password = $_REQUEST['pass'];

        if (!array_key_exists($user, self::ADMINS) || strcmp(md5($password), self::ADMINS[$user]) != 0) {
            getRoute()->redirect(ROOTDIR.'/login?msg=User%20or%20password%20is%20incorrect');

            return;
        }

        // * Assume it's all good for the time being * //
        // Redirect to the logged in home page
        getSession()->set(Constants::LOGGED_IN, true);
        getRoute()->redirect(ROOTDIR.'/admin');
    }

    public static function processLogout()
    {
        // Redirect to the logged in home page
        getSession()->set(Constants::LOGGED_IN, false);

        getRoute()->redirect(ROOTDIR.'/');
    }
}
