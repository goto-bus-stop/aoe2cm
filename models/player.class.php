<?php

include_once 'models/draft.class.php';

class Player
{
	const PLAYER_NONE = -1;
    const PLAYER_1 = 0;
    const PLAYER_2 = 1;
    const PLAYER_BOTH_1 = 2;
    const PLAYER_BOTH_2 = 3;

    const NAME_LENGTH_LIMIT = 32;

    public $id = -1;
    public $draft_id = -1;
    public $session_id = "";
    public $role = self::PLAYER_NONE;
    public $name = "";


    public function __construct($db_info = null) {
    	if(is_array($db_info)) {
    		$this->load_from_info($db_info);
    	} else if (is_numeric($db_info)) {
    		$players_db = getDatabase()->one('SELECT * FROM user WHERE id=:Id',
        	array(':Id' => $db_info));
        	if($player_db) {
        		$this->load_from_info($player_db);
        	}
    	}
    }

    private function load_from_info($db_info) {
    	$this->id = intval($db_info['id']);
    	$this->draft_id = intval($db_info['game_id']);
    	$this->session_id = $db_info['session_id'];
    	$this->role = intval($db_info['role']);
    	$this->name = $db_info['name'];
    }

    public function save() {
        if($this->id < 0) {
            $player_id = getDatabase()->execute('INSERT INTO user (game_id, session_id, role, name) VALUES (:Draft, :Session, :Role, :Name);',
                    array(':Draft' => $this->draft_id,
                        ':Session' => $this->session_id,
                        ':Role' => $this->role,
                        ':Name' => $this->name));
            $this->id = $player_id;
        } else {
            getDatabase()->execute('UPDATE user SET game_id=:Draft, session_id=:Session, role=:Role, name=:Name WHERE id=:Id',
                array(':Draft' => $this->draft_id,
                        ':Session' => $this->session_id,
                        ':Role' => $this->role,
                        ':Name' => $this->name,
                        ':Id' => $this->id));
        }
    }

    public function set_name($name) {
        if($this->id < 0) {
            return;
        }

        getDatabase()->execute('UPDATE user SET name=:Name WHERE id=:Id',
            array(':Name' => $name, ':Id' => $this->id));
        $this->name = $name;
    }

    public static function find_draft(Draft $draft)
    {
        $players_db = getDatabase()->all('SELECT * FROM user WHERE game_id=:Id ORDER BY role ASC',
        	array(':Id' => $draft->id));

        $players = array();
        foreach($players_db as $player_info){
        	$player = new Player($player_info);
        	$players[] = $player;
        }

        return $players;
    }

    public static function opponent($role) {
        switch($role) {
            case self::PLAYER_2:
                return self::PLAYER_1;
            case self::PLAYER_1: 
                return self::PLAYER_2;
            default:
                return self::PLAYER_NONE;
        }
    }

    public static function is_parallel($role)
    {
        switch($role) {
            case self::PLAYER_BOTH_1:
            case self::PLAYER_BOTH_2:
                return true;
            default:
                return false;
        }
    }

    public static function get_effective_player($role)
    {
        switch($role) {
            case self::PLAYER_BOTH_1:
                return self::PLAYER_1;
            case self::PLAYER_BOTH_2:
                return self::PLAYER_2;
            default:
                return $role;
        }
    }
}