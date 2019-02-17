<?php
$draft = $this->draft;
$join = $this->join;
$role = $this->role;

$query = http_build_query([
    'code' => $draft->code,
    'role' => $role,
]);
?>
<div class="content">

<script>

$(document).ready(function(){
    $('.bjoin').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/_join?<?php echo $query; ?>";
    });


    $('.bspectate').click(function() {
        window.location.href="<?php echo ROOTDIR; ?>/spectate?<?php echo $query; ?>";
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

<div id="instructions" class="draft-content">
    <div id="draft-title" class="centered text-primary info-card"> <?php echo $draft->title; ?> </div>
    
    <div class="pure-g">
        <div class="pure-u-1-1" >
            <div id="join_games" class="card home_card">
                <h1><span><span class="red-glow"><?php echo $draft->players[0]->name."</span> "._(" vs ")." <span class=\"blue-glow\">".$draft->players[1]->name; ?></span></h1>
            <?php
                if(!$join) {
            ?>
                <br />
                <span class="text-primary">Looks like the draft is already under way. You can't join.</span>
                <br />
                <br />
                <div class="centered">
                    <div class="bspectate shadowbutton blue-glow" style="color: #fff;"><?php echo _("Spectate"); ?></div>
                </div>
            <?php
                } else {
            ?>
                <div class="pure-g">
                    <div class="centered pure-u-1-2"><div class="bspectate shadowbutton blue-glow" style="color: #fff;"><?php echo _("Spectate"); ?></div> </div>
                    <div class="centered pure-u-1-2"><div class="bjoin shadowbutton green-glow" style="color: #fff;"><?php echo _("Join"); ?></div></div>
                </div>
            <?php
                }
            ?>
            
            </div>
        </div>
    </div>
</div>
</div>
