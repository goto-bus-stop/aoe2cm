<?php

include_once 'lib/constants.class.php';
include_once 'controllers/draft.class.php';
include_once 'lib/civgrid.class.php';
include_once 'models/draft.class.php';
include_once 'models/player.class.php';
include_once 'models/turn.class.php';

function maintenance() {
	//delete all unfinished games
	include_once "views/header.php";
	echo "<div class=\"content\">";
	echo "Deleting unfinished games... ".getDatabase()->execute('DELETE FROM game WHERE date_started < TIMESTAMPADD(DAY,-1,NOW()) AND state<>:State',
		array(':State' => Draft::STATE_DONE));
	echo "<br />";
	echo "Deleting old demo games... ".getDatabase()->execute('DELETE FROM game WHERE date_started < TIMESTAMPADD(DAY,-1,NOW()) AND (type & :Mask)=:Value',
		array(':Mask' => Draft::PRACTICE_MASK, ':Value' => Draft::PRACTICE));

	echo "</div>";

	include_once "views/footer.php";
}

function export_finished() {
	//delete all unfinished games
	 $game_infos = getDatabase()->all('SELECT * FROM game WHERE (state='.Constants::DRAFT_STATE_DONE.') && (type & '.Draft::PRACTICE_MASK.')!='.Draft::PRACTICE.' ORDER BY date_started DESC');
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

?>