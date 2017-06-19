<?php

include_once 'models/draft.class.php';
include_once 'models/player.class.php';
include_once 'lib/name_generator.php';

class DraftController
{

    public static function test() {
        $draft = Draft::find_with_code('jXP');

        $turns = $draft->get_turns();

        var_dump($draft->get_turns()[-1]);
        var_dump($draft->get_turns()[-1]->get_disabled_picks());
        var_dump($draft->get_disabled_picks());
    }
    public static function display()
    {
        if (!isset($_REQUEST['code']) || !ctype_alnum($_REQUEST['code'])) {
            header('HTTP/1.0 404 Not Found');

            return;
        }
        
        $params = array();
        $params['body'] = 'draft.php';
        $params['title'] = 'draft';
        $params['goUp'] = true;

        $draft = Draft::find_with_code($_REQUEST['code']);
        if(!$draft->exists() || $draft->player_role == Player::PLAYER_NONE) {
            getRoute()->redirect(ROOTDIR.'/spectate?code='.$draft->code);
            return;
        }

        $params['draft'] = $draft;
        $params['preset_turns'] = $draft->get_preset_turns();
        $params['preset_pre_turns'] = $draft->get_preset_pre_turns();

        getTemplate()->display('baseplate.php', $params);
    }

    public static function player_get_stored_name() {
        if(empty($_COOKIE['username'])) {
            return generate_random_name();
        } else {
            return substr($_COOKIE['username'], 0, Player::NAME_LENGTH_LIMIT);
        }
    }

    public static function join() {
        if(!isset($_REQUEST['code']) || !ctype_alnum($_REQUEST['code'])) {
            header('HTTP/1.0 404 Not Found');
            return;
        }
        
        $params = array();
        $params['body'] = 'join.php';
        $params['title'] = 'Join Game';
        $params['goUp'] = true;
        $params['spectate'] = true;
        $params['join'] = true;
        
        $draft = Draft::find_with_code($_REQUEST['code']);
        if (!$draft->exists()) {
            header('HTTP/1.0 404 Not Found');
            return;
        }
        
        //already a known player, redirect
        if($draft->player_role != Player::PLAYER_NONE) {
            getRoute()->redirect(ROOTDIR.'/draft?code='.$draft->code);
            return;
        }

        $player_count = count($draft->get_players());
        if (!$draft->is_waiting() || $player_count >= 2) {
            $params['join'] = false;
        }

        $params['code'] = $_REQUEST['code'];
        if(isset($_REQUEST['role'])) {
            $params['role'] = $_REQUEST['role'];
        }
        
        $params['draft'] = $draft;

        getTemplate()->display('baseplate.php', $params);
    }

    public static function _join() {
        if(!isset($_REQUEST['code']) || !ctype_alnum($_REQUEST['code'])) {
            header('HTTP/1.0 404 Not Found');
            return;
        }

        $draft = Draft::find_with_code($_REQUEST['code']);
        if (!$draft->exists()) {
            header('HTTP/1.0 404 Not Found');
            return;
        }

        //already a known player, redirect
        if($draft->player_role != Player::PLAYER_NONE) {
            getRoute()->redirect(ROOTDIR.'/draft?code='.$draft->code);
            return;
        }
        
        $player_count = count($draft->get_players());
        if (!$draft->is_waiting() || $player_count >= 2) {
            getRoute()->redirect(ROOTDIR.'/spectate?code='.trim($_REQUEST['code']));
            return;
        }

        $new_player = new Player();

        $new_player->draft_id = $draft->id;
        $new_player->name = self::player_get_stored_name();
        $new_player->session_id = session_id();
        $requested_role = Player::PLAYER_1;

        //determine role
        if(isset($_REQUEST['role'])) {
            $requested_role = ($_REQUEST['role'] == 1)? Player::PLAYER_2 : Player::PLAYER_1;
        }
        if($player_count > 0 && $draft->players[0]->role == $requested_role)
        {
            $requested_role = Player::opponent($draft->players[0]->role);
        }

        $new_player->role = $requested_role;
        $new_player->save();
        $player_count += 1;

        if($player_count >= 2) {
            //start the game
            $draft->starting();
        }

        getRoute()->redirect(ROOTDIR.'/draft?code='.$draft->code);
    }

