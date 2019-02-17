<?php
namespace Aoe2CM;

use Medoo\Medoo;
use Hashids\Hashids;

class Draft
{
    const STATE_ERROR = '-2';
    const STATE_WAITING = '-1';
    const STATE_STARTED = '0';
    const STATE_DONE = '1';
    const STATE_STARTING = '2';
    const STATE_READY = '3';

    const START_TIMEOUT = 3;

    const TIME_SHORT = 30;
    const TIME_MIDDLE = 60;
    const TIME_LONG = 90;
    const PADDING_TIME = 2;
    const TIMEOUT = self::TIME_SHORT;
    const DISCONNECT_TIMEOUT = 10;


    const TYPE_1V1 = 0x00;
    const TYPE_2V2 = 0x01;
    const TYPE_3V3 = 0x02;
    const TYPE_4V4 = 0x03;
    const PRACTICE = 0x04;

    const TYPES = [
        self::TYPE_1V1,
        self::TYPE_2V2,
        self::TYPE_3V3,
        self::TYPE_4V4,
    ];

    const AOE_VERSION_AOC = 1;
    const AOE_VERSION_AOF = 2;
    const AOE_VERSION_AOAK = 3;
    const AOE_VERSION_AOR = 4;

    const AOC_VERSIONS = [
        self::AOE_VERSION_AOC,
        self::AOE_VERSION_AOF,
        self::AOE_VERSION_AOAK,
        self::AOE_VERSION_AOR,
    ];

    const TYPE_MASK = 0x03;
    const PRACTICE_MASK = 0x04;

    public $id = -1;
    public $code = '';
    public $title = '';
    public $players = [];
    public $player_role = -1;
    public $type = self::TYPE_1V1;
    public $aoe_version = self::AOE_VERSION_AOC;
    public $preset = null;
    public $date_started = null;
    public $date_started_str = null;
    public $current_turn = -1;
    public $state = self::STATE_ERROR;
    private $turns = [];

    public function __construct($db_info = null)
    {
        if (is_array($db_info)) {
            $this->loadFromInfo($db_info);
        } elseif (is_numeric($db_info)) {
            $draft_info = service()->db->get('game', '*', ['id' => $db_info]);
            if (!is_null($draft_info)) {
                $this->loadFromInfo($draft_info);
            }
        } else {
            // leave everything to default
        }
    }

    public function getCivCount()
    {
        switch ($this->aoe_version) {
            case self::AOE_VERSION_AOF:
                return Constants::AOF_CIV_COUNT;
            case self::AOE_VERSION_AOAK:
                return Constants::AOAK_CIV_COUNT;
            case self::AOE_VERSION_AOR:
                return Constants::AOR_CIV_COUNT;
            default:
                return Constants::AOC_CIV_COUNT;
        }
    }

    public function getAoeVersion()
    {
        return $this->aoe_version;
    }

    public static function findWithCode($code)
    {
        $draft_info = service()->db->get('game', '*', ['code' => $code]);
        return new Draft($draft_info);
    }

    public static function typeGetStr($type)
    {
        switch ($type & self::TYPE_MASK) {
            case self::TYPE_1V1:
                return _("1 v 1");
            case self::TYPE_2V2:
                return _("2 v 2");
            case self::TYPE_3V3:
                return _("3 v 3");
            case self::TYPE_4V4:
                return _("4 v 4");
        }
        return "";
    }

    public static function aoeVersionGetStr($version)
    {
        switch ($version) {
            case self::AOE_VERSION_AOC:
                return _("Conquerors");
            case self::AOE_VERSION_AOF:
                return _("Forgotten");
            case self::AOE_VERSION_AOAK:
                return _("Forgotten+African Kingdoms");
            case self::AOE_VERSION_AOR:
                return _("Forgotten+African Kingdoms+Rise of Rajas");
        }
        return _("Age of Empires II");
    }

    public function getTypeString()
    {
        return self::typeGetStr($this->type);
    }

    public static function create($new_type, $new_preset)
    {
        $draft_state = self::STATE_WAITING;

        service()->db->insert('game', [
            'type' => $new_type,
            'state' => $draft_state,
            'preset_id' => ($new_preset->exists()) ? $new_preset->id : null,
            'aoe_version' => $new_preset->getAoeVersion()
        ]);
        $draft_id = service()->db->id();

        if ($draft_id) {
            $hashids = new Hashids(Constants::UNIQ_SALT);
            $generated_code = ''.$hashids->encode($draft_id);
            service()->db->update('game', ['code' => $generated_code], ['id' => $draft_id]);
        }

        $draft = new Draft($draft_id);
        $draft->addPreTurns();

        return $draft;
    }

