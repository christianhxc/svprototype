<?php

require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

class formUceti extends page {

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
//        echo "Guardar previo ".$this->config["guardarPrevio"];
        if ($this->config["guardarPrevio"] == "2") {
            $this->tpl->setVariable("updateMuestra", "1");
        }
        $this->tpl->setVariable("action", $this->config["action"]);

        $this->tpl->addBlockFile('CONTENT', 'contentBlock', Configuration::templatesPath . 'uceti/formulario.tpl.html');
        $this->tpl->setVariable('urlPrefix', Configuration::getUrlprefix());

        $relleno = "";
        $totalCatalogos = helperCatalogos::getAntecedentesVacunalesTotalInfluenza()-2;
        for ($i = 0; $i < $totalCatalogos; $i++) {
            $relleno.='<tr><td>&nbsp;</td></tr>';
        }
        $this->tpl->setVariable('rellenoFechasAntecedente', $relleno);

        // ESTADO DE LA MUESTRA
//                $this->tpl->setVariable('estadoMuestra',$this->config["data"]["SIT_ID"]);
//                $this->tpl->setVariable('idMuestra',$this->config["data"]["MUE_ID"]);                

        $this->tpl->setVariable("disInfo", $this->config["info"] != "" ? '' : 'none');
        $this->tpl->setVariable("desInfo", $this->config["info"]);

        $this->tpl->setVariable("silab_local", ConfigurationHospitalInfluenza::getSilabLocal());
        $this->tpl->setVariable("silab_remoto", Etiquetas::silab_remoto);

        if ($this->config["error"]) {
            $this->tpl->setVariable('mensajeError', '');
            $this->tpl->setVariable('mostrarError', '');
            $this->tpl->setVariable('valError', $this->config["mensaje"]);
        } else {
            $this->tpl->setVariable('mensajeError', 'none');
            $this->tpl->setVariable('mostrarError', 'none');
            $this->tpl->setVariable('valError', '');
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
                        $this->tpl->setVariable("selDisIndividuo", ($corregimiento["id_corregimiento"] == $this->config["data"]["individuo"]["idCorregimiento"] ? 'selected="selected"' : ''));

                    $this->tpl->parse('blkCorIndividuo');
                }
            }
        }

        $tiposMuestras = helperCatalogos::getTiposMuestraInfluenza();
        if (is_array($tiposMuestras)) {
            foreach ($tiposMuestras as $tipoMuestra) {
                $this->tpl->setCurrentBlock('blkTipoMuestra');
                $this->tpl->setVariable("valTipoMuestra", $tipoMuestra["id_cat_muestra_laboratorio"]);
                $this->tpl->setVariable("opcTipoMuestra", htmlentities($tipoMuestra["nombre_muestra_laboratorio"]));

                if ($this->config["preselect"])
                    $this->tpl->setVariable("selTipoMuestra", ($tipoMuestra["id_cat_muestra_laboratorio"] == $this->config["data"]["muestras_laboratorio"]["tipo_muestra"] ? 'selected="selected"' : ''));

                $this->tpl->parse('blkTipoMuestra');
            }
        }

