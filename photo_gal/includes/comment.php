<?php
// needs database.php
require_once (LIB_PATH.DS.'database.php');

class Comment extends DatabaseObject {
	protected static $tbName="comments";
	protected static $db_fields = array('id', 'photo_id', 'created', 'author', 'body');
	
	public $id;
	public $photo_id;
	public $created;
	public $author;
	public $body;
	
	public static function make($photo_id, $author="Anonymous", $body) {
		if(!empty($photo_id) && !empty($author) && !empty($body)) {
			$comment = new Comment();
			$comment->photo_id = (int)$photo_id;
			$comment->created = strftime("%Y-%m-%d %H:%M:%S", time());
			$comment->author = $author;
			$comment->body = $body;
			return $comment;
		} else {
			return false;
		}

	}
	
	public static function find_comments_on($photo_id=0) {
		global $db;
		
		$sql_to_prep = "SELECT * FROM ". static::$tbName . " WHERE photo_id=:photo_id";
		$sql_to_prep .= " ORDER BY created ASC";
		$res_ary = static::find_by_sql($sql_to_prep, array(":photo_id" => "{$photo_id}"));
		
		return $res_ary;
	}
}
?>