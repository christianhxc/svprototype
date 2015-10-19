<?php

require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
//require_once ('libs/dal/vih/MuestraSilab.php');

class formNotic extends page {
    
    public $config;

    function __construct($data = null) {
        $this->config = $data;
        parent::__construct($data);
    }

    public function parseContent() {
        date_default_timezone_set("America/Panama");
        header("Content-Type: text/html;charset=utf-8");
        $lectura = false;
        if ($this->config["action"] == "N")
            $nuevo = true;
        else
            $nuevo = false;
        if ($this->config["action"] == "R" || $this->config["action"] == "M") {
            $lectura = true;
        }
        //echo "hola";
        $this->tpl->setVariable("action", $this->config["action"]);
        
        $this->tpl->addBlockFile('CONTENT', 'contentBlock', Configuration::templatesPath . 'notic/formulario.tpl.html');
        $this->tpl->setVariable('urlPrefix', Configuration::getUrlprefix());

        // ESTADO DE LA MUESTRA
//                $this->tpl->setVariable('estadoMuestra',$this->config["data"]["SIT_ID"]);
//                $this->tpl->setVariable('idMuestra',$this->config["data"]["MUE_ID"]);                

        $this->tpl->setVariable("disInfo", $this->config["info"] != "" ? '' : 'none');
        $this->tpl->setVariable("desInfo", $this->config["info"]);

        if ($this->config["error"]) {
            $this->tpl->setVariable('mensajeError', '');
            $this->tpl->setVariable('mostrarError', '');
            $this->tpl->setVariable('valError', $this->config["mensaje"]);
        } else {
            $this->tpl->setVariable('mensajeError', 'none');
            $this->tpl->setVariable('mostrarError', 'none');
            $this->tpl->setVariable('valError', '');
        }
        // Carga catálogo de razones de tipo Id
        
        $tiposId = $this->config["catalogos"]["tipoId"];
        //print_r($tiposId);exit;
        if (is_array($tiposId)) {
            foreach ($tiposId as $tipoId) {
                $this->tpl->setCurrentBlock('blkTipoId');
                $this->tpl->setVariable("valTipoId", $tipoId["id_tipo_identidad"]);
                $this->tpl->setVariable("opcTipoId", htmlentities($tipoId["nombre_tipo"]));
                if (isset($this->config["read"]["tipo_identificacion"]))
                    $this->tpl->setVariable("selTipoId", ($tipoId["id_tipo_identidad"] == $this->config["read"]["tipo_identificacion"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selTipoId", ($tipoId["id_tipo_identidad"] == $this->config["data"]["individuo"]["tipoId"]) ? 'selected="selected"' : '');

                $this->tpl->parse('blkTipoId');
            }
        }
              
        $paises = $this->config["catalogos"]["paises"];
        if (is_array($paises)) {
            foreach ($paises as $pais) {
                $this->tpl->setCurrentBlock('blkPais');
                $this->tpl->setVariable("valPais", $pais["pais"]);
                $this->tpl->setVariable("opcPais", htmlentities($pais["descripcionPais"]));
                if ($nuevo)
                    $this->tpl->setVariable("selPais", ($pais["pais"] == 174 ? 'selected="selected"' : ''));
                else if (isset($this->config["read"]["per_id_pais"]))
                    $this->tpl->setVariable("selPais", ($pais["pais"] == $this->config["read"]["per_id_pais"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selPais", ($pais["pais"] == $this->config["data"]["individuo"]["pais"] ? 'selected="selected"' : ''));
                $this->tpl->parse('blkPais');
                
                $this->tpl->setCurrentBlock('blkPaisConta');
                $this->tpl->setVariable("valPaisConta", $pais["pais"]);
                $this->tpl->setVariable("opcPaisConta", htmlentities($pais["descripcionPais"]));
                if ($nuevo)
                    $this->tpl->setVariable("selPaisConta", ($pais["pais"] == 174 ? 'selected="selected"' : ''));
                else if (isset($this->config["read"]["id_pais_contagio"]))
                    $this->tpl->setVariable("selPaisConta", ($pais["pais"] == $this->config["read"]["id_pais_contagio"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selPaisConta", ($pais["pais"] == $this->config["data"]["individuo"]["pais_conta"] ? 'selected="selected"' : ''));
                $this->tpl->parse('blkPaisConta');
            }
        }

        $provincias = $this->config["catalogos"]["provincias"];
        if (is_array($provincias)) {
            foreach ($provincias as $provincia) {
                $this->tpl->setCurrentBlock('blkProIndividuo');
                $this->tpl->setVariable("valProIndividuo", $provincia["provincia"]);
                $this->tpl->setVariable("opcProIndividuo", htmlentities($provincia["descripcionProvincia"]));
                if (isset($this->config["read"]["id_provincia"]))
                    $this->tpl->setVariable("selProIndividuo", ($provincia["id_provincia"] == $this->config["read"]["id_provincia"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selProIndividuo", ($provincia["id_provincia"] == $this->config["data"]["individuo"]["idProvincia"] ? 'selected="selected"' : ''));
                $this->tpl->parse('blkProIndividuo');
                
                $this->tpl->setCurrentBlock('blkProConta');
                $this->tpl->setVariable("valProConta", $provincia["provincia"]);
                $this->tpl->setVariable("opcProConta", htmlentities($provincia["descripcionProvincia"]));
                if (isset($this->config["read"]["id_pro_conta"]))
                    $this->tpl->setVariable("selProConta", ($provincia["provincia"] == $this->config["read"]["id_pro_conta"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selProConta", ($provincia["provincia"] == $this->config["data"]["individuo"]["provincia_conta"] ? 'selected="selected"' : ''));
                $this->tpl->parse('blkProConta');
            }
        }
                
        $servicios = $this->config["catalogos"]["servicios"];
        if (is_array($servicios)) {
            foreach ($servicios as $servicio) {
                $this->tpl->setCurrentBlock('blkServicio');
                $this->tpl->setVariable("valServicio", $servicio["id_servicio"]);
                $this->tpl->setVariable("opcServicio", htmlentities($servicio["nombre_servicio"]));
                if (isset($this->config["read"]["id_servicio"]))
                    $this->tpl->setVariable("selServicio", ($servicio["id_servicio"] == $this->config["read"]["id_servicio"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selServicio", ($servicio["id_servicio"] == $this->config["data"]["notificacion"]["servicio"] ? 'selected="selected"' : ''));
                else if ($servicio["id_servicio"] == 14)
                    $this->tpl->setVariable("selServicio", 'selected="selected"');
                $this->tpl->parse('blkServicio');
            }
        }
        
        $cargos = $this->config["catalogos"]["cargos"];
        if (is_array($cargos)) {
            foreach ($cargos as $cargo) {
                $this->tpl->setCurrentBlock('blkCargo');
                $this->tpl->setVariable("valCargo", $cargo["id_cargo"]);
                $this->tpl->setVariable("opcCargo", htmlentities($cargo["nombre_cargo"]));
                if (isset($this->config["read"]["id_cargo"]))
                    $this->tpl->setVariable("selCargo", ($cargo["id_cargo"] == $this->config["read"]["id_cargo"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selCargo", ($cargo["id_cargo"] == $this->config["data"]["notificacion"]["cargo"] ? 'selected="selected"' : ''));

                $this->tpl->parse('blkCargo');
            }
        }
        
        $signosSintomas = $this->config["catalogos"]["signosSintomas"];
        if (is_array($signosSintomas)) {
            foreach ($signosSintomas as $signoSintoma) {
                $this->tpl->setCurrentBlock('blkSignos');
                $this->tpl->setVariable("valSignos", $signoSintoma["id_sintoma"]);
                $this->tpl->setVariable("opcSignos", htmlentities($signoSintoma["nombre_sintoma"]));
                $this->tpl->parse('blkSignos');
            }
        }
        
        $tiposMuestras = $this->config["catalogos"]["tipoMuestras"];
        if (is_array($tiposMuestras)) {
            foreach ($tiposMuestras as $tipoMuestra) {
                $this->tpl->setCurrentBlock('blkTipoMuestra');
                $this->tpl->setVariable("valTipoMuestra", $tipoMuestra["id_tipo_muestra"]);
                $this->tpl->setVariable("opcTipoMuestra", htmlentities($tipoMuestra["nombre_tipo_muestra"]));
                if (isset($this->config["read"]["id_tipo_muestra"]))
                    $this->tpl->setVariable("selTipoMuestra", ($tipoMuestra["id_tipo_muestra"] == $this->config["read"]["id_tipo_muestra"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selTipoMuestra", ($tipoMuestra["id_tipo_muestra"] == $this->config["data"]["clinica"]["tipo_muestra"] ? 'selected="selected"' : ''));

                $this->tpl->parse('blkTipoMuestra');
            }
        }
        
        if ($nuevo) {
            
            $nombreUsuario = htmlentities(clsCaus::obtenerNombres()) . " " . htmlentities(clsCaus::obtenerApellidos());
            if (isset($this->config["data"]["notificacion"]["nombreRegistra"]))
                $nombreUsuario = $this->config["data"]["notificacion"]["nombreRegistra"];
            $this->tpl->setVariable('valNombreRegistra', $nombreUsuario);
            //echo "vamos";exit;
            $fechaFormulario = date("d/m/Y");
            $this->tpl->setVariable('valFormHora', date('g'));
            $this->tpl->setVariable('valFormMinutos', date('H'));
            
            if (date('a') == "am") {
                $this->tpl->setVariable('chkFormTipoHoraAM', 'checked="checked"');
            } else {
                $this->tpl->setVariable('chkFormTipoHoraPM', 'checked="checked"');
            }
            if (isset($this->config["data"]["notificacion"]["fecha_formulario"]))
                $fechaFormulario = $this->config["data"]["notificacion"]["fecha_formulario"];
            
            $this->tpl->setVariable('valFecForm', $fechaFormulario);
            
            
        } else if ($lectura) {
            $this->tpl->setVariable('action', 'M');
            $this->tpl->setVariable('valIdForm', $this->config['read']['id_notic']);
            $this->tpl->setVariable('valReadOnly', 'readonly="readonly" disabled="disabled"');
            //print_r($this->config['read']);exit;
            
            //Individuo
            $asegurado = $this->config['read']['per_asegurado'];
            switch ($asegurado) {
                case '0': $this->tpl->setVariable('chkAseguradoNo', 'checked="checked"'); break;
                case '1': $this->tpl->setVariable('chkAseguradoSi', 'checked="checked"'); break;
                case '2': $this->tpl->setVariable('chkAseguradoNoSabe', 'checked="checked"'); break;
            }
            $this->tpl->setVariable('valIdentificador', $this->config['read']['numero_identificacion']);
            if ($this->config["read"]["tipo_identificacion"]==1){
                $arrayIdentificador = explode("-",$this->config['read']['numero_identificacion']);
                $this->tpl->setVariable('valIdentificador1', $arrayIdentificador[0]);
                $this->tpl->setVariable('valIdentificador2', $arrayIdentificador[1]);
                $this->tpl->setVariable('valIdentificador3', $arrayIdentificador[2]);
            }
            $this->tpl->setVariable('valPrimerNombre', htmlentities($this->config['read']['primer_nombre']));
            $this->tpl->setVariable('valSegundoNombre', htmlentities($this->config['read']['segundo_nombre']));
            $this->tpl->setVariable('valPrimerApellido', htmlentities($this->config['read']['primer_apellido']));
            $this->tpl->setVariable('valSegundoApellido', htmlentities($this->config['read']['segundo_apellido']));
            if($this->config['read']['fecha_nacimiento'] != '0000-00-00' && $this->config['read']['fecha_nacimiento'] != '')
                $this->tpl->setVariable('valFechaNacimiento', helperString::toDateView($this->config['read']['fecha_nacimiento']));
            $this->tpl->setVariable('valEdad', $this->config['read']['per_edad']);
            $tipoEdad = $this->config['read']['per_tipo_edad'];
            switch ($tipoEdad) {
                case '1': $this->tpl->setVariable('selDias', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selMeses', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selAnios', 'selected="selected"'); break;
                case '4': $this->tpl->setVariable('selHoras', 'selected="selected"'); break;
                case '5': $this->tpl->setVariable('selMinutos', 'selected="selected"'); break;
            }
            $sexo = $this->config['read']['sexo'];
            switch ($sexo) {
                case 'M': $this->tpl->setVariable('selSexoM', 'selected="selected"'); break;
                case 'F': $this->tpl->setVariable('selSexoF', 'selected="selected"'); break;
            }
            $this->tpl->setVariable('valNombreResponsable', $this->config['read']['nombre_responsable']);
            $lugarConta = $this->config['read']['per_contagio'];
            switch ($lugarConta) {
                case '1': $this->tpl->setVariable('selConta1', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selConta2', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selConta3', 'selected="selected"'); break;
                case '4': $this->tpl->setVariable('selConta4', 'selected="selected"'); break;
                case '5': $this->tpl->setVariable('selConta5', 'selected="selected"'); break;
            }   
            $this->tpl->setVariable('valNombreConta', $this->config['read']['per_nombre_contagio']);
            $this->tpl->setVariable('valIdProConta', $this->config['read']['id_reg_conta']);
            $this->tpl->setVariable('valIdRegConta', $this->config['read']['id_pro_conta']);
            $this->tpl->setVariable('valIdDisConta', $this->config['read']['id_dis_conta']);
            $this->tpl->setVariable('valIdCorConta', $this->config['read']['id_cor_conta']);
            $this->tpl->setVariable('valReferenciaConta', $this->config['read']['punto_referencia_contagio']);
            
            //Datos clinicos epidemiologicos
            $this->tpl->setVariable('valEvento1', $this->config['read']['cie1'].$this->config['read']['nom_eve1']);
            $this->tpl->setVariable('valEvento1Nombre', $this->config['read']['cie1'].$this->config['read']['nom_eve1']);
            $this->tpl->setVariable('valEvento1Id', $this->config['read']['id_eve1']);
            $estadoEve1 = $this->config['read']['estado_diag1'];
            switch ($estadoEve1) {
                case '1': $this->tpl->setVariable('selEve1Sos', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selEve1Con', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selEve1Des', 'selected="selected"'); break;
            }
            $this->tpl->setVariable('valEvento2', $this->config['read']['cie2'].$this->config['read']['nom_eve2']);
            $this->tpl->setVariable('valEvento2Nombre', $this->config['read']['cie2'].$this->config['read']['nom_eve2']);
            $this->tpl->setVariable('valEvento2Id', $this->config['read']['id_eve2']);
            $estadoEve2 = $this->config['read']['estado_diag2'];
            switch ($estadoEve2) {
                case '1': $this->tpl->setVariable('selEve2Sos', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selEve2Con', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selEve2Des', 'selected="selected"'); break;
            }
            $this->tpl->setVariable('valEvento3', $this->config['read']['cie3'].$this->config['read']['nom_eve3']);
            $this->tpl->setVariable('valEvento3Nombre', $this->config['read']['cie3'].$this->config['read']['nom_eve3']);
            $this->tpl->setVariable('valEvento3Id', $this->config['read']['id_eve3']);
            $estadoEve3 = $this->config['read']['estado_diag3'];
            switch ($estadoEve3) {
                case '1': $this->tpl->setVariable('selEve3Sos', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selEve3Con', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selEve3Des', 'selected="selected"'); break;
            }
            
            $condicion = $this->config['read']['condicion'];
            switch ($condicion) {
                case '1': $this->tpl->setVariable('chkClinica1', 'checked="checked"'); break;
                case '2': $this->tpl->setVariable('chkClinica2', 'checked="checked"'); break;
                case '3': $this->tpl->setVariable('chkClinica3', 'checked="checked"'); break;
                case '4': $this->tpl->setVariable('chkClinica4', 'checked="checked"'); break;
            }
            $this->tpl->setVariable('valFechaInicioSintomas', helperString::toDateView($this->config['read']['fecha_inicio_sintomas']));
            $this->tpl->setVariable('valSemanaEpi', $this->config['read']['semana_epi']);
            $this->tpl->setVariable('valAnioEpi', $this->config['read']['anio']);
            $this->tpl->setVariable('valFechaHospitalizacion', helperString::toDateView($this->config['read']['fecha_hospitalizacion']));
            $this->tpl->setVariable('valFechaDefuncion', helperString::toDateView($this->config['read']['fecha_defuncion']));
            $this->tpl->setVariable('valFechaMuestra', helperString::toDateView($this->config['read']['fecha_toma_muestra']));
            $criterio = $this->config['read']['criterio_caso_confirmado'];
            switch ($criterio) {
                case '1': $this->tpl->setVariable('selCriterio1', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selCriterio2', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selCriterio3', 'selected="selected"'); break;
                case '4': $this->tpl->setVariable('selCriterio4', 'selected="selected"'); break;
            }
            
            //Notificacion
            $this->tpl->setVariable('valNotificacionUnidad', $this->config['read']['nombre_un']);
            $this->tpl->setVariable('valNotificacionIdUn', $this->config['read']['id_un']);
            $this->tpl->setVariable('valTelefonoInvestigador', $this->config['read']['telefono']);
            $this->tpl->setVariable('valFechaRegional', helperString::toDateView($this->config['read']['fecha_regional']));
            $this->tpl->setVariable('valNombreInvestigador', $this->config['read']['nombre_reporta']);
            $this->tpl->setVariable('valNombreRegistra', $this->config['read']['nombre_registra']);
            $this->tpl->setVariable('valFechaNotificacion', helperString::toDateView($this->config['read']['fecha_notificacion']));
            $this->tpl->setVariable('valFecForm', helperString::toDateView($this->config['read']['fecha_formulario']));
            $this->tpl->setVariable('valNumeroForm', $this->config['read']['id_notic']);
            if (isset($this->config['read']['hora_formulario'])){
                $varHoraForm = $this->config['read']['hora_formulario'];
                $arrayHoraForm = explode(":",$varHoraForm);
                $horario = 1; // am: 1 pm:0
                if ($arrayHoraForm[0]>12){
                    $horario = 0;
                    $arrayHoraForm[0] = $arrayHoraForm[0]-12;
                }
                $this->tpl->setVariable('valFormHora', $arrayHoraForm[0]);
                $this->tpl->setVariable('valFormMinutos', $arrayHoraForm[1]);
                ($horario == 0)? $this->tpl->setVariable('chkFormTipoHoraPM', 'checked="checked"') : $this->tpl->setVariable('chkFormTipoHoraAM', 'checked="checked"');               
            }
            $this->tpl->setVariable('valComentarios', $this->config['read']['comentario']);
            
            //Signos y Sintomas
            $sintomaTotal = "";
            if (isset($this->config['read']['sintomas'])) {
                foreach ($this->config['read']['sintomas'] as $sintoma) {
                    $sintomaTotal .= $sintoma['id_sintoma']."#-#".$sintoma['nombre_sintoma']."#-#".$sintoma['fecha_sintoma']."###";
                }
                $sintomaTotal = substr($sintomaTotal, 0,strlen($sintomaTotal)-3 );
                $this->tpl->setVariable('valSignoSintomaRelacionados', $sintomaTotal);
            }
        }

        // Muestra si ocurrió un error
        $this->tpl->setVariable("mensajeErrorGeneral", $this->config['Merror']);
        $this->tpl->setVariable("disError", (isset($this->config['Merror']) ? '' : 'none'));

        // Muestra botones GUARDAR y CANCELAR según permisos
        require_once ('libs/caus/clsCaus.php');
        if (!$lectura) {
            if (clsCaus::validarSeccion(ConfigurationCAUS::noticFormulario, ConfigurationCAUS::Agregar))
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarNotic();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
        }
        else {
            if (clsCaus::validarSeccion(ConfigurationCAUS::noticFormulario, ConfigurationCAUS::Modificar))
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarNotic();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Actualizar</a>&nbsp;');
        }
        if (clsCaus::validarSeccion(ConfigurationCAUS::noticFormulario, ConfigurationCAUS::Consultar))
            $this->tpl->setVariable("botonCancelar", '<a href="index.php" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');

        $this->tpl->touchBlock('contentBlock');
    }
    
    function decode($string) 
    { 
        if(mb_detect_encoding($string, 'UTF-8', true)) 
           return utf8_decode(stripcslashes($string)); 
       else 
           return htmlspecialchars(stripcslashes($string)); 
    } 

}



?>
