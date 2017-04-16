<?php
// Class for photo comments specifically
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
		date_default_timezone_set('US/Eastern');
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

	public function try_send_notification() { 
		$mail = new PHPMailer();		
		
		$mail->IsSMTP();
		// DEBUG ONLY comment out otherwise *************
		// $mail->SMTPDebug = 2;

		$mail->SMTPAuth = true; // ?true for tls/smtp
		$mail->SMTPSecure = 'tls';
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 587;
		$mail->Username = "mbrick02@gmail.com";
		$mail->Password = "F.##^.";
		$mail->setFrom("mbrick02@gmail.com", "Photo Gallery");
		$mail->addAddress("mbrick02@yahoo.com", "Photo Gallery Admin");
		$mail->Subject = "New Photo Gallery Comment: " . strftime("%T", time());
		$created = datetime_to_text($this->created);
		// Body (using heredoc <<<TITLE ending with TITLE--by itself on a line)
		$mail->Body = <<<EMAILBODY
A new comment  has been received in the Photo Gallery.
At {$created}, {$this->author} wrote:
<br/>
{$this->body}
EMAILBODY;
		$mail->AltBody = "plain-text comment has been received At {$created}, {$this->author} wrote: {$this->body}";
		
		$result = $mail->Send();
		return $result;
	}
}
?>