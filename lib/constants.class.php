<?php

require_once 'models/draft.class.php';
require_once 'models/player.class.php';
require_once 'models/turn.class.php';

class Constants
{
    const LOGGED_IN = 'logged_in';
    const UNIQ_SALT = 'Age of Empires Captains Mode';

    const CIV_HIDDEN = -1;
    const CIV_RANDOM = 0;
    const AOC_CIV_COUNT = 18;
    const AOF_CIV_COUNT = 23;
    const AOAK_CIV_COUNT = 27;
    const AOR_CIV_COUNT = 31;
    const RANDOM_CHOICE_OFFSET = 36;

        public static function js_constants() {
        ?>
<script>
var STATE_ERROR = <?php echo Draft::STATE_ERROR; ?>;
var STATE_WAITING = <?php echo Draft::STATE_WAITING; ?>;
var STATE_STARTING = <?php echo Draft::STATE_STARTING; ?>;
var STATE_READY = <?php echo Draft::STATE_READY; ?>;
var STATE_STARTED = <?php echo Draft::STATE_STARTED; ?>;
var STATE_DONE = <?php echo Draft::STATE_DONE; ?>;
var CHOICE_PICK = <?php echo Turn::DO_PICK; ?>;
var CHOICE_BAN = <?php echo Turn::DO_BAN; ?>;
var CHOICE_OTHER = <?php echo Turn::DO_OTHER; ?>;
var CHOICE_HIDE = <?php echo Turn::DO_HIDE; ?>;
var DISCONNECT_TIMEOUT = <?php echo Draft::DISCONNECT_TIMEOUT; ?>;;
var TIMEOUT = <?php echo Draft::TIMEOUT; ?>;
var PADDING_TIME = <?php echo Draft::PADDING_TIME; ?>;
var CIV_RANDOM = <?php echo self::CIV_RANDOM; ?>;
var CIV_HIDDEN = <?php echo self::CIV_HIDDEN; ?>;

var PLAYER_1 = <?php echo Player::PLAYER_1; ?>;
var PLAYER_2 = <?php echo Player::PLAYER_2; ?>;
</script>
        <?php
    }

}
