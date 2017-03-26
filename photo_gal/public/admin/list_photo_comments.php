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