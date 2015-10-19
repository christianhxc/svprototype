<?php
require_once ('libs/pages/page.php');

class ingresoAlicuotasPage extends page
{
	public $config;

	function __construct($data = null)
	{
                $this->config = $data;
		parent::__construct($data);
	}

	public function parseContent()
	{
                if($this->config["action"]=="N")
                    $nuevo = true;
                else
                    $nuevo = false;

                // Almacena el valor de la accion G, M, etc.
                $this->tpl->setVariable("action",$this->config["action"]);
                $this->tpl->setVariable("muestra",$this->config["muestra"]);

		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'ventanilla/ingresoAlicuotas.tpl.html');

                // Mostrar el listado de tipos de vigilancia
                $tipos_vigilancia = $this->config["catalogos"]["tipo_vigilancia"];
                if(is_array($tipos_vigilancia))
                {
                        foreach($tipos_vigilancia as $tipo)
                        {
                                $this->tpl->setCurrentBlock('blkTipoVigilancia');
                                $this->tpl->setVariable("valTipoVigilancia",$tipo["TIP_VIG_ID"]);
                                $this->tpl->setVariable("opcTipoVigilancia",htmlentities($tipo["TIP_VIG_NOMBRE"]));

                                if($this->config["preselect"])
                                    $this->tpl->setVariable("selTipoVigilancia",($tipo["TIP_VIG_ID"]==$this->config["data"]["individuo"]["tipo_vigilancia"]?'selected="selected"':''));
                                $this->tpl->parse('blkTipoVigilancia');
                        }
                }

                // Carga catálogos de departamentos para individuo
                $departamentos = $this->config["catalogos"]["departamento"];
                if(is_array($departamentos))
                {
                        foreach($departamentos as $departamento)
                        {
                            $this->tpl->setCurrentBlock('blkDeptoIndividuo');
                            $this->tpl->setVariable("valDeptoIndividuo",$departamento["departamento"]);
                            $this->tpl->setVariable("opcDeptoIndividuo",htmlentities($departamento["descripciondepartamento"]));

                            if($this->config["preselect"])
                                $this->tpl->setVariable("selDeptoIndividuo",($departamento["departamento"]==$this->config["data"]["individuo"]["departamento"]?'selected="selected"':''));
                            $this->tpl->parse('blkDeptoIndividuo');
                        }
                }

                // Carga catálogos de areas para muestra
                $areas = $this->config["catalogos"]["area"];
                if(is_array($areas))
                {
                        foreach($areas as $area)
                        {
                            $this->tpl->setCurrentBlock('blkAreaMuestra');
                            $this->tpl->setVariable("valAreaMuestra",$area["codigoas"]);
                            $this->tpl->setVariable("opcAreaMuestra",htmlentities($area["nombreas"]));

                            if($this->config["preselect"])
                                $this->tpl->setVariable("selAreaMuestra",($area["codigoas"]==$this->config["data"]["muestra"]["area_salud"]?'selected="selected"':''));
                            $this->tpl->parse('blkAreaMuestra');
                        }
                }

