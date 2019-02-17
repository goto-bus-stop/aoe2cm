<?php

namespace Aoe2CM;

class Preset
{

    const PRESET_DISABLED = 0;
    const PRESET_ENABLED = 1;
    const PRESET_ARCHIVED = 2;

    const DEFAULT_TURNS_1V1 = [
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
    ];

    const DEFAULT_TURNS_2V2 = [
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
    ];

    const DEFAULT_TURNS_3V3 = [
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
    ];

    const DEFAULT_TURNS_4V4 = [
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
        ['player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE],
    ];

    const DEFAULT_TURNS = [
        Draft::TYPE_1V1 => self::DEFAULT_TURNS_1V1,
        Draft::TYPE_2V2 => self::DEFAULT_TURNS_2V2,
        Draft::TYPE_3V3 => self::DEFAULT_TURNS_3V3,
        Draft::TYPE_4V4 => self::DEFAULT_TURNS_4V4,
    ];

    const PRESET_ITEM_TYPE_DESCRIPTION = 0;
    const PRESET_ITEM_TYPE_DRAFT_1V1 = 1;
    const PRESET_ITEM_TYPE_DRAFT_2V2 = 2;
    const PRESET_ITEM_TYPE_DRAFT_3V3 = 3;
    const PRESET_ITEM_TYPE_DRAFT_4V4 = 4;
    const PRESET_ITEM_TYPE_PRE_ACTION = 5;
    const PRESET_ITEM_TYPE_AOE_VERSION = 6;

    //TODO make thse into functions

    const DRAFT_TYPE_PRESETS = [
        Draft::TYPE_1V1 => self::PRESET_ITEM_TYPE_DRAFT_1V1,
        Draft::TYPE_2V2 => self::PRESET_ITEM_TYPE_DRAFT_2V2,
        Draft::TYPE_3V3 => self::PRESET_ITEM_TYPE_DRAFT_3V3,
        Draft::TYPE_4V4 => self::PRESET_ITEM_TYPE_DRAFT_4V4,
    ];

    const PRESET_TO_DRAFT_TYPE = [
        self::PRESET_ITEM_TYPE_DRAFT_1V1 => Draft::TYPE_1V1,
        self::PRESET_ITEM_TYPE_DRAFT_2V2 => Draft::TYPE_2V2,
        self::PRESET_ITEM_TYPE_DRAFT_3V3 => Draft::TYPE_3V3,
        self::PRESET_ITEM_TYPE_DRAFT_4V4 => Draft::TYPE_4V4,
    ];

    public $id = -1;
    public $name = "";
    public $description = "";
    public $state = self::PRESET_DISABLED;
    public $items = [];
    public $turns = self::DEFAULT_TURNS;
    public $pre_actions = [];
    public $type = Draft::TYPE_1V1;
    public $aoe_version = Draft::AOE_VERSION_AOC;

    public function __construct($db_info = null)
    {
        if (is_array($db_info)) {
             $this->loadFromInfo($db_info);
        } elseif (is_numeric($db_info)) {
            $preset_info = service()->db->get('preset', '*', ['id' => $db_info]);
            if (!empty($preset_info)) {
                $this->loadFromInfo($preset_info);
            }
        }
    }

    public static function findWithName($name)
    {
        $preset_db = service()->db->get('preset', '*', ['name[~]' => $name]);
        return new Preset($preset_db);
    }

    public static function findAll()
    {
        $presets_db = service()->db->select('preset', '*', [
            'ORDER' => [
                'state' => 'DESC',
                'name' => 'ASC',
            ],
        ]);
        $ret_array = [];
        if (!empty($presets_db)) {
            foreach ($presets_db as $preset_info) {
                $ret_array[] = new Preset($preset_info);
            }
        }
        return $ret_array;
    }

    public function save()
    {
        if (!$this->exists()) {
            service()->db->insert('preset', [
                'name' => $this->name,
                'state' => self::PRESET_DISABLED,
            ]);
            $this->id = service()->db->id();
        }
        
        service()->db->delete('preset_item', ['preset_id' => $this->id]);
        service()->db->update('preset', [
           'name' => $this->name,
           'state' => $this->state,
        ], ['id' => $this->id]);

        service()->db->insert('preset_item', [
           [
               'preset_id' => $this->id,
               'type' => self::PRESET_ITEM_TYPE_DESCRIPTION,
               'datas' => $this->description,
           ],
           [
               'preset_id' => $this->id,
               'type' => self::DRAFT_TYPE_PRESETS[$this->type],
               'datas [JSON]' => $this->turns[$this->type],
           ],
           [
               'preset_id' => $this->id,
               'type' => self::PRESET_ITEM_TYPE_PRE_ACTION,
               'datas [JSON]' => $this->pre_actions,
           ],
           [
               'preset_id' => $this->id,
               'type' => self::PRESET_ITEM_TYPE_AOE_VERSION,
               'datai' => $this->aoe_version,
           ],
        ]);
    }

    public function delete()
    {
        if (!$this->exists()) {
            return;
        }

        service()->db->delete('preset', ['id' => $this->id]);
    }

    public function setState($state)
    {
        if (!$this->exists()) {
            return;
        }
        $this->state = $state;
        service()->db->update('preset', ['state' => $state], ['id' => $this->id]);
    }

    public function setType($new_type)
    {
        if (!$this->exists()) {
            return;
        }

        if ($new_type != $this->type && $new_type < count($this->turns)) {
            service()->db->delete('preset_item', [
                'preset_id' => $this->id,
                'type' => self::DRAFT_TYPE_PRESETS[$this->type],
            ]);
            service()->db->insert('preset_item', [
                'preset_id' => $this->id,
                'type' => self::DRAFT_TYPE_PRESETS[$new_type],
                'datas [JSON]' => $this->turns[$new_type],
            ]);
            $this->type = $new_type;
        }
    }

    public function setName($name)
    {
        if (!$this->exists() || empty($name)) {
            return;
        }

        service()->db->update('preset', ['name' => $name], ['id' => $this->id]);
        $this->name = $name;
    }

    public function setDescription($description)
    {
        if (!$this->exists()) {
            return;
        }

        service()->db->update('preset_item', [
           'datas' => $description,
        ], [
           'preset_id' => $this->id,
           'type' => self::PRESET_ITEM_TYPE_DESCRIPTION,
        ]);
        $this->description = $description;
    }

    public function setAoeVersion($version)
    {
        if (!$this->exists()) {
            return;
        }

        service()->db->update('preset_item', [
           'datai' => $version,
        ], [
           'preset_id' => $this->id,
           'type' => self::PRESET_ITEM_TYPE_AOE_VERSION,
        ]);
        $this->aoe_version = $version;
    }

    private function saveTurns()
    {
        if (!$this->exists()) {
            return;
        }

        //check if there is such turn
        $preset_turns_db = service()->db->get('preset_item', [
            'preset_id' => $this->id,
            'type' => self::DRAFT_TYPE_PRESETS[$this->type],
        ]);
        if (empty($preset_turns_db)) {
            service()->db->insert('preset_item', [
                'type' => self::DRAFT_TYPE_PRESETS[$this->type],
                'datas [JSON]' => $this->getPresetTurns(),
            ], ['id' => $this->id]);
        } else {
            service()->db->update('preset_item', [
                'type' => self::DRAFT_TYPE_PRESETS[$this->type],
                'datas [JSON]' => $this->getPresetTurns(),
            ], ['id' => $this->id]);
        }
    }

    private function savePreTurns()
    {
        if (!$this->exists()) {
            return;
        }

        //check if there is such turn
        $preset_turns_db = service()->get('preset_item', 'id', [
           'preset_id' => $this->id,
           'type' => self::PRESET_ITEM_TYPE_PRE_ACTION,
        ]);
        if (empty($preset_turns_db)) {
            service()->db->insert('preset_item', [
                'type' => self::PRESET_ITEM_TYPE_PRE_ACTION,
                'datas [JSON]' => $this->pre_actions,
            ]);
        } else {
            service()->db->update('preset_item', [
                'type' => self::PRESET_ITEM_TYPE_PRE_ACTION,
                'datas [JSON]' => $this->pre_actions,
            ], ['preset_id' => $this->id]);
        }
    }

    public function addTurn(Turn $turn)
    {

        if (!$this->exists()) {
            return;
        }

        $preset_turns = $this->getPresetTurns();

        if ($turn->turn_no > count($preset_turns)) {
            return;
        }


        $new_turn = [
            'player' => $turn->player_role,
            'action' => $turn->action,
            'hidden' => $turn->hidden,
        ];
        array_splice($preset_turns, $turn->turn_no, 0, [$new_turn]);
        $this->turns[$this->type] = $preset_turns;
        $this->saveTurns();
    }

    public function setTurnPlayer($index, $player_role)
    {
        if (!$this->exists()) {
            return;
        }
        $preset_turns =$this->getPresetTurns();

        if ($index >= count($preset_turns) || $index < 0) {
            return;
        }

        $preset_turns[$index]['player'] = $player_role;
        $this->turns[$this->type] = $preset_turns;
        $this->saveTurns();
    }

    public function setTurnAction($index, $action)
    {
        if (!$this->exists()) {
            return;
        }
        $preset_turns = $this->getPresetTurns();

        if ($index >= count($preset_turns) || $index < 0) {
            return;
        }

        $preset_turns[$index]['action'] = $action;
        $this->turns[$this->type] = $preset_turns;
        $this->saveTurns();
    }

    public function setTurnHidden($index, $hidden)
    {
        if (!$this->exists()) {
            return;
        }
        $preset_turns = $this->getPresetTurns();

        if ($index >= count($preset_turns) || $index < 0) {
            return;
        }

        $preset_turns[$index]['hidden'] = $hidden;
        $this->turns[$this->type] = $preset_turns;
        $this->saveTurns();
    }

    public function deleteTurn($index)
    {
        if (!$this->exists()) {
            return;
        }
        $preset_turns = $this->getPresetTurns();

        array_splice($preset_turns, $index, 1);
        $this->turns[$this->type] = $preset_turns;
        $this->saveTurns();
    }

    public function addPreTurn(Turn $turn)
    {

        if (!$this->exists()) {
            return;
        }

        $preset_turns = $this->getPresetPreTurns();

        if ($turn->turn_no > count($preset_turns)) {
            return;
        }

        $new_turn = ['player' => $turn->player_role, 'action' => $turn->action, 'civ' => $turn->civ];
        array_splice($preset_turns, $turn->turn_no, 0, [$new_turn]);
        $this->pre_actions = $preset_turns;
        $this->savePreTurns();
    }

    public function setPreTurnPlayer($index, $player_role)
    {
        if (!$this->exists()) {
            return;
        }
        $preset_turns = $this->getPresetPreTurns();

        if ($index >= count($preset_turns) || $index < 0) {
            return;
        }

        $preset_turns[$index]['player'] = $player_role;
        $this->pre_actions = $preset_turns;
        $this->savePreTurns();
    }

    public function setPreTurnAction($index, $action)
    {
        if (!$this->exists()) {
            return;
        }
        $preset_turns = $this->getPresetPreTurns();

        if ($index >= count($preset_turns) || $index < 0) {
            return;
        }

        $preset_turns[$index]['action'] = $action;
        $this->pre_actions = $preset_turns;
        $this->savePreTurns();
    }

    public function setPreTurnCiv($index, $civ)
    {
        if (!$this->exists()) {
            return;
        }
        $preset_turns = $this->getPresetPreTurns();

        if ($index >= count($preset_turns) || $index < 0) {
            return;
        }

        $preset_turns[$index]['civ'] = $civ;
        $this->pre_actions = $preset_turns;
        $this->savePreTurns();
    }

    public function delPreTurn($index)
    {
        if (!$this->exists()) {
            return;
        }
        $preset_turns = $this->getPresetPreTurns();

        array_splice($preset_turns, $index, 1);
        $this->pre_actions = $preset_turns;
        $this->savePreTurns();
    }

    private function loadFromInfo($info)
    {
        $this->id = intval($info['id']);
        $this->name = $info['name'];
        $this->state = intval($info['state']);
        $this->type = Draft::TYPE_1V1;
        $this->loadItems();
    }

    private function loadItems()
    {
        if (empty($this->id)) {
            return;
        }
        $db_items = service()->db->select('preset_item', '*', ['preset_id' => $this->id]);

        foreach ($db_items as $item) {
            $item_type = intval($item['type']);
            if ($item_type == self::PRESET_ITEM_TYPE_DESCRIPTION) {
                $this->description = $item['datas'];
            } elseif ($item_type == self::PRESET_ITEM_TYPE_DRAFT_1V1) {
                $this->turns[Draft::TYPE_1V1] = json_decode($item['datas'], true);
                $this->type = self::PRESET_TO_DRAFT_TYPE[$item_type];
            } elseif ($item_type == self::PRESET_ITEM_TYPE_DRAFT_2V2) {
                $this->turns[Draft::TYPE_2V2] = json_decode($item['datas'], true);
                $this->type = self::PRESET_TO_DRAFT_TYPE[$item_type];
            } elseif ($item_type == self::PRESET_ITEM_TYPE_DRAFT_3V3) {
                $this->turns[Draft::TYPE_3V3] = json_decode($item['datas'], true);
                $this->type = self::PRESET_TO_DRAFT_TYPE[$item_type];
            } elseif ($item_type == self::PRESET_ITEM_TYPE_DRAFT_4V4) {
                $this->turns[Draft::TYPE_4V4] = json_decode($item['datas'], true);
                $this->type = self::PRESET_TO_DRAFT_TYPE[$item_type];
            } elseif ($item_type == self::PRESET_ITEM_TYPE_PRE_ACTION) {
                $this->pre_actions= json_decode($item['datas'], true);
            } elseif ($item_type == self::PRESET_ITEM_TYPE_AOE_VERSION) {
                $this->aoe_version= intval($item['datai']);
            }
        }
    }

    public function getTitle()
    {
        if (empty($this->name)) {
            return Draft::typeGetStr($this->type);
        } else {
            return $this->name;
        }
    }

    public function getTurns($type)
    {
        return $this->turns[$type];
    }

    public function getPresetTurns()
    {
        return $this->turns[$this->type];
    }

    public function getPresetPreTurns()
    {
        return $this->pre_actions;
    }

    public function getAoeVersion()
    {
        return $this->aoe_version;
    }

    public function exists()
    {
        return $this->id > 0;
    }

    public static function find($id)
    {
        return new Preset($id);
    }

    public static function findAllEnabled()
    {
        $db_presets = service()->db->select('preset', ['state' => self::PRESET_ENABLED]);
        $presets = [];
        if (!empty($db_presets)) {
            foreach ($db_presets as $preset_info) {
                $presets[] = new Preset($preset_info);
            }
        }
        return $presets;
    }
}
