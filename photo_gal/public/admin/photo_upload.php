<?php
require_once("../../includes/initialize.php");

if(!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php 
	$max_file_size = 10485760; // expressed in bytes 10 MB
	
	$message ="";
	if(isset($_POST['submit'])) {
		$photo = new Photograph();
		$photo->caption = $_POST['caption'];
		 
		$photo->attach_file($_FILES['file_upload']); // success tested in save() error
		if ($photo->save()) {
			// Success
			$message = "Photograph uploaded successfully.";
		} else {
			// Failure
			$message = join("<br />", $photo->errors);
		}
	}
?>
<?php include_layout_template("admin_header.php") ?>
	<h2>Photo Upload</h2>
	<?php echo output_message($message); ?>
	<form action="photo_upload.php" enctype="multipart/form-data" method="POST">
		<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
		<p><input type="file" name="file_upload" /> </p>
		<p>Caption: <input type="text" name="caption" value="" /></p>
		<p><input type="submit" name="submit" value="Upload" /></p>
	</form>
<?php include_layout_template("admin_footer.php") ?>

?>