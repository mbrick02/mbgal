<?php
require_once("../../includes/initialize.php");
require_once(LIB_PATH.DS."formcheck.php");

if(!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php 
if(isset($_POST['submit'])) {  // form was submitted
	$username = htmlentities(trim($_POST['username'])); // populate with old val before error check
	$password = htmlentities(trim($_POST['password']));
	$first_name = htmlentities(trim($_POST['first_name']));
	$last_name = htmlentities(trim($_POST['last_name']));
	
	$formChk = new Formcheck();
	// ** DEBUG current test (of uname via formcheck)
	$minUnameLgth = 5;
	$bUName = "length, spaces or other unusable chars in username ({$minUnameLgth} character minimum)";
	if ($formChk->ok_tfld(trim($_POST['username']), $minUnameLgth)){
		//validate other values;		
		$required_fields = array("username", "password", "first_name", "last_name");
		$formChk->proper_post($required_fields);
	} else {
		$formChk->errors["username"] = $bUName;
	}
	
	// using assoc array with Validations
	$maxUnameLgth = 25;
	$maxPwLgth = 20;
	$fields_with_max_lengths = array("username" => $maxUnameLgth, "password" => $maxPwLgth);
	
	$formChk->validate_max_lengths($fields_with_max_lengths);
	
	if (!empty($formChk->errors)) {
		echo $formChk->form_errors();
	} else {
		// no errors  -> User[$value] = formChk->vals[$*a_value]
		// hashed password =  $user->hash_pword(formChk->vals[$password])
		// ??? repopulate fields?????
		$user = new User();
		
		$user->username = $formChk->form_vals['username'];
		$user->first_name = $formChk->form_vals['first_name'];
		$user->last_name = $formChk->form_vals['last_name'];
		$user->password = $formChk->form_vals['password'];	
		
		if ($user->save()) {
			// Success
			redirect_to('list_users.php');
		} else {
			// Failure
			$formChk->errors['user'] = "unable to create User - may not be unique " . $DEBUGSaveVal;
			echo $formChk->form_errors();
		}
	}		
} else { // Form has not been submitted.
	$username = "";
	$password = "";
	$first_name = "";
	$last_name = "";
}

?>
<?php include_layout_template("admin_header.php") ?>
	<h2>user Create</h2>
	<a href="index.php">&laquo; Back</a><br>
	<?php echo output_message($message); ?>
	<table>
	<form action="create_user.php" enctype="multipart/form-data" method="POST">
		<tr><td align="right"><div style="float:right; text-align: right; width:100%;">Username:</div></td><td><input type="text" name="username" value="<?php echo htmlentities($username); ?>" /></td></tr>
		
		<tr><td align="right"><div style="float:right; text-align: right; width:100%;">First name:</div> </td><td><input type="text" name="first_name" value="<?php echo htmlentities($first_name); ?>" /></td></tr>
		<tr><td align="right"><div style="float:right;text-align: right; width:100%;">Last name:</div> </td><td><input type="text" name="last_name" value="<?php echo htmlentities($last_name); ?>" /></td></tr>
		<tr><td align="right"><div style="float:right;text-align: right; width:100%;">Password:</div></td><td><input type="password" name="password" value="<?php echo htmlentities($password); ?>" /></td></tr>
		<tr><td colspan="2"><input type="submit" name="submit" value="Create" /></td></tr>
	</form>
	</table>
<?php include_layout_template("admin_footer.php") ?>