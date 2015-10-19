<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

class pagDenominadoresHome extends page
{
	public $config;

	function __construct($data = null)
	{
		$this->config = $data;
		parent::__construct($data);
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'denominadores/denominadoresHome.tpl.html');
		
		// ### Mostrar mensajes de notifiacion al usuario ###
		$this->tpl->setVariable("disInfo", $this->config["result"] > 0 ? '' : 'none');
		$this->tpl->setVariable("disError", $this->config["result"] < 0 ? '' : 'none');
		if ($this->config["result"] == 1){ // Datos guardados exitosamente
			$this->tpl->setVariable("desInfo", "Los datos han sido guardados con exito");
		}
		if ($this->config["result"] == 2){ // Borrado exitoso
			$this->tpl->setVariable("desInfo", "Los datos han sido borrados con exito");
		}
		
		// Mostrar las entradas
		$entradas = $this->config["entradas"];
		if(is_array($entradas)){
			foreach($entradas as $entrada){
				$this->tpl->setCurrentBlock('blkEntradas');
				$this->tpl->setVariable($entrada);

                // Mostrar o esconder las acciones que el usuario puede realizar
                $entrada["acciones"] = $this->config["acciones"];
                $this->tpl = TemplateHelp::showActionButtons($this->tpl, $entrada);

				$this->tpl->parse('blkEntradas');
			}
		}else{
			$this->tpl->touchBlock('blkNoEntradas');
		}
		
		$this->tpl->setGlobalVariable('prinurl',TemplateHelp::getSearchParameters($this->config['path'].'.php?',$this->config['search']));
		$this->tpl = TemplateHelp::getPaginator($this->tpl,$this->config);

        // Mostrar o esconder las acciones que el usuario puede realizar
        $this->tpl = TemplateHelp::showButtons($this->tpl,$this->config);
		
		// Mostrar los demas datos del formulario
		$this->tpl->setVariable($this->config["search"]);
		$this->tpl->parse('contentBlock');
	}
}