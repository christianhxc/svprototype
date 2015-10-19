<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

class pruebasDerivacionPage extends page
{
	public $config;

	function __construct($data = null)
	{
                $this->config = $data;
		parent::__construct($data);
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'analista/pruebasDerivacion.tpl.html');

                // ESTADO DE LA MUESTRA
                $this->tpl->setVariable('estadoMuestra',$this->config["data"]["SIT_ID"]);


                if($this->config["error"])
                {
                    $this->tpl->setVariable('mensajeError','');
                    $this->tpl->setVariable('mostrarError','');
                    $this->tpl->setVariable('valError',$this->config["mensaje"]);
                }
                else
                {
                    $this->tpl->setVariable('mensajeError','none');
                    $this->tpl->setVariable('mostrarError','none');
                    $this->tpl->setVariable('valError','');
                }

                if($this->config["exito"])
                {
                    $this->tpl->setVariable('mostrarExito','');
                    switch($this->config["selectMensaje"])
                    {
                        case '1':
                            $data = helperMuestra::getCodigos($this->config["muestra"]);
                            $this->tpl->setVariable('valExito','&#161;Prueba(s) / Conclusi&oacute;n asignada(s) correctamente&#33; C&Oacute;DIGO GLOBAL: <strong>'.$data[0]["mue_codigo_global_anio"].
                                    ' - '. helperString::completeZeros($data[0]["mue_codigo_global_numero"]).'</strong> C&Oacute;DIGO CORRELATIVO: <strong>'
                                    .$data[0]["mue_codigo_correlativo_alfa"].' - '.helperString::completeZeros($data[0]["mue_codigo_correlativo_numero"]).'</strong>');
                        break;
                    }
                }
                else
                    $this->tpl->setVariable('mensajeExito','none');
                
                $this->tpl->setVariable('valIdEvento', $this->config["data"]["EVE_ID"]);
                $this->tpl->setVariable('valIdEventoTipo', $this->config["data"]["TIP_MUE_ID"]);
                $this->tpl->setVariable('valTipo', htmlentities($this->config["data"]["TIP_MUE_NOMBRE"]));
                $this->tpl->setVariable('valId', $this->config["data"]["MUE_ID"]);
                $this->tpl->setVariable('valGlobal', $this->config["data"]["MUE_CODIGO_GLOBAL_ANIO"].' - '.helperString::completeZeros($this->config["data"]["MUE_CODIGO_GLOBAL_NUMERO"]));
                $this->tpl->setVariable('valCorrelativo', $this->config["data"]["MUE_CODIGO_CORRELATIVO_ALFA"].' - '.helperString::completeZeros($this->config["data"]["MUE_CODIGO_CORRELATIVO_NUMERO"]));
                $this->tpl->setVariable('valFechaToma',  helperString::toDateView($this->config["data"]["MUE_FECHA_TOMA"]));
                $this->tpl->setVariable('valFechaRecepcion', helperString::toDateView($this->config["data"]["MUE_FECHA_RECEPCION"]));

                $this->tpl->setVariable('valFechaInicio', ($this->config["data"]["inicio"]==NULL?'No disponible':helperString::toDateView($this->config["data"]["inicio"])));
                $this->tpl->setVariable('valFechaDerivacion', helperString::toDateView($this->config["data"]["DER_FECHA"]));
                
                $this->tpl->setVariable('valArea', htmlentities($this->config["data"]["ARE_ANA_NOMBRE"]));


                $stringOtro = (trim($this->config["data"]["establecimiento"])=='No Disponible'?$this->config["data"]["otro_establecimiento"]:$this->config["data"]["establecimiento"]);

                $this->tpl->setVariable('procedencia',  '<br/><strong>PROCEDENCIA DE LA MUESTRA</strong>:<br/>&Aacute;rea de Salud: '.htmlentities($this->config["data"]["area"])
                        .'<br/>Distrito de Salud: '.htmlentities($this->config["data"]["ds"])
                        .'<br/>Establecimiento: '.htmlentities($stringOtro).'<br/>');

