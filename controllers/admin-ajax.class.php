<?php

use Klein\{Request, Response, ServiceProvider};

require_once __DIR__.'/../lib/TurnsGrid.class.php';

class AdminAjaxController
{
    static public function check_login(Request $request, Response $response, ServiceProvider $service) {
        if(!($service->session)(Constants::LOGGED_IN)) {
            $response->code(403);
            return false;
        }
        return true;
    }

    static public function preset_set_type(Request $request, Response $response, ServiceProvider $service) {
        if (!self::check_login($request, $response, $service)) return;

        $preset = new Preset($request->param('preset_id'));
        $preset->set_type($request->param('type'));
        $turns = $preset->get_preset_turns();

        $turns_grid = new TurnsGrid($preset->get_aoe_version());
        return $turns_grid->editableTimeline($turns);
    }

    static public function preset_set_aoe_version(Request $request, Response $response, ServiceProvider $service) {
        if (!self::check_login($request, $response, $service)) return;

        $preset = new Preset($request->param('request_id'));
        $preset->set_aoe_version($request->param('version'));

        $turns_grid = new TurnsGrid($preset->get_aoe_version());
        return $turns_grid->editablePreTurns($preset->get_preset_pre_turns());
    }

    static public function add_turn(Request $request, Response $response, ServiceProvider $service) {
        if (!self::check_login($request, $response, $service)) return;

        $index = $request->param('index');
        $preset_id = $request->param('preset_id');

        $preset = new Preset($preset_id);

        $new_turn = new Turn();
        $new_turn->turn_no = intval($index);
        $preset->add_turn($new_turn);

        $turns = $preset->get_preset_turns();

        $turns_grid = new TurnsGrid($preset->get_aoe_version());
        return $turns_grid->editableTimeline($turns);
    }

    static public function del_turn(Request $request, Response $response, ServiceProvider $service) {
        if (!self::check_login($request, $response, $service)) return;

        $index = $request->param('index');
        $preset_id = $request->param('preset_id');

        $preset = new Preset($preset_id);
        $preset->del_turn(intval($index));

        $turns = $preset->get_preset_turns();

        $turns_grid = new TurnsGrid($preset->get_aoe_version());
        return $turns_grid->editableTimeline($turns);
    }

    static public function change_turn(Request $request, Response $response, ServiceProvider $service) {
        if (!self::check_login($request, $response, $service)) return;

        $index = $request->param('index');
        $preset_id = $request->param('preset_id');

        $preset = new Preset($preset_id);
        if($request->paramsPost()->exists('hidden')) {
            $preset->set_turn_hidden($index, intval($request->param('hidden')));
        }
        if($request->paramsPost()->exists('role')) {
            $preset->set_turn_player($index, intval($request->param('role')));
        }
        if($request->paramsPost()->exists('action')) {
            $preset->set_turn_action($index, intval($request->param('action')));
        }

        $turns = $preset->get_preset_turns();

        $turns_grid = new TurnsGrid($preset->get_aoe_version());
        return $turns_grid->editableTimeline($turns);
    }

    static public function add_pre_turn(Request $request, Response $response, ServiceProvider $service) {
        if (!self::check_login($request, $response, $service)) return;

        $index = $request->param('index');
        $preset_id = $request->param('preset_id');

        $preset = new Preset($preset_id);

        $new_turn = new Turn();
        $new_turn->turn_no = intval($index);
        $preset->add_pre_turn($new_turn);

        $turns = $preset->get_preset_pre_turns();

        $turns_grid = new TurnsGrid($preset->get_aoe_version());
        return $turns_grid->editablePreTurns($turns);
    }

    static public function del_pre_turn(Request $request, Response $response, ServiceProvider $service) {
        if (!self::check_login($request, $response, $service)) return;

        $index = $request->param('index');
        $preset_id = $request->param('preset_id');

        $preset = new Preset($preset_id);
        $preset->del_pre_turn(intval($index));

        $turns = $preset->get_preset_pre_turns();

        $turns_grid = new TurnsGrid($preset->get_aoe_version());
        return $turns_grid->editablePreTurns($turns);
    }

    static public function change_pre_turn(Request $request, Response $response, ServiceProvider $service) {
        if (!self::check_login($request, $response, $service)) return;

        $index = $request->param('index');
        $preset_id = $request->param('preset_id');

        $preset = new Preset($preset_id);
        if($request->paramsPost()->exists('civ')) {
            $preset->set_pre_turn_civ($index, intval($request->param('civ')));
        }
        if($request->paramsPost()->exists('role')) {
            $preset->set_pre_turn_player($index, intval($request->param('role')));
        }
        if($request->paramsPost()->exists('action')) {
            $preset->set_pre_turn_action($index, intval($request->param('action')));
        }

        $turns = $preset->get_preset_pre_turns();

        $turns_grid = new TurnsGrid($preset->get_aoe_version());
        return $turns_grid->editablePreTurns($turns);
    }

    static public function set_preset_name(Request $request, Response $response, ServiceProvider $service) {
        if (!self::check_login($request, $response, $service)) return;

        $preset = new Preset($request->param('preset_id'));
        $preset->set_name(trim($request->param('name')));

        echo $preset->name;
    }

    static public function set_preset_description(Request $request, Response $response, ServiceProvider $service) {
        if (!self::check_login($request, $response, $service)) return;

        $preset = new Preset($request->param('preset_id'));
        $preset->set_description(trim($request->param('description')));

        echo $preset->description;
    }
}
