<?php
require_once (LIB_PATH.DS.'database.php');
// *** also requires PHPMailer which should be included in initialize

class Emailer extends DatabaseObject { // ****UNTESTED BASED ON comment.php
	public function try_send_notification() {
		$mail = new PHPMailer();
	
		$mail->IsSMTP();
		// DEBUG ONLY comment out otherwise *************
		// $mail->SMTPDebug = 2;
	
		$mail->SMTPAuth = true; // ?true for tls/smtp
		$mail->SMTPSecure = 'tls';
		$mail->Host = "smtp.gmail.com";  // can also use mail.lumos.net:25
		/*
		// lumos settings * USE if you don't use gmail
$mail->Host = "mail.lumos.net"; // lumos doesnt seem to work ->IsSMTP
$mail->Port = 25; // ?think this is just default mail -- NO IsSMTP
$mail->Username = "mbrick02";
$mail->Password = "jlessshort8inshort";
$mail->SetFrom($from, $fromName);
/// lumos above ****

		 */
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