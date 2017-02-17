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
		$res_sth = $db->exec_qry($sql_to_prep, array(":id" => "{$id}"));
		return $res_sth->fetch(PDO::FETCH_ASSOC);
	}
	
	// Skoglund has find_by_sql (self::find_by_sql in 'find' above) 
	public static function find_by_sql($sql="") {
		global $db;
		return $db->exec_qry($sql);  // statement handler
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
		/* $obj = new self;
		$obj->id = 				$sth_rec['id'];
		$obj->username = 	$sth_rec['username'];
		$obj->password = 	$sth_rec['password'];
		$obj->first_name = $sth_rec['first_name'];
		$obj->last_name = 	$sth_rec['last_name']; */
		
		
		// More dynamic, short-form approach:
		foreach ($sth_rec as $attribute=>$value){
			if($obj->has_attribute($attribute)) {
				$obj-$attribute = $value;
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