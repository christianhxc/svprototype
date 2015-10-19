<?php
require_once ('libs/pages/page.php');
require_once ('libs/Etiquetas.php');

class enfermedadCronicaPage extends page
{
	public $config;

	function __construct($data = null)
	{
            $this->config = $data;
            parent::__construct($data);
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'/catalogos/enfermedadCronica.tpl.html');
                
                $this->tpl->setVariable("disError", $this->config["error"] != "" ? '' : 'none');
                $this->tpl->setVariable("desError", $this->config["error"]);

                $this->tpl->setVariable("disInfo", $this->config["info"] != "" ? '' : 'none');
                $this->tpl->setVariable("desInfo", $this->config["info"]);

                $this->tpl->setVariable("legEnfermedadesCronicas", Etiquetas::legEnfermedadesCronicas);
                
	}
}

?>