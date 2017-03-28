<?php
// Define the core paths
// Define them as absolute paths to make sure that require_once works as expected

// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// (\ for Windows, / for Unix) *** NOTE: I willhave to test for Windows or Linux path
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

// *** NOTE: I willhave to test for Windows or Linux path
if (PHP_OS === "Linux") { // or php_uname('s')
	defined('SITE_ROOT') ? null : 
define('SITE_ROOT', DS.'var'.DS.'www'.DS.'html'.DS.'sandbox'.DS.'GitPhoGal11'.DS.'mbgal'.DS.'photo_gal');
} elseif (PHP_OS === "WINNT") {
//	define('SITE_ROOT', DS.'Users'.DS.'mbrickler'.DS.'Sites'.DS.'photo_gallery')/
	defined('SITE_ROOT') ? null :
	// die("You need to define Win SITE_ROOT in includes/initialize.php (?c:backslsh?)");
	define('SITE_ROOT', 'C:'.DS.'wamp64'.DS.'www'.DS.'sandbox'.DS.'mbPhotGalRepo'.DS.'mbgal'.DS.'photo_gal');
	// C:\wamp64\www\sandbox\mbPhotGalRepo\mbgal\photo_gal\includes
} else {
//	define('SITE_ROOT', DS.'Users'.DS.'mbrickler'.DS.'Sites'.DS.'photo_gallery')/
	defined('SITE_ROOT') ? null :
	die("You need to define OS SITE_ROOT in includes/initialize.php (?c:backslsh?)");
}
defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');

// load config file first
require_once(LIB_PATH.DS."config.php");

// load basic functions next so that everything after can use them
require_once(LIB_PATH.DS."functions.php");

// load core objects
require_once(LIB_PATH.DS."session.php");
require_once(LIB_PATH.DS."database.php");
require_once(LIB_PATH.DS."database_object.php");
require_once(LIB_PATH.DS."database_object.php");
require_once(LIB_PATH.DS."pagination.php");

// load database-related classes
require_once(LIB_PATH.DS."user.php");
require_once(LIB_PATH.DS."photograph.php");

// load logger class
require_once(LIB_PATH.DS."logger.php");

?>