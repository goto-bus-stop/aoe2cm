<div class="content">

<script>

document.body.style.background = "#605a56 url('../images/tournaments/mastersofarabia2_bg.png') no-repeat center center fixed";
document.body.style.backgroundSize = "cover";

$(document).ready(function(){
    $('.moara2').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/create?p=<?php echo $preset->id ?>";
    });

    $('.moara2-practice').click(function() {
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
    <!-- a href="<?php echo ROOTDIR;?>/admin"><span class="admin-link">admin</span></a -->
</div>

<div style="margin: auto; padding-top: 64px">
    <img src="<?php echo ROOTDIR;?>/images/tournaments/mastersofarabia2nobg2.png" width="100%" style="border-radius: 2px"/>
</div>

<div style="margin: auto; clear: both; padding-bottom: 16px; text-align: center;vertical-align: middle">
    <div style="float: left"><img src="<?php echo ROOTDIR;?>/images/aoczone-logo-new.png" height="64px" /></div>
    <!--div style="display: inline-block"><img src="<?php echo ROOTDIR;?>/images/captainsmodelogo_nocm_dilated.png" height="48px" style="margin-top: 2px; margin-bottom: 2px; margin-right: 8px"/></div-->
    <div style="float: right"><img src="<?php echo ROOTDIR;?>/images/voobly-logo-small.png" height="64px" /></div>
   
    <div style="clear: both"></div>
</div>


<fieldset id="instructions">
    <legend>Captains Mode Instructions</legend>
    <div class="double-outer-border">
        <div class="double-inner-border">
            <span class="text-primary"><u>Read this before hosting!</u> Also make sure to read the general rules for the captains mode first.</span> <br /><br />
            <span class="text-primary">There are 3 pre-banned civilizations in this tourney: aztecs, huns and mayans. Nobody can pick or ban these.</span><br /><br />
            <span class="text-primary">Each of the players has the opportunity to <span class="red-glow">ban</span> 2 civilizations first that <u>neither</u> player can pick later. The <span class="red-glow">bans</span> are <u>global</u>. For example if the first captain bans franks, in the following rounds the second captain can't ban or pick franks.</span><br /><br />
            <span class="text-primary">After the bans each player has to <span class="green-glow">pick</span> a civilization. This pick will be <u>hidden</u> from the other captain, until the draft ends.</span> <br /><br />
            <span class="text-primary">(In case you would like to try it out on your own click: <div class="moara2-practice shadowbutton"><small class="green-glow">practice</small></div>)</span>
            <br /><br />
            <span class="text-primary">When you are ready and read the rules click:</span><br />
            <div class="centered">
                <div class="moara2 shadowbutton"><big class="red-glow">Host draft</big></div>
            </div>
            <br />
            <br />
        </div>
    </div>
</fieldset>

</div>
