

function add_turn(index) {
	var val = {
		"preset_id": PRESET_ID,
		"index": index
	};
	
	$.ajax({
		url: "admin-ajax/add-turn",
		type: "post",
		data: val,
		datatype: "html",
		success: function(data){
			$("#user-turns .editable-turns").html(data).show();
			setup_editable_turns();
		},
		error: function() {
          $("#result").html('There was an error sending a choice to the server.');
          $("#result").addClass('msg_error');
          $("#result").fadeIn(1500);
		}
	});
	return true;
}

function delete_turn(index) {
	var val = {
		"preset_id": PRESET_ID,
		"index": index
	};
	
	$.ajax({
		url: "admin-ajax/delete-turn",
		type: "post",
		data: val,
		datatype: "html",
		success: function(data){
			$("#user-turns .editable-turns").html(data).show();
			setup_editable_turns();
		},
		error: function() {
          $("#result").html('There was an error sending a choice to the server.');
          $("#result").addClass('msg_error');
          $("#result").fadeIn(1500);
		}
	});
	return true;
}

function change_turn(val) {
	$.ajax({
		url: "admin-ajax/change-turn",
		type: "post",
		data: val,
		datatype: "html",
		success: function(data){
			$("#user-turns .editable-turns").html(data).show();
			setup_editable_turns();
		},
		error: function() {
          $("#result").html('There was an error sending a choice to the server.');
          $("#result").addClass('msg_error');
          $("#result").fadeIn(1500);
		}
	});
}

function change_turn_player(index, player) {
	var val = {
		"preset_id": PRESET_ID,
		"index": index,
		"role": player
	};

	change_turn(val);
	
	return true;
}

function change_turn_action(index, action) {
	var val = {
		"preset_id": PRESET_ID,
		"index": index,
		"action": action
	};

	change_turn(val);
	
	return true;
}

function change_turn_hidden(index, hidden) {
	var val = {
		"preset_id": PRESET_ID,
		"index": index,
		"hidden": (hidden)?1:0
	};

	change_turn(val);
	
	return true;
}

function setup_editable_turns() {

	$(".turn-add").click(function(e){
        e.stopPropagation();
		add_turn($(this).data('id'));
	});
	$(".turn-delete").click(function(e){
        e.stopPropagation();
		delete_turn($(this).data('id'));
	});

	$(".turn-player").change(function(){
		change_turn_player($(this).data('id'), $(this).val());
	});

	$(".turn-action").change(function(){
		change_turn_action($(this).data('id'), $(this).val());
	});
	$(".turn-hidden").change(function(){
		change_turn_hidden($(this).data('id'), $(this).is(':checked'));
	});
}

function add_pre_turn(index) {
	var val = {
		"preset_id": PRESET_ID,
		"index": index
	};
	
	$.ajax({
		url: "admin-ajax/add-pre-turn",
		type: "post",
		data: val,
		datatype: "html",
		success: function(data){
			$("#admin-turns").html(data).show();
			setup_editable_pre_turns();
		},
		error: function() {
          $("#result").html('There was an error sending a choice to the server.');
          $("#result").addClass('msg_error');
          $("#result").fadeIn(1500);
		}
	});
	return true;
}

function delete_pre_turn(index) {
	var val = {
		"preset_id": PRESET_ID,
		"index": index
	};
	
	$.ajax({
		url: "admin-ajax/delete-pre-turn",
		type: "post",
		data: val,
		datatype: "html",
		success: function(data){
			$("#admin-turns").html(data).show();
			setup_editable_pre_turns();
		},
		error: function() {
          $("#result").html('There was an error sending a choice to the server.');
          $("#result").addClass('msg_error');
          $("#result").fadeIn(1500);
		}
	});
	return true;
}

function change_pre_turn(val) {
	$.ajax({
		url: "admin-ajax/change-pre-turn",
		type: "post",
		data: val,
		datatype: "html",
		success: function(data){
			$("#admin-turns").html(data).show();
			setup_editable_pre_turns();
		},
		error: function() {
          $("#result").html('There was an error sending a choice to the server.');
          $("#result").addClass('msg_error');
          $("#result").fadeIn(1500);
		}
	});
}

function change_pre_turn_player(index, player) {
	var val = {
		"preset_id": PRESET_ID,
		"index": index,
		"role": player
	};

	change_pre_turn(val);
	
	return true;
}

function change_pre_turn_action(index, action) {
	var val = {
		"preset_id": PRESET_ID,
		"index": index,
		"action": action
	};

	change_pre_turn(val);
	
	return true;
}

function change_pre_turn_civ(index, civ) {
	var val = {
		"preset_id": PRESET_ID,
		"index": index,
		"civ": civ
	};

	change_pre_turn(val);
	
	return true;
}

function setup_editable_pre_turns() {

	$(".pre-turn-add").click(function(e){
        e.stopPropagation();
		add_pre_turn($(this).data('id'));
	});
	$(".pre-turn-delete").click(function(e){
        e.stopPropagation();
		delete_pre_turn($(this).data('id'));
	});

	$(".pre-turn-player").change(function(){
		change_pre_turn_player($(this).data('id'), $(this).val());
	});

	$(".pre-turn-action").change(function(){
		change_pre_turn_action($(this).data('id'), $(this).val());
	});
	$(".pre-turn-civ").change(function(){
		change_pre_turn_civ($(this).data('id'), $(this).val());
	});
}

function set_preset_name(new_name) {
	
	var val = {
		"preset_id": PRESET_ID,
		"name": new_name
	};
	
	$.ajax({
		url: "admin-ajax/set-preset-name",
		type: "post",
		data: val,
		datatype: "html",
		success: function(data){
			$('#preset-name').html(data);
		},
		error: function() {
          $("#result").html('There was an error sending a choice to the server.');
          $("#result").addClass('msg_error');
          $("#result").fadeIn(1500);
		}
	});
	return true;
}

function set_preset_description(new_description) {
	
	var val = {
		"preset_id": PRESET_ID,
		"description": new_description
	};
	
	$.ajax({
		url: "admin-ajax/set-preset-description",
		type: "post",
		data: val,
		datatype: "html",
		success: function(data){
			$('#preset-description').html(data);
		},
		error: function() {
          $("#result").html('There was an error sending a choice to the server.');
          $("#result").addClass('msg_error');
          $("#result").fadeIn(1500);
		}
	});
	return true;
}
