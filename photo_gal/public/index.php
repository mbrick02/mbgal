<?php
	require_once("../includes/initialize.php");

	include_layout_template("header.php");
	
	if(isset($database)) { // if NOT do NOTHING (so NOT indenting)
	// $sql_to_prep = "INSERT INTO users (id, username, password, first_name, last_name) ";
	// $sql_to_prep .= "VALUES (1, :usernm, 'secretpw1', 'Mich1', 'Bri1')";
	// $result = $db->exec_qry($sql_to_prep, array(':usernm' => 'mber01'));
	
	$user = User::find_by_id(1);
	if ($user) {
		echo "user fullname: " . $user->fullNam() . "<hr />";
		echo "<br/> <pre>";
		print_r($user);
		echo "</pre>";
	} else {
		echo "no user found. <br />";
	}
	$found_users = User::find_all();
	
	$i = 0;
	foreach ($found_users as $a_user) {
		++$i;
		echo "user $i: ", $a_user->id, " ", $a_user->first_name, " ", $a_user->username, " pw: ", $a_user->password, "<br />";
	}

} else { echo "**No db!"; }  // isset DB else: END prog

?>

<?php echo "<br />" . output_message($message); ?>
<h2>Photos</h2>
<?php $photosAry = Photograph::find_all(); ?>


<?php 
// $target_path = SITE_ROOT .DS. 'public' .DS. $this->upload_dir .DS. $this->filename;
foreach ($photosAry as $photo) { // or use foreach($phs as $ph): format (no curlies)
	echo "<div style=\"float: left; margin-left: 20px;\">";
	echo "<a href=\"photo.php?id={$photo->id}\">";
	echo "<img src=\"". $photo->image_path() . "\" alt=\"" . $photo->caption . "\" ";
	echo "width=\"200\" /></a>";
	echo "<p>". $photo->caption . "</p>";
	echo "</div>";
}
?>
<?php include_layout_template("footer.php") ?>


