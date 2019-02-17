<?php
require_once 'lib/civgrid.class.php';
require_once 'lib/constants.class.php';
require_once 'lib/TurnsGrid.class.php';
require_once 'views/overlay.php';

$draft = $this->draft;
$preset_turns = $this->preset_turns;
$preset_pre_turns = $this->preset_pre_turns;

$show_overlay = false;
$non_existent = false;
$is_live = false;

if ($draft->exists()) {
    if ($draft->isWaiting()) {
        $show_overlay = true;
    }

    if ($draft->isStarting() || $draft->isWaiting() || $draft->ready() || $draft->current_turn < 0) {
        $is_live = true;
    }
} else {
    $show_overlay = true;
    $non_existent = true;
}

$turns_grid = new TurnsGrid($draft->getAoeVersion());
    
Constants::printJsConstants();
DraftController::printJsDraftProperties($draft);
?>

<script>

var SPECTATOR_TIMEOUT = 5;

var spectatorLiveMode = <?php echo (($is_live) ? 'true': 'false'); ?>;

$(document).ready(function(){
    
    <?php if ($draft->exists()) { ?>
        if(spectatorLiveMode) {
            spectator_set_live();
            getState(update_draw);
        } else {
            getState(update_spectator);
        }
        <?php if ($draft->player_role == Player::PLAYER_NONE && count($draft->players) == 0) {
            ?> overlay(true, 'hosting'); <?php
        } elseif ($draft->isWaiting()) {
            ?> overlay(true, 'waiting'); <?php
        } else {
            ?> overlay(false, 'waiting'); <?php
        } ?>
    <?php } else {?>
        overlay(true, 'nogame');
    <?php } ?>
});
</script>


<?php overlay_view($draft->code); ?> 


<div class="draft-content">

<div id="draft-title" class="centered text-primary info-card"><?php echo $draft->title; ?></div>
    
<?php
        $turns_grid->timeline($preset_turns);
        
        $turns_grid->drafter($preset_pre_turns, $preset_turns);
        
        $turns_grid->printActionText();
?>

<div id="spectator-controls" class="centered">
    <div class="spectator-action shadowbutton text-primary" onclick="spectator_play_pause()">
        <span id="spectator-play" style="display:none"><?php echo _("play"); ?></span>
        <span id="spectator-pause"><?php echo _("pause"); ?></span>
    </div>
    <div class="spectator-action shadowbutton text-primary" onclick="spectator_next_turn()">
        <span id="spectator-next"><?php echo _("next"); ?></span>
    </div>
    <div class="spectator-action shadowbutton text-primary" onclick="spectator_fast_forward()">
        <span id="spectator-forward"><?php echo _("fast-forward"); ?></span>
    </div>
</div>

<div>
    <?php
        $grid = new CivGrid($draft->getAoeVersion());
        $grid->printGrid();
        $grid->printTooltipsData();
    ?>
</div>
    
</div>