    public static function create() {

        $hashids = new Hashids\Hashids(Constants::UNIQ_SALT);
        $practice = 0;
        $type = Draft::TYPE_1V1;
        $admin_host = false;

        if(isset($_REQUEST['t']) && is_numeric($_REQUEST['t'])){
            $type = intval($_REQUEST['t']);
        }

        $preset_id = null;
        if(isset($_REQUEST['p']) && is_numeric($_REQUEST['p'])) {
            $preset_id = intval($_REQUEST['p']);
        }

        $preset = Preset::find($preset_id);
        if($preset->exists()) {
            $type = $preset->type;
        }

        if(isset($_REQUEST['d']) && $_REQUEST['d'] == '1'){
            $practice = 1;
            $type |= Draft::PRACTICE;
        }

        if(isset($_REQUEST['host']) && $_REQUEST['host'] == '1') {
            $admin_host = TRUE;
        }


        $draft = Draft::create($type, $preset);

        if(!$draft->exists()) {
            getRoute()->redirect(ROOTDIR.'/?msg=game_error');
            return;
        }

        if($admin_host) {
            getRoute()->redirect(ROOTDIR.'/spectate?code='.$draft->code);
            return;
        }

        if($draft->is_practice()) {
            $new_player = new Player();
            $new_player->draft_id = $draft->id;
            $new_player->name = self::player_get_stored_name();
            $new_player->session_id = session_id();
            $new_player->role = Player::PLAYER_1;
            $new_player->save();

            $demo_player = new Player();
            $demo_player->draft_id = $draft->id;
            $demo_player->name = _('Practice Opponent');
            $demo_player->role = Player::PLAYER_2;
            $demo_player->save();

            $draft->starting();
            $draft->ready();

            getRoute()->redirect(ROOTDIR.'/demo?code='.$draft->code);
        } else {
            //add the player as he requested

            $new_player = new Player();

            $new_player->draft_id = $draft->id;
            $new_player->name = self::player_get_stored_name();
            $new_player->session_id = session_id();
            $requested_role = Player::PLAYER_1;

            //determine role
            if(isset($_REQUEST['role'])) {
                $requested_role = ($_REQUEST['role'] == 1)? Player::PLAYER_2 : Player::PLAYER_1;
            }

            $new_player->role = $requested_role;
            $new_player->save();
            getRoute()->redirect(ROOTDIR.'/draft?code='.$draft->code);
        }
        
    }

    public static function demo()
    {
        if (!isset($_REQUEST['code']) || !ctype_alnum($_REQUEST['code'])) {
            header('HTTP/1.0 404 Not Found');

            return;
        }

        $params = array();
        $params['body'] = 'demo.php';
        $params['title'] = 'practice';
        $params['goUp'] = true;

        $draft = Draft::find_with_code($_REQUEST['code']);
        if(!$draft->exists() || !$draft->is_practice() || $draft->player_role == Player::PLAYER_NONE) {
            getRoute()->redirect(ROOTDIR.'/spectate?code='.$draft->code);
            return;
        }

        $params['draft'] = $draft;
        $params['preset_turns'] = $draft->get_preset_turns();
        $params['preset_pre_turns'] = $draft->get_preset_pre_turns();

        getTemplate()->display('baseplate.php', $params);
    }

