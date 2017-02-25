<?php
// class created as late static binding parent of (extends) user.php
require_once (LIB_PATH.DS.'database.php');

class DatabaseObject {
	/* not needed
	public static function set_tbName($tbName) {
		static::$tbName = $tbName;
	} */  // **But don't set: protected static $tbName=""; here as latebinding

	public static function find_all() {
		return static::find_by_sql("SELECT * FROM ".static::$tbName);
	}

	public static function find_by_id($id=0) {
		global $db;
		$sql_to_prep = "SELECT * FROM ".static::$tbName . " WHERE id=:id";
		$res_ary = static::find_by_sql($sql_to_prep, array(":id" => "{$id} LIMIT 1"));

		return !empty($res_ary) ? array_shift($res_ary) : false;
	}

	// Skoglund has find_by_sql (self/static::find_by_sql in 'find' above--late binding 'static')
	public static function find_by_sql($sql="", $field_val_ary="") {
		global $db;
		$sth = $db->exec_qry($sql, $field_val_ary);  // statement handler
		$obj_array = array();
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$obj_array[] = static::instantiate($row);
		}
		return $obj_array;
	}

	 private static function instantiate($sth_rec) {
	 	// ? Could check that $sth_rec exists and is array...?
	 	
	 	$class_name = get_called_class();
	 	$obj = new $class_name; // static;
	 	// Simple, long-form approach:
	 	/* $obj->id = 				$sth_rec['id'];
	 	 $obj->username = 	$sth_rec['username'];
	 	 $obj->password = 	$sth_rec['password'];
	 	 $obj->first_name = $sth_rec['first_name'];
	 	 $obj->last_name = 	$sth_rec['last_name']; */


	 	// More dynamic, short-form approach:
	 	foreach ($sth_rec as $attribute=>$value){
	 		if($obj->has_attribute($attribute)) {
	 			$obj->$attribute = $value;
	 		}
	 	}

	 	return $obj;
	 }

	 private function has_attribute($attribute) {
	 	// get_object_vars returns an associative array with all attributes
	 	// (incl. private ones!) as the keys and their current values as the value
	 	$object_vars = get_object_vars($this);
	 	// We don't care about the value, we just want to know if the key exists
	 	// Will return true or false
	 	return array_key_exists($attribute, $object_vars);

	 }

} // ** END class database_object
?>