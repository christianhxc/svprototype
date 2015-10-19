<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

class derivarMuestraPage extends page
{
	public $config;

	function __construct($data = null)
	{
                $this->config = $data;
		parent::__construct($data);
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'analista/derivarMuestra.tpl.html');

                // ESTADO DE LA MUESTRA
                $this->tpl->setVariable('estadoMuestra',$this->config["data"]["SIT_ID"]);
                $this->tpl->setVariable('idMuestra',$this->config["data"]["MUE_ID"]);

                // ESCONDER DATA Y BOTONES SI ANALISIS HA SIDO FINALIZADO
                $this->tpl->setVariable('textoCancelar','Cancelar');
                if($this->config["data"]["SIT_ID"]==Configuration::recibidaAnalisis)
                {
                     $this->tpl->setVariable('mostrarFin','');
                     $this->tpl->setVariable('f',0);
                     $this->tpl->setVariable('mostrarAsignar','');
                }
                else if($this->config["data"]["SIT_ID"]==Configuration::analisisFinalizado)
                {
                     $this->tpl->setVariable('mostrarFin','none');
                     $this->tpl->setVariable('mostrarGuardar','none');                     
                     $this->tpl->setVariable('f',0);
                     $this->tpl->setVariable('textoCancelar','Regresar');
                     $this->tpl->setVariable('mostrarAsignar','none');

                     $this->tpl->setVariable('mostrarEstado','none');
                     $this->tpl->setVariable('mostrarMotivo','none');
                     $this->tpl->setVariable('valorEstado','ABCD');
                     $this->tpl->setVariable('valorMotivo','ABCD');
                     $this->tpl->setVariable('estadoMuestraAnalisis','<br/>AN&Aacute;LISIS FINALIZADO');
                }
                
                
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

                $this->tpl->setVariable('mensajeExito','none');
                $this->tpl->setVariable('valIdEvento', $this->config["data"]["EVE_ID"]);
                $this->tpl->setVariable('valIdEventoTipo', $this->config["data"]["TIP_MUE_ID"]);
                $this->tpl->setVariable('valTipo', $this->config["data"]["TIP_MUE_NOMBRE"]);
                $this->tpl->setVariable('valId', $this->config["data"]["MUE_ID"]);
                $this->tpl->setVariable('valGlobal', $this->config["data"]["MUE_CODIGO_GLOBAL_ANIO"].' - '.helperString::completeZeros($this->config["data"]["MUE_CODIGO_GLOBAL_NUMERO"]));
                $this->tpl->setVariable('valCorrelativo', $this->config["data"]["MUE_CODIGO_CORRELATIVO_ALFA"].' - '.helperString::completeZeros($this->config["data"]["MUE_CODIGO_CORRELATIVO_NUMERO"]));
                $this->tpl->setVariable('valFechaToma',  helperString::toDateView($this->config["data"]["MUE_FECHA_TOMA"]));
                $this->tpl->setVariable('valFechaRecepcion', helperString::toDateView($this->config["data"]["MUE_FECHA_RECEPCION"]));
                $this->tpl->setVariable('valArea', htmlentities($this->config["data"]["ARE_ANA_NOMBRE"]));
                $this->tpl->setVariable('idArea', htmlentities($this->config["data"]["ARE_ANA_ID"]));
                $this->tpl->setVariable('valEvento', $this->config["data"]["EVE_NOMBRE"]);

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
                        // Selecciona el evento guardado
                        if($estado["EST_ID"]==$this->config["data"]["EST_ID"])
                        {
                            $this->tpl->setVariable("selEstado",'selected="selected"');
                            $estadoSeleccionado = $estado["EST_NOMBRE"];
                        }
                        else
                            $this->tpl->setVariable("selEstado",'');
                    }
                }

                // Carga motivo del estado
                $motivoSeleccionado ='';
                $motivos = helperMuestra::getMotivos($this->config["data"]["EST_MOT_ID"]);
                if(is_array($motivos))
                {
                    foreach($motivos as $motivo)
                    {
                        // Selecciona el evento guardado
                        if($motivo["MOT_ID"]==$this->config["data"]["MOT_ID"])
                        {
                                $this->tpl->setVariable("selMotivo",'selected="selected"');
                                $motivoSeleccionado = $motivo["MOT_NOMBRE"];
                        }
                        else
                            $this->tpl->setVariable("selMotivo",'');
                    }
                }


		// Mostrar las pruebas que tiene asignada la muestra del grid
		$entradas = $this->config["pruebas"];
                $this->tpl->setVariable('conteo',count($entradas));
                $indice = 1;

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
                                $this->tpl->setVariable('pruebaFila',$entrada["PRU_NOMBRE"]);
                                $this->tpl->setVariable('p',$entrada["PRU_ID"]);

                                $this->tpl->setVariable('idColResultado','r'.$indice);
                                $this->tpl->setVariable('resultadoFila',$entrada["RES_NOMBRE"]);
                                $this->tpl->setVariable('r',$entrada["RES_ID"]);

                                $this->tpl->setVariable('idColTipo','t'.$indice);
                                $this->tpl->setVariable('tipoFila',$entrada["TIP_NOMBRE"]);
                                $this->tpl->setVariable('t',$entrada["TIP_ID"]);

                                $this->tpl->setVariable('idColsubtipo','s'.$indice);
                                $this->tpl->setVariable('subtipoFila',$entrada["SUB_NOMBRE"]);
                                $this->tpl->setVariable('s',$entrada["SUB_ID"]);

                                $this->tpl->setVariable('idColFecha','f'.$indice);
                                $this->tpl->setVariable('fechaFila',  helperString::toDateView($entrada["ANA_MUE_FECHA"]));
                                
                                $this->tpl->setVariable('idColComentario','c'.$indice);
                                $this->tpl->setVariable('comentarioFila',($entrada["ANA_MUE_COMENTARIOS"]==NULL?'':$entrada["ANA_MUE_COMENTARIOS"]));

                                // Botones de eliminar y editar