                // Carga catálogo de áreas de análisis
                $areas = $this->config["catalogos"]["area_analisis"];
                if(is_array($areas))
                {
                        foreach($areas as $area)
                        {
                            $this->tpl->setCurrentBlock('blkAreas');
                            $this->tpl->setVariable("valAreas",$area["ARE_ANA_ID"]);
                            $this->tpl->setVariable("opcAreas",htmlentities($area["ARE_ANA_NOMBRE"]));

                            if($this->config["preselect"])
                                $this->tpl->setVariable("selAreas",($area["ARE_ANA_ID"]==$this->config["data"]["muestra"]["area_analisis"]?'selected="selected"':''));
                            $this->tpl->parse('blkAreas');
                        }
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

                // Set del valor de las variables
                $this->tpl->setVariable('valID',(nuevo?'-1':$this->config["data"]["individuo"]["id_individuo"]));

                // Mostrar o esconder los códigos y asignarle su valor
                if(!$this->config["codigos"])
                {
                    $this->tpl->setVariable('valDisplayCodigoGlobal','none');
                    $this->tpl->setVariable('valDisplayCodigoCorrelativo','none');
                }
                else
                {
                    $this->tpl->setVariable('valDisplayCodigoGlobal','');
                    $this->tpl->setVariable('valDisplayCodigoCorrelativo','');
                }

                // Inicializar variables si es nuevo
                if($nuevo)
                {
                    $this->tpl->setVariable("valID",'-1');
                    $this->tpl->setVariable("muestra",'-1');
                    $this->tpl->setVariable("chkMuestraHumana",'');
                    $this->tpl->setVariable("chkConfidencial",'');
                    $this->tpl->setVariable("chkIdentificadorNoDisponible",'');                    
                    $this->tpl->setVariable("chkNoDirIndividuo",'');

                    $this->tpl->setVariable("chkNoRechazada",'checked="checked"');
                    $this->tpl->setVariable("chkPaciente",'checked="checked"');
                    $this->tpl->setVariable("chkDonador",'');
                    $this->tpl->setVariable("chkNoDistrito",'');
                    $this->tpl->setVariable("chkNoEstablecimiento",'');                    

                    $this->tpl->setVariable("valMostrarCarga",'none');
                    $this->tpl->setVariable("mostrarRechazo",'none');
                    $this->tpl->setVariable("valMostrarSerologica",'none');
                    $this->tpl->setVariable("valMostrarRadiosPD",'none');    
                    $this->tpl->setVariable("chkNoRechazada",'checked="checked"');
                    $this->tpl->setVariable("valRazonRechazo",'1');
                    $this->tpl->setVariable("chkSiRechazada",'');
                    $this->tpl->setVariable("valSE",'0');
                    $this->tpl->setVariable('validts', '0');

//                    $this->tpl->setVariable('valGlobalCorrelativo', '0');
//                    $this->tpl->setVariable('valGlobalAnio', '0');
//                    $this->tpl->setVariable('valEventoCodigo', '0');
//                    $this->tpl->setVariable('valEventoCorrelativo', '0');
                    $this->tpl->setVariable('valMostrarServicioIntra','none');
                    $this->tpl->setVariable('valMostrarReferida','none');                    
                }
                else
                {
                    $this->tpl->setVariable('valID',$this->config["data"]["individuo"]["id_individuo"]);
                    // EDITAR O TRATAR DE GUARDAR SI OCURRE UN ERROR (recarga datos)
                    if($this->config["data"]["individuo"]["no_muestra_humana"]=='on')
                    {
                        $this->tpl->setVariable('chkMuestraHumana', 'checked="checked"');
                        $this->tpl->setVariable('valMostrarReferida','');
                    }
                    else{
                        $this->tpl->setVariable('chkMuestraHumana', '');
                        $this->tpl->setVariable('valMostrarReferida','none');
                    }

                    // Determina si chequea caso confidencial
                    if($this->config["data"]["individuo"]["confidencial"]=='on')
                        $this->tpl->setVariable('chkConfidencial', 'checked="checked"');
                    else
                        $this->tpl->setVariable('chkConfidencial', '');

                    // Identificador
                    $this->tpl->setVariable('valIdentificador', $this->config["data"]["individuo"]["identificador"]);
                    // Determina si chequea identificador no disponible
                    if($this->config["data"]["individuo"]["identificador_no_disponible"]=='on')
                        $this->tpl->setVariable('chkIdentificadorNoDisponible', 'checked="checked"');
                    else
                        $this->tpl->setVariable('chkIdentificadorNoDisponible', '');

                    // Historia Clínica
                    $this->tpl->setVariable('valHistoriaClinica', $this->config["data"]["individuo"]["historia_clinica"]);

                    // Nombres y apellidos
                    $this->tpl->setVariable('valPrimerNombre', $this->config["data"]["individuo"]["primer_nombre"]);
                    $this->tpl->setVariable('valSegundoNombre', $this->config["data"]["individuo"]["segundo_nombre"]);
                    
                    $this->tpl->setVariable('valPrimerApellido', $this->config["data"]["individuo"]["primer_apellido"]);
                    $this->tpl->setVariable('valSegundoApellido', $this->config["data"]["individuo"]["segundo_apellido"]);
                    $this->tpl->setVariable('valCasada', $this->config["data"]["individuo"]["casada"]);

                    // Tipo de edad y edad
                    switch($this->config["data"]["individuo"]["tipo_edad"]){
                        case '1':
                            $this->tpl->setVariable('selDias','selected="selected"');
                            break;
                        case '2':
                            $this->tpl->setVariable('selMeses','selected="selected"');
                            break;
                        case '3':
                            $this->tpl->setVariable('selAnios','selected="selected"');
                            break;
                    };
                    $this->tpl->setVariable('valEdad', $this->config["data"]["individuo"]["edad"]);
                    $this->tpl->setVariable('valFechaNacimiento', $this->config["data"]["individuo"]["fecha_nacimiento"]);

                    switch($this->config["data"]["individuo"]["sexo"]){
                        case 'M':
                            $this->tpl->setVariable('selSexoM','selected="selected"');
                            break;
                        case 'F':
                            $this->tpl->setVariable('selSexoF','selected="selected"');
                            break;
                    };

                    $this->tpl->setVariable('valTipoVigilancia', $this->config["data"]["individuo"]["tipo_vigilancia"]);

                    // PROCEDENCIA DE MUESTRA

                    // HIDDEN
                    $this->tpl->setVariable('validdep', $this->config["data"]["loaded"]["iddep"]);
                    $this->tpl->setVariable('validmun', $this->config["data"]["loaded"]["idmun"]);
                    $this->tpl->setVariable('validlp', $this->config["data"]["loaded"]["idlp"]);

                    // COMBO MUNICIPIO Y COMBO LUGAR POBLADO

                    // TRAER MUNICIPIOS DEL DEPARTAMENTO SELECCIONADO
                    $municipios= helperLugar::getMunicipios($this->config["data"]["individuo"]["departamento"]);

                    if(is_array($municipios))
                    {
                            foreach($municipios as $municipio)
                            {
                                    $this->tpl->setCurrentBlock('blkMuniIndividuo');
                                    $this->tpl->setVariable("valMuniIndividuo",$municipio["municipio"]);
                                    $this->tpl->setVariable("opcMuniIndividuo",htmlentities($municipio["descripcionmunicipio"]));

                                    if($this->config["preselect"])
                                        $this->tpl->setVariable("selMuniIndividuo",($municipio["municipio"]==$this->config["data"]["individuo"]["municipio"]?'selected="selected"':''));
                                    $this->tpl->parse('blkMuniIndividuo');
                            }
                    }

                    // TRAER LUGARES POBLADOS DEL MUNICIPIO SELECCIONADO
                    $lugares= helperLugar::getZonas($this->config["data"]["individuo"]["departamento"], $this->config["data"]["individuo"]["municipio"]);

                    if(is_array($lugares))
                    {
                            foreach($lugares as $lugar)
                            {
                                    $this->tpl->setCurrentBlock('blkLocalidadIndividuo');
                                    $this->tpl->setVariable("valLocalidadIndividuo",$lugar["zona"]);
                                    $this->tpl->setVariable("opcLocalidadIndividuo",htmlentities($lugar["descripcionzona"]));

                                    if($this->config["preselect"])
                                        $this->tpl->setVariable("selLocalidadIndividuo",($lugar["zona"]==$this->config["data"]["individuo"]["localidad"]?'selected="selected"':''));
                                    $this->tpl->parse('blkLocalidadIndividuo');
                            }
                    }

                    // Direccion y no direccion
                    $this->tpl->setVariable("valDireccionIndividuo",$this->config["data"]["individuo"]["direccion"]);
                    if($this->config["data"]["individuo"]["no_direccion_individuo"]=='on')
                        $this->tpl->setVariable('chkNoDirIndividuo', 'checked="checked"');
                    else
                        $this->tpl->setVariable('chkNoDirIndividuo', '');


                    // PROCEDENCIA DE MUESTRA
                    // Carga catálogos de areas para distrito y establecimiento de muestra
                    $param["idas"]=$this->config["data"]["muestra"]["area_salud"];
                    $distritos = helperLugar::getDistritosSalud($param);
                    if(is_array($distritos))
                    {
                            foreach($distritos as $distrito)
                            {
                                $this->tpl->setCurrentBlock('blkDistritoMuestra');
                                $this->tpl->setVariable("valDistritoMuestra",$distrito["codigods"]);
                                $this->tpl->setVariable("opcDistritoMuestra",htmlentities($distrito["nombreds"]));

                                if($this->config["preselect"])
                                    $this->tpl->setVariable("selDistritoMuestra",($distrito["codigods"]==$this->config["data"]["muestra"]["distrito"]?'selected="selected"':''));
                                $this->tpl->parse('blkDistritoMuestra');
                            }
                    }
                    // HIDDEN PROCEDECIA DE MUESTRA
                    $this->tpl->setVariable('validas', $this->config["data"]["muestra"]["area_salud"]);
                    $this->tpl->setVariable('validds', $this->config["data"]["muestra"]["distrito"]);
                    $this->tpl->setVariable('validEstablecimiento', $this->config["data"]["muestra"]["establecimiento"]);
                    $this->tpl->setVariable('validts', $this->config["data"]["muestra"]["tipo_establecimiento"]);

                    if($this->config["data"]["muestra"]["no_distrito_muestra"]=='on')
                        $this->tpl->setVariable('chkNoDistrito', 'checked="checked"');
                    else
                        $this->tpl->setVariable('chkNoDistrito', '');

                    // Carga y selecciona el establecimiento
                    if($this->config["data"]["muestra"]["no_establecimiento"]=='on')
                        $this->tpl->setVariable('chkNoEstablecimiento', 'checked="checked"');
                    else
                        $this->tpl->setVariable('chkNoEstablecimiento', '');

                    $param["distrito"]=$this->config["data"]["muestra"]["distrito"];
                    $servicios = helperLugar::getServiciosSalud($param);
                    
                    if(is_array($servicios))
                    {
                            foreach($servicios as $servicio)
                            {
                                $this->tpl->setCurrentBlock('blkServicioMuestra');
                                $this->tpl->setVariable("valServicioMuestra",$servicio["idts"].'-'.$servicio["tipo"].'-'.$servicio["hospital"]);
                                $this->tpl->setVariable("opcServicioMuestra",htmlentities($servicio["nombre"]));

                                if($this->config["preselect"])
                                    $this->tpl->setVariable("selServicioMuestra",($servicio["idts"]==$this->config["data"]["muestra"]["establecimiento"]?'selected="selected"':''));
                                $this->tpl->parse('blkServicioMuestra');
                            }
                    }

                    // OTRO ESTABLECIMIENTO
                    $this->tpl->setVariable('valOtroEstablecimiento',$this->config["data"]["muestra"]["otro_establecimiento"]); 

                    // Servicio intrahospitalar
                    $tipoServicioIntra = explode("-",  Configuration::tiposHospitales);
                    
                    (in_array($this->config["data"]["muestra"]["tipo_establecimiento"],$tipoServicioIntra)?$this->tpl->setVariable('valMostrarServicioIntra',''):$this->tpl->setVariable('valMostrarServicioIntra','none'));
                    $this->tpl->setVariable('valServicioIntra',$this->config["data"]["muestra"]["servicio"]);

                    // Referida por
                    if($this->config["data"]["individuo"]["no_muestra_humana"]=='on')
                                $this->tpl->setVariable('valReferidaPor',$this->config["data"]["muestra"]["referida_por"]);
                    else
                        $this->tpl->setVariable('valReferidaPor','');


                    // DATOS DE LA MUESTRA
                    
                    // Area de análisis
                    $this->tpl->setVariable('valArea',$this->config["data"]["muestra"]["area_analisis"]);

                    // EVENTOS
                    $eventos = helperCatalogos::getEventos($this->config["data"]["muestra"]["area_analisis"]);
                    if(is_array($eventos))
                    {
                            foreach($eventos as $evento)
                            {
                                $this->tpl->setCurrentBlock('blkEventos');
                                $this->tpl->setVariable("valEventos",$evento["eve_id"]);
                                $this->tpl->setVariable("opcEventos",htmlentities($evento["eve_nombre"]));

                                if($this->config["preselect"])
                                    $this->tpl->setVariable("selEventos",($evento["eve_id"]==$this->config["data"]["muestra"]["evento"]["id_evento"]?'selected="selected"':''));
                                $this->tpl->parse('blkEventos');
                            }
                    }

                    $this->tpl->setVariable('valEvento',$this->config["data"]["muestra"]["evento"]["id_evento"]);
                    $this->tpl->setVariable('valDonador',$this->config["data"]["muestra"]["evento"]["donador"]);
                    $this->tpl->setVariable('valConfidencial',$this->config["data"]["muestra"]["evento"]["confidencial"]);
                    $this->tpl->setVariable('valViral',$this->config["data"]["muestra"]["evento"]["carga_viral"]);
                    $this->tpl->setVariable('valIniciosintomas',$this->config["data"]["evento"]["muestra"]["inicio_sintomas"]);
                    $this->tpl->setVariable('valEncuesta',$this->config["data"]["muestra"]["evento"]["encuesta_serologica"]);

                    $this->tpl->setVariable("valMostrarCarga",($this->config["data"]["muestra"]["evento"]["carga_viral"]=='1'?'':'none'));
                    $this->tpl->setVariable("valMostrarSerologica",($this->config["data"]["muestra"]["evento"]["encuesta_serologica"]=='1'?'':'none'));
                    $this->tpl->setVariable("valMostrarRadiosPD",($this->config["data"]["muestra"]["evento"]["donador"]=='1'?'':'none'));

                    //echo '<pre>'; print_r($this->config["data"]); echo '</pre>'; exit;

                    // CHECK CARGA VIRAL
                    if($this->config["data"]["muestra"]["carga_viral_check"]=='on')
                        $this->tpl->setVariable('chkCargaViral', 'checked="checked"');
                    else
                        $this->tpl->setVariable('chkCargaViral', '');

                    // CHECK ENCUESTA SEROLOGICA
                    if($this->config["data"]["muestra"]["encuesta_serologica_check"]=='on')
                        $this->tpl->setVariable('chkEncuestaSerologica', 'checked="checked"');
                    else
                        $this->tpl->setVariable('chkEncuestaSerologica', '');

                    // TIPO DE MUESTRA
                    $tipos = helperCatalogos::getTiposMuestra($this->config["data"]["muestra"]["evento"]["id_evento"]);
                    if(is_array($tipos))
                    {
                            foreach($tipos as $tipo)
                            {
                                $this->tpl->setCurrentBlock('blkTipoMuestra');
                                $this->tpl->setVariable("valTipoMuestra",$tipo["tip_mue_id"]);
                                $this->tpl->setVariable("opcTipoMuestra",htmlentities($tipo["tip_mue_nombre"]));

                                if($this->config["preselect"])
                                    $this->tpl->setVariable("selTipoMuestra",($tipo["tip_mue_id"]==$this->config["data"]["muestra"]["tipo"]?'selected="selected"':''));
                                $this->tpl->parse('blkTipoMuestra');
                            }
                    }
                    $this->tpl->setVariable("valTipoMuestra",$this->config["data"]["muestra"]["tipo"]);

                    // CHECK Donador
                    if($this->config["data"]["muestra"]["chk_donador"]=='on')
                        $this->tpl->setVariable('chkDonador', 'checked="checked"');
                    else
                        $this->tpl->setVariable('chkDonador', '');

                    // CHECK Paciente
                    if($this->config["data"]["muestra"]["chk_paciente"]=='on')
                        $this->tpl->setVariable('chkPaciente', 'checked="checked"');
                    else
                        $this->tpl->setVariable('chkPaciente', '');

                    // MUESTRA RECHAZADA
                    if($this->config["data"]["muestra"]["no_rechazada"]=='on')
                        $this->tpl->setVariable('chkNoRechazada', 'checked="checked"');
                    else
                    {
                        $this->tpl->setVariable('chkNoRechazada', '');
                        $this->tpl->setVariable("mostrarRechazo",'none');
                    }

                    if($this->config["data"]["muestra"]["si_rechazada"]=='on'){
                        $this->tpl->setVariable('chkSiRechazada', 'checked="checked"');
                        $this->tpl->setVariable("mostrarRechazo",'');
                    }
                    else
                    {
                        $this->tpl->setVariable('chkSiRechazada', '');
                        $this->tpl->setVariable("mostrarRechazo",'none');
                    }

                    $this->tpl->setVariable("valRazonRechazo",$this->config["data"]["muestra"]["razon_rechazo"]);
                    
                    // FECHAS
                    $this->tpl->setVariable("valInicioSintomas",$this->config["data"]["muestra"]["fecha_inicio_sintomas"]);
                    $this->tpl->setVariable("valToma",$this->config["data"]["muestra"]["fecha_toma"]);
                    $this->tpl->setVariable("valRecepcion",$this->config["data"]["muestra"]["fecha_recepcion"]);

                    // SEMANA
                    $this->tpl->setVariable("valSE",$this->config["data"]["muestra"]["semana"]);

                    // COMENTARIOS Y OBSERVACIONES
                    $this->tpl->setVariable("textComentarios",$this->config["data"]["muestra"]["comentarios_adicionales"]);
                    $this->tpl->setVariable("textAntecedentes",$this->config["data"]["muestra"]["antecedentes"]);
                    $this->tpl->setVariable("textResultadosExternos",$this->config["data"]["muestra"]["resultados_externos"]);                   
                }

                switch($this->config["data"]["muestra"]["hospitalario"])
                {
                    case 0:
                        $this->tpl->setVariable("selNormal"," selected ");
                        $this->tpl->setVariable("imagenEstado",'');
                        break;
                    case 1:
                        $this->tpl->setVariable("selHospitalizado"," selected ");
                        $this->tpl->setVariable("imagenEstado", '<img src="{urlprefix}img/alerta_naranja.png" alt="HOSPITALIZADO" width="16" height="16"/>');
                        break;
                    case 2:
                        $this->tpl->setVariable("selFallecido", " selected ");
                        $this->tpl->setVariable("imagenEstado",'<img src="{urlprefix}img/alerta_rojo.png" alt="FALLECIDO" width="16" height="16"/>');
                        break;
                }

                // Muestra si ocurrió un error
                $this->tpl->setVariable("disError",($this->config['error']? '':'none'));
		$this->tpl->parse('contentBlock');
	}
}

?>
