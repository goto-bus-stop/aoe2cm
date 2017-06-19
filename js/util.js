
function Timer(callback, delay) {
    var timerId, start, remaining = delay;
    var paused = false;

    this.isPaused = function() {
    	return paused;
    };

    this.pause = function() {
        window.clearTimeout(timerId);
        remaining -= new Date() - start;
        paused = true;
    };

    this.resume = function() {
    	paused = false;
        start = new Date();
        window.clearTimeout(timerId);
        timerId = window.setTimeout(callback, remaining);
    };

    this.stop = function() {
    	clearTimeout(timerId);
    };

    this.resume();
}

String.prototype.format = function() {
    var s = this,
        i = arguments.length;

    while (i--) {
        s = s.replace(new RegExp('\\{' + i + '\\}', 'gm'), arguments[i]);
    }
    return s;
};

function setup_themeswitcher() {
	//check theme
	if(typeof Cookies.get("theme") === "undefined") {
		Cookies.set("theme", "material", {expires: 365});
	}

	$("#themeswitcher").addClass("theme-"+Cookies.get("theme"));

	$("#themeswitcher").click(function() {
		var theme = Cookies.get("theme");
		if(typeof theme === "undefined" || theme == "material-chroma") {
			theme = "material";
		} else if(theme == "material") {
			theme = "material-dark";
		} else if(theme == "material-dark") {
			theme = "material-chroma";
		} else {
			theme = "material-dark";
		}
		Cookies.set("theme", theme, {expires: 365});
		location.reload();
	});
}

function overlay(show_overlay, message) {

	if(typeof message == 'undefined') {
		message = 'nogame';
	}

	$('#overlay div').css('display', 'none');
	$('#overlay #'+message+'-message').css('display', 'block');

	el = document.getElementById("overlay");
	el.style.visibility = show_overlay ? "visible" : "hidden";
}

function get_state(state_callback) {
	/* Send the data using post and put the results in a div */
	var val = {
		"id": DRAFT_ID,
		"turn": _storedTurn
	};
  $.ajax({
      url: "draft-state",
      type: "get",
      data: val,
	  datatype: "json",
      success: function(data){
		  state_callback(data);
      },
      error:function(){
      	  state_callback({'error': $("#action_msg_error_update").html()});
      }
    });
}

function send_ready(state_callback) {
	/* Send the data using post and put the results in a div */
	var val = {
		"id": DRAFT_ID
	};
  $.ajax({
      url: "draft-ready",
      type: "post",
      data: val,
	  datatype: "json",
      success: function(data){
		  state_callback(data);
      },
      error:function(){
      	  state_callback({'error': $("#action_msg_error_sending_ready").html()});
      }
    });
}

function send_start(state_callback) {
	/* Send the data using post and put the results in a div */
	var val = {
		"id": DRAFT_ID
	};
  $.ajax({
      url: "draft-start",
      type: "post",
      data: val,
	  datatype: "json",
      success: function(data){
		  state_callback(data);
      },
      error:function(){
      	  state_callback({'error': $("#action_msg_error_starting").html()});
      }
    });
}

function send_pick(civ_id, state_callback) {

	if(_storedTurn < 0) {
		return false;
	}

	var val = {
		"draft_id": DRAFT_ID,
		"civ": civ_id,
		"turn_no": _storedTurn
	};

	$.ajax({
		url: "draft-choose",
		type: "post",
		data: val,
		datatype: "json",
		success: function(data){
			state_callback(data);
		},
		error: function() {
      	  state_callback({'error':  $("#action_msg_error_pic_ban").html()});
		}
	});
	return true;
}

function send_name(new_name, state_callback) {

	var val = {
		"draft_id": DRAFT_ID,
		"name": new_name
	};

	$.ajax({
		url: "set-name",
		type: "post",
		data: val,
		datatype: "json",
		success: function(data){
			var p_captain_name = new_name.substr(0, 32);
			Cookies.set("username", p_captain_name, {expires: 365});
			state_callback(data);
		},
		error: function() {
      	  state_callback({'error': $("#action_msg_error_set_name").html()});
		}
	});
	return true;

}

