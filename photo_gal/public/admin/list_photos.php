<?php
require_once("../../includes/initialize.php");
require_once(LIB_PATH.DS."comment.php");

if(!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php // not needed, $message cleared in session; $message =""; ?>
<?php include_layout_template("admin_header.php") ?>
<h2>List of Photos</h2>
<a href="index.php">&laquo; Back</a>
<?php $photosAry = Photograph::find_all(); ?>
<?php echo output_message($message); ?>
<table class="bordered">
<tr>
	<th>Image</th>
	<th>Filename</th>
	<th>Type</th>
	<th>Size</th>
	<th>Caption</th>
	<th>Show comments</th>
	<th>&nbsp;</th>
</tr>

<?php 
// $target_path = SITE_ROOT .DS. 'public' .DS. $this->upload_dir .DS. $this->filename;
foreach ($photosAry as $photo) { // or use foreach($phs as $ph): format (no curlies)
	echo "<tr>";
	echo "<td><img src=\"../". $photo->image_path() . "\" alt=\"" . $photo->caption . "\" ";
	echo "width=\"100\" /></td>";
	echo "<td>". $photo->filename . "</td>";
	echo "<td>". $photo->type . "</td>";
	echo "<td>". $photo->size_as_text() . "</td>";
	echo "<td>". $photo->caption . "</td>";
	echo "<td><a href=\"list_photo_comments.php?id=" . $photo->id . "\">";
	echo count($photo->comments()) . " Comments</a></td>";
	echo "<td><a href=\"delete_photo.php?id=" . $photo->id . "\">Delete</a></td>";
	echo "</tr>";
}
?>
</table>
<br>
<a href="photo_upload.php">Upload a new photo</a>

<?php include_layout_template("admin_footer.php") ?>