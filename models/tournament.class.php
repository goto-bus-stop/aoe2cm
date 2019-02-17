<?php
declare(strict_types=1);
namespace Aoe2CM;

class Tournament
{
    const STATE_DISABLED = 0;
    const STATE_ENABLED = 1;
    const STATE_ARCHIVED = 2;

    public $id = -1;
    public $state = self::STATE_DISABLED;
    public $name = "";
    public $description = "";
    public $code = "";
    public $html = "";

    public function __construct($db_info = null)
    {
        if (is_array($db_info)) {
            $this->loadFromInfo($db_info);
        } elseif (is_numeric($db_info)) {
            $info = service()->db->get('tournament', '*', ['id' => $db_info]);
            if (!empty($info)) {
                $this->loadFromInfo($info);
            }
        }
    }

    private function loadFromInfo($info): void
    {
        $this->id = intval($info['id']);
        $this->name = $info['name'];
        $this->description = intval($info['state']);
        $this->code = $info['code'];
    }

    public function loadData(): void
    {
        if (!$this->exists()) {
            return;
        }

        $data = service()->db->get('tournament_data', '*', ['tournament_id' => $this->id]);
        if (!empty($data)) {
            $this->html = $data['html'];
        }
    }

    public static function findAll(): array
    {
        $tourney_db = service()->db->select('tournament', '*');
        $ret_array = [];
        if (!empty($tourney_db)) {
            foreach ($tourney_db as $tourney_info) {
                $ret_array[] = new Tournament($tourney_info);
            }
        }
        return $ret_array;
    }

    public function save(): void
    {
        if (!$this->exists()) {
            service()->db->insert('tournament', [
                'name' => $this->name,
                'state' => $this->state,
                'description' => $this->description,
                'code' => $this->code,
            ]);
            $this->id = service()->db->id();

            if ($this->exists()) {
                service()->db->insert('tournament_data', [
                    'tournement_id' => $this->id,
                    'html' => $this->html,
                ]);
            }
        } else {
            service()->db->update('tournament', [
                'name' => $this->name,
                'state' => $this->state,
                'code' => $this->code,
                'description' => $this->description,
            ], ['id' => $this->id]);
            service()->db->update('tournament_data', [
                'html' => $this->html,
            ], ['tournament_id' => $this->id]);
        }
    }

    public function delete(): void
    {
        if (!$this->exists()) {
            return;
        }

        service()->db->delete('tournament', ['id' => $this->id]);
    }

    public function exists(): bool
    {
        return $this->id > 0;
    }
}
