<?php
use Aoe2CM\CivGrid;
use Aoe2CM\TurnsGrid;
use Aoe2CM\Constants;
use Aoe2CM\DraftController;

$draft = $this->draft;
$preset_turns = $this->preset_turns;
$preset_pre_turns = $this->preset_pre_turns;

$show_overlay = false;

$grid = new CivGrid($draft->getAoeVersion());
$turns_grid = new TurnsGrid($draft->getAoeVersion());

Constants::printJsConstants();
DraftController::printJsDraftProperties($draft);
?>

<script>
$(document).ready(function () {
    overlay(<?= json_encode($show_overlay) ?>, 'nogame');
<?php
    $grid->printJsTooltips();
    $turns_grid->printJsNames($draft->player_role);
?>
    getState(update_draw);

    $(".choice").click(function () {
        if(!gblActiveUser) {
            //return;
        }
        var civ_id = $(".choice").index(this);
        if ($.inArray(civ_id, gblDisabledCivs) != -1) {
            //return; //TODO enable just in case
        }
        if (send_pick(civ_id, update_draw)) {
            gblActiveUser = false;
            stop_countdown();
            //TODO do something with this!!!
            $(".choice").removeClass('choice-pick choice-ban');
        }
    });
});
</script>

<?php require_once __DIR__.'/overlay.php'; ?>
<?php overlay_view($draft->code); ?>

<div>
    <span id="result"></span>
</div>

<div style="padding-top: 16px">
    <fieldset id="instructions">
        <legend><?= _("Practice") ?></legend>
        <div class="double-outer-border">
            <div class="double-inner-border">
                <span class="text-primary red-glow" style="font-size: 1.5em">
                    <?= _("Make sure you read this!") ?>
                </span><br />
                <span class="text-primary">
                    <?= _("In Practice mode You control both player's picks and bans."
                        . "The page looks just as if you were the blue player. "
                        . "<b>BUT</b>, while the yellow player's choices are grayed out and there is no highlight, "
                        . "you can still pick and ban civs for him by clicking on the civ.") ?>
                </span> <br />
            </div>
        </div>
    </fieldset>
</div>

<div class="draft-content practice-draft">
    <div id="draft-title" class="centered text-primary info-card"><?= $draft->title ?></div>
    <?php
        $turns_grid->timeline($preset_turns);
        $turns_grid->drafter($preset_pre_turns, $preset_turns);
        $turns_grid->printActionText();
        $turns_grid->printActionMessage();
        $grid->printGrid();
        $grid->printTooltipsData();
    ?>
</div>
