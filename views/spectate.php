<?php 
require_once 'lib/civgrid.class.php';
require_once 'lib/constants.class.php';
require_once 'lib/TurnsGrid.class.php';
require_once 'views/overlay.php';

$draft = $this->draft;
$preset_turns = $this->preset_turns;
$preset_pre_turns = $this->preset_pre_turns;

$show_overlay = FALSE;
$non_existent = FALSE;
$is_live = FALSE;

if($draft->exists()) {

	if($draft->is_waiting()) {
		$show_overlay = TRUE;
	}

	if($draft->is_starting() || $draft->is_waiting() || $draft->ready() || $draft->current_turn < 0) {
		$is_live = TRUE;
	}
} else {
	$show_overlay = TRUE;
	$non_existent = TRUE;
}

$turns_grid = new TurnsGrid($draft->get_aoe_version()); 
	
Constants::js_constants();
DraftController::js_draft_properties($draft);
?>

<script>

var SPECTATOR_TIMEOUT = 5;

var spectatorLiveMode = <?php echo (($is_live) ? 'true': 'false'); ?>;

$(document).ready(function(){
	
	<?php if($draft->exists()) { ?>
		if(spectatorLiveMode) {
			spectator_set_live();
			get_state(update_draw);
		} else {
			get_state(update_spectator);
		}
		<?php if($draft->player_role == Player::PLAYER_NONE && count($draft->players) == 0) {
			?> overlay(true, 'hosting'); <?php
		} else if($draft->is_waiting()) {
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
		
		$turns_grid->action_text();
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
		$grid = new CivGrid($draft->get_aoe_version());
		$grid->display_grid();
		$grid->setup_tooltips_data();
	?>
</div>
	
</div>