function start_countdown(timeout, timeout_cb) {
	$('#countdown-timer').removeClass('countdown-timer-last');
	var countdown = $('#countdown-timer').data('countdown');
	if(typeof countdown == 'undefined') {
		$('#countdown-timer').countdown({
			refresh: 250,
			date: +(new Date) + timeout*1000,
			render: function(data) {
				if(data.min == 0 && data.sec <= 10) {
					$(this.el).addClass('countdown-timer-last');
				}
			  $(this.el).text(this.leadingZeros(data.min, 1)+ ":"+this.leadingZeros(data.sec, 2));
			},
			onEnd: function() {
			  $(this.el).removeClass('countdown-timer-last');
			  timeout_cb();
			}
		});
	} else {
		countdown.update(+(new Date) + timeout*1000);
		countdown.options.onEnd = function() {
		  $(this.el).removeClass('countdown-timer-last');
		  timeout_cb();
		}
		countdown.start();
	}

}

function pause_countdown() {
	var countdown = $('#countdown-timer').data('countdown');
	if( typeof countdown != 'undefined') {
		countdown.stop();
		countdown.remaining = countdown.getDiffDate();
	}
}


function resume_countdown() {
	var countdown = $('#countdown-timer').data('countdown');
	if( typeof countdown != 'undefined') {
		if(typeof countdown.remaining != 'undefined') {
			var remaining =
				countdown.remaining.min*60000+
				countdown.remaining.sec*1000 +
				countdown.remaining.millisec;
			countdown.update(+(new Date) + remaining);
		}
		countdown.start();
	}
}

function stop_countdown() {
	var countdown = $('#countdown-timer').data('countdown');
	if( typeof countdown != 'undefined') {
		countdown.stop();
	}
}

function update_turns(currentTurn) {
	$('.turn').each(function(index){
		if(currentTurn >= TURNS.length) {
			$(this).fadeTo(420, 1);
		} else if(currentTurn > index) {
			$(this).fadeTo(420, 0.125);
		} else if(currentTurn == index) {
			$(this).fadeTo(420, 1);
		} else {
			$(this).fadeTo(420, 0.5);
		}
	});
}


function update_chosen(currentTurn, activate, showall) {
	if (activate === undefined) activate = true;
	if (showall === undefined) showall = false;
	$('.pick,.ban').removeClass('active-choice');

	$.each(_storedTurns, function(i, value) {
		var turn_i = parseInt(i);
		if(turn_i < currentTurn || showall) {

			var i_civ = value.civ;
			var random_pick = false;
			if('civ_random' in value && value['civ_random'] == true) {
				$('.turn-no-'+i).addClass('random-pick');
			}
			$('.choice > div').eq(i_civ).clone().appendTo($('.turn-no-'+i+' .box-content').html("").css('visibility', 'visible'));
			$('.turn-no-'+i).fadeTo(420, 1.0);
			$(".turn-no-"+i+" img").tooltipster({
				theme: 'aoecm-tooltip',
				animation: 'fade',
				delay: 640,
				touchDevices: false,
				position: 'bottom',
				arrow: true,
				content: $('.civ-bonus').eq(value.civ)
			});
		}
	});

	if(activate) {
		$('.turn-no-'+currentTurn).fadeTo(420, 1.0).addClass('active-choice');
	}
}

function clear_choices() {

	$(".choice").removeClass('choice-pick choice-ban');
	$(".choice").removeClass('choice-chosen choice-banned choice-disabled');

	//update choices
	$.each(_storedTurns, function(i, value){
		if(value.action == CHOICE_HIDE && value.civ > 0) {
			$(".choice").eq(value.civ).addClass('choice-hidden');
			gblDisabledCivs.push(value.civ);
		}
	});
}

