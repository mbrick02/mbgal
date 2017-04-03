<?php

function displayTemplate($filename, $assignedVars) {
	if(file_exists($filename)) {
		$output = file_get_contents($filename);
		foreach($assignedVars as $key => $value) {
			$output = preg_replace('/{'.$key.'}/', $value, $output);
			// above is: regular expression replace
		}
		echo $output;
	} else {
		echo "*** Missing template error ****";
	}
}

$template = "template2.php";
$assignedVars = array('pageTitle' => "Template Test", 'content' =>"This is a test of template with search and replace.");

displayTemplate($template, $assignedVars);

?>