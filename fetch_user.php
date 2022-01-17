<?php
include('database_connection.php');
session_start();

$query = "SELECT * FROM login WHERE user_id != '".$_SESSION['user_id']."'";
$statement = $connect->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$output = '<table class="table-striped">';
	foreach($result as $row){
		$status = '';
		$current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
		$current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
		$user_last_activity = fetch_user_last_activity($row['user_id'], $connect);
		if($user_last_activity > $current_timestamp){
			$status = '<h2><span class="label label-success">Online</span></h2>';
		} else {
			$status = '<h2><span class="label label-danger">Offline</span></h2>';
		}
	$output .= '
	 <tr>
	  <td class="start_chat" data-touserid="'.$row['user_id'].'" data-tousername="'.$row['username'].'"><img src="./images/user.png" /><h2 ><font>'.$row['username'].' '.count_unseen_message($row['user_id'], $_SESSION['user_id'], $connect).'</font></h2></td>
	  
	 </tr>
	 ';
	}
$output .= '</table>';

echo $output;

?>