function spectator_show_choices() {

	$(".choice").eq(0).hide();
	clear_choices();
}

function update_choices(currentTurn, userIndex) {

	$(".choice").removeClass('choice-pick choice-ban');
	$(".choice").removeClass('choice-chosen choice-banned choice-disabled');

	//update choices
	$.each(_storedTurns, function(i, value){
		if(value.action == CHOICE_HIDE && value.civ > 0) {
			$(".choice").eq(value.civ).addClass('choice-hidden');
			gblDisabledCivs.push(value.civ);
		}
	});

	if(userIndex == TURNS[currentTurn]["player"]){
		if(TURNS[currentTurn].action == CHOICE_PICK) {
			$(".choice").addClass('choice-pick');
		} else {
			$(".choice").addClass('choice-ban');
		}

		var current_action = TURNS[currentTurn].action;

		$.each(_storedTurns, function(i, value) {
			var disabled_civs = [];
			if(current_action == CHOICE_PICK) {
				var disabled_civs = value.disabled_picks[userIndex];
			} else {
				var disabled_civs = value.disabled_bans[userIndex];
			}

			$.each(disabled_civs, function(index, dciv) {
				var disabled_element = $(".choice").eq(dciv);
				disabled_element.addClass('choice-disabled').removeClass('choice-pick choice-ban');
				gblDisabledCivs.push(dciv);
			});

			//highlight bans and picks
			if(value.civ > 0) {
				if(current_action == CHOICE_PICK) {

					if(userIndex == value.player && value.action == CHOICE_PICK) {
						$(".choice").eq(value.civ).addClass('choice-chosen');
					}

				} else if(current_action == CHOICE_BAN) {
					if(userIndex == value.player && value.action == CHOICE_BAN) {
						$(".choice").eq(value.civ).addClass('choice-banned');
					}

					if(userIndex != value.player && value.action == CHOICE_PICK) {
						$(".choice").eq(value.civ).addClass('choice-chosen');
					}
				}
			}

		});

		active_user = true;
	} else {
		$(".choice").addClass('choice-disabled');
		if(TURNS[currentTurn]["player"] == (1-userIndex)){
			$.each(_storedTurns, function(i, value){
				var disabled_civs = [];
				if(current_action == CHOICE_PICK) {
					var disabled_civs = value.disabled_picks[1 - userIndex];
				} else {
					var disabled_civs = value.disabled_bans[1 - userIndex];
				}

				$.each(disabled_civs, function(index, dciv) {
					gblDisabledCivs.push(dciv);
				});
			});
		}
	}
}


var _updateTimeout = null;
var _storedTurn = -36;
var _storedNames = null;
var _storedTurns = {};


function timout_callback() {
	if(!gblActiveUser) {
		return;
	}
	gblActiveUser = false;
	$(".choice").removeClass('choice-pick choice-ban');
	$("#action-text .action-string").html("<span class=\"red-glow\">"+$("#action_msg_too_late_random").html()+"</span>");
	var random_pick = 0;
	send_pick(random_pick, update_draw);
}

function update_refresh() {

	clearTimeout(_updateTimeout);
	_updateTimeout = setTimeout(function() {
			get_state(update_draw);
		}, 1000);
}

_errorTimeout = null;

function show_error(error_msg) {
	$("#action-message div").html(error_msg);
	$("#action-message div").slideDown(250);
	clearTimeout(_errorTimeout);
	_errorTimeout = setTimeout(function() {
		$("#action-message div").slideUp(250);
	}, 2000);
}

