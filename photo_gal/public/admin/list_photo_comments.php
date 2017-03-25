// **** FROM photo.php  BELOW IS code from list_photos
// need to get the photo id to show the image and find all the comments 
<?php
require_once("../includes/initialize.php");
require_once(LIB_PATH.DS."comment.php");

if(empty($_GET['id'])) {
	$session->message("No photograph ID was provided.");
	redirect_to('index.php');
}

$photo = Photograph::find_by_id($_GET['id']);
if (!$photo) {
	$session->message("The photo could not be located.");
	redirect_to('index.php');
}

if(isset($_POST['submit'])) {
	$author = trim($_POST['author']);
	$body = trim($_POST['body']);
	
	$new_comment = Comment::make($photo->id, $author, $body);
	if($new_comment && $new_comment->save()) {
		// was saved -- no $message nessary, because comment will now show
		
		// Don't render (or could resubmit same values) instead...
		redirect_to("photo.php?id={$photo->id}");
	} else {
		// Failed
		$message = "There was an error that prevented the comment from being saved.";
	}
} else {
	$author = "";
	$body = "";
}

$comments = $photo->comments();
?>
<?php include_layout_template("header.php") ?>

<a href="index.php">&laquo; Back</a><br />
<br />

<div style="margin-left: 20px;">
	<img src="<?php echo $photo->image_path(); ?>" width="400px"/>
	<p><?php echo $photo->caption; ?></p>
</div>

<div id="comments">
	<?php foreach($comments as $comment): ?>
		<div class="comment" style="margin-bottom: 2em";>
			<div class="author">
				<?php echo htmlentities($comment->author); ?> wrote:
			</div>
			<div class="body">
				<?php echo strip_tags($comment->body, '<strong><em><p>'); ?>
			</div>
			<div class="meta-info" style="font-size: 0.8em;">
				<?php echo htmlentities(datetime_to_text($comment->created)); ?>
			</div>
		</div>
	<?php endforeach; ?>
	<?php if(empty($comments)) { echo "No Comments."; } ?>
</div>


<?php include_layout_template("footer.php") ?>

// ****FROM list_photos.... ***************************************************************
<?php
require_once("../../includes/initialize.php");

if(!$session->is_logged_in()) { redirect_to("login.php"); }
?>
<?php // not needed, $message cleared in session; $message =""; ?>
<?php include_layout_template("admin_header.php") ?>
<h2>List of Photos</h2>
<?php $photosAry = Photograph::find_all(); ?>
<?php echo output_message($message); ?>
<table class="bordered">
<tr>
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
	echo "<td>". $comment->date . "</td>";
	echo "<td><a href=\"delete_comment.php?id=" . $photo->id . "\">Delete</a></td>";
	echo "</tr>";
}
?>
</table>
<br>
<a href="photo_upload.php">Upload a new photo</a>

<?php include_layout_template("admin_footer.php") ?>

?>