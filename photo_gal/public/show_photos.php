<?php
require_once("../includes/initialize.php");
?>
<?php // not needed, $message cleared in session; $message =""; ?>
<?php include_layout_template("header.php") ?>
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