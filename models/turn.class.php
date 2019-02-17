<?php

namespace Aoe2CM;

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

    const DO_STRING_IDS = [
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
        self::DO_HIDE => 'hide',
    ];

    const PICK_ACTIONS = [
        self::DO_PICK,
        self::DO_GLOBAL_PICK,
        self::DO_EXCLUSIVE_PICK,
    ];

    const BAN_ACTIONS = [
        self::DO_BAN,
        self::DO_GLOBAL_BAN,
        self::DO_EXCLUSIVE_BAN,
    ];

    const ADMIN_ACTIONS = [
        self::DO_REVEAL_ALL,
        self::DO_REVEAL_PICKS,
        self::DO_REVEAL_BANS,
        self::DO_DISABLE_BAN,
        self::DO_DISABLE_PICK,
        self::DO_DISABLE_BAN_AND_PICK,
        self::DO_HIDE,
    ];

    const TURNS_NO_MIN = -36;
    const TURNS_NO_MAX = 36;

    const DISABLED_EMPTY = [
        Player::PLAYER_1 => [],
        Player::PLAYER_2 => [],
    ];

    public $draft_id = null;
    public $turn_no = 0;
    public $time_created = null;
    public $civ = 0;
    public $hidden = self::TURN_VISIBLE;
    public $player_role = -1;
    public $action = self::DO_PICK;

    public function __construct($db_entry = null)
    {
        if (is_array($db_entry)) {
            $this->draft_id = intval($db_entry['game_id']);
            $this->turn_no = intval($db_entry['turn_no']);
            $this->civ = intval($db_entry['civ']);
            $this->hidden = intval($db_entry['hidden']);
            $this->player_role = intval($db_entry['player']);
            $this->action = intval($db_entry['action']);
            $this->time_created = self::getTimeInSeconds($db_entry['time_created']);
        }
    }


    public static function getTimeInSeconds($dateString)
    {
        $date = new DateTime($dateString);
        return floatval($date->getTimestamp().'.'.$date->format('u'));
    }

    public function isReveal()
    {
        return $this->action == self::DO_REVEAL_PICKS ||
            $this->action == self::DO_REVEAL_BANS ||
            $this->action == self::DO_REVEAL_ALL;
    }

    public function isRevealPick()
    {
        return $this->action == self::DO_REVEAL_PICKS || $this->action == self::DO_REVEAL_ALL;
    }

    public function isRevealBan()
    {
        return $this->action == self::DO_REVEAL_BANS || $this->action == self::DO_REVEAL_ALL;
    }

    public function isHidden()
    {
        return $this->hidden == self::TURN_HIDDEN;
    }

    public function isHiddenBan()
    {
        return $this->hidden == self::TURN_HIDDEN && $this->isBan();
    }

    public function isHiddenPick()
    {
        return $this->hidden == self::TURN_HIDDEN && $this->isPick();
    }

    public function getDisabledEmpty()
    {

        return [Player::PLAYER_1 => [], Player::PLAYER_2 => []];
    }

    public function getCiv()
    {
        if ($this->civ >= Constants::RANDOM_CHOICE_OFFSET) {
            return $this->civ - Constants::RANDOM_CHOICE_OFFSET;
        }
        return $this->civ;
    }

    public function isRandomlyChosen()
    {
        return $this->civ >= Constants::RANDOM_CHOICE_OFFSET;
    }

    public function getDisabledPicks()
    {
        if ($this->getCiv() <= Constants::CIV_RANDOM) {
            return self::DISABLED_EMPTY;
        }

        $disabled_civs = self::DISABLED_EMPTY;
        switch ($this->action) {
            case self::DO_GLOBAL_BAN:
            case self::DO_GLOBAL_PICK:
                $disabled_civs[Player::PLAYER_1][] = $this->getCiv();
                $disabled_civs[Player::PLAYER_2][] = $this->getCiv();
                break;
            case self::DO_EXCLUSIVE_BAN:
            case self::DO_BAN:
                if ($this->player_role != Player::PLAYER_NONE) {
                    $disabled_civs[Player::opponent($this->player_role)][] = $this->getCiv();
                }
                break;
            case self::DO_EXCLUSIVE_PICK:
                if ($this->player_role != Player::PLAYER_NONE) {
                    $disabled_civs[$this->player_role][] = $this->getCiv();
                }
                break;
            case self::DO_DISABLE_BAN_AND_PICK:
            case self::DO_DISABLE_PICK:
            case self::DO_HIDE:
                if ($this->player_role == Player::PLAYER_NONE) {
                    $disabled_civs[Player::PLAYER_1][] = $this->getCiv();
                    $disabled_civs[Player::PLAYER_2][] = $this->getCiv();
                } else {
                    $disabled_civs[$this->player_role][] = $this->getCiv();
                }
                break;
        }
        return $disabled_civs;
    }

    public function getDisabledBans()
    {
        if ($this->getCiv() <= Constants::CIV_RANDOM) {
            return self::DISABLED_EMPTY;
        }

        $disabled_civs = self::DISABLED_EMPTY;
        switch ($this->action) {
            case self::DO_GLOBAL_BAN:
            case self::DO_GLOBAL_PICK:
                $disabled_civs[Player::PLAYER_1][] = $this->getCiv();
                $disabled_civs[Player::PLAYER_2][] = $this->getCiv();
                break;
            case self::DO_EXCLUSIVE_BAN:
                if ($this->player_role != Player::PLAYER_NONE) {
                    $disabled_civs[$this->player_role][] = $this->getCiv();
                }
                break;
            case self::DO_DISABLE_BAN_AND_PICK:
            case self::DO_DISABLE_BAN:
            case self::DO_HIDE:
                if ($this->player_role == Player::PLAYER_NONE) {
                    $disabled_civs[Player::PLAYER_1][] = $this->getCiv();
                    $disabled_civs[Player::PLAYER_2][] = $this->getCiv();
                } else {
                    $disabled_civs[$this->player_role][] = $this->getCiv();
                }
                break;
        }
        return $disabled_civs;
    }

    public function getClientAction()
    {
        if (self::actionIsPick($this->action)) {
            return self::DO_PICK;
        } elseif (self::actionIsBan($this->action)) {
            return self::DO_BAN;
        } elseif (self::actionIsHide($this->action)) {
            return self::DO_HIDE;
        }
        return self::DO_OTHER;
    }

    public function isPick()
    {
        return in_array($this->action, self::PICK_ACTIONS);
    }

    public function isBan()
    {
        return in_array($this->action, self::BAN_ACTIONS);
    }

    public function isHide()
    {
        return $this->action == self::DO_HIDE;
    }

    public static function actionIsPick($action)
    {
        return in_array($action, self::PICK_ACTIONS);
    }
    
    public static function actionIsBan($action)
    {
        return in_array($action, self::BAN_ACTIONS);
    }

    public static function actionIsHide($action)
    {
        return $action == self::DO_HIDE;
    }

    public static function actionIsGlobal($action)
    {
        return $action == self::DO_GLOBAL_BAN || $action == self::DO_GLOBAL_PICK;
    }

    public static function actionIsAdmin($action, $player)
    {
        if ($player == Player::PLAYER_NONE) {
            return true;
        }

        return in_array($action, self::ADMIN_ACTIONS);
    }

    public static function actionGetType($action)
    {
        if (self::actionIsPick($action)) {
            return Turn::DO_PICK;
        } elseif (self::actionIsBan($action)) {
            return TURN::DO_BAN;
        } else {
            return Turn::DO_OTHER;
        }
    }

    public static function getDoStrings()
    {
        return [
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
            self::DO_HIDE => _('hide'),
        ];
    }

    public static function getFancyDoStrings()
    {
        return [
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
            self::DO_HIDE => _('hide'),
        ];
    }
}
