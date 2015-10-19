<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

class recibirInformeDerivacionesPage extends page
{
	public $config;

	function __construct($data = null)
	{
                $this->config = $data;
		parent::__construct($data);
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'analista/recibirInformeDerivaciones.tpl.html');

                // Muestra mensajes de error correspondientes
                $this->tpl->setVariable('mensajeError',($this->config["error"]?'':'none'));
                $this->tpl->setVariable('mensajeExito',($this->config["exito"]?'':'none'));

                $this->tpl->setVariable('mostrarExito',($this->config["exito"]?'':'none'));
                $this->tpl->setVariable('mostrarError',($this->config["error"]?'':'none'));

                $this->tpl->setVariable('valError',($this->config["error"]?$this->config["mensaje"]:''));
                $this->tpl->setVariable('valExito',($this->config["exito"]?$this->config["mensaje"]:''));
                

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

                $this->tpl->setVariable("gd",$this->config["search"]["idd"]);
                $this->tpl->setVariable("gh",$this->config["search"]["idh"]);

                $entradas = $this->config["entradas"];

		if(is_array($entradas))
                {
			foreach($entradas as $entrada)
                        {
                            $this->tpl->setCurrentBlock('blkEntradas');

                                $this->tpl->setVariable('id',$entrada["id"]);
                                $this->tpl->setVariable('area', htmlentities($entrada["ARE_ANA_NOMBRE"]));
                                $this->tpl->setVariable('evento',htmlentities($entrada["EVE_NOMBRE"]));

                                $this->tpl->touchBlock("blkHeaderRecibirTodos");
                                $this->tpl->setCurrentBlock("blkRecibirTodas");
                                    if($entrada["INF_ENV_RESTANTES"]>0)
                                        $this->tpl->setVariable('linkRecibirTodas','<a href="javascript:recibirTodas('.$entrada["id"].')" onmouseout="RollOut(this)" onmouseover="RollOver(this)" class="ui-state-default ui-corner-all ui-link-button" title="Recibir todas las derivaciones de este informe">
                                        <span class="ui-icon ui-icon-check"></span></a>');
                                    else
                                        $this->tpl->setVariable('linkRecibirTodas','&nbsp;');
                                $this->tpl->parse("blkRecibirTodas");


//                                $this->tpl->touchBlock("blkHeaderSelectivo");
//                                    $this->tpl->setCurrentBlock("blkRecibirSelectivo");
//                                    if($entrada["INF_ENV_RESTANTES"]>0)
//                                        $this->tpl->setVariable('linkRecibirSelectivo','<a href="javascript:recibirSelectivo('.$entrada["id"].')" onmouseout="RollOut(this)" onmouseover="RollOver(this)" class="ui-state-default ui-corner-all ui-link-button" title="Recepci&oacute;n selectiva de muestras">
//                                            <span class="ui-icon ui-icon-transferthick-e-w"></span></a>');
//                                    else
//                                        $this->tpl->setVariable('linkRecibirSelectivo','&nbsp;');
//                                $this->tpl->parse("blkRecibirSelectivo");

                                
                            $this->tpl->parse('blkEntradas');
			}
		}
                else
                    $this->tpl->touchBlock('blkNoEntradas');

                $this->tpl->setGlobalVariable('prinurl',TemplateHelp::getSearchParameters($this->config['path'].'.php?',$this->config['search']));
                $this->tpl = TemplateHelp::getPaginator($this->tpl, $this->config);

                require_once ('libs/caus/clsCaus.php');
                if(clsCaus::validarSeccion(ConfigurationCAUS::a2, ConfigurationCAUS::Consultar))
                {
                    $this->tpl->setVariable("botonBuscar",'<div style="margin-top:10px"><a href="javascript:buscarInforme();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)"><span class="ui-icon ui-icon-search"></span> Buscar</a></div>');
                    $this->tpl->setVariable("mostrarTablaSearch",'');
                }
                else
                {
                    $this->tpl->setVariable("botonBuscar",'&nbsp;');
                    $this->tpl->setVariable("mostrarTablaSearch",'none');
                }


		$this->tpl->parse('contentBlock');
	}
}
?>
