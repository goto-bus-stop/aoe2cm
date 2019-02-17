<?php

use Klein\{Request, Response, ServiceProvider};
use Medoo\Medoo;

require_once 'lib/constants.class.php';
require_once 'controllers/draft.class.php';
require_once 'lib/civgrid.class.php';
require_once 'models/draft.class.php';
require_once 'models/player.class.php';
require_once 'models/turn.class.php';

function maintenance() {
    //delete all unfinished games
    require_once "views/header.php";
    echo "<div class=\"content\">";
    echo "Deleting unfinished games... ";
    $result = service()->db->delete('game', [
        'state[!]' => Draft::STATE_DONE,
        'date_started[<]' => Medoo::raw('TIMESTAMPADD(DAY, -1, NOW())'),
    ]);
    echo "{$result->rowCount()}<br />";
    echo "Deleting old demo games... ";
    $result = service()->db->query('
        DELETE FROM game
        WHERE date_started < TIMESTAMPADD(DAY, -1, NOW())
          AND (type & :mask) = :value
    ', ['mask' => Draft::PRACTICE_MASK, 'value' => Draft::PRACTICE]);
    echo "{$result->rowCount()}<br />";
    echo "</div>";

    require_once "views/footer.php";
}

function export_finished() {
    //delete all unfinished games
    $game_infos = service()->db->query('SELECT * FROM game WHERE (state='.Constants::DRAFT_STATE_DONE.') && (type & '.Draft::PRACTICE_MASK.')!='.Draft::PRACTICE.' ORDER BY date_started DESC')->fetchAll();
    $last_games = array();
    $do_strings = Turn::get_do_strings();
    foreach($game_infos as $game_info) {

        $draft = new Draft($game_info);
        $cgrid = new CivGrid($draft->get_aoe_version());
        $civs = $cgrid->get_civs();

        echo $draft->code." - ".$draft->title."\n";
        echo $draft->players[0]->name." vs ".$draft->players[1]->name."\n";
        foreach($draft->get_turns() as $turn) {
            echo $turn->turn_no.": ".$turn->player_role."-".$do_strings[$turn->action]." ".$civs[$turn->civ]."\n";
        }
        echo "\n\n";
    }
}
