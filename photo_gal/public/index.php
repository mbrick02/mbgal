<?php
require_once("../includes/database.php");
require_once("../includes/user.php");

if(isset($database)) { // if NOT do NOTHING (so NOT indenting)
// $sql_to_prep = "INSERT INTO users (id, username, password, first_name, last_name) ";
// $sql_to_prep .= "VALUES (1, :usernm, 'secretpw1', 'Mich1', 'Bri1')";
// $result = $db->exec_qry($sql_to_prep, array(':usernm' => 'mber01'));

echo "user fullname: " . $user->fullNam();
$sth_rec = User::find_by_id(1);

$found_users = $User::find_all();

/* $i = 0;
While ($user = $found_users->fetch(PDO::FETCH_ASSOC)){
	++$i;
	echo "user $i: ", $user['first_name'], " ", $user['last_name'], "<br />";
} */


echo $found_user['username'];
} else { echo "**No db!"; }  // isset DB else: END prog

?>

