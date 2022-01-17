<?php
include('database_connection.php');
session_start();
$message = '';

if(isset($_SESSION['user_id'])) {
	header('location:index.php');
}

if(isset($_POST["login"])) {
	if(isset($_POST['email']) != "") {
		$query = "SELECT * FROM login WHERE email = :email";
		$statement = $connect->prepare($query);
		$statement->execute(array(':email' => $_POST["email"]));
		$count = $statement->rowCount();
			if($count > 0) {
					$result = $statement->fetchAll();
				foreach($result as $row){
					if(password_verify($_POST["password"], $row["password"])){
						$_SESSION['user_id'] = $row['user_id'];
						$_SESSION['email'] = $row['email'];
						$_SESSION['username'] = $row['username'];
						$sub_query = "INSERT INTO login_details (user_id) VALUES ('".$row['user_id']."')";
						$statement = $connect->prepare($sub_query);
						$statement->execute();
						$_SESSION['login_details_id'] = $connect->lastInsertId();
						header("location:index.php");
						sleep(2); // to show gif loading icon
					} else {
						$message = "<label>Wrong Password</label>";
					}
				}
			} else {
				$message = "<label>Wrong Email</labe>";
			}
	} else {
		$message = "<label>Enter Email</labe>";
	}
}
?>

<html> 

	<head>
		<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
		<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
		<link rel="stylesheet" href="css/style.css">
	</head>
	
<body>
	<section id="loginPage">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-md-12">
					
					<div class="account-wall">
						<h1 class="text-center">Welcome Back!</h1>
						<form method="POST" class="form-signin">
							<label class="input-title">Email</label>
							<input type="email" name="email" class="form-control" placeholder="Email"  autofocus autocomplete="off">
							<label class="input-title">Password</label>
							<input type="password" class="form-control" name="password" placeholder="Your Password" autocomplete="off">
							<div class="login-btn">
								<button class="btn btn-lg btn-primary btn-block" id="login" name="login" type="submit">
									Login
								</button>
							</div>
						
						
						</form>
						<center><font size="4"><p class="text-danger font"><?php echo $message; ?></p></font></center>
						
						<!-- <center><b>Login details:</b> <br />
						username: admin <br />
						password: admin <br />
						--- <br />
						username: user <br />
						password: admin <br />
						--- <br />
						username: test <br />
						password: admin <br />
						
						</center> -->
						<a href="#" class="text-center new-account">Create an account </a>
					</div>
					
				</div>
			</div>
		</div>
	</section>
</body>
<script>
		$(function(){
			$("#login").click(function(){
				$(this).html("<span>Loading</span><center><img class='loading-gif' src='images/prijava.gif' width='25px' alt='loading' />").fadeIn();   // loader icon
			});
		});
</script>  
</html>
