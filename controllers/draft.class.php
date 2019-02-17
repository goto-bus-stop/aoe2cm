<?php
namespace Aoe2CM;

use Klein\Request;
use Klein\Response;
use Klein\ServiceProvider;

class DraftController
{
    public static function test(Request $request, Response $response, ServiceProvider $service)
    {
        $draft = Draft::findWithCode('jXP');

        $turns = $draft->getTurns();

        var_dump($draft->getTurns()[-1]);
        var_dump($draft->getTurns()[-1]->getDisabledPicks());
        var_dump($draft->getDisabledPicks());
    }

    public static function display(Request $request, Response $response, ServiceProvider $service)
    {
        if (!ctype_alnum($request->param('code'))) {
            $response->code(404);

            return;
        }

        $draft = Draft::findWithCode($request->param('code'));
        if (!$draft->exists() || $draft->player_role == Player::PLAYER_NONE) {
            $response->redirect(ROOTDIR.'/spectate?code='.$draft->code);
            return;
        }


        $service->render(__DIR__.'/../views/draft.php', [
            'title' => 'draft',
            'goUp' => true,
            'draft' => $draft,
            'preset_turns' => $draft->getPresetTurns(),
            'preset_pre_turns' => $draft->getPresetPreTurns(),
        ]);
    }

    public static function getStoredPlayerName(Request $request)
    {
        $cookies = $request->cookies();
        if ($cookies->exists('username')) {
            return generate_random_name();
        } else {
            return substr($cookies->get('username'), 0, Player::NAME_LENGTH_LIMIT);
        }
    }

