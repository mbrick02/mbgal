<?php
// logger called in initialize.php (required to define SITE_ROOT) 
class Logger {
	public static $logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
	
	public static function log_action($action, $message="") {
		$new = file_exists(self::$logfile); // should not need this
		$dateStr = date('Y-m-d H:i:s'); // format year/m/d:HH:MM:SS
		$separator = " ";
		$actionPad = str_pad($action, 8, ".");
		$content = $dateStr . $separator. "{$actionPad}: $message";
	
		// file_put_contents(self::$logfile, $content.PHP_EOL, FILE_APPEND) // other approach
		if($handle = fopen(self::$logfile, 'a')) { // append
			fwrite($handle, $content.PHP_EOL); // returns number of bytes or false
			fclose($handle);
			if($new) { chmod(self::$logfile, 0755); }  // should not need this
		} else {
			echo "log_action for ". self::$logfile . 
			 " <br /> could not open log file for writing for: $message";
		}
	}
	
	public static function clear_log($msg = "cleared") {
		$dateStr = date('Y-m-d H:i:s'); // format year/m/d:HH:MM:SS
			// $timestamp = strftime("%Y-%m-%d %H:%M:%S", time());  // in place of $dateStr
		$separator = " ";
		$actionPad = str_pad("cleared", 8, ".");
		$content = $dateStr . $separator . "{$actionPad}: $msg";
		
		if($size = file_put_contents(self::$logfile, $content.PHP_EOL)) {
			// echo "A file of {$size} bytes was created.";
		} else {
			echo "log_action for " . self::$logfile . " <br /> could not open log file for writing for: $message";
		}
	}
}
?>