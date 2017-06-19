<div class="content">

<script>
var wrapperElement = document.getElementById("wrapper");
wrapperElement.style.background = "#605a56 url('../images/tournaments/black-forest.jpg') no-repeat center center";
wrapperElement.style.backgroundSize = "cover";

$(document).ready(function(){
    $('.itd-g1').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/create?p=<?php echo $preset_g1->id ?>";
    });

    $('.itd-g2').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/create?p=<?php echo $preset_g2->id ?>";
    });


    $('.itd-practice-g1').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/create?p=<?php echo $preset_g1->id ?>&d=1";
    });

    $('.itd-practice-g2').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/create?p=<?php echo $preset_g2->id ?>&d=1";
    });

    $('#captain-name').editable(function(value, settings) {
        var p_captain_name = value.substr(0, 32);
        Cookies.set("username", p_captain_name, {expires: 365});
        return(p_captain_name);
    }, {
        cssclass: 'name-changer',
        type: 'text',
        tooltip: 'Click to set name...',
        submit: 'OK'
    });
});


</script>

<div style="position:absolute;top:8px;right:8px; ">
    <div id="captain-name"><?php if(isset($_COOKIE['username'])){ echo $_COOKIE['username']; } ?></div>
</div>

<div style="margin: auto; padding-top: 64px">
    <img src="<?php echo ROOTDIR;?>/images/tournaments/itd_banner_alpha.png" width="100%" style="border-radius: 2px"/>
</div>


<fieldset id="instructions">
    <legend>Instructions</legend>
    <div class="double-outer-border">
        <div class="double-inner-border">
             <span class="text-primary"><u>Read this before hosting!</u> Also make sure to read the general rules for the captains mode first.</span> <br /><br />
            <span class="text-primary">Every game will use Captains Mode using the hidden pick option. You won't see the enemy's pick until you picked something.</span><br /><br />
            <span class="text-primary">In G1 teams will <b>globally</b> ban 1 civ, then pick 1, then <b>globally</b> ban another one and pick 2. <u>Your bans in G1 apply to to everyone!</u> If you ban something, neither you or the enemy captain can pick that civ later. </span><br /><br />
            <span class="text-primary">In G2-3 teams will ban 1 civ, then pick 1 civ, then ban another civ and pick another civ, then ban another civ and pick their final civ. The bans apply only to the enemy captain, unlike in G1.</span><br /><br />
            <span class="text-primary">Each game can be hosted by either captain.</span> <br /><br />
            <span class="text-primary">
            <b>Round1 BO3</b><br />
            <b>Round2 BO3</b><br />
            <b>Semi-finals BO5</b><br />
            <b>Final BO5</b><br />
            </span><br /><br />
            <span class="text-primary">(In case you would like to try it out on your own click: <br /><div class="itd-practice-g1 shadowbutton"><small class="green-glow">practice g1</small></div> <div class="itd-practice-g2 shadowbutton"><small class="green-glow">practice g2-5</small></div>)</span>
            <br /><br />
            <span class="text-primary">When you are ready and read the rules click:</span><br />
            <div class="centered">
                <div class="itd-g1 shadowbutton"><big class="red-glow">Host g1</big></div>
                <div class="itd-g2 shadowbutton"><big class="red-glow">Host g2-5</big></div>
            </div>
            <br />
            <br />
        </div>
    </div>
</fieldset>

<?php 
if(!empty($last_drafts)) {
?>
    <div class="home_card box">
        <h2><?php echo _("Recent Drafts"); ?></h2>
        <table class="pure-table pure-table-horizontal recent-drafts">
            <tbody>
                <?php
                foreach($last_drafts as $draft) {
                ?>
                <tr>
                    <td class='recent-title'><?php echo $draft->title; ?></td>
                    <td class='recent-users'><?php echo $draft->players[0]->name." "._(" vs ")." ".$draft->players[1]->name; ?></td>
                    <td class='recent-action'><?php
                        echo "<a href=\"".ROOTDIR."/spectate?code=".$draft->code."\" class=\"text-primary shadowbutton\">";
                        if($draft->is_done()) {
                            echo _("Watch");
                        } else {
                            echo _("Watch Live");
                        }
                        echo "</a>";
                        ?>
                    </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
<?php 
}
?>

</div>