    private function addPreTurns()
    {
        if (!$this->exists()) {
            return;
        }

        $preset_actions = $this->preset->getPresetPreTurns();

        $disabled_civs = [];

        if (!empty($preset_actions)) {
            $next_turn = -count($preset_actions);
            foreach ($preset_actions as $preset_turn) {
                $civ = Constants::CIV_HIDDEN;
                if (isset($preset_turn['civ'])) {
                    $civ = $preset_turn['civ'];
                    if ($civ == Constants::CIV_RANDOM && Turn::actionIsHide($preset_turn['action'])) {
                        $civ = $this->getRandomCiv($disabled_civs);
                    }

                    if ($civ != Constants::CIV_RANDOM) {
                        $disabled_civs[] = $civ;   //not the best solution, but will work only for hide
                    }
                }
                $this->insertTurn(
                    $next_turn,
                    $civ,
                    Player::getEffectivePlayer($preset_turn['player']),
                    $preset_turn['action'],
                    Turn::TURN_VISIBLE
                );

                $next_turn += 1;
            }
        }
    }

    private function loadFromInfo($db_info)
    {
        $this->id = intval($db_info['id']);
        $this->code = $db_info['code'];
        $this->state = intval($db_info['state']);
        $this->type = intval($db_info['type']);
        $this->aoe_version = intval($db_info['aoe_version']);
        $this->date_started_str = $db_info['date_started'];
        $this->date_started = Turn::getTimeInSeconds($this->date_started_str);
        $this->loadPlayers();
        $this->loadPreset($db_info['preset_id']);
        $this->updateTitle();
        $this->loadCurrentTurn();
    }


    private function reload()
    {
        $draft_info = service()->db->get('game', '*', ['id' => $this->id]);
        if (!is_null($draft_info)) {
            $this->loadFromInfo($draft_info);
        }
        $this->loadTurns();
    }


    private function loadPlayers()
    {
        if ($this->id < 0) {
            return;
        }
        $this->players = Player::findDraft($this);

        $m_session_id = session_id();
        foreach ($this->players as $player) {
            if (strcmp($m_session_id, $player->session_id) == 0) {
                $this->player_role = $player->role;
            }
        }
    }

    public function getCurrentPlayer()
    {
        $m_session_id = session_id();
        foreach ($this->players as $player) {
            if (strcmp($m_session_id, $player->session_id) == 0) {
                return $player;
            }
        }
        return null;
    }

    private function loadPreset($preset_id)
    {

        if (empty($preset_id)) {
            $this->preset = new Preset();
        }
        $this->preset = Preset::find($preset_id);
    }

    private function updateTitle()
    {
        $this->title = "";

        if ($this->preset->exists()) {
            $this->title = $this->preset->getTitle();
        } else {
            $this->title = $this->getTypeString();
        }
    }

    private function loadCurrentTurn()
    {
        $current_turn_db = service()->db->get('current_turn', '*', ['game_id' => $this->id]);
        if (empty($current_turn_db)) {
            $this->current_turn = -1;
        } else {
            $this->current_turn = intval($current_turn_db['current_turn']);
        }
    }

    private function loadTurns()
    {
        $db_turns = service()->db->select('turn', '*', [
            'game_id' => $this->id,
            'ORDER' => [
                'turn_no' => 'ASC',
                'action' => 'ASC',
            ],
        ]);

        $this->turns = [];

        foreach ($db_turns as $db_turn) {
            $turn_no = intval($db_turn['turn_no']);
            $new_turn = new Turn($db_turn);
            $this->turns[$turn_no] = $new_turn;
        }
    }

    public function exists()
    {
        return $this->id >= 0;
    }

    public function isPractice()
    {
        return ($this->type & self::PRACTICE_MASK) == self::PRACTICE;
    }

    public function isWaiting()
    {
        return $this->state == self::STATE_WAITING;
    }

    public function isDone()
    {
        return $this->state == self::STATE_DONE;
    }

    public function isStarting()
    {
        return $this->state == self::STATE_STARTING;
    }

    public function isStarted()
    {
        return $this->state == self::STATE_STARTED;
    }

    public function isReady()
    {
        return $this->state = self::STATE_READY;
    }

    public function getPlayers()
    {
        return $this->players;
    }

    public function getTurns()
    {
        if ($this->turns == null) {
            $this->loadTurns();
        }

        return $this->turns;
    }