                $tipoFLU = '';
                if($this->config["data"]["EVE_ID"]==Configuration::FLU)
                {
                    if($this->config["data"]["flu"]=='1')
                        $tipoFLU = ' ETI';
                    else if($this->config["data"]["flu"]=='2')
                        $tipoFLU = ' IRAG';
                }

                $this->tpl->setVariable('valEvento', htmlentities($this->config["data"]["EVE_NOMBRE"].$tipoFLU));

                $propiedades = '';
                $this->tpl->setVariable('rowPropiedades', '');
                switch($this->config["data"]["EVE_ID"])
                {
                    case Configuration::VIH:
                        if($this->config["data"]["carga"]== 1)
                        {
                            $propiedades = 'CARGA VIRAL';
                            if($this->config["data"]["donador"]== 1)
                                $propiedades .= '&nbsp;- DONADOR';
                            else if($this->config["data"]["donador"]== 2)
                                $propiedades .= '&nbsp;- PACIENTE';
                        }
                        else
                        {
                            if($this->config["data"]["donador"]== 1)
                                $propiedades .= 'DONADOR';
                            else if($this->config["data"]["donador"]== 2)
                                $propiedades .= 'PACIENTE';
                        }

                        break;
                    case Configuration::HEPB:
                        if($this->config["data"]["donador"]== 1)
                            $propiedades .= 'DONADOR';
                        else if($this->config["data"]["donador"]== 2)
                            $propiedades .= 'PACIENTE';
                        break;
                    case Configuration::HEPC:
                        if($this->config["data"]["donador"]== 1)
                            $propiedades .= 'DONADOR';
                        else if($this->config["data"]["donador"]== 2)
                            $propiedades .= 'PACIENTE';
                        break;
                    case Configuration::CHA:
                        // paciente o donador
                        if($this->config["data"]["serologica"]== 1)
                        {
                            $propiedades = 'ENCUESTA SEROL&Oacute;GICA';
                            if($this->config["data"]["donador"]== 1)
                                $propiedades .= '&nbsp;- DONADOR';
                            else if($this->config["data"]["donador"]== 2)
                                $propiedades .= '&nbsp;- PACIENTE';
                        }
                        else
                        {
                            if($this->config["data"]["donador"]== 1)
                                $propiedades .= 'DONADOR';
                            else if($this->config["data"]["donador"]== 2)
                                $propiedades .= 'PACIENTE';
                        }
                        break;
                    default:
                        $this->tpl->setVariable('rowPropiedades', 'none');
                    break;
                }
                $this->tpl->setVariable('propiedades', $propiedades);

                $this->tpl->setVariable('mostrarCondicion', 'none');
                switch($this->config["data"]["condicion"])
                {
                    case 1:
                        $this->tpl->setVariable('mostrarCondicion', '');
                        $this->tpl->setVariable('valorCondicion', ' HOSPITALIZADO');
                        break;
                    case 2:
                        $this->tpl->setVariable('mostrarCondicion', '');
                        $this->tpl->setVariable('valorCondicion', ' FALLECIDO');
                        break;
                    default:
                        break;
                }

                // Tendencia serológica
                if($this->config["data"]["EVE_ID"] == Configuration::dengue)
                {
                    $this->tpl->setVariable('mostrarTendencia','');
                    $this->tpl->setVariable('valorTendencia',htmlentities($this->config["data"]["MUE_TENDENCIA"]));
                }
                else
                {
                    $this->tpl->setVariable('mostrarTendencia','none');
                    $this->tpl->setVariable('valorTendencia','');
                }

