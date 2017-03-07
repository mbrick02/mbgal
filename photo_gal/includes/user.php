<?php
require_once (LIB_PATH.DS.'database.php');

class User extends DatabaseObject {
	protected static $tbName="users";
	public $id;
	public $username;
	public $password;
	public $hashpw;
	public $first_name;
	public $last_name;
	
	public static function authenticate($username="", $password="") {
		global $db;
		// Skoglund uses escape_val(), but don't need to with DBO->exec
		// $username = $db->escape_value($username);
		// $username = $db->escape_value($username);
		// Won't use password_hash here (use only for New User):
		// $hashed_password = password_hash($password); // , $defaultalgo
		//  Instead, query for user via username (which MUST be unique)
		//    and verify with password_verify($password, $user["hashed_password"]))
	
		$sql = "SELECT * FROM ".self::$tbName . " ";
		$sql .= "WHERE username = '{$username}' ";
		$sql .= "LIMIT 1";
	
		$res_ary = self::find_by_sql($sql);
		$user = !empty($res_ary) ? array_shift($res_ary) : false;
		if ($user) {
			// upgrade: if (!password_verify($password, $user['hashed_pw'])) {
			if (!($user->password === $password)) {
				$user = false;
			}
		}
		return $user;
	}
	
	public function fullNam(){
		if(isset($this->first_name) && isset($this->last_name)){
			return $this->first_name . " " . $this->last_name;
		} else {
			return "";
		}
	}

	function hash_pword($pword) {
		 $hashedPword = password_hash(trim($pword), PASSWORD_BCRYPT, ['cost' => 10]);
		 return $hashedPword;
	}
	
	/* old function version of "authenticate" above
	 function attempt_login($username, $password) {
		 // find user
		 $admin = find_admin_by_username($username);
		
		 if ($admin) {
		 // found admin now check password
		 if (password_verify($password, $admin["hashed_password"])) {
		 return $admin;
		 } else {
		 // admin not found
		 return false;
		 } // else if($admin)
		
		  } else {
		  // admin not found
		  return false;
		  }
	} */  // use function authenticate instead
	
// original prototype code for database_object
/*  continue comment expanded to end (after database_object extending)

// Common database methods
	public static function find_all() {
		return self::find_by_sql("SELECT * FROM ".self::$tbName);
	}
	
	public static function find_by_id($id=0) {
		global $db;
		$sql_to_prep = "SELECT * FROM ".self::$tbName . " WHERE id=:id";
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
	
	private static function instantiate($sth_rec) {
		// ? Could check that $sth_rec exists and is array...?
		// Simple, long-form approach:
		$obj = new self;
		/* $obj->id = 				$sth_rec['id'];
		$obj->username = 	$sth_rec['username'];
		$obj->password = 	$sth_rec['password'];
		$obj->first_name = $sth_rec['first_name'];
		$obj->last_name = 	$sth_rec['last_name']; */
/*  continue comment expanded to end (after database_object extending)
		
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
*/ // comment out code for what was previous prototype for database_object
		 
} // ** END class User

// User::set_tbName("users");  // not needed
?>