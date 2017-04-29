<?php
	require_once("../../includes/initialize.php");
	// linux user: gogolbordel	Gogol	Bordelo	gogbordel1
	if($session->is_logged_in()) {
		redirect_to("index.php");
	}
	
	// form's submit tag w/name="submit attribute!
	if (isset($_POST['submit'])) {
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		
		// Check database to see if username/password exist.
		// DEBUG remove for testinsg (using code below)****SAVE THIS LINE****: $found_user = User::authenticate($username, $password);
		
		/* DEBUG used as substitute for above class call to authenticate  -- REMOVE below after use****************vvvvvv
		 */
		$sql = "SELECT * FROM users ";
		$sql .= "WHERE username = '{$username}' ";
		$sql .= "LIMIT 1";
		
		$res_ary = User::find_by_sql($sql);
		$found_user = !empty($res_ary) ? array_shift($res_ary) : false;
		
		$hashDBpw = $found_user->hashpw;
		$hashDBpwResult = password_verify($password, $hashDBpw) ? "verified plain hashpw" : "failed to verify PLAIN hashpw";
		echo $hashDBpwResult  . "<br>";
		$hashDBpw60 = substr($hashDBpw, 0, 60);  // ????**** put this in user::hash_pw ???
		$hashDBpw60Result = password_verify($password, $hashDBpw60) ? "verified hashpw60" : "failed to verify hashpw60";
		echo "Entered password: " . $password . "<br>";
		echo "DB password: " . $found_user->password . "<br>";
		echo "hashed DB password: " . $found_user->hashpw . "<br>";
		echo "hashDBpw60: " . $hashDBpw60 . "<br>"; 
		echo $hashDBpw60Result  . "<br>";
		$newpwhash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
		echo "hash of new pw: " . $newpwhash . "<br>";
		$newhashpwResult = password_verify($password, $newpwhash) ? "verified NEWhashpw" : "failed to verify NEWhashpw";
		echo $newhashpwResult . "<br>";
		$session->login($found_user); // login no matter what for DEBUG******HIGH SECURITY RISK *****DEBUG********
		/* END DEBUG substitute code -- REMOVE  above after use*****^^^^^
		 */
	/* DEBUG -- ****SAVE THIS CODE AFTER TESTING ABOVE ************
		if ($found_user) {
			$session->login($found_user);
			Logger::log_action("Login", "{$username} logged in.");
			redirect_to("index.php");
		} else {
			// username/password combo was not found in the database
			Logger::log_action("FailLgn", "{$username} failed login attempt.");
			$message = "Username/password combination incorrect.";
		}
	END DEBUG *****SAVE THIS CODE  PUT BACK IN AFTER USING TEST CODE ABOVE THIS SECTION ***********/
	} else { // Form has not been submitted.
		$username = "";
		$password = "";
	}
?>
<html>
	<head>
		<title>Photo Gallery Login</title>
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
