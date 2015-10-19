<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
require_once ('libs/Configuration.php');

class factorRiesgoVihPage extends page
{
	public $config;

	function __construct($data = null)
	{
                $this->config = $data;
		parent::__construct($data);
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'vih/factorRiesgoVih.tpl.html');

                // Muestra mensajes de error correspondientes
                $this->tpl->setVariable('mensajeError',($this->config["error"]?'':'none'));
                $this->tpl->setVariable('mensajeExito',($this->config["exito"]?'':'none'));

                switch($this->config["search"]["caso"]){
                    case '1': $this->tpl->setVariable("selCaso1",'selected'); break;
                    case '2': $this->tpl->setVariable("selCaso2",'selected'); break;
                }
                
                switch($this->config["search"]["condicion"]){
                    case '1': $this->tpl->setVariable("selCondicion1",'selected'); break;
                    case '2': $this->tpl->setVariable("selCondicion2",'selected'); break;
                    case '3': $this->tpl->setVariable("selCondicion3",'selected'); break;
                }
                
                $provincias = $this->config["catalogos"]["provincias"];
                if (is_array($provincias)) {
                    foreach ($provincias as $provincia) {
                        $this->tpl->setCurrentBlock('blkPro');
                        $this->tpl->setVariable("valPro", $provincia["provincia"]);
                        $this->tpl->setVariable("opcPro", htmlentities($provincia["descripcionProvincia"]));

                        if ($this->config["preselect"])
                            $this->tpl->setVariable("selPro", ($provincia["id_provincia"] == $this->config["search"]["provincia"] ? 'selected="selected"' : ''));

                        $this->tpl->parse('blkPro');
                    }
                }
                
                

//                require_once ('libs/caus/clsCaus.php');
//                if(clsCaus::validarSeccion(ConfigurationCAUS::r4, ConfigurationCAUS::Reportes))
                    $this->tpl->setVariable("botonGenerar",'<div style="margin-top:10px"><a href="javascript:validarReporte();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)" title="Por favor considere que el reporte puede tardarse">Generar</a></div>');
		$this->tpl->parse('contentBlock');
	}
}
?>