<?php
require_once __DIR__.'/../lib/constants.class.php';
require_once __DIR__.'/../controllers/draft.class.php';
require_once __DIR__.'/../lib/name_generator.php';
?>

<script>

function practice_game(index) {
    window.location.href="<?php echo ROOTDIR; ?>/create?t="+index+"&d=1";
}

function host_game(index) {
    window.location.href="<?php echo ROOTDIR; ?>/create?t="+index;
}

function practice_preset_game() {
    var preset_id = $('#preset-chooser-practice').val()
    window.location.href="<?php echo ROOTDIR; ?>/create?p="+preset_id+"&d=1";
}

function host_preset_game() {
    var preset_id = $('#preset-chooser').val()
    window.location.href="<?php echo ROOTDIR; ?>/create?p="+preset_id;
}

function set_username() {
    overlay(false, 'set-name');
    var username = $("#set-name-message .inset-input").val();
    var p_captain_name = username.substr(0, 32);
    $("#captain-name").html(p_captain_name);
    Cookies.set("username", p_captain_name, {expires: 365});
    location.reload(true);
}

$.fn.animateRotate = function(angle, duration, easing, complete) {
  var args = $.speed(duration, easing, complete);
  var step = args.step;
  return this.each(function(i, e) {
    args.complete = $.proxy(args.complete, e);
    args.step = function(now) {
      $.style(e, 'transform', 'rotate(' + now + 'deg)');
      if (step) return step.apply(e, arguments);
    };

    $({deg: 0}).animate({deg: angle}, args);
  });
};

$(document).ready(function(){
    $('.gametype').click(function() {
        host_game($('.gametype').index(this));
    });

    $('.gametype-practice').click(function() {
        practice_game($('.gametype-practice').index(this));
    });

    $('#host_preset_game').click(function() {
        host_preset_game();
    });

    $('#practice_preset_game').click(function() {
        practice_preset_game();
    });

    $("#preset-chooser").chosen({disable_search_threshold: 3, width: "95%"});
    $("#languages").chosenImage({disable_search_threshold: 10, width: "42px"});
     $('#languages').on('change', function(evt, params) {
        if(typeof params.selected !== undefined) {
            window.location.href="<?php echo ROOTDIR; ?>/?lang="+params.selected;
        }
      });

    $("#preset-chooser-practice").chosen({disable_search_threshold: 3, width: "95%"});

    $("#join-game-button").click(function() {
        $("#join-game-submit").click();
    });

    $("#spectate-game-button").click(function() {
        $("#spectate-game-submit").click();
    });


    setup_themeswitcher();

    $('#captain-name').editable(function(value, settings) {
        var p_captain_name = value.substr(0, 32);
        Cookies.set("username", p_captain_name, {expires: 365});
        return(p_captain_name);
    }, {
        cssclass: 'name-changer',
        type: 'text',
        tooltip: '<?php echo _("Click to set name..."); ?>',
        submit: '<?php echo _("OK"); ?>'
    });

    if(typeof Cookies.get("tab_open") !== "undefined") {
        $("#"+Cookies.get("tab_open")).attr('checked', true);
    }

    $("input[name='tabs']").click(function() {
        Cookies.set("tab_open", this.value, {expires: 1});
    });

});

</script>

<?php if (empty($_COOKIE['username'])) { ?>
    <div id="overlay" class="text-primary" style="visibility: visible">
         <div id="set-name-message" style="display: block">
            <h2><span><?php echo _("Welcome"); ?></span></h2>
            <p><?php echo _("Looks like it's your first time here. First, set a captain name for yourself:"); ?> <br />
                <input type="text" class="inset-input" value="<?php echo generate_random_name(); ?>" > <br />
                <?php echo _("You can edit this anytime in the future by clicking your name."); ?>
            </p>
            <span><a onclick="set_username();"><span class='back-icon'><?php echo _("Set name"); ?></span></a></span>
            <p><?php echo _("Make sure you read the rules!"); ?></p>
         </div>
    </div>
<?php } ?>

<div class="content">

<div style="position:absolute;top:8px;right:8px;">
    <div id="captain-name">
        <?php if (isset($_COOKIE['username'])) { ?>
            <?= $_COOKIE['username']; ?>
        <?php } ?>
    </div>
    <div id="themeswitcher"><span><?php echo _("switch theme"); ?></span></div>
    <div id="languageswitcher" class="styled-select">
        <select id="languages">
            <option value="en_US" data-img-src="images/flags/en_US.png"
                <?php if ($_SESSION["lang"] == 'en_US') { ?>
                    selected
                <?php } ?>>&nbsp;</option>
            <option value="hu_HU" data-img-src="images/flags/hu_HU.png"
                <?php if ($_SESSION["lang"] == 'hu_HU') { ?>
                    selected
                <?php } ?>>&nbsp;</option>
        </select>
    </div>
    <!-- a href="<?php echo ROOTDIR;?>/admin"><span class="admin-link"><?php echo _("admin"); ?></span></a -->
