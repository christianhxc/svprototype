<?php

require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

//require_once ('libs/dal/vih/MuestraSilab.php');

class formVicIts extends page {

    public $config;

    function __construct($data = null) {
        $this->config = $data;
        parent::__construct($data);
    }

    public function parseContent() {

        $lectura = false;
        //test
//        $this->tpl->setVariable('drpExaEspeculoSi', 'selected="selected"');

        if ($this->config["action"] == "N")
            $nuevo = true;
        else
            $nuevo = false;
        if ($this->config["action"] == "R" || $this->config["action"] == "M") {
            $lectura = true;
        }
        $this->tpl->setVariable("action", $this->config["action"]);

        $this->tpl->addBlockFile('CONTENT', 'contentBlock', Configuration::templatesPath . 'vicits/formulario_fixed.tpl.html');
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

        $paises = $this->config["catalogos"]["paises"];
        if (is_array($paises)) {
            $flagPais = true;
            foreach ($paises as $pais) {
                $this->tpl->setCurrentBlock('blkPaisIndividuo');
                $this->tpl->setVariable("valPaisIndividuo", $pais["pais"]);
                $this->tpl->setVariable("opcPaisIndividuo", htmlentities($pais["descripcionPais"]));

                if ($this->config["preselect"])
                    $this->tpl->setVariable("selPaisIndividuo", ($pais["pais"] == $this->config["data"]["individuo"]["idPais"] ? 'selected="selected"' : ''));
                else {
                    $this->tpl->setVariable("selPaisIndividuo", ($pais["pais"] == 174 ? 'selected="selected"' : ''));
                }
                $this->tpl->parse('blkPaisIndividuo');

                $this->tpl->setCurrentBlock('blkPaisTS');
                $this->tpl->setVariable("valPaisTS", $pais["pais"]);
                $this->tpl->setVariable("opcPaisTS", htmlentities($pais["descripcionPais"]));

                if ($flagPais) {
                    if ($pais["pais"] == $this->config['read']['antec_ts_id_pais']) {
                        $this->tpl->setVariable("selPaisTS", 'selected="selected"');
                        $flagPais = false;
                    } else {
                        $this->tpl->setVariable("selPaisTS", ($pais["pais"] == 174 ? 'selected="selected"' : ''));
                    }
                }
                $this->tpl->parse('blkPaisTS');
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

        $itses = $this->config["catalogos"]["its"];
        if (is_array($itses)) {
            foreach ($itses as $its) {
                $this->tpl->setCurrentBlock('blkITS');
                $this->tpl->setVariable("valITS", $its["id_ITS"]);
                $this->tpl->setVariable("opcITS", htmlentities($its["nombre_ITS"]));
                $this->tpl->parse('blkITS');
            }
        }

        $drogas = $this->config["catalogos"]["drogas"];
        if (is_array($drogas)) {
            foreach ($drogas as $droga) {
                $this->tpl->setCurrentBlock('blkDrogas');
                $this->tpl->setVariable("valDrogas", $droga["id_droga"]);
                $this->tpl->setVariable("opcDrogas", htmlentities($droga["nombre_droga"]));
                $this->tpl->parse('blkDrogas');
            }
        }

        $clinicas = $this->config["catalogos"]["clinicas"];
        if (is_array($clinicas)) {
            foreach ($clinicas as $clinica) {
                $this->tpl->setCurrentBlock('blkClinica');
                $this->tpl->setVariable("valClinica", $clinica["id_clinica_tarv"]);
                $this->tpl->setVariable("opcClinica", htmlentities($clinica["nombre_clinica_tarv"]));
                if ($clinica["id_clinica_tarv"] == $this->config['read']['id_clinica_tarv'])
                    $this->tpl->setVariable("selClinica", 'selected="selected"');
                $this->tpl->parse('blkClinica');
            }
        }

        if ($nuevo) {
            $nombreUsuario = htmlentities(clsCaus::obtenerNombres()) . " " . htmlentities(clsCaus::obtenerApellidos());
            if (isset($this->config["data"]["notificacion"]["nombreUsuario"]))
                $nombreUsuario = $this->config["data"]["notificacion"]["nombreUsuario"];
            $this->tpl->setVariable('noti_nombre_registra', $nombreUsuario);
            $fechaFormulario = date("d/m/Y");
            if (isset($this->config["data"]["notificacion"]["fecha_formulario"]))
                $fechaFormulario = $this->config["data"]["notificacion"]["fecha_formulario"];
            $this->tpl->setVariable('noti_fecha_form_vicits', $fechaFormulario);
        } else if ($lectura) {
////////////////*******************IMPORTANTE NO BORRAR***********//////////////////////// 
//            echo "Sexo ".$this->config['read']['sexo'];
            switch ($this->config['read']['sexo']) {
                case 'M':
                    $this->tpl->setVariable('selSexoM', 'selected="selected"');
                    break;
                case 'F':
                    $this->tpl->setVariable('selSexoF', 'selected="selected"');
                    break;
            }
////////////////*******************IMPORTANTE NO BORRAR***********////////////////////////            
            $this->tpl->setVariable('action', 'M');
            $this->tpl->setVariable('valIdVicItsForm', $this->config['read']['id_vicits_form']);
            //print_r($this->config['read']);exit;
            //
////////////////******************* begin INDIVIDUO ***********//////////////////////// 

            $this->tpl->setVariable('valIdentificador', $this->config['read']['numero_identificacion']);
            $this->tpl->setVariable('valReadOnly', 'readonly="readonly" disabled="disabled"');
            $this->tpl->setVariable('valNombreIdentidad', $this->config['read']['per_nombre_trans']);
            $sabeLeer = $this->config['read']['per_sabe_leer'];
            switch ($sabeLeer) {
                case '1':
                    $this->tpl->setVariable('selLeeSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selLeeNo', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selLeeDesc', 'selected="selected"');
                    break;
            }

////////////////******************* begin ANTECEDENTES ***********//////////////////////// 
            $abusoSexual = $this->config['read']['antec_abuso_sexual'];
            switch ($abusoSexual) {
                case '1': $this->tpl->setVariable('selASSi', 'selected="selected"');
                    break;
                case '2': $this->tpl->setVariable('selASNo', 'selected="selected"');
                    break;
                case '3': $this->tpl->setVariable('selASDesc', 'selected="selected"');
                    break;
            }
            $this->tpl->setVariable('valEdadAS', $this->config['read']['antec_edad_abuso_sexual']);
            $abusoSexual12 = $this->config['read']['antec_abuso_ultimo'];
            switch ($abusoSexual12) {
                case '1': $this->tpl->setVariable('selAS12Si', 'selected="selected"');
                    break;
                case '2': $this->tpl->setVariable('selAS12No', 'selected="selected"');
                    break;
                case '3': $this->tpl->setVariable('selAS12Desc', 'selected="selected"');
                    break;
            }
            $this->tpl->setVariable('valInicioSexual', $this->config['read']['antec_edad_inicio_sexual']);
            $trabajoSexual = $this->config['read']['antec_ts_alguna_vez'];
            switch ($trabajoSexual) {
                case '1': $this->tpl->setVariable('selTSSi', 'selected="selected"');
                    break;
                case '2': $this->tpl->setVariable('selTSNo', 'selected="selected"');
                    break;
                case '3': $this->tpl->setVariable('selTSDesc', 'selected="selected"');
                    break;
            }
            $TSActual = $this->config['read']['antec_ts_actual'];
            switch ($TSActual) {
                case '1': $this->tpl->setVariable('selActualTSSi', 'selected="selected"');
                    break;
                case '2': $this->tpl->setVariable('selActualTSNo', 'selected="selected"');
                    break;
                case '3': $this->tpl->setVariable('selActualTSDesc', 'selected="selected"');
                    break;
            }
            $TSTiempo = $this->config['read']['antec_ts_tiempo'];
            switch ($TSTiempo) {
                case '1':
                    $this->tpl->setVariable('selTiempoTS1', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selTiempoTS2', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selTiempoTS3', 'selected="selected"');
                    break;
                case '4':
                    $this->tpl->setVariable('selTiempoTS4', 'selected="selected"');
                    break;
            }
            $this->tpl->setVariable('valCuantoTS', $this->config['read']['antec_ts_tiempo_anios']);
            $TSOtroPais = $this->config['read']['antec_ts_otro_pais'];
            switch ($TSOtroPais) {
                case '1':
                    $this->tpl->setVariable('selOtroPaisTSSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selOtroPaisTSNo', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selOtroPaisTSDesc', 'selected="selected"');
                    break;
            }
            $relacionCon = $this->config['read']['antec_relacion'];
            switch ($relacionCon) {
                case '1':
                    $this->tpl->setVariable('selRelSexual1', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selRelSexual2', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selRelSexual3', 'selected="selected"');
                    break;
                case '4':
                    $this->tpl->setVariable('selRelSexual4', 'selected="selected"');
                    break;
            }
            $tuvoITS = $this->config['read']['antec_its_ultimo'];
            switch ($tuvoITS) {
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
            $this->tpl->setVariable('valNombreLugarTS', $this->config['read']['antec_ts_nombre_lugar']);
            $tipoLugarTS = $this->config['read']['antec_ts_tipo_lugar'];
            switch ($tipoLugarTS) {
                case '1':
                    $this->tpl->setVariable('selTipoTS1', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selTipoTS2', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selTipoTS3', 'selected="selected"');
                    break;
                case '4':
                    $this->tpl->setVariable('selTipoTS4', 'selected="selected"');
                    break;
                case '5':
                    $this->tpl->setVariable('selTipoTS5', 'selected="selected"');
                    break;
                case '6':
                    $this->tpl->setVariable('selTipoTS6', 'selected="selected"');
                    break;
                case '7':
                    $this->tpl->setVariable('selTipoTS7', 'selected="selected"');
                    break;
            }

            $tuvoVIH = $this->config['read']['antec_vih'];
            switch ($tuvoVIH) {
                case '1':
                    $this->tpl->setVariable('selVIHSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selVIHNo', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selVIHDesc', 'selected="selected"');
                    break;
            }
            $this->tpl->setVariable('valFechaDiagVih', helperString::toDateView($this->config['read']['antec_fecha_vih']));
            $preVIH = $this->config['read']['antec_consejeria_pre'];
            switch ($preVIH) {
                case '1':
                    $this->tpl->setVariable('selPreVIHSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selPreVIHNo', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selPreVIHDesc', 'selected="selected"');
                    break;
            }
            $posVIH = $this->config['read']['antec_consejeria_post'];
            switch ($posVIH) {
                case '1':
                    $this->tpl->setVariable('selPosVIHSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selPosVIHNo', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selPosVIHDesc', 'selected="selected"');
                    break;
            }
            $clinicaTARV = $this->config['read']['antec_referido_TARV'];
            switch ($clinicaTARV) {
                case '1':
                    $this->tpl->setVariable('selTARVSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selTARVNo', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selTARVDesc', 'selected="selected"');
                    break;
            }
            $alcohol = $this->config['read']['antec_consumo_alcohol'];
            switch ($alcohol) {
                case '1':
                    $this->tpl->setVariable('selConsumoAlcoholSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selConsumoAlcoholNo', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selConsumoAlcoholDesc', 'selected="selected"');
                    break;
            }
            $alcoholSem = $this->config['read']['antec_consumo_alcohol_semana'];
            switch ($alcoholSem) {
                case '1':
                    $this->tpl->setVariable('selFrecAlcohol1', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selFrecAlcohol2', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selFrecAlcohol3', 'selected="selected"');
                    break;
                case '4':
                    $this->tpl->setVariable('selFrecAlcohol4', 'selected="selected"');
                    break;
                case '5':
                    $this->tpl->setVariable('selFrecAlcohol5', 'selected="selected"');
                    break;
                case '6':
                    $this->tpl->setVariable('selFrecAlcoholDesc', 'selected="selected"');
                    break;
            }

            ////////////////////////////////// SOLO MUJERES ///////////////////////////////////
            $anticonceptivo = $this->config['read']['antec_anticonceptivo'];
            switch ($anticonceptivo) {
                case '1':
                    $this->tpl->setVariable('selAnticonceptivoSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selAnticonceptivoNo', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selAnticonceptivoDesc', 'selected="selected"');
                    break;
            }
            $this->tpl->setVariable('valCheckDiu', ($this->config['read']['antec_anticonceptivo_diu'] == '1' ? 'checked="checked"' : ''));
            $this->tpl->setVariable('valCheckPildora', ($this->config['read']['antec_anticonceptivo_pildora'] == '1' ? 'checked="checked"' : ''));
            $this->tpl->setVariable('valCheckEsteril', ($this->config['read']['antec_anticonceptivo_condon'] == '1' ? 'checked="checked"' : ''));
            $this->tpl->setVariable('valCheckCondon', ($this->config['read']['antec_anticonceptivo_inyeccion'] == '1' ? 'checked="checked"' : ''));
            $this->tpl->setVariable('valCheckInyeccion', ($this->config['read']['antec_anticonceptivo_esteriliza'] == '1' ? 'checked="checked"' : ''));
            $this->tpl->setVariable('valCheckOtro', ($this->config['read']['antec_anticonceptivo_otro'] == '1' ? 'checked="checked"' : ''));
            $this->tpl->setVariable('valOntroAnti', $this->config['read']['antec_anticonceptivo_nombre_otro']);

            $this->tpl->setVariable('valGinecoMenarquia', $this->config['read']['antec_obstetrico_menarquia']);
            $this->tpl->setVariable('valGinecoAbortos', $this->config['read']['antec_obstetrico_abortos']);
            $this->tpl->setVariable('valGinecoVivos', $this->config['read']['antec_obstetrico_muertos']);
            $this->tpl->setVariable('valGinecoMuertos', $this->config['read']['antec_obstetrico_vivos']);
            $this->tpl->setVariable('valGinecoEmbarazos', $this->config['read']['antec_obstetrico_total']);

            //ITS relacionadas
            $itsTotal = "";
            if (isset($this->config['its'])) {
                foreach ($this->config['its'] as $its) {
                    $itsTotal .= $its['id_ITS'] . "-" . $its['nombre_ITS'] . "###";
                }

                $itsTotal = substr($itsTotal, 0, strlen($itsTotal) - 3);
                $this->tpl->setVariable('valITSRelacionados', $itsTotal);
            }

            //Consumo de drogas relacionadas
            $drogaTotal = "";
            if (isset($this->config['drogas'])) {
                foreach ($this->config['drogas'] as $droga) {
                    $nombreTiempo = ($droga["fecha_consumo"] == 1) ? "12 meses" : "30 dias";
                    $drogaTotal .= $droga['id_droga'] . "#-#" . $droga['nombre_droga'] . "#-#" . $droga['fecha_consumo'] . "#-#" . $nombreTiempo . "###";
                }
                $drogaTotal = substr($drogaTotal, 0, strlen($drogaTotal) - 3);
                $this->tpl->setVariable('valDrogasRelacionadas', $drogaTotal);
            }


            //Notificacion
//            $unidadDisponible = $this->config['read']['unidad_disponible'];
//            if ($unidadDisponible == '1') {
//                $this->tpl->setVariable('valNotificacionUnidad', $this->config['read']['nombre_un']);
//                $this->tpl->setVariable('valNotificacionIdUn', $this->config['read']['id_un']);
//                $this->tpl->setVariable('valUnidadNoDisponible', '');
//            } else {
//                $this->tpl->setVariable('valNotificacionUnidad', "");
//                $this->tpl->setVariable('valNotificacionIdUn', "");
//                $this->tpl->setVariable('valUnidadNoDisponible', 'checked="checked"');
//            }
//
//
//            $this->tpl->setVariable('valNombreInvestigador', $this->config['read']['nombre_notifica']);
//            $this->tpl->setVariable('valFechaNotificacion', helperString::toDateView($this->config['read']['fecha_notificacion']));
//            $this->tpl->setVariable('valSemanaEpi', $this->config['read']['semana_epi']);
//            $this->tpl->setVariable('valNotificacionAnio', $this->config['read']['anio']);
//            $this->tpl->setVariable('valNombreRegistra', $this->config['read']['nombre_registra']);
//            $this->tpl->setVariable('valFecFormVih', helperString::toDateView($this->config['read']['fecha_formulario']));
/////////////////////////////////////BEGIN PARTE A - PARTE B////////////////////////////////////////////////////////               
            //	[antec_motivo_consulta] => 0 
//        $vicIts["antec_motivo_consulta"] = helperVicIts::validarCombo($data["sintomas"]["antec_motivo_consulta"]);
            switch ($this->config['read']['antec_motivo_consulta']) {
                case '1':
                    $this->tpl->setVariable('drpMotivoConsultaNuevo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpMotivoConsultaMolestias', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('drpMotivoConsultaTrimestral', 'selected="selected"');
                    break;
                case '4':
                    $this->tpl->setVariable('drpMotivoConsultaSemestral', 'selected="selected"');
                    break;
            }
////	[globalSintomasSignosRelacionados] => 1-22 )
            $sintomasSignos = $this->config['signos_sintomas'];
            $sintomaTotal = "";
            if (isset($sintomasSignos)) {
                foreach ($sintomasSignos as $sintomaSigno) {

                    $sintomaTotal .= $sintomaSigno['id_signo_sintoma'] . "#-#" .
                            $sintomaSigno['nombre_signo_sintoma'] . "#-#" .
                            $sintomaSigno['dias'] . "###";
                }
                $sintomaTotal = substr($sintomaTotal, 0, strlen($sintomaTotal) - 3);
//                echo $sintomaTotal."<br/>";
                $this->tpl->setVariable('valSintomasSignosRelacionados', htmlentities($sintomaTotal));
            }

            $antibioticos = $this->config['antibioticos'];
            $antibioticoTotal = "";
            if (isset($antibioticos)) {
                foreach ($antibioticos as $antibiotico) {

                    $antibioticoTotal .= $antibiotico['nombre'] . "#-#" .
                            $antibiotico['nombre'] . "#-#" .
                            $antibiotico['motivo'] . "#-#" .
                            helperString::toDateView($antibiotico['fecha']) . "###";
                }
                $antibioticoTotal = substr($antibioticoTotal, 0, strlen($antibioticoTotal) - 3);
//                echo $antibioticoTotal."<br/>";
                $this->tpl->setVariable('valAntibioticosRelacionados', htmlentities($antibioticoTotal));
            }

            $diagnosticosTratamientos = $this->config['diagnostico_tratamiento'];
            $dxtxTotal = "";
            if (isset($diagnosticosTratamientos)) {
                foreach ($diagnosticosTratamientos as $diagnosticoTratamiento) {

                    $dxtxTotal .= $diagnosticoTratamiento['id_diag_sindromico'] . "#-#" .
                            $diagnosticoTratamiento['id_diag_etiologico'] . "#-#" .
                            $diagnosticoTratamiento['id_tratamiento'] . "#-#" .
                            $diagnosticoTratamiento['nombre_diag_sindromico'] . "#-#" .
                            $diagnosticoTratamiento['nombre_diag_etiologico'] . "#-#" .
                            $diagnosticoTratamiento['nombre_tratamiento'] . "###";
                }
                $dxtxTotal = substr($dxtxTotal, 0, strlen($dxtxTotal) - 3);
//                echo $sintomaTotal."<br/>";
                $this->tpl->setVariable('valDiagnosticosTratamientoRelacionados', htmlentities($dxtxTotal));
            }
////[otros_datos] => Array ( 
////	[otro_antibiotico] => -1 
//        $vicIts["otro_antibiotico"] = helperVicIts::validarCombo($data["otros_datos"]["otro_antibiotico"]);
            switch ($this->config['read']['otro_antibiotico']) {
                case '1':
                    $this->tpl->setVariable('drpAntibioticoSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpAntibioticoNo', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('drpAntibioticoNoSabe', 'selected="selected"');
                    break;
            }
////	[otro_ovulos_vagina] => -1 
//        $vicIts["otro_ovulos_vagina"] = helperVicIts::validarCombo($data["otros_datos"]["otro_ovulos_vagina"]);
            switch ($this->config['read']['otro_ovulos_vagina']) {
                case '1':
                    $this->tpl->setVariable('drpOvulosVaginalesSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpOvulosVaginalesNo', 'selected="selected"');
                    break;
            }
////	[otro_ducha_vagina] => -1 
//        $vicIts["otro_ducha_vagina"] = helperVicIts::validarCombo($data["otros_datos"]["otro_ducha_vagina"]);
            switch ($this->config['read']['otro_ducha_vagina']) {
                case '1':
                    $this->tpl->setVariable('drpDuchasVaginalesSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpDuchasVaginalesNo', 'selected="selected"');
                    break;
            }
////	[otro_fecha_citologia] => 
//        $vicIts["otro_fecha_citologia"] = helperVicIts::validarFecha($data["otros_datos"]["otro_fecha_citologia"]);
            $this->tpl->setVariable('otroFechaCitologia', helperString::toDateView($this->config['read']['otro_fecha_citologia']));
////	[otro_citologia_resultado] => 
//        $vicIts["otro_citologia_resultado"] = helperVicIts::validarString($data["otros_datos"]["otro_citologia_resultado"]);
            $this->tpl->setVariable('otroResultadoCitologia', htmlentities($this->config['read']['otro_citologia_resultado']));
////	[globalAntibioticosRelacionados] => ) 
////[condon] => Array ( 
////	[condon_rel_anal_otro] => -1 
//        $vicIts["condon_rel_anal_otro"] = helperVicIts::validarCombo($data["condon"]["condon_rel_anal_otro"]);
            switch ($this->config['read']['condon_rel_anal_otro']) {
                case '1':
                    $this->tpl->setVariable('drpRelacionAnalOtroSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpRelacionAnalOtroNo', 'selected="selected"');
                    break;
            }
////	[condon_rel_sexual] => -1 
//        $vicIts["condon_rel_sexual"] = helperVicIts::validarCombo($data["condon"]["condon_rel_sexual"]);
            switch ($this->config['read']['condon_rel_sexual']) {
                case '1':
                    $this->tpl->setVariable('drpRelacionesSexualSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpRelacionesSexualNo', 'selected="selected"');
                    break;
            }
////	[condon_rel_anal] => -1 
//        $vicIts["condon_rel_anal"] = helperVicIts::validarCombo($data["condon"]["condon_rel_anal"]);
            switch ($this->config['read']['condon_rel_anal']) {
                case '1':
                    $this->tpl->setVariable('drpRelacionesAnalesSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpRelacionesAnalesNo', 'selected="selected"');
                    break;
            }
////	[condon_tipo_rel_anal] => -1 
//        $vicIts["condon_tipo_rel_anal"] = helperVicIts::validarCombo($data["condon"]["condon_tipo_rel_anal"]);
            switch ($this->config['read']['condon_tipo_rel_anal']) {
                case '1':
                    $this->tpl->setVariable('drpTipoRelacionesAnalesPenetra', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpTipoRelacionesAnalesPenetrado', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('drpTipoRelacionesAnalesAmbos', 'selected="selected"');
                    break;
            }
////	[condon_sexo_oral] => -1 
//        $vicIts["condon_sexo_oral"] = helperVicIts::validarCombo($data["condon"]["condon_sexo_oral"]);
            switch ($this->config['read']['condon_sexo_oral']) {
                case '1':
                    $this->tpl->setVariable('drpSexoOralSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpSexoOralNo', 'selected="selected"');
                    break;
            }
////	[condon_ult_rel_uso_condon] => -1 
//        $vicIts["condon_ult_rel_uso_condon"] = helperVicIts::validarCombo($data["condon"]["condon_ult_rel_uso_condon"]);
            switch ($this->config['read']['condon_ult_rel_uso_condon']) {
                case '1':
                    $this->tpl->setVariable('drpUltRelCondonSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpUltRelCondonNo', 'selected="selected"');
                    break;
            }
////	[par_hombre_fija] => -1 
//        $vicIts["par_hombre_fija"] = helperVicIts::validarCombo($data["condon"]["par_hombre_fija"]);
            switch ($this->config['read']['par_hombre_fija']) {
                case '1':
                    $this->tpl->setVariable('drpHombreFijaSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpHombreFijaNo', 'selected="selected"');
                    break;
            }
//        if ($vicIts["par_hombre_fija"] == '1') {
////	[par_hombre_fija_uso_condon] => -1 
//            $vicIts["par_hombre_fija_uso_condon"] = helperVicIts::validarCombo($data["condon"]["par_hombre_fija_uso_condon"]);
            switch ($this->config['read']['par_hombre_fija_uso_condon']) {
                case '1':
                    $this->tpl->setVariable('drpHombreFijaUsoCondonNunca', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpHombreFijaUsoCondonAVeces', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('drpHombreFijaUsoCondonSiempre', 'selected="selected"');
                    break;
            }
////	[par_hombre_fija_ult_usu_condon] => -1 
//            $vicIts["par_hombre_fija_ult_usu_condon"] = helperVicIts::validarCombo($data["condon"]["par_hombre_fija_ult_usu_condon"]);
            switch ($this->config['read']['par_hombre_fija_ult_usu_condon']) {
                case '1':
                    $this->tpl->setVariable('drpHombreFijaUltUsoCondonSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpHombreFijaUltUsoCondonNo', 'selected="selected"');
                    break;
            }
//                }
////	[par_hombre_casual] => -1 
//        $vicIts["par_hombre_casual"] = helperVicIts::validarCombo($data["condon"]["par_hombre_casual"]);
            switch ($this->config['read']['par_hombre_casual']) {
                case '1':
                    $this->tpl->setVariable('drpHombreCasualSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpHombreCasualNo', 'selected="selected"');
                    break;
            }
//        if ($vicIts["par_hombre_casual"] == '1') {
////	[par_hombre_casual_uso_condon] => -1 
            switch ($this->config['read']['par_hombre_casual_uso_condon']) {
                case '1':
                    $this->tpl->setVariable('drpHombreCasualUsoCondonNunca', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpHombreCasualUsoCondonAVeces', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('drpHombreCasualUsoCondonSiempre', 'selected="selected"');
                    break;
            }
//            $vicIts["par_hombre_casual_uso_condon"] = helperVicIts::validarCombo($data["condon"]["par_hombre_casual_uso_condon"]);
////	[par_hombre_casual_ult_usu_condon] => -1 
            switch ($this->config['read']['par_hombre_casual_ult_usu_condon']) {
                case '1':
                    $this->tpl->setVariable('drpHombreCasualUltUsoCondonSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpHombreCasualUltUsoCondonNo', 'selected="selected"');
                    break;
            }
//            $vicIts["par_hombre_casual_ult_usu_condon"] = helperVicIts::validarCombo($data["condon"]["par_hombre_casual_ult_usu_condon"]);
//        }
////	[par_mujer_fija] => -1 
//        $vicIts["par_mujer_fija"] = helperVicIts::validarCombo($data["condon"]["par_mujer_fija"]);
            switch ($this->config['read']['par_mujer_fija']) {
                case '1':
                    $this->tpl->setVariable('drpMujerFijaSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpMujerFijaNo', 'selected="selected"');
                    break;
            }
//        if ($vicIts["par_mujer_fija"] == '1') {
////	[par_mujer_fija_uso_condon] => -1 
//            $vicIts["par_mujer_fija_uso_condon"] = helperVicIts::validarCombo($data["condon"]["par_mujer_fija_uso_condon"]);
            switch ($this->config['read']['par_mujer_fija_uso_condon']) {
                case '1':
                    $this->tpl->setVariable('drpMujerFijaUsoCondonNunca', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpMujerFijaUsoCondonAVeces', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('drpMujerFijaUsoCondonSiempre', 'selected="selected"');
                    break;
            }
////	[par_mujer_fija_ult_usu_condon] => -1 
//            $vicIts["par_mujer_fija_ult_usu_condon"] = helperVicIts::validarCombo($data["condon"]["par_mujer_fija_ult_usu_condon"]);
            switch ($this->config['read']['par_mujer_fija_ult_usu_condon']) {
                case '1':
                    $this->tpl->setVariable('drpMujerFijaUltUsoCondonSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpMujerFijaUltUsoCondonNo', 'selected="selected"');
                    break;
            }
//        }
////	[par_mujer_casual] => -1 
//        $vicIts["par_mujer_casual"] = helperVicIts::validarCombo($data["condon"]["par_mujer_casual"]);
            switch ($this->config['read']['par_mujer_casual']) {
                case '1':
                    $this->tpl->setVariable('drpMujerCasualSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpMujerCasualNo', 'selected="selected"');
                    break;
            }
//        if ($vicIts["par_mujer_casual"] == '1') {
////	[par_mujer_casual_uso_condon] => -1 
//            $vicIts["par_mujer_casual_uso_condon"] = helperVicIts::validarCombo($data["condon"]["par_mujer_casual_uso_condon"]);
            switch ($this->config['read']['par_mujer_casual_uso_condon']) {
                case '1':
                    $this->tpl->setVariable('drpMujerCasualUsoCondonNunca', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpMujerCasualUsoCondonAVeces', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('drpMujerCasualUsoCondonSiempre', 'selected="selected"');
                    break;
            }
////	[par_mujer_casual_ult_usu_condon] => 1 
//            $vicIts["par_mujer_casual_ult_usu_condon"] = helperVicIts::validarCombo($data["condon"]["par_mujer_casual_ult_usu_condon"]);
            switch ($this->config['read']['par_mujer_casual_ult_usu_condon']) {
                case '1':
                    $this->tpl->setVariable('drpMujerCasualUltUsoCondonSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpMujerCasualUltUsoCondonNo', 'selected="selected"');
                    break;
            }
//        }
////	[ts_cliente_quincena] => [ts_cliente_semana] => 
//        $vicIts["ts_cliente_quincena"] = helperVicIts::validarString($data["condon"]["ts_cliente_quincena"]);
            $this->tpl->setVariable('ts_cliente_quincena', htmlentities($this->config['read']['ts_cliente_quincena']));
            $this->tpl->setVariable('ts_cliente_semana', htmlentities($this->config['read']['ts_cliente_semana']));
////	[ts_uso_condon] => -1 
//        $vicIts["ts_uso_condon"] = helperVicIts::validarCombo($data["condon"]["ts_uso_condon"]);
            switch ($this->config['read']['ts_uso_condon']) {
                case '1':
                    $this->tpl->setVariable('drpClienteUsoCondonNunca', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpClienteUsoCondonAVeces', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('drpClienteUsoCondonSiempre', 'selected="selected"');
                    break;
            }
////      [ts_ultimo_usu_condon] => -1 ) 
//        $vicIts["ts_ultimo_usu_condon"] = helperVicIts::validarCombo($data["condon"]["ts_ultimo_usu_condon"]);
            switch ($this->config['read']['ts_ultimo_usu_condon']) {
                case '1':
                    $this->tpl->setVariable('drpClienteUltUsoCondonSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpClienteUltUsoCondonNo', 'selected="selected"');
                    break;
            }
////[examen_general] => Array (  // 
////	[exa_realizado] => -1 
//        $vicIts["exa_realizado"] = helperVicIts::validarCombo($data["examen_general"]["exa_realizado"]);
            switch ($this->config['read']['exa_realizado']) {
                case '1':
                    $this->tpl->setVariable('drpExaGeneralSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpExaGeneralNo', 'selected="selected"');
                    break;
            }
            if ($this->config['read']['exa_realizado'] == '1') {
//        if ($vicIts["exa_realizado"] == '1') {
////	[exa_temperatura] => 
//            $vicIts["exa_temperatura"] = helperVicIts::validarString($data["examen_general"]["exa_temperatura"]);
                $this->tpl->setVariable('exa_temperatura', htmlentities($this->config['read']['exa_temperatura']));
////	[exa_libras] => 
//            $vicIts["exa_libras"] = helperVicIts::validarString($data["examen_general"]["exa_libras"]);
                $this->tpl->setVariable('exa_libras', htmlentities($this->config['read']['exa_libras']));
////	[exa_PA] => 
//            $vicIts["exa_PA"] = helperVicIts::validarString($data["examen_general"]["exa_PA"]);
                $this->tpl->setVariable('exa_PA', htmlentities($this->config['read']['exa_PA']));
////	[exa_ganglio] => -1 
//            $vicIts["exa_ganglio"] = helperVicIts::validarCombo($data["examen_general"]["exa_ganglio"]);
                switch ($this->config['read']['exa_ganglio']) {
                    case '1':
                        $this->tpl->setVariable('drpExaGanglioNormal', 'selected="selected"');
                        break;
                    case '2':
                        $this->tpl->setVariable('drpExaGanglioAnormal', 'selected="selected"');
                        $this->tpl->setVariable('exaGanglioCuello', ($this->config['read']['exa_ganglio_cuello'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaGanglioAxilar', ($this->config['read']['exa_ganglio_axilar'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaGanglioInguinal', ($this->config['read']['exa_ganglio_inguinal'] == '1' ? 'checked="checked"' : ''));

                        break;
                    case '3':
                        $this->tpl->setVariable('drpExaGanglioNoSabe', 'selected="selected"');
                        break;
                }
//            if ($vicIts["exa_ganglio"] == '2') {
//                $vicIts["exa_ganglio_cuello"] = helperVicIts::validarCheck($data["examen_general"]["exa_ganglio_cuello"]);
//                $vicIts["exa_ganglio_axilar"] = helperVicIts::validarCheck($data["examen_general"]["exa_ganglio_axilar"]);
//                $vicIts["exa_ganglio_inguinal"] = helperVicIts::validarCheck($data["examen_general"]["exa_ganglio_inguinal"]);
//            }
////	[exa_rash] => -1 
//            $vicIts["exa_rash"] = helperVicIts::validarCombo($data["examen_general"]["exa_rash"]);
                switch ($this->config['read']['exa_rash']) {
                    case '1':
                        $this->tpl->setVariable('drpExaRashAusencia', 'selected="selected"');
                        break;
                    case '2':
                        $this->tpl->setVariable('drpExaRashPresencia', 'selected="selected"');
                        break;
                    case '3':
                        $this->tpl->setVariable('drpExaRashNoSabe', 'selected="selected"');
                        break;
                }
                if ($this->config['read']['exa_rash'] == '2') {
                    switch ($this->config['read']['exa_rash_opcion']) {
                        case '1':
                            $this->tpl->setVariable('drpExaRashOpcGen', 'selected="selected"');
                            break;
                        case '2':
                            $this->tpl->setVariable('drpExaRashOpcLoc', 'selected="selected"');
                            break;
                        case '3':
                            $this->tpl->setVariable('drpExaRashOpcNoSabe', 'selected="selected"');
                            break;
                    }
                }
//            if ($vicIts["exa_rash"] == '2') {
//                $vicIts["exa_rash_opcion"] = helperVicIts::validarCombo($data["examen_general"]["exa_rash_opcion"]);
//            }
////	[exa_boca] => -1 
//            $vicIts["exa_boca"] = helperVicIts::validarCombo($data["examen_general"]["exa_boca"]);
                switch ($this->config['read']['exa_boca']) {
                    case '1':
                        $this->tpl->setVariable('drpExaBocaNormal', 'selected="selected"');
                        break;
                    case '2':
                        $this->tpl->setVariable('drpExaBocaAnormal', 'selected="selected"');
                        $this->tpl->setVariable('exaBocaMonilia', ($this->config['read']['exa_boca_monilia'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaBocaUlcera', ($this->config['read']['exa_boca_ulcera'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaBocaAmigdalas', ($this->config['read']['exa_boca_amigdalas'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaBocaIrritacionFaringea', ($this->config['read']['exa_boca_irritacion_faringea'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaBocaOtro', ($this->config['read']['exa_boca_otro'] == '1' ? 'checked="checked"' : ''));

                        break;
                    case '3':
                        $this->tpl->setVariable('drpExaBocaNoSabe', 'selected="selected"');
                        break;
                }
//            if ($vicIts["exa_boca"] == '2') {
//                $vicIts["exa_boca_monilia"] = helperVicIts::validarCheck($data["examen_general"]["exa_boca_monilia"]);
//                $vicIts["exa_boca_ulcera"] = helperVicIts::validarCheck($data["examen_general"]["exa_boca_ulcera"]);
//                $vicIts["exa_boca_amigdalas"] = helperVicIts::validarCheck($data["examen_general"]["exa_boca_amigdalas"]);
//                $vicIts["exa_boca_irritacion_faringea"] = helperVicIts::validarCheck($data["examen_general"]["exa_boca_irritacion_faringea"]);
//                $vicIts["exa_boca_otro"] = helperVicIts::validarCheck($data["examen_general"]["exa_boca_otro"]);
//            }
////	[exa_pene] => -1 
//            $vicIts["exa_pene"] = helperVicIts::validarCombo($data["examen_general"]["exa_pene"]);
                switch ($this->config['read']['exa_pene']) {
                    case '1':
                        $this->tpl->setVariable('drpExaPeneNormal', 'selected="selected"');
                        break;
                    case '2':
                        $this->tpl->setVariable('drpExaPeneAnormal', 'selected="selected"');
                        $this->tpl->setVariable('exaPeneUlcera', ($this->config['read']['exa_pene_ulcera'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaPeneVerruga', ($this->config['read']['exa_pene_verruga'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaPeneAmpolla', ($this->config['read']['exa_pene_ampolla'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaPeneOtro', ($this->config['read']['exa_pene_otro'] == '1' ? 'checked="checked"' : ''));
                        break;
                    case '3':
                        $this->tpl->setVariable('drpExaPeneNoSabe', 'selected="selected"');
                        break;
                }
//            if ($vicIts["exa_pene"] == '2') {
//                $vicIts["exa_pene_ulcera"] = helperVicIts::validarCheck($data["examen_general"]["exa_pene_ulcera"]);
//                $vicIts["exa_pene_verruga"] = helperVicIts::validarCheck($data["examen_general"]["exa_pene_verruga"]);
//                $vicIts["exa_pene_ampolla"] = helperVicIts::validarCheck($data["examen_general"]["exa_pene_ampolla"]);
//                $vicIts["exa_pene_otro"] = helperVicIts::validarCheck($data["examen_general"]["exa_pene_otro"]);
//            }
////	[exa_testiculo] => -1 
//            $vicIts["exa_testiculo"] = helperVicIts::validarCombo($data["examen_general"]["exa_testiculo"]);
                switch ($this->config['read']['exa_testiculo']) {
                    case '1':
                        $this->tpl->setVariable('drpExaTesticuloNormal', 'selected="selected"');
                        break;
                    case '2':
                        $this->tpl->setVariable('drpExaTesticuloAnormal', 'selected="selected"');
                        $this->tpl->setVariable('exaTesticuloUlcera', ($this->config['read']['exa_testiculo_ulcera'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaTesticuloVerruga', ($this->config['read']['exa_testiculo_verruga'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaTesticuloAmpolla', ($this->config['read']['exa_testiculo_ampolla'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaTesticuloOtro', ($this->config['read']['exa_testiculo_otro'] == '1' ? 'checked="checked"' : ''));
                        break;
                    case '3':
                        $this->tpl->setVariable('drpExaTesticuloNoSabe', 'selected="selected"');
                        break;
                }
//            if ($vicIts["exa_testiculo"] == '2') {
//                $vicIts["exa_testiculo_ulcera"] = helperVicIts::validarCheck($data["examen_general"]["exa_testiculo_ulcera"]);
//                $vicIts["exa_testiculo_verruga"] = helperVicIts::validarCheck($data["examen_general"]["exa_testiculo_verruga"]);
//                $vicIts["exa_testiculo_ampolla"] = helperVicIts::validarCheck($data["examen_general"]["exa_testiculo_ampolla"]);
//                $vicIts["exa_testiculo_otro"] = helperVicIts::validarCheck($data["examen_general"]["exa_testiculo_otro"]);
//            }
////	[exa_abdomen] => -1 
//            $vicIts["exa_abdomen"] = helperVicIts::validarCombo($data["examen_general"]["exa_abdomen"]);
                switch ($this->config['read']['exa_abdomen']) {
                    case '1':
                        $this->tpl->setVariable('drpExaAbdomenNormal', 'selected="selected"');
                        break;
                    case '2':
                        $this->tpl->setVariable('drpExaAbdomenAnormal', 'selected="selected"');
                        $this->tpl->setVariable('exaAbdomenFosaIzq', ($this->config['read']['exa_abdomen_fosa_izq'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaAbdomenHipogastrico', ($this->config['read']['exa_abdomen_hipogastrico'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaAbdomenFosaDer', ($this->config['read']['exa_abdomen_fosa_der'] == '1' ? 'checked="checked"' : ''));
                        break;
                    case '3':
                        $this->tpl->setVariable('drpExaAbdomenNoSabe', 'selected="selected"');
                        break;
                }
//            if ($vicIts["exa_abdomen"] == '2') {
//                $vicIts["exa_abdomen_fosa_izq"] = helperVicIts::validarCheck($data["examen_general"]["exa_abdomen_fosa_izq"]);
//                $vicIts["exa_abdomen_hipogastrico"] = helperVicIts::validarCheck($data["examen_general"]["exa_abdomen_hipogastrico"]);
//                $vicIts["exa_abdomen_fosa_der"] = helperVicIts::validarCheck($data["examen_general"]["exa_abdomen_fosa_der"]);
//            }
////	[exa_vulva] => -1 
//            $vicIts["exa_vulva"] = helperVicIts::validarCombo($data["examen_general"]["exa_vulva"]);
                switch ($this->config['read']['exa_vulva']) {
                    case '1':
                        $this->tpl->setVariable('drpExaVulvaNormal', 'selected="selected"');
                        break;
                    case '2':
                        $this->tpl->setVariable('drpExaVulvaAnormal', 'selected="selected"');
                        $this->tpl->setVariable('exaVulvaUlcera', ($this->config['read']['exa_vulva_ulcera'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaVulvaVerruga', ($this->config['read']['exa_vulva_verruga'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaVulvaVesicula', ($this->config['read']['exa_vulva_vesicula'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaVulvaOtro', ($this->config['read']['exa_vulva_otro'] == '1' ? 'checked="checked"' : ''));
                        break;
                    case '3':
                        $this->tpl->setVariable('drpExaVulvaNoSabe', 'selected="selected"');
                        break;
                }
//            if ($vicIts["exa_vulva"] == '2') {
//                $vicIts["exa_vulva_ulcera"] = helperVicIts::validarCheck($data["examen_general"]["exa_vulva_ulcera"]);
//                $vicIts["exa_vulva_verruga"] = helperVicIts::validarCheck($data["examen_general"]["exa_vulva_verruga"]);
//                $vicIts["exa_vulva_vesicula"] = helperVicIts::validarCheck($data["examen_general"]["exa_vulva_vesicula"]);
//                $vicIts["exa_vulva_otro"] = helperVicIts::validarCheck($data["examen_general"]["exa_vulva_otro"]);
//            }
////	[exa_meato] => -1 
//            $vicIts["exa_meato"] = helperVicIts::validarCombo($data["examen_general"]["exa_meato"]);
                switch ($this->config['read']['exa_meato']) {
                    case '1':
                        $this->tpl->setVariable('drpExaMeatoNormal', 'selected="selected"');
                        break;
                    case '2':
                        $this->tpl->setVariable('drpExaMeatoAnormal', 'selected="selected"');
                        $this->tpl->setVariable('exaMeatoHiperemia', ($this->config['read']['exa_meato_hiperemia'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaMeatoSecrecion', ($this->config['read']['exa_meato_secrecion'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaMeatoVerruga', ($this->config['read']['exa_meato_verruga'] == '1' ? 'checked="checked"' : ''));
                        break;
                    case '3':
                        $this->tpl->setVariable('drpExaMeatoNoSabe', 'selected="selected"');
                        break;
                }
//            if ($vicIts["exa_meato"] == '2') {
//                $vicIts["exa_meato_hiperemia"] = helperVicIts::validarCheck($data["examen_general"]["exa_meato_hiperemia"]);
//                $vicIts["exa_meato_secrecion"] = helperVicIts::validarCheck($data["examen_general"]["exa_meato_secrecion"]);
//                $vicIts["exa_meato_verruga"] = helperVicIts::validarCheck($data["examen_general"]["exa_meato_verruga"]);
//            }
////	[exa_ano] => -1 )
//            $vicIts["exa_ano"] = helperVicIts::validarCombo($data["examen_general"]["exa_ano"]);
                switch ($this->config['read']['exa_ano']) {
                    case '1':
                        $this->tpl->setVariable('drpExaAnoNormal', 'selected="selected"');
                        break;
                    case '2':
                        $this->tpl->setVariable('drpExaAnoAnormal', 'selected="selected"');
                        $this->tpl->setVariable('exaAnoUlcera', ($this->config['read']['exa_ano_ulcera'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaAnoVerruga', ($this->config['read']['exa_ano_verruga'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaAnoSecrecion', ($this->config['read']['exa_ano_secrecion'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaAnoVesicula', ($this->config['read']['exa_ano_vesicula'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaAnoOtro', ($this->config['read']['exa_ano_otro'] == '1' ? 'checked="checked"' : ''));
                        break;
                    case '3':
                        $this->tpl->setVariable('drpExaAnoNoSabe', 'selected="selected"');
                        break;
                }
//            if ($vicIts["exa_ano"] == '2') {
//                $vicIts["exa_ano_ulcera"] = helperVicIts::validarCheck($data["examen_general"]["exa_ano_ulcera"]);
//                $vicIts["exa_ano_verruga"] = helperVicIts::validarCheck($data["examen_general"]["exa_ano_verruga"]);
//                $vicIts["exa_ano_secrecion"] = helperVicIts::validarCheck($data["examen_general"]["exa_ano_secrecion"]);
//                $vicIts["exa_ano_vesicula"] = helperVicIts::validarCheck($data["examen_general"]["exa_ano_vesicula"]);
//                $vicIts["exa_ano_otro"] = helperVicIts::validarCheck($data["examen_general"]["exa_ano_otro"]);
//            }
//        }
            }
////[examen_especulo] => Array ( 
////	[exa_especulo_realizado] => -1 
            switch ($this->config['read']['exa_especulo_realizado']) {
                case '1':
                    $this->tpl->setVariable('drpExaEspeculoSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpExaEspeculoNo', 'selected="selected"');
                    break;
            }
            if ($this->config['read']['exa_especulo_realizado'] == '1') {
//        $vicIts["exa_especulo_realizado"] = helperVicIts::validarCombo($data["examen_especulo"]["exa_especulo_realizado"]);
//        if ($vicIts["exa_especulo_realizado"] == '1') {
////	[exa_vagina] => -1 
//            $vicIts["exa_vagina"] = helperVicIts::validarCombo($data["examen_especulo"]["exa_vagina"]);
                switch ($this->config['read']['exa_vagina']) {
                    case '1':
                        $this->tpl->setVariable('drpExaVaginaNormal', 'selected="selected"');
                        break;
                    case '2':
                        $this->tpl->setVariable('drpExaVaginaAnormal', 'selected="selected"');
                        $this->tpl->setVariable('exaVaginaUlcera', ($this->config['read']['exa_vagina_ulcera'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaVaginaHiperemia', ($this->config['read']['exa_vagina_hiperamia'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaVaginaMenstruacion', ($this->config['read']['exa_vagina_menstruacion'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaVaginaAtrofia', ($this->config['read']['exa_vagina_atrofia'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaVaginaOtro', ($this->config['read']['exa_vagina_otro'] == '1' ? 'checked="checked"' : ''));
                        break;
                    case '3':
                        $this->tpl->setVariable('drpExaVaginaNoSabe', 'selected="selected"');
                        break;
                }
//            if ($vicIts["exa_vagina"] == '2') {
//                $vicIts["exa_vagina_ulcera"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_vagina_ulcera"]);
//                $vicIts["exa_vagina_hiperamia"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_vagina_hiperamia"]);
//                $vicIts["exa_vagina_menstruacion"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_vagina_menstruacion"]);
//                $vicIts["exa_vagina_atrofia"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_vagina_atrofia"]);
//                $vicIts["exa_vagina_otro"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_vagina_otro"]);
//            }
////	[exa_flujo] => -1 
//            $vicIts["exa_flujo"] = helperVicIts::validarCombo($data["examen_especulo"]["exa_flujo"]);
                switch ($this->config['read']['exa_flujo']) {
                    case '1':
                        $this->tpl->setVariable('drpExaFlujoSi', 'selected="selected"');
                        break;
                    case '2':
                        $this->tpl->setVariable('drpExaFlujoNo', 'selected="selected"');
                        break;
                    case '3':
                        $this->tpl->setVariable('drpExaFlujoNoSabe', 'selected="selected"');
                        break;
                }
                if ($this->config['read']['exa_flujo'] == '1') {
                    switch ($this->config['read']['exa_flujo_cantidad']) {
                        case '1':
                            $this->tpl->setVariable('drpExaFlujoCantidadNormal', 'selected="selected"');
                            break;
                        case '2':
                            $this->tpl->setVariable('drpExaFlujoCantidadAnormal', 'selected="selected"');
                            break;
                        case '3':
                            $this->tpl->setVariable('drpExaFlujoCantidadNoSabe', 'selected="selected"');
                            break;
                    }
                    switch ($this->config['read']['exa_flujo_color']) {
                        case '1':
                            $this->tpl->setVariable('drpExaFlujoColorBlanco', 'selected="selected"');
                            break;
                        case '2':
                            $this->tpl->setVariable('drpExaFlujoColorVerdeAmarillo', 'selected="selected"');
                            break;
                        case '3':
                            $this->tpl->setVariable('drpExaFlujoColorAmarillo', 'selected="selected"');
                            break;
                        case '4':
                            $this->tpl->setVariable('drpExaFlujoColorCafe', 'selected="selected"');
                            break;
                        case '5':
                            $this->tpl->setVariable('drpExaFlujoColorNoSabe', 'selected="selected"');
                            break;
                    }
                    $this->tpl->setVariable('exaFlujoAspectoSanguinolento', ($this->config['read']['exa_flujo_asp_sanguinolento'] == '1' ? 'checked="checked"' : ''));
                    $this->tpl->setVariable('exaFlujoAspectoGrumoso', ($this->config['read']['exa_flujo_asp_grumoso'] == '1' ? 'checked="checked"' : ''));
                    $this->tpl->setVariable('exaFlujoAspectoEspumoso', ($this->config['read']['exa_flujo_asp_espumoso'] == '1' ? 'checked="checked"' : ''));
                    $this->tpl->setVariable('exaFlujoAspectoMucoso', ($this->config['read']['exa_flujo_asp_mucoso'] == '1' ? 'checked="checked"' : ''));
                    $this->tpl->setVariable('exaFlujoAspectoOlor', ($this->config['read']['exa_flujo_olor'] == '1' ? 'checked="checked"' : ''));
                }
//            if ($vicIts["exa_flujo"] == '2') {
//                $vicIts["exa_flujo_asp_sanguinolento"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_flujo_asp_sanguinolento"]);
//                $vicIts["exa_flujo_asp_grumoso"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_flujo_asp_grumoso"]);
//                $vicIts["exa_flujo_asp_espumoso"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_flujo_asp_espumoso"]);
//                $vicIts["exa_flujo_asp_mucoso"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_flujo_asp_mucoso"]);
//                $vicIts["exa_flujo_olor"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_flujo_olor"]);
//            }
////	[exa_flujo_cantidad] => -1
//            $vicIts["exa_flujo_cantidad"] = helperVicIts::validarCombo($data["examen_especulo"]["exa_flujo_cantidad"]);
////	[exa_flujo_color] => -1 
//            $vicIts["exa_flujo_color"] = helperVicIts::validarCombo($data["examen_especulo"]["exa_flujo_color"]);
////	[exa_cervix] => -1 
//            $vicIts["exa_cervix"] = helperVicIts::validarCombo($data["examen_especulo"]["exa_cervix"]);
                switch ($this->config['read']['exa_cervix']) {
                    case '1':
                        $this->tpl->setVariable('drpExaCervixNormal', 'selected="selected"');
                        break;
                    case '2':
                        $this->tpl->setVariable('drpExaCervixAnormal', 'selected="selected"');
                        $this->tpl->setVariable('exaCervixUlcera', ($this->config['read']['exa_cervix_ulcera'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaCervixHiperemia', ($this->config['read']['exa_cervix_hiperamia'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaCervixFriable', ($this->config['read']['exa_cervix_friable'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaCervixTumor', ($this->config['read']['exa_cervix_tumor'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaCervixPus', ($this->config['read']['exa_cervix_pus'] == '1' ? 'checked="checked"' : ''));
                        break;
                    case '3':
                        $this->tpl->setVariable('drpExaCervixAusencia', 'selected="selected"');
                        break;
                    case '4':
                        $this->tpl->setVariable('drpExaCervixNoSabe', 'selected="selected"');
                        break;
                }
//            if ($vicIts["exa_cervix"] == '2') {
//                $vicIts["exa_cervix_ulcera"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_cervix_ulcera"]);
//                $vicIts["exa_cervix_hiperamia"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_cervix_hiperamia"]);
//                $vicIts["exa_cervix_friable"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_cervix_friable"]);
//                $vicIts["exa_cervix_tumor"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_cervix_tumor"]);
//                $vicIts["exa_cervix_pus"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_cervix_pus"]);
//            }
//        }
            }
////[examen_bimanual] => Array ( 
////	[exa_bimanual_realizado] => -1 ) 
//        $vicIts["exa_bimanual_realizado"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bimanual_realizado"]);
            switch ($this->config['read']['exa_bimanual_realizado']) {
                case '1':
                    $this->tpl->setVariable('drpExaBimanualSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpExaBimanualNo', 'selected="selected"');
                    break;
            }
            if ($this->config['read']['exa_bimanual_realizado'] == '1') {
//        if ($vicIts["exa_bimanual_realizado"] == '1') {
////	[exa_bi_anexo] => -1 
//            $vicIts["exa_bi_anexo"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_anexo"]);
                switch ($this->config['read']['exa_bi_anexo']) {
                    case '1':
                        $this->tpl->setVariable('drpExaBiAnexoNormal', 'selected="selected"');
                        break;
                    case '2':
                        $this->tpl->setVariable('drpExaBiAnexoAnormal', 'selected="selected"');
                        break;
                    case '3':
                        $this->tpl->setVariable('drpExaBiAnexoNoSabe', 'selected="selected"');
                        break;
                }
                if ($this->config['read']['exa_bi_anexo'] == '2') {
                    switch ($this->config['read']['exa_bi_anexo_sangrado']) {
                        case '1':
                            $this->tpl->setVariable('drpExaAnexoSangradoNormal', 'selected="selected"');
                            break;
                        case '2':
                            $this->tpl->setVariable('drpExaAnexoSangradoAnormal', 'selected="selected"');
                            break;
                        case '3':
                            $this->tpl->setVariable('drpExaAnexoSangradoNoSabe', 'selected="selected"');
                            break;
                    }
                    switch ($this->config['read']['exa_bi_anexo_dolor']) {
                        case '1':
                            $this->tpl->setVariable('drpExaBiAnexoDolorDerecho', 'selected="selected"');
                            break;
                        case '2':
                            $this->tpl->setVariable('drpExaBiAnexoDolorIzquierdo', 'selected="selected"');
                            break;
                        case '3':
                            $this->tpl->setVariable('drpExaBiAnexoDolorNoSabe', 'selected="selected"');
                            break;
                    }
                    switch ($this->config['read']['exa_bi_anexo_tumor']) {
                        case '1':
                            $this->tpl->setVariable('drpExaBiAnexoTumorDerecho', 'selected="selected"');
                            break;
                        case '2':
                            $this->tpl->setVariable('drpExaBiAnexoTumorIzquierdo', 'selected="selected"');
                            break;
                        case '3':
                            $this->tpl->setVariable('drpExaBiAnexoTumorNoSabe', 'selected="selected"');
                            break;
                    }
                }
////	[exa_bi_anexo_sangrado] => -1 
//            $vicIts["exa_bi_anexo_sangrado"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_anexo_sangrado"]);
////	[exa_bi_anexo_dolor] => -1 
//            $vicIts["exa_bi_anexo_dolor"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_anexo_dolor"]);
////	[exa_bi_anexo_tumor] => -1 
//            $vicIts["exa_bi_anexo_tumor"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_anexo_tumor"]);
////	[exa_bi_hipogastrico] => -1 
//            $vicIts["exa_bi_hipogastrico"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_hipogastrico"]);
                switch ($this->config['read']['exa_bi_hipogastrico']) {
                    case '1':
                        $this->tpl->setVariable('drpExaBiHipogastricoNormal', 'selected="selected"');
                        break;
                    case '2':
                        $this->tpl->setVariable('drpExaBiHipogastricoDolor', 'selected="selected"');
                        break;
                    case '3':
                        $this->tpl->setVariable('drpExaBiHipogastricoNoSabe', 'selected="selected"');
                        break;
                }
////	[exa_bi_cervix] => -1 
//            $vicIts["exa_bi_cervix"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_cervix"]);
                switch ($this->config['read']['exa_bi_cervix']) {
                    case '1':
                        $this->tpl->setVariable('drpExaBiCervixNormal', 'selected="selected"');
                        break;
                    case '2':
                        $this->tpl->setVariable('drpExaBiCervixAnormal', 'selected="selected"');
                        $this->tpl->setVariable('exaBiCervixAusente', ($this->config['read']['exa_bi_cervix_ausente'] == '1' ? 'checked="checked"' : ''));
                        $this->tpl->setVariable('exaBiCervixDolor', ($this->config['read']['exa_bi_cervix_dolor'] == '1' ? 'checked="checked"' : ''));

                        break;
                    case '3':
                        $this->tpl->setVariable('drpExaBiCervixNoSabe', 'selected="selected"');
                        break;
                }
////	[exa_bi_cervix_anormal] => -1  ALGUIEN LA QUITO
////            $vicIts["exa_bi_cervix_anormal"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_cervix_anormal"]);
//            $vicIts["exa_bi_cervix_ausente"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_cervix_ausente"]);
//            $vicIts["exa_bi_cervix_dolor"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_cervix_dolor"]);
////	[exa_bi_utero] => -1 
//            $vicIts["exa_bi_utero"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_utero"]);
                switch ($this->config['read']['exa_bi_utero']) {
                    case '1':
                        $this->tpl->setVariable('drpExaBiUteroNormal', 'selected="selected"');
                        break;
                    case '2':
                        $this->tpl->setVariable('drpExaBiUteroAnormal', 'selected="selected"');
                        break;
                    case '3':
                        $this->tpl->setVariable('drpExaBiUteroNoSabe', 'selected="selected"');
                        break;
                }
                if ($this->config['read']['exa_bi_utero'] == '2') {
                    switch ($this->config['read']['exa_bi_utero_anormal']) {
                        case '1':
                            $this->tpl->setVariable('drpExaBiUteroAnormalAusente', 'selected="selected"');
                            break;
                        case '2':
                            $this->tpl->setVariable('drpExaBiUteroAnormalAumentado', 'selected="selected"');
                            break;
                        case '3':
                            $this->tpl->setVariable('drpExaBiUteroAnormalNoSabe', 'selected="selected"');
                            break;
                    }
                }
////	[exa_bi_utero_anormal] => -1 ) 
//            $vicIts["exa_bi_utero_anormal"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_utero_anormal"]);
//        }
//
            }
////PARTE B
////[muestras_laboratorio] => Array ( 
////	[usuario_sano] => -1 
//        $vicIts["usuario_sano"] = helperVicIts::validarCombo($data["muestras_laboratorio"]["usuario_sano"]);
            switch ($this->config['read']['usuario_sano']) {
                case '1':
                    $this->tpl->setVariable('drpUsuarioSanoSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpUsuarioSanoNo', 'selected="selected"');
                    break;
            }
////	[muestra_ninguna] => -1 
//        $vicIts["muestra_ninguna"] = helperVicIts::validarCombo($data["muestras_laboratorio"]["muestra_ninguna"]);
//        if ($vicIts["muestra_ninguna"] == '1') {
//            $vicIts["muestra_sangre_ts"] = helperVicIts::validarCheck($data["muestras_laboratorio"]["muestra_sangre_ts"]);
//            $vicIts["muestra_flujo_vaginal"] = helperVicIts::validarCheck($data["muestras_laboratorio"]["muestra_flujo_vaginal"]);
//            $vicIts["muestra_endocervix"] = helperVicIts::validarCheck($data["muestras_laboratorio"]["muestra_endocervix"]);
//            $vicIts["muestra_citologia"] = helperVicIts::validarCheck($data["muestras_laboratorio"]["muestra_citologia"]);
//            $vicIts["muestra_ulcera_ts"] = helperVicIts::validarCheck($data["muestras_laboratorio"]["muestra_ulcera_ts"]);
//            $vicIts["muestra_sangre_hsh"] = helperVicIts::validarCheck($data["muestras_laboratorio"]["muestra_sangre_hsh"]);
//            $vicIts["muestra_ulcera"] = helperVicIts::validarCheck($data["muestras_laboratorio"]["muestra_ulcera"]);
//            $vicIts["muestra_secrecion_uretral"] = helperVicIts::validarCheck($data["muestras_laboratorio"]["muestra_secrecion_uretral"]);
//            $vicIts["muestra_secrecion_anal"] = helperVicIts::validarCheck($data["muestras_laboratorio"]["muestra_secrecion_anal"]);
//        }
            switch ($this->config['read']['muestra_ninguna']) {
                case '1':
                    $this->tpl->setVariable('drpMuestrasTomoSi', 'selected="selected"');
                    $this->tpl->setVariable('muestraSangreTS', ($this->config['read']['muestra_sangre_ts'] == '1' ? 'checked="checked"' : ''));
                    $this->tpl->setVariable('muestraFlujoVaginal', ($this->config['read']['muestra_flujo_vaginal'] == '1' ? 'checked="checked"' : ''));
                    $this->tpl->setVariable('muestraEndocervix', ($this->config['read']['muestra_endocervix'] == '1' ? 'checked="checked"' : ''));
                    $this->tpl->setVariable('muestraCitologia', ($this->config['read']['muestra_citologia'] == '1' ? 'checked="checked"' : ''));
                    $this->tpl->setVariable('muestraUlceraTS', ($this->config['read']['muestra_ulcera_ts'] == '1' ? 'checked="checked"' : ''));
                    $this->tpl->setVariable('muestraSangreHSH', ($this->config['read']['muestra_sangre_hsh'] == '1' ? 'checked="checked"' : ''));
                    $this->tpl->setVariable('muestraUlcera', ($this->config['read']['muestra_ulcera'] == '1' ? 'checked="checked"' : ''));
                    $this->tpl->setVariable('muestraSecrecionUretral', ($this->config['read']['muestra_secrecion_uretral'] == '1' ? 'checked="checked"' : ''));
                    $this->tpl->setVariable('muestraSecrecionAnal', ($this->config['read']['muestra_secrecion_anal'] == '1' ? 'checked="checked"' : ''));
                    break;
                case '2':
                    $this->tpl->setVariable('drpMuestrasTomoNo', 'selected="selected"');
                    break;
            }
////	[fecha_menstruacion] => 
//        $vicIts["fecha_menstruacion"] = helperVicIts::validarFecha($data["muestras_laboratorio"]["fecha_menstruacion"]);
            $this->tpl->setVariable('valFechaMenstruacion', helperString::toDateView($this->config['read']['fecha_menstruacion']));

////	[embarazo] => -1 
//        $vicIts["embarazo"] = helperVicIts::validarCombo($data["muestras_laboratorio"]["embarazo"]);
//        if ($vicIts["embarazo"] == '1')
////	[embarazo_semanas] => ) 
//            $vicIts["embarazo_semanas"] = helperVicIts::validarString($data["muestras_laboratorio"]["embarazo_semanas"]);
//
            switch ($this->config['read']['embarazo']) {
                case '1':
                    $this->tpl->setVariable('drpEmbarazoSi', 'selected="selected"');
                    $this->tpl->setVariable("embarazo_semanas", htmlentities($this->config['read']["embarazo_semanas"]));
                    break;
                case '2':
                    $this->tpl->setVariable('drpEmbarazoNo', 'selected="selected"');
                    break;
            }

////[diagnostico_tratamiento] => Array ( 
////	[otro_tratamiento] => -1 
//        $vicIts["otro_tratamiento"] = helperVicIts::validarCombo($data["diagnostico_tratamiento"]["otro_tratamiento"]);
//        if ($vicIts["otro_tratamiento"] == '1') {
//            $vicIts["tx_sulfato"] = helperVicIts::validarCheck($data["diagnostico_tratamiento"]["tx_sulfato"]);
//            $vicIts["tx_acido_folico"] = helperVicIts::validarCheck($data["diagnostico_tratamiento"]["tx_acido_folico"]);
//            $vicIts["tx_prenatales"] = helperVicIts::validarCheck($data["diagnostico_tratamiento"]["tx_prenatales"]);
//            $vicIts["tx_toxoide"] = helperVicIts::validarCheck($data["diagnostico_tratamiento"]["tx_toxoide"]);
//            $vicIts["intervencion"] = helperVicIts::validarCombo($data["diagnostico_tratamiento"]["intervencion"]);
////	[diag_otro] => 
//            $vicIts["diag_otro"] = helperVicIts::validarString($data["diagnostico_tratamiento"]["diag_otro"]);
////	[diag_otro_medicamento] => 
//            $vicIts["diag_otro_medicamento"] = helperVicIts::validarString($data["diagnostico_tratamiento"]["diag_otro_medicamento"]);
//        }
            switch ($this->config['read']['otro_tratamiento']) {
                case '1':
                    $this->tpl->setVariable('drpOtroTratamientoSi', 'selected="selected"');
                    $this->tpl->setVariable('txSulfato', ($this->config['read']['tx_sulfato'] == '1' ? 'checked="checked"' : ''));
                    $this->tpl->setVariable('txAcidoFolico', ($this->config['read']['tx_acido_folico'] == '1' ? 'checked="checked"' : ''));
                    $this->tpl->setVariable('txPrenatales', ($this->config['read']['tx_prenatales'] == '1' ? 'checked="checked"' : ''));
                    $this->tpl->setVariable('txToxoide', ($this->config['read']['tx_toxoide'] == '1' ? 'checked="checked"' : ''));
                    break;
                case '2':
                    $this->tpl->setVariable('drpOtroTratamientoNo', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('drpOtroTratamientoNoSabe', 'selected="selected"');
                    break;
            }
            if ($this->config['read']['otro_tratamiento'] == '1') {

                $this->tpl->setVariable("diag_otro", htmlentities($this->config['read']["diag_otro"]));
                $this->tpl->setVariable("diag_otro_medicamento", htmlentities($this->config['read']["diag_otro_medicamento"]));
            }
            switch ($this->config['read']['intervencion']) {
                case '1':
                    $this->tpl->setVariable('drpDiagIntervencionSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpDiagIntervencionNo', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('drpDiagIntervencionNoSabe', 'selected="selected"');
                    break;
            }
////	[globalDiagnosticosTratamientoRelacionados] => 1-1-1###3-5-7 )
////[notificacion] => Array ( 
////	[noti_referencia_pareja] => -1 
//        $vicIts["noti_referencia_pareja"] = helperVicIts::validarCombo($data["notificacion"]["noti_referencia_pareja"]);
            switch ($this->config['read']['noti_referencia_pareja']) {
                case '1':
                    $this->tpl->setVariable('drpDxRefParejaSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('drpDxRefParejaNo', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('drpDxRefParejaNoSabe', 'selected="selected"');
                    break;
            }
////	[noti_preservativos] => -1
//        $vicIts["noti_preservativos"] = helperVicIts::validarCombo($data["notificacion"]["noti_preservativos"]);
//        if ($vicIts["noti_preservativos"] == '1')
//            $vicIts["noti_preservativos_cuantos"] = helperVicIts::validarString($data["notificacion"]["noti_preservativos_cuantos"]);
            switch ($this->config['read']['noti_preservativos']) {
                case '1':
                    $this->tpl->setVariable('drpDxPreservativosSi', 'selected="selected"');
                    $this->tpl->setVariable("noti_preservativos_cuantos", htmlentities($this->config['read']["noti_preservativos_cuantos"]));
                    break;
                case '2':
                    $this->tpl->setVariable('drpDxPreservativosNo', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('drpDxPreservativosNoSabe', 'selected="selected"');
                    break;
            }
////	[noti_medicamento1] => 
//        $vicIts["noti_medicamento1"] = helperVicIts::validarString($data["notificacion"]["noti_medicamento1"]);
////	[noti_medicamento2] => 
//        $vicIts["noti_medicamento2"] = helperVicIts::validarString($data["notificacion"]["noti_medicamento2"]);
////	[noti_medicamento3] => 
//        $vicIts["noti_medicamento3"] = helperVicIts::validarString($data["notificacion"]["noti_medicamento3"]);
            $this->tpl->setVariable("noti_medicamento1", htmlentities($this->config['read']["noti_medicamento1"]));
            $this->tpl->setVariable("noti_medicamento2", htmlentities($this->config['read']["noti_medicamento2"]));
            $this->tpl->setVariable("noti_medicamento3", htmlentities($this->config['read']["noti_medicamento3"]));
////	[noti_numero] => ) 
//        $vicIts["noti_id_un"] = helperVicIts::validarString($data["notificacion"]["noti_id_un"]);
            //FALTA TRAER EL NOMBRE DE LA NOTIC
            
            $id_un = $this->config['read']["id_un"];
            $nombre_un = $this->config['read']["nombre_un"];
                        
            $this->tpl->setVariable("noti_unidad_notificadora", htmlentities($nombre_un));
            $this->tpl->setVariable("noti_id_un", htmlentities($id_un));
//        $vicIts["noti_nombre_medico"] = helperVicIts::validarString($data["notificacion"]["noti_nombre_medico"]);
            $this->tpl->setVariable("noti_nombre_medico", htmlentities($this->config['read']["noti_nombre_medico"]));
//        $vicIts["fecha_consulta"] = helperVicIts::validarFecha($data["notificacion"]["fecha_consulta"]);
            $this->tpl->setVariable("noti_fecha_consulta", helperString::toDateView($this->config['read']["fecha_consulta"]));
//        $vicIts["nombre_registra"] = helperVicIts::validarString($data["notificacion"]["nombre_registra"]);
            $this->tpl->setVariable("noti_nombre_registra", htmlentities($this->config['read']["nombre_registra"]));
//        $vicIts["fecha_form_vicits"] = helperVicIts::validarFecha($data["notificacion"]["fecha_form_vicits"]);
            $this->tpl->setVariable("noti_fecha_form_vicits", helperString::toDateView($this->config['read']["fecha_formulario"]));
//        $semanaanio = Utils::calcularSemanaEpi($data["notificacion"]["fecha_consulta"]);
//        $vicIts["semana_epi"] = $semanaanio["semana"];
//        $vicIts["anio"] = $semanaanio["anio"];
/////////////////////////////////////END PARTE A - PARTE B//////////////////////////////////////////////////////// 
            $this->tpl->setVariable("displayBuscarBorrar", "none");
        }

        // Muestra si ocurrió un error
        $this->tpl->setVariable("mensajeErrorGeneral", $this->config['Merror']);
        $this->tpl->setVariable("disError", (isset($this->config['Merror']) ? '' : 'none'));

        // Muestra botones GUARDAR y CANCELAR según permisos
        require_once ('libs/caus/clsCaus.php');
        if (!$lectura) {
            if (clsCaus::validarSeccion(ConfigurationCAUS::vicItsFormulario, ConfigurationCAUS::Agregar))
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarVicIts();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
        }
        else {
            if (clsCaus::validarSeccion(ConfigurationCAUS::vicItsFormulario, ConfigurationCAUS::Modificar))
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarVicIts();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Actualizar</a>&nbsp;');
        }
        if (clsCaus::validarSeccion(ConfigurationCAUS::vicItsFormulario, ConfigurationCAUS::Consultar))
            $this->tpl->setVariable("botonCancelar", '<a href="index.php" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');

        $this->tpl->touchBlock('contentBlock');
    }

}

?>
