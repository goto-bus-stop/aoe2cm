<?php
use Aoe2CM\CivGrid;
use Aoe2CM\TurnsGrid;
use Aoe2CM\Constants;
use Aoe2CM\DraftController;

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

<?php require_once __DIR__.'/overlay.php'; ?>
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
