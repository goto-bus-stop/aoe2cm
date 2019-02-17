<?php
namespace Aoe2CM;

class TurnsGrid
{
    private $aoe_version;

    public function __construct($aoe_version = Draft::AOE_VERSION_AOC)
    {
        $this->aoe_version = $aoe_version;
    }

    private function countActions($preset_turns)
    {
        $picks = [Player::PLAYER_1 => 0, Player::PLAYER_2 => 0];
        $bans = [Player::PLAYER_1 => 0, Player::PLAYER_2 => 0];
        if (is_array($preset_turns)) {
            foreach ($preset_turns as $turn) {
                if (Turn::actionIsPick($turn['action'])) {
                    if ($turn['player'] != Player::PLAYER_NONE) {
                        $picks[Player::getEffectivePlayer($turn['player'])] += 1;
                    }
                } elseif (Turn::actionIsBan($turn['action'])) {
                    if ($turn['player'] != Player::PLAYER_NONE) {
                        $bans[Player::getEffectivePlayer($turn['player'])] += 1;
                    } elseif ($turn['action'] == Turn::DO_GLOBAL_BAN) {
                        $bans[Player::PLAYER_1] += 1;
                        $bans[Player::PLAYER_2] += 1;
                    }
                }
            }
        }

        return [$picks, $bans];
    }

    private function getElementIndexes(
        $preset_pre_turns,
        $pre_pick_count,
        $pre_ban_count,
        $preset_turns,
        $pick_count,
        $ban_count
    ) {
        $player_1_pick_count = $pick_count[Player::PLAYER_1] + $pre_pick_count[Player::PLAYER_1];
        $player_2_pick_count = $pick_count[Player::PLAYER_2] + $pre_pick_count[Player::PLAYER_2];
        $player_1_ban_count = $ban_count[Player::PLAYER_1] + $pre_ban_count[Player::PLAYER_1];
        $player_2_ban_count = $ban_count[Player::PLAYER_2] + $pre_ban_count[Player::PLAYER_2];

        $player_1_pick_offset = 0;
        $player_1_ban_offset = $player_1_pick_offset + $player_1_pick_count;
        $player_2_pick_offset = $player_1_ban_offset + $player_1_ban_count;
        $player_2_ban_offset = $player_2_pick_offset + $player_2_pick_count;

        $player_1_picks = 0;
        $player_1_bans = 0;
        $player_2_picks = 0;
        $player_2_bans = 0;

        $return_indexes = [];

        for ($i = -count($preset_pre_turns); $i < 0; ++$i) {
            $current_turn = $preset_pre_turns[$i + count($preset_pre_turns)];
            if ($current_turn['player'] == Player::PLAYER_1 || $current_turn['player'] == Player::PLAYER_BOTH_1) {
                if (Turn::actionIsPick($current_turn['action'])) {
                    $return_indexes[$player_1_pick_offset + $player_1_picks] = $i;
                    $player_1_picks += 1;
                } elseif (Turn::actionIsBan($current_turn['action'])) {
                    $return_indexes[$player_1_ban_offset + $player_1_bans] = $i;
                    $player_1_bans += 1;
                }
            } elseif ($current_turn['player'] == Player::PLAYER_2 || $current_turn['player'] == Player::PLAYER_BOTH_1) {
                if (Turn::actionIsPick($current_turn['action'])) {
                    $return_indexes[$player_2_pick_offset + $player_2_picks] = $i;
                    $player_2_picks += 1;
                } elseif (Turn::actionIsBan($current_turn['action'])) {
                    $return_indexes[$player_2_ban_offset + $player_2_bans] = $i;
                    $player_2_bans += 1;
                }
            } else {
                if ($current_turn['action'] == Turn::DO_GLOBAL_BAN) {
                    $return_indexes[$player_1_ban_offset + $player_1_bans] = $i;
                    $player_1_bans += 1;
                    $return_indexes[$player_2_ban_offset + $player_2_bans] = $i;
                    $player_2_bans += 1;
                }
            }
        }

        for ($i = 0; $i < count($preset_turns); ++$i) {
            $current_turn = $preset_turns[$i];
            if ($current_turn['player'] == Player::PLAYER_1 || $current_turn['player'] == Player::PLAYER_BOTH_1) {
                if (Turn::actionIsPick($current_turn['action'])) {
                    $return_indexes[$player_1_pick_offset + $player_1_picks] = $i;
                    $player_1_picks += 1;
                } elseif (Turn::actionIsBan($current_turn['action'])) {
                    $return_indexes[$player_1_ban_offset + $player_1_bans] = $i;
                    $player_1_bans += 1;
                }
            } elseif ($current_turn['player'] == Player::PLAYER_2 || $current_turn['player'] == Player::PLAYER_BOTH_2) {
                if (Turn::actionIsPick($current_turn['action'])) {
                    $return_indexes[$player_2_pick_offset + $player_2_picks] = $i;
                    $player_2_picks += 1;
                } elseif (Turn::actionIsBan($current_turn['action'])) {
                    $return_indexes[$player_2_ban_offset + $player_2_bans] = $i;
                    $player_2_bans += 1;
                }
            } else {
                if ($current_turn['action'] == Turn::DO_GLOBAL_BAN) {
                    $return_indexes[$player_1_ban_offset + $player_1_bans] = $i;
                    $player_1_bans += 1;
                    $return_indexes[$player_2_ban_offset + $player_2_bans] = $i;
                    $player_2_bans += 1;
                }
            }
        }

        return $return_indexes;
    }

