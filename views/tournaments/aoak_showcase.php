<div class="content">

<script>

$(document).ready(function(){
    $('.aoak-g1').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/create?p=<?php echo $preset_g1->id ?>";
    });


    $('.aoak-g3').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/create?p=<?php echo $preset_g3->id ?>";
    });

    $('.aoak-practice-g1').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/create?p=<?php echo $preset_g1->id ?>&d=1";
    });


    $('.aoak-practice-g3').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/create?p=<?php echo $preset_g3->id ?>&d=1";
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
    <!--a href="<?php echo ROOTDIR;?>/admin"><span class="admin-link">admin</span></a-->
</div>

<div style="margin: auto; padding-top: 64px">
    <img src="<?php echo ROOTDIR;?>/images/tournaments/aoak_showcase.png" width="100%" style="border-radius: 2px"/>
</div>


<fieldset id="instructions">
    <legend>Instructions</legend>
    <div class="double-outer-border">
        <div class="double-inner-border">
            <span class="text-primary"><u>Read this before hosting!</u> Also make sure to read the general rules for the captains mode first.</span> <br /><br />
            <span class="text-primary">In G1-2 the captains ban 2 civs (not AoAK civs) then pick 3 civs. In G2-3 each of the captains has 3 pairs of a <span class="red-glow">ban</span>, then a <span class="green-glow">pick</span>. The bans and picks are all hidden - watch the timeline at the top to see when they get revealed. The bans are not global: they ban the civ only for the other captain - the first captain can still pick said civ.</span><br /><br />
            <span class="text-primary">All games must be drafted with captains mode and are hidden, so they can be hosted by anyone. The captains mode does not enforce the rules of the number of drafted AoAK and AoF civs. You have to keep track of them yourself. </span> <br /><br />
            <span class="text-primary">The general rules for this tourney are <a href="http://aoczone.net/viewtopic.php?f=3&t=120261">here</a>.<br /><br />
            <span class="text-primary">
            <b>BO5</b><br />
            <ul>
            <li>G1 – Arabia (w/ new animals) 2 different AoAK Civs required per team </li>
            <li>G2 – Team Islands (w/ new animals) 2 different AoAK Civs per team (not the 2 used last game)</li>
            <li>G3 – Nomad, 1 Forgotten Civ required per team</li>
            <li>G4 – Arena, 1 AoAK Civ required, 1 Forgotten Civ required per team</li>
            <li>G5 – Valley, 1 AoAK Civ required, 1 Forgotten Civ required per team</li>
            </ul>
            </span><br /><br />
            <span class="text-primary">(In case you would like to try it out on your own click: <div class="aoak-practice-g1 shadowbutton"><small class="green-glow">practice g1-2</small></div> <div class="aoak-practice-g3 shadowbutton"><small class="green-glow">practice g3-5</small></div>)</span>
            <br /><br />
            <span class="text-primary">When you are ready and read the rules click:</span><br />
            <div class="centered">
                <div class="aoak-g1 shadowbutton"><big class="red-glow">Host G1-G2</big></div>
                <div class="aoak-g3 shadowbutton"><big class="red-glow">Host G3-G5</big></div>
            </div>
            <br />
            <br />
        </div>
    </div>
</fieldset>

</div>
