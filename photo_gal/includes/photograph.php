<?php
require_once (LIB_PATH.DS.'database.php');

class Photograph extends DatabaseObject {
	protected static $tbName="photographs";
	protected static $db_fields = array('id', 'filename', 'type', 'size', 'caption');
	public $id;
	public $filename;
	public $type;
	public $size;
	public $caption;
	
	private $temp_path;
	protected $upload_dir="images";
	public $errors=array();
	
	protected $uploadErrors = array(
	// http:// ww.php.net/manual/en/features/file-upload.errors.php
			UPLOAD_ERR_OK => "No error.",
			UPLOAD_ERR_INI_SIZE => "Larger than upload_max_filesize (in php.ini).",
			UPLOAD_ERR_FORM_SIZE => "Larger than MAX_FILE_SIZE.",
			UPLOAD_ERR_PARTIAL => "Partial upload.",
			UPLOAD_ERR_NO_FILE => "No file.",
			UPLOAD_ERR_NO_TMP_DIR > "No temporary directory.",
			UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
			UPLOAD_ERR_EXTENSION => "File upload stopped by extension."
	);
	
	public function destroy() {
		// First remove the database entry
		if($this->delete()) {
			// then remove the file
			$target_path = SITE_ROOT.DS.'public'.DS.$this->image_path();
			
			return unlink($target_path) ? true : false;
		} else {
			// database delete failed
			return false;
		}
	}
	
	// Pass in $_FILE(['uploaded_file']) as an argument
	public function attach_file($file) {
		// Perform error checking on the form parameters
		if(!$file || empty($file) || !is_array($file)) {
			// error: nothing uploaded or wrong argument usage
			$this->errors[] = "No file was uploaded.";
			return false;
		} elseif($file['error'] !=0) {
			// error: report what PHP says went wrong
			$this->errors[] = $this->upload_errors[$file['error']];
			return false;
		} else {  // everything okay
			// Set object attributes to the form parameters.
			$this->temp_path = $file['tmp_name'];
			$this->filename = basename($file['name']);  // basename is filename at end of path
			$this->type = $file['type'];
			$this->size = $file['size'];
			// Don't worry about saving anything to the database yet.
			return true;
		}
	}

	// this save() overwrites parent (database_object) save
	public function save() {
		// A new record won't have an id yet.
		if (isset($this->id)) {
			// Really just update caption
			$this->update();
		} else {
			// Make sure there are no errors
			
			// Can't save if ther are pre-existing errors
			if (!empty($this->errors)) { return false; }
			
			// Make sure the caption is not too long for the DB
			if (strlen($this->caption) > 255) {
				$this->errors[] = "The caption can only be 255 characters";
				return false;
			}
			
			// Can't save without filename and temp location
			if (empty($this->filename) || empty($this->temp_path)) {
				$this->errors[] = "The file location was not available";
				return false;
			}
			
			// Determine the target_path
			$target_path = SITE_ROOT .DS. 'public' .DS. $this->upload_dir .DS. $this->filename;
			
			// Make sure a file doesn't already exist in the target location
			if(file_exists($target_path)) {
				$this->errors[] = "The file {$this->filename} already exists.";
				return false;
			}
			
			// Attempt to move the file
			if(move_uploaded_file($this->temp_path, $target_path)) {
				// Success
				// Save a corresponding entry to the database
				if($this->create()) {
					// We are done with temp_path, file isn't there anymore
					unset($this->temp_path);
					return true;
				}
				
			} else {
				// Failure
				$this->errors[] = "The file upload failed, possibly incorrect permissions on upload folder.";
				return false;
			}
			
		}
	}
	
	public function image_path() {
		return $this->upload_dir.DS.$this->filename;
	}
	
	public function size_as_text() {
		if ($this->size < 1024) {
			return "{$this->size} bytes";
		} elseif($this->size < 1048576) {
			$sizeKB = round($this->size/1024);
			return "{$sizeKB} KB";
		} else {
			$sizeMB = round($this->size/1048576, 1);
			return "{$sizeMB} MB";
		}
	}
	
	public function comments() {
		return Comment::find_comments_on($this->id);
	}
}
?>