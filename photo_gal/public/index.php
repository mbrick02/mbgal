<?php
	require_once("../includes/initialize.php");

	$upperPage = ""; // *** DELETE this with below AND echo line in page
	
	// **** DELETE BELOW AFTER WE IMPLEMENT HASH PW ****
	
	if(isset($database)) { // if NOT do NOTHING (so NOT indenting)
	// $sql_to_prep = "INSERT INTO users (id, username, password, first_name, last_name) ";
	// $sql_to_prep .= "VALUES (1, :usernm, 'secretpw1', 'Mich1', 'Bri1')";
	// $result = $db->exec_qry($sql_to_prep, array(':usernm' => 'mber01'));
	
	$user = User::find_by_id(1);
	if ($user) {
		/* *** DEBUG: 
		echo "user fullname: " . $user->fullNam() . "<hr />";
		echo "<br/> <pre>";
		echo print_r($user);
		echo "</pre>";
		// /* */
	} else {
		$upperPage .= "no user found. <br />";
	}
	$found_users = User::find_all();
	
	$i = 0;
	foreach ($found_users as $a_user) {
		++$i;
		$upperPage .= "user $i: " . $a_user->id . " " . $a_user->first_name . " ";
		$upperPage .=  $a_user->username . " pw: " . $a_user->password . "<br />";
	}

} else { $upperPage .= "**No db!"; }  // isset DB else: END prog

?>
<?php // **** DELETE ABOVE AFTER WE IMPLEMENT HASH PW **** 
	include_layout_template("header.php");
?>
<?php echo $upperPage; // *** remove this with above ?>
<?php echo "<br />" . output_message($message); ?>
<h2>Photos</h2>
<?php 
	// 1. the current page number ($current_page)
	$page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
	
	// 2. records per page ($per_page)
	$per_page = 2;
	
	// 3. total record count ($total_count)
	$total_count = Photograph::count_all();
	
	// Find all photos
	// use ($photos) pagination instead of: $photosAry = Photograph::find_all();
	
	$pagination = new Pagination($page, $per_page, $total_count);
	
	$sql = "SELECT * FROM photographs ";
	$sql .= "LIMIT {$per_page} ";
	$sql .= "OFFSET {$pagination->offset()}";
	$photos = Photograph::find_by_sql($sql);
	
	// Need to add ?page=$page to links want to maintain the curr page ($page in $session)
?>
<?php 
// $target_path = SITE_ROOT .DS. 'public' .DS. $this->upload_dir .DS. $this->filename;
// (Note: previously used $photosAry via Photograph::find_all())
foreach ($photos as $photo) { // or use foreach($phs as $ph): format (no curlies)
	echo "<div style=\"float: left; margin-left: 20px;\">";
	echo "<a href=\"photo.php?id={$photo->id}\">";
	echo "<img src=\"". $photo->image_path() . "\" alt=\"" . $photo->caption . "\" ";
	echo "width=\"200\" /></a>";
	echo "<p>". $photo->caption . "</p>";
	echo "</div>";
}
?>
<div id="pagination" style="clear: both;">
<?php 
	if($pagination->total_pages() > 1) {
		
		if($pagination->has_previous_page()) {
			echo " <a href=\"index.php?page=";
			echo $pagination->previous_page();
			echo "\">&laquo; Previous</a> ";
		}
		
		for ($i=1; $i <= $pagination->total_pages(); $i++) {
			if($i == $page) {
				echo " <span class=\"selected\">{$i}</span> ";
			} else {
				echo " <a href=\"index.php?page={$i}\">{$i}</a> ";
			}
			
		}
		
		if($pagination->has_next_page()) {
			echo " <a href=\"index.php?page=";
			echo $pagination->next_page();
			echo "\">Next &raquo;</a> ";
		}
	}
?>
</div>
<?php include_layout_template("footer.php") ?>