    public static function spectate()
    {
        if (!isset($_REQUEST['code']) || !ctype_alnum($_REQUEST['code'])) {
            header('HTTP/1.0 404 Not Found');

            return;
        }

        $params = array();
        $params['body'] = 'spectate.php';
        $params['title'] = _('spectate');
        $params['goUp'] = true;
        $draft = Draft::find_with_code($_REQUEST['code']);
        /*
        if($draft->player_role != Player::PLAYER_NONE) {
            getRoute()->redirect(ROOTDIR.'/draft?code='.$draft->code);
            return;
        }
        */

        $params['draft'] = $draft;
        $params['preset_turns'] = $draft->get_preset_turns();
        $params['preset_pre_turns'] = $draft->get_preset_pre_turns();

        getTemplate()->display('baseplate.php', $params);
    }


    public static function get_player_info($draft)
    {
        $players = $draft->get_players();

        $user_index = $draft->get_player_role();
        $player_names = array(_('Host'), _('Guest'));

        foreach($players as $player) {
            $player_names[$player->role] = $player->name;
        }
        
        $info = array('role' => $user_index, 'players' => $player_names);
        if (count($players) != 2) {
            $info['player_count'] = count($players);
        }

        return $info;
    }

    private static function get_turn_time($turn_created, $draft_created)
    {
        return $turn_created - $draft_created - Draft::START_TIMEOUT;
    }

    private static function get_return_turn(Turn $turn, Draft $draft) {
        $info = array(
            'no' => $turn->turn_no,
            'civ' => $turn->get_civ(),
            'action' => $turn->get_client_action(),
            'player' => $turn->player_role,
            'disabled_bans' => $turn->get_disabled_bans(),
            'disabled_picks' => $turn->get_disabled_picks(),
            'created' => self::get_turn_time($turn->time_created, $draft->date_started));

        if($turn->is_randomly_chosen()) {
            $info['civ_random'] = true;
        }

        return $info;
    }

    private static function get_hidden_return_turn(Turn $turn, Draft $draft) {
        $info = self::get_return_turn($turn, $draft);
        $info['civ'] = Constants::CIV_HIDDEN;
        $info['disabled_bans'] = Turn::DISABLED_EMPTY;
        $info['disabled_picks'] = Turn::DISABLED_EMPTY;
        unset($info['civ_random']);
        return $info;
    }

    private static function get_time_from_start(Draft $draft)
    {
        $last_updated = $draft->date_started;
        return time() - $last_updated - Draft::START_TIMEOUT;
    }


