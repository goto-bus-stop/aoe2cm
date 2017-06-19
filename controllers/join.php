<?php

include_once 'lib/HashGenerator.php';
include_once 'lib/Hashids.php';
include_once 'lib/constants.class.php';
include_once 'lib/name_generator.php';


function join_game() {
	if(isset($_REQUEST['join'])) {
		$game_info = getDatabase()->one('SELECT * FROM game WHERE BINARY code=:Code AND state !=:State',
			array(':Code' => trim($_REQUEST['code']), ':State' => Constants::DRAFT_STATE_DONE));
		$msg = ''.$game_info['id'].' '.$game_info['state'];
		if($game_info) {
			$game_id = $game_info['id'];
			$users = getDatabase()->all('SELECT * FROM user WHERE game_id=:Game', array(':Game' => $game_id));
			$known_user = FALSE;
			$msg .= ''.count($users);
			for($i = 0; $i < count($users); $i++)
			{
				if (strcmp($users[$i]['session_id'], session_id()) == 0) {
					$known_user = TRUE;
					$msg .= 'known';
				}
			}


			$captain_name = (empty($_COOKIE['username'])) ? generate_random_name() : substr($_COOKIE['username'], 0, Constants::NAME_LENGTH_LIMIT);

			if(count($users) == 0 && !$known_user && $game_info['state'] == Constants::DRAFT_STATE_WAITING) {
				$user_role = (isset($_REQUEST['role']) && $_REQUEST['role'] == 1)? 1 : 0;

				$user_id = getDatabase()->execute('INSERT INTO user (game_id, session_id, role, name) VALUES (:Game, :Session, :Role, :Name);',
					array(':Game' => $game_id, ':Session' => session_id(), ':Role' => $user_role, ':Name' => $captain_name));
				$known_user = TRUE;
			}
		
			if(count($users) == 1 && !$known_user && $game_info['state'] == Constants::DRAFT_STATE_WAITING) {
				$user_role = 1 - $users[0]['role'];

				if(isset($_REQUEST['role'])) {
					$request_role = ($_REQUEST['role'] == 1)? 1 : 0;

					if($request_role == $users[0]['role']) {
						$user_role = -1;
						$msg .= 'role_taken';
					} else {
						$user_role = $request_role;
					}
				}

				if($user_role >= 0) {
					//adding second use
					$user_id = getDatabase()->execute('INSERT INTO user (game_id, session_id, role, name) VALUES (:Game, :Session, :Role, :Name);',
						array(':Game' => $game_id, ':Session' => session_id(), ':Role' => $user_role, ':Name' => $captain_name));
					//updating game started info
					getDatabase()->execute('UPDATE game SET date_started=CURRENT_TIMESTAMP(3), state=:State WHERE id=:Game', array(':Game' => $game_id, ':State' => Constants::DRAFT_STATE_STARTING));
					$known_user = TRUE;
					$msg .= 'added';
				}
			}
			
			if($known_user) {
				$msg .= 'redirected';
				getRoute()->redirect(ROOTDIR.'/draft?code='.trim($_REQUEST['code']));
				return;
			}
			
		}
		
		getRoute()->redirect(ROOTDIR.'/spectate?code='.trim($_REQUEST['code']).'&msg='.$msg);
	} elseif (isset($_REQUEST['spectate'])) {
		getRoute()->redirect(ROOTDIR.'/spectate?code='.trim($_REQUEST['code']));
	} else {
		getRoute()->redirect(ROOTDIR.'?error=');
	}
}

