var me={username:null,token:null,s_color:null};
var game_status={};
var board={};
var pl={};
var last_update=new Date().getTime();
var timer=null;
var timerc;
var interval;
$(function () {
	draw_empty_board();
	fill_board();
	
	$('#login').click( login_to_game);
	$('#reset').click( reset_board);
	$('#move_div').hide();
	game_status_update();
	
});


function draw_empty_board() {
	
	var t='<table id="board">'
	
	
	for(var i=1;i<=6;i++) {
		t += '<tr id = '+i+'>';
		
		for(var x=1;x<=7;x++) {
			t += '<td class="chess_square" id="square_'+i+'_'+x+'">' + i +','+x+'</td>'; 
		}
		t+='</tr>';
	}
	t+='</table>';
	
	$('#board').html(t);
	$('.chess_square').click(click_on_piece);
	
	
}

function fill_board() {
	$.ajax({url: "index.php/board/",
		method:'GET', 
		headers: {"X-Token": me.token},
		success: fill_board_by_data });
}

function reset_board() {
	$.ajax({url: "index.php/board/", headers: {"X-Token": me.token}, method: 'POST',  success: fill_board_by_data });
	$('#move_div').hide();
	$('#game_initializer').show(2000);
	$('#username').val('');
	me={username:null,token:null,s_color:null}
}
function fill_board_by_data(data) {
	board=data;
	for(var i=0;i<data.length;i++) {
		var o = data[i];
		var id = '#square_'+ o.x +'_' + o.y;
		
		var pc= (o.s_color!=null)?o.s_color:'';
		var im = (o.s_color!=null)?'<img class="piece '+'" src="images/'+pc+'.png">':'';
		$(id).addClass(o.s_color+'_square').html(im);
	}


}



function login_to_game() {
	if($('#username').val()=='') {
		alert('You have to set a username');
		
		return;
	}
	var color = $('#Scolor').val();
	draw_empty_board(color);
	fill_board();
	
	$.ajax({url: "index.php/players/" +color, 
			method: 'PUT',
			dataType: "json",
			headers: {"X-Token": me.token},
			contentType: 'application/json',
			data: JSON.stringify( {username: $('#username').val(), s_color: color}),
			
			success: login_result,
			error: login_error});
			console.log($('#username').val(), color, )
}

function login_result(data) {
	
	me = data[0];
	if(me.token==null){
		alert('Username already exists')
	}else{
	console.log(me)
	$('#game_initializer').hide();
	update_info();
	game_status_update();
}
}

function login_error(data) {
	
	var x = data.responseJSON;
	alert(x.errormesg);
}

function game_status_update() {
	
	clearTimeout(timer);
	$.ajax({url: "index.php/status/", success: update_status,headers: {"X-Token": me.token} });
}

function update_status(data) {
	last_update=new Date().getTime();
	var game_stat_old = game_status;
	game_status=data[0];
	update_info();
	clearTimeout(timer);
	if(game_status.p_turn==me.s_color &&  me.s_color!=null) {
		x=0;
		// do play
		if(game_stat_old.p_turn!=game_status.p_turn) {
			fill_board();
			clearInterval(interval);
			clock();
			var wait=setTimeout(function() { $('#clock').show()}, 1000);
			
		}
		$('#move_div').show(1000);
		timer=setTimeout(function() { game_status_update();}, 15000);
	} else {
		// must wait for something
		$('#move_div').hide(1000);
		timer=setTimeout(function() { game_status_update();}, 6000);
		clearInterval(interval);
		$('#clock').hide();
		
	}
 	
}

function update_info(){

	$('#game_info').html("I am Player: "+me.s_color+", my name is "+me.username +'<br>Token='+me.token+'<br>Game state: '+game_status.status+', '+'Player '+ game_status.p_turn+' must play now.');
	
	
}

function do_move( id ) {


	
    $.ajax({
        url: "index.php/board/place/",
        method: 'PUT',
        dataType: 'json',
        headers: { "X-Token": me.token },
        contentType: 'application/json',
        data: JSON.stringify({ id: id }),
        success: move_result,
        error: login_error
	});
	
}

function click_on_piece(e) {
	
	var o=e.target;
	console.log(o)
	if(o.tagName!='TD') {o=o.parentNode;}
	if(o.tagName!='TD') {return;}
	
	var id=o.id;
	var a=id.substr(id.length - 1);
	console.log(a)
	do_move(a);
	
	
}




function move_result(data){
	fill_board_by_data(data);
	game_status_update();
	
}



function clock(){
	var minutes=4;
	var seconds=60;
var timer2 = "5:01";
 interval = setInterval(function() {
	
	seconds--;
	if(seconds==0 && minutes!=0){
		minutes--;
		seconds=60;
		
	}
	else if(seconds==0 && minutes==0){
		clearInterval(interval);
		
	}
	else if(seconds==60){
		$('#clock').html(minutes + ':' + '00');
		
	}
	if(seconds!=60){
	$('#clock').html(minutes + ':' + seconds);
	}
/*
  var clock = timer2.split(':');
  //by parsing integer, I avoid all extra string processing
  var minutes = parseInt(clock[0], 10);
  var seconds = parseInt(clock[1], 10);
  --seconds;
  minutes = (seconds < 0) ? --minutes : minutes;
  if (minutes < 0) clearInterval(interval);
  seconds = (seconds < 0) ? 59 : seconds;
  seconds = (seconds < 10) ? '0' + seconds : seconds;
  //minutes = (minutes < 10) ?  minutes : minutes;
  $('#clock').html(minutes + ':' + seconds);
  timer2 = minutes + ':' + seconds;*/



}, 1000);
}

