<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

class envioMuestraPage extends page
{
	public $config;

	function __construct($data = null)
	{
                $this->config = $data;
		parent::__construct($data);
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'ventanilla/envioMuestra.tpl.html');

                // Muestra mensajes de error correspondientes
                $this->tpl->setVariable('mensajeError',($this->config["error"]?'':'none'));
                $this->tpl->setVariable('mensajeExito',($this->config["exito"]?'':'none'));

                $this->tpl->setVariable('mostrarExito',($this->config["exito"]?'':'none'));
                $this->tpl->setVariable('mostrarError',($this->config["error"]?'':'none'));
//
//                $this->tpl->setVariable('valError',($this->config["error"]?$this->config["mensaje"]:''));
//                $this->tpl->setVariable('valExito',($this->config["exito"]?$this->config["mensaje"]:''));
//

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

                // Carga evento y seleccionados
                if($this->config["search"]["area"]!=0)
                {
                    $eventos = helperCatalogos::getEventos($this->config["search"]["area"]);

                    if(is_array($eventos))
                    {
                        foreach($eventos as $evento)
                        {
                            $this->tpl->setCurrentBlock('blkEventos');
                            $this->tpl->setVariable("valEventos",$evento["eve_id"]);
                            $this->tpl->setVariable("opcEventos",htmlentities($evento["eve_nombre"]));

                            // Selecciona el área guardada
                            $this->tpl->setVariable("selEventos",($evento["eve_id"]==$this->config["search"]["evento"]?'selected="selected"':''));
                            $this->tpl->parse('blkEventos');
                        }
                    }
                }

                // Valores de búsqueda almacenados
                $this->tpl->setVariable("n1",htmlentities($this->config["search"]["primer_nombre"].' '.$this->config["search"]["segundo_nombre"]));
                //$this->tpl->setVariable("n2",htmlentities($this->config["search"]["segundo_nombre"]));
                $this->tpl->setVariable("a1",htmlentities($this->config["search"]["primer_apellido"].' '.$this->config["search"]["segundo_apellido"]));
                //$this->tpl->setVariable("a2",htmlentities($this->config["search"]["segundo_apellido"]));
                $this->tpl->setVariable("id",htmlentities($this->config["search"]["identificador"]));
                $this->tpl->setVariable("hc",htmlentities($this->config["search"]["historia_clinica"]));

                $this->tpl->setVariable("t1",($this->config["search"]["rechazada"]=='2'?'selected':''));
                $this->tpl->setVariable("t2",($this->config["search"]["rechazada"]=='1'?'selected':''));
                $this->tpl->setVariable("t3",($this->config["search"]["rechazada"]=='0'?'selected':''));

                $this->tpl->setVariable("gd",$this->config["search"]["global_desde"]);
                $this->tpl->setVariable("gh",$this->config["search"]["global_hasta"]);
                $this->tpl->setVariable("cd",$this->config["search"]["correlativo_desde"]);
                $this->tpl->setVariable("ch",$this->config["search"]["correlativo_hasta"]);
                $this->tpl->setVariable("td",$this->config["search"]["toma_desde"]);
                $this->tpl->setVariable("th",$this->config["search"]["toma_hasta"]);
                $this->tpl->setVariable("rd",$this->config["search"]["recepcion_desde"]);
                $this->tpl->setVariable("rh",$this->config["search"]["recepcion_hasta"]);

                require_once ('libs/caus/clsCaus.php');
                if(clsCaus::validarSeccion(ConfigurationCAUS::v4, ConfigurationCAUS::Consultar))
                    $this->tpl->setVariable("botonBuscar",'<div style="margin-top:10px"><a href="javascript:buscarMuestras();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)"><span class="ui-icon ui-icon-search"></span> Buscar</a></div>');
		$this->tpl->parse('contentBlock');
	}
}
?>
