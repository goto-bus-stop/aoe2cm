<div class="content">

<style>
body {
    background-color: #222831;
}

.content {
    width: 1280px;
}

#instructions .double-outer-border{
    background: transparent;
    box-shadow: none;
}
</style>

<script>
//document.body.style.background = "#222831";

$(document).ready(function(){
    $('.game23').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/create?p=<?php echo $preset->id ?>";
    });


    $('.game23-practice').click(function() {
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
</div>

<div style="margin: auto; padding-top: 64px">
    <img src="<?php echo ROOTDIR;?>/images/tournaments/allstars.png" width="100%" style="border-radius: 2px"/>
</div>


<fieldset id="instructions" style="">
    <legend style="color: #ffcc00">Instructions</legend>
    <div class="double-outer-border">
        <div class="double-inner-border">
             <span class="text-primary">Read this before hosting! Also make sure to read the general rules for the captains mode first. </span>
            <br />
            <br />
             <span class="text-primary">For rules and settings see official announcement <a href="https://escape.gg/allstars-settings/" style="color: #ffcc00; text-decoration: underline">link</a>.</span>
            <br />

            <span class="text-primary">(In case you would like to try it out on your own click: <div class="game23-practice shadowbutton"><small class="green-glow">practice G2-G3</small></div>)</span>
            <br /><br />
            <span class="text-primary">When you are ready and read the rules click:</span><br />
            <div class="centered">
                <div class="game23 shadowbutton"><big class="red-glow" style="color:#ffcc00">Host G2-3</big></div>
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
