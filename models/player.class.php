<?php
declare(strict_types=1);
namespace Aoe2CM;

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

    public function __construct($db_info = null)
    {
        if (is_array($db_info)) {
            $this->loadFromInfo($db_info);
        } elseif (is_numeric($db_info)) {
            $players_db = service()->db->get('user', '*', ['id' => $db_info]);
            if ($player_db) {
                $this->loadFromInfo($player_db);
            }
        }
    }

    private function loadFromInfo($db_info): void
    {
        $this->id = intval($db_info['id']);
        $this->draft_id = intval($db_info['game_id']);
        $this->session_id = $db_info['session_id'];
        $this->role = intval($db_info['role']);
        $this->name = $db_info['name'];
    }

    public function save(): void
    {
        if ($this->id < 0) {
            $player_id = service()->db->insert('user', [
                'game_id' => $this->draft_id,
                'session_id' => $this->session_id,
                'role' => $this->role,
                'name' => $this->name,
            ]);
            $this->id = $player_id;
        } else {
            service()->db->update('user', [
                'game_id' => $this->draft_id,
                'session_id' => $this->session_id,
                'role' => $this->role,
                'name' => $this->name,
            ], ['id' => $this->id]);
        }
    }

    public function setName($name): void
    {
        if ($this->id < 0) {
            return;
        }

        service()->db->update('user', ['name' => $name], ['id' => $this->id]);
        $this->name = $name;
    }

    public static function findDraft(Draft $draft): array
    {
        $players_db = service()->db->select('user', '*', [
            'game_id' => $draft->id,
            'ORDER' => ['role' => 'ASC'],
        ]);

        $players = [];
        foreach ($players_db as $player_info) {
            $player = new Player($player_info);
            $players[] = $player;
        }

        return $players;
    }

    public static function opponent($role): int
    {
        switch ($role) {
            case self::PLAYER_2:
                return self::PLAYER_1;
            case self::PLAYER_1:
                return self::PLAYER_2;
            default:
                return self::PLAYER_NONE;
        }
    }

    public static function isParallel($role): bool
    {
        switch ($role) {
            case self::PLAYER_BOTH_1:
            case self::PLAYER_BOTH_2:
                return true;
            default:
                return false;
        }
    }

    public static function getEffectivePlayer($role): int
    {
        switch ($role) {
            case self::PLAYER_BOTH_1:
                return self::PLAYER_1;
            case self::PLAYER_BOTH_2:
                return self::PLAYER_2;
            default:
                return $role;
        }
    }
}