    public static function join(Request $request, Response $response, ServiceProvider $service)
    {
        if (!ctype_alnum($request->param('code'))) {
            return $response->code(400);
        }

        $draft = Draft::findWithCode($request->param('code'));
        if (!$draft->exists()) {
            return $response->code(404);
        }

        //already a known player, redirect
        if ($draft->player_role != Player::PLAYER_NONE) {
            return $response->redirect(ROOTDIR.'/draft?code='.$draft->code);
        }

        $join = true;
        $player_count = count($draft->getPlayers());
        if (!$draft->isWaiting() || $player_count >= 2) {
            $join = false;
        }

        $code = $request->param('code');

        $role = null;
        if ($request->param('role') != null) {
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

    public static function doJoin(Request $request, Response $response, ServiceProvider $service)
    {
        if (!$request->param('code') || !ctype_alnum($request->param('code'))) {
            return $response->code(400);
        }

        $draft = Draft::findWithCode($request->param('code'));
        if (!$draft->exists()) {
            return $response->code(404);
        }

        //already a known player, redirect
        if ($draft->player_role != Player::PLAYER_NONE) {
            return $response->redirect(ROOTDIR.'/draft?code='.$draft->code);
        }

        $player_count = count($draft->getPlayers());
        if (!$draft->isWaiting() || $player_count >= 2) {
            return $response->redirect(ROOTDIR.'/spectate?code='.trim($request->param('code')));
        }

        $new_player = new Player();

        $new_player->draft_id = $draft->id;
        $new_player->name = self::getStoredPlayerName($request);
        $new_player->session_id = session_id();
        $requested_role = Player::PLAYER_1;

        //determine role
        if ($request->param('role') != null) {
            $requested_role = ($request->param('role') == 1)? Player::PLAYER_2 : Player::PLAYER_1;
        }
        if ($player_count > 0 && $draft->players[0]->role == $requested_role) {
            $requested_role = Player::opponent($draft->players[0]->role);
        }

        $new_player->role = $requested_role;
        $new_player->save();
        $player_count += 1;

        if ($player_count >= 2) {
            //start the game
            $draft->starting();
        }

        $response->redirect(ROOTDIR.'/draft?code='.$draft->code);
    }

    public static function create(Request $request, Response $response, ServiceProvider $service)
    {
        $practice = 0;
        $type = Draft::TYPE_1V1;
        $admin_host = false;

        if (is_numeric($request->param('t'))) {
            $type = intval($request->param('t'));
        }

        $preset_id = null;
        if (is_numeric($request->param('p'))) {
            $preset_id = intval($request->param('p'));
        }

        $preset = Preset::find($preset_id);
        if ($preset->exists()) {
            $type = $preset->type;
        }

        if ($request->param('d') == '1') {
            $practice = 1;
            $type |= Draft::PRACTICE;
        }

        if ($request->param('host') == '1') {
            $admin_host = true;
        }

        $draft = Draft::create($type, $preset);

        if (!$draft->exists()) {
            $response->redirect(ROOTDIR.'/?msg=game_error');
            return;
        }

        if ($admin_host) {
            $response->redirect(ROOTDIR.'/spectate?code='.$draft->code);
            return;
        }

        if ($draft->isPractice()) {
            $new_player = new Player();
            $new_player->draft_id = $draft->id;
            $new_player->name = self::getStoredPlayerName($request);
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
            $new_player->name = self::getStoredPlayerName($request);
            $new_player->session_id = session_id();
            $requested_role = Player::PLAYER_1;

            //determine role
            if ($request->param('role') != null) {
                $requested_role = ($request->param('role') == 1)? Player::PLAYER_2 : Player::PLAYER_1;
            }

            $new_player->role = $requested_role;
            $new_player->save();
            $response->redirect(ROOTDIR.'/draft?code='.$draft->code);
        }
    }

    public static function demo(Request $request, Response $response, ServiceProvider $service)
    {
        if (!$request->param('code') || !ctype_alnum($request->param('code'))) {
            $response->code(404);

            return;
        }

        $draft = Draft::findWithCode($request->param('code'));
        if (!$draft->exists() || !$draft->isPractice() || $draft->player_role == Player::PLAYER_NONE) {
            $response->redirect(ROOTDIR.'/spectate?code='.$draft->code);
            return;
        }

        $service->render(__DIR__.'/../views/demo.php', [
            'title' => 'practice',
            'goUp' => true,
            'draft' => $draft,
            'preset_turns' => $draft->getPresetTurns(),
            'preset_pre_turns' => $draft->getPresetPreTurns(),
        ]);
    }

    public static function spectate(Request $request, Response $response, ServiceProvider $service)
    {
        if (!$request->param('code') || !ctype_alnum($request->param('code'))) {
            $response->code(404);

            return;
        }

        $draft = Draft::findWithCode($request->param('code'));

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
            'preset_turns' => $draft->getPresetTurns(),
            'preset_pre_turns' => $draft->getPresetPreTurns(),
        ]);
    }


    public static function getPlayerInfo(Draft $draft)
    {
        $players = $draft->getPlayers();

        $user_index = $draft->getPlayerRole();
        $player_names = [_('Host'), _('Guest')];

        foreach ($players as $player) {
            $player_names[$player->role] = $player->name;
        }

        $info = ['role' => $user_index, 'players' => $player_names];
        if (count($players) != 2) {
            $info['player_count'] = count($players);
        }

        return $info;
    }

    private static function getTurnTime($turn_created, $draft_created)
    {
        return $turn_created - $draft_created - Draft::START_TIMEOUT;
    }

    private static function getReturnTurn(Turn $turn, Draft $draft)
    {
        $info = [
            'no' => $turn->turn_no,
            'civ' => $turn->getCiv(),
            'action' => $turn->getClientAction(),
            'player' => $turn->player_role,
            'disabled_bans' => $turn->getDisabledBans(),
            'disabled_picks' => $turn->getDisabledPicks(),
            'created' => self::getTurnTime($turn->time_created, $draft->date_started),
        ];

        if ($turn->isRandomlyChosen()) {
            $info['civ_random'] = true;
        }

        return $info;
    }

    private static function getHiddenReturnTurn(Turn $turn, Draft $draft)
    {
        $info = self::getReturnTurn($turn, $draft);
        $info['civ'] = Constants::CIV_HIDDEN;
        $info['disabled_bans'] = Turn::DISABLED_EMPTY;
        $info['disabled_picks'] = Turn::DISABLED_EMPTY;
        unset($info['civ_random']);
        return $info;
    }

