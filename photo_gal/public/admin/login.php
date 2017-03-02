<?php
	require_once("../../includes/initialize.php");
	
	if($session->is_logged_in()) {
		// redirect_to("index.php");
		// reset for testing: 
		$session->logout();
	}
	
	// form's submit tag w/name="submit attribute!
	if (isset($_POST['submit'])) {
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		
		// Check database to see if username/password exist.
		$found_user = User::authenticate($username, $password);
		
		if ($found_user) {
			$session->login($found_user);
			Logger::log_action("Login", "{$username} logged in.");
			redirect_to("index.php");
		} else {
			// username/password combo was not found in the database
			Logger::log_action("FailLgn", "{$username} failed login attempt.");
			$message = "Username/password combination incorrect.";
		}
	} else { // Form has not been submitted.
		$username = "";
		$password = "";
	}
?>
<html>
	<head>
		<title>Photo Gallery</title>
		<link href="../stylesheets/main.css" media="all" 
		rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div id="header">
			<h1>Photo Gallery</h1>
		</div>
		<div id="main">
			<h2>Staff Login</h2>
			<?php echo output_message($message);?>
			
			<form action="login.php" method="post">
			<table>
				<tr>
					<td>Username:</td>
					<td><input type="text" name="username" maxlength="30"
					 value="<?php echo htmlentities($username); ?>" /></td>
				</tr>
				<tr>
					<td>Password:</td>
					<td><input type="password" name="password" maxlength="30"
					 value="<?php echo htmlentities($password); ?>" /></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" name="submit" value="Login"></td>
				</tr>
			</table>
			</form>
		</div>
		<div id="footer">Copyright <?php echo date("Y", time()); ?> MB</div>
	</body>
</html>
<?php if(isset($database)) { $db->closeConnection(); }?>
