<?php
	require_once("../../includes/initialize.php");
	
	if(!$session->is_logged_in()) { redirect_to("login.php"); } 
	
	if($_GET['clear']=='true') {
		clear_log("cleared by " . $_SESSION['username']);
	}
?>
<?php include_layout_template("admin_header.php") ?>

			<h2>Menu</h2>
			<a href="index.php?clear=true">Clear Log</a>
<?php include_layout_template("admin_footer.php") ?>