</div>

<div class="title">
    <span id="aoe-title"><?php echo _("Age of Empires II"); ?></span>
    <span id="cm-logo"></span>
    <span id="cm-title"><?php echo _("Captains mode"); ?></span>
</div>

<div class="tabs">

   <input type="radio" name="tabs" id="tab1" value="tab1" checked >
   <label for="tab1">
       <i class="fa fa-welcome"></i><span><?php echo gettext("Welcome") ?></span>
   </label>

   <input type="radio" name="tabs" id="tab2" value="tab2">
   <label for="tab2">
       <i class="fa fa-host"></i><span><?php echo gettext("Host or Join"); ?></span>
   </label>

   <input type="radio" name="tabs" id="tab3" value="tab3">
   <label for="tab3">
       <i class="fa fa-spectate"></i><span><?php echo _("Spectate"); ?></span>
   </label>

   <input type="radio" name="tabs" id="tab4"  value="tab4">
   <label for="tab4">
       <i class="fa fa-practice"></i><span><?php echo _("Practice"); ?></span>
   </label>

   <div id="tab-content1" class="tab-content">
          <fieldset id="instructions">
            <legend>Instructions</legend>
            <div class="double-outer-border">
                <?php // phpcs:disable ?>
                <div class="double-inner-border">
                    <span class="text-primary"><?= _("Captains mode is a turn-based civilization picker. Each captain can <span class='green-glow'>pick</span> and <span class='red-glow'>ban</span> civilizations in a predefined order. Bans prevent the opponent's captain from picking the civilizations. ") ?></span> <br /> <br />
                    <span class="text-primary"><?= _("For each turn captains have <b>30 seconds</b>. In case of timeout a random civilization is picked for the captain or no civilization gets banned.") ?></span> <br /> <br />
                    <span class="text-primary"><?= _("There are also extension available for tournaments. <b>Hidden</b> option (black corners) hides the civilization choices until they are explicitely showed (visible in the timeline at the top). <b>Global</b> pick or bans disables the civilization for later turns. For other specific settings, please refer to the preset descriptions.") ?> </span> <br /><br />
                    <span class="text-primary"><?= _("<b>Practice</b> mode is for testing purposes, where both sides are controlled by a single player.") ?></span> <br />
                </div>
                <?php // phpcs:enable ?>
            </div>
        </fieldset>
        <div id="tournaments" class="home_card box">
            <h2><?php echo _("Tournaments"); ?></h2>
            <!--div class="tournament-banner">
                <a href="<?php echo ROOTDIR;?>/cmmonthly">
                    <img src="<?php echo ROOTDIR;?>/images/tournaments/captainsmode_dmedition.png" width="100%" />
                    <span class="tournament-arrow"></span>
                </a>
            </div-->
            <div class="tournament-banner">
                <a href="<?php echo ROOTDIR;?>/allstars">
                    <img src="<?php echo ROOTDIR;?>/images/tournaments/allstars_banner.png" width="100%" />
                    <span class="tournament-arrow arrow-light"></span>
                </a>
            </div>
        </div>
   </div> <!-- #tab-content1 -->
   <div id="tab-content2" class="tab-content">
        <div class="pure-g">
            <div class="pure-u-1-1">
                <div class="card home_card">
                    <h2><span><?php echo _("Use preset"); ?></span></h2>
                    <div class="styled-select">
                        <select id="preset-chooser" class="text-primary">
                            <?php foreach ($this->presets as $preset) { ?>
                                <option value="<?= $preset->id ?>"><?= $preset->getTitle() ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="pure-g join-actions text-primary">
                        <div class="pure-u-1-1">
                            <div class="join-action" id="host_preset_game">
                                <div class="shadowbutton text-primary">
                                    <span><?php echo _("Host draft"); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
          <div class="pure-g">
            <div class="pure-u-1-1 selectionbox" >
                <div id="new_game" class="card">
                    <h2><span><?php echo _("Quick draft"); ?></span></h2>
                    <div class="pure-g">
                        <div class="pure-u-1-2">
                            <div class="gametype shadowbutton blue-glow"  >
                                <?php echo _("1 v 1"); ?>
                            </div>
                        </div>
                        <div class="pure-u-1-2">
                            <div class="gametype shadowbutton red-glow"   >
                                <?php echo _("2 v 2"); ?>
                            </div>
                        </div>
                        <div class="pure-u-1-2">
                            <div class="gametype shadowbutton green-glow" >
                                <?php echo _("3 v 3"); ?>
                            </div>
                        </div>
                        <div class="pure-u-1-2">
                            <div class="gametype shadowbutton yellow-glow">
                                <?php echo _("4 v 4"); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="join_game" class="home_card box">
            <h2><?php echo _("Join existing draft"); ?></h2>
            <form action="_join" method="get">
            <div class="centered text-primary info-card"><?php echo _("code:") ?></div>
            <div class="code">
                <input type='text' name='code' class="inset-input" />
            </div>
            <div class="pure-g join-actions text-primary">
                <div class="pure-u-1-1">
                    <div class="join-action">
                        <div class="shadowbutton text-primary" id="join-game-button">
                            <span><?php echo _("Join"); ?></span>
                        </div>
                        <input id="join-game-submit" type="submit" name="join" value="Join" style="display:none" />
                    </div>
                </div>
            </div>
            </form>
        </div>
   </div> <!-- #tab-content2 -->
   <div id="tab-content3" class="tab-content">
        <div id="spectate_game" class="home_card box">
            <h2><?php echo _("I have a code"); ?></h2>
            <form action="spectate" method="get">
            <div class="centered text-primary info-card"><?php echo _("code:"); ?></div>
            <div class="code">
                <input type='text' name='code' class="inset-input" />
            </div>
            <div class="pure-g join-actions text-primary">
                <div class="pure-u-1-1">
                    <div class="join-action">
                        <div class="shadowbutton text-primary" id="spectate-game-button">
                                <span><?php echo _("Spectate"); ?></span>
                        </div>
                        <input id="spectate-game-submit" type="submit" style="display:none"/>
                    </div>
                </div>
            </div>
            </form>
        </div>
        <?php if (count($this->last_drafts) > 0) { ?>
            <div id="recent_drafts" class="home_card box">
                <h2><?php echo _("Recent Drafts"); ?></h2>
                <table class="pure-table pure-table-horizontal recent-drafts">
                    <tbody>
                        <?php foreach ($this->last_drafts as $draft) { ?>
                            <tr>
                                <td class='recent-title'><?= $draft->title ?></td>
                                <td class='recent-users'>
                                    <?= $draft->players[0]->name." "._(" vs ")." ".$draft->players[1]->name ?>
                                </td>
                                <td class='recent-action'>
                                    <a href="<?= ROOTDIR ?>/spectate?code=<?= $draft->code ?>"
                                         class="text-primary shadowbutton">
                                        <?= $draft->isDone() ? _("Watch") : _("Watch Live") ?>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
   </div> <!-- #tab-content3 -->
   <div id="tab-content4" class="tab-content">
        <div class="pure-g">
            <div class="pure-u-1-1">
                <div class="card home_card">
                    <h2><span><?php echo _("Use preset"); ?></span></h2>
                    <div class="styled-select">
                        <select id="preset-chooser-practice" class="text-primary">
                            <?php foreach ($this->presets as $preset) { ?>
                                <option value="<?= $preset->id ?>"><?= $preset->getTitle() ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="pure-g join-actions text-primary">
                        <div class="pure-u-1-1">
                            <div class="join-action" id="practice_preset_game">
                                <div class="shadowbutton text-primary">
                                    <span><?php echo _("Practice"); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
          <div class="pure-g">
            <div class="pure-u-1-1 selectionbox" >
                <div id="new_game" class="home_card card">
                    <h2><span><?php echo _("Quick draft"); ?></span></h2>
                    <div class="pure-g">
                        <div class="pure-u-1-2">
                            <div class="gametype-practice shadowbutton blue-glow">
                                <?php echo _("1 v 1"); ?>
                            </div>
                        </div>
                        <div class="pure-u-1-2">
                            <div class="gametype-practice shadowbutton red-glow"   >
                                <?php echo _("2 v 2"); ?>
                            </div>
                        </div>
                        <div class="pure-u-1-2">
                            <div class="gametype-practice shadowbutton green-glow" >
                                <?php echo _("3 v 3"); ?>
                            </div>
                        </div>
                        <div class="pure-u-1-2">
                            <div class="gametype-practice shadowbutton yellow-glow">
                                <?php echo _("4 v 4"); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
