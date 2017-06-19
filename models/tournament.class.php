<?php

class Tournament {

    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;
    const STATE_ARCHIVED = 2;

    public $id = -1;
    public $state = self::STATE_DISABLED;
    public $name = "";
    public $description = "";
    public $code = "";
    public $html = "";

    public function __construct($db_info = null) {
        if(is_array($db_info)) {
            $this->load_from_info($db_info);
        } elseif(is_numeric($db_info)) {
            $info = getDatabase()->one('SELECT * FROM tournament WHERE id=:Id',
                array(':Id' => $db_info));
            if(!empty($info)) {
                $this->load_from_info($info);
            }
        }
    }

    private function load_from_info($info) {
        $this->id = intval($info['id']);
        $this->name = $info['name'];
        $this->description = intval($info['state']);
        $this->code = $info['code'];
    }

    public function load_data() {
        if(!$this->exists()) {
            return ;
        }

        $data = getDatabase()->one('SELECT * FROM tournament_data WHERE tournament_id=:Id',
            array(':Id' => $this->id));
        if(!empty($data)) {
            $this->html = $data['html'];
        }
    }

    public static function find_all() {
        $tourney_db = getDatabase()->all('SELECT * FROM tournament');
        $ret_array = array();
        if(!empty($tourney_db)) {
            foreach($tourney_db as $tourney_info) {
                $ret_array[] = new Tournament($tourney_info);
            }
        }
        return $ret_array;
    }


    public function save() {
        if(!$this->exists()) {
            $tournament_id = getDatabase()->execute('INSERT INTO tournament (name, state,description,code) VALUES (:Name,:State,:Description,:Code)',
                array(':Name' => $this->name, ':State' => $this->state,
                    ':Description' => $this->description, ':Code' => $this->code));
            $this->id = $tournament_id;

            if($this->exists()) {
                getDatabase()->execute('INSERT INTO tournament_data (tournement_id,html) VALUES (:Id, :Html)',
                    array(':Id' => $this->id, ':Html' => $this->html));
            }

        } else {

            getDatabase()->execute('UPDATE tournament SET name=:Name, state=:State,code=:Code,description=:Description WHERE id=:Id',
                array(':Id' => $this->id,
                        ':State' => $this->state,
                        ':Name' => $this->name,
                        ':Code' => $this->code,
                        ':Description' => $this->description));

             getDatabase()->execute('UPDATE tournament_data SET name=:Name, state=:State,code=:Code,description=:Description WHERE tournament_id=:Id',
                array(':Id' => $this->id,
                        ':Html' => $this->html));
        }
    }

    public function delete() {
        if(!$this->exists()) {
            return;
        }

        getDatabase()->execute('DELETE FROM tournament WHERE id=:Id',
            array(':Id' => $this->id));
    }

    public function exists() {
        return $this->id > 0;
    }

}