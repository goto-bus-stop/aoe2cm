<?php

include_once 'lib/TurnsGrid.class.php';

class AdminAjaxController
{
	static public function check_login() {
		if(!getSession()->get(Constants::LOGGED_IN)) {
			header("HTTP/1.0 404 Not Found");
			die();
			return;
		}
	}
	
	static public function preset_set_type() {
		self::check_login();

		$preset = new Preset($_REQUEST['preset_id']);
		$preset->set_type($_REQUEST['type']);
		$turns = $preset->get_preset_turns();
		
		$turns_grid = new TurnsGrid($preset->get_aoe_version());
		return $turns_grid->editableTimeline($turns);
	}

	static public function preset_set_aoe_version() {
		self::check_login();

		$preset = new Preset($_POST['preset_id']);
		$preset->set_aoe_version($_POST['version']);
		
		$turns_grid = new TurnsGrid($preset->get_aoe_version());
		return $turns_grid->editablePreTurns($preset->get_preset_pre_turns());
	}
	
	static public function add_turn() {
		self::check_login();
		
		$index = $_REQUEST['index'];
		$preset_id = $_REQUEST['preset_id'];

		$preset = new Preset($preset_id);

		$new_turn = new Turn();
		$new_turn->turn_no = intval($index);
		$preset->add_turn($new_turn);

		$turns = $preset->get_preset_turns();
		
		$turns_grid = new TurnsGrid($preset->get_aoe_version());
		return $turns_grid->editableTimeline($turns);
	}
	
	static public function del_turn() {
		self::check_login();
		
		$index = $_REQUEST['index'];
		$preset_id = $_REQUEST['preset_id'];

		$preset = new Preset($preset_id);
		$preset->del_turn(intval($index));

		$turns = $preset->get_preset_turns();
		
		$turns_grid = new TurnsGrid($preset->get_aoe_version());
		return $turns_grid->editableTimeline($turns);
		
	}
	
	static public function change_turn() {
		self::check_login();
		
		$index = intval($_REQUEST['index']);
		$preset_id = $_REQUEST['preset_id'];

		$preset = new Preset($preset_id);
		if(isset($_REQUEST['hidden'])) {
			$preset->set_turn_hidden($index, intval($_REQUEST['hidden']));
		}
		if(isset($_REQUEST['role'])) {
			$preset->set_turn_player($index, intval($_REQUEST['role']));
		}
		if(isset($_REQUEST['action'])) {
			$preset->set_turn_action($index, intval($_REQUEST['action']));
		}
		
		$turns = $preset->get_preset_turns();

		$turns_grid = new TurnsGrid($preset->get_aoe_version());
		return $turns_grid->editableTimeline($turns);
	}
	
	
	static public function add_pre_turn() {
		self::check_login();
		
		$index = $_REQUEST['index'];
		$preset_id = $_REQUEST['preset_id'];

		$preset = new Preset($preset_id);

		$new_turn = new Turn();
		$new_turn->turn_no = intval($index);
		$preset->add_pre_turn($new_turn);

		$turns = $preset->get_preset_pre_turns();
		
		$turns_grid = new TurnsGrid($preset->get_aoe_version());
		return $turns_grid->editablePreTurns($turns);
	}
	
	static public function del_pre_turn() {
		self::check_login();
		
		$index = $_REQUEST['index'];
		$preset_id = $_REQUEST['preset_id'];

		$preset = new Preset($preset_id);
		$preset->del_pre_turn(intval($index));

		$turns = $preset->get_preset_pre_turns();
		
		$turns_grid = new TurnsGrid($preset->get_aoe_version());
		return $turns_grid->editablePreTurns($turns);
		
	}
	
	static public function change_pre_turn() {
		self::check_login();
		
		$index = intval($_REQUEST['index']);
		$preset_id = $_REQUEST['preset_id'];

		$preset = new Preset($preset_id);
		if(isset($_REQUEST['civ'])) {
			$preset->set_pre_turn_civ($index, intval($_REQUEST['civ']));
		}
		if(isset($_REQUEST['role'])) {
			$preset->set_pre_turn_player($index, intval($_REQUEST['role']));
		}
		if(isset($_REQUEST['action'])) {
			$preset->set_pre_turn_action($index, intval($_REQUEST['action']));
		}
		
		$turns = $preset->get_preset_pre_turns();
		
		$turns_grid = new TurnsGrid($preset->get_aoe_version());
		return $turns_grid->editablePreTurns($turns);
	}
	
	
	static public function set_preset_name() {
		self::check_login();

		$preset = new Preset($_REQUEST['preset_id']);
		$preset->set_name(trim($_REQUEST['name']));
		
		echo $preset->name;
	}

	static public function set_preset_description() {
		self::check_login();
		
		$preset = new Preset($_REQUEST['preset_id']);
		$preset->set_description(trim($_REQUEST['description']));
		
		echo $preset->description;
	}
}
?>
