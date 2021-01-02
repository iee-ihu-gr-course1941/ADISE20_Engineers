<?php


function show_piece($x,$y) {
	global $mysqli;
	
	$sql = 'select * from board where x=? and y=?';
	$st = $mysqli->prepare($sql);
	$st->bind_param('ii',$x,$y);
	$st->execute();
	$res = $st->get_result();
	header('Content-type: application/json');
	print json_encode($res->fetch_all(MYSQLI_ASSOC), JSON_PRETTY_PRINT);
}
   
function place_piece($column, $token)
{
		
	if ($token == null || $token == '') {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg' => "token is not set."]);
		exit;
	} 
	$color = current_color($token);
	if($color==null ) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"You are not a player of this game."]);
		exit;
	}
	$status = read_status();
	if($status['status']!='started') {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"Game is not in action."]);
		exit;
	}
	if($status['p_turn']!=$color) {
		header("HTTP/1.1 400 Bad Request");
		print json_encode(['errormesg'=>"It is not your turn."]);
		exit;
	}
		
		
		global $mysqli;
		$sql = 'call `place_piece`(?);';

		$st = $mysqli->prepare($sql);
		$st->bind_param('i', $column);
		$st->execute();
		

		header('Content-type: application/json');
		print json_encode(read_board(), JSON_PRETTY_PRINT);
	
}
	
	
	

	

		
function show_board()
{
	header('Content-type: application/json');
	print json_encode(read_board(), JSON_PRETTY_PRINT);
}

function reset_board() {
	global $mysqli;
	$sql = 'call clean_board()';
	$mysqli->query($sql);
	
}

function read_board() {
	global $mysqli;
	$sql = 'select * from board';
	$st = $mysqli->prepare($sql);
	$st->execute();
	$res = $st->get_result();
	return($res->fetch_all(MYSQLI_ASSOC));
}



