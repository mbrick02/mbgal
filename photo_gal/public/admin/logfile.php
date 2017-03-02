<?php
require_once("../../includes/initialize.php");

if(!$session->is_logged_in()) { redirect_to("login.php"); }

if(isset($_GET['clear']) && ($_GET['clear']=='true')) {
	Logger::clear_log("cleared by " . $_SESSION['username']);
}
?>
<?php include_layout_template("admin_header.php") ?>

<a href="index.php">&laquo; Back</a>
<br />
<h2>Log File</h2>
<p><a href="logfile.php?clear=true">Clear Log</a></p>

<?php 
	if (file_exists($logfile))
?>

<?php include_layout_template("admin_footer.php") ?>