function update_draw(draftData) {

	if(draftData.error) {
		show_error(draftData.error);
		update_refresh();
		return;
	}

	if(draftData.msg) {
		show_error(draftData.msg);
	}

	//disable user action
	gblActiveUser = false;

	var currentTurn = draftData.current_turn;
	var userIndex = draftData.role;
	if(typeof currentTurn === 'undefined') {
		show_error($("#action_msg_data_connection_issues").html()+' '+draftData.msg)
		update_refresh();
		return;
	}

	if(_storedTurn != currentTurn) {
		_storedTurn = currentTurn;
	}

	//update Turns
	if(draftData.turns) {
		$.each(draftData.turns, function(i, value){
			_storedTurns[value.no] = value;
		});
	}

	//if draft state is error
	if(draftData.state == STATE_ERROR) {
		overlay(true, 'unfinished');
		clearTimeout(_updateTimeout);
		stop_countdown();
		return;
	}

	//show overlay if not yet started
	if(draftData.state == STATE_WAITING) {

		update_chosen(currentTurn, false);
		//update_choices(currentTurn, userIndex);
		clear_choices();
		update_refresh();
		if(draftData.player_count !== 'undefined' && draftData.player_count == 0) {
			overlay(true, 'hosting');
		} else {
			overlay(true, 'waiting');
		}
		return;
	}
	else {
		overlay(false, 'waiting');
	}

	//check if initialized
	if(_storedNames == null) {
		_storedNames = [$("#drafter_msg_host_captain").text(), $("#drafter_msg_guest_captain").text()];
	}
	//We are already on the way, update player names
	//update player names
	if(typeof draftData.players !== 'undefined') {
		for(var i = 0; i < 2; i++) {
			if(draftData.players[i] != _storedNames[i]){
				_storedNames[i] = draftData.players[i];
				if(!$('#player-'+i+' .player-name form').length) {
					$('#player-'+i+' .player-name').html(draftData.players[i]);
				}
			}
		}
	}

	if(draftData.state == STATE_DONE) {

		$(".player").removeClass('player-inactive');
		clear_choices();
		$(".choice").addClass('choice-disabled');
		if(userIndex >= PLAYER_1) {
			$("#action-text .action-string").html($("#action_msg_draft_ended").html()+
				"<br /><span class=\"extra-info\">" +
				$("#action_msg_paste_code").html().format("<span class=\"draft-code\">"+DRAFT_CODE+"</span>") +
				"</span>");
		} else {
			$("#action-text .action-string").html($("#action_msg_draft_ended").html()+
				"<br /><span class=\"extra-info\">" +
				$("#action_msg_paste_code").html().format("<span class=\"draft-code\">"+DRAFT_CODE+"</span>") +
				"</span>");
		}
		clearTimeout(_updateTimeout);
		stop_countdown();

		update_turns(currentTurn);
		update_chosen(currentTurn, false, true);

		return;
	}

	//if we are only starting the draft, we should let the players get ready and they should notify each other

	if(draftData.state == STATE_STARTING) {

		update_chosen(currentTurn, false);
		//update_choices(currentTurn, userIndex);
		clear_choices();
		//user 2 should get ready
		if(userIndex == PLAYER_2) {
			//show ready button
			clearTimeout(_updateTimeout);
			$("#action-text .action-string").html(
				$("#action_msg_ready_msg").html()
					.format("<div class=\"shadowbutton text-primary\" id=\"draft-ready-button\">" +
							$("#action_msg_ready").html() +
							"</div>"));
			$("#draft-ready-button").click(function() {
				send_ready(update_draw);
			});
		} else {
			//the others waiting for the second player
			$("#action-text .action-string").html($("#action_msg_waiting_guest").html());
			update_refresh();
		}
		return;
	}

	if(draftData.state == STATE_READY) {

		update_chosen(currentTurn, false);
		//update_choices(currentTurn, userIndex);
		clear_choices();

		//host should see a button to start the draft
		//user 1 should start draft
		if(userIndex == PLAYER_1) {
			//show ready button
			$("#action-text .action-string").html(
				$("#action_msg_guest_ready").html() + " " +
				$("#action_msg_send_code").html().format("<span class=\"draft-code\">"+DRAFT_CODE+"</span>") + " " +
				$("#action_msg_click_to_begin").html().format("<div class=\"shadowbutton text-primary\" id=\"draft-start-button\">" + " " +
					$("#action_msg_start").html()+"</div>"));

			$("#draft-start-button").click(function() {
				send_start(update_draw);
			});
			clearTimeout(_updateTimeout);
		} else {
			//the others waiting for the second player
			$("#action-text .action-string").html($("#action_msg_waiting_host").html() +
				"<br />" +
				 $("#action_msg_send_code").html().format("<span class=\"draft-code\">"+DRAFT_CODE+"</span> ."));
			update_refresh();
		}
		return;
	}

	//this means we are in STATE_STARTED

	//if not yet started, just waiting for the countdown
	if(currentTurn <= 0 && draftData.time_passed < 0) {

		draftData.time_passed = -draftData.time_passed;
		clearTimeout(_updateTimeout);

		$("#action-text .action-string").html($("#action_msg_starting_draft_countdown").html().format("<div id=\"countdown-timer\">0</div>"));

		//activate only the player this side is using
		$(".player").removeClass('player-inactive');
		if(userIndex >= PLAYER_1) {
			$("#player-"+(1-userIndex)+" .player").addClass('player-inactive');
		}

		$(".choice").addClass('choice-disabled');
		start_countdown(draftData.time_passed, function(){
			get_state(update_draw);
			});

		return;
	}

	var currentPlayer = draftData.active_player;

	var timeoutLeft = TIMEOUT + PADDING_TIME - draftData.time_passed;

	if(timeoutLeft < -DISCONNECT_TIMEOUT) {
		if(userIndex == currentPlayer){
			overlay(true, 'error');
		} else {
			overlay(true, 'disconnected');
		}
		clearTimeout(_updateTimeout);
		stop_countdown();
		return;
	}

	var activeUser = (userIndex == currentPlayer);

	if(activeUser || userIndex < 0) { //we are the active user, or we are spectators
		var currentAction = TURNS[currentTurn]['action'];
		var temptext = $("#action_msg_text_"+currentAction).html();
		var getreadytext = $("#action_msg_get_ready").html();
		$("#action-text .action-string").html(temptext+"... <div id=\"countdown-timer\" class=\"countdown-timer-last\">"+getreadytext+"</div>");
	} else {
		$("#action-text .action-string").html($("#action_msg_waiting_other").html().format("<div id=\"countdown-timer\"></div>"));
	}

	update_turns(currentTurn);
	update_chosen(currentTurn, true, true);

	//rest choices
	$(".player").removeClass('player-inactive');
	$("#player-"+(1-currentPlayer)+" .player").addClass('player-inactive');

	gblDisabledCivs = [];

	update_choices(currentTurn, userIndex);

	clearTimeout(_updateTimeout);

	gblActiveUser = false;
	//update interval
	if(activeUser) {

		var active_function = function(){
			//activate a bit later
			gblActiveUser = true;
			start_countdown(Math.min(Math.max(timeoutLeft,0.01), TIMEOUT), timout_callback);
		};
		if(timeoutLeft <= TIMEOUT) {
			active_function();
		} else {
			stop_countdown();
			_updateTimeout = setTimeout(active_function, Math.max(timeoutLeft - TIMEOUT, 0)*1000);
		}
	} else {
		var waiting_function = function() {
			start_countdown(Math.min(Math.max(timeoutLeft,0.01), TIMEOUT), function(){});
			_updateTimeout = setInterval(function() {
					get_state(update_draw);
				}, 1000);
		};
		if(timeoutLeft <= TIMEOUT) {
			waiting_function();
		} else {
			stop_countdown();
			_updateTimeout = setTimeout(waiting_function, Math.max(timeoutLeft - TIMEOUT, 0)*1000);
		}
	}
}

