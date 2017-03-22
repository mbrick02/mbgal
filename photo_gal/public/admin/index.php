<?php
	require_once("../../includes/initialize.php");
	
	if(!$session->is_logged_in()) { redirect_to("login.php"); } 
?>
<?php include_layout_template("admin_header.php") ?>
<?php echo output_message($message); ?>
			<h2>Menu</h2>
			<Ul>
				<li><a href="logfile.php">View Log file</a></li>
				<li><a href="logout.php">logout</a></li>
			</Ul>
<?php include_layout_template("admin_footer.php") ?>