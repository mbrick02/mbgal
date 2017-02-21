<?php
require_once ('database.php');

class User {
	public $id;
	public $username;
	public $password;
	public $first_name;
	public $last_name;
	
	public static function find_all() {
		return self::find_by_sql("SELECT * FROM users");
	}
	
	public static function find_by_id($id=0) {
		global $db;
		$sql_to_prep = "SELECT * FROM users WHERE id=:id";
		$res_ary = self::find_by_sql($sql_to_prep, array(":id" => "{$id} LIMIT 1"));
		
		return !empty($res_ary) ? array_shift($res_ary) : false;
	}
	
	// Skoglund has find_by_sql (self::find_by_sql in 'find' above) 
	public static function find_by_sql($sql="", $field_val_ary="") {
		global $db;
		$sth = $db->exec_qry($sql, $field_val_ary);  // statement handler
		$obj_array = array();
		while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
			$obj_array[] = self::instantiate($row);
		}
		return $obj_array;
	}

	public static function authenticate($username="", $password="") {
		global $db;
		// Skoglund uses escape_val(), but don't need to with DBO->exec
		// $username = $db->escape_value($username);
		// $username = $db->escape_value($username);
		$hashed_password = password_hash($password); // , $defaultalgo
		
		$sql = "SELECT * FROM users ";
		$sql .= "WHERE username = '{$username}' ";
		$sql .= "AND password = '{$hashed_password}' ";
		$sql .= "LIMIT 1";
		
		$res_ary = self::find_by_sql($sql);
		
		return !empty($res_ary) ? array_shift($res_ary) : false;
	}
	
	public function fullNam(){
		if(isset($this->first_name) && isset($this->last_name)){
			return $this->first_name . " " . $this->last_name;
		} else {
			return "";
		}
	}

	private static function instantiate($sth_rec) {
		// ? Could check that $sth_rec exists and is array...?
		// Simple, long-form approach:
		$obj = new self;
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
	
} // ** END class User
?>