    private static function getTimeFromStart(Draft $draft)
    {
        $last_updated = $draft->date_started;
        return time() - $last_updated - Draft::START_TIMEOUT;
    }


    private static function getDraftState(Draft $draft, $from_turn)
    {
        $return_turns = [];

        if ($draft->isDone()) {
            $turns = $draft->getTurns();
            foreach ($turns as $turn) {
                $return_turns[] = self::getReturnTurn($turn, $draft);
            }
        } elseif ($from_turn <= $draft->current_turn) {
            //only if there is anything to show filter through the hidden ones

            $turns = $draft->getTurns();
            $flag_hide_bans = true;
            $flag_hide_picks = true;

            $player_role = $draft->getPlayerRole();

            for ($i = $draft->current_turn; $i >= $from_turn; --$i) {
                if (!$turns[$i]) {
                    continue;
                }

                $current_turn = $turns[$i];

                $should_hide = false;
                if ($current_turn->isHidden()) {
                    if ($current_turn->player_role != $player_role &&
                        (($current_turn->isHiddenBan() && $flag_hide_bans) ||
                         ($current_turn->isHiddenPick() && $flag_hide_picks) )) {
                        $should_hide = true;
                    }
                }

                //check for reveals
                if ($current_turn->isRevealBan()) {
                    $flag_hide_bans = false;
                }

                if ($current_turn->isRevealPick()) {
                    $flag_hide_picks = false;
                }

                //now fill the return array
                if ($should_hide) {
                    //just place a dummy there
                    $return_turns[] = self::getHiddenReturnTurn($current_turn, $draft);
                } else {
                    $return_turns[] = self::getReturnTurn($current_turn, $draft);
                }
            }

            //reveal bans backwards if necessary
            if (!$flag_hide_bans) {
                $min_turn = min(array_keys($turns));
                //go backwards until we find a reveal
                for ($i = $from_turn; $i >= $min_turn; --$i) {
                    if (!$turns[$i]) {
                        continue;
                    }
                        //check for reveals
                    if ($turns[$i]->isRevealBan()) {
                        break;
                    }
                    //check for hidden ban
                    if ($turns[$i]->player_role != $player_role && $turns[$i]->isHiddenBan()) {
                        $return_turns[] = self::getReturnTurn($turns[$i], $draft);
                    }
                }
            }

            //reveal picks backwards if necessary
            if (!$flag_hide_picks) {
                $min_turn = min(array_keys($turns));
                //go backwards until we find a reveal
                for ($i = $from_turn; $i >= $min_turn; --$i) {
                    if (!$turns[$i]) {
                        continue;
                    }
                        //check for reveals
                    if ($turns[$i]->isRevealPick()) {
                        break;
                    }
                    //check for hidden pick
                    if ($turns[$i]->player_role != $player_role && $turns[$i]->isHiddenPick()) {
                        $return_turns[] = self::getReturnTurn($turns[$i], $draft);
                    }
                }
            }
        } //end getting turns

        $time_passed = 0;

        if ($draft->isStarted()) {
            if ($draft->current_turn < 0) {
                $time_passed = self::getTimeFromStart($draft);
            } else {
                $last_turn = $draft->getLastTurn();
                if (!empty($last_turn)) {
                    $last_updated = $last_turn->time_created;
                    $time_passed = microtime(true) - $last_updated;
                }

                if ($draft->waitingForParallelPick()) {
                    $previous_to_last_turn = $draft->getPreviousToLastTurn();
                    if (!empty($previous_to_last_turn)) {
                        $last_updated = $previous_to_last_turn->time_created;
                        $time_passed = microtime(true) - $last_updated;
                    } else {
                        $time_passed = self::getTimeFromStart($draft);
                    }
                }
            }
        }

        $next_turn = $draft->getNextTurn();
        $active_player = $draft->getActivePlayer();

        $player_info = self::getPlayerInfo($draft);

        $draft_info = array_merge($player_info, [
            'current_turn' => $next_turn,
            'turns' => $return_turns,
            'state' => $draft->state,
            'time_passed' => $time_passed,
            'active_player' => $active_player,
        ]);

        return $draft_info;
    }

