<?php
require_once("../../includes/initialize.php");

if(!$session->is_logged_in()) { redirect_to("login.php"); }

if(isset($_GET['clear']) && ($_GET['clear']=='true')) {
	Logger::clear_log($_SESSION['username'] . " cleared file");
	// redirect to this same pages so that URL not w/'clear=true'
	redirect_to('logfile.php');
}
?>
<?php include_layout_template("admin_header.php") ?>

<a href="index.php">&laquo; Back</a>
<br />
<h2>Log File</h2>
<p><a href="logfile.php?clear=true">Clear Log</a></p>

<?php 
	if (file_exists(Logger::$logfile) && is_readable(Logger::$logfile) &&
			$handle = fopen(Logger::$logfile, 'r')) { // read
			echo  "<ul class=\"log-entries\">";
			while(!feof($handle)) {
				$entry = fgets($handle);
				preg_match("/^(.+?)\-(.+?)\-(.+?)\ " .
					"(.+?)\:(.+?)\:(.+?)\ (.+?)\:(.+?)\ (.+?)$/i", 
						$entry, $compons);
				// use $compons in future to limit by date or user or action
				// $compons: 1=Y, 2=m, 3=d, 4=h, 5=i(min), 6=s, 7=action, 8=usern 
				if(trim($entry) != "") {
					echo "<li>{$entry} by {$compons[8]}</li>";
				}
			}
			echo "</ul>";
			fclose($handle);
	} else {
		echo "Could not read from {Logger::$logfile}.";
	}
?>

<?php include_layout_template("admin_footer.php") ?>