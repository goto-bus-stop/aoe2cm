
<div class="content">
<div class="login_message">
	<?php 
		if($message) {
			echo $message;
		}
	?>
</div>

<form action="login" id="login" method="post">
	<fieldset class="info-card text-primary">
		<legend><?php echo _("Login"); ?></legend>
		<table>
			<tr><td class="text-primary"><?php echo _("Username"); ?>:</td><td><input type="text" name="user" class="inset-input text-primary" /></td></tr>
			<tr><td class="text-primary"><?php echo _("Password"); ?>:</td><td><input type="password" name="pass" class="inset-input text-primary"/></td></tr>
			<tr><td colspan="2" style="text-align: center"><input type="submit" name="login" value="<?php echo _("Login"); ?>" class="shadowbutton text-primary" /></td></tr>
		</table>
	</fieldset>
</form>
</div>


