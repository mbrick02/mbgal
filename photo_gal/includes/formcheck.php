<?php
// Class for database form content processiing specifically
// needs database.php
require_once (LIB_PATH.DS.'database.php');

// **4/16/17 currently UNTESTED and UNUSED code originally plain functions NOT class

// **automate this as much as possible to deal with the form given  


class Formcheck { // ******extends DatabaseObject -- may not NEED to extend DB
	public $errors = array();
	public $form_vals = array(); // assoc. array of (should be) text values.

	
	public function presence($value) {
		return (isset($value) && $value !== "");
	}
	
	public function proper_post($req_flds) {	
		// idea of func based on: $username = isset($_POST['username']) ? $_POST['username'] : "";
		// 	UPDATED so that now put values in Formcheck::from_vals
		
		// Validations		//$req_flds example: $required_fields = array("username", "password");
		foreach ($req_flds as $field) {
			if (isset($_POST[$field])){
				$value = trim($_POST[$field]);
				if (!$this->presence($value)) {
					$this->errors[$field] = ucfirst($field) . " can't be blank";
				} else {
					$this->form_vals[$field] = $value; // note: use of ->$field so field name is used as attribute
				}
			} else {
				$this->errors[$field] = ucfirst($field) . " not set as proper post";
			}
			
		} // end foreach

		/*  DEBUG uname & pw validation
		**END DEBUG uname & pw validation */
		
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
			if (!self::has_max_length($value, $max)) {
				$this->errors[$field] = ucfirst($field) . " is too long";
			}
		}
	}
	
	// *	Type
	
	// * Inclusion in a set
	public static function has_inclusion_in($value, $set) {
		return in_array($value, $set);
	}
	
	
	// * Format in this case check for at sign by default
	public static function has_char($value, $char="@") {
		return strpos($value, $char) === false;
	}
	
	public static function ok_tfld($username, $min_length=3) {
		$regex = '/^[a-zA-Z0-9\?\*\&\%\$]{' . $min_length . ',}$/';
		// "/^[a-zA-Z0-9\?\*\&\%\$]{5,}$/"
		return preg_match($regex, $username) ? true : false;
		// start alphaNum or certain punct -- 5 or less to end
		// \p{P} -- chars of punctu. prop (but no * and allowed \)
		// \s=char, space or new line--not needed
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