    public function getNextTurn()
    {
        $preset_turns = $this->getPresetTurns();
        if ($this->waitingForParallelPick() &&
            $this->current_turn > 0 &&
            $preset_turns[$this->current_turn]['player'] == Player::PLAYER_BOTH_2) {
            return $this->current_turn - 1;
        } elseif ($this->areParallelTurnsNext() && $this->getPlayerRole() == PLAYER::PLAYER_2) {
            return $this->current_turn + 2;
        } else {
            return $this->current_turn + 1;
        }
    }

    public function getPresetTurns()
    {
        return $this->preset->getTurns($this->type & self::TYPE_MASK);
    }

    public function getPresetPreTurns()
    {
        return $this->preset->getPresetPreTurns();
    }


    public function getLastTurn()
    {
        if (isset($this->getTurns()[$this->current_turn])) {
            return $this->turns[$this->current_turn];
        } else {
            return null;
        }
    }

    public function getPreviousToLastTurn()
    {
        $previous_to_last_index = $this->current_turn - 1;
        $last_turn = $this->getLastTurn();
        if ($this->waitingForParallelPick() && isset($last_turn) && $last_turn->player_role == Player::PLAYER_2) {
            $previous_to_last_index = $this->current_turn - 2;
        }
        if (isset($this->getTurns()[$previous_to_last_index])) {
            return $this->turns[$previous_to_last_index];
        } else {
            return null;
        }
    }

    public function waitingForParallelPick(): bool
    {
        $preset_turns = $this->getPresetTurns();
        if (!isset($preset_turns[$this->current_turn])) {
            return false;
        }
        if (!Player::isParallel($preset_turns[$this->current_turn])) {
            return false;
        }

        if (!isset($this->turns[$this->current_turn - 1]) &&
            $preset_turns[$this->current_turn - 1]['player'] == Player::PLAYER_BOTH_1 &&
            $preset_turns[$this->current_turn]['player'] == Player::PLAYER_BOTH_2) {
            return true;
        }

        if ($preset_turns[$this->current_turn + 1]['player'] == Player::PLAYER_BOTH_2 &&
            $preset_turns[$this->current_turn]['player'] == Player::PLAYER_BOTH_1) {
            return true;
        }

        return false;
    }

    public function areParallelTurnsNext()
    {
        $preset_turns = $this->getPresetTurns();
        if (!isset($preset_turns[$this->current_turn + 1]) || !isset($preset_turns[$this->current_turn + 2])) {
            return false;
        }
        $next_1 = $this->current_turn + 1;
        $next_2 = $this->current_turn + 2;

        return $preset_turns[$next_1]['player'] == Player::PLAYER_BOTH_1 &&
            $preset_turns[$next_2]['player'] == Player::PLAYER_BOTH_2;
    }

    public function getPlayerRole()
    {
        return $this->player_role;
    }

    public function getActivePlayer()
    {
        $active_player = -1;
        $preset_turns = $this->getPresetTurns();
        $next_turn = $this->getNextTurn();
        if ($this->isStarted() &&
            $next_turn < count($preset_turns)) {
            $active_player = Player::getEffectivePlayer($preset_turns[$next_turn]['player'], $this->getPlayerRole());
        }
        return $active_player;
    }

    public function setState($state)
    {
        //update game state
        service()->db->update('game', ['state' => $state], ['id' => $this->id]);
        $this->state = $state;
    }

    public function starting()
    {
        if ($this->state != self::STATE_WAITING) {
            return;
        }

        service()->db->update('game', [
            'date_started' => Medoo::raw('CURRENT_TIMESTAMP(3)'),
            'state' => self::STATE_STARTING,
        ], ['id' => $this->id]);

        $this->state = self::STATE_STARTING;
    }

    public function ready()
    {
        if ($this->state != self::STATE_STARTING) {
            return;
        }

        service()->db->update('game', [
            'date_started' => Medoo::raw('CURRENT_TIMESTAMP(3)'),
            'state' => self::STATE_READY,
        ], ['id' => $this->id]);
        $draft_info = service()->db->get('game', '*', ['id' => $this->id]);

        $this->state = self::STATE_READY;
        $this->date_started = Turn::getTimeInSeconds($draft_info['date_started']);
    }

    public function start()
    {
        if ($this->state != self::STATE_READY) {
            return;
        }

        service()->db->update('game', [
            'date_started' => Medoo::raw('CURRENT_TIMESTAMP(3)'),
            'state' => self::STATE_STARTED,
        ], ['id' => $this->id]);
        $draft_info = service()->db->get('game', '*', ['id' => $this->id]);

        $this->state = self::STATE_STARTED;
        $this->date_started = Turn::getTimeInSeconds($draft_info['date_started']);
    }

