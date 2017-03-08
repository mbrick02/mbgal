<?php
require_once("../../includes/initialize.php");

// if(!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php include_layout_template("admin_header.php") ?>
<?php 
	/* $user = new User;
	$user->username = "newMB";
	$user->password = "newsecret1";
//	$user->hashpw = "";
	$user->first_name = "Louie";
	$user->last_name = "LaBreek";
	
	if ($user->create(array('username', 'password', 'first_name', 'last_name'))){
		echo "success!<br/>";
	} else {
		echo "failed to create user";
	}  */

	/* $user = User::find_by_id(5);

	if (!empty($user)) {
		// echo "User: " . $user->username;
		echo "<br/> <pre>";
		print_r($user);
		echo "</pre>";
	} else {
		echo "No user found"; 
	}
	
	$user->password = "UNOTROsecret";
	*/
	/*
	echo "<pre>";
	print_r($user->update());
	echo "</pre>";
	 */
	// echo $user->update();
/*
	if ($user->save()) {
		echo "user updated";
	} else {
		echo "NO update of user";
	} */
	
	$user = User::find_by_id(16);
	$user->delete();

?>

<?php include_layout_template("admin_footer.php") ?>
?>