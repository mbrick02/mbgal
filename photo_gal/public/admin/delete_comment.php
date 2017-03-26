<?php
require_once("../../includes/initialize.php");

if(!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php 
	if(empty($_GET['id'])) {
		$session->message("No comment ID was provided.");
		redirect_to('index.php');
	}
	
	$comment = Comment::find_by_id($_GET['id']);
	
	if($comment && $comment->delete()) {
		$session->message("The comment by  {$comment->author} was deleted.");
		redirect_to("list_photo_comments.php?id={$comment->photo_id}");
	} else {
		$session->message("The comment could not be deleted.");
		redirect_to('index.php');
	}
?>
<?php if(isset($database)) { $database->closeConnection(); } ?>