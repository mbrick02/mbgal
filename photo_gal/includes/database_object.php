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
	 
	 public function create($aryFlds="") {
		 global $db;
		 
		 if (!is_array($aryFlds)){
		 	$aryFlds = array('username', 'password', 'first_name', 'last_name');
		 }
		 
	 	$strFlds = implode(", ", $aryFlds);
	 	$aryParams = $aryFlds;
	 	foreach ($aryParams as &$value) {
	 		$value = ':'.$value;
	 	}
	 	unset($value);
	 	$strParams = implode(", ", $aryParams);
		
		
		 // INSERT INTO table (key, key) VALUES ('value', 'value') use PDO
		 $sql = "INSERT INTO " . static::$tbName ." (";
		 // $sql .= 'username', 'password', 'first_name', 'last_name';
		 $sql .= $strFlds;
		 $sql .= ") VALUES (";
		 // $sql .= ":username, :password, :first_name, :last_name" . ")";
		 $sql .= $strParams . ")";
		 
		 $fld_param_fld_ary = array_combine($aryParams, $aryFlds);
		 $field_val_ary = array();
		 foreach($fld_param_fld_ary as $key => $value) {
		 	$field_val_ary[$key] = $this->{$value};
		 }
		 
		 /* array(':username' => $this->username, 
		 ':password' => $this->password,
		 ':first_name' => $this->first_name, ':last_name' => $this->last_name); */
		 // $field_val_ary = array(':username', ':password', 
		 //        ':first_name', ':last_name'
		  
		 $sth = $db->exec_qry($sql, $field_val_ary);  // statement handler
		 
		 if ($sth) {
		 	$this->id = $db->pdo->lastInsertId();
		 	return true;
		 } else {
		 	return false;
		 }
		 
	}  // end public create()
	
	/* to be added after test in user.php
	public function update() {
	 	 
	}
	 
	public function delete() {
	 	 
	}*/
} // ** END class database_object
?>