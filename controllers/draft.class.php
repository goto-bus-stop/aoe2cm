<?php

require_once __DIR__.'/../models/draft.class.php';
require_once __DIR__.'/../models/player.class.php';
require_once __DIR__.'/../lib/name_generator.php';

class DraftController
{
  public static function test()
  {
        $draft = Draft::find_with_code('jXP');

        $turns = $draft->get_turns();

        var_dump($draft->get_turns()[-1]);
        var_dump($draft->get_turns()[-1]->get_disabled_picks());
        var_dump($draft->get_disabled_picks());
    }

    public static function display($request, $response, $service)
    {
        if (!ctype_alnum($request->param('code'))) {
            $response->code(404);

            return;
        }

        $draft = Draft::find_with_code($request->param('code'));
        if(!$draft->exists() || $draft->player_role == Player::PLAYER_NONE) {
            $response->redirect(ROOTDIR.'/spectate?code='.$draft->code);
            return;
        }


        $service->render(__DIR__.'/../views/draft.php', [
            'title' => 'draft',
            'goUp' => true,
            'draft' => $draft,
            'preset_turns' => $draft->get_preset_turns(),
            'preset_pre_turns' => $draft->get_preset_pre_turns(),
        ]);
    }

    public static function player_get_stored_name($request) {
        $cookies = $request->cookies();
        if($cookies->exists('username')) {
            return generate_random_name();
        } else {
            return substr($cookies->get('username'), 0, Player::NAME_LENGTH_LIMIT);
        }
    }

    public static function join($request, $response, $service) {
        if(!ctype_alnum($request->param('code'))) {
            $response->code(404);
            return;
        }

        $draft = Draft::find_with_code($request->param('code'));
        if (!$draft->exists()) {
            $response->code(404);
            return;
        }

        //already a known player, redirect
        if($draft->player_role != Player::PLAYER_NONE) {
            $response->redirect(ROOTDIR.'/draft?code='.$draft->code);
            return;
        }

        $join = true;
        $player_count = count($draft->get_players());
        if (!$draft->is_waiting() || $player_count >= 2) {
            $join = false;
        }

        $code = $request->param('code');

        $role = null;
        if($request->param('role') != null) {
            $role = $request->param('role');
        }

        $service->render(__DIR__.'/../views/join.php', [
            'title' => 'Join Game',
            'goUp' => true,
            'spectate' => true,
            'draft' => $draft,
            'join' => $join,
            'code' => $code,
            'role' => $role,
        ]);
    }

