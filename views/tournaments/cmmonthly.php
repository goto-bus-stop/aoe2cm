<div class="content">

<script>

$(document).ready(function(){
    $('.cmmonthly').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/create?p=<?php echo $preset->id ?>";
    });


    $('.cmmonthly-hidden').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/create?p=<?php echo $preset_hidden->id ?>";
    });

    $('.cmmonthly-practice').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/create?p=<?php echo $preset->id ?>&d=1";
    });

    setup_themeswitcher();

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
    <div id="themeswitcher"><span>switch theme</span></div>
    <!-- a href="<?php echo ROOTDIR;?>/admin"><span class="admin-link">admin</span></a-->
</div>

<div style="margin: auto; padding-top: 64px">
    <img src="<?php echo ROOTDIR;?>/images/tournaments/captainsmode_dmedition.png" width="100%" style="border-radius: 2px"/>
</div>


<fieldset id="instructions">
    <legend>Instructions</legend>
    <div class="double-outer-border">
        <div class="double-inner-border">
            <span class="text-primary"><u>Read this before hosting!</u> Also make sure to read the general rules for the captains mode first.</span> <br /><br />
            <span class="text-primary">Each of the players has the opportunity to <span class="red-glow">ban</span> 2 civilizations first for the other captain. This is followed by a <span class="green-glow">pick</span>, then a <span class="red-glow">ban</span> and a final <span class="green-glow">pick</span>. Each team has to pick different civilizations.</span><br /><br />
            <span class="text-primary">G1 can be hosted by either player. From G2 and onwards the hosting captain is specified by the settings!</span> <br /><br />
            <span class="text-primary">
            <b>BO1</b><br />
            <ul>
            <li>Game 1 - TU_GA - Hidden</li>
            </ul>
            <b>BO3</b><br />
            <ul>
            <li>Game 1 - TU_GA - Hidden </li>
            <li>Game 2 - TU_Gold Rush - Loser team starting the draft </li>
            <li>Game 3 - TU_Baltic - Winner team starting the draft </li>
            </ul>
            <b>BO5 </b><br />
            <ul>
            <li>Game 1 - TU_GA - Hidden
            <li>Game 2 - TU_Gold Rush - Loser team starting the draft </li>
            <li>Game 3 - TU_Baltic - Winner team starting the draft </li>
            <li>Game 4 - TU_Graveyards - Loser team starting the draft </li>
            <li>Game 5 - TU_Ghostlake - Winner team starting the draft </li>
            </ul>
            </span><br /><br />
            <span class="text-primary">(In case you would like to try it out on your own click: <div class="cmmonthly-practice shadowbutton"><small class="green-glow">practice</small></div>)</span>
            <br /><br />
            <span class="text-primary">When you are ready and read the rules click:</span><br />
            <div class="centered">
                <div class="cmmonthly-hidden shadowbutton"><big class="red-glow">Host G1 (hidden)</big></div>
                <div class="cmmonthly shadowbutton"><big class="red-glow">Host G2-G5</big></div>
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
