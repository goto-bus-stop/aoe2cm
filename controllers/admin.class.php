<?php
declare(strict_types=1);
namespace Aoe2CM;

use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;

class AdminController
{
    public static function display(Request $request, Response $response, ServiceProvider $service)
    {
        if (!($service->session)(Constants::LOGGED_IN)) {
            return $response->redirect(ROOTDIR.'/login');
        }

        $service->render(__DIR__.'/../views/admin_home.php', [
            'title' => 'Admin',
            'tournaments' => Tournament::findAll(),
            'presets' => Preset::findAll(),
        ]);
    }

    public static function newPreset(Request $request, Response $response, ServiceProvider $service)
    {
        if (!($service->session)(Constants::LOGGED_IN)) {
            $response->redirect(ROOTDIR.'/login');
            return;
        }

        if ($request->param('name') == null) {
            $response->redirect(ROOTDIR.'/admin');
            return;
        }

        $preset = new Preset();
        $preset->name = $request->param('name');
        $preset->save();

        $response->redirect(ROOTDIR.'/preset-edit?p='.$preset->id);
    }

    public static function editPreset(Request $request, Response $response, ServiceProvider $service)
    {
        if (!($service->session)(Constants::LOGGED_IN)) {
            $response->redirect(ROOTDIR.'/login');
            return;
        }

        //load preset
        if (!is_numeric($request->param('p'))) {
            return $response->code(400);
        }

        $preset = new Preset($request->param('p'));
        if (!$preset->exists()) {
            return $response->code(404);
        }

        $service->render(__DIR__.'/../views/admin_edit.php', [
            'title' => 'Admin - edit preset',
            'preset' => $preset,
            'home' => false,
        ]);
    }

    public static function presetHistory(Request $request, Response $response, ServiceProvider $service)
    {
        if (!($service->session)(Constants::LOGGED_IN)) {
            return $response->redirect(ROOTDIR.'/login');
        }

        //load preset
        if (!is_numeric($request->param('p'))) {
            return $response->code(400);
        }

        $preset = new Preset($request->param('p'));
        if (!$preset->exists()) {
            return $response->code(404);
        }

        $service->render(__DIR__.'/../preset_history.php', [
            'title' => 'Preset - History',
            'preset' => $preset,
            'home' => false,
            'last_drafts' => Draft::getLastWithPreset($preset, 150),
        ]);
    }

    public static function enablePreset(Request $request, Response $response, ServiceProvider $service)
    {
        if (!($service->session)(Constants::LOGGED_IN)) {
            return $response->redirect(ROOTDIR.'/login');
        }

        if ($request->param('e') == null) {
            return $response->code(400);
        }

        if ($request->param('p') == null) {
            return $response->code(400);
        }

        $preset = new Preset($request->param('p'));
        if (!$preset->exists()) {
            return $response->code(404);
        }

        $preset->setState($request->param('e'));

        $response->redirect(ROOTDIR.'/admin', null, false, true);
    }

    public static function deletePreset(Request $request, Response $response, ServiceProvider $service)
    {
        if (!($service->session)(Constants::LOGGED_IN)) {
            $response->redirect(ROOTDIR.'/login');
            return;
        }

        if ($request->param('p') == null) {
            return $response->code(400);
        }

        $preset = new Preset($request->param('p'));
        if (!$preset->exists()) {
            return $response->code(404);
        }
        $preset->delete();

        $response->redirect(ROOTDIR.'/admin', null, false, true);
    }

    public static function newTournament(Request $request, Response $response, ServiceProvider $service)
    {
        if (!($service->session)(Constants::LOGGED_IN)) {
            return $response->redirect(ROOTDIR.'/login');
        }

        if ($request->param('name') == null || $request->param('description') == null) {
            return $response->redirect(ROOTDIR.'/admin');
        }

        $tournament = new Tournament();
        $tournament->name = $request->param('name');
        $tournament->description = $request->param('description');
        $tournament->save();

        $response->redirect(ROOTDIR.'/admin', null, false, true);
    }

    public static function deleteTournament(Request $request, Response $response, ServiceProvider $service)
    {
        if (!($service->session)(Constants::LOGGED_IN)) {
            return $response->redirect(ROOTDIR.'/login');
        }

        if ($request->param('id') == null) {
            return $response->code(400);
        }

        $tournament = new Tournament($request->param('id'));
        if (!$tournament->exists()) {
            return $response->code(404);
        }
        $tournament->delete();

        $response->redirect(ROOTDIR.'/admin', null, false, true);
    }


    public static function login(Request $request, Response $response, ServiceProvider $service)
    {
        $service->render(__DIR__.'/../views/admin_login.php', [
            'title' => 'Admin login',
            'message' => $request->param('msg', ''),
        ]);
    }

    public static function processLogin(Request $request, Response $response, ServiceProvider $service)
    {
        // Confirm the password is correct
        $user = $request->param('user');
        $password = $request->param('pass');

        $ADMINS = [
            'user' => md5('passwordhash'),
        ];

        if (!array_key_exists($user, $ADMINS) || strcmp(md5($password), $ADMINS[$user]) != 0) {
            return $response->redirect(ROOTDIR.'/login?msg='.urlencode('User or password is incorrect'));
        }

        // * Assume it's all good for the time being * //
        // Redirect to the logged in home page
        ($service->session)(Constants::LOGGED_IN, true);
        $response->redirect(ROOTDIR.'/admin');
    }

    public static function processLogout(Request $request, Response $response, ServiceProvider $service)
    {
        // Redirect to the logged in home page
        ($service->session)(Constants::LOGGED_IN, false);
        $response->redirect(ROOTDIR.'/');
    }
}
