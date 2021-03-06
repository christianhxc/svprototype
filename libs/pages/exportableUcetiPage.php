<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
require_once ('libs/Configuration.php');

class exportableUcetiPage extends page
{
	public $config;

	function __construct($data = null)
	{
                $this->config = $data;
		parent::__construct($data);
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'uceti/exportableUceti.tpl.html');

                // Muestra mensajes de error correspondientes
                $this->tpl->setVariable('mensajeError',($this->config["error"]?'':'none'));
                $this->tpl->setVariable('mensajeExito',($this->config["exito"]?'':'none'));

                
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

                // Valores de búsqueda almacenados  
                
                switch($this->config["search"]["nivel"]){
                    case '1': $this->tpl->setVariable("selNivel1",'selected'); break;
                    case '2': $this->tpl->setVariable("selNivel2",'selected'); break;
                    case '3': $this->tpl->setVariable("selNivel3",'selected'); break;
                    case '4': $this->tpl->setVariable("selNivel4",'selected'); break;
                    case '5': $this->tpl->setVariable("selNivel5",'selected'); break;
                    case '6': $this->tpl->setVariable("selNivel6",'selected'); break;
                }
                
                switch($this->config["search"]["caso"]){
                    case '1': $this->tpl->setVariable("selCaso1",'selected'); break;
                    case '2': $this->tpl->setVariable("selCaso2",'selected'); break;
                }
                
                switch($this->config["search"]["condicion"]){
                    case '1': $this->tpl->setVariable("selCondicion1",'selected'); break;
                    case '2': $this->tpl->setVariable("selCondicion2",'selected'); break;
                    case '3': $this->tpl->setVariable("selCondicion3",'selected'); break;
                }

                // Carga catálogos de areas para muestra
                if (isset($this->config["search"]["provincia"])) {
                    $regiones = helperLugar::getRegionSaludPersona($this->config["search"]["provincia"]);
                    if (is_array($regiones)) {
                        foreach ($regiones as $region) {
                            $this->tpl->setCurrentBlock('blkReg');
                            $this->tpl->setVariable("valReg", $region["codigoRegion"]);
                            $this->tpl->setVariable("opcReg", htmlentities($region["nombreRegion"]));

                            if ($this->config["preselect"])
                                $this->tpl->setVariable("selReg", ($region["codigoRegion"] == $this->config["search"]["region"]  ? 'selected="selected"' : ''));

                            $this->tpl->parse('blkReg');
                        }
                    }
                }
                if (isset($this->config["search"]["region"])) {
                    $distritos = helperLugar::getDistritoSaludPersona($this->config["search"]["provincia"], $this->config["search"]["region"]);
                    if (is_array($distritos)) {
                        foreach ($distritos as $distrito) {
                            $this->tpl->setCurrentBlock('blkDis');
                            $this->tpl->setVariable("valDis", $distrito["codigoDistrito"]);
                            $this->tpl->setVariable("opcDis", htmlentities($distrito["nombreDistrito"]));

                            if ($this->config["preselect"])
                                $this->tpl->setVariable("selDis", ($distrito["codigoDistrito"] == $this->config["search"]["distrito"] ? 'selected="selected"' : ''));

                            $this->tpl->parse('blkDis');
                        }
                    }
                }
                if (isset($this->config["search"]["distrito"])) {
                    $corregimientos = helperLugar::getCorregimientoSaludPersona($this->config["search"]["distrito"]);
                    if (is_array($corregimientos)) {
                        foreach ($corregimientos as $corregimiento) {
                            $this->tpl->setCurrentBlock('blkCor');
                            $this->tpl->setVariable("valCor", $corregimiento["codigoCorregimiento"]);
                            $this->tpl->setVariable("opcCor", htmlentities($corregimiento["nombreCorregimiento"]));

                            if ($this->config["preselect"])
                                $this->tpl->setVariable("selCor", ($corregimiento["codigoCorregimiento"] == $this->config["search"]["corregimiento"] ? 'selected="selected"' : ''));

                            $this->tpl->parse('blkCor');
                        }
                    }
                }

//                require_once ('libs/caus/clsCaus.php');
//                if(clsCaus::validarSeccion(ConfigurationCAUS::r4, ConfigurationCAUS::Reportes))
                    $this->tpl->setVariable("botonGenerar",'<div style="margin-top:10px"><a href="javascript:validarReporte();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)" title="Por favor considere que el reporte puede tardarse">Generar</a></div>');
		$this->tpl->parse('contentBlock');
	}
}
?>