                // Carga estados de la muestra
                $estadoSeleccionado = '';
                $estados = helperMuestra::getEstados();
                if(is_array($estados))
                {
                    foreach($estados as $estado)
                    {
                        $this->tpl->setCurrentBlock('blkEstado');
                        $this->tpl->setVariable("valEstado",$estado["EST_ID"]);
                        $this->tpl->setVariable("opcEstado",$estado["EST_NOMBRE"]);

                        // Selecciona el evento guardado
                        if($estado["EST_ID"]==$this->config["data"]["EST_ID"])
                        {
                            $this->tpl->setVariable("selEstado",'selected="selected"');
                            $estadoSeleccionado = $estado["EST_NOMBRE"];
                        }
                        else
                            $this->tpl->setVariable("selEstado",'');

                        //$this->tpl->setVariable("selEstado",($estado["EST_ID"]==$this->config["data"]["EST_ID"]?'selected="selected"':''));
                        $this->tpl->parse('blkEstado');
                    }
                }

                // Carga motivo del estado
                $motivoSeleccionado ='';
                $motivos = helperMuestra::getMotivos($this->config["data"]["EST_ID"]);
                if(is_array($motivos))
                {
                    foreach($motivos as $motivo)
                    {
                        $this->tpl->setCurrentBlock('blkMotivo');
                        $this->tpl->setVariable("valMotivo",$motivo["MOT_ID"]);
                        $this->tpl->setVariable("opcMotivo",$motivo["MOT_NOMBRE"]);

                        // Selecciona el evento guardado
                        if($motivo["MOT_ID"]==$this->config["data"]["MOT_ID"])
                        {
                                $this->tpl->setVariable("selMotivo",'selected="selected"');
                                $motivoSeleccionado = $motivo["MOT_NOMBRE"];
                        }
                        else
                            $this->tpl->setVariable("selMotivo",'');
                        $this->tpl->parse('blkMotivo');
                    }
                }

                // Carga pruebas
                $pruebas = helperMuestra::getPruebas($this->config["data"]["EVE_ID"], $this->config["data"]["TIP_MUE_ID"]);
                if(is_array($pruebas))
                {
                    foreach($pruebas as $prueba)
                    {
                        $this->tpl->setCurrentBlock('blkPruebas');
                        $this->tpl->setVariable("valPrueba",$prueba["PRU_ID"]);
                        $this->tpl->setVariable("opcPrueba",htmlentities($prueba["PRU_NOMBRE"]));
                        $this->tpl->parse('blkPruebas');
                    }
                }

		// Mostrar las entradas del grid
		$entradas = $this->config["pruebas"];
                $this->tpl->setVariable('conteo',count($entradas));
                $indice = 1;
               $pruebasYaAsignadas = '';

