<?php
// Class for database form content processiing specifically
// needs database.php
require_once (LIB_PATH.DS.'database.php');

// **4/16/17 currently UNTESTED and UNUSED code originally plain functions NOT class
// has alphaNum or punct: preg_match("/^[a-zA-Z0-9\p{P}]{5,}$/", "", $str);
// \p{P}
// \s=char, space or new line--not needed

// **automate this as much as possible to deal with the form given  


class Formcheck { // ******extends DatabaseObject -- may not NEED to extend DB
	public $errors = array();
	
	public static function has_presence($value) {
		return (isset($value) && $value !== "");
	}
	
	public static function has_min_length($value, $min) {
		return strlen($value) >= $min;
	}
	
	public static function has_max_length($value, $max) {
		return strlen($value) <= $max;
	}
	
	public static function validate_max_lengths($fields_with_max_lengths) {
		
		// Uses/requires an assoc. array
		foreach($fields_with_max_lengths as $field => $max) {
			$value = trim($_POST["$field"]);
			if (!has_max_length($value, $max)) {
				$this->errors[$field] = ucfirst($field) . " is too long";
			}
		}
	}
	
	// *	Type
	
	// * Inclusion in a set
	public static function has_inclusion_in($value, $set) {
		return in_array($value, $set);
	}
	
	// * Uniqueness (check DB)
	
	
	// * Format in this case check for at sign by default
	public static function has_char($value, $char="@") {
		return strpos($value, $char) === false;
	}
	
	public static function accept_username($username) {
		return preg_match("/^[a-zA-Z0-9\?\*\&\%\$]{5,}$/", $username) ? true : false;
	}
	
	public function form_errors() {
		$output = "";
		if (!empty($this->errors)) {
			$output .= "<div class=\"error\">";
			$output .=  "Please fix the following errors:";
			$output .=  "<ul>";
			foreach ($this->errors as $key => $error) {
				$output .=  "<li>{$error}</li>";
			}
			$output .=  "</ul>";
			$output .=  "</div>";
		}
		return $output;
	}
	
}
?>