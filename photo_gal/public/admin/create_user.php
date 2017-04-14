<?php
require_once("../../includes/initialize.php");

if(!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php 
	$max_file_size = 10485760; // expressed in bytes 10 MB
	
	// no need for this since set in $session: $message ="";
	if(isset($_POST['submit'])) {
		$user = new User();
		$user->username = oneValidWordNOTWRITTENYET($_POST['username']);
		$user->first_name = oneValidWord($_POST['first_name']);
		$user->last_name = oneValidWord($_POST['last_name']);
		$user->hashed_password = hashed(oneValidWord($_POST['password']));
		
		$user->attach_file($_FILES['file_upload']); // success tested in save() error
		if ($user->save()) {
			// Success
			$session->message("user created successfully.");
			redirect_to('list_users.php');
		} else {
			// Failure
			$message = join("<br />", $user->errors);
		}
	}
?>
<?php include_layout_template("admin_header.php") ?>
	<h2>user Upload</h2>
	<?php echo output_message($message); ?>
	<form action="create_user.php" enctype="multipart/form-data" method="POST">
		<p>Username: <input type="text" name="username" value="" /></p>
		<p>First name: <input type="text" name="first_name" value="" /></p>
		<p>Last name: <input type="text" name="last_name" value="" /></p>
		<p>Password: <input type="text" name="password" value="" /></p>
		<p><input type="submit" name="submit" value="Upload" /></p>
	</form>
<?php include_layout_template("admin_footer.php") ?>

?>