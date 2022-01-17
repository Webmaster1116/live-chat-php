<?php
$connect = new PDO("mysql:host=localhost;dbname=kpvechat", "root", "");
date_default_timezone_set('Australia/Sydney');
// // // // // // // // // // // // // // // // // // //

function fetch_user_last_activity($user_id, $connect){
	$query = "SELECT * FROM login_details WHERE user_id = '$user_id' ORDER BY last_activity DESC LIMIT 1";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row) {
		return $row['last_activity'];
	}
}

function fetch_user_chat_history($from_user_id, $to_user_id, $connect){
	$query = "SELECT * FROM chat_message WHERE (from_user_id = '".$from_user_id."' AND to_user_id = '".$to_user_id."') OR (from_user_id = '".$to_user_id."' AND to_user_id = '".$from_user_id."') ORDER BY timestamp";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();

	$output = '<ul class="list-unstyled">';
	foreach($result as $row){
		$user_name = '';
		if($row["from_user_id"] == $from_user_id) {
			$user_name = 'You';
		} else {
			$user_name = '<b class="text-danger"><img class="user-avatar" src="./images/user.png" /></b>';
		}
		if($user_name == 'You') {
			$output .= '
		<li style=" padding:10px 0;">
		<p class="your-message"><span>'.$row["chat_message"].'</span>
			<div align="right">
			<small style="font-size:12px; color:rgb(0, 0, 0, 0.5)"><em>'.$row['timestamp'].'<input class="count-message" type="hidden" value="'.$row['chat_message_id'].'"/></em></small>
			</div>
		</p>
		</li>
		';
		} else {
			$output .= '
		<li style=" padding:10px 0;">
		<p class="user-message">'.$user_name.'  <span>'.$row["chat_message"].'</span>
			<div align="left">
			<small style="font-size:12px; color:rgb(0, 0, 0, 0.5)"><em>'.$row['timestamp'].'<input class="count-message" type="hidden" value="'.$row['chat_message_id'].'"/></em></small>
			</div>
		</p>
		</li>
		';
		}
		
		}
	$output .= '</ul>';
	return $output;
}

function get_user_name($user_id, $connect){
	$query = "SELECT username FROM login WHERE user_id = '$user_id'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row){
		return $row['username'];
	}
}

function count_unseen_message($from_user_id, $to_user_id, $connect){
	$query = "SELECT * FROM chat_message WHERE from_user_id = '$from_user_id' AND to_user_id = '$to_user_id' AND status = '1'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$count = $statement->rowCount();
	$output = '';
	if($count > 0){
		$output = '<span class="label label-success">'.$count.'</span>';
	}
	return $output;
}

?>