function convert_board(&$orig_board) {
	$board=[];
	foreach($orig_board as $i=>&$row) {
		$board[$row['x']][$row['y']] = &$row;
	} 
	return($board);
}
function winner2($column, $token){
	global $mysqli;
    $b1 = read_board();
	$b2 = convert_board($b1);
	$rk=0;
	$yk=0;
	$ro=0;
	$yo=0;
	$rd=0;
	$yd=0;

	//katheta
	
	for($i=1;$i<=6;$i++){
		for($j=1;$j<=7;$j++){
		if($b2[$i][$j]['s_color']=='Y'){
			$yk++;
				if($yk==4 &&$b2[$i-1][$j]['s_color']=='Y'&&$b2[$i-2][$j]['s_color']=='Y'&&$b2[$i-3][$j]['s_color']=='Y'){
					
					$sql = "update game_status set status='ended', result= 'Y',p_turn=null where p_turn is not null and status='started'";
					$st = $mysqli->prepare($sql);
					$r = $st->execute();
				}
		}
		else if($b2[$i][$j]['s_color']=='R'){
			$rk++;
				if($rk==4 &&$b2[$i-1][$j]['s_color']=='R'&&$b2[$i-2][$j]['s_color']=='R'&&$b2[$i-3][$j]['s_color']=='R'){
					$sql = "update game_status set status='ended', result= 'R',p_turn=null where p_turn is not null and status='started'";
					$st = $mysqli->prepare($sql);
					$r = $st->execute();
				}
		}
		}
	}


//orizontia
for($i=1;$i<=6;$i++){
	$yo=0;
	$ro=0;
	for($j=$column;$j<=7;$j++){
	if($b2[$i][$j]['s_color']=='Y'){
		$yo++;
			if($yo==4 &&$b2[$i][$j-1]['s_color']=='Y'&&$b2[$i][$j-2]['s_color']=='Y'&&$b2[$i][$j-3]['s_color']=='Y'){
				
				$sql = "update game_status set status='ended', result= 'Y',p_turn=null where p_turn is not null and status='started'";
				$st = $mysqli->prepare($sql);
				$r = $st->execute();
			}else if($yo==4 &&$b2[$i][$j+1]['s_color']=='Y'&&$b2[$i][$j+2]['s_color']=='Y'&&$b2[$i][$j+3]['s_color']=='Y'){
				$sql = "update game_status set status='ended', result= 'Y',p_turn=null where p_turn is not null and status='started'";
				$st = $mysqli->prepare($sql);
				$r = $st->execute();
			}
			
	}
	else if($b2[$i][$j]['s_color']=='R'){
		$ro++;
			if($b2[$i][$j-1]['s_color']=='R'&&$b2[$i][$j-2]['s_color']=='R'&&$b2[$i][$j-3]['s_color']=='R'&&$ro==4 ) {
				$sql = "update game_status set status='ended', result= 'R',p_turn=null where p_turn is not null and status='started'";
				$st = $mysqli->prepare($sql);
				$r = $st->execute();
				}
				else if ($ro==4 &&$b2[$i][$j+1]['s_color']=='R'&&$b2[$i][$j+2]['s_color']=='R'&&$b2[$i][$j+3]['s_color']=='R'){
					$sql = "update game_status set status='ended', result= 'R',p_turn=null where p_turn is not null and status='started'";
				$st = $mysqli->prepare($sql);
				$r = $st->execute();
				}
			}
		
	
		}
	
	}
	//diagonia

	for($i=1;$i<=6;$i++){
		for($j = 1;$j<=7; $j++){
			if($b2[$i][$j]['s_color'] == 'Y' &&($b2[$i][$j]==$b2['5']['4']|| $b2[$i][$j]==$b2['6']['4']||$b2[$i][$j]==$b2['6']['3']||$b2[$i][$j]==$b2['4']['4']||$b2[$i][$j]==$b2['5']['3']||$b2[$i][$j]==$b2['6']['2']||$b2[$i][$j]==$b2['4']['1']||$b2[$i][$j]==$b2['4']['2']||$b2[$i][$j]==$b2['5']['1']||$b2[$i][$j]==$b2['4']['3']||$b2[$i][$j]==$b2['5']['2']||$b2[$i][$j]==$b2['6']['1'])){
				$yd++;
				if($b2[$i-1][$j+1]['s_color'] == 'Y'&& $b2[$i-2][$j+2]['s_color'] == 'Y'&&$b2[$i-3][$j+3]['s_color'] == 'Y'){
					
							
							$sql = "update game_status set status='ended', result= 'Y',p_turn=null where p_turn is not null and status='started'";
							$st = $mysqli->prepare($sql);
							$r = $st->execute();
						
					

				}
			}else if($b2[$i][$j]['s_color'] == 'R' &&($b2[$i][$j]==$b2['5']['4']|| $b2[$i][$j]==$b2['6']['4']||$b2[$i][$j]==$b2['6']['3']||$b2[$i][$j]==$b2['4']['4']||$b2[$i][$j]==$b2['5']['3']||$b2[$i][$j]==$b2['6']['2']||$b2[$i][$j]==$b2['4']['1']||$b2[$i][$j]==$b2['4']['2']||$b2[$i][$j]==$b2['5']['1']||$b2[$i][$j]==$b2['4']['3']||$b2[$i][$j]==$b2['5']['2']||$b2[$i][$j]==$b2['6']['1'])){
				$yd++;
				if($b2[$i-1][$j+1]['s_color'] == 'R'&& $b2[$i-2][$j+2]['s_color'] == 'R'&&$b2[$i-3][$j+3]['s_color'] == 'R'){
					
							
							$sql = "update game_status set status='ended', result= 'R',p_turn=null where p_turn is not null and status='started'";
							$st = $mysqli->prepare($sql);
							$r = $st->execute();
						
					

				}}

				if($b2[$i][$j]['s_color'] == 'Y' &&($b2[$i][$j]==$b2['4']['4']|| $b2[$i][$j]==$b2['4']['5']||$b2[$i][$j]==$b2['4']['6']||$b2[$i][$j]==$b2['4']['7']||$b2[$i][$j]==$b2['5']['4']||$b2[$i][$j]==$b2['5']['5']||$b2[$i][$j]==$b2['5']['6']||$b2[$i][$j]==$b2['5']['7']||$b2[$i][$j]==$b2['6']['4']||$b2[$i][$j]==$b2['6']['5']||$b2[$i][$j]==$b2['6']['6']||$b2[$i][$j]==$b2['6']['7'])){
					$yd++;
					if($b2[$i-1][$j-1]['s_color'] == 'Y'&& $b2[$i-2][$j-2]['s_color'] == 'Y'&&$b2[$i-3][$j-3]['s_color'] == 'Y'){
						
								
								$sql = "update game_status set status='ended', result= 'Y',p_turn=null where p_turn is not null and status='started'";
								$st = $mysqli->prepare($sql);
								$r = $st->execute();
							
						
	
					}
				}else if($b2[$i][$j]['s_color'] == 'R' &&($b2[$i][$j]==$b2['4']['4']|| $b2[$i][$j]==$b2['4']['5']||$b2[$i][$j]==$b2['4']['6']||$b2[$i][$j]==$b2['4']['7']||$b2[$i][$j]==$b2['5']['4']||$b2[$i][$j]==$b2['5']['5']||$b2[$i][$j]==$b2['5']['6']||$b2[$i][$j]==$b2['5']['7']||$b2[$i][$j]==$b2['6']['4']||$b2[$i][$j]==$b2['6']['5']||$b2[$i][$j]==$b2['6']['6']||$b2[$i][$j]==$b2['6']['7'])){
					$yd++;
					if($b2[$i-1][$j-1]['s_color'] == 'R'&& $b2[$i-2][$j-2]['s_color'] == 'R'&&$b2[$i-3][$j-3]['s_color'] == 'R'){
						
								
								$sql = "update game_status set status='ended', result= 'R',p_turn=null where p_turn is not null and status='started'";
								$st = $mysqli->prepare($sql);
								$r = $st->execute();
							
						
	
					}}

	
		}
	}
	
}










?>