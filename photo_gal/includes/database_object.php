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
	 	$object_vars = $this->attributes();
	 	// We don't care about the value, we just want to know if the key exists
	 	// Will return true or false
	 	return array_key_exists($attribute, $object_vars);

	 }
	 
	 protected function attributes() {
	 	// return an array of attribute keys and their values
	 	// return get_object_vars($this); // ** returns all attribs
	 	// 		--private and protected and ones that don't have corresponding db fields
	 	$attributes = array();
	 	foreach(static::$db_fields as $field){
	 		if(property_exists($this, $field)) {
	 			$attributes[$field] = $this->$field;
	 		}
	 	}
	 	return $attributes;
	 }
	
	 public function save() {
	 	// a new record won't have an id yet.
	 	return isset($this->id) ? $this->update() : $this->create();
	 }
	 
	 /*
	  *  not used since we use PDO -- which does not allow sql injection 
	  protected function sanitized_attributes() {
	  	global $db;
	  	$clean_attributes = array();
	  	
	  	// sanitize the values before submitting
	  	 for each($this->attributes() as $key => $value){
	  	 	$clean_attributes[$key] = $db->escape_value($value);
	  	 }
	  	 return $clean_attributes;
	  }
	  
	  * 
	  */
	 
	public function create($aryFlds="") {  // $aryFlds is assoc. array $key => $val
		 global $db;
		 $attributes = $this->attributes(); // get all attributes for class/this object
		 
		 if (!is_array($aryFlds)){
		 	$aryFlds = $attributes;  // put all attributes array of fields
		 }
		 $aryFlds = array_filter($aryFlds);  // take out empty $key/$vals
		
		$aryFldKeys = array_keys($aryFlds);
	 	$strFlds = implode(", ", $aryFldKeys);  // implode() = join() //'username', 'password', 'first...
	 	$aryParams = $aryFldKeys;
	 	foreach ($aryParams as &$value) {
	 		$value = ':'.$value;
	 	}
	 	unset($value);
	 	$strParams = implode(", ", $aryParams);
		
		 // INSERT INTO table (key, key) VALUES ('value', 'value') use PDO
		 $sql = "INSERT INTO " . static::$tbName ." (";
		 $sql .= $strFlds; //'username', 'password', 'first...
		 $sql .= ") VALUES (";
		 $sql .= $strParams . ")"; // ":username, :password, :first..." . ")"; // NOTE: no 's
		 
		 
	 	// combine so $fld_param_fld_ary[':fieldx'] = 'fieldx'
	 	$fld_param_val = array_combine($aryParams, array_values($aryFlds));
/*	(used when $aryFlds was not assoc array with vals)	 $field_val_ary = array();
		 foreach($fld_param_val as $key => $value) {
		 	$field_val_ary[$key] = $this->{$value};
		 } */
		 // $field_param_val=array(':uname' => 'auname',...=array(':f1' => $this->f1,':f2...
		  
		 $sth = $db->exec_qry($sql, $fld_param_val);  // statement handler
		 
		 if ($sth) {
		 	$this->id = $db->pdo->lastInsertId();
		 	return true;
		 } else {
		 	return false;
		 }
		 
	}  // end public create()
	
	public function update($aryFlds="") {  // $aryFlds should be assoc. array $key => '$value'
	 	 global $db;
	 	 $attributes = $this->attributes(); // get all attributes for class/this object
	 	 	
	 	 if (!is_array($aryFlds)){
	 	 	$aryFlds = $attributes;  // put all attributes array of fields
	 	 }
	 	 $aryFlds = array_filter($aryFlds);  // take out empty $key/$vals

	 	 $aryFldKeys = array_keys($aryFlds);
	 	 
	 	 $aryParams = $aryFldKeys;
	 	 foreach ($aryParams as &$value) {
	 	 	$value = ':'.$value;
	 	 }
	 	 unset($value);

	 	 
	 	 // set so $fld_param_fld_ary[':fieldx'] = 'fieldx'
	 	 $fld_param_key = array_combine($aryParams, $aryFldKeys);
	 	 
	 	 $attrib_pairs = array();
	 	 foreach($fld_param_key as $key => $value) {
	 	 	// Examp.:  "username=" . :username . "', ";  ... ."'";
	 	 	$attrib_pairs[] =  "{$value}=" . $key;
	 	 }
	 	 // examp $attrib_pairs = array("fld1=:fld1", "f2=:f2"...);
	 	 
	 	 // UPDATE table SET key='value', key='value' WHERE condition (sing-quo vals)
	 	 // Examp: UPDATE table SET username='unameVal', ... WHERE id=1
	 	 // Examp: UPDATE table SET username=:param1, ... WHERE id=1 // NOTE: no 's
	 	 $sql = "UPDATE " . static::$tbName ." SET ";
	 	 $sql .= join(", ", $attrib_pairs); // same as implode(',', $ary);
	 	 $sql .= " WHERE id=" . $this->id; // id from DB so should be safe from sql injection
	 	 
	 	 $fld_param_val = array_combine($aryParams, array_values($aryFlds));
	 	 
	 	 $sth = $db->exec_qry($sql, $fld_param_val);  // statement handler
	 	 
	 	 // $affected_rows = $stmt->rowCount(); (not $db->pdo) instead of mysqli affected_rows
	 	 return ($sth->rowCount() == 1) ? true : false;
	 	 // DEBUG 
	 	 /* if ($sth->rowCount() == 1){
	 	 	return true;
	 	 } else {
	 	 	return false;
	 	 	// ** DEBUG: return $fld_param_val;
	 	 } */
	} // End public method update()
	 
	public function delete() {
		global $db;
		
		$sql = "DELETE FROM " . static::$tbName;
		$sql .= " WHERE id=" . $this->id;  // id from DB so should be safe from sql injection
		$sql .= " LIMIT 1";
	 	
		$sth = $db->exec_qry($sql);  // statement handler
		
		// $affected_rows = $stmt->rowCount(); (not $db->pdo) instead of mysqli affected_rows
		return ($sth->rowCount() == 1) ? true : false;
	}
} // ** END class database_object
?>