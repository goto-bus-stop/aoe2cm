<?php
include_once 'lib/uniqid.php';
include_once 'lib/civgrid.class.php';
include_once 'lib/constants.class.php';
include_once 'lib/TurnsGrid.class.php';

?>

<script>
var PRESET_ID = <?php echo $preset->id; ?>;

function change_preset_turn_type(type) {
	var val = {
		"preset_id": PRESET_ID,
		"type": type
	};
	
	$.ajax({
		url: "admin-ajax/set-preset-type",
		type: "post",
		data: val,
		datatype: "html",
		success: function(data){
			$("#user-turns .editable-turns").html(data).show();
			setup_editable_turns();
		},
		error: function() {
          $("#result").html('<?php echo _("There was an error sending a choice to the server."); ?>');
          $("#result").addClass('msg_error');
          $("#result").fadeIn(1500);
		}
	});
	return true;
}


function change_preset_aoe_version(version) {
	var val = {
		"preset_id": PRESET_ID,
		"version": version
	};
	
	$.ajax({
		url: "admin-ajax/set-preset-aoe-version",
		type: "post",
		data: val,
		datatype: "html",
		success: function(data){
			$("#admin-turns").html(data).show();
			setup_editable_pre_turns();
		},
		error: function() {
          $("#result").html('<?php echo _("There was an error changing aoe version."); ?>');
          $("#result").addClass('msg_error');
          $("#result").fadeIn(1500);
		}
	});
	return true;
}

$(document).ready(function(){

	

	setup_editable_turns();
	setup_editable_pre_turns();
	
	$("#user-turns-type").change(function() {
		change_preset_turn_type($(this).val());
	});

	$("#aoe-version").change(function() {
		change_preset_aoe_version($(this).val());
	});

	$('#preset-name').editable(function(value,settings) {
			set_preset_name(value);
			return(value);
		}, {
			cssclass: 'name-changer',
			type: 'text',
			tooltip: '<?php echo _("Click to edit..."); ?>',
			submit: '<?php echo _("OK"); ?>'
		});
	$('#preset-description').editable(function(value,settings) {
			set_preset_description(value);
			return(value);
		}, {
			type      : 'textarea',
			tooltip: '<?php echo _("Click to edit..."); ?>',
        	cancel    : '<?php echo _("Cancel"); ?>',
			submit: '<?php echo _("OK"); ?>'
		});
});
</script>

<div class="header">
	<a href="<?php echo ROOTDIR; ?>/admin?nocache=<?php echo rand_uniqid(rand()); ?>" class="shadowbutton text-primary"><?php echo _("Done"); ?></a>
</div>

<div class="content admin box preset-edit">

<div id="preset-name" class="centered text-primary info-card"><?php echo $preset->name; ?></div> 

<div id="preset-description" class="centered text-primary info-card"><?php echo $preset->description; ?></div> 

<div class="centered text-primary">
	<select id="aoe-version">
		<?php
		foreach(Draft::AOC_VERSIONS as $version) {
			echo "<option value=\"{$version}\" ".(($version == $preset->get_aoe_version())?"selected":"").">".Draft::aoe_version_get_str($version)."</option>";
		}
		?>
	</select>
</div>
<div class="centered text-primary info-card"><?php echo _("Admin turns"); ?></div>
<div id="admin-turns" class="editable-turns">
<?php
	$turns_grid = new TurnsGrid($preset->get_aoe_version());
	$turns_grid->editablePreTurns($preset->get_preset_pre_turns());
?>
</div>

<div class="centered text-primary info-card"><?php echo _("User turns"); ?></div>
<div id="user-turns">
	<select id="user-turns-type">
		<?php
		foreach(Draft::TYPES as $turn_type) {
			echo "<option value=\"{$turn_type}\" ".(($turn_type == $preset->type)?"selected":"").">".Draft::type_get_str($turn_type)."</option>";
		}
		?>
	</select>
<?php
	echo "<div class=\"editable-turns\">";
	$turns_grid->editableTimeline($preset->get_preset_turns());
	echo "</div>";

?>
</div>

<fieldset id="instructions">
	<legend>Legend</legend>
		<div class="double-outer-border">
			<div class="double-inner-border">
				<table class="pure-table pure-table-bordered">
				<tr><td><b>Player Both 1/Player Both 2</b></td><td>For parallel turns. Always should follow each other, and Player Both 1 should always come first</td></tr>
				<tr><td><b>Player None</b></td><td>Admin turn</td></tr>
				<tr><td><b>global ban/pick</b></td><td>Nobody can pick or ban the given civilization later</td></tr>
				<tr><td><b>show ban/pick and reveal all</b></td><td>Should be an admin turn, always after hidden turns revealing the picks, bans or both.</td></tr>
				<tr><td><b>exclusive ban/pick</b></td><td>The given captain cannot choose the same civ twice for a given action. For example, if he picked mayans before, he can't pick mayans again, but still can ban mayans. Has no effect on the opponent other than the base action (pick/ban)</td></tr>
				<tr><td><b>disable ban/pick/completely</b></td><td>Grays out the given civilization and it cannot be picked or banned for the given action after that point. Meant to be used as admin turn. completely = pick + ban.</td></tr>
				<tr><td><b>hide</b></td><td>Completely hides the civilization. Should be a pre-turn.</td></tr>
			</table>
		</div>
	</div>
</fieldset>

</div>