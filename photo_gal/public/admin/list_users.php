<?php
require_once("../../includes/initialize.php");
// require_once(LIB_PATH.DS."comment.php");

// linux: mber01 secretpw1
// win: mber01	secretpw2
if(!$session->is_logged_in()) { redirect_to("login.php"); }
$upperPage = ""; // *** DELETE this with below AND echo line in page

// **** DELETE BELOW AFTER WE IMPLEMENT HASH PW ****

if(isset($database)) { // if NOT do NOTHING (so NOT indenting)
	// $sql_to_prep = "INSERT INTO users (id, username, password, first_name, last_name) ";
	// $sql_to_prep .= "VALUES (1, :usernm, 'secretpw1', 'Mich1', 'Bri1')";
	// $result = $db->exec_qry($sql_to_prep, array(':usernm' => 'mber01'));
	
	/*  DEBUG probably don't need to look at user #1 anymore
	$user = User::find_by_id(1);
	if ($user) {
		/* *** DEBUG:
		 echo "user fullname: " . $user->fullNam() . "<hr />";
		 echo "<br/> <pre>";
		 echo print_r($user);
		 echo "</pre>";
		 // /* *.../
	} else {
		$upperPage .= "no user found. <br />";
	} END DEBUG 1 $user */
	
	/*  DEBUG2 $found_users -- listed below in table
	$found_users = User::find_all();
	
	$i = 0;
	foreach ($found_users as $a_user) {
		++$i;
		$upperPage .= "user $i: " . $a_user->id . " " . $a_user->first_name . " ";
		$upperPage .=  $a_user->username . " pw: " . $a_user->password . "<br />";
	} END DEBUG 2 $found_users */
	
} else { $upperPage .= "**No db!"; }  // isset DB else: END prog
?>
<?php // not needed, $message cleared in session; $message =""; ?>
<?php include_layout_template("admin_header.php") ?>
<?php echo $upperPage; // *** remove this when you remove sect. above ?>
<h2>List of Users</h2>
<a href="index.php">&laquo; Back</a><br>
<?php $usersAry = User::find_all(); ?>
<?php echo output_message($message); ?>
<table class="bordered">
<tr>
	<th>uname</th>
	<th>fName</th>
	<th>lName</th>
	<th>pw</th>
	<th>&nbsp;</th>
</tr>

<?php 
// $target_path = SITE_ROOT .DS. 'public' .DS. $this->upload_dir .DS. $this->filename;
foreach ($usersAry as $user) { // or use foreach($phs as $ph): format (no curlies)
	echo "<tr>";
	echo "<td>{$user->username}</td>";
	echo "<td>". $user->first_name . "</td>";
	echo "<td>". $user->last_name. "</td>";
	echo "<td>". $user->password. "</td>";
	echo "<td><a href=\"delete_user.php?id=" . $user->id;
	echo "\" onclick=\"return confirm('Are you sure?');\">Delete</a></td>";
	echo "</tr>";
}
/*
 $user = User::findByID(2);
 $user->delete();
 echo $user->firstName . " was deleted";  // instance (not user) is still there  */
?>
</table>
<br>
<a href="create_user.php">Create user</a>

<?php include_layout_template("admin_footer.php") ?>