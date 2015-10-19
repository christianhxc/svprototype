<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

class consolidadosPage extends page
{
	public $config;

	function __construct($data = null)
	{
                $this->config = $data;
		parent::__construct($data);
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'reportes/consolidados.tpl.html');

                // Muestra mensajes de error correspondientes
                $this->tpl->setVariable('mensajeError','none');
                $this->tpl->setVariable('mostrarError','none');

                // Cargar áreas de análisis
                $areas = $this->config["catalogos"]["area_analisis"];
                if(is_array($areas))
                {                            
                        foreach($areas as $area)
                        {
                            $this->tpl->setCurrentBlock('blkAreas');
                            $this->tpl->setVariable("valAreas",$area["ARE_ANA_ID"]);
                            $this->tpl->setVariable("opcAreas",htmlentities($area["ARE_ANA_NOMBRE"]));

                            // Selecciona el área guardada
                            $this->tpl->setVariable("selAreas",($area["ARE_ANA_ID"]==$this->config["search"]["area"]?'selected="selected"':''));
                            $this->tpl->parse('blkAreas');
                        }
                }

                require_once ('libs/caus/clsCaus.php');
                if(clsCaus::validarSeccion(ConfigurationCAUS::r1, ConfigurationCAUS::Reportes))
                    $this->tpl->setVariable("botonGenerar",'<div style="margin-top:10px"><a href="javascript:validar();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)"><span class="ui-icon ui-icon-document-b"></span> Generar Reporte </a></div>');                

		$this->tpl->parse('contentBlock');
	}
}
?>
