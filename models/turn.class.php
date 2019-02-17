<?php
require_once 'models/player.class.php';

class Turn
{

    const TURN_VISIBLE = 0;
    const TURN_HIDDEN = 1;

	//base stuff stored in the db
    const DO_OTHER = -1;
    const DO_PICK = 0;
    const DO_BAN = 1;
    const DO_REVEAL_PICKS = 2;
    const DO_REVEAL_BANS = 3;

    const DO_GLOBAL_PICK = 4;
    const DO_GLOBAL_BAN = 5;
    const DO_EXCLUSIVE_PICK = 6; //for the same player, can't choose it a second time
    const DO_EXCLUSIVE_BAN = 7; //for the same player, can't ban it one more time
    const DO_REVEAL_ALL = 8;
    const DO_DISABLE_PICK = 9;
    const DO_DISABLE_BAN = 10;
    const DO_DISABLE_BAN_AND_PICK = 11; //basically the same as global ban
    const DO_HIDE = 12;

    const DO_STRING_IDS = array(
        self::DO_PICK => 'pick',
        self::DO_BAN => 'ban',
        self::DO_REVEAL_PICKS => 'rpicks',
        self::DO_REVEAL_BANS => 'rbans',
        self::DO_GLOBAL_PICK => 'gpick',
        self::DO_GLOBAL_BAN => 'gban',
        self::DO_EXCLUSIVE_PICK => 'epick',
        self::DO_EXCLUSIVE_BAN => 'eban',
        self::DO_REVEAL_ALL => 'rall',
        self::DO_DISABLE_PICK => 'disp',
        self::DO_DISABLE_BAN => 'disb',
        self::DO_DISABLE_BAN_AND_PICK => 'disbp',
        self::DO_HIDE => 'hide');

    const PICK_ACTIONS = array(
        self::DO_PICK,
        self::DO_GLOBAL_PICK,
        self::DO_EXCLUSIVE_PICK);

    const BAN_ACTIONS = array(
        self::DO_BAN,
        self::DO_GLOBAL_BAN,
        self::DO_EXCLUSIVE_BAN);

    const ADMIN_ACTIONS = array(
        self::DO_REVEAL_ALL,
        self::DO_REVEAL_PICKS,
        self::DO_REVEAL_BANS,
        self::DO_DISABLE_BAN,
        self::DO_DISABLE_PICK,
        self::DO_DISABLE_BAN_AND_PICK,
        self::DO_HIDE
        );

    const TURNS_NO_MIN = -36;
    const TURNS_NO_MAX = 36;

    const DISABLED_EMPTY = array(
        Player::PLAYER_1 => array(),
        Player::PLAYER_2 => array()
        );

    public $draft_id = null;
    public $turn_no = 0;
    public $time_created = null;
    public $civ = 0;
    public $hidden = self::TURN_VISIBLE;
    public $player_role = -1;
    public $action = self::DO_PICK;

    public function __construct($db_entry = null) {
    	if(is_array($db_entry)) {
    		$this->draft_id = intval($db_entry['game_id']);
    		$this->turn_no = intval($db_entry['turn_no']);
    		$this->civ = intval($db_entry['civ']);
            $this->hidden = intval($db_entry['hidden']);
    		$this->player_role = intval($db_entry['player']);
    		$this->action = intval($db_entry['action']);
    		$this->time_created = self::get_time_in_seconds($db_entry['time_created']);
    	} 
    }


    public static function get_time_in_seconds($dateString) {
        $date = new DateTime($dateString);
        return floatval($date->getTimestamp().'.'.$date->format('u'));
    }

    public function is_reveal() {
        return $this->action == self::DO_REVEAL_PICKS || $this->action == self::DO_REVEAL_BANS || $this->action == self::DO_REVEAL_ALL;
    }

    public function is_reveal_pick() {
        return $this->action == self::DO_REVEAL_PICKS || $this->action == self::DO_REVEAL_ALL;
    }

    public function is_reveal_ban() {
        return $this->action == self::DO_REVEAL_BANS || $this->action == self::DO_REVEAL_ALL;
    }

    public function is_hidden() {
        return $this->hidden == self::TURN_HIDDEN;
    }

    public function is_hidden_ban() {
        return $this->hidden == self::TURN_HIDDEN && $this->is_ban();
    }

    public function is_hidden_pick() {
        return $this->hidden == self::TURN_HIDDEN && $this->is_pick();
    }

    public function get_disabled_empty() {

        return array(Player::PLAYER_1 => array(), Player::PLAYER_2 => array());
    }

    public function get_civ() {
        if($this->civ >= Constants::RANDOM_CHOICE_OFFSET) {
            return $this->civ - Constants::RANDOM_CHOICE_OFFSET;
        }
        return $this->civ;
    }

    public function is_randomly_chosen() {
        return $this->civ >= Constants::RANDOM_CHOICE_OFFSET;
    }

