<?php
include('database_connection.php');
session_start();

$data = array(
 ':to_user_id'  => $_POST['to_user_id'],
 ':from_user_id'  => $_SESSION['user_id']
);
$from_user_id = $_SESSION['user_id'];
$to_user_id = $_POST['to_user_id'];
///
// update notifications < - 
	$query2 = "UPDATE chat_message SET status = '0' WHERE from_user_id = '".$to_user_id."' AND to_user_id = '".$from_user_id."' AND status = '1'";
	$statement2 = $connect->prepare($query2);
	$statement2->execute();


// messages 
$query = "SELECT * FROM chat_message WHERE from_user_id = :from_user_id AND to_user_id = :to_user_id OR from_user_id = :to_user_id AND to_user_id = :from_user_id ORDER BY timestamp";

$statement = $connect->prepare($query);
$statement->execute($data);
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
	echo $output;
?>
