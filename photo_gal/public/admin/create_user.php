<?php
require_once("../../includes/initialize.php");
require_once(LIB_PATH.DS."formcheck.php");

if(!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php 
if(isset($_POST['submit'])) {  // form was submitted
		/*  DEBUG uname & pw validation
		 
			$username = isset($_POST['username']) ? $_POST['username'] : "";
			$password = isset($_POST['password']) ? $_POST['password'] : "";

			// Validations
			$fields_required = array("username", "password");
			foreach ($fields_required as $field) {
				$value = trim($_POST[$field]);
				if (!has_presence($value)) {
					$errors[$field] = ucfirst($field) . " can't be blank";
				}
			}
			**END DEBUG uname & pw validation */
	// ** DEBUG current test (of uname via formcheck
	$minUnameLgth = 5;
	$gUName = "good username";
	$bUName = "length, spaces or other unusable chars in username";
	echo Formcheck::ok_uname(trim($_POST['username']), $minUnameLgth) ? $gUName: $bUName;
	
			/* **DEBUG other validation checks
			// using assoc array with Validations
			$maxUnameLgth = 25;
			$maxPwLgth = 20;
			$fields_with_max_lengths = array("username" => $maxUnameLgth, "password" => $maxPwLgth);

			validate_max_lengths($fields_with_max_lengths);

			if (empty($errors)) {
		 		...
		 	}
		 */
		

		/*
		$user = new User();
		$user->username = oneValidWordNOTWRITTENYET($_POST['username']);
		$user->first_name = oneValidWord($_POST['first_name']);
		$user->last_name = oneValidWord($_POST['last_name']);
		$user->hashed_password = hashed(oneValidWord($_POST['password']));
			
		if ($user->save()) {
			// Success
			$session->message("user created successfully.");
			redirect_to('list_users.php');
		} else {
			// Failure
			$message = join("<br />", $user->errors);
		}
		*/
	}
?>
<?php include_layout_template("admin_header.php") ?>
	<h2>user Create</h2>
	<?php echo output_message($message); ?>
	<form action="create_user.php" enctype="multipart/form-data" method="POST">
		<p>Username: <input type="text" name="username" value="" /></p>
		<p>First name: <input type="text" name="first_name" value="" /></p>
		<p>Last name: <input type="text" name="last_name" value="" /></p>
		<p>Password: <input type="text" name="password" value="" /></p>
		<p><input type="submit" name="submit" value="Create" /></p>
	</form>
<?php include_layout_template("admin_footer.php") ?>

?>