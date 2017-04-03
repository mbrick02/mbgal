<?php
require_once("../../includes/initialize.php");
require_once(LIB_PATH.DS."comment.php");

if(!$session->is_logged_in()) { redirect_to("login.php"); }

// need to get the photo id to show the image and find all the comments
if(empty($_GET['id'])) {
	$session->message("No photograph ID was provided.");
	redirect_to('index.php');
}

$photo = Photograph::find_by_id($_GET['id']);
if (!$photo) {
	$session->message("The photo could not be located.");
	redirect_to('index.php');
}


$comments = $photo->comments();
?>
<?php 
/*
 * **** use this sort of code for photos pagination ****
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
 * 
 */

?>
<?php include_layout_template("admin_header.php") ?>

<a href="index.php">&laquo; Back</a><br />
<br />

<div style="margin-left: 20px;">
	<img src="../<?php echo $photo->image_path(); ?>" width="400px"/>
	<p><?php echo $photo->caption; ?></p>
</div>

<h2>Comments on Photo</h2>
<?php echo output_message($message); ?>
<table class="bordered">
<tr>
	<th>Image</th>
	<th>Author</th>
	<th>Comment</th>
	<th>Date</th>
	<th>&nbsp;</th>
</tr>

<?php 
// $target_path = SITE_ROOT .DS. 'public' .DS. $this->upload_dir .DS. $this->filename;
foreach ($comments as $comment) { // or use foreach($phs as $ph): format (no curlies)
	echo "<tr>";
	echo "<td><img src=\"../". $photo->image_path() . "\" alt=\"" . $photo->caption . "\" ";
	echo "width=\"100\" /></td>";
	echo "<td>". $comment->author . "</td>";
	echo "<td>". $comment->body . "</td>";
	echo "<td>". datetime_to_text($comment->created) . "</td>";
	echo "<td><a href=\"delete_comment.php?id=" . $comment->id . "\">Delete</a></td>";
	echo "</tr>";
}
?>
</table>
<br />

<?php include_layout_template("admin_footer.php") ?>

?>