<?php
// file should be called through initialize.php allowing LIB_PATH.DS
// require_once(LIB_PATH.DS."config.php");
require_once("config.php");  //*** need to establish above line
class MySQLDatabase {

  private $lastQuery;
  private $dsn;
  private $opt;
  public $pdo;



   // Note: __construct is only run once,
   // 	so it is a good place to do checks that may required later by other methods
  public function __construct() {
      $this->dsn = "mysql:host=". DB_SERVER . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR;

      $this->opt = [
           PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
           PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
           PDO::ATTR_EMULATE_PREPARES   => false,
      ];
      $this->openConnection();
	}

  public function openConnection() {  // ?make_pdo
      try {
        // echo "PDO string: <br />" . $this->dsn . ", " . DB_USER . ", " . DB_PASS . ", " .  $this->opt . "<br />";
        $this->pdo = new PDO($this->dsn, DB_USER, DB_PASS, $this->opt);
      } catch (Exception $e) {
        echo "DB connect failed: </br/>";
        var_dump($e->getMessage());

      }
/*
    	// Test if connection succeeded
    	if(pdo_connect_errno()) {
    		die("Database connection failed: " .
    				pdo_connect_error() .
    				" (" . pdo_connect_errno() . ")"
    			);
    		}
*/
  }

  public function closeConnection() {
    if (isset($this->pdo)) {
     		$this->pdo = null;
    }
  }

// ** Change query and confirmQuery to Prepare/Execute format
/*
// ** below VS. PDO: $stmt = $pdo->query('SELECT name FROM users');
public function query($query){
	$this->lastQuery = $query;
  $result = $this->pdo->query($query);
  $this->confirmQuery($result);
	return $result;
}

private function confirmQuery($result) {
  if (!$result) {
  		$output = "Database query failed: " . pdo_error() . "<br /><br />";
  		$output .= "Last SQL query: " . $this->lastQuery;
      die($output);
  }
}
*/

}
// note: this allows us options of alternate db classes (e.g. class OracleDatabase)
// ** see "Making Database … Agnostic (course file)

$database = new MySQLDatabase();
//  if we want to close it: $database->closeConnection();

// if you want a shortcut/alias reference:
$db =& $database;
// $pdo =& $db->pdo;