function create_game() {
	/* create the class object */
	$hashids = new Hashids\Hashids(Constants::UNIQ_SALT);
	$demo = 0; $hidden = 0;
	$type = 0; $diffcivsteam = 0;
	$host_game = FALSE;

	if(isset($_REQUEST['t']) && is_numeric($_REQUEST['t'])){
		$type |= intval($_REQUEST['t']);
	}
	if(isset($_REQUEST['h']) && $_REQUEST['h'] == '1'){
		$hidden = 1;
		$type |= Constants::GAME_HIDDEN;
	}
	if(isset($_REQUEST['d']) && $_REQUEST['d'] == '1'){
		$demo = 1;
		$type |= Constants::GAME_DEMO;
	}
	if(isset($_REQUEST['dct']) && $_REQUEST['dct'] == '1'){
		$diffcivsteam = 1;
		$type |= Constants::GAME_DIFFCIVSPERTEAM;
	}

	if($demo == 0 && isset($_REQUEST['host']) && $_REQUEST['host'] == '1') {
		$host_game = TRUE;
	}

	$preset = null;
	if(isset($_REQUEST['p'])) {
		$preset = intval($_REQUEST['p']);
		//check in the db for the said preset
		$preset_db = getDatabase()->one('SELECT * FROM preset WHERE id=:Id', array(':Id' => $preset));
		if(empty($preset_db)) {
			getRoute()->redirect(ROOTDIR.'/');
		}
	}

	$game_state = Constants::DRAFT_STATE_WAITING;
	if($demo) {
		$game_state = Constants::DRAFT_STATE_READY;
	}
	
	$game_id = getDatabase()->execute('INSERT INTO game (type,state,code, preset_id) VALUES (:Type,:State,:Invite,:Preset);',
		array(':Type' => $type, ':State' => $game_state, ':Invite' => '0', ':Preset' => $preset));
		
	if($game_id){
		$generated_code = ''.$hashids->encode($game_id);
		getDatabase()->execute('UPDATE game SET code=:Code WHERE id=:Id', array(':Code' => $generated_code, ':Id' => $game_id));
		
		if(!$host_game) {
			$captain_name = (empty($_COOKIE['username'])) ? "Host Captain" : substr($_COOKIE['username'], 0, Constants::NAME_LENGTH_LIMIT);
			$user_id = getDatabase()->execute('INSERT INTO user (game_id, session_id, name) VALUES (:Game, :Session, :Name);',
				array(':Game' => $game_id, ':Session' => session_id(), ':Name' => $captain_name));
		}

		if($demo) {
			$user_id = getDatabase()->execute('INSERT INTO user (game_id, session_id, name,role) VALUES (:Game, :Session, :Name, :Role);',
				array(':Game' => $game_id, ':Session' => session_id(), ':Name' => "Demo Opponent", ':Role' => 1));

			getDatabase()->execute('UPDATE game SET date_started=CURRENT_TIMESTAMP(3), state=:State WHERE id=:Game', array(':Game' => $game_id, ':State' => Constants::DRAFT_STATE_READY));
		}

		//insert global bans to game history
		if($preset != null) {
			$disabled_civ_items = getDatabase()->all('SELECT * FROM preset_item WHERE preset_id=:Id AND type=:Type',
				array(':Id' => $preset, ':Type' => Constants::PRESET_ITEM_TYPE_DISABLED_CIV));
			if(!empty(disabled_civ_items)) {
				for($i = 0; $i < count($disabled_civ_items); $i++) {
					$turn_id = getDatabase()->execute('INSERT INTO turn (game_id, turn_no, civ,action,player) VALUES (:Game, :No, :Data, :Action, :Player);',
						array(':Game' => $game_id, ':No' => -($i+1), ':Data' => $disabled_civ_items[$i]['datai'],
							':Action' => Constants::DO_BAN, ':Player' => -1));
				}
			}
		}


		if($demo) {
			getRoute()->redirect(ROOTDIR.'/demo?code='.$generated_code);
		} else if($host_game) {
			getRoute()->redirect(ROOTDIR.'/spectate?code='.$generated_code);
		} else {
			getRoute()->redirect(ROOTDIR.'/draft?code='.$generated_code);
		}
	} else {
		getRoute()->redirect(ROOTDIR.'/');
	}
}

?>
