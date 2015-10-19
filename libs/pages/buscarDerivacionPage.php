<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
require_once ('libs/Configuration.php');
require_once ('libs/caus/clsCaus.php');

class buscarDerivacionPage extends page
{
	public $config;

	function __construct($data = null)
	{
                $this->config = $data;
		parent::__construct($data);
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'analista/buscarDerivacion.tpl.html');

                // Muestra mensajes de error correspondientes
                $this->tpl->setVariable('mensajeError',($this->config["error"]?'':'none'));
                $this->tpl->setVariable('mensajeExito',($this->config["exito"]?'':'none'));

                switch($this->config["selectMensaje"])
                {
                    case '1':
                        $data = helperMuestra::getCodigosDerivacion($this->config["muestra"]);
                        $this->tpl->setVariable('valExito','&#161;Prueba(s) editadas(s) correctamente&#33; C&Oacute;DIGO GLOBAL: <strong>'.$data[0]["MUE_CODIGO_GLOBAL_ANIO"].
                                ' - '. helperString::completeZeros($data[0]["MUE_CODIGO_GLOBAL_NUMERO"]).'</strong> C&Oacute;DIGO CORRELATIVO: <strong>'
                                .$data[0]["MUE_CODIGO_CORRELATIVO_ALFA"].' - '.helperString::completeZeros($data[0]["MUE_CODIGO_CORRELATIVO_NUMERO"]).'</strong>');
                    break;
                    case '2':
                        $data = helperMuestra::getCodigos($this->config["muestra"]);
                        $this->tpl->setVariable('valExito','&#161;Muestra editada correctamente&#33; C&Oacute;DIGO GLOBAL: <strong>'.$data[0]["mue_codigo_global_anio"].
                                ' - '. helperString::completeZeros($data[0]["mue_codigo_global_numero"]).'</strong> C&Oacute;DIGO CORRELATIVO: <strong>'
                                .$data[0]["mue_codigo_correlativo_alfa"].' - '.helperString::completeZeros($data[0]["mue_codigo_correlativo_numero"]).'</strong>');
                    break;
                    case '3':
                        $data = helperMuestra::getCodigosDerivacion($this->config["muestra"]);
                        $this->tpl->setVariable('valExito','La derivaci&oacute;n con C&Oacute;DIGO GLOBAL: <strong>'.$data[0]["MUE_CODIGO_GLOBAL_ANIO"].
                                ' - '. helperString::completeZeros($data[0]["MUE_CODIGO_GLOBAL_NUMERO"]).'</strong> C&Oacute;DIGO CORRELATIVO: <strong>'
                                .$data[0]["MUE_CODIGO_CORRELATIVO_ALFA"].' - '.helperString::completeZeros($data[0]["MUE_CODIGO_CORRELATIVO_NUMERO"]).'</strong>
                                    se anul&oacute; correctamente');
                    break;
                    case '4':
                        $this->tpl->setVariable('valError','&#161;Imposible anular derivaci&oacute;n, ya posee pruebas asignadas y/o su an&aacute;lisis ha finalizado&#33;');
                    break;
                    case '5':
                        $this->tpl->setVariable('valError','&#161;Imposible editar, muestra no existente&#33;');
                    break;
                    case '6':
                        $this->tpl->setVariable('valError','&#161;Imposible agregar pruebas, derivaci&oacute;n no existente&#33;');
                        break;
                    case '7':
                        $this->tpl->setVariable('valError','&#161;Derivaci&oacute;n  no encontrada&#33;');
                        break;
                    case '8':
                        $this->tpl->setVariable('valExito','&#161;An&aacute;lisis de derivaci&oacute;n revertido correctamente&#33;');
                        break;
                    case '9':
                        $this->tpl->setVariable('valError','&#161;Imposible revertir an&aacute;lisis de derivaci&oacute;n, por favor intente nuevamente&#33;');
                        break;
                }

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
                $this->tpl->setVariable("t1",($this->config["search"]["rechazada"]=='2'?'selected':''));
                $this->tpl->setVariable("t2",($this->config["search"]["rechazada"]=='1'?'selected':''));
                $this->tpl->setVariable("t3",($this->config["search"]["rechazada"]=='0'?'selected':''));

                $this->tpl->setVariable("gd",$this->config["search"]["global_desde"]);
                $this->tpl->setVariable("gh",$this->config["search"]["global_hasta"]);
                $this->tpl->setVariable("cd",$this->config["search"]["correlativo_desde"]);
                $this->tpl->setVariable("ch",$this->config["search"]["correlativo_hasta"]);
                $this->tpl->setVariable("dd",$this->config["search"]["derivacion_desde"]);
                $this->tpl->setVariable("dh",$this->config["search"]["derivacion_hasta"]);

                $puedeRevertir = clsCaus::validarSeccion(ConfigurationCAUS::a4, ConfigurationCAUS::Habilitar);
                if($puedeRevertir)
                    $this->tpl->setVariable("colspanTabla",'8');
                else
                    $this->tpl->setVariable("colspanTabla",'7');


		// Mostrar las entradas del grid
		$entradas = $this->config["entradas"];

		if(is_array($entradas))
                {
			foreach($entradas as $entrada)
                        {
                            $this->tpl->setCurrentBlock('blkEntradas');                            

                                if($puedeRevertir)
                                {
                                    $this->tpl->touchBlock("blkHeaderRevertir");
                                    $this->tpl->setCurrentBlock("blkOpcionRevertir");
                                    if($entrada["ubicacion"] == Configuration::analisisFinalizadoDer)
                                    {
                                        $m = $entrada["MUE_ID"];
                                        $this->tpl->setVariable('linkRevertir','<a href="buscarDerivacion.php?action=R&m='.$entrada["MUE_ID"].'&t=2" onmouseout="RollOut(this)" onmouseover="RollOver(this)" class="ui-state-default ui-corner-all ui-link-button" title="Revertir conclusi&oacute;n" onclick="return confirm(\'\xbfEst\xe1 seguro que desea revertir el fin de an\xe1lisis de esta derivaci\xf3n?\');"> <span class="ui-icon ui-icon-alert"></span></a>');
                                    }
                                    else
                                        $this->tpl->setVariable('linkRevertir','&nbsp;');
                                    $this->tpl->parse("blkOpcionRevertir");
                                }


                                $this->tpl->touchBlock("blkHeaderPruebas");
                                $this->tpl->touchBlock("blkHeaderBorrar");
                                
                                $this->tpl->setCurrentBlock("blkOpcionPruebas");
                                    $this->tpl->setVariable('id',$entrada["MUE_ID"]);
                                    if(!clsCaus::validarSeccion(ConfigurationCAUS::a4, ConfigurationCAUS::Modificar))
                                        $this->tpl->setVariable('pruebasMostrarLink','none');
                                    else
                                        $this->tpl->setVariable('pruebasMostrarLink','');
                                $this->tpl->parse("blkOpcionPruebas");
                                
                                $this->tpl->setCurrentBlock("blkOpcionBorrar");
                                    $this->tpl->setVariable('id',$entrada["MUE_ID"]);
                                    if(!clsCaus::validarSeccion(ConfigurationCAUS::a4, ConfigurationCAUS::Borrar))
                                        $this->tpl->setVariable('anularMostrarLink','none');
                                    else
                                        $this->tpl->setVariable('anularMostrarLink','');
                                $this->tpl->parse("blkOpcionBorrar");

                                $this->tpl->setVariable('global',$entrada["global"].' - '. helperString::completeZeros($entrada["gnumero"]));
                                $this->tpl->setVariable('correlativo',$entrada["correlativo"].' - '. helperString::completeZeros($entrada["cnumero"]));
                                $this->tpl->setVariable('area', htmlentities($entrada["ARE_ANA_NOMBRE"]));
                                $this->tpl->setVariable('evento', htmlentities($entrada["EVE_NOMBRE"]));                               

                                if($entrada["ubicacion"]==Configuration::enAnalisisDer)
                                    $this->tpl->setVariable('estado', "En An&aacute;lisis");
                                else if($entrada["ubicacion"]==Configuration::analisisFinalizadoDer)
                                    $this->tpl->setVariable('estado', "An&aacute;lisis Finalizado");

                            $this->tpl->parse('blkEntradas');
			}
		}
                else
                    $this->tpl->touchBlock('blkNoEntradas');
                
                $this->tpl->setGlobalVariable('prinurl',TemplateHelp::getSearchParameters($this->config['path'].'.php?',$this->config['search']));
                $this->tpl = TemplateHelp::getPaginator($this->tpl, $this->config);

                require_once ('libs/caus/clsCaus.php');
                if(clsCaus::validarSeccion(ConfigurationCAUS::a4, ConfigurationCAUS::Consultar)){
                    $this->tpl->setVariable("botonBuscar",'<div style="margin-top:10px"><a href="javascript:buscarMuestra();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)"><span class="ui-icon ui-icon-search"></span> Buscar</a></div>');
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