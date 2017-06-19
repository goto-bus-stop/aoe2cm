
<div class="header">
	<a href="<?php echo ROOTDIR; ?>/logout" class="text-primary shadowbutton"><?php echo _("Logout"); ?></a>
	<a href="<?php echo ROOTDIR; ?>/maintenance" class="text-primary shadowbutton"><?php echo _("Maintenance"); ?></a>
</div>

<div class="admin content">
	<div class="double-outer-border">
		<div class="double-inner-border">
			<div id="newpreset">
				<p>
					<form action="preset-new" method="get">
						<span class="text-primary"><?php echo _("New preset"); ?>:</span>
						<input type="text" name="name" size="64" class="inset-input text-primary"/>
						<input type="submit" name="new" value="Create" class="shadowbutton text-primary"/>
					</form>
				</p>
			</div>

			<table class="presets pure-table pure-table-horizontal">
				<thead>
					<tr>
						<td><?php echo _("Name"); ?></td>
						<td><?php echo _("Enabled"); ?></td>
						<td><?php echo _("Actions"); ?></td>
					</tr>
				</thead>
				<tbody>
<?php

;
			foreach ($presets as $preset) {
				echo "<tr>\n";
				echo "<td>".$preset->name."</td>\n";
				if($preset->state == Preset::PRESET_ENABLED){
					echo "<td>"._("true")."</td>\n";
				} else {
					echo "<td>"._("false")."</td>\n";
				}
				echo "<td>";
				echo "<a href=\"".ROOTDIR."/preset-history?p=".$preset->id."\" class=\"text-primary shadowbutton\">history</a> &nbsp; ";
				echo "<a href=\"".ROOTDIR."/preset-edit?p=".$preset->id."\" class=\"text-primary shadowbutton\">edit</a> &nbsp; ";
				if($preset->state == Preset::PRESET_DISABLED){
					echo "<a href=\"".ROOTDIR."/preset-enable?e=1&p=".$preset->id."\" class=\"text-primary shadowbutton\">"._("enable")."</a> &nbsp; ";
				} else {
					echo "<a href=\"".ROOTDIR."/preset-enable?e=0&p=".$preset->id."\" class=\"text-primary shadowbutton\">"._("disable")."</a> &nbsp; ";
				}
				
				echo "<a href=\"".ROOTDIR."/preset-delete?p=".$preset->id."\" class=\"text-primary shadowbutton\">delete</a>";
				echo "</td></tr>\n";
			}

?>
				</tbody>
			</table>
		</div>
	</div>

	<div class="double-outer-border" style="display: none">
		<div class="double-inner-border">
			<div id="newtournament">
				<p>
					<form action="tournament-new" method="post">
						<span class="text-primary"><?php echo _("New tournament"); ?>:</span><br />
						<span><?php echo _("Name"); ?>:</span><input type="text" name="name" size="64" class="inset-input text-primary"/><br />
						<span><?php echo _("Description"); ?>:</span><textarea rows="5" cols="50" name="description" maxlength="255" class="inset-input text-primary"></textarea><br />
						<input type="submit" name="new" value="Create" class="shadowbutton text-primary"/>
					</form>
				</p>
			</div>

			<table class="tournaments pure-table pure-table-horizontal">
				<thead>
					<tr>
						<td><?php echo _("Name"); ?></td>
						<td><?php echo _("Query"); ?></td>
						<td><?php echo _("Actions"); ?></td>
					</tr>
				</thead>
				<tbody>
<?php
			foreach ($tournaments as $tournament) {
				echo "<tr>\n";
				echo "<td>".$tournament->name."</td>\n";
				echo "<td>".htmlentities($tournament->description)."</td>\n";
				echo "<td>";
				
				echo "<a href=\"".ROOTDIR."/tournament-delete?id=".$tournament->id."\" class=\"text-primary shadowbutton\">delete</a>";
				echo "</td></tr>\n";
			}
?>
				</tbody>
			</table>
		</div>
	</div>
</div>
