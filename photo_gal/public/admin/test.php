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
	// **3/6/17 DEBUG -- $user->create will NOT normally return a value (remove "$sth =" below) 
	// 
	if ($user->create(array('username', 'password', 'first_name', 'last_name'))){
		echo "success!<br/>";
	} else {
		echo "failed to create user";
	} */


?>

<?php include_layout_template("admin_footer.php") ?>
?>