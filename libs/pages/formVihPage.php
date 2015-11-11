<?php

require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
//require_once ('libs/dal/vih/MuestraSilab.php');

class formVih extends page {

    public $config;

    function __construct($data = null) {
        $this->config = $data;
        parent::__construct($data);
    }

    public function parseContent() {
        $lectura = false;
        if ($this->config["action"] == "N")
            $nuevo = true;
        else
            $nuevo = false;
        if ($this->config["action"] == "R" || $this->config["action"] == "M") {
            $lectura = true;
        }
        $this->tpl->setVariable("action", $this->config["action"]);

        $this->tpl->addBlockFile('CONTENT', 'contentBlock', Configuration::templatesPath . 'vih/formulario.tpl.html');
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
        if (is_array($tiposId)) {
            foreach ($tiposId as $tipoId) {
                $this->tpl->setCurrentBlock('blkTipoId');
                $this->tpl->setVariable("valTipoId", $tipoId["id_tipo_identidad"]);
                $this->tpl->setVariable("opcTipoId", htmlentities($tipoId["nombre_tipo"]));
                if (isset($this->config["read"]["id_tipo_identidad"]))
                    $this->tpl->setVariable("selTipoId", ($tipoId["id_tipo_identidad"] == $this->config["read"]["id_tipo_identidad"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selTipoId", ($tipoId["id_tipo_identidad"] == $this->config["data"]["individuo"]["tipoId"]) ? 'selected="selected"' : '');

                $this->tpl->parse('blkTipoId');
            }
        }

        foreach ($this->config["catalogos"]["factores"] as $factor) {
            $this->tpl->setCurrentBlock('blkFactoresVih');
            $this->tpl->setVariable($factor);
            $this->tpl->setVariable('chkFactorVih',$factor['sel'] != "-1" ? 'checked="checked"' : '');
            $this->tpl->parse('blkFactoresVih');
        }

        foreach ($this->config["catalogos"]["modos"] as $factor) {
            $this->tpl->setCurrentBlock('blkFactoresModosVih');
            $this->tpl->setVariable($factor);
            $this->tpl->setVariable('chkFactorVih',$factor['sel'] != "-1" ? 'checked="checked"' : '');
            $this->tpl->parse('blkFactoresModosVih');
        }
        
        $gruposFactorRiesgo = $this->config["catalogos"]["grupoFactorRiesgo"];
        if (is_array($gruposFactorRiesgo)) {
            foreach ($gruposFactorRiesgo as $grupoFactorRiesgo) {
                $this->tpl->setCurrentBlock('blkFactorRiesgo');
                $this->tpl->setVariable("valFactorRiesgo", $grupoFactorRiesgo["id_grupo_factor"]);
                $this->tpl->setVariable("opcFactorRiesgo", htmlentities($grupoFactorRiesgo["grupo_factor_nombre"]));
                $this->tpl->parse('blkFactorRiesgo');
            }
        }
        $etnias = $this->config["catalogos"]["etnia"];
        if (is_array($etnias)) {
            foreach ($etnias as $etnia) {
                $this->tpl->setCurrentBlock('blkEtnia');
                $this->tpl->setVariable("valEtnia", $etnia["id_etnia"]);
                $this->tpl->setVariable("opcEtnia", htmlentities($etnia["nombre_etnia"]));
                if ($this->config["preselect"])
                    $this->tpl->setVariable("selEtnia", ($etnia["id_etnia"] == $this->config["data"]["individuo"]["etnia"] ? 'selected="selected"' : ''));

                $this->tpl->parse('blkEtnia');
            }
        }
        
        $generos = $this->config["catalogos"]["genero"];
        if (is_array($generos)) {
            foreach ($generos as $genero) {
                $this->tpl->setCurrentBlock('blkGenero');
                $this->tpl->setVariable("valGenero", $genero["id_genero"]);
                $this->tpl->setVariable("opcGenero", htmlentities($genero["genero_nombre"]));
                if ($this->config["preselect"])
                    $this->tpl->setVariable("selGenero", ($genero["id_genero"] == $this->config["data"]["individuo"]["genero"] ? 'selected="selected"' : ''));

                $this->tpl->parse('blkGenero');
            }
        }

        $provincias = $this->config["catalogos"]["provincias"];
        if (is_array($provincias)) {
            foreach ($provincias as $provincia) {
                $this->tpl->setCurrentBlock('blkProIndividuo');
                $this->tpl->setVariable("valProIndividuo", $provincia["provincia"]);
                $this->tpl->setVariable("opcProIndividuo", htmlentities($provincia["descripcionProvincia"]));

                if ($this->config["preselect"])
                    $this->tpl->setVariable("selProIndividuo", ($provincia["id_provincia"] == $this->config["data"]["individuo"]["idProvincia"] ? 'selected="selected"' : ''));

                $this->tpl->parse('blkProIndividuo');

                $this->tpl->setCurrentBlock('blkProIndividuoDiagnostico');
                $this->tpl->setVariable("valProIndividuo", $provincia["provincia"]);
                $this->tpl->setVariable("opcProIndividuo", htmlentities($provincia["descripcionProvincia"]));

                if ($this->config["preselect"])
                    $this->tpl->setVariable("selProIndividuo", ($provincia["id_provincia"] == $this->config["data"]["individuo"]["idProvinciaDiagnostico"] ? 'selected="selected"' : ''));

                $this->tpl->parse('blkProIndividuoDiagnostico');
            }
        }
        if (isset($this->config["data"]["individuo"]["idProvincia"])) {
            $regiones = $this->config["catalogos"]["regiones"];
            if (is_array($regiones)) {
                foreach ($regiones as $region) {
                    $this->tpl->setCurrentBlock('blkRegIndividuo');
                    $this->tpl->setVariable("valRegIndividuo", $region["id_region"]);
                    $this->tpl->setVariable("opcRegIndividuo", htmlentities($region["nombre_region"]));

                    if ($this->config["preselect"])
                        $this->tpl->setVariable("selRegIndividuo", ($region["id_region"] == $this->config["data"]["individuo"]["idRegion"] ? 'selected="selected"' : ''));

                    $this->tpl->parse('blkRegIndividuo');
                }
            }
        }
        if (isset($this->config["data"]["individuo"]["idRegionDiagnostico"])) {
            $regiones = $this->config["catalogos"]["regiones"];
            if (is_array($regiones)) {
                foreach ($regiones as $region) {
                    $this->tpl->setCurrentBlock('blkRegIndividuoDiagnostico');
                    $this->tpl->setVariable("valRegIndividuo", $region["id_region"]);
                    $this->tpl->setVariable("opcRegIndividuo", htmlentities($region["nombre_region"]));

                    if ($this->config["preselect"])
                        $this->tpl->setVariable("selRegIndividuo", ($region["id_region"] == $this->config["data"]["individuo"]["idRegionDiagnostico"] ? 'selected="selected"' : ''));

                    $this->tpl->parse('blkRegIndividuoDiagnostico');
                }
            }
        }
        if (isset($this->config["data"]["individuo"]["idProvincia"])) {
            $distritos = $this->config["catalogos"]["distritos"];
            if (is_array($distritos)) {
                foreach ($distritos as $distrito) {
                    $this->tpl->setCurrentBlock('blkDisIndividuo');
                    $this->tpl->setVariable("valDisIndividuo", $distrito["id_distrito"]);
                    $this->tpl->setVariable("opcDisIndividuo", htmlentities($distrito["nombre_distrito"]));

                    if ($this->config["preselect"])
                        $this->tpl->setVariable("selDisIndividuo", ($distrito["id_distrito"] == $this->config["data"]["individuo"]["idDistrito"] ? 'selected="selected"' : ''));

                    $this->tpl->parse('blkDisIndividuo');
                }
            }
        }

        if (isset($this->config["data"]["individuo"]["idProvinciaDiagnostico"])) {
            $distritos = $this->config["catalogos"]["distritos"];
            if (is_array($distritos)) {
                foreach ($distritos as $distrito) {
                    $this->tpl->setCurrentBlock('blkDisIndividuoDiagnostico');
                    $this->tpl->setVariable("valDisIndividuo", $distrito["id_distrito"]);
                    $this->tpl->setVariable("opcDisIndividuo", htmlentities($distrito["nombre_distrito"]));

                    if ($this->config["preselect"])
                        $this->tpl->setVariable("selDisIndividuo", ($distrito["id_distrito"] == $this->config["data"]["individuo"]["idDistritoDiagnostico"] ? 'selected="selected"' : ''));

                    $this->tpl->parse('blkDisIndividuoDiagnostico');
                }
            }
        }

        if (isset($this->config["data"]["individuo"]["idDistrito"])) {
            $corregimientos = $this->config["catalogos"]["corregimientos"];
            if (is_array($corregimientos)) {
                foreach ($corregimientos as $corregimiento) {
                    $this->tpl->setCurrentBlock('blkCorIndividuo');
                    $this->tpl->setVariable("valCorIndividuo", $corregimiento["id_corregimiento"]);
                    $this->tpl->setVariable("opcCorIndividuo", htmlentities($corregimiento["nombre_corregimiento"]));

                    if ($this->config["preselect"])
                        $this->tpl->setVariable("selCorIndividuo", ($corregimiento["id_corregimiento"] == $this->config["data"]["individuo"]["idCorregimiento"] ? 'selected="selected"' : ''));

                    $this->tpl->parse('blkCorIndividuo');
                }
            }
        }

        if (isset($this->config["data"]["individuo"]["idDistritoDiagnostico"])) {
            $corregimientos = $this->config["catalogos"]["corregimientos"];
            if (is_array($corregimientos)) {
                foreach ($corregimientos as $corregimiento) {
                    $this->tpl->setCurrentBlock('blkCorIndividuoDiagnostico');
                    $this->tpl->setVariable("valCorIndividuo", $corregimiento["id_corregimiento"]);
                    $this->tpl->setVariable("opcCorIndividuo", htmlentities($corregimiento["nombre_corregimiento"]));

                    if ($this->config["preselect"])
                        $this->tpl->setVariable("selCorIndividuo", ($corregimiento["id_corregimiento"] == $this->config["data"]["individuo"]["idCorregimientoDiagnostico"] ? 'selected="selected"' : ''));

                    $this->tpl->parse('blkCorIndividuoDiagnostico');
                }
            }
        }
        
        $clinicas = $this->config["catalogos"]["clinicas"];
        if (is_array($clinicas)) {
            foreach ($clinicas as $clinica) {
                $this->tpl->setCurrentBlock('blkClinica');
                $this->tpl->setVariable("valClinica", $clinica["id_clinica_tarv"]);
                $this->tpl->setVariable("opcClinica", htmlentities($clinica["nombre_clinica_tarv"]));
                if ($clinica["id_clinica_tarv"] == $this->config['tarv'][0]['id_clinica_tarv'])
                    $this->tpl->setVariable("selClinica", 'selected="selected"');
                $this->tpl->parse('blkClinica');
            }
        }

        if ($nuevo) {
            $nombreUsuario = htmlentities(clsCaus::obtenerNombres()) . " " . htmlentities(clsCaus::obtenerApellidos());
            if (isset($this->config["data"]["notificacion"]["nombreUsuario"]))
                $nombreUsuario = $this->config["data"]["notificacion"]["nombreUsuario"];
            $this->tpl->setVariable('valNombreRegistra', $nombreUsuario);
            $fechaFormulario = date("d/m/Y");
            if (isset($this->config["data"]["notificacion"]["fecha_formulario"]))
                $fechaFormulario = $this->config["data"]["notificacion"]["fecha_formulario"];
            $this->tpl->setVariable('valFecFormVih', $fechaFormulario);
            $this->tpl->setVariable('otraInstalacion', 'none');
            $this->tpl->setVariable('valNotificacionOtraUnidad', '');
            $this->tpl->setVariable('valDatoSilab', 0);
            $this->tpl->setVariable('hideResDiagnostico', 'style="display: none;"');

            
        } else if ($lectura) {
            $this->tpl->setVariable('action', 'M');
            $this->tpl->setVariable('valIdVihForm', $this->config['read']['id_vih_form']);
            //print_r($this->config['read']);exit;
            //
            //Individuo
            $asegurado = $this->config['read']['per_asegurado'];
            switch ($asegurado) {
                case '0':
                    $this->tpl->setVariable('chkAseguradoNo', 'checked="checked"');
                    break;
                case '1':
                    $this->tpl->setVariable('chkAseguradoSi', 'checked="checked"');
                    break;
                case '2':
                    $this->tpl->setVariable('chkAseguradoDesc', 'checked="checked"');
                    break;
            }
            
            $this->tpl->setVariable('valIdentificador', $this->config['read']['numero_identificacion']);
            $this->tpl->setVariable('valReadOnly', 'readonly="readonly" disabled="disabled"');
            
            //Datos de comportamiento 
            $ultimoIts = $this->config['read']['comp_its_ultimo'];
            switch ($ultimoIts) {
                case '1':
                    $this->tpl->setVariable('selItsUltimoSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selItsUltimoNo', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selItsUltimoDesc', 'selected="selected"');
                    break;
            }
            if($this->config['read']['comp_its_ultimo']==1){
                $ulcerativa = $this->config['read']['comp_its_ulcerativa'];
                switch ($ulcerativa) {
                    case '1':
                        $this->tpl->setVariable('selItsUlcerativaSi', 'selected="selected"');
                        break;
                    case '2':
                        $this->tpl->setVariable('selItsUlcerativaNo', 'selected="selected"');
                        break;
                    case '3':
                        $this->tpl->setVariable('selItsUlcerativaDesc', 'selected="selected"');
                        break;
                }
            }
            
            $this->tpl->setVariable('valVidaSexual', $this->config['read']['comp_edad_inicio_sexual']);
//            $usoCondon = $this->config['read']['comp_uso_condon'];
//            switch ($usoCondon) {
//                case '1':
//                    $this->tpl->setVariable('selCondonRelSi', 'selected="selected"');
//                    break;
//                case '2':
//                    $this->tpl->setVariable('selCondonRelNo', 'selected="selected"');
//                    break;
//                case '3':
//                    $this->tpl->setVariable('selCondonRelDesc', 'selected="selected"');
//                    break;
//            }
            $trabajadorSexual = $this->config['read']['comp_trabajador_sexual'];
            switch ($trabajadorSexual) {
                case '1':
                    $this->tpl->setVariable('selTrabajoSexualSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selTrabajoSexualNo', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selTrabajoSexualDesc', 'selected="selected"');
                    break;
            }
            
            $donante = $this->config['read']['comp_donante_sangre'];
            switch ($donante) {
                case '1':
                    $this->tpl->setVariable('selDonanteSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selDonanteNo', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selDonanteDesc', 'selected="selected"');
                    break;
            }
            $this->tpl->setVariable('valFechaDonacion', $this->config['read']['comp_donante_fecha']);
            $this->tpl->setVariable('valInstalacion', $this->config['read']['comp_donante_instalacion']);
            $preso= $this->config['read']['comp_per_preso'];
            switch ($preso) {
                case '1':
                    $this->tpl->setVariable('selPresoSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selPresoNo', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selPresoDesc', 'selected="selected"');
                    break;
            }
            $embarazada= $this->config['read']['comp_embarazada'];
            switch ($embarazada) {
                case '1':
                    $this->tpl->setVariable('selEmbarazadaSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selEmbarazadaNo', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selEmbarazadaDesc', 'selected="selected"');
                    break;
            }
            $embCaptada= $this->config['read']['comp_emb_captada'];
            switch ($embCaptada) {
                case '1':
                    $this->tpl->setVariable('selEmbCaptada1', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selEmbCaptada2', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selEmbCaptada3', 'selected="selected"');
                    break;
                case '4':
                    $this->tpl->setVariable('selEmbCaptadaDesc', 'selected="selected"');
                    break;
            }
            $this->tpl->setVariable('valAnioEmb1', $this->config['read']['comp_emb_1']);
            $this->tpl->setVariable('valAnioEmb2', $this->config['read']['comp_emb_2']);
            $this->tpl->setVariable('valAnioEmb3', $this->config['read']['comp_emb_3']);
            
            //Enfermedades oportunistas
            $enfermedadTotal = "";
            if (isset($this->config['enfermedades'])) {
                foreach ($this->config['enfermedades'] as $enfermedad) {
                    $enfermedadTotal .= $enfermedad['id_evento'] . "-" . $enfermedad['cie_10_1']." ".$enfermedad['nombre_evento']."###";
                }
                
                $enfermedadTotal = substr($enfermedadTotal, 0,strlen($enfermedadTotal)-3 );
                $this->tpl->setVariable('valEnfOportunistaRelacionados', $enfermedadTotal);
            }
            
            //Factores de Riesgo
            $factorTotal = "";
            if (isset($this->config['factores'])) {
                foreach ($this->config['factores'] as $factor) {
                    $factor['id_factor'] = ($factor['id_factor']==0)?"-1":$factor['id_factor'];
                    $factorTotal .= $factor['id_grupo_factor']."#-#".$factor['grupo_factor_nombre']."#-#".$factor['id_factor']."#-#".$factor['factor_nombre']."###";
                }
                $factorTotal = substr($factorTotal, 0,strlen($factorTotal)-3 );
                $this->tpl->setVariable('valFactorRiesgoRelacionados', $factorTotal);
            }
            
            //Condicion del paciente
            $condicion= $this->config['read']['cond_condicion_paciente'];
            switch ($condicion) {
                case '1':
                    $this->tpl->setVariable('selCondicionVivo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selCondicionMuerto', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selCondicionDesc', 'selected="selected"');
                    break;
            }

            for ($i = 1; $i <= 3; $i++) {
                $this->tpl->setVariable('valVigilanciaTermino'.$i . $this->config['read']['termino_embarazo_'.$i], 'checked="checked"');
                $this->tpl->setVariable('valVigilanciaCedula'.$i, $this->config['read']['numero_cedula_'.$i]);
                $this->tpl->setVariable('valVigilanciaNombre'.$i, $this->config['read']['nombre_'.$i]);
                $this->tpl->setVariable('valVigilanciaApellido'.$i, $this->config['read']['apellido_'.$i]);
                $this->tpl->setVariable('valVigilanciaNacimiento'.$i, helperString::toDateView($this->config['read']['nacimiento_'.$i]));
                $this->tpl->setVariable('valVigilanciaSexo'.$i . $this->config['read']['sexo_'.$i], 'checked="checked"');
                $this->tpl->setVariable('valVigilanciaPcr1'.$i . $this->config['read']['pcr1_'.$i], 'checked="checked"');
                $this->tpl->setVariable('valVigilanciaPcr2'.$i . $this->config['read']['pcr2_'.$i], 'checked="checked"');
                $this->tpl->setVariable('valVigilanciaFechaPcr1'.$i, helperString::toDateView($this->config['read']['pcr1_fecha_'.$i]));
                $this->tpl->setVariable('valVigilanciaFechaPcr2'.$i, helperString::toDateView($this->config['read']['pcr2_fecha_'.$i]));
            }

            $this->tpl->setVariable('valRazonSida'.$this->config['read']['razon_sida'], 'checked="checked"');
            $this->tpl->setVariable('valCondicionSida', ($this->config['read']['cond_sida'] == '1' ? 'checked="checked"' : ''));
            $this->tpl->setVariable('valFechaSida', helperString::toDateView($this->config['read']['cond_fecha_sida']));
            $this->tpl->setVariable('valEdadSida', $this->config['read']['cond_edad_sida']);
            $this->tpl->setVariable('valCondicionVih', ($this->config['read']['cond_vih'] == '1' ? 'checked="checked"' : ''));
            $this->tpl->setVariable('valFechaVih', helperString::toDateView($this->config['read']['cond_fecha_vih']));
            $this->tpl->setVariable('valEdadVih', $this->config['read']['cond_edad_vih']);
            $this->tpl->setVariable('valSobrevida', $this->config['read']['cond_sobrevida']);
            if($this->config['read']['cond_fecha_defuncion']!="")
                $this->tpl->setVariable('valFechaDefuncion', helperString::toDateView($this->config['read']['cond_fecha_defuncion']));
            
            $this->tpl->setVariable('valLugarDefuncion', $this->config['read']['cond_otro_defuncion']);
            
            if ($this->config['read']['cond_id_un_defuncion']!=null&&$this->config['read']['cond_id_un_defuncion']!="")
                $this->tpl->setVariable('valIdUnDefuncion', $this->config['read']['cond_id_un_defuncion']);
            else if ($this->config['read']['cond_otro_defuncion']!="")
                $this->tpl->setVariable('valOtroDefuncion', 'checked="checked"');
                         
                       
            $this->tpl->setVariable('valSobrevidaSida', $this->config['read']['cond_sobrevida_sida']);
            $this->tpl->setVariable('valLugarDiagnostico', $this->config['read']['cond_lugar_diagnostico']);
            $this->tpl->setVariable('valLugarDiagnosticoSida', $this->config['read']['cond_lugar_diagnostico_sida']);

            if ($this->config["action"] == "R")
                $this->tpl->setVariable('showResDiagnostico', 'readonly="readonly" disabled="disabled"');

            //Notificacion
            $unidadDisponible = $this->config['read']['unidad_disponible'];
            if ($unidadDisponible == '1') {
                $this->tpl->setVariable('valNotificacionUnidad', $this->config['read']['nombre_un']);
                $this->tpl->setVariable('valNotificacionIdUn', $this->config['read']['id_un']);
                $this->tpl->setVariable('valUnidadNoDisponible', '');
                $this->tpl->setVariable('otraInstalacion', 'none');
                $this->tpl->setVariable('valNotificacionOtraUnidad', '');
            } else {
                $this->tpl->setVariable('valNotificacionUnidad', "");
                $this->tpl->setVariable('valNotificacionIdUn', "");
                $this->tpl->setVariable('valUnidadNoDisponible', 'checked="checked"');
                $this->tpl->setVariable('otraInstalacion', '');
                $this->tpl->setVariable('valNotificacionOtraUnidad', $this->config['read']['otro_nombre_un']);
            }
                        
            
            $this->tpl->setVariable('valNombreInvestigador', $this->config['read']['nombre_notifica']);
            $this->tpl->setVariable('valFechaNotificacion', helperString::toDateView($this->config['read']['fecha_notificacion']));
            $this->tpl->setVariable('valSemanaEpi', $this->config['read']['semana_epi']);
            $this->tpl->setVariable('valNotificacionAnio', $this->config['read']['anio']);
            $this->tpl->setVariable('valNombreRegistra', $this->config['read']['nombre_registra']);
            $this->tpl->setVariable('valFecFormVih', helperString::toDateView($this->config['read']['fecha_formulario']));
            $this->tpl->setVariable('valDatoSilab', $this->config['read']['silab']);
            $this->tpl->setVariable('valDatoEpiInfo', $this->config['read']['epiInfo']);
            
            //datos clinica TARV
            
            
            if (isset($this->config['tarv'])){
                //echo "hola ";
                //print_r($this->config['tarv'][0]);exit;
                $this->tpl->setVariable('valFechaTarv', helperString::toDateView($this->config['tarv'][0]['tarv_fec_ingreso']));
                $this->tpl->setVariable('valFechaTarvInicio', helperString::toDateView($this->config['tarv'][0]['tarv_fec_inicio']));
                $this->tpl->setVariable('valFechaCd4', helperString::toDateView($this->config['tarv'][0]['tarv_fec_cd4']));
                $this->tpl->setVariable('valResultadoCd4', $this->config['tarv'][0]['tarv_res_cd4']);
                $this->tpl->setVariable('valRecuento1Cd4', helperString::toDateView($this->config['tarv'][0]['tarv_fec_cd4_350']));
                $this->tpl->setVariable('valResRecuento1Cd4', $this->config['tarv'][0]['tarv_res_cd4_350']);
                $this->tpl->setVariable('valRecuento2Cd4', helperString::toDateView($this->config['tarv'][0]['tarv_fec_cd4_200']));
                $this->tpl->setVariable('valResRecuento2Cd4', $this->config['tarv'][0]['tarv_res_cd4_200']);
                $this->tpl->setVariable('valCargaViral', helperString::toDateView($this->config['tarv'][0]['tarv_fec_carga_viral']));
                $this->tpl->setVariable('valResCargaViral', $this->config['tarv'][0]['tarv_res_carga_viral']);
            }
            
            //require_once ('libs/dal/vih/MuestraSilab.php');
            $muestras = MuestraSilab::construirMuestraSilab($this->config["muestras"]);
            $muestraSplit = explode("###",$muestras);
            if (isset($muestraSplit[1])&&$muestraSplit[1]!=''){
                $this->tpl->setVariable('valEstadoSilab', '<img width=16 height=16 src="../img/iconos/valido.png"> Muestra de SILAB - Actualizada');
                $this->tpl->setVariable('valResultadoSilab',$muestraSplit[0]);
                $this->tpl->setVariable('valGlobalMuestras',$muestraSplit[1]);
                $this->tpl->setVariable('valGlobalPruebas',$muestraSplit[2]);
            }
            else{
                $this->tpl->setVariable('valEstadoSilab', '<img width=16 height=16 src="../img/iconos/pendiente.png"> Sin Muestra de SILAB');
                $this->tpl->setVariable('valResultadoSilab','');
                $this->tpl->setVariable('valGlobalMuestras','');
                $this->tpl->setVariable('valGlobalPruebas','');
            }
        }

        // Muestra si ocurrió un error
        $this->tpl->setVariable("mensajeErrorGeneral", $this->config['Merror']);
        $this->tpl->setVariable("disError", (isset($this->config['Merror']) ? '' : 'none'));

        // Muestra botones GUARDAR y CANCELAR según permisos
        require_once ('libs/caus/clsCaus.php');
        if (!$lectura) {
            if (clsCaus::validarSeccion(ConfigurationCAUS::vihFormulario, ConfigurationCAUS::Agregar))
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarVih();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
        }
        else {
            if (clsCaus::validarSeccion(ConfigurationCAUS::vihFormulario, ConfigurationCAUS::Modificar))
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarVih();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Actualizar</a>&nbsp;');
        }
        if (clsCaus::validarSeccion(ConfigurationCAUS::vihFormulario, ConfigurationCAUS::Consultar))
            $this->tpl->setVariable("botonCancelar", '<a href="index.php" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');

        $this->tpl->touchBlock('contentBlock');
    }

}

?>
