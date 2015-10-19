<?php
require_once('Connection.php');
require_once('conf.php');
require_once('Mail.php');
require_once('Help.php');

class sendMails{
	private $data;
	private $mail;
	
	function __construct($info)
	{
		$this->data = $info;		
		$this->mail =& Mail::factory('sendmail');
	}
	
	public function sendFriend(){
		$tpl = Help::getEmailTemplate(Conf::enviarAmigo);
		$headers['From'] =  $tpl['from'];
		$headers['Subject'] = str_replace('{name}',$this->data['name'],$tpl['subject']);
		$headers["Content-Type"] = "text/html"; 
		
		$body = $tpl['body'];
		$body = str_replace('{name}',$this->data['name'],$body);
		$body = str_replace('{email}',$this->data['email'],$body);
		$body = str_replace('{comment}',$this->data['comment'],$body);
		$body = str_replace('{codigo}',$this->data['codigo'],$body);
		
		$listto = explode(',',$this->data['to']);
		if (is_array($listto)){
			foreach ($listto as $to){
				if (trim($to)!=''){
					$headers['To'] = trim($to);
					$this->mail->send($to,$headers,$body);
				}
			}
		}
		return $this->mail;
	}
}
?>