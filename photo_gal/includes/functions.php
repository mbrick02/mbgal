<?php
function redirect_to($location = NULL) {
	if($location != NULL) {
		header("Location: {$location}");
		exit;
	}
}

function strip_zeros_from_date($marked_string="") {
	// remove the marked zeros
	$no_zeros = str_replace('*0', '', $marked_string);
	// remove any remaining marks
	$cleaned_string = str_replace('*', '', $no_zeros);
	return $cleaned_string;
}
// define $message for other pages
$message = "";
function output_message($message="") {
	if (!empty($message)) {
		return "<p class=\"message\">{$message}</p>";
	} else {
		return "";
	}
}

function __autoload($class_name) {
	$class_name = strtolower($class_name);
	$path = LIB_PATH.DS."{$class_name}.php";
	if(file_exists($path)) {
		require_once ($path);
	} else {
		die("The file {$class_name}.php could not be found.");
	}
}

function include_layout_template($template="") {
	include(SITE_ROOT.DS.'public'.DS.'layouts'.DS.$template);
}

function log_action($action, $message="") {
	$file = SITE_ROOT.DS.'logs'.DS.'log.txt';
	$dateStr = date('Y-m-d H:i:s'); // format year/m/d:HH:MM:SS
	$separator = " ";
	$actionPad = str_pad($action, 8, ".");
	$content = $dateStr . $separator. "$actionPad: $message \n";
	
	if($handle = fopen($file, 'a')) { // append
		fwrite($handle, $content); // returns number of bytes or false
		fclose($handle);
	} else {
		echo "log_action for $file <br /> could not open log file for writing for: $message";
	}
}

function clear_log($msg = "cleared") {
	$file = SITE_ROOT.DS.'logs'.DS.'log.txt';
	$dateStr = date('Y-m-d H:i:s'); // format year/m/d:HH:MM:SS

	$content = "$dateStr   $msg\n";
	
	if($size = file_put_contents($file, $content)) {
		// echo "A file of {$size} bytes was created.";
	} else {
		echo "log_action for $file <br /> could not open log file for writing for: $message";
	}
}
?>
