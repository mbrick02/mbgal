<?php
	require_once("../includes/initialize.php");
?>
<?php 
	include_layout_template("header.php");
?>
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