var _spectatorTimer = new Timer(function(){}, 0);
var _spectatorTurn = -36;

function spectator_set_live() {
	$("#draft-title").append(' - Live');
	$("#spectator-controls").slideUp(240);
}

function spectator_next_turn() {
	stop_countdown();
	_spectatorTimer.stop();
	_spectatorTurn += 1;
	get_state(update_spectator);
}

function spectator_fast_forward() {
	stop_countdown();
	_spectatorTimer.stop();
	$("#spectator-controls").slideUp(240);
	_spectatorTurn = _storedTurn;
	get_state(update_spectator);
}

function _spectator_next_turn(draftData) {
	stop_countdown();
	_spectatorTurn += 1;
	if(draftData.state != STATE_DONE) {
		get_state(update_spectator);
	} else {
		update_spectator(draftData);
	}
}

function spectator_play_pause() {
	if(_spectatorTimer.isPaused()) {
		resume_countdown();
		_spectatorTimer.resume();
		$("#spectator-play").hide();
		$("#spectator-pause").show();
	} else {
		$("#spectator-play").show();
		$("#spectator-pause").hide();
		pause_countdown();
		_spectatorTimer.pause();
	}
}

function update_spectator(draftData) {

	if(typeof draftData.current_turn === 'undefined') {
		return;
	}

	//current turn means the "next turn" - so the last stored
	//turn is current_turn -1
	var currentTurn = draftData.current_turn;

	if(currentTurn <= _spectatorTurn) {
		_storedTurn = -36;
		update_draw(draftData);
		$("#spectator-controls").slideUp(240);
		if(draftData.state != STATE_DONE) {
			spectator_set_live();
		}
		return;
	}

	//update controls

	if(!_spectatorTimer.isPaused()) {
		$("#spectator-play").hide();
		$("#spectator-pause").show();
	}


	if(_storedNames == null) {
		_storedNames = [$("#drafter_msg_host_captain").text(), $("#drafter_msg_guest_captain").text()];
	}
	//update player names
	if(typeof draftData.players !== 'undefined') {
		for(var i = 0; i < 2; i++) {
			if(draftData.players[i] != _storedNames[i]){
				_storedNames[i] = draftData.players[i];
				if(!$('#player-'+i+' .player-name form').length) {
					$('#player-'+i+' .player-name').html(draftData.players[i]);
				}
			}
		}
	}

	if(draftData.turns) {
		$.each(draftData.turns, function(i, value){
			_storedTurns[value.no] = value;
		});
		_storedTurn = currentTurn;
	}

	spectator_show_choices();

	if(_spectatorTurn < 0) {
		_spectatorTurn = -1;
		//start initial countdown
		$("#action-text .action-string").html($("#action_msg_starting_spectating").html().format("<div id=\"countdown-timer\">0</div>"));
		start_countdown(SPECTATOR_TIMEOUT, function() {
				_spectator_next_turn(draftData);
			});
		return;
	}

	var currentPlayer = _storedTurns[_spectatorTurn]['player'];
	$(".player").removeClass('player-inactive');
	$("#player-"+(1-currentPlayer)+" .player").addClass('player-inactive');

	var previousTime = 0;
	if(_spectatorTurn > 0) {
		previousTime = _storedTurns[_spectatorTurn - 1]['created'];
	}

	//update visible data
	update_turns(_spectatorTurn);
	update_chosen(_spectatorTurn, true, _storedTurn == _spectatorTurn);

	var timeDiff = _storedTurns[_spectatorTurn]['created'] - previousTime;

	var currentAction = TURNS[_spectatorTurn]['action'];
	var temptext = $("#action_msg_text_"+currentAction).html();
	$("#action-text .action-string").html(temptext+"... <div id=\"countdown-timer\">30</div>");
	start_countdown(TIMEOUT, function(){});

	var t_paused = _spectatorTimer.isPaused();
	//start timer
	_spectatorTimer = new Timer(function(){
		_spectator_next_turn(draftData);
	}, timeDiff*1000);

	if(t_paused) {
		pause_countdown();
		_spectatorTimer.pause();
	}
}
