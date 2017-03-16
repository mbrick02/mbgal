<?php
require_once("../../includes/initialize.php");

// if(!$session->is_logged_in()) { redirect_to("login.php"); }

$delID = "";

if (isset($_GET['delID'])) {
	$delID = $_GET['delID'];
}
?>
<?php include_layout_template("admin_header.php") ?>
<?php 
	/*
	$user = new User;
	$user->username = "nothrMB";
	$user->password = "nothrsecret1";
//	$user->hashpw = "";
	$user->first_name = "Louer";
	$user->last_name = "Breeker";
	
	if ($user->create()){
		echo "success!<br/>";
	} else {
		echo "failed to create user...<br/>";
	} */

	$user = User::find_by_id(17);

	if (!empty($user)) {
		// echo "User: " . $user->username;
		echo "<br/> User to update: ";
		echo "<pre>";
		print_r($user);
		echo "</pre>";
	} else {
		echo "No user found"; 
	}
	
	$user->first_name = "Newerfirst";
	
	$userUpdateVal = $user->update();
	
	if ($userUpdateVal === true) {
	echo "User updated!";
	} else {
		echo $userUpdateVal;
	}

	// echo $user->update();
/*
	if ($user->save()) {
		echo "user updated";
	} else {
		echo "NO update of user";
	} */
	
	/* if (!empty($delID)) {
		$user = User::find_by_id($delID);
		if($user->delete()) {
			echo "deleted id: ", $delID;	
		}
	} else {
		echo "no delete ID";
	} */
?>
<?php include_layout_template("admin_footer.php") ?>

?>