    private function getPlayerSummary($preset_turns, $player)
    {
        $bans = 0;
        $picks = 0;
        $index = 0;
        $counter = 0;
        $prev_do = 0;
        $do_strings = Turn::getDoStrings();
        for (; $index < count($preset_turns); ++$index) {
            if (Player::getEffectivePlayer($preset_turns[$index]['player']) != $player) {
                continue;
            }
            if ($counter == 0) { //first hit
                $prev_do = $preset_turns[$index]['action'];
                $counter = 1;
                continue;
            }
            if ($prev_do != $preset_turns[$index]['action']) {
                ?>
                <span class="turn-summary-group"><?php echo $counter.' '.$do_strings[$prev_do].' '; ?></span>
                <?php
                if (Turn::actionIsPick($prev_do)) {
                    $picks += $counter;
                } elseif (Turn::actionIsBan($prev_do)) {
                    $bans += $counter;
                }
                $prev_do = $preset_turns[$index]['action'];
                $counter = 0;
            }
            ++$counter;
        }

        if ($counter > 0) {
            ?>
            <span class="turn-summary-group"><?php echo $counter.' '.$do_strings[$prev_do].' '; ?></span>
            <?php
            if (Turn::actionIsPick($prev_do)) {
                $picks += $counter;
            } elseif (Turn::actionIsBan($prev_do)) {
                $bans += $counter;
            }
            ?>
            (<span class="turn-summary-group">
                <?= $bans ?> <?= $do_strings[TURN::DO_BAN] ?>
                <?= $picks ?> <?= $do_strings[TURN::DO_PICK] ?>
            </span>)
            <?php
        }
    }

    private function printPlayerChooser($class, $id, $player)
    {
        ?>
        <select class="<?php echo $class; ?>" data-id="<?php echo $id; ?>">
            <option value="<?php echo Player::PLAYER_1; ?>"
                <?php if ($player == Player::PLAYER_1) { ?>
                    selected
                <?php } ?>
            >
                <?php echo _("Player"); ?> 1
            </option>
            <option value="<?php echo Player::PLAYER_2; ?>"
                <?php if ($player == Player::PLAYER_2) { ?>
                    selected
                <?php } ?>
            >
                <?php echo _("Player"); ?> 2
            </option>
            <option value="<?php echo Player::PLAYER_BOTH_1; ?>"
                <?php if ($player == Player::PLAYER_BOTH_1) { ?>
                    selected
                <?php } ?>
            >
                <?php echo _("Player Both"); ?>1
            </option>
            <option value="<?php echo Player::PLAYER_BOTH_2; ?>"
                <?php if ($player == Player::PLAYER_BOTH_2) { ?>
                    selected
                <?php } ?>
            >
                <?php echo _("Player Both"); ?>2
            </option>
            <option value="<?php echo Player::PLAYER_NONE; ?>"
                <?php if ($player == Player::PLAYER_NONE) { ?>
                    selected
                <?php } ?>
            >
                <?php echo _("None"); ?>
            </option>
        </select>
        <?php
    }

