<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

class asignarAlicuotaPage extends page
{
	public $config;

	function __construct($data = null)
	{
                $this->config = $data;
		parent::__construct($data);
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'ventanilla/asignarAlicuota.tpl.html');

                // ESTADO DE LA MUESTRA
                $this->tpl->setVariable('estadoMuestra',$this->config["data"]["SIT_ID"]);
                $this->tpl->setVariable('idMuestra',$this->config["data"]["MUE_ID"]);                
                
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

                                // Carga catálogo de razones de rechazo
                $razones = $this->config["catalogos"]["razones_rechazo"];
                if(is_array($razones))
                {
                        foreach($razones as $razon)
                        {
                            $this->tpl->setCurrentBlock('blkRazones');
                            $this->tpl->setVariable("valRazon",$razon["RAZ_REC_ID"]);
                            $this->tpl->setVariable("opcRazon",htmlentities($razon["RAZ_REC_NOMBRE"]));

                            if($this->config["preselect"])
                                $this->tpl->setVariable("selRazon",($razon["RAZ_REC_ID"]==$this->config["data"]["muestra"]["razon_rechazo"]?'selected="selected"':''));

                            $this->tpl->parse('blkRazones');
                        }
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

                $this->tpl->setVariable('valorEstado',$estadoSeleccionado);
                $this->tpl->setVariable('valorMotivo',$motivoSeleccionado);
		$this->tpl->parse('contentBlock');
	}
}
?>