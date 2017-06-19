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
</div>

<div style="margin: auto; padding-top: 64px">
    <img src="<?php echo ROOTDIR;?>/images/tournaments/Legacy_Cup_Reduced.png" width="100%" style="border-radius: 2px"/>
</div>


<fieldset id="instructions">
    <legend>Instructions</legend>
    <div class="double-outer-border">
        <div class="double-inner-border">
             <span class="text-primary">Read this before hosting! Also make sure to read the general rules for the captains mode first. </span>
            <br />
            <br />
             <span class="text-primary">For rules and settings see <a href="http://aoczone.net/viewtopic.php?f=1415&t=126006">this post</a> on AocZone.</span>
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