//                                $this->tpl->setCurrentBlock("blkOpcionBorrar");
//                                if($this->config["data"]["SIT_ID"]==Configuration::analisisFinalizado)
//                                    $this->tpl->setVariable('linkBorrar','&nbsp;');
//                                else
//                                    $this->tpl->setVariable('linkBorrar','<a href="javascript:borrarPrueba('.$indice.')" onmouseout="RollOut(this)" onmouseover="RollOver(this)" class="ui-state-default ui-corner-all ui-link-button" title="Borrar prueba">
//                                    <span class="ui-icon ui-icon-trash"></span></a>');
//                                $this->tpl->parse("blkOpcionBorrar");

//                                $this->tpl->setCurrentBlock("blkOpcionEditar");
//                                    if($this->config["data"]["SIT_ID"]==Configuration::analisisFinalizado)
//                                        $this->tpl->setVariable('linkEditar','&nbsp;');
//                                    else
//                                        $this->tpl->setVariable('linkEditar','<a href="javascript:editarPrueba('.$indice.')" onmouseout="RollOut(this)" onmouseover="RollOver(this)" class="ui-state-default ui-corner-all ui-link-button" title="Editar prueba">
//                                        <span class="ui-icon ui-icon-pencil"></span></a>');
//                                $this->tpl->parse("blkOpcionEditar");
                                
                            $this->tpl->parse('blkEntradas');
                            $indice++;
			}
		}
                else
                {
                    $this->tpl->touchBlock('blkNoEntradas');
                    // Ocultar el botón de finalizar análisis si no tiene una sola prueba asignada
                     $this->tpl->setVariable('mostrarFin','none');
                     $this->tpl->setVariable('f',0);
                }


                if(isset($this->config["conclusion"]))
                {
                    if($this->config["data"]["SIT_ID"]==Configuration::analisisFinalizado)
                    {
                         $this->tpl->setVariable('valorResultadoFinal',$this->config["conclusion"]["resultado"]);

                         // Mostrar primer resultado específico
                         if($this->config["conclusion"]["t1"] == $this->config["conclusion"]["s1"] &&
                                 $this->config["conclusion"]["t1"] == Configuration::idTipoNoaplica
                                 && $this->config["conclusion"]["s1"] == Configuration::idSubtipoNoaplica)
                         {
                            $this->tpl->setVariable('valorTipo1',$this->config["conclusion"]["tipo1"]);
                            $this->tpl->setVariable('valorSubtipo1','');
                         }
                         else
                         {
                            if($this->config["conclusion"]["s1"] == Configuration::idSubtipoNoaplica)
                                $this->tpl->setVariable('valorTipo1',$this->config["conclusion"]["tipo1"]);
                            else
                                $this->tpl->setVariable('valorTipo1',$this->config["conclusion"]["tipo1"].' '.$this->config["conclusion"]["subtipo1"]);
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
                                $this->tpl->setVariable('valorTipo2',$this->config["conclusion"]["tipo2"]);
                            else
                                $this->tpl->setVariable('valorTipo2',$this->config["conclusion"]["tipo2"].' '.$this->config["conclusion"]["subtipo2"]);
                         }
                         
                         $this->tpl->setVariable('valorComentariosFinales', htmlentities($this->config["conclusion"]["comentarios"]));
                    }
                }

                $this->tpl->setVariable('valorEstado',$estadoSeleccionado);
                $this->tpl->setVariable('valorMotivo',$motivoSeleccionado);
		$this->tpl->parse('contentBlock');
	}
}
?>