    private function printActionChooser($class, $id, $action)
    {
        ?>
        <select class="<?php echo $class; ?>" data-id="<?php echo $id; ?>">
        <?php foreach (Turn::getFancyDoStrings() as $action_type => $action_string) { ?>
            <option value="{$action_type}"
                <?php if ($action_type == $action) { ?>
                    selected
                <?php } ?>
            >
                <?= $action_string ?>
            </option>
        <?php } ?>
        </select>
        <?php
    }

    private function printCivChooser($class, $id, $civ)
    {
        $cgrid = new CivGrid($this->aoe_version);
        $civs = $cgrid->getCivs();
        ?>
        <select class="<?php echo $class; ?>" data-id="<?php echo $id; ?>">
        <?php
        for ($index = 0; $index < count($civs); ++$index) {
            echo "<option value=\"".$index."\" ".(($index == $civ)?"selected":"").">".$civs[$index]."</option>";
        }
        ?>
        </select>
        <?php
    }

    public function editableTimeline($preset_turns)
    {
        ?>
        <table class="pure-table pure-table-horizontal">
            <thead>
                <tr>
                    <td>#</td>
                    <td><?php echo _("Player"); ?></td>
                    <td><?php echo _("Action"); ?></td>
                    <td class="turn-edit-actions"><?php echo _("Hidden"); ?>
                        <span class="turn-add" data-id="<?php echo 0; ?>">+</span>
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($index = 0; $index < count($preset_turns); ++$index) {
                    $turn = $preset_turns[$index];
                    ?>
                    <tr>
                        <td><?php echo $index+1; ?></td>
                        <td><?php $this->printPlayerChooser('turn-player', $index, $turn['player']); ?></td>
                        <td><?php $this->printActionChooser('turn-action', $index, $turn['action']); ?></td>
                        <td class="turn-edit-actions">
                            <input type="checkbox"
                                class="turn-hidden"
                                id="turn-hidden-<?php echo $index; ?>"
                                data-id="<?php echo $index; ?>"
                                <?= $turn['hidden'] == Turn::TURN_HIDDEN ? "checked" : "" ?>
                            />
                            <label for="turn-hidden-<?php echo $index; ?>"><span></span></label>
                            <span class="turn-delete" data-id="<?php echo $index; ?>">&ndash;</span>
                            <span class="turn-add" data-id="<?php echo $index+1; ?>">+</span>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>

    <div class="turn-summary">
        <div class="text-primary card">
            <div class="player-summary">
                <?php echo _("Player"); ?> 1: <?php $this->getPlayerSummary($preset_turns, Player::PLAYER_1);
                ?>
            </div>
            <br />
            <div class="player-summary">
                <?php echo _("Player"); ?> 2: <?php $this->getPlayerSummary($preset_turns, Player::PLAYER_2);
                ?>
            </div>

            <div class="card-background"></div>
        </div>
    </div>
        <?php
    }

    public function editablePreTurns($preset_turns)
    {
        ?>
        <table class="pure-table pure-table-horizontal">
            <thead>
                <tr>
                    <td>#</td>
                    <td><?php echo _("Player"); ?></td>
                    <td><?php echo _("Action"); ?></td>
                    <td class="turn-edit-actions"><?php echo _("Civilization"); ?>
                        <span class="pre-turn-add" data-id="<?php echo 0; ?>">+</span>
                    </td>
                </tr>
            </thead>
            <tbody>
                <?php
                for ($index = 0; $index < count($preset_turns); ++$index) {
                    $turn = $preset_turns[$index];
                    ?>
                    <tr>
                        <td><?php echo $index+1; ?></td>
                        <td><?php $this->printPlayerChooser('pre-turn-player', $index, $turn['player']); ?></td>
                        <td><?php $this->printActionChooser('pre-turn-action', $index, $turn['action']); ?></td>
                        <td class="turn-edit-actions">
                            <?php $this->printCivChooser('pre-turn-civ', $index, $turn['civ']); ?>
                            <span class="pre-turn-delete" data-id="<?php echo $index; ?>">&ndash;</span>
                            <span class="pre-turn-add" data-id="<?php echo $index+1; ?>">+</span>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <?php
    }

    public function timeline($preset_turns)
    {
        if (empty($preset_turns)) {
            return;
        }

        $do_strings = Turn::getDoStrings();
        ?>
    <div class="turn-row">
        <div class="pure-g">
            <div class="pure-u-1-24 arrow-start"><div><?php echo _("Start"); ?></div></div>
            <?php
            for ($index = 0; $index < count($preset_turns); ++$index) {
                $do = $preset_turns[$index]['action'];
                $extra_class = ($preset_turns[$index]['hidden'] == Turn::TURN_HIDDEN)?"turn-hidden":"";
                $extra_class .= (Player::isParallel($preset_turns[$index]['player'])) ? " turn-parallel" : "";
                $extra_class .= Turn::actionIsGlobal($do) ? " turn-global" : "";
                ?>
                <div class="pure-u-1-24 turn">
                    <div  class="turn-<?php echo Player::getEffectivePlayer($preset_turns[$index]['player']);
                    ?> turn-do-<?php echo Turn::actionGetType($do)." ".$extra_class;?>">
                        <span>
                            <?php
                            echo $do_strings[$do];
                            ?>
                        </span>
                        <?php
                        ?>
                    </div>
                </div>
                <?php
            }
            ?>
        <div class="pure-u-1-24 arrow-end"><div><span><?php echo _("End"); ?></span></div></div>
        <div class="pure-u-1-24" id="firefox-bug" style="width: 16px; height: 1px;"></div>
    </div>
</div>
        <?php
    }

    public function printActionText()
    {
        ?>
    <div id="action-text" class="centered">
        <div class="action-string info-card text-primary">
        </div>
        <!-- action messages -->
        <div style="display:none">
            <?php // phpcs:disable ?>
            <span id="action_msg_error_update"><?= _("Error updating the draft state.") ?></span>
            <span id="action_msg_error_sending_ready"><?= _("Error sending ready.") ?></span>
            <span id="action_msg_error_starting"><?= _("Error starting draft.") ?></span>
            <span id="action_msg_error_pic_ban"><?= _("Error picking/banning civ.") ?></span>
            <span id="action_msg_error_setName"><?= _("Error setting name.") ?></span>
            <span id="action_msg_text_0"><?= _("<span class='green-glow'><b>Pick</b></span> a civilization!") ?></span>
            <span id="action_msg_text_1"><?= _("<span class='red-glow'><b>Ban</b></span> a civilization for the enemy!") ?></span>
            <span id="action_msg_too_late_random"><?= _("Too late. Random pick.") ?></span>
            <span id="action_msg_draft_ended"><?= _("Drafting ended.") ?></span>
            <span id="action_msg_paste_code"><?= _("Please paste this code into in-game chat: {0} for spectating later.") ?></span>
            <span id="action_msg_use_code"><?= _("Use this code: {0} to spectate later.") ?></span>
            <span id="action_msg_ready_msg"><?= _("Click {0} to let the host start the draft.") ?></span>
            <span id="action_msg_ready"><?= _("Ready") ?></span>
            <span id="action_msg_get_ready"><?= _("get ready!") ?></span>
            <span id="action_msg_waiting_guest"><?= _("Waiting for the guest captain to get ready.") ?></span>
            <span id="action_msg_waiting_host"><?= _("Waiting for the host captain to start the draft.") ?></span>
            <span id="action_msg_guest_ready"><?= _("Guest is ready.") ?></span>
            <span id="action_msg_send_code"><?= _("Send this code to spectators: {0}. ") ?></span>
            <span id="action_msg_click_to_begin"><?= _("Click {0} to begin.") ?></span>
            <span id="action_msg_start"><?= _("Start") ?></span>
            <span id="action_msg_starting_draft_countdown"><?= _("Starting draft in... {0}") ?></span>
            <span id="action_msg_waiting_other"><?= _("Waiting for the other captain... {0}") ?></span>
            <span id="action_msg_starting_spectating"><?= _("Starting spectating in... {0}") ?></span>
            <?php // phpcs:enable ?>
        </div>

    </div>
        <?php
    }

    public function printActionMessage()
    {
        ?>
        <div id="action-message" class="centered">
            <div class="info-card text-primary"></div>
        </div>
        <!-- messages for translations-->
         <div style="display:none">
            <span id="action_msg_data_connection_issues"><?php echo _("Data connection issues."); ?></span>
         </div>
        <?php
    }

    public function drafter($preset_pre_turns, $preset_turns)
    {
        $pick_count = [];
        $ban_count = [];
        list($pick_count, $ban_count) = $this->countActions($preset_turns);
        $pre_pick_count = [];
        $pre_ban_count = [];
        list($pre_pick_count, $pre_ban_count) = $this->countActions($preset_pre_turns);
        $element_indexes = $this->getElementIndexes(
            $preset_pre_turns,
            $pre_pick_count,
            $pre_ban_count,
            $preset_turns,
            $pick_count,
            $ban_count
        );
        $counter = 0;

        $total_pick_count = [
            0 => ($pick_count[Player::PLAYER_1] + $pre_pick_count[Player::PLAYER_1]),
            1 => ($pick_count[Player::PLAYER_2] + $pre_pick_count[Player::PLAYER_2]),
        ];


        $total_ban_count = [
            0 => ($ban_count[Player::PLAYER_1] + $pre_ban_count[Player::PLAYER_1]),
            1 => ($ban_count[Player::PLAYER_2] + $pre_ban_count[Player::PLAYER_2]),
        ];
        ?>
    <div class="pure-g players">
        <?php
        for ($player_index = 0; $player_index < 2; ++$player_index) {
            ?>
            <div class="pure-u-1-2">
                <div id="player-<?php echo $player_index;
                ?>" class="double-outer-border">
                    <div class="double-inner-border">
                        <div class="player">
                            <div class="head-text">
                                <?php echo ($player_index == 0) ? _('Host') : _('Guest');
                                ?>
                            </div>
                            <div class="player-head">
                                <div class="player-name" ><?php echo _("Captain")." ".($player_index + 1);
                                ?></div>
                            </div>
                            <div class="chosen">
                                <div class="head-text"><?php echo _("Picks"); ?></div>
                                <div class="picks">
                                    <?php
                                    for ($i = 0; $i < $total_pick_count[$player_index]; $i++, $counter++) {
                                        ?>
                                    <div class="pick card turn-no-<?php echo $element_indexes[$counter];
                                    ?>">
                                        <div class="box-content"><img src="images/civs/hidden.png"/></div>
                                        <div class="card-overlay"></div>
                                    </div>
                                        <?php
                                    }
                                    ?>
                                </div>

                                <div class="head-text"><?php echo _("Bans"); ?></div>
                                <div class="bans">
                                    <?php
                                    for ($i = 0; $i < $total_ban_count[$player_index]; $i++, $counter++) {
                                        ?>
                                    <div class="ban card turn-no-<?php echo $element_indexes[$counter];
                                    ?>">
                                        <div class="box-content"><img src="images/civs/hidden.png"/></div>
                                        <div class="card-overlay"></div>
                                    </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        ?>

        <!-- Messages for translations -->
        <div style="display:none">
            <span id="drafter_msg_host_captain"><?php echo _("Host Captain"); ?></span>
            <span id="drafter_msg_guest_captain"><?php echo _("Guest Captain"); ?></span>
        </div>
    </div>
        <?php
    }

    public function printJsNames($player_role)
    {
        ?>
    $('#player-<?php echo $player_role; ?> .player-name').addClass('player-side');
    $('#player-<?php echo $player_role; ?>').addClass('player-side');
    $('#player-<?php echo $player_role; ?> .player-name').editable(function(value,settings) {
            send_name(value, update_draw);
            return(value);
        }, {
            cssclass: 'name-changer',
            type: 'text',
            tooltip: '<?php echo _("Click to edit..."); ?>',
            submit: '<?php echo _("OK"); ?>'
        });
        <?php
    }
}