    public static function checkDisconnectError(Draft $draft, array $data)
    {
        $error = false;
        if ($draft->isStarted()) {
            $time_left = Draft::TIMEOUT + Draft::PADDING_TIME - $data['time_passed'];
            if ($time_left < -Draft::DISCONNECT_TIMEOUT) {
                $error = true;
            }
        }

        if ($error) {
            $draft->setState(Draft::STATE_ERROR);
        }
    }

    public static function getState(Request $request, Response $response, ServiceProvider $service)
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

        $return_turns = self::getDraftState($draft, $from_turn);

        //check for incosistency in the data
        self::checkDisconnectError($draft, $return_turns);

        $response->noCache();
        $response->json($return_turns);
    }

    private static function returnState($draft, $response, $from_turn = Turn::TURNS_NO_MIN)
    {
        $return_turns = self::getDraftState($draft, $from_turn);

        $response->noCache();
        $response->json($return_turns);
    }

    public static function draftStart(Request $request, Response $response, ServiceProvider $service)
    {
        if (!is_numeric($request->param('id'))) {
            return $response->code(400);
        }

        $draft = new Draft(intval($request->param('id')));
        if (!$draft->exists()) {
            return $response->code(400);
        }

        //if we are not in ready state, we can't start
        if (!$draft->isReady()) {
            return self::returnState($draft, $response);
        }

        //check if the player is the host
        if ($draft->getPlayerRole() != Player::PLAYER_1) {
            return $response->code(403);
        }

        $draft->start();
        self::returnState($draft, $response);
    }

    public static function draftReady(Request $request, Response $response, ServiceProvider $service)
    {
        if (!is_numeric($request->param('id'))) {
            return $response->code(400);
        }

        $draft = new Draft(intval($request->param('id')));
        if (!$draft->exists()) {
            return $response->code(404);
        }

        if (!$draft->isStarting()) {
            return self::returnState($draft, $response);
        }


        //check if user is allowed to do this
        if ($draft->getPlayerRole() != Player::PLAYER_2) {
            return $response->code(403);
        }

        $draft->ready();

        self::returnState($draft, $response);
    }

    public static function setName(Request $request, Response $response, ServiceProvider $service)
    {
        if (!is_numeric($request->param('draft_id'))) {
            return $response->code(400);
        }

        $draft = new Draft(intval($request->param('draft_id')));
        if (!$draft->exists()) {
            return $response->code(404);
        }


        $player_role = $draft->getPlayerRole();
        if ($player_role != Player::PLAYER_1 && $player_role != Player::PLAYER_2) {
            return $response->code(403);
        }

        $player = $draft->getCurrentPlayer();

        if (!is_null($player)) {
            $name = substr($request->param('name'), 0, Player::NAME_LENGTH_LIMIT);
            $player->setName(htmlentities($name, ENT_QUOTES, 'UTF-8'));
        }

        self::returnState($draft, $response);
    }

    public static function postChoice(Request $request, Response $response, ServiceProvider $service)
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

        $error = $draft->addTurn($turn_no, $civ);

        $return_info = self::getDraftState($draft, $turn_no);
        if (!empty($error)) {
            $return_info['msg'] = $error;
        }
        $response->json($return_info);
    }

    public static function printJsDraftProperties(Draft $draft)
    {
        $turns = $draft->getPresetTurns();
        ?>
<script>
var gblActiveUser = false;
var gblDisabledCivs = []

var TURNS = [ <?php
foreach ($turns as $turn) {
    $player = isset($turn['player']) ? Player::getEffectivePlayer($turn['player']) : Player::PLAYER_NONE;
    $action = isset($turn['action']) ? Turn::actionGetType($turn['action']) : Turn::DO_OTHER;
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
