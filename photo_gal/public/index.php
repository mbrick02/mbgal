<?php
require_once("../includes/database.php");

if(isset($database)) {
	$db->test();
}
else { echo "**No db!"; }
echo "<br />";

$sql_to_prep = "INSERT INTO users (id, username, password, first_name, last_name) ";
$sql_to_prep .= "VALUES (1, ':usernm', 'secretpw', 'Mer', 'Ber')";

$result = $db->exec_qry($sql_to_prep, array(':usernm' => 'mber01'));

$sql_to_prep = "SELECT * FROM users WHERE id=1";

$result = $db->exec_query($sql_to_prep);

$found_user = $result->fetch();
echo $found_user['username'];


?>
