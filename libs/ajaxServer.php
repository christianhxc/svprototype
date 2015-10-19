<?php
require_once "HTML/AJAX/Server.php";

class AutoServer extends HTML_AJAX_Server{
	var $initMethods = true;
	
	function initHandleMails()
	{
		require_once "interface/handleMails.php";		
		$obj = new handleMails();
		$this->registerClass($obj);
	}
	
	function initHandleRating()
	{
		require_once "interface/handleRating.php";
		$obj = new handleRating();
		$this->registerClass($obj);
	}
	
	function initHandleActions()
	{
		require_once "interface/handleActions.php";
		$obj = new handleActions();
		$this->registerClass($obj);
	}
	
	
}
	$objServer = new AutoServer();
	$objServer->handleRequest();

?>