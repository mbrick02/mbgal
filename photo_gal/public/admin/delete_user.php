<?php
require_once("../../includes/initialize.php");

if(!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php 
if(empty($_GET['id']) || (!is_numeric($_GET['id']))) {
		$session->message("No user provided.");
		redirect_to('index.php');
	}
	
	$user = User::find_by_id($_GET['id']);
	
	if($user && $user->delete()) {
		$session->message("The user {$user->username} was deleted.");
		redirect_to('list_users.php');
	} else {
		$session->message("The user could not be deleted.");
		redirect_to('index.php');
	}
?>
<?php if(isset($database)) { $database->closeConnection(); } ?>