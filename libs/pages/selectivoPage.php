<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

class SelectivoPage extends page
{
	public $config;

	function __construct($data = null)
	{
                $this->config = $data;
		parent::__construct($data);
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'analista/selectivo.tpl.html');

                // Muestra mensajes de error correspondientes
                $this->tpl->setVariable('mensajeError',($this->config["error"]?'':'none'));
                $this->tpl->setVariable('mensajeExito',($this->config["exito"]?'':'none'));

                $this->tpl->setVariable('mostrarExito',($this->config["exito"]?'':'none'));
                $this->tpl->setVariable('mostrarError',($this->config["error"]?'':'none'));

                $this->tpl->setVariable('valError',($this->config["error"]?$this->config["mensaje"]:''));
                $this->tpl->setVariable('valExito',($this->config["exito"]?$this->config["mensaje"]:''));

                $this->tpl->setVariable('informe',$this->config["datosMuestra"][0]["numero"]);
                $this->tpl->setVariable('area',htmlentities($this->config["datosMuestra"][0]["area"]));
                $this->tpl->setVariable('generador',htmlentities($this->config["datosMuestra"][0]["quien"]));
                $this->tpl->setVariable('fecha',$this->config["datosMuestra"][0]["fecha"]);

		$this->tpl->parse('contentBlock');
	}
}
?>
