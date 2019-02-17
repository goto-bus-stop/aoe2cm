<?php
namespace Aoe2CM;

use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;

class AdminAjaxController
{
    public static function checkLogin(Request $request, Response $response, ServiceProvider $service)
    {
        if (!($service->session)(Constants::LOGGED_IN)) {
            $response->code(403);
            return false;
        }
        return true;
    }

    public static function setPresetType(Request $request, Response $response, ServiceProvider $service)
    {
        if (!self::checkLogin($request, $response, $service)) {
            return;
        }

        $preset = new Preset($request->param('preset_id'));
        $preset->setType($request->param('type'));
        $turns = $preset->getPresetTurns();

        $turns_grid = new TurnsGrid($preset->getAoeVersion());
        return $turns_grid->editableTimeline($turns);
    }

    public static function setPresetAoeVersion(Request $request, Response $response, ServiceProvider $service)
    {
        if (!self::checkLogin($request, $response, $service)) {
            return;
        }

        $preset = new Preset($request->param('request_id'));
        $preset->setAoeVersion($request->param('version'));

        $turns_grid = new TurnsGrid($preset->getAoeVersion());
        return $turns_grid->editablePreTurns($preset->getPresetPreTurns());
    }

    public static function addTurn(Request $request, Response $response, ServiceProvider $service)
    {
        if (!self::checkLogin($request, $response, $service)) {
            return;
        }

        $index = $request->param('index');
        $preset_id = $request->param('preset_id');

        $preset = new Preset($preset_id);

        $new_turn = new Turn();
        $new_turn->turn_no = intval($index);
        $preset->addTurn($new_turn);

        $turns = $preset->getPresetTurns();

        $turns_grid = new TurnsGrid($preset->getAoeVersion());
        return $turns_grid->editableTimeline($turns);
    }

    public static function deleteTurn(Request $request, Response $response, ServiceProvider $service)
    {
        if (!self::checkLogin($request, $response, $service)) {
            return;
        }

        $index = $request->param('index');
        $preset_id = $request->param('preset_id');

        $preset = new Preset($preset_id);
        $preset->deleteTurn(intval($index));

        $turns = $preset->getPresetTurns();

        $turns_grid = new TurnsGrid($preset->getAoeVersion());
        return $turns_grid->editableTimeline($turns);
    }

    public static function changeTurn(Request $request, Response $response, ServiceProvider $service)
    {
        if (!self::checkLogin($request, $response, $service)) {
            return;
        }

        $index = $request->param('index');
        $preset_id = $request->param('preset_id');

        $preset = new Preset($preset_id);
        if ($request->paramsPost()->exists('hidden')) {
            $preset->setTurnHidden($index, intval($request->param('hidden')));
        }
        if ($request->paramsPost()->exists('role')) {
            $preset->setTurnPlayer($index, intval($request->param('role')));
        }
        if ($request->paramsPost()->exists('action')) {
            $preset->setTurnAction($index, intval($request->param('action')));
        }

        $turns = $preset->getPresetTurns();

        $turns_grid = new TurnsGrid($preset->getAoeVersion());
        return $turns_grid->editableTimeline($turns);
    }

    public static function addPreTurn(Request $request, Response $response, ServiceProvider $service)
    {
        if (!self::checkLogin($request, $response, $service)) {
            return;
        }

        $index = $request->param('index');
        $preset_id = $request->param('preset_id');

        $preset = new Preset($preset_id);

        $new_turn = new Turn();
        $new_turn->turn_no = intval($index);
        $preset->addPreTurn($new_turn);

        $turns = $preset->getPresetPreTurns();

        $turns_grid = new TurnsGrid($preset->getAoeVersion());
        return $turns_grid->editablePreTurns($turns);
    }

    public static function delPreTurn(Request $request, Response $response, ServiceProvider $service)
    {
        if (!self::checkLogin($request, $response, $service)) {
            return;
        }

        $index = $request->param('index');
        $preset_id = $request->param('preset_id');

        $preset = new Preset($preset_id);
        $preset->delPreTurn(intval($index));

        $turns = $preset->getPresetPreTurns();

        $turns_grid = new TurnsGrid($preset->getAoeVersion());
        return $turns_grid->editablePreTurns($turns);
    }

    public static function changePreTurn(Request $request, Response $response, ServiceProvider $service)
    {
        if (!self::checkLogin($request, $response, $service)) {
            return;
        }

        $index = $request->param('index');
        $preset_id = $request->param('preset_id');

        $preset = new Preset($preset_id);
        if ($request->paramsPost()->exists('civ')) {
            $preset->setPreTurnCiv($index, intval($request->param('civ')));
        }
        if ($request->paramsPost()->exists('role')) {
            $preset->setPreTurnPlayer($index, intval($request->param('role')));
        }
        if ($request->paramsPost()->exists('action')) {
            $preset->setPreTurnAction($index, intval($request->param('action')));
        }

        $turns = $preset->getPresetPreTurns();

        $turns_grid = new TurnsGrid($preset->getAoeVersion());
        return $turns_grid->editablePreTurns($turns);
    }

    public static function setPresetName(Request $request, Response $response, ServiceProvider $service)
    {
        if (!self::checkLogin($request, $response, $service)) {
            return;
        }

        $preset = new Preset($request->param('preset_id'));
        $preset->setName(trim($request->param('name')));

        echo $preset->name;
    }

    public static function setPresetDescription(Request $request, Response $response, ServiceProvider $service)
    {
        if (!self::checkLogin($request, $response, $service)) {
            return;
        }

        $preset = new Preset($request->param('preset_id'));
        $preset->setDescription(trim($request->param('description')));

        echo $preset->description;
    }
}
