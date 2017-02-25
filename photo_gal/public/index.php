<?php
	require_once("../includes/initialize.php");
	
	if(isset($database)) { // if NOT do NOTHING (so NOT indenting)
	// $sql_to_prep = "INSERT INTO users (id, username, password, first_name, last_name) ";
	// $sql_to_prep .= "VALUES (1, :usernm, 'secretpw1', 'Mich1', 'Bri1')";
	// $result = $db->exec_qry($sql_to_prep, array(':usernm' => 'mber01'));
	
	$user = User::find_by_id(1);
	if ($user) {
		echo "user fullname: " . $user->fullNam() . "<hr />";
	} else {
		echo "no user found. <br />";
	}
	$found_users = User::find_all();
	
	$i = 0;
	foreach ($found_users as $a_user) {
		++$i;
		echo "user $i: ", $a_user->first_name, " ", $a_user->username, " pw: ", $a_user->password, "<br />";
	}

} else { echo "**No db!"; }  // isset DB else: END prog

?>