    public function finish()
    {
        if ($this->state != self::STATE_STARTED) {
            return;
        }

        service()->db->update('game', ['state' => self::STATE_DONE], ['id' => $this->id]);

        $this->state = self::STATE_DONE;
    }

    public function getDisabledPicks()
    {
        if ($this->player_role == Player::PLAYER_NONE) {
            return [];
        }
        $turn_keys = array_keys($this->getTurns());
        rsort($turn_keys, SORT_NUMERIC);

        $disabled_picks = Turn::DISABLED_EMPTY;

        $flag_skip_hidden_bans = true;
        $flag_skip_hidden_picks = true;

        foreach ($turn_keys as $turn_no) {
            $current_turn = $this->turns[$turn_no];

            if ($current_turn->isRevealBan()) {
                $flag_skip_hidden_bans = false;
            }

            if ($current_turn->isRevealPick()) {
                $flag_skip_hidden_picks = false;
            }

            if (($current_turn->isHiddenBan() && $flag_skip_hidden_bans) ||
                ($current_turn->isHiddenPick() && $flag_skip_hidden_picks)) {
                continue;
            }

            $disabled_by_turn = $current_turn->getDisabledPicks();
            $disabled_picks[Player::PLAYER_1] = array_merge(
                $disabled_picks[Player::PLAYER_1],
                $disabled_by_turn[Player::PLAYER_1]
            );
            $disabled_picks[Player::PLAYER_2] = array_merge(
                $disabled_picks[Player::PLAYER_2],
                $disabled_by_turn[Player::PLAYER_2]
            );
        }

        $disabled_picks[Player::PLAYER_1] = array_unique($disabled_picks[Player::PLAYER_1]);
        $disabled_picks[Player::PLAYER_2] = array_unique($disabled_picks[Player::PLAYER_2]);

        return $disabled_picks;
    }

    public function getDisabledBans()
    {
        if ($this->player_role == Player::PLAYER_NONE) {
            return [];
        }
        $turn_keys = array_keys($this->getTurns());
        rsort($turn_keys, SORT_NUMERIC);

        $disabled_bans = Turn::DISABLED_EMPTY;

        $flag_skip_hidden_bans = true;
        $flag_skip_hidden_picks = true;

        foreach ($turn_keys as $turn_no) {
            $current_turn = $this->turns[$turn_no];

            if ($current_turn->isRevealBan()) {
                $flag_skip_hidden_bans = false;
            }

            if ($current_turn->isRevealPick()) {
                $flag_skip_hidden_picks = false;
            }

            if (($current_turn->isHiddenBan() && $flag_skip_hidden_bans) ||
                ($current_turn->isHiddenPick() && $flag_skip_hidden_picks)) {
                continue;
            }

            $disabled_by_turn = $current_turn->getDisabledBans();

            $disabled_bans[Player::PLAYER_1] = array_merge(
                $disabled_bans[Player::PLAYER_1],
                $disabled_by_turn[Player::PLAYER_1]
            );

            $disabled_bans[Player::PLAYER_2] = array_merge(
                $disabled_bans[Player::PLAYER_2],
                $disabled_by_turn[Player::PLAYER_2]
            );
        }

        $disabled_bans[Player::PLAYER_1] = array_unique($disabled_bans[Player::PLAYER_1]);
        $disabled_bans[Player::PLAYER_2] = array_unique($disabled_bans[Player::PLAYER_2]);

        return $disabled_bans;
    }

    private function insertTurn($turn_no, $civ, $role, $action, $hidden)
    {
        $turn_id = service()->db->insert('turn', [
            'game_id' => $this->id,
            'turn_no' => $turn_no,
            'civ' => $civ,
            'player' => $role,
            'action' => $action,
            'hidden' => $hidden,
            'time_created' => Medoo::raw('CURRENT_TIMESTAMP(3)'),
        ]);
    }

    private function getRandomCiv($disabled_civs)
    {
        $enabled_civs = [];
        for ($i = 1; $i <= $this->getCivCount(); $i++) {
            if (in_array($i, $disabled_civs)) {
                continue;
            }

            $enabled_civs[] = $i;
        }
        if (!empty($enabled_civs)) {
            return $enabled_civs[array_rand($enabled_civs)];
        }

        return Constants::CIV_RANDOM;
    }

    private function isValidTurnNo($turn_no)
    {
        $next_turn = $this->getNextTurn();
        return $next_turn == $turn_no;
    }

    private function isPlayersTurn($preset_role)
    {
        return $this->getPlayerRole() == Player::getEffectivePlayer($preset_role);
    }

