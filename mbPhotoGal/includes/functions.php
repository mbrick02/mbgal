<?php
function redirect_to($new_location) {
	header("Location: " . $new_location);
exit;
}

function mysql_prep($string) {  // ** DONT USE - use escapeValue - make db lang agnostic
	$escaped_string = mysqli_real_escape_string($this->connection, $string);
	return $escaped_string;
}

// This would not be db agnostic: public function mysqlPrep($string) {
// this is a mysqli function to clean up string for sql query
function escapeValue($string) {	
	$escaped_string = mysqli_real_escape_string($this->connection, $string);
	return $escaped_string;
}

function strip_zeros_from_date($marked_string="") {
	// remove the marked zeros
	$no_zeros = str_replace('*0', '', $marked_string);
	// remove any remaining marks
	$cleaned_string = str_replace('*', '', $no_zeros);
	return $cleaned_string;
}
?>
