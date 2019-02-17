<?php 
require_once 'lib/civgrid.class.php';
require_once 'lib/constants.class.php';
require_once 'lib/TurnsGrid.class.php';
require_once 'controllers/draft.class.php';
require_once 'views/overlay.php';

$draft = $this->draft;
$preset_turns = $this->preset_turns;
$preset_pre_turns = $this->preset_pre_turns;

$show_overlay = FALSE;

$grid = new CivGrid($draft->get_aoe_version());
$turns_grid = new TurnsGrid($draft->get_aoe_version()); 

Constants::js_constants();
DraftController::js_draft_properties($draft);
?>

<script>

$(document).ready(function(){

	overlay(<?php if($show_overlay){ echo "true";}else{ echo "false";}?>, 'nogame');
<?php

	$grid->setup_tooltips_js();
	$turns_grid->setup_names_js($draft->player_role);
?>
	get_state(update_draw);
	
	$(".choice").click(function() {
		if(!gblActiveUser) {
			//return;
		}
		var civ_id = $(".choice").index(this);
		if($.inArray(civ_id, gblDisabledCivs) != -1) {
			//return; //TODO enable just in case
		}
		if(send_pick(civ_id, update_draw)) {
			gblActiveUser = false;
			stop_countdown();
			//TODO do something with this!!!
			$(".choice").removeClass('choice-pick choice-ban');
		}
		});	
});
</script>

<?php overlay_view($draft->code); ?>

<div>
	<span id="result"></span>
</div>

<div style="padding-top: 16px">
<fieldset id="instructions">
	<legend><?php echo _("Practice"); ?></legend>
	<div class="double-outer-border">
		<div class="double-inner-border">
			<span class="text-primary red-glow" style="font-size: 1.5em"><?php echo _("Make sure you read this!"); ?></span><br />
			<span class="text-primary"><?php echo _("In Practice mode You control both player's picks and bans."
				. "The page looks just as if you were the blue player. <b>BUT</b>, while the yellow player's choices are grayed out and there is no highlight, "
				. "you can still pick and ban civs for him by clicking on the civ."); ?>
			</span> <br />
		</div>
	</div>
</fieldset>
</div>

<div class="draft-content practice-draft">

<div id="draft-title" class="centered text-primary info-card"> <?php echo $draft->title; ?> </div>

	<?php
		$turns_grid->timeline($preset_turns);
		
		$turns_grid->drafter($preset_pre_turns, $preset_turns);
		
		$turns_grid->action_text();

		$turns_grid->action_message();
		
		$grid->display_grid();
		$grid->setup_tooltips_data();
?>

</div>
