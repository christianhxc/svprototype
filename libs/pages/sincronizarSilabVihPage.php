<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
require_once ('libs/Configuration.php');

class sincronizarVihPage extends page
{
	public $config;

	function __construct($data = null)
	{
                $this->config = $data;
		parent::__construct($data);
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'vih/sincronizarSilabVih.tpl.html');

                // Muestra mensajes de error correspondientes
                $this->tpl->setVariable('mensajeError',($this->config["error"]?'':'none'));
                $this->tpl->setVariable('mensajeExito',($this->config["exito"]?'':'none'));
                                
                //if(clsCaus::validarSeccion(ConfigurationCAUS::vih, ConfigurationCAUS::vihSincronizarSilab)){
                    $this->tpl->setVariable("botonTraerDatos",'<div style="margin-top:10px"><a href="javascript:validarTraerDatos();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)" title="Traer todos los casos de VIH de SILAB">Traer datos</a></div>');
                    $this->tpl->setVariable("botonSincronizar",'<div style="margin-top:10px"><a href="javascript:validarSincronizar();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)" title="Sincronizar los casos de SILAB y SISVIG">Sincronizar</a></div>');
                    $this->tpl->setVariable("botonFactor",'<div style="margin-top:10px"><a href="javascript:validarSincronizarFactor();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)" title="Sincronizar los factores de riesgo de SILAB en SISVIG">Factores de Riesgo</a></div>');
                

//                require_once ('libs/caus/clsCaus.php');
//                if(clsCaus::validarSeccion(ConfigurationCAUS::r4, ConfigurationCAUS::Reportes))
                    $this->tpl->setVariable("botonGenerar",'<div style="margin-top:10px"><a href="javascript:validarReporte();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)" title="Por favor considere que el reporte puede tardarse">Generar</a></div>');
		$this->tpl->parse('contentBlock');
	}
}
?>