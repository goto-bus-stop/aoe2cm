<?php
function overlay_view ($code) {
    $previous = ROOTDIR;
    $go_back = _("Go to home page");
    if(isset($_SERVER['HTTP_REFERER'])) {
        $previous = $_SERVER['HTTP_REFERER'];
        $go_back = _("Go back");
    }

?>
<div id="overlay" class="text-primary">
     <div id="waiting-message">
		<h2><span><?php echo _("Please wait..."); ?></span></h2>
		<p><?php echo _("Waiting for the other captain to join."); ?>
        <?php _("Send <b>privately</b> this code:"); ?>
        <br /> <input type="text" class="inset-input" value="<?php echo $code; ?>" readonly>
        <br /> <?php _("or url: "); ?>
        <br />
			<input type="text" class="inset-input copy-url" value="http://<?php echo $_SERVER['HTTP_HOST'].ROOTDIR.'/join?code='.$code; ?>" readonly>
		</p>
		<span><a href='<?php echo $previous; ?>'><span class='back-icon'><?php echo $go_back; ?></span></a></span>
     </div>
     <div id="hosting-message">
		<h2><span><?php echo _("Please wait..."); ?></span></h2>
		<p><?php echo _("Waiting for captains to join. Send this code first to host then guest:"); ?> <br /> <input type="text" class="inset-input" value="<?php echo $code; ?>" readonly> <br />
			<?php echo _("Alternatively send this url to the host captian:"); ?> <br />
			<input type="text" class="inset-input copy-url" value="http://<?php echo $_SERVER['HTTP_HOST'].ROOTDIR.'/join?role=0&code='.$code; ?>" readonly><br />
			<?php echo _("and this url to the guest captain:"); ?><br />
			<input type="text" class="inset-input copy-url" value="http://<?php echo $_SERVER['HTTP_HOST'].ROOTDIR.'/join?role=1&code='.$code; ?>" readonly>
		</p>
		<span><a href='<?php echo $previous; ?>'><span class='back-icon'><?php echo $go_back; ?></span></a></span>
     </div>
	 <div id="error-message">
     	  <h2 class="error"><span><?php echo _("Error"); ?></span></h2>
		<p><?php echo _("A network error occured, please try a new game."); ?></p>
		<span><a href='<?php echo $previous; ?>'><span class='back-icon'><?php echo $go_back; ?></span></a></span>
	 </div>
	 <div id="unfinished-message">
     	  <h2 class="error"><span><?php echo _("Error"); ?></span></h2>
		<p><?php echo _("This game was not finished due to network error or one of the users disconnecting."); ?></p>
		<span><a href='<?php echo $previous; ?>'><span class='back-icon'><?php echo $go_back; ?></span></a></span>
	 </div>
	 <div id="disconnected-message">
     	<h2 class="error"><span><?php echo _("Error"); ?></span></h2>
		<p><?php echo _("The other user disconnected."); ?></p>
		<span><a href='<?php echo $previous; ?>'><span class='back-icon'><?php echo $go_back; ?></span></a></span>
	 </div>
	 <div id="nogame-message" >
     	<h2 class="error"><span><?php echo _("Error"); ?></span></h2>
		<p><?php echo _("No such game found."); ?></p>
		<span><a href='<?php echo $previous; ?>'><span class='back-icon'><?php echo $go_back; ?></span></a></span>
	 </div>
</div>

<?php } ?>