<?php

require_once('Mail.php');
require_once('Mail/mime.php');
require_once('Net/SMTP.php');

class sendMail {

	static function sendContactUs($nmail) {
		$nmail['to'] = Configuration::emailTo;
		$nmail['subject'] = Configuration::contactSubject;
		$nmail['subject'] = Configuration::contactSubject;
		$nmail["format"] = "text/html"; 
		
		$body = '
			<b>Name:</b> '.$nmail['name'].'<br>
			<b>e-mail:</b> '.$nmail['from'].'<br>
			<b>Message:</b> '.$nmail['body'].'<br>
		';
		
		$nmail['body'] = $body;
		$result = self::send($nmail);
		return $result;
	}

	// $priority # 1 High, 3 Normal 
	private function send($nmail, $priority = '3') {
		
		$headers = array(
			"To" => $nmail['to'],
			"Bcc" => $nmail['bcc'],
			"cc" => $nmail['cc'],
			"From" => $nmail['from'],
			"Subject" => $nmail['subject'],
			"Return-Path" => $nmail['from'],
			"Content-Type" => $nmail['format'],
			"X-Priority" => $priority
		);
		
		$params['sendmail_path'] = '/usr/lib/sendmail';
		
		$mail =& Mail::factory('sendmail', $params);	
		$mail->send($nmail['to'],$headers,$nmail['body']);

		if (PEAR::isError($mail))
			return array('code' => -1, 'message' => 'The was an error, try contact us later.');
		else
			return array('code' => 1, 'message' => "Thanks for contacting us, we'll contact you soon as possible.");
	}
	
	static function checkEmail($email) {
		if (!preg_match("/^([a-zA-Z0-9])+([\.a-zA-Z0-9_-])*@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-]+)*\.([a-zA-Z]{2,6})$/", $email)) {
			return false;
		}else {
			return true;
		}
	}
}
?>