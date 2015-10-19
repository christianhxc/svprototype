<?php
require_once ('page.php');

class loginPage extends page
{
	public $config;

	function __construct($data = null)
	{
                $this->config = $data;
		parent::__construct($data);
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'login.tpl.html');
                $error = $this->config["error"]["mensaje"];

                if($error=="")
                    $this->tpl->setVariable("valHide",'display:none');
                else
                    $this->tpl->setVariable("valHide",'');
                $this->tpl->setVariable("errorIngreso",$error);
                $this->tpl->setVariable("valNombreUsuario",'');
                $this->tpl->setVariable("valPassword",'');
                $this->tpl->setVariable("action",'i');                
		$this->tpl->parse('contentBlock');
	}
}

?>