<?php


require_once 'models/player.class.php';
require_once 'models/turn.class.php';
require_once 'models/draft.class.php';

class Preset
{

    const PRESET_DISABLED = 0;
    const PRESET_ENABLED = 1;
    const PRESET_ARCHIVED = 2;

    const DEFAULT_TURNS_1V1 = array(
        array('player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE),
        );

    const DEFAULT_TURNS_2V2 = array(
        array('player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE),
        );

    const DEFAULT_TURNS_3V3 = array(
        array('player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE),
        array('player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE)
        );

const DEFAULT_TURNS_4V4 = array(
    array('player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
    array('player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
    array('player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
    array('player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
    array('player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE),
    array('player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE),
    array('player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE),
    array('player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE),
    array('player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
    array('player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
    array('player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE),
    array('player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE),
    array('player' => Player::PLAYER_2, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
    array('player' => Player::PLAYER_1, 'action' => Turn::DO_BAN, 'hidden' => Turn::TURN_VISIBLE),
    array('player' => Player::PLAYER_2, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE),
    array('player' => Player::PLAYER_1, 'action' => Turn::DO_PICK, 'hidden' => Turn::TURN_VISIBLE));


    const DEFAULT_TURNS = array(
        Draft::TYPE_1V1 => self::DEFAULT_TURNS_1V1,
        Draft::TYPE_2V2 => self::DEFAULT_TURNS_2V2,
        Draft::TYPE_3V3 => self::DEFAULT_TURNS_3V3,
        Draft::TYPE_4V4 => self::DEFAULT_TURNS_4V4 );

    const PRESET_ITEM_TYPE_DESCRIPTION = 0;
    const PRESET_ITEM_TYPE_DRAFT_1V1 = 1;
    const PRESET_ITEM_TYPE_DRAFT_2V2 = 2;
    const PRESET_ITEM_TYPE_DRAFT_3V3 = 3;
    const PRESET_ITEM_TYPE_DRAFT_4V4 = 4;
    const PRESET_ITEM_TYPE_PRE_ACTION = 5;
    const PRESET_ITEM_TYPE_AOE_VERSION = 6;

    //TODO make thse into functions

    const DRAFT_TYPE_PRESETS = array(
        Draft::TYPE_1V1 => self::PRESET_ITEM_TYPE_DRAFT_1V1,
        Draft::TYPE_2V2 => self::PRESET_ITEM_TYPE_DRAFT_2V2,
        Draft::TYPE_3V3 => self::PRESET_ITEM_TYPE_DRAFT_3V3,
        Draft::TYPE_4V4 => self::PRESET_ITEM_TYPE_DRAFT_4V4, );

    const PRESET_TO_DRAFT_TYPE = array(
        self::PRESET_ITEM_TYPE_DRAFT_1V1 => Draft::TYPE_1V1,
        self::PRESET_ITEM_TYPE_DRAFT_2V2 => Draft::TYPE_2V2,
        self::PRESET_ITEM_TYPE_DRAFT_3V3 => Draft::TYPE_3V3,
        self::PRESET_ITEM_TYPE_DRAFT_4V4 => Draft::TYPE_4V4, );


    public $id = -1;
    public $name = "";
    public $description = "";
    public $state = self::PRESET_DISABLED;
    public $items = array();
    public $turns = self::DEFAULT_TURNS;
    public $pre_actions = array();
    public $type = Draft::TYPE_1V1;
    public $aoe_version = Draft::AOE_VERSION_AOC;


    public function __construct($db_info = null)
    {
        if ( is_array($db_info) ) {
             $this->load_from_info($db_info);
         }
         else if (is_numeric($db_info))
         {
             $preset_info = service()->db->get('preset', '*', ['id' => $db_info]);
             if(!empty($preset_info)) {
                $this->load_from_info($preset_info);
            }
        }
    }

    public static function find_with_name($name) {
        $preset_db = service()->db->get('preset', '*', ['name[~]' => $name]);
        return new Preset($preset_db);
    }

    public static function find_all() {
        $presets_db = service()->db->select('preset', '*', [
            'ORDER' => [
                'state' => 'DESC',
                'name' => 'ASC',
            ],
        ]);
        $ret_array = array();
        if(!empty($presets_db)) {
            foreach($presets_db as $preset_info) {
                $ret_array[] = new Preset($preset_info);
            }
        }
        return $ret_array;
    }

     public function save() {
        if(!$this->exists()) {
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

    public function delete() {
        if(!$this->exists()) {
            return;
        }

        service()->db->delete('preset', ['id' => $this->id]);
    }

    public function set_state($state) {
        if(!$this->exists()) {
            return;
        }
        $this->state = $state;
        service()->db->update('preset', ['state' => $state], ['id' => $this->id]);
    }

    public function set_type($new_type) {
        if(!$this->exists()) {
            return;
        }

        if($new_type != $this->type && $new_type < count($this->turns)) {
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

    public function set_name($name) {
        if(!$this->exists() || empty($name)) {
            return;
        }

        service()->db->update('preset', ['name' => $name], ['id' => $this->id]);
        $this->name = $name;
    }

     public function set_description($description) {
        if(!$this->exists()) {
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

     public function set_aoe_version($version) {
        if(!$this->exists()) {
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

    private function save_turns() {
        if(!$this->exists()) {
            return;
        }

        //check if there is such turn
        $preset_turns_db = service()->db->get('preset_item', [
            'preset_id' => $this->id,
            'type' => self::DRAFT_TYPE_PRESETS[$this->type],
        ]);
        if(empty($preset_turns_db)) {
            service()->db->insert('preset_item', [
                'type' => self::DRAFT_TYPE_PRESETS[$this->type],
                'datas [JSON]' => $this->get_preset_turns(),
            ], ['id' => $this->id]);
        } else {
            service()->db->update('preset_item', [
                'type' => self::DRAFT_TYPE_PRESETS[$this->type],
                'datas [JSON]' => $this->get_preset_turns(),
            ], ['id' => $this->id]);
        }
    }

     private function save_pre_turns() {
        if(!$this->exists()) {
            return;
        }

        //check if there is such turn
        $preset_turns_db = service()->get('preset_item', 'id', [
            'preset_id' => $this->id,
            'type' => self::PRESET_ITEM_TYPE_PRE_ACTION,
        ]);
        if(empty($preset_turns_db)) {
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

    public function add_turn(Turn $turn) {

        if(!$this->exists()) {
            return;
        }

        $preset_turns = $this->get_preset_turns();

        if($turn->turn_no > count($preset_turns)) {
            return;
        }


        $new_turn = [
            'player' => $turn->player_role,
            'action' => $turn->action,
            'hidden' => $turn->hidden,
        ];
        array_splice($preset_turns, $turn->turn_no, 0, array($new_turn));
        $this->turns[$this->type] = $preset_turns;
        $this->save_turns();
    }

    public function set_turn_player($index, $player_role) {
        if(!$this->exists()) {
            return;
        }
        $preset_turns =$this->get_preset_turns();

        if($index >= count($preset_turns) || $index < 0) {
            return;
        }

        $preset_turns[$index]['player'] = $player_role;
        $this->turns[$this->type] = $preset_turns;
        $this->save_turns();
    }

    public function set_turn_action($index, $action) {
        if(!$this->exists()) {
            return;
        }
        $preset_turns = $this->get_preset_turns();

        if($index >= count($preset_turns) || $index < 0) {
            return;
        }

        $preset_turns[$index]['action'] = $action;
        $this->turns[$this->type] = $preset_turns;
        $this->save_turns();
    }

    public function set_turn_hidden($index, $hidden) {
        if(!$this->exists()) {
            return;
        }
        $preset_turns = $this->get_preset_turns();

        if($index >= count($preset_turns) || $index < 0) {
            return;
        }

        $preset_turns[$index]['hidden'] = $hidden;
        $this->turns[$this->type] = $preset_turns;
        $this->save_turns();
    }

    public function del_turn($index) {
         if(!$this->exists()) {
            return;
        }
        $preset_turns = $this->get_preset_turns();

        array_splice($preset_turns, $index, 1);
        $this->turns[$this->type] = $preset_turns;
        $this->save_turns();
    }

    public function add_pre_turn(Turn $turn) {

        if(!$this->exists()) {
            return;
        }

        $preset_turns = $this->get_preset_pre_turns();

        if($turn->turn_no > count($preset_turns)) {
            return;
        }

        $new_turn = array('player' => $turn->player_role, 'action' => $turn->action, 'civ' => $turn->civ);
        array_splice($preset_turns, $turn->turn_no, 0, array($new_turn));
        $this->pre_actions = $preset_turns;
        $this->save_pre_turns();
    }

     public function set_pre_turn_player($index, $player_role) {
        if(!$this->exists()) {
            return;
        }
        $preset_turns = $this->get_preset_pre_turns();

        if($index >= count($preset_turns) || $index < 0) {
            return;
        }

        $preset_turns[$index]['player'] = $player_role;
        $this->pre_actions = $preset_turns;
        $this->save_pre_turns();
    }

    public function set_pre_turn_action($index, $action) {
        if(!$this->exists()) {
            return;
        }
        $preset_turns = $this->get_preset_pre_turns();

         if($index >= count($preset_turns) || $index < 0) {
            return;
        }

        $preset_turns[$index]['action'] = $action;
        $this->pre_actions = $preset_turns;
        $this->save_pre_turns();
    }

    public function set_pre_turn_civ($index, $civ) {
        if(!$this->exists()) {
            return;
        }
        $preset_turns = $this->get_preset_pre_turns();

        if($index >= count($preset_turns) || $index < 0) {
            return;
        }

        $preset_turns[$index]['civ'] = $civ;
        $this->pre_actions = $preset_turns;
        $this->save_pre_turns();
    }

    public function del_pre_turn($index) {
         if(!$this->exists()) {
            return;
        }
        $preset_turns = $this->get_preset_pre_turns();

        array_splice($preset_turns, $index, 1);
        $this->pre_actions = $preset_turns;
        $this->save_pre_turns();
    }

    private function load_from_info($info) {
        $this->id = intval($info['id']);
        $this->name = $info['name'];
        $this->state = intval($info['state']);
        $this->type = Draft::TYPE_1V1;
        $this->load_items();
    }

    private function load_items() {
        if (empty($this->id)) {
           return;
        }
        $db_items = service()->db->select('preset_item', '*', ['preset_id' => $this->id]);

        foreach ($db_items as $item) {

            $item_type = intval($item['type']);
            if ($item_type == self::PRESET_ITEM_TYPE_DESCRIPTION) {
                $this->description = $item['datas'];

            } else if ($item_type == self::PRESET_ITEM_TYPE_DRAFT_1V1 ) {

                $this->turns[Draft::TYPE_1V1] = json_decode($item['datas'], true);
                $this->type = self::PRESET_TO_DRAFT_TYPE[$item_type];

            } else if ($item_type == self::PRESET_ITEM_TYPE_DRAFT_2V2 ) {

                $this->turns[Draft::TYPE_2V2] = json_decode($item['datas'], true);
                $this->type = self::PRESET_TO_DRAFT_TYPE[$item_type];

            } else if ($item_type == self::PRESET_ITEM_TYPE_DRAFT_3V3 ) {

                $this->turns[Draft::TYPE_3V3] = json_decode($item['datas'], true);
                $this->type = self::PRESET_TO_DRAFT_TYPE[$item_type];

            } else if ($item_type == self::PRESET_ITEM_TYPE_DRAFT_4V4 ) {

                $this->turns[Draft::TYPE_4V4] = json_decode($item['datas'], true);
                $this->type = self::PRESET_TO_DRAFT_TYPE[$item_type];

            }  else if ($item_type == self::PRESET_ITEM_TYPE_PRE_ACTION ) {

                $this->pre_actions= json_decode($item['datas'], true);
            } else if ($item_type == self::PRESET_ITEM_TYPE_AOE_VERSION ) {

                $this->aoe_version= intval($item['datai']);
            }
        }
    }

    public function get_title() {
        if(empty($this->name)) {
            return Draft::type_get_str($this->type);
        } else {
            return $this->name;
        }
    }

    public function get_turns($type) {
        return $this->turns[$type];
    }

    public function get_preset_turns() {
        return $this->turns[$this->type];
    }

    public function get_preset_pre_turns() {
        return $this->pre_actions;
    }

    public function get_aoe_version() {
        return $this->aoe_version;
    }

    public function exists() {
        return $this->id > 0;
    }

    public static function find($id) {
        return new Preset($id);
    }

    public static function find_all_enabled() {
        $db_presets = service()->db->select('preset', ['state' => self::PRESET_ENABLED]);
        $presets = array();
        if(!empty($db_presets)) {
            foreach($db_presets as $preset_info) {
                $presets[] = new Preset($preset_info);
            }
        }
        return $presets;
    }
}
