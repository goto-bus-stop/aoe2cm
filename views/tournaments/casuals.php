<div class="content">

<script>

$(document).ready(function(){
    $('.ctw').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/create?p=<?php echo $preset->id ?>";
    });


    $('.ctw-practice').click(function() {
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
    <img src="<?php echo ROOTDIR;?>/images/tournaments/casuals_to_war.jpg" width="100%" style="border-radius: 2px"/>
</div>


<fieldset id="instructions">
    <legend>Instructions</legend>
    <div class="double-outer-border">
        <div class="double-inner-border">
             <span class="text-primary">Read this before hosting! Also make sure to read the general rules for the captains mode first. </span>
            <br />
            <br />
             <span class="text-primary">There are 3 different stages in this tournament but the same rules for picking the civilizations apply to all of them. The group tables are <a href="http://challonge.com/casualstowar">here</a>.</span>
            <br />
            <br />

            <span class="text-primary">One player hosts a draft by clicking on the host button on the bottom and sends the invite link to the second player. It does not matter which player creates the draft.</span>
            <br />
            <br />

            <span class="text-primary">Each player is allowed to ban 3 civilizations and pick 1 civilization in each match. This means that in the group stage the civ selection has to be repeated for all 3 games against each player in the group and also in each match of the Best of 3 and Best of 5 in the Knockout stages. </span>
            <br />
            <br />

            <span class="text-primary">When the draft starts, the first player is allowed to ban one civilization, which will not be revealed to the other player. Afterwards, the second player bans a civ not visible to the first. This process has to be repeated until both players have banned 3 civs for the other.</span>
            <br />
            <br />

            <span class="text-primary">Only then the bans will be shown to the other player and the players can now pick their civs out of the pool of civs that were not banned. The pick will not be revealed to the other player until both players have made their choices.</span>
            <br />
            <br />

            <span class="text-primary">Once the draft is over, the match can start. Needless to say, each player needs to stick to the choice he has made during the draft. </span>
            <br />
            <br />

            <span class="text-primary">For further information on the different stages of the tournament, check the <a href="https://docs.google.com/document/d/1ywmiPZxl5MIfU7HSewfQGjwDgilyQTaIHHifd2A0vAI/edit">google document</a> send to all players or the <a href="http://steamcommunity.com/groups/casualstowar">steam group</a>. Here is a bonus instructions video:
            </span> <br /><br />
            <iframe width="560" height="315" src="https://www.youtube.com/embed/gYNsvWc-D9k" frameborder="0" allowfullscreen></iframe>

            <br />
            <br />
            <span class="text-primary">(In case you would like to try it out on your own click: <div class="ctw-practice shadowbutton"><small class="green-glow">practice</small></div>)</span>
            <br /><br />
            <span class="text-primary">When you are ready and read the rules click:</span><br />
            <div class="centered">
                <div class="ctw shadowbutton"><big class="red-glow">Host</big></div>
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
