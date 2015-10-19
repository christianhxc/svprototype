<?php
require_once ('libs/pages/page.php');

class cambiarClave extends page
{
	public $config;

	function __construct($data = null)
	{
            $this->config = $data;
            parent::__construct($data);
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'cambiarClave.tpl.html');
                $this->tpl->setVariable("disError", $this->config["error"] != "" ? '' : 'none');
                $this->tpl->setVariable("desError", $this->config["error"]);

                $this->tpl->setVariable("valUsername", $this->config["username"]);
		$this->tpl->parse('contentBlock');
	}
}

?>