    private static function get_draft_state(Draft $draft, $from_turn)
    {
        $return_turns = array();

        if($draft->is_done()) {
            
            $turns = $draft->get_turns();
            foreach($turns as $turn){
                $return_turns[] = self::get_return_turn($turn, $draft);
            }
        } else if($from_turn <= $draft->current_turn) {
            //only if there is anything to show filter through the hidden ones

            $turns = $draft->get_turns();
            $flag_hide_bans = true;
            $flag_hide_picks = true;

            $player_role = $draft->get_player_role();

            for($i = $draft->current_turn; $i >= $from_turn; --$i) {
                if(!isset($turns[$i])) {
                    continue;
                }

                $current_turn = $turns[$i];

                $should_hide = false;
                if($current_turn->is_hidden()) {
                    if($current_turn->player_role != $player_role &&
                        (($current_turn->is_hidden_ban() && $flag_hide_bans) ||
                         ($current_turn->is_hidden_pick() && $flag_hide_picks) )) {
                        $should_hide = true;
                    }
                }

                //check for reveals
                if($current_turn->is_reveal_ban()) {
                    $flag_hide_bans = false;
                }

                if($current_turn->is_reveal_pick()) {
                    $flag_hide_picks = false;
                }

                //now fill the return array
                if($should_hide) {
                    //just place a dummy there
                    $return_turns[] = self::get_hidden_return_turn($current_turn, $draft);
                } else {
                    $return_turns[] = self::get_return_turn($current_turn, $draft);
                }
            }

            //reveal bans backwards if necessary
            if(!$flag_hide_bans) {
                $min_turn = min(array_keys($turns));
                //go backwards until we find a reveal
                for($i = $from_turn; $i >= $min_turn; --$i) {
                    if(!isset($turns[$i])) {
                        continue;
                    }
                        //check for reveals
                    if($turns[$i]->is_reveal_ban()) {
                        break;
                    }
                    //check for hidden ban
                    if($turns[$i]->player_role != $player_role && $turns[$i]->is_hidden_ban()) {
                        $return_turns[] = self::get_return_turn($turns[$i], $draft);
                    }
                }
            }

            //reveal picks backwards if necessary
            if(!$flag_hide_picks) {
                $min_turn = min(array_keys($turns));
                //go backwards until we find a reveal
                for($i = $from_turn; $i >= $min_turn; --$i) {
                    if(!isset($turns[$i])) {
                        continue;
                    }
                        //check for reveals
                    if($turns[$i]->is_reveal_pick()) {
                        break;
                    }
                    //check for hidden pick
                    if($turns[$i]->player_role != $player_role && $turns[$i]->is_hidden_pick()) {
                        $return_turns[] = self::get_return_turn($turns[$i], $draft);
                    }
                }
            }
        } //end getting turns

        $time_passed = 0;

        if ( $draft->is_started() ) {
            if ($draft->current_turn < 0) {
                $time_passed = self::get_time_from_start($draft);
            } else {
                $last_turn = $draft->get_last_turn();
                if (!empty($last_turn)) {
                    $last_updated = $last_turn->time_created;
                    $time_passed = microtime(true) - $last_updated;
                }

                if($draft->waiting_for_parallel_pick()) {
                    $previous_to_last_turn = $draft->get_previous_to_last_turn();
                    if(!empty($previous_to_last_turn)) {
                        $last_updated = $previous_to_last_turn->time_created;
                        $time_passed = microtime(true) - $last_updated;
                    } else {
                        $time_passed = self::get_time_from_start($draft);
                    }
                }
            }
        }

        $next_turn = $draft->get_next_turn();
        $active_player = $draft->get_active_player();

        $player_info = self::get_player_info($draft);

        $draft_info = array_merge($player_info, array('current_turn' => $next_turn, 'turns' => $return_turns, 'state' => $draft->state, 'time_passed' => $time_passed,
        'active_player' => $active_player));
        
        return $draft_info;
    }

    public static function check_disconnect_error($draft, $data) {
        $error = false;
        if( $draft->is_started() ) {

            $time_left = Draft::TIMEOUT + Draft::PADDING_TIME - $data['time_passed'];
            if($time_left < -Draft::DISCONNECT_TIMEOUT) {
                $error = true;
            }
        }

        if($error) {
            $draft->set_state(Draft::STATE_ERROR);
        }
    }

    public static function get_state()
    {
        if (!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id'])) {
            header('HTTP/1.0 404 Not Found');
            return;
        }

        $from_turn = Turn::TURNS_NO_MIN;
        if (isset($_REQUEST['turn'])) {
            $from_turn = intval($_REQUEST['turn']);
        }

        $draft = new Draft(intval($_REQUEST['id']));
        if (!$draft->exists()) {
            header('HTTP/1.0 404 Not Found');
        }

        $return_turns = self::get_draft_state($draft, $from_turn);

        //check for incosistency in the data
        self::check_disconnect_error($draft, $return_turns);

        header('Content-Type: application/json');

        header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past		

        print json_encode($return_turns);
    }

    private static function return_state($draft, $from_turn = Turn::TURNS_NO_MIN)
    {
        $return_turns = self::get_draft_state($draft, $from_turn);
        header('Content-Type: application/json');

        header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past		

        print json_encode($return_turns);
    }

