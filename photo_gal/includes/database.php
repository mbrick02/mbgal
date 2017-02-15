<?php
// file should be called through initialize.php allowing LIB_PATH.DS
// require_once(LIB_PATH.DS."config.php");
require_once("config.php");  //*** need to establish above line
class MySQLDatabase {

  private $lastQuery = "no query yet";
  private $dsn;
  private $opt;
  public $pdo;

	public function test() {
		echo $this->lastQuery;
	}
	
	// Note: __construct run once, good for checks required by other methods
	function __construct() {
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
	        echo "DB object/connect failed: <br/>";
	        var_dump($e->getMessage());
	        die("<br />");
	      }

  }

  public function closeConnection() {
    if (isset($this->pdo)) {
     		$this->pdo = null;
    }
  }

  private function db_fail($type, $loca, $reas, $code) {
  	$output = "Database $type";
  	$output .= " failed for: <br />" .__FILE__;
  	$output .= "<br /> in $loca method: <br /><br />";
  	$output .=  "$reas<br />";
  	$output .=  "$code<br />Error Code: <br />" . $this->pdo->errorCode();
  	$output .= "<br />";
  	die ($output);
  }
  
  public function exec_qry($sql_to_prep, $array_qry_vals = "") {
  	$type = "Query";
  	$loca = "exec_qry";
  	try {
  		// $array_qry_vals =somethinglike= array(':calories' => 150, ':color' => 'red');
  		$this->lastQuery = $sql_to_prep;
  		// sth = statement handler
  		$sth = $this->pdo->prepare($sql_to_prep);
  		if (is_a($sth, "PDOStatement")){
  			// best pass ary params (not: $sth->bindParam(':var', $cals);)
  			$result = $sth->execute($array_qry_vals);
  		} else {
  			// statement failed
  			$reas = "Error with statement";
  			$code = $sql_to_prep;
  			$this->db_fail($type, $loca, $reas, $code);
  		}
  		return $result;
  	} catch (PDOException $e) {
  		$reas = "PDOException";
  		$code = $sql_to_prep . "<br />" . $e->getMessage(). "<br />";
  		$this->db_fail($type, $loca, $reas, $code);
  	}  catch (Exception $e) {
  		$reas = "Gen. Exception";
  		$code = $sql_to_prep . "<br />" . $e->getMessage(). "<br />";
  		$this->db_fail($type, $loca, $reas, $code);
  	} finally {
  		$reas = "try failed finally";
  		$code = $sql_to_prep;
  		$this->db_fail($type, $loca, $reas, $code);
  	}
  		
  	
  }
  
  
  		
  /*
  // This would not be db agnostic: public function mysqlPrep($string) {
  // this is a mysqli function to clean up string for sql query
  // *** This should NOT be used with PDO prepare/execute & $this->connection WONT work
  public function escapeValue($string) {
  	$escaped_string = mysqli_real_escape_string($this->connection, $string);
  	return $escaped_string;
  }
	*/
} // *** END class def MySQLDatabase

// note: this allows us options of alternate db classes 
//    (e.g. class OracleDatabase)
// ** see "Making Database … Agnostic (course file)

$database = new MySQLDatabase();
//  if we want to close it: $database->closeConnection();

// if you want a shortcut/alias reference:
$db =& $database;
// $pdo =& $db->pdo;
