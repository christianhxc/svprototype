<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
require_once ('libs/Configuration.php');

class area_eventoPage extends page
{
	public $config;

	function __construct($data = null)
	{
                $this->config = $data;
		parent::__construct($data);
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'reportes/area_evento.tpl.html');

                // Muestra mensajes de error correspondientes
                $this->tpl->setVariable('mensajeError',($this->config["error"]?'':'none'));
                $this->tpl->setVariable('mensajeExito',($this->config["exito"]?'':'none'));

                switch($this->config["selectMensaje"])
                {
                    case '1':
                        $data = helperMuestra::getCodigos($this->config["muestra"]);
                        $this->tpl->setVariable('valExito','&#161;Muestra agregada correctamente&#33; C&Oacute;DIGO GLOBAL: <strong>'.$data[0]["mue_codigo_global_anio"].
                                ' - '. helperString::completeZeros($data[0]["mue_codigo_global_numero"]).'</strong> C&Oacute;DIGO CORRELATIVO: <strong>'
                                .$data[0]["mue_codigo_correlativo_alfa"].' - '.helperString::completeZeros($data[0]["mue_codigo_correlativo_numero"]).'</strong>');
                    break;
                    case '2':
                        $data = helperMuestra::getCodigos($this->config["muestra"]);
                        $this->tpl->setVariable('valExito','&#161;Muestra editada correctamente&#33; C&Oacute;DIGO GLOBAL: <strong>'.$data[0]["mue_codigo_global_anio"].
                                ' - '. helperString::completeZeros($data[0]["mue_codigo_global_numero"]).'</strong> C&Oacute;DIGO CORRELATIVO: <strong>'
                                .$data[0]["mue_codigo_correlativo_alfa"].' - '.helperString::completeZeros($data[0]["mue_codigo_correlativo_numero"]).'</strong>');
                    break;
                    case '3':
                        $data = helperMuestra::getCodigos($this->config["muestra"]);
                        $this->tpl->setVariable('valExito','La muestra con C&Oacute;DIGO GLOBAL: <strong>'.$data[0]["mue_codigo_global_anio"].
                                ' - '. helperString::completeZeros($data[0]["mue_codigo_global_numero"]).'</strong> C&Oacute;DIGO CORRELATIVO: <strong>'
                                .$data[0]["mue_codigo_correlativo_alfa"].' - '.helperString::completeZeros($data[0]["mue_codigo_correlativo_numero"]).'</strong>
                                    se anul&oacute; correctamente');
                        //$this->tpl->setVariable('valExito','&#161;Muestra anulada correctamente&#33;');
                    break;
                    case '4':
                        $this->tpl->setVariable('valError','&#161;Imposible anular la muestra, ya posee pruebas asignadas&#33;');
                    break;
                    case '5':
                        $this->tpl->setVariable('valError','&#161;Imposible editar, muestra no existente&#33;');
                    break;
                    // Despliega los códigos de la muestra y sus alicuotas
                    case '6':
                        $ids = $this->config["muestras"];
                        $ids = explode('-',$ids);

                        // Muestra original
                        $data = helperMuestra::getCodigos($ids[0]);
                        $informacion = '&#161;Muestra agregada correctamente&#33; C&Oacute;DIGO GLOBAL: <strong>'.$data[0]["mue_codigo_global_anio"].
                                ' - '. helperString::completeZeros($data[0]["mue_codigo_global_numero"]).'</strong> C&Oacute;DIGO CORRELATIVO: <strong>'
                                .$data[0]["mue_codigo_correlativo_alfa"].' - '.helperString::completeZeros($data[0]["mue_codigo_correlativo_numero"]).'</strong><br/>
                                    <br/>Al&iacute;cuotas asignadas a esta muestra: <br/>';

                        for($i=1; $i<count($ids); $i++)
                        {
                            // Alicuotas
                                $data = helperMuestra::getCodigos($ids[$i]);
                                $informacion .= $i.'. C&Oacute;DIGO GLOBAL: <strong>'.$data[0]["mue_codigo_global_anio"].
                                ' - '. helperString::completeZeros($data[0]["mue_codigo_global_numero"]).'</strong> C&Oacute;DIGO CORRELATIVO: <strong>'
                                .$data[0]["mue_codigo_correlativo_alfa"].' - '.helperString::completeZeros($data[0]["mue_codigo_correlativo_numero"]).'</strong><br/>';
                        }
                        $this->tpl->setVariable('valExito',$informacion);
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
                $this->tpl->setVariable("n",htmlentities($this->config["search"]["nombres"]));
                $this->tpl->setVariable("a",htmlentities($this->config["search"]["apellidos"]));                
                $this->tpl->setVariable("gd",$this->config["search"]["global_desde"]);
                $this->tpl->setVariable("gh",$this->config["search"]["global_hasta"]);
                $this->tpl->setVariable("cd",$this->config["search"]["correlativo_desde"]);
                $this->tpl->setVariable("ch",$this->config["search"]["correlativo_hasta"]);
                $this->tpl->setVariable("td",$this->config["search"]["con_desde"]);
                $this->tpl->setVariable("th",$this->config["search"]["con_hasta"]);

                if($this->config["search"]["estado"]==0)
                    $this->tpl->setVariable("es1",'selected');
                else if($this->config["search"]["estado"]==1)
                    $this->tpl->setVariable("es2",'selected');
                else if($this->config["search"]["estado"]==2)
                    $this->tpl->setVariable("es3",'selected');

                
                // Carga resultados finales y selecciona
                if($this->config["search"]["evento"]!=''){
                    $resultados = helperCatalogos::getResultadosFinales($this->config["search"]["evento"]);

                    if(is_array($resultados))
                    {
                        foreach($resultados as $resultado)
                        {
                            $this->tpl->setCurrentBlock('blkResultados');
                            $this->tpl->setVariable("valResultado",$resultado["id"]);
                            $this->tpl->setVariable("opcResultado",htmlentities($resultado["nombre"]));

                            // Selecciona el resultado final guardado anteriormente en la búsqueda
                            $this->tpl->setVariable("selResultado",($resultado["id"]==$this->config["search"]["resultado"]?'selected="selected"':''));
                            $this->tpl->parse('blkResultados');
                        }
                    }
                }

                // Carga catálogos de areas para muestra
                $departamentos = helperLugar::getDepartamentos();
                if(is_array($departamentos))
                {
                        foreach($departamentos as $departamento)
                        {
                            $this->tpl->setCurrentBlock('blkDepartamento');
                            $this->tpl->setVariable("valDep",$departamento["departamento"]);
                            $this->tpl->setVariable("opcDep",htmlentities($departamento["descripciondepartamento"]));
                            $this->tpl->setVariable("selDep",($departamento["departamento"]==$this->config["search"]["dep"]?'selected="selected"':''));
                            $this->tpl->parse('blkDepartamento');
                        }
                }
                
                $param["iddep"]=$this->config["search"]["dep"];
                $municipios = helperLugar::getMunicipios($param["iddep"]);
                if(is_array($municipios))
                {
                        foreach($municipios as $municipio)
                        {
                            $this->tpl->setCurrentBlock('blkMunicipio');
                            $this->tpl->setVariable("valMun",$municipio["municipio"]);
                            $this->tpl->setVariable("opcMun",htmlentities($municipio["descripcionmunicipio"]));
                            $this->tpl->setVariable("selMun",($municipio["municipio"]==$this->config["search"]["mun"]?'selected="selected"':''));
                            $this->tpl->parse('blkMunicipio');
                        }
                }
                
                $param["idmun"]=$this->config["search"]["mun"]; 
                //echo "id distrito: ".$param["idmun"];exit;
//                $servicios = helperLugar::getServiciosSalud($param);
//                if(is_array($servicios))
//                {
//                        foreach($servicios as $servicio)
//                        {
//                            $this->tpl->setCurrentBlock('blkServicioMuestra');
//                            $this->tpl->setVariable("valEstablecimiento",$servicio["idts"].'-'.$servicio["tipo"].'-'.$servicio["hospital"]);
//                            $this->tpl->setVariable("opcEstablecimiento",htmlentities($servicio["nombre"]));
//
//                            if($this->config["preselect"])
//                                $this->tpl->setVariable("selEstablecimiento",($servicio["idts"]==$this->config["search"]["centro"]?'selected="selected"':''));
//                            $this->tpl->parse('blkServicioMuestra');
//                        }
//                }  

                $this->tpl->setVariable("centro", '0');
                $this->tpl->setVariable("centro", ($this->config["search"]["centro"]==''?'0':$this->config["search"]["centro"]));
                $this->tpl->setVariable("o", ($this->config["search"]["otro"]==''?'':$this->config["search"]["otro"]));
                if($this->config["search"]["otro"]=='')
                    $this->tpl->setVariable("disableEstablecimiento", '');
                else
                    $this->tpl->setVariable("disableEstablecimiento", 'disabled');                
                
                $this->tpl->setGlobalVariable('prinurl',TemplateHelp::getSearchParameters($this->config['path'].'.php?',$this->config['search']));
                $this->tpl = TemplateHelp::getPaginator($this->tpl, $this->config);

                require_once ('libs/caus/clsCaus.php');
                if(clsCaus::validarSeccion(ConfigurationCAUS::r2, ConfigurationCAUS::Reportes))
                    $this->tpl->setVariable("botonGenerar",'<div style="margin-top:10px"><a href="javascript:buscarMuestra();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)"><span class="ui-icon ui-icon-document-b"></span> Generar Reporte</a></div>');

                if(!clsCaus::validarSeccion(ConfigurationCAUS::r2, ConfigurationCAUS::TipoReporteAnalista))
                    $this->tpl->setVariable("mostrarTipo",'none');
                else
                    $this->tpl->setVariable("mostrarTipo",'');
		$this->tpl->parse('contentBlock');
	}
}
?>