<?php
require_once("../../includes/initialize.php");

if(!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php include_layout_template("admin_header.php") ?>

	<h2>Photo Upload</h2>
	
	<form action="photo-upload.php" enctype="mulipart/form-data" method="POST">
		<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
		<p><input type="file" name="file_upload" /> </p>
		<p>Caption: <input type="text" name="caption" value="" /></p>p>
		<input type="submit" name="submit" value="Upload" />
	</form>

<?php include_layout_template("admin_footer.php") ?>

?>