    public static function _join($request, $response, $service) {
        if(!$request->param('code') || !ctype_alnum($request->param('code'))) {
            return $response->code(404);
        }

        $draft = Draft::find_with_code($request->param('code'));
        if (!$draft->exists()) {
            return $response->code(404);
        }

        //already a known player, redirect
        if($draft->player_role != Player::PLAYER_NONE) {
            return $response->redirect(ROOTDIR.'/draft?code='.$draft->code);
        }

        $player_count = count($draft->get_players());
        if (!$draft->is_waiting() || $player_count >= 2) {
            return $response->redirect(ROOTDIR.'/spectate?code='.trim($request->param('code')));
        }

        $new_player = new Player();

        $new_player->draft_id = $draft->id;
        $new_player->name = self::player_get_stored_name($request);
        $new_player->session_id = session_id();
        $requested_role = Player::PLAYER_1;

        //determine role
        if($request->param('role') != null) {
            $requested_role = ($request->param('role') == 1)? Player::PLAYER_2 : Player::PLAYER_1;
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

        $response->redirect(ROOTDIR.'/draft?code='.$draft->code);
    }

    public static function create($request, $response, $service) {
        $practice = 0;
        $type = Draft::TYPE_1V1;
        $admin_host = false;

        if(is_numeric($request->param('t'))){
            $type = intval($request->param('t'));
        }

        $preset_id = null;
        if(is_numeric($request->param('p'))) {
            $preset_id = intval($request->param('p'));
        }

        $preset = Preset::find($preset_id);
        if($preset->exists()) {
            $type = $preset->type;
        }

        if($request->param('d') == '1'){
            $practice = 1;
            $type |= Draft::PRACTICE;
        }

        if($request->param('host') == '1') {
            $admin_host = TRUE;
        }

        $draft = Draft::create($type, $preset);

        if(!$draft->exists()) {
            $response->redirect(ROOTDIR.'/?msg=game_error');
            return;
        }

        if($admin_host) {
            $response->redirect(ROOTDIR.'/spectate?code='.$draft->code);
            return;
        }

        if($draft->is_practice()) {
            $new_player = new Player();
            $new_player->draft_id = $draft->id;
            $new_player->name = self::player_get_stored_name($request);
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

            $response->redirect(ROOTDIR.'/demo?code='.$draft->code);
        } else {
            //add the player as he requested

            $new_player = new Player();

            $new_player->draft_id = $draft->id;
            $new_player->name = self::player_get_stored_name($request);
            $new_player->session_id = session_id();
            $requested_role = Player::PLAYER_1;

            //determine role
            if($request->param('role') != null) {
                $requested_role = ($request->param('role') == 1)? Player::PLAYER_2 : Player::PLAYER_1;
            }

            $new_player->role = $requested_role;
            $new_player->save();
            $response->redirect(ROOTDIR.'/draft?code='.$draft->code);
        }
    }

    public static function demo($request, $response, $service)
    {
        if (!$request->param('code') || !ctype_alnum($request->param('code'))) {
            $response->code(404);

            return;
        }

        $draft = Draft::find_with_code($request->param('code'));
        if(!$draft->exists() || !$draft->is_practice() || $draft->player_role == Player::PLAYER_NONE) {
            $response->redirect(ROOTDIR.'/spectate?code='.$draft->code);
            return;
        }

        $service->render(__DIR__.'/../views/demo.php', [
            'title' => 'practice',
            'goUp' => true,
            'draft' => $draft,
            'preset_turns' => $draft->get_preset_turns(),
            'preset_pre_turns' => $draft->get_preset_pre_turns(),
        ]);
    }

    public static function spectate($request, $response, $service)
    {
        if (!$request->param('code') || !ctype_alnum($request->param('code'))) {
            $response->code(404);

            return;
        }

        $draft = Draft::find_with_code($request->param('code'));

        /*
        if($draft->player_role != Player::PLAYER_NONE) {
            $response->redirect(ROOTDIR.'/draft?code='.$draft->code);
            return;
        }
        */

        $service->render(__DIR__.'/../views/spectate.php', [
            'title' => _('spectate'),
            'goUp' => true,
            'draft' => $draft,
            'preset_turns' => $draft->get_preset_turns(),
            'preset_pre_turns' => $draft->get_preset_pre_turns(),
        ]);
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
        $info = [
            'no' => $turn->turn_no,
            'civ' => $turn->get_civ(),
            'action' => $turn->get_client_action(),
            'player' => $turn->player_role,
            'disabled_bans' => $turn->get_disabled_bans(),
            'disabled_picks' => $turn->get_disabled_picks(),
            'created' => self::get_turn_time($turn->time_created, $draft->date_started),
        ];

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
        $return_turns = [];

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
                if(!$turns[$i]) {
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
                    if(!$turns[$i]) {
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
                    if(!$turns[$i]) {
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

    public static function get_state($request, $response, $service)
    {
        if (!is_numeric($request->param('id'))) {
            return $response->code(404)->json(null);
        }

        $from_turn = Turn::TURNS_NO_MIN;
        if ($request->param('turn')) {
            $from_turn = intval($request->param('turn'));
        }

        $draft = new Draft(intval($request->param('id')));
        if (!$draft->exists()) {
            return $response->code(404)->json(null);
        }

        $return_turns = self::get_draft_state($draft, $from_turn);

        //check for incosistency in the data
        self::check_disconnect_error($draft, $return_turns);

        $response->noCache();
        $response->json($return_turns);
    }

    private static function return_state($draft, $response, $from_turn = Turn::TURNS_NO_MIN)
    {
        $return_turns = self::get_draft_state($draft, $from_turn);

        $response->noCache();
        $response->json($return_turns);
    }

    public static function draft_start($request, $response, $service)
    {
        if (!is_numeric($request->param('id'))) {
            $response->code(404);

            return;
        }

        $draft = new Draft(intval($request->param('id')));
        if (!$draft->exists()) {
            $response->code(404);
        }

        //if we are not in ready state, we can't start
        if (!$draft->is_ready()) {
            self::return_state($draft, $response);
            return;
        }

        //check if the player is the host
        if ( $draft->get_player_role() != Player::PLAYER_1) {
            $response->code(404);
            return;
        }

        $draft->start();
        self::return_state($draft, $response);
    }

    public static function draft_ready($request, $response, $service)
    {
        if (!is_numeric($request->param('id'))) {
            $response->code(404);

            return;
        }

        $draft = new Draft(intval($request->param('id')));
        if (!$draft->exists()) {
            $response->code(404);
        }

        if (!$draft->is_starting()) {
            self::return_state($draft, $response);
            return;
        }


        //check if user is allowed to do this
        if ($draft->get_player_role() != Player::PLAYER_2) {
            $response->code(404);
            return;
        }

        $draft->ready();

        self::return_state($draft, $response);
    }

    public static function set_name($request, $response, $service)
    {
        if (!is_numeric($request->param('draft_id'))) {
            $response->code(404);
            return;
        }

        $draft = new Draft(intval($request->param('draft_id')));
        if (!$draft->exists()) {
            $response->code(404);
            return;
        }


        $player_role = $draft->get_player_role();
        if ($player_role != Player::PLAYER_1 && $player_role != Player::PLAYER_2) {
            $response->code(404);
            return;
        }

        $player = $draft->get_current_player();

        if(!is_null($player)) {
            $player->set_name(htmlentities(substr($request->param('name'), 0, Player::NAME_LENGTH_LIMIT), ENT_QUOTES, 'UTF-8'));
        }

        self::return_state($draft, $response);
    }

    public static function post_choice($request, $response, $service)
    {
        if (!is_numeric($request->param('draft_id')) ||
            !is_numeric($request->param('turn_no')) ||
            !is_numeric($request->param('civ'))) {
            return $response->code(400)->json(null);
        }

        $turn_no = intval($request->param('turn_no'));
        $draft_id = intval($request->param('draft_id'));
        $civ = intval($request->param('civ'));

        $draft = new Draft(intval($draft_id));
        if (!$draft->exists()) {
            return $response->code(404)->json(null);
        }

        $error = $draft->add_turn($turn_no, $civ);

        $return_info = self::get_draft_state($draft, $turn_no);
        if(!empty($error)) {
            $return_info['msg'] = $error;
        }
        $response->json($return_info);
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