    public function get_disabled_picks() {
        if($this->get_civ() <= Constants::CIV_RANDOM) {
            return self::DISABLED_EMPTY;
        }

        $disabled_civs = self::DISABLED_EMPTY;
        switch($this->action) {
            case self::DO_GLOBAL_BAN:
            case self::DO_GLOBAL_PICK:
                $disabled_civs[Player::PLAYER_1][] = $this->get_civ();
                $disabled_civs[Player::PLAYER_2][] = $this->get_civ();
                break;
            case self::DO_EXCLUSIVE_BAN:
            case self::DO_BAN:
                if($this->player_role != Player::PLAYER_NONE) {
                    $disabled_civs[Player::opponent($this->player_role)][] = $this->get_civ();
                }
                break;
            case self::DO_EXCLUSIVE_PICK:
                if($this->player_role != Player::PLAYER_NONE) {
                    $disabled_civs[$this->player_role][] = $this->get_civ();
                }
                break;
            case self::DO_DISABLE_BAN_AND_PICK:
            case self::DO_DISABLE_PICK:
            case self::DO_HIDE:
                if($this->player_role == Player::PLAYER_NONE) {
                    $disabled_civs[Player::PLAYER_1][] = $this->get_civ();
                    $disabled_civs[Player::PLAYER_2][] = $this->get_civ();
                } else {
                    $disabled_civs[$this->player_role][] = $this->get_civ();
                }
                break;
        }
        return $disabled_civs;
    }

    public function get_disabled_bans() {
        if($this->get_civ() <= Constants::CIV_RANDOM) {
            return self::DISABLED_EMPTY;
        }

        $disabled_civs = self::DISABLED_EMPTY;
        switch($this->action) {
            case self::DO_GLOBAL_BAN:
            case self::DO_GLOBAL_PICK:
                $disabled_civs[Player::PLAYER_1][] = $this->get_civ();
                $disabled_civs[Player::PLAYER_2][] = $this->get_civ();
                break;
            case self::DO_EXCLUSIVE_BAN:

                if($this->player_role != Player::PLAYER_NONE) {
                    $disabled_civs[$this->player_role][] = $this->get_civ();
                }
                break;
            case self::DO_DISABLE_BAN_AND_PICK:
            case self::DO_DISABLE_BAN:
            case self::DO_HIDE:
                if($this->player_role == Player::PLAYER_NONE) {
                    $disabled_civs[Player::PLAYER_1][] = $this->get_civ();
                    $disabled_civs[Player::PLAYER_2][] = $this->get_civ();
                } else {
                    $disabled_civs[$this->player_role][] = $this->get_civ();
                }
                break;
        }
        return $disabled_civs;
    }

    public function get_client_action() {
        if(self::action_is_pick($this->action)) {
            return self::DO_PICK;
        } else if(self::action_is_ban($this->action)) {
            return self::DO_BAN;
        } else if(self::action_is_hide($this->action)){
            return self::DO_HIDE;
        }
        return self::DO_OTHER;
    }

    public function is_pick() {
        return in_array($this->action, self::PICK_ACTIONS);
    }

    public function is_ban() {
        return in_array($this->action, self::BAN_ACTIONS);
    }

    public function is_hide() {
        return $this->action == self::DO_HIDE;
    }

    public static function action_is_pick($action) {
        return in_array($action, self::PICK_ACTIONS);
    }
	
    public static function action_is_ban($action) {
        return in_array($action, self::BAN_ACTIONS);
    }

    public static function action_is_hide($action) {
        return $action == self::DO_HIDE;
    }

    public static function action_is_global($action) {
        return $action == self::DO_GLOBAL_BAN || $action == self::DO_GLOBAL_PICK;
    }

    public static function action_is_admin($action, $player) {
        if($player == Player::PLAYER_NONE) {
            return true;
        }

        return in_array($action, self::ADMIN_ACTIONS);
    }

    public static function action_get_type($action) {
        if(self::action_is_pick($action)) {
            return Turn::DO_PICK;
        } elseif (self::action_is_ban($action)) {
            return TURN::DO_BAN;
        } else {
            return Turn::DO_OTHER;
        }
    }

    public static function get_do_strings() {
        return array(
            self::DO_PICK => _('pick'),
            self::DO_BAN => _('ban'),
            self::DO_REVEAL_PICKS => _('show picks'),
            self::DO_REVEAL_BANS => _('show bans'),
            self::DO_GLOBAL_PICK => _('<b>g</b>pick'),
            self::DO_GLOBAL_BAN => _('<b>g</b>ban'),
            self::DO_EXCLUSIVE_PICK => _('pick'),
            self::DO_EXCLUSIVE_BAN => _('ban'),
            self::DO_REVEAL_ALL => _('show'),
            self::DO_DISABLE_PICK => _('disable for pick'),
            self::DO_DISABLE_BAN => _('disable for ban'),
            self::DO_DISABLE_BAN_AND_PICK => _('disable civ'),
            self::DO_HIDE => _('hide'));
    }

    public static function get_fancy_do_strings() {
        return array(
            self::DO_PICK => _('pick'),
            self::DO_BAN => _('ban'),
            self::DO_REVEAL_PICKS => _('show picks'),
            self::DO_REVEAL_BANS => _('show bans'),
            self::DO_GLOBAL_PICK => _('global pick'),
            self::DO_GLOBAL_BAN => _('global ban'),
            self::DO_EXCLUSIVE_PICK => _('exclusive pick'),
            self::DO_EXCLUSIVE_BAN => _('exclusive ban'),
            self::DO_REVEAL_ALL => _('reveal all'),
            self::DO_DISABLE_PICK => _('disable pick'),
            self::DO_DISABLE_BAN => _('disable ban'),
            self::DO_DISABLE_BAN_AND_PICK => _('disable completely'),
            self::DO_HIDE => _('hide'));
    }
}