<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

class derivarPage extends page
{
	public $config;

	function __construct($data = null)
	{
                $this->config = $data;
		parent::__construct($data);
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'analista/derivar.tpl.html');

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

                $this->tpl->setVariable('mensajeExito','none');
                $this->tpl->setVariable('valIdEvento', $this->config["data"]["EVE_ID"]);
                $this->tpl->setVariable('valIdEventoTipo', $this->config["data"]["TIP_MUE_ID"]);
                $this->tpl->setVariable('valTipo', $this->config["data"]["TIP_MUE_NOMBRE"]);
                $this->tpl->setVariable('valId', $this->config["data"]["MUE_ID"]);
                $this->tpl->setVariable('valGlobal', $this->config["data"]["MUE_CODIGO_GLOBAL_ANIO"].' - '.helperString::completeZeros($this->config["data"]["MUE_CODIGO_GLOBAL_NUMERO"]));
                $this->tpl->setVariable('valCorrelativo', $this->config["data"]["MUE_CODIGO_CORRELATIVO_ALFA"].' - '.helperString::completeZeros($this->config["data"]["MUE_CODIGO_CORRELATIVO_NUMERO"]));
                $this->tpl->setVariable('valFechaInicio', ($this->config["data"]["inicio"]==NULL?'No Disp.':helperString::toDateView($this->config["data"]["inicio"])));
                $this->tpl->setVariable('valFechaToma',  helperString::toDateView($this->config["data"]["MUE_FECHA_TOMA"]));
                $this->tpl->setVariable('valFechaRecepcion', helperString::toDateView($this->config["data"]["MUE_FECHA_RECEPCION"]));
                $this->tpl->setVariable('valArea', htmlentities($this->config["data"]["ARE_ANA_NOMBRE"]));
                $this->tpl->setVariable('idArea', htmlentities($this->config["data"]["ARE_ANA_ID"]));

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

                $this->tpl->setVariable('valEvento', htmlentities($this->config["data"]["EVE_NOMBRE"]).$tipoFLU);

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

                $this->tpl->setVariable('comentarios',htmlentities($this->config["data"]["comentarios"]));

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

                $this->tpl->setVariable('valorEstado',$estadoSeleccionado);
                $this->tpl->setVariable('valorMotivo',$motivoSeleccionado);
		$this->tpl->parse('contentBlock');
	}
}
?>