<?php
// file should be called through initialize.php allowing LIB_PATH.DS
require_once(LIB_PATH.DS."config.php");

class MySQLDatabase {

  private $lastQuery;
  private $dsn;
  private $opt;
  private $connection;

  $this->dsn = "mysql:host=". DB_SERVER . ";dbname=" . DB_NAME . ";charset=" . DB_CHAR;


  $this->opt = [
       PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
       PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
       PDO::ATTR_EMULATE_PREPARES   => false,
   ];

   // Note: __construct is only run once,
   // 	so it is a good place to do checks that may required later by other methods
  public function __construct() {
	   $this->openConnection();
	}

   public function openConnection() {
	// *** change to PDO: $pdo = new PDO("mysql:host=localhost;dbname=database", 'username', 'password');
// $this->connection = new PDO("mysql:host=". DB_SERVER . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
// $this->connection = new PDO($this->dbn, DB_USER, DB_PASS, $this->opt);
  $this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

	// Test if connection succeeded
	if(mysqli_connect_errno()) {
		die("Database connection failed: " .
				mysqli_connect_error() .
				" (" . mysqli_connect_errno() . ")"
			);
		}
   }

   public function closeConnection() {
	if (isset($this->connection)) {
   		mysqli_close($this->$connection);
}
   }

// ** Change query and confirmQuery to Prepare/Execute format
/*
    // ** below VS. PDO: $stmt = $pdo->query('SELECT name FROM users');
    public function query($query){
    	$this->lastQuery = $query;
      // $this->query??? = $this->connection->query?????
    $result = mysqli_query($this->connection, $query);
         // collected in $result resource; note reverse order from mysql()
    $this->confirmQuery($result);
    	return $result;
    }

    private function confirmQuery($result) {
      if (!$result) {
      		$output = "Database query failed: " . mysqli_error() . "<br /><br />";
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