    public static function draft_start()
    {
        if (!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id'])) {
            header('HTTP/1.0 404 Not Found');

            return;
        }

        $draft = new Draft(intval($_REQUEST['id']));
        if (!$draft->exists()) {
            header('HTTP/1.0 404 Not Found');
        }

        //if we are not in ready state, we can't start
        if (!$draft->is_ready()) {
            self::return_state($draft);
            return;
        }

        //check if the player is the host
        if ( $draft->get_player_role() != Player::PLAYER_1) {
            header('HTTP/1.0 404 Not Found');
            return;
        }

        $draft->start();
        self::return_state($draft);
    }

    public static function draft_ready()
    {
        if (!isset($_REQUEST['id']) || !is_numeric($_REQUEST['id'])) {
            header('HTTP/1.0 404 Not Found');

            return;
        }

        $draft = new Draft(intval($_REQUEST['id']));
        if (!$draft->exists()) {
            header('HTTP/1.0 404 Not Found');
        }

        if (!$draft->is_starting()) {
            self::return_state($draft);
            return;
        }

        
        //check if user is allowed to do this
        if ($draft->get_player_role() != Player::PLAYER_2) {
            header('HTTP/1.0 404 Not Found');
            return;
        }

        $draft->ready();

        self::return_state($draft);
    }

    public static function set_name()
    {
        if (!isset($_REQUEST['draft_id']) || !is_numeric($_REQUEST['draft_id'])) {
            header('HTTP/1.0 404 Not Found');
            return;
        }

        $draft = new Draft(intval($_REQUEST['draft_id']));
        if (!$draft->exists()) {
            header('HTTP/1.0 404 Not Found');
            return;
        }


        $player_role = $draft->get_player_role();
        if ($player_role != Player::PLAYER_1 && $player_role != Player::PLAYER_2) {
            header('HTTP/1.0 404 Not Found');
            return;
        }

        $player = $draft->get_current_player();

        if(!is_null($player)) {
            $player->set_name(htmlentities(substr($_REQUEST['name'], 0, Player::NAME_LENGTH_LIMIT), ENT_QUOTES, 'UTF-8'));
        }

        self::return_state($draft);
    }

    public static function post_choice()
    {
        if (!isset($_REQUEST['draft_id']) || !is_numeric($_REQUEST['draft_id']) ||
            !isset($_REQUEST['turn_no']) || !is_numeric($_REQUEST['turn_no']) ||
                !isset($_REQUEST['civ']) || !is_numeric($_REQUEST['civ'])) {
            header('HTTP/1.0 404 Not Found');

            return;
        }

        $turn_no = intval($_REQUEST['turn_no']);
        $draft_id = intval($_REQUEST['draft_id']);
        $civ = intval($_REQUEST['civ']);
        
        $draft = new Draft(intval($draft_id));
        if (!$draft->exists()) {
            header('HTTP/1.0 404 Not Found');
        }
        
        $error = $draft->add_turn($turn_no, $civ);

        $return_info = self::get_draft_state($draft, $turn_no);
        if(!empty($error)) {
            $return_info['msg'] = $error;
        }
        header('Content-Type: application/json');
        print json_encode($return_info);
    }

    public static function js_draft_properties(Draft $draft) {
        $turns = $draft->get_preset_turns();
?>
<script>
var gblActiveUser = false;
var gblDisabledCivs = []

var TURNS = [ <?php
    foreach($turns as $turn) {
        $player = isset($turn['player']) ? Player::get_effective_player($turn['player']) : Player::PLAYER_NONE;
        $action = isset($turn['action']) ? Turn::action_get_type($turn['action']) : Turn::DO_OTHER;
        $hidden = isset($turn['hidden']) ? $turn['hidden'] : Turn::TURN_VISIBLE;
        echo "{ \"player\": ".$player.", \"action\": ".$action.", \"hidden\": ".$hidden." },\n";
    }
    ?> ];

var DRAFT_ID = <?php echo $draft->id; ?>;
var DRAFT_CODE = "<?php echo $draft->code; ?>";
var DRAFT_PRACTICE = true;
</script>
<?php
    }
}
