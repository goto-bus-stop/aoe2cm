<?php
require_once 'lib/civgrid.class.php';
require_once 'lib/constants.class.php';
require_once 'lib/TurnsGrid.class.php';
require_once 'controllers/draft.class.php';
require_once 'views/overlay.php';

$draft = $this->draft;
$preset_turns = $this->preset_turns;
$preset_pre_turns = $this->preset_pre_turns;

$show_overlay = false;

if ($draft->isWaiting()) {
    $show_overlay = true;
}

$grid = new CivGrid($draft->getAoeVersion());
$turns_grid = new TurnsGrid($draft->getAoeVersion());

Constants::printJsConstants();
DraftController::printJsDraftProperties($draft);

?>

<script>

$(document).ready(function(){

    <?php

    $grid->printJsTooltips();
    $turns_grid->printJsNames($draft->player_role);

    if ($show_overlay) {
        ?> overlay(true, 'waiting'); <?php
    } else {
        ?> overlay(false); <?php
    }
    ?>
    getState(update_draw);
    
    $(".choice").click(function() {
        if(!gblActiveUser) {
            return;
        }
        var civ_id = $(".choice").index(this);
        if($.inArray(civ_id, gblDisabledCivs) != -1) {
            return;
        }
        if(send_pick(civ_id, update_draw)) {
            gblActiveUser = false;
            stop_countdown();
            //TODO do something about this
            $(".choice").removeClass('choice-draft choice-ban');
        }
        }); 
});
</script>

<?php overlay_view($draft->code); ?> 

<div class="draft-content">


<div id="draft-title" class="centered text-primary info-card"> <?php echo $draft->title; ?> </div>

    <?php
        $turns_grid->timeline($preset_turns);
        
        $turns_grid->drafter($preset_pre_turns, $preset_turns);
        
        $turns_grid->printActionText();
        $turns_grid->printActionMessage();
        
        $grid->printGrid();
        $grid->printTooltipsData();
    ?>

</div>