    public function addTurn($turn_no, $civ)
    {
        if (!$this->isStarted()) {
            return _("Not started.");
        }

        $next_turn = $this->getNextTurn();
        $preset_turns = $this->getPresetTurns();

        //check if finished already
        if ($next_turn >= count($preset_turns)) {
            return _("Already past last turn.");
        }

        //check if the client knows the correct turn
        if (!$this->isValidTurnNo($turn_no)) {
            return _("Bad turn number.");
        }

        $preset_turn = $preset_turns[$next_turn];

        if (!$this->isPractice()) {
            //check if the player is allowed to do this
            if (!$this->isPlayersTurn($preset_turn['player'])) {
                return _("It's the opponent's turn.");
            }
        }

        if ($civ > $this->getCivCount() || $civ < 0) {
            return _("No such civilization.");
        }

        if (Turn::actionIsAdmin($preset_turn['action'], $preset_turn['player'])) {
            return _("It's not your turn.");
        }

        $preset_effective_player = Player::getEffectivePlayer($preset_turn['player']);

        $disabled_civs = [];
        if (Turn::actionIsPick($preset_turn['action'])) {
            $disabled_civs = $this->getDisabledPicks()[$preset_effective_player];
        } elseif (Turn::actionIsBan($preset_turn['action'])) {
            $disabled_civs = $this->getDisabledBans()[$preset_effective_player];
        }

        //not allowed to change this civ
        if (in_array($civ, $disabled_civs)) {
            return _("This pick/ban is not allowed.");
        }

        if (Turn::actionIsPick($preset_turn['action']) && $civ == Constants::CIV_RANDOM) {
            $civ = $this->getRandomCiv($disabled_civs) + Constants::RANDOM_CHOICE_OFFSET;
        }

        //okay, the addition passed all our tests, time to add
        $this->insertTurn(
            $next_turn,
            $civ,
            $preset_effective_player,
            $preset_turn['action'],
            intval($preset_turn['hidden'])
        );

        //check for reveal and other admin turns
        $this->loadCurrentTurn();
        $this->loadTurns();

        $this->checkForAdminTurns();

        $this->reload();
        return "";
    }

    private function checkForAdminTurns()
    {
        if (!$this->isStarted()) {
            return;
        }

        $next_turn = $this->getNextTurn();
        $preset_turns = $this->getPresetTurns();
        while (true) {
            if ($next_turn >= count($preset_turns)) {
                $this->finish();
                return;
            }

            $preset_turn = $preset_turns[$next_turn];

            if (Turn::actionIsAdmin($preset_turn['action'], $preset_turn['player'])) {
                //add turn
                $civ = Constants::CIV_HIDDEN;
                if (isset($preset_turn['civ'])) {
                    $civ = $preset_turn['civ'];
                }
                $this->insertTurn(
                    $next_turn,
                    $civ,
                    $preset_turn['player'],
                    $preset_turn['action'],
                    intval($preset_turn['hidden'])
                );

                $next_turn += 1;
            } else {
                break;
            }
        }
    }

    public static function getLast($count = 6, $activePreset = true)
    {
        $queryString = '
            SELECT g.id, g.type, g.preset_id, g.aoe_version, g.state, g.code, g.date_started
            FROM game AS g
        ';
        if ($activePreset) {
            $queryString .= sprintf('
                JOIN preset ON preset.id = g.preset_id AND preset.state = %d
            ', Preset::PRESET_ENABLED);
        }
        $queryString .= '
            WHERE (
                (g.state='.self::STATE_STARTED.') ||
                (g.state='.self::STATE_DONE.')
            ) && (g.type & '.self::PRACTICE_MASK.') != '.self::PRACTICE.'
            ORDER BY g.date_started DESC
            LIMIT :count
        ';
        $draft_infos = service()->db->query($queryString, ['count' => $count])->fetchAll();
        $last_drafts = [];
        foreach ($draft_infos as $draft_info) {
            $current_draft = new Draft($draft_info);
            $last_drafts[] = $current_draft;
        }
        return $last_drafts;
    }

    public static function getLastWithPreset(Preset $preset, $count = 6)
    {
        $draft_infos = service()->db->query('
            SELECT *
            FROM game
            WHERE preset_id=:preset
              AND ((state='.self::STATE_STARTED.') || (state='.self::STATE_DONE.'))
              AND (type & '.self::PRACTICE_MASK.') != '.self::PRACTICE.'
            ORDER BY date_started DESC
            LIMIT :count
        ', [
            'preset' => $preset->id,
            'count' => $count,
        ])->fetchAll();
        $last_drafts = [];
        foreach ($draft_infos as $draft_info) {
            $current_draft = new Draft($draft_info);
            $last_drafts[] = $current_draft;
        }
        return $last_drafts;
    }
}