//        echo "hola";exit;
        $enfermedades = helperCatalogos::getEnfermedadesCronicasInfluenza();
        $enfermedadTotal = "";
        if (isset($enfermedades)) {
            foreach ($enfermedades as $enfermedad) {
                $enfermedadTotal .= $enfermedad['id_cat_enfermedad_cronica'] . "-99-" . $enfermedad['nombre_enfermedad_cronica'] . "###";
            }
            $enfermedadTotal = substr($enfermedadTotal, 0, strlen($enfermedadTotal) - 3);
            $this->tpl->setVariable('valEnfermedadesRelacionados', $enfermedadTotal);
        }

        $vacunas = helperCatalogos::getAntecedentesVacunalesInfluenza();
        $vacunaTotal = "";
        if (isset($vacunas)) {
            foreach ($vacunas as $vacuna) {
                $vacunaTotal .= $vacuna['id_cat_antecendente_vacunal'] . "--99--" . $vacuna['nombre_antecendente_vacunal'] . "###";
            }
            $vacunaTotal = substr($vacunaTotal, 0, strlen($vacunaTotal) - 3);
            $this->tpl->setVariable('valVacunasRelacionados', $vacunaTotal);
        }

        if ($nuevo) {
            $this->tpl->setVariable('valDisplayTrimestreOblig', 'none');
            $this->tpl->setVariable('valDisplayTipoContactoOblig', 'none');
            $this->tpl->setVariable('valDisplayRiesgoCual', 'none');
            $this->tpl->setVariable('valDisplayViajeDonde', 'none');
            $this->tpl->setVariable('valDisplayHospitalizado', 'none');
            $this->tpl->setVariable('valDisplayFechaHospOblig', 'none');
            $this->tpl->setVariable('valDisplayCondensacionOblig', 'none');
            $this->tpl->setVariable('valDisplayDerrameOblig', 'none');
            $this->tpl->setVariable('valDisplayBroncoOblig', 'none');
            $this->tpl->setVariable('valDisplayInfiltradoOblig', 'none');
            $this->tpl->setVariable('valDisplayOtroRXOblig', 'none');
            $this->tpl->setVariable('valDisplayOtroNombre', 'none');
            $this->tpl->setVariable('valDisplayAntibiotico', 'none');
            $this->tpl->setVariable('valDisplayAntiviral', 'none');
            $this->tpl->setVariable('valDisplayOtroHallazgo', 'none');

            $this->tpl->setVariable('valDisplayAutopsia', 'none');
            $this->tpl->setVariable('valDisplayOtraRegion', 'none');
            $nombreUsuario = htmlentities(clsCaus::obtenerNombres()) . " " . htmlentities(clsCaus::obtenerApellidos());
            if (isset($this->config["data"]["notificacion"]["nombreUsuario"]))
                $nombreUsuario = $this->config["data"]["notificacion"]["nombreUsuario"];
            $this->tpl->setVariable('valNombreRegistra', $nombreUsuario);
            $institucionUsuario = htmlentities(clsCaus::obtenerOrgCodigo());
            if (isset($this->config["data"]["notificacion"]["institucionUsuario"]))
                $institucionUsuario = $this->config["data"]["notificacion"]["institucionUsuario"];
            $this->tpl->setVariable('valInstitucionUsuario', $institucionUsuario);
            $fechaFormulario = date("d/m/Y");
            if (isset($this->config["data"]["notificacion"]["fechaFormulario"]))
                $fechaFormulario = $this->config["data"]["notificacion"]["fechaFormulario"];
            $this->tpl->setVariable('valFechaFormulario', $fechaFormulario);


            // Carga catálogo de razones de tipo Id
            $tiposId = $this->config["catalogos"]["tipoId"];
            if (is_array($tiposId)) {
                foreach ($tiposId as $tipoId) {
                    $this->tpl->setCurrentBlock('blkTipoId');
                    $this->tpl->setVariable("valTipoId", $tipoId["id_tipo_identidad"]);
                    $this->tpl->setVariable("opcTipoId", htmlentities($tipoId["nombre_tipo"]));
                    if ($this->config["preselect"])
                        $this->tpl->setVariable("selTipoId", ($tipoId["id_tipo_identidad"] == $this->config["data"]["individuo"]["tipoId"] ? 'selected="selected"' : ''));

                    $this->tpl->parse('blkTipoId');

                    $this->tpl->setCurrentBlock('blkPopTipoId');
                    $this->tpl->setVariable("valPopTipoId", $tipoId["id_tipo_identidad"]);
                    $this->tpl->setVariable("opcPopTipoId", htmlentities($tipoId["nombre_tipo"]));
                    $this->tpl->setVariable("selPopTipoId", '');
                    $this->tpl->parse('blkPopTipoId');
                }
            }
//            $this->tpl->setVariable('selEmbarazoNo', 'selected="selected"'); Quitados por peticion de la dra molto.
//            $this->tpl->setVariable('selCronicaSi', 'selected="selected"');
//            $this->tpl->setVariable('selRiesgoNo', 'selected="selected"');
//            $this->tpl->setVariable('selContactoNo', 'selected="selected"');
            $this->tpl->setVariable('valEstadoSilab', '<img width=16 height=16 src="../img/iconos/pendiente.png"> Pendiente de muestra');
            $this->tpl->setVariable('disBorrarSilab', 'none');
            $this->tpl->setVariable('disBuscarSilab', '');
            $this->tpl->setVariable('chkTarVacunaSi', 'checked="checked"');

            $valNotificacionIdUn = '';
            $valNotificacionUnidad = '';
            $lista = clsCaus::obtenerUbicaciones(ConfigurationCAUS::Localidad);
//            print_r($lista);
            if (is_array($lista)) {
                foreach ($lista as $value) {
                    $valNotificacionIdUn = $value;
                    $valNotificacionUnidad = helperLugar::getUnidadNotificadoraNombre($valNotificacionIdUn);
                    break;
                }
            }

//            echo $valNotificacionIdUn . " nombre " . $valNotificacionUnidad;
            $this->tpl->setVariable('valNotificacionUnidad', htmlentities($valNotificacionUnidad));
            $this->tpl->setVariable('valNotificacionIdUn', $valNotificacionIdUn);
            $this->tpl->setVariable('valUnidadNoDisponible', '');
        } else if ($lectura) {
            $this->tpl->setVariable('buscarPaciente', 'none');
            $this->tpl->setVariable('action', 'M');
            $this->tpl->setVariable('valIdUceti', $this->config['read']['id_flureg']);
            //print_r($this->config['read']);
            //Notificacion
            $unidadDisponible = $this->config['read']['unidad_disponible'];
            if ($unidadDisponible == '1') {
                $this->tpl->setVariable('valNotificacionUnidad', htmlentities($this->config['read']['nombre_un']));
                $this->tpl->setVariable('valNotificacionIdUn', $this->config['read']['id_un']);
                $this->tpl->setVariable('valUnidadNoDisponible', '');
            } else {
                $this->tpl->setVariable('valNotificacionUnidad', "");
                $this->tpl->setVariable('valNotificacionIdUn', "");
                $this->tpl->setVariable('valUnidadNoDisponible', 'checked="checked"');
            }
            $tipoPaciente = $this->config['read']['per_tipo_paciente'];
            switch ($tipoPaciente) {
                case '0':
                    $this->tpl->setVariable('selTipoPacienteSel', 'selected="selected"');
                    break;
                case '1':
                    $this->tpl->setVariable('selTipoAmbulatorioSel', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selTipoPacienteHospitalizado', 'selected="selected"');
                    break;
//                case '3':
//                    $this->tpl->setVariable('selTipoPacienteDesconoce', 'selected="selected"');
//                    break;
            }
            $estaHospitalizado = $this->config['read']['per_hospitalizado'];
            switch ($estaHospitalizado) {
                case '0':
                    $this->tpl->setVariable('chkHospitalizadoNo', 'checked="checked"');
                    break;
                case '1':
                    $this->tpl->setVariable('chkHospitalizadoSi', 'checked="checked"');
                    break;
            }
            $hospitalizadoLugar = $this->config['read']['per_hospitalizado_lugar'];
            switch ($hospitalizadoLugar) {
                case '0':
                    $this->tpl->setVariable('selHospitalizadoSel', 'selected="selected"');
                    break;
                case '1':
                    $this->tpl->setVariable('selHospitalizadoObs', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selHospitalizadoSala', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selHospitalizadoUci', 'selected="selected"');
                    break;
            }

            $this->tpl->setVariable('valFechaFormulario', helperString::toDateView($this->config['read']['fecha_formulario']));
            $this->tpl->setVariable('valNombreInvestigador', htmlentities($this->config['read']['nombre_investigador']));
            $this->tpl->setVariable('valNombreRegistra', htmlentities($this->config['read']['nombre_registra']));

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
            // Carga catálogo de razones de tipo Id
            $tiposId = $this->config["catalogos"]["tipoId"];
            if (is_array($tiposId)) {
                foreach ($tiposId as $tipoId) {
                    $this->tpl->setCurrentBlock('blkTipoId');
                    $this->tpl->setVariable("valTipoId", $tipoId["id_tipo_identidad"]);
                    $this->tpl->setVariable("opcTipoId", htmlentities($tipoId["nombre_tipo"]));
                    $this->tpl->setVariable("selTipoId", ($tipoId["id_tipo_identidad"] == $this->config["read"]["tipo_identificacion"] ? 'selected="selected"' : ''));
                    $this->tpl->parse('blkTipoId');

                    $this->tpl->setCurrentBlock('blkPopTipoId');
                    $this->tpl->setVariable("valPopTipoId", $tipoId["id_tipo_identidad"]);
                    $this->tpl->setVariable("opcPopTipoId", htmlentities($tipoId["nombre_tipo"]));
                    $this->tpl->setVariable("selPopTipoId", ($tipoId["id_tipo_identidad"] == $this->config["read"]["tipo_identificacion"] ? 'selected="selected"' : ''));
                    $this->tpl->parse('blkPopTipoId');
                }
            }
            $this->tpl->setVariable('valIdentificador', $this->config['read']['numero_identificacion']);
            $this->tpl->setVariable('valReadOnly', 'readonly="readonly" disabled="disabled"');

            //Antecedentes
            $vac_tarjeta = $this->config['read']['vac_tarjeta'];
            switch ($vac_tarjeta) {
                case '0':
                    $this->tpl->setVariable('chkTarVacunaNo', 'checked="checked"');
                    break;
                case '1':
                    $this->tpl->setVariable('chkTarVacunaSi', 'checked="checked"');
                    break;
            }
            $vac_segun_esquema = $this->config['read']['vac_segun_esquema'];
            switch ($vac_segun_esquema) {
                case '0':
                    $this->tpl->setVariable('chkVacEsquemaNo', 'checked="checked"');
                    break;
                case '1':
                    $this->tpl->setVariable('chkVacEsquemaSi', 'checked="checked"');
                    break;
            }
            $this->tpl->setVariable('valFechaUltDosis', helperString::toDateViewOther($this->config['read']['vac_fecha_ultima_dosis']));
            $this->tpl->setVariable('valFechaAnioPrevio', helperString::toDateViewOther($this->config['read']['vac_fecha_anio_previo']));
            //Factor de riesgo
            $riesgo_embarazo = $this->config['read']['riesgo_embarazo'];
            switch ($riesgo_embarazo) {
                case '0':
                    $this->tpl->setVariable('selEmbarazoNo', 'selected="selected"');
                    break;
                case '1':
                    $this->tpl->setVariable('selEmbarazoSi', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selEmbarazoDesc', 'selected="selected"');
                    break;
            }

            $riesgo_trimestre = $this->config['read']['riesgo_trimestre'];
            switch ($riesgo_trimestre) {
                case '1':
                    $this->tpl->setVariable('selTrimestre1', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selTrimestre2', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selTrimestre3', 'selected="selected"');
                    break;
            }

            $riesgo_enf_cronica = $this->config['read']['riesgo_enf_cronica'];
            switch ($riesgo_enf_cronica) {
                case '1':
                    $this->tpl->setVariable('selCronicaSi', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('selCronicaNo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selCronicaDesc', 'selected="selected"');
                    break;
            }

            $riesgo_profesional = $this->config['read']['riesgo_profesional'];
            switch ($riesgo_profesional) {
                case '1':
                    $this->tpl->setVariable('selRiesgoSi', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('selRiesgoNo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selRiesgoDesc', 'selected="selected"');
                    break;
            }

            $riesgo_pro_cual = $this->config['read']['riesgo_pro_cual'];
            switch ($riesgo_pro_cual) {
                case '1':
                    $this->tpl->setVariable('selRiesgoCual1', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selRiesgoCual2', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selRiesgoCual3', 'selected="selected"');
                    break;
            }

            $riesgo_viaje = $this->config['read']['riesgo_viaje'];
            switch ($riesgo_viaje) {
                case '1':
                    $this->tpl->setVariable('selViajeSi', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('selViajeNo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selViajeDesc', 'selected="selected"');
                    break;
            }

            $this->tpl->setVariable('valViajeDonde', htmlentities($this->config['read']['riesgo_viaje_donde']));

            $riesgo_contacto_confirmado = $this->config['read']['riesgo_contacto_confirmado'];
            switch ($riesgo_contacto_confirmado) {
                case '1':
                    $this->tpl->setVariable('selContactoSi', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('selContactoNo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selContactoDesc', 'selected="selected"');
                    break;
            }

            $riesgo_contacto_tipo = $this->config['read']['riesgo_contacto_tipo'];
            switch ($riesgo_contacto_tipo) {
                case '0':
                    $this->tpl->setVariable('selContactoTipo0', 'selected="selected"');
                    break;
                case '1':
                    $this->tpl->setVariable('selContactoTipo1', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selContactoTipo2', 'selected="selected"');
                    break;
                case '3':
                    $this->tpl->setVariable('selContactoTipo3', 'selected="selected"');
                    break;
            }

            $riesgo_aislamiento = $this->config['read']['riesgo_aislamiento'];
            switch ($riesgo_aislamiento) {
                case '1':
                    $this->tpl->setVariable('selAislamientoSi', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('selAislamientoNo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selAislamientoDesc', 'selected="selected"');
                    break;
            }
            $this->tpl->setVariable('valNombreContacto', htmlentities($this->config['read']['riesgo_contacto_nombre']));

            //Datos Clinico
            $this->tpl->setVariable('valDatosClinicosCheckGripal', ($this->config['read']['eve_sindrome'] == '1' ? 'checked="checked"' : ''));
            $this->tpl->setVariable('valDatosClinicosCheckCentinela', ($this->config['read']['eve_centinela'] == '1' ? 'checked="checked"' : ''));
            $this->tpl->setVariable('valDatosClinicosCheckInusitado', ($this->config['read']['eve_inusitado'] == '1' ? 'checked="checked"' : ''));
            $this->tpl->setVariable('valDatosClinicosCheckImprevisto', ($this->config['read']['eve_imprevisto'] == '1' ? 'checked="checked"' : ''));
            $this->tpl->setVariable('valDatosClinicosCheckExcesivo', ($this->config['read']['eve_excesivo'] == '1' ? 'checked="checked"' : ''));
            $this->tpl->setVariable('valDatosClinicosCheckConglomerado', ($this->config['read']['eve_conglomerado'] == '1' ? 'checked="checked"' : ''));
            $this->tpl->setVariable('valDatosClinicosCheckNeumonia', ($this->config['read']['eve_neumo_bacteriana'] == '1' ? 'checked="checked"' : ''));
            $nombre_evento = $this->config['read']["cie_10_1"] . " - " . $this->config['read']["nombre_evento"];
            $this->tpl->setVariable('valDatosClinicosEventoId', $this->config['read']['id_evento']);
            $this->tpl->setVariable('valDatosClinicosEventoNombre', htmlentities($nombre_evento));


            $this->tpl->setVariable('valFechaInicioSintomas', helperString::toDateView($this->config['read']['fecha_inicio_sintoma']));
            $this->tpl->setVariable('valFechaHospitalizacion', helperString::toDateView($this->config['read']['fecha_hospitalizacion']));
            $this->tpl->setVariable('valFechaNotificacion', helperString::toDateView($this->config['read']['fecha_notificacion']));
            $this->tpl->setVariable('valFechaEgreso', helperString::toDateView($this->config['read']['fecha_egreso']));
            $this->tpl->setVariable('valFechaDefuncion', helperString::toDateView($this->config['read']['fecha_defuncion']));

            $antibiotico = $this->config['read']['antibiotico'];
            switch ($antibiotico) {
                case '1':
                    $this->tpl->setVariable('selAntiUltimaSemanaSi', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('selAntiUltimaSemanaNo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selAntiUltimaSemanaDesc', 'selected="selected"');
                    break;
            }
            $this->tpl->setVariable('valAntibioticosCual', htmlentities($this->config['read']['antibiotico_cual']));
            $this->tpl->setVariable('valAntibioticosFecha', helperString::toDateView($this->config['read']['antibiotico_fecha']));

            $antibiotico = $this->config['read']['antiviral'];
            switch ($antibiotico) {
                case '1':
                    $this->tpl->setVariable('selAntiviralesSi', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('selAntiviralesNo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selAntiviralesDesc', 'selected="selected"');
                    break;
            }
            $this->tpl->setVariable('valAntiviralesCual', htmlentities($this->config['read']['antiviral_cual']));
            $this->tpl->setVariable('valAntiviralesFecha', helperString::toDateView($this->config['read']['antiviral_fecha']));

            $sintoma_fiebre = $this->config['read']['sintoma_fiebre'];
            switch ($sintoma_fiebre) {
                case '1':
                    $this->tpl->setVariable('selFiebreSi', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('selFiebreNo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selFiebreDesc', 'selected="selected"');
                    break;
            }
            $this->tpl->setVariable('valFechaFiebre', helperString::toDateView($this->config['read']['fecha_fiebre']));
            $sintoma_tos = $this->config['read']['sintoma_tos'];
            switch ($sintoma_tos) {
                case '1':
                    $this->tpl->setVariable('selTosSi', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('selTosNo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selTosDesc', 'selected="selected"');
                    break;
            }
            $this->tpl->setVariable('valFechaTos', helperString::toDateView($this->config['read']['fecha_tos']));
            $sintoma_garganta = $this->config['read']['sintoma_garganta'];
            switch ($sintoma_garganta) {
                case '1':
                    $this->tpl->setVariable('selGargantaSi', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('selGargantaNo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selGargantaDesc', 'selected="selected"');
                    break;
            }
            $this->tpl->setVariable('valFechaGarganta', helperString::toDateView($this->config['read']['fecha_garganta']));
            $sintoma_rinorrea = $this->config['read']['sintoma_rinorrea'];
            switch ($sintoma_rinorrea) {
                case '1':
                    $this->tpl->setVariable('selRinorreaSi', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('selRinorreaNo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selRinorreaDesc', 'selected="selected"');
                    break;
            }
            $this->tpl->setVariable('valFechaRinorrea', helperString::toDateView($this->config['read']['fecha_rinorrea']));
            $sintoma_respiratoria = $this->config['read']['sintoma_respiratoria'];
            switch ($sintoma_respiratoria) {
                case '1':
                    $this->tpl->setVariable('selRespiratoriaSi', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('selRespiratoriaNo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selRespiratoriaDesc', 'selected="selected"');
                    break;
            }
            $this->tpl->setVariable('valFechaRespiratoria', helperString::toDateView($this->config['read']['fecha_respiratoria']));
            $sintoma_otro = $this->config['read']['sintoma_otro'];
            switch ($sintoma_otro) {
                case '1':
                    $this->tpl->setVariable('selHallazgoOtroSi', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('selHallazgoOtroNo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selHallazgoOtroDesc', 'selected="selected"');
                    break;
            }
            $this->tpl->setVariable('valFechaHallazgoOtro', helperString::toDateView($this->config['read']['fecha_otro']));
            $this->tpl->setVariable('valHallazgoOtroNombre', htmlentities($this->config['read']['sintoma_nombre_otro']));

            $torax_condensacion = $this->config['read']['torax_condensacion'];
            switch ($torax_condensacion) {
                case '1':
                    $this->tpl->setVariable('selCondensacionSi', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('selCondensacionNo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selCondensacionDesc', 'selected="selected"');
                    break;
            }
            $torax_derrame = $this->config['read']['torax_derrame'];
            switch ($torax_derrame) {
                case '1':
                    $this->tpl->setVariable('selPleuralSi', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('selPleuralNo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selPleuralDesc', 'selected="selected"');
                    break;
            }
            $torax_broncograma = $this->config['read']['torax_broncograma'];
            switch ($torax_broncograma) {
                case '1':
                    $this->tpl->setVariable('selBroncogramaSi', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('selBroncogramaNo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selBroncogramaDesc', 'selected="selected"');
                    break;
            }
            $torax_infiltrado = $this->config['read']['torax_infiltrado'];
            switch ($torax_infiltrado) {
                case '1':
                    $this->tpl->setVariable('selInfiltradoSi', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('selInfiltradoNo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selInfiltradoDesc', 'selected="selected"');
                    break;
            }
            $torax_otro = $this->config['read']['torax_otro'];
            switch ($torax_otro) {
                case '1':
                    $this->tpl->setVariable('selResultadoOtroSi', 'selected="selected"');
                    break;
                case '0':
                    $this->tpl->setVariable('selResultadoOtroNo', 'selected="selected"');
                    break;
                case '2':
                    $this->tpl->setVariable('selResultadoOtroDesc', 'selected="selected"');
                    break;
            }
            $this->tpl->setVariable('resultadoOtroNombre', htmlentities($this->config['read']['torax_nombre_otro']));

            $tipoMuestrasTotal = "";
            if (isset($this->config['tipoMuestras'])) {
                foreach ($this->config['tipoMuestras'] as $tipoMuestra) {
                    $tipoMuestrasTotal .= $tipoMuestra['id_cat_muestra_laboratorio'] . "-" .
                            $tipoMuestra['nombre_muestra_laboratorio'] . "-" .
                            helperString::toDateView($tipoMuestra['fecha_toma']) . "-" .
                            helperString::toDateView($tipoMuestra['fecha_envio']) . "-" .
                            helperString::toDateView($tipoMuestra['fecha_recibo_laboratorio']) . "###";
                }
                $tipoMuestrasTotal = substr($tipoMuestrasTotal, 0, strlen($tipoMuestrasTotal) - 3);
                $this->tpl->setVariable('valMuestrasUceti', $tipoMuestrasTotal);
//                echo $tipoMuestrasTotal;
            }
            $this->tpl->setVariable('valNombreTomaMuestra', htmlentities($this->config['read']['nombre_toma_muestra']));

            $enfermedadTotal = "";
            if (isset($this->config['enfermedades'])) {
                foreach ($this->config['enfermedades'] as $enfermedad) {
                    $enfermedadTotal .= $enfermedad['id_cat_enfermedad_cronica'] . "-" . $enfermedad['resultado'] . "-" . $enfermedad['nombre_enfermedad_cronica'] . "###";
                }
                $enfermedadTotal = substr($enfermedadTotal, 0, strlen($enfermedadTotal) - 3);
                $this->tpl->setVariable('valEnfermedadesRelacionados', htmlentities($enfermedadTotal));
                //echo $enfermedadTotal;
            }

            $vacunas = $this->config['vacunas'];
            $vacunaTotal = "";
            if (isset($vacunas)) {
                foreach ($vacunas as $vacuna) {
                    $fechaVac = helperString::toDateView($vacuna['fecha']);
                    $desconoceVac = $vacuna['desconoce'];
                    if ($fechaVac == NULL)
                        $fechaVac = "";
                    if ($desconoceVac == "-1")
                        $desconoceVac = "99";
                    $vacunaTotal .= $vacuna['id_cat_antecendente_vacunal'] . "-" .
                            $vacuna['dosis'] . "-" .
                            $desconoceVac . "-" .
                            $fechaVac . "-" .
                            $vacuna['nombre_antecendente_vacunal'] . "###";
                    //echo $fechaVac."<br/>";
                }
                $vacunaTotal = substr($vacunaTotal, 0, strlen($vacunaTotal) - 3);
                //echo $vacunaTotal."<br/>";
                $this->tpl->setVariable('valVacunasRelacionados', htmlentities($vacunaTotal));
            }

            $pendiente_silab = $this->config['read']['pendiente_silab'];
            $actualizacion_silab = $this->config['read']['actualizacion_silab'];
//            print_r($this->config['read']);
//            echo "silab ".$pendiente_silab;
            switch ($pendiente_silab) {
                case '1':
                    $this->tpl->setVariable('valEstadoSilab', '<img width=16 height=16 src="../img/iconos/valido.png"> Muestra de silab - Actualizada el ' . $actualizacion_silab);
                    $this->tpl->setVariable('disBorrarSilab', '');
                    $this->tpl->setVariable('disBuscarSilab', 'none');
                    break;
                case '0':
                    $this->tpl->setVariable('valEstadoSilab', '<img width=16 height=16 src="../img/iconos/pendiente.png"> Pendiente de muestra');
                    $this->tpl->setVariable('disBorrarSilab', 'none');
                    $this->tpl->setVariable('disBuscarSilab', '');
                    break;
            }

            $muestras = muestraSilab::construirMuestraSilabUceti($this->config["muestras"]);
            $muestraSplit = explode("###", $muestras);
            if (isset($muestraSplit[1]) && $muestraSplit[1] != '') {
                $this->tpl->setVariable('valEstadoSilab', '<img width=16 height=16 src="../img/iconos/valido.png"> Muestra de SILAB - Actualizada');
                $this->tpl->setVariable('valResultadoSilab', $muestraSplit[0]);
                $this->tpl->setVariable('valGlobalMuestras', $muestraSplit[1]);
//                echo $muestraSplit[1];
                $this->tpl->setVariable('valGlobalPruebas', $muestraSplit[2]);
            } else {
                $this->tpl->setVariable('valEstadoSilab', '<img width=16 height=16 src="../img/iconos/pendiente.png"> Sin Muestra de SILAB');
                $this->tpl->setVariable('valResultadoSilab', '');
                $this->tpl->setVariable('valGlobalMuestras', '');
                $this->tpl->setVariable('valGlobalPruebas', '');
            }
        }

        //$this->tpl->setVariable('disError',"");
        //$this->tpl->setVariable('mensajeErrorGeneral',"Esto es una prueba de error desde php");
        // Muestra si ocurrió un error
        $this->tpl->setVariable("mensajeErrorGeneral", $this->config['Merror']);
        $this->tpl->setVariable("disError", (isset($this->config['Merror']) ? '' : 'none'));

        // Muestra botones GUARDAR y CANCELAR según permisos
        require_once ('libs/caus/clsCaus.php');
        if (!$lectura) {
            if (clsCaus::validarSeccion(ConfigurationCAUS::influenza, ConfigurationCAUS::Agregar)) {
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarUceti();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
                $this->tpl->setVariable("botonGuardarPrevio", '<a href="javascript:guardadoPrevioUceti(0);" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardado Previo</a>&nbsp;');
            }
        } else {
            if (clsCaus::validarSeccion(ConfigurationCAUS::influenza, ConfigurationCAUS::Agregar)) {
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarUceti();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Actualizar</a>&nbsp;');
                $this->tpl->setVariable("botonGuardarPrevio", '<a href="javascript:guardadoPrevioUceti(0);" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardado Previo</a>&nbsp;');
            }
        }
        if (clsCaus::validarSeccion(ConfigurationCAUS::influenza, ConfigurationCAUS::Consultar))
            $this->tpl->setVariable("botonCancelar", '<a href="index.php" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');

        $this->tpl->touchBlock('contentBlock');
    }

}

//            $this->tpl->setVariable('valPrimerNombre', $this->config['read']['primer_nombre']);
//            $this->tpl->setVariable('valSegundoNombre', $this->config['read']['segundo_nombre']);
//            $this->tpl->setVariable('valPrimerApellido', $this->config['read']['primer_apellido']);
//            $this->tpl->setVariable('valSegundoApellido', $this->config['read']['segundo_apellido']);
//            $fechaNacimiento = substr($this->config['read']['fecha_nacimiento'], 0, 10);
//            $this->tpl->setVariable('valFechaNacimiento', helperString::toDateView($fechaNacimiento));
//            
//            $tipoEdad = $this->config['read']['tipo_edad'];
//            switch ($tipoEdad) {
//                case '1':
//                    $this->tpl->setVariable('selDias', 'selected="selected"');
//                    break;
//                case '2':
//                    $this->tpl->setVariable('selMeses', 'selected="selected"');
//                    break;
//                case '3':
//                    $this->tpl->setVariable('selAnios', 'selected="selected"');
//                    break;
//            }
//            $this->tpl->setVariable('valEdad', $this->config['read']['edad']);
//            
//            $sexo = $this->config['read']['sexo'];
//            switch ($sexo) {
//                case 'M':
//                    $this->tpl->setVariable('selSexoM', 'selected="selected"');
//                    break;
//                case 'F':
//                    $this->tpl->setVariable('selSexoF', 'selected="selected"');
//                    break;
//            }
//            
//            $this->tpl->setVariable('valNombreResponsable', $this->config['read']['nombre_responsable']);
//            $this->tpl->setVariable('valDireccionIndividuo', $this->config['read']['dir_referencia']);
//            $this->tpl->setVariable('valOtraDireccion', $this->config['read']['dir_trabajo']);
//            $this->tpl->setVariable('valTelefono', $this->config['read']['tel_residencial']);
?>
