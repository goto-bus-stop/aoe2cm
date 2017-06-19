<div class="content">

<script>
document.body.style.background = "#12001d";

$(document).ready(function(){
    $('.game1').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/create?p=<?php echo $preset->id ?>";
    });


    $('.game1-practice').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/create?p=<?php echo $preset->id ?>&d=1";
    });

    $('.game3').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/create?p=<?php echo $preset2->id ?>";
    });


    $('.game3-practice').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/create?p=<?php echo $preset2->id ?>&d=1";
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
</div>

<div style="margin: auto; padding-top: 64px">
    <img src="<?php echo ROOTDIR;?>/images/tournaments/return_of_the_kings.png" width="100%" style="border-radius: 2px"/>
</div>


<fieldset id="instructions" style="-webkit-filter: hue-rotate(100deg); filter: hue-rotate(100deg);">
    <legend>Instructions</legend>
    <div class="double-outer-border">
        <div class="double-inner-border">
             <span class="text-primary">Read this before hosting! Also make sure to read the general rules for the captains mode first. </span>
            <br />
            <br />
             <span class="text-primary">For rules and settings see official announcement <a href="http://escape.gg/rotk-settings/">link</a>.</span>
            <br />
            <ul>
                <li>G1 – Higher seed picks a civ pool for each team: AoK/AoC civs for one team and Expansion civs for the other team. Hidden pick 2 different civs. AoK/AoC team hosts the draft.<br /></li>
                <li>G2 – Swap civ pools: AoK/AoC civs for one team and Expansion civs for the other team. Hidden pick 2 different civs, no repeating civs from G1.</li>
                <li>G3 – Ban and select civs using the order 1 global ban each, 1 pick each, 2 hidden bans, 1 pick. Each team must pick one AoK/AoC civ and one expansion civ.</li>
                <li>G4 – Team Random, one AoK/AoC civ and one expansion civ (note we cannot do this in game, so admins will randomly pick the civs and then give them to the teams).</li>
                <li>G5 – Hidden civ, free pick. No restrictions.</li>
            </ul>
            <br />

            <span class="text-primary">(In case you would like to try it out on your own click: <div class="game1-practice shadowbutton"><small class="green-glow">practice G1-G2</small></div> <div class="game3-practice shadowbutton"><small class="green-glow">practice G3</small></div>)</span>
            <br /><br />
            <span class="text-primary">When you are ready and read the rules click:</span><br />
            <div class="centered">
                <div class="game1 shadowbutton"><big class="red-glow">Host G1-2</big></div>
                <br />
                <div class="game3 shadowbutton"><big class="red-glow">Host G3</big></div>
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