		if(is_array($entradas))
                {
			foreach($entradas as $entrada)
                        {
                            $this->tpl->setCurrentBlock('blkEntradas');

//                                $this->tpl->setVariable('i',$entrada["ANA_MUE_ID"]);
//                                $this->tpl->setVariable('idPrueba','pr'.$entrada["ANA_MUE_ID"]);

                                $this->tpl->setVariable('i',$indice);
                                $this->tpl->setVariable('idPrueba','pr'.$indice);

                                $this->tpl->setVariable('idColPrueba','p'.$indice);
                                $this->tpl->setVariable('pruebaFila',htmlentities($entrada["PRU_NOMBRE"]));
                                $this->tpl->setVariable('p',$entrada["PRU_ID"]);
                                $pruebasYaAsignadas.=$entrada["PRU_ID"].' ';

                                $this->tpl->setVariable('idColResultado','r'.$indice);
                                $this->tpl->setVariable('resultadoFila',htmlentities($entrada["RES_NOMBRE"]));
                                $this->tpl->setVariable('r',$entrada["RES_ID"]);

                                $this->tpl->setVariable('idColFecha','f'.$indice);
                                $this->tpl->setVariable('fechaFila',  helperString::toDateView($entrada["ANA_MUE_FECHA"]));

                                $this->tpl->setVariable('idColComentario','c'.$indice);
                                $this->tpl->setVariable('comentarioFila',($entrada["ANA_MUE_COMENTARIOS"]==NULL?'':htmlentities($entrada["ANA_MUE_COMENTARIOS"])));

                                // Botones de eliminar y editar
                                $this->tpl->setCurrentBlock("blkOpcionBorrar");
                                if($this->config["data"]["SIT_ID"]==Configuration::analisisFinalizadoDer)
                                    $this->tpl->setVariable('linkBorrar','&nbsp;');
                                else
                                    $this->tpl->setVariable('linkBorrar','<a href="javascript:borrarPrueba('.$indice.')" onmouseout="RollOut(this)" onmouseover="RollOver(this)" class="ui-state-default ui-corner-all ui-link-button" title="Borrar prueba">
                                    <span class="ui-icon ui-icon-trash"></span></a>');
                                $this->tpl->parse("blkOpcionBorrar");

                                $this->tpl->setCurrentBlock("blkOpcionEditar");
                                    if($this->config["data"]["SIT_ID"]==Configuration::analisisFinalizadoDer)
                                        $this->tpl->setVariable('linkEditar','&nbsp;');
                                    else
                                        $this->tpl->setVariable('linkEditar','<a href="javascript:editarPrueba('.$indice.')" onmouseout="RollOut(this)" onmouseover="RollOver(this)" class="ui-state-default ui-corner-all ui-link-button" title="Editar prueba">
                                        <span class="ui-icon ui-icon-pencil"></span></a>');
                                $this->tpl->parse("blkOpcionEditar");

                            $this->tpl->parse('blkEntradas');
                            $indice++;
			}
                        $pruebasYaAsignadas = trim($pruebasYaAsignadas);
		}
                else
                {
                    $pruebasYaAsignadas = '';
                    $this->tpl->touchBlock('blkNoEntradas');
                }

                $this->tpl->setVariable('pruebasAsignadas',$pruebasYaAsignadas);

                $this->tpl->setVariable('mostrarTabla','none');
                $this->tpl->setVariable('mostrarTabla2','');
                // ESCONDER DATA Y BOTONES SI ANALISIS HA SIDO FINALIZADO
                $this->tpl->setVariable('textoCancelar','Cancelar');

                $preselect = false;
                if($this->config["data"]["SIT_ID"]==Configuration::enAnalisisDer)
                {
                     $this->tpl->setVariable('mostrarFin','');
                     $this->tpl->setVariable('f',0);
                     $this->tpl->setVariable('mostrarAsignar','');

                     // Mostrar opciones previamente seleccionadas de la conclusión
                     if(isset($this->config["conclusion"])){
                         $preselect = true;
                     }
                }
                else if($this->config["data"]["SIT_ID"]==Configuration::analisisFinalizadoDer)
                {
                     $this->tpl->setVariable('mostrarTabla','');
                     $this->tpl->setVariable('mostrarTabla2','none');
                     $this->tpl->setVariable('mostrarFin','none');
                     $this->tpl->setVariable('mostrarGuardar','none');
                     $this->tpl->setVariable('f',0);
                     $this->tpl->setVariable('textoCancelar','Regresar');
                     $this->tpl->setVariable('mostrarAsignar','none');

                     $this->tpl->setVariable('mostrarEstado','none');
                     $this->tpl->setVariable('mostrarMotivo','none');
                     $this->tpl->setVariable('valorEstado',$estadoSeleccionado);
                     $this->tpl->setVariable('valorMotivo',$motivoSeleccionado);
                     $this->tpl->setVariable('estadoMuestraAnalisis','<br/>AN&Aacute;LISIS FINALIZADO');

                     $this->tpl->setVariable('mostrarResultadoFinal','none');
                     $this->tpl->setVariable('mostrarTipo1','none');
                     $this->tpl->setVariable('mostrarSubtipo1','none');
                     $this->tpl->setVariable('mostrarTipo2','none');
                     $this->tpl->setVariable('mostrarSubtipo2','none');
                     $this->tpl->setVariable('disabledComentariosFinales','disabled');
                     $this->tpl->setVariable('valorResultadoFinal',$this->config["conclusion"]["resultado"]);

                     // Mostrar primer resultado específico
                     if($this->config["conclusion"]["t1"] == $this->config["conclusion"]["s1"] &&
                             $this->config["conclusion"]["t1"] == Configuration::idTipoNoaplica
                             && $this->config["conclusion"]["s1"] == Configuration::idSubtipoNoaplica)
                     {
                        $this->tpl->setVariable('valorTipo1',htmlentities($this->config["conclusion"]["tipo1"]));
                        $this->tpl->setVariable('valorSubtipo1','');
                     }
                     else
                     {
                        if($this->config["conclusion"]["s1"] == Configuration::idSubtipoNoaplica)
                            $this->tpl->setVariable('valorTipo1',htmlentities($this->config["conclusion"]["tipo1"]));
                        else
                            $this->tpl->setVariable('valorTipo1',htmlentities($this->config["conclusion"]["tipo1"].' '.$this->config["conclusion"]["subtipo1"]));
                     }

                     // Mostrar segundo resultado específico
                     if($this->config["conclusion"]["t2"] == $this->config["conclusion"]["s2"]
                             && $this->config["conclusion"]["t2"] == Configuration::idTipoNoaplica
                             && $this->config["conclusion"]["s2"] == Configuration::idSubtipoNoaplica)
                     {
                        $this->tpl->setVariable('valorTipo2',htmlentities($this->config["conclusion"]["tipo2"]));
                        $this->tpl->setVariable('valorSubtipo2','');
                     }
                     else
                     {
                        if($this->config["conclusion"]["s2"] == Configuration::idSubtipoNoaplica)
                            $this->tpl->setVariable('valorTipo2',htmlentities($this->config["conclusion"]["tipo2"]));
                        else
                            $this->tpl->setVariable('valorTipo2',htmlentities($this->config["conclusion"]["tipo2"].' '.$this->config["conclusion"]["subtipo2"]));
                     }

                     $this->tpl->setVariable('disabledComentariosFinales','readonly="readonly"');
                     $this->tpl->setVariable('valorComentariosFinales',htmlentities($this->config["conclusion"]["comentarios"]));
                }

                // Preselecciona y Carga resultados finales
                $resultadosFinales = helperMuestra::getResultadosFinales($this->config["data"]["EVE_ID"]);
                if(is_array($resultadosFinales))
                {
                    foreach($resultadosFinales as $resultadofinal)
                    {
                        $this->tpl->setCurrentBlock('blkResultadoFinal');
                        $this->tpl->setVariable("valResultadoFinal",$resultadofinal["RES_FIN_ID"]);
                        if($preselect)
                        {
                            if($resultadofinal["RES_FIN_ID"] == $this->config["conclusion"]["res"])
                                $this->tpl->setVariable("selResultadoFinal",'selected');
                            else
                                $this->tpl->setVariable("selResultadoFinal",'');
                        }
                        $this->tpl->setVariable("opcResultadoFinal",$resultadofinal["RES_FIN_NOMBRE"]);
                        $this->tpl->parse('blkResultadoFinal');
                    }
                }

                if($preselect)
                {
                    // Preselecciona y carga combo 1 de resultado específico 1
                    $tipos = helperMuestra::getTipos($this->config["data"]["EVE_ID"], $this->config["conclusion"]["res"]);
                    if(is_array($tipos))
                    {
                        foreach($tipos as $tipo)
                        {
                            $this->tpl->setCurrentBlock('blkTipo1');
                            $this->tpl->setVariable("valTipo1",$tipo["TIP_ID"]);

                            if($tipo["TIP_ID"] == $this->config["conclusion"]["t1"])
                                $this->tpl->setVariable("selTipo1",'selected');
                            else
                                $this->tpl->setVariable("selTipo1",'');
                            $this->tpl->setVariable("opcTipo1",$tipo["TIP_NOMBRE"]);
                            $this->tpl->parse('blkTipo1');
                        }
                    }

                    // Preselecciona y carga combo 1 de resultado específico 2
                    if(is_array($tipos))
                    {
                        foreach($tipos as $tipo)
                        {
                            $this->tpl->setCurrentBlock('blkTipo2');
                            $this->tpl->setVariable("valTipo2",$tipo["TIP_ID"]);

                            if($tipo["TIP_ID"] == $this->config["conclusion"]["t2"])
                                $this->tpl->setVariable("selTipo2",'selected');
                            else
                                $this->tpl->setVariable("selTipo2",'');
                            $this->tpl->setVariable("opcTipo2",$tipo["TIP_NOMBRE"]);
                            $this->tpl->parse('blkTipo2');
                        }
                    }

                    // Preselecciona y carga combo 2 de resultado específico 1
                    $tipos = helperMuestra::getSubtipos($this->config["data"]["EVE_ID"], $this->config["conclusion"]["res"], $this->config["conclusion"]["t1"]);
                    if(is_array($tipos))
                    {
                        foreach($tipos as $tipo)
                        {
                            $this->tpl->setCurrentBlock('blkSubtipo1');
                            $this->tpl->setVariable("valSubtipo1",$tipo["SUB_ID"]);

                            if($tipo["SUB_ID"] == $this->config["conclusion"]["s1"])
                                $this->tpl->setVariable("selSubtipo1",'selected');
                            else
                                $this->tpl->setVariable("selSubtipo1",'');
                            $this->tpl->setVariable("opcSubtipo1",$tipo["SUB_NOMBRE"]);
                            $this->tpl->parse('blkSubtipo1');
                        }
                    }

                  // Preselecciona y carga combo 2 de resultado específico 2
                    $tipos = helperMuestra::getSubtipos($this->config["data"]["EVE_ID"], $this->config["conclusion"]["res"], $this->config["conclusion"]["t2"]);
                    if(is_array($tipos))
                    {
                        foreach($tipos as $tipo)
                        {
                            $this->tpl->setCurrentBlock('blkSubtipo2');
                            $this->tpl->setVariable("valSubtipo2",$tipo["SUB_ID"]);

                            if($tipo["SUB_ID"] == $this->config["conclusion"]["s2"])
                                $this->tpl->setVariable("selSubtipo2",'selected');
                            else
                                $this->tpl->setVariable("selSubtipo2",'');
                            $this->tpl->setVariable("opcSubtipo2",$tipo["SUB_NOMBRE"]);
                            $this->tpl->parse('blkSubtipo2');
                        }
                    }
                }

                $this->tpl->setVariable('comentarios',$this->config["data"]["comentarios"]);
                $this->tpl->setVariable("valorComentariosFinales",htmlentities($this->config["conclusion"]["comentarios"]));
                $this->tpl->setVariable("disabledComentariosFinales",'');

                // Nombre de la persona
                $nombre = $this->config["data"]["IND_PRIMER_NOMBRE"].' '.$this->config["data"]["IND_SEGUNDO_NOMBRE"].' ';
                $nombre.= $this->config["data"]["IND_PRIMER_APELLIDO"].' '.$this->config["data"]["IND_SEGUNDO_APELLIDO"];
                $nombre = trim($nombre);

                if($this->config["data"]["EVE_ID"] == Configuration::VIH)
                        $nombre = $this->config["data"]["IND_HISTORIA_CLINICA"];
                else
                {
                    if($nombre =='')
                        $nombre='No corresponde';
                }
                $this->tpl->setVariable('nombre', htmlentities($nombre));

		$this->tpl->parse('contentBlock');
	}
}
?>