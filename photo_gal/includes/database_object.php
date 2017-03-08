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
		$sql_to_prep = "SELECT * FROM ".static::$tbName . " WHERE id=:id LIMIT 1";
		$res_ary = static::find_by_sql($sql_to_prep, array(":id" => "{$id}"));
		
		return !empty($res_ary) ? array_shift($res_ary) : false;
	}

	// Skoglund has find_by_sql (self/static::find_by_sql in 'find' above--late binding 'static')
	public static function find_by_sql($sql="", $field_val_ary="") {
		global $db;
		$sth = $db->exec_qry($sql, $field_val_ary);  // statement handler
		$obj_array = array();
	/* DEBUG  */	
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
	
	 public function save() {
	 	// a new record won't have an id yet.
	 	return isset($this->id) ? $this->update() : $this->create();
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
		
	 	// combine so $fld_param_fld_ary[':fieldx'] = 'fieldx'
	 	$fld_param_fld_ary = array_combine($aryParams, $aryFlds);
		
		 // INSERT INTO table (key, key) VALUES ('value', 'value') use PDO
		 $sql = "INSERT INTO " . static::$tbName ." (";
		 // $sql .= 'username', 'password', 'first_name', 'last_name';
		 $sql .= $strFlds;
		 $sql .= ") VALUES (";
		 // $sql .= ":username, :password, :first_name, :last_name" . ")"; // NOTE: no 's
		 $sql .= $strParams . ")";
		   
		 $field_val_ary = array();
		 foreach($fld_param_fld_ary as $key => $value) {
		 	$field_val_ary[$key] = $this->{$value};
		 }
		 
		 /* examp: array(':field1' => $this->field1,':field2' => $this->field2); */
		 // $field_val_ary = array(':username' => 'ausername', ... 
		  
		 $sth = $db->exec_qry($sql, $field_val_ary);  // statement handler
		 
		 if ($sth) {
		 	$this->id = $db->pdo->lastInsertId();
		 	return true;
		 } else {
		 	return false;
		 }
		 
	}  // end public create()
	
	public function update($aryFlds="") {
	 	 global $db;
	 	 
	 	 if (!is_array($aryFlds)){
	 	 	$aryFlds = array('username', 'password', 'first_name', 'last_name');
	 	 }

	 	 $aryParams = $aryFlds;
	 	 foreach ($aryParams as &$value) {
	 	 	$value = ':'.$value;
	 	 }
	 	 unset($value);

	 	 
	 	 // set so $fld_param_fld_ary[':fieldx'] = 'fieldx'
	 	 $fld_param_fld_ary = array_combine($aryParams, $aryFlds);
	 	 
	 	 $sqlUpdates = "";
	 	 $field_val_ary = array();
	 	 foreach($fld_param_fld_ary as $key => $value) {
	 	 	// Examp.: $sql .= "username='" . $this->username . "', ";  ... ."'";
	 	 	$sqlUpdates .=  "{$value}=" . $key . ", ";
	 	 	// set array of vals for :fields to be executed below
	 	 	$field_val_ary[$key] = $this->{$value};
	 	 }
	 	 
	 	 // delete last comma -- trim first trim() to get rid of last space or rtrim space too
	 	 $sqlUpdates = rtrim($sqlUpdates,', ');
	 	 
	 	 // UPDATE table SET key='value', key='value' WHERE condition (sing-quo vals)
	 	 // Examp: UPDATE table SET username='unameVal', ... WHERE id=1
	 	 // Examp: UPDATE table SET username=:param1, ... WHERE id=1 // NOTE: no 's
	 	 $sql = "UPDATE " . static::$tbName ." SET ";
	 	 $sql .= $sqlUpdates;
	 	 $sql .= " WHERE id=" . $this->id; // id from DB so should be safe from sql injection
	 	 	
	 	 /* examp array(':field1' => $this->field1,':field2' => $this->field2); */
	 	 
	 	 $sth = $db->exec_qry($sql, $field_val_ary);  // statement handler
	 	 
	 	 // $affected_rows = $stmt->rowCount(); (not $db->pdo) instead of mysqli affected_rows
	 	 
	 	 return ($sth->rowCount() == 1) ? true : false;
	} // End public method update()
	 
	public function delete($aryFlds="") {
		global $db;
		
		$sql = "DELETE FROM " . static::$tbName;
		$sql .= " WHERE id=" . $this->id;  // id from DB so should be safe from sql injection
		$sql .= " LIMIT 1";
	 	 
	}
} // ** END class database_object
?>