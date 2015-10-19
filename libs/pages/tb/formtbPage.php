<?php

require_once ('libs/pages/page.php');
require_once ('libs/pages/tb/auxtbPage.php');
require_once ('libs/TemplateHelp.php');

class formtb extends page {

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

        $this->tpl->addBlockFile('CONTENT', 'contentBlock', Configuration::templatesPath . 'tb/formulario.tpl.html');
        $this->tpl->setVariable('urlPrefix', Configuration::getUrlprefix());

//        $relleno = "";
//        $totalCatalogos = helperCatalogos::getAntecedentesVacunalesTotalInfluenza()-2;
//        for ($i = 0; $i < $totalCatalogos; $i++) {
//            $relleno.='<tr><td>&nbsp;</td></tr>';
//        }
//        $this->tpl->setVariable('rellenoFechasAntecedente', $relleno);

        // ESTADO DE LA MUESTRA
//                $this->tpl->setVariable('estadoMuestra',$this->config["data"]["SIT_ID"]);
//                $this->tpl->setVariable('idMuestra',$this->config["data"]["MUE_ID"]);                

        $this->tpl->setVariable("disInfo", $this->config["info"] != "" ? '' : 'none');
        $this->tpl->setVariable("desInfo", $this->config["info"]);

        $this->tpl->setVariable("silab_local", ConfigurationHospitalInfluenza::getSilabLocal());
//        $this->tpl->setVariable("silab_remoto", Etiquetas::silab_remoto);

        if ($this->config["error"]) {
            $this->tpl->setVariable('mensajeError', '');
            $this->tpl->setVariable('mostrarError', '');
            $this->tpl->setVariable('valError', $this->config["mensaje"]);
        } else {
            $this->tpl->setVariable('mensajeError', 'none');
            $this->tpl->setVariable('mostrarError', 'none');
            $this->tpl->setVariable('valError', '');
        }

        // Catalogo de poblaciones
        $poblaciones = $this->config["catalogos"]["poblacion"];
        if (is_array($poblaciones)) {
            foreach ($poblaciones as $poblacion) {
                $this->tpl->setCurrentBlock('blkPoblacion');
                $this->tpl->setVariable("valPoblacion", $poblacion["id_gpopoblacional"]);
                $this->tpl->setVariable("opcPoblacion", htmlentities($poblacion["nombre_gpopoblacional"]));
                if ($this->config["read"])
                    $this->tpl->setVariable("selPoblacion", ($poblacion["id_gpopoblacional"] == $this->config["read"]["id_gpopoblacional"]) ? 'selected="selected"' : '');

                $this->tpl->parse('blkPoblacion');
            }
        }
        
        // Catalogo de etnias
        $etnias = $this->config["catalogos"]["etnia"];
        if (is_array($etnias)) {
            foreach ($etnias as $etnia) {
                $this->tpl->setCurrentBlock('blkEtnia');
                $this->tpl->setVariable("valEtnia", $etnia["id_etnia"]);
                $this->tpl->setVariable("opcEtnia", htmlentities($etnia["nombre_etnia"]));
                if ($this->config["read"])
                    $this->tpl->setVariable("selEtnia", ($etnia["id_etnia"] == $this->config["read"]["id_etnia"]) ? 'selected="selected"' : '');

                $this->tpl->parse('blkEtnia');
            }
        }
        // Profesion TB
        
        $profesiones = $this->config["catalogos"]["profesion"];
        if (is_array($profesiones)) {
            foreach ($profesiones as $profesion) {
                $this->tpl->setCurrentBlock('blkProfesion');
                $this->tpl->setVariable("valProfesion", $profesion["id_profesion"]);
                $this->tpl->setVariable("opcProfesion", htmlentities($profesion["nombre_profesion"]));
                if ($this->config["read"])
                    $this->tpl->setVariable("selProfesion", ($profesion["id_profesion"] == $this->config["read"]["id_profesion"]) ? 'selected="selected"' : '');

                $this->tpl->parse('blkProfesion');
            }
        }
        
        $this->tpl->setVariable('valotrosProfesion', $this->config['read']['otrosprofesion']);
        
        // Antecedentes MDR
        $GrupoMDR = $this->config["catalogos"]["MDR"];
        if (is_array($GrupoMDR)) {
            foreach ($GrupoMDR as $grupomdr) {
                // Preseleccionar la opcion del formulario
//                echo " //// ";
//                print_r($grupomdr);
//                print_r($this->config["MDR"]);
//                echo " +++ <br/>";
                if (is_array($this->config["MDR"]))
                    $grupomdr["chkGrupoMDR"] = !in_array($grupomdr["id_grupo_riesgo_MDR"], $this->config["MDR"]) ? '' : 'checked="checked"';

                $this->tpl->setCurrentBlock('blkGrupoMDR');
                $this->tpl->setVariable($grupomdr);
                $this->tpl->parse('blkGrupoMDR');
            }
        }

        // Inmunodepresor
        $Inmonodepresores = $this->config["catalogos"]["inmunodepresor"];
        if (is_array($Inmonodepresores)) {
            foreach ($Inmonodepresores as $Inmunodepresor) {
                // Preseleccionar la opcion del formulario
                if (is_array($this->config["Inmunodepresor"]))
                    $Inmunodepresor["chkInmunodepresor"] = !in_array($Inmunodepresor["id_inmunodepresor"], $this->config["Inmunodepresor"]) ? '' : 'checked="checked"';

                $this->tpl->setCurrentBlock('blkInmunodepresor');
                $this->tpl->setVariable($Inmunodepresor);
                $this->tpl->parse('blkInmunodepresor');
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
                        $this->tpl->setVariable("selDisIndividuo", ($corregimiento["id_corregimiento"] == $this->config["data"]["individuo"]["idCorregimiento"] ? 'selected="selected"' : ''));

                    $this->tpl->parse('blkCorIndividuo');
                }
            }
        }
        
        
        $this->tpl = auxPagetb::auxparseHTMLVIH($this->tpl,$this->config);
        
        $this->tpl = auxPagetb::auxparseHTMLControl($this->tpl,$this->config);
        
        $this->tpl = auxPagetb::auxparseHTMLContactos($this->tpl,$this->config);
        

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

            $this->tpl->setVariable('valEstadoSilab', '<img width=16 height=16 src="../img/iconos/pendiente.png"> Pendiente de muestra');
            $this->tpl->setVariable('disBorrarSilab', 'none');
            $this->tpl->setVariable('disBuscarSilab', '');
            $this->tpl->setVariable('chkTarVacunaSi', 'checked="checked"');

            $valNotificacionIdUn = '';
            $valNotificacionUnidad = '';
            $lista = clsCaus::obtenerUbicaciones(ConfigurationCAUS::Localidad);
            if (is_array($lista)) {
                foreach ($lista as $value) {
                    $valNotificacionIdUn = $value;
                    $valNotificacionUnidad = helperLugar::getUnidadNotificadoraNombre($valNotificacionIdUn);
                    break;
                }
            }

            $this->tpl->setVariable('valNotificacionUnidad', htmlentities($valNotificacionUnidad));
            $this->tpl->setVariable('valNotificacionIdUn', $valNotificacionIdUn);
            $this->tpl->setVariable('valUnidadNoDisponible', '');
            
            $this->tpl->setVariable('selResClinicoS', 'selected="selected"');
            $this->tpl->setVariable('selRXS', 'selected="selected"');
            $this->tpl->setVariable('selResWRDS', 'selected="selected"'); 
            $this->tpl->setVariable('selHistopaS', 'selected="selected"'); 
            
            // Aquí empieza la modificacion de registro //
        } else if ($lectura) {
            $this->tpl->setVariable('buscarPaciente', 'none');
            $this->tpl->setVariable('action', 'M');
            $this->tpl->setVariable('valIdtb', $this->config['read']['id_tb']);
//            print_r($this->config['read']);
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


            $this->tpl->setVariable('valFechaFormulario', helperString::toDateView($this->config['read']['fecha_formulario']));
            $this->tpl->setVariable('valNombreInvestigador', htmlentities($this->config['read']['nombre_investigador']));
            $this->tpl->setVariable('valNombreRegistra', htmlentities($this->config['read']['nombre_registra']));

            $this->tpl->setVariable('valFechaNotificacion', helperString::toDateView($this->config["read"]["fecha_notificacion"]));
            
            //Individuo
            $this->tpl->setVariable('chkAsegurado'.$this->config['read']['per_asegurado'], 'checked="checked"');
            
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
            if ($this->config["read"]["tipo_identificacion"]==1){
                $arrayIdentificador = explode("-",$this->config['read']['numero_identificacion']);
                $this->tpl->setVariable('valIdentificador1', $arrayIdentificador[0]);
                $this->tpl->setVariable('valIdentificador2', $arrayIdentificador[1]);
                $this->tpl->setVariable('valIdentificador3', $arrayIdentificador[2]);
            }
            
            $this->tpl->setVariable('valReadOnly', 'readonly="readonly" disabled="disabled"');


          
            // Empleado
            $this->tpl->setVariable('selEmpleado'.$this->config['read']['per_empleado'], 'selected="selected"');
            $this->tpl->setVariable('selAntesPreso'.$this->config['read']['per_antes_preso'], 'selected="selected"');
            $this->tpl->setVariable('valFechaAntesPreso', helperString::toDateView($this->config['read']['per_fecha_antespreso']));
            
            // Embarazo
            $this->tpl->setVariable('selEmbarazo'.$this->config['read']['riesgo_embarazo'], 'selected="selected"');
            
            $this->tpl->setVariable('valResponsable', $this->config['read']['per_nombre_referencia']);
            $this->tpl->setVariable('valParentesco', $this->config['read']['per_parentesco']);
            $this->tpl->setVariable('valTelefonoFamiliar', $this->config['read']['per_telefono_referencia']);

            
            // Antecedentes
            $this->tpl->setVariable('selDiabetes'.$this->config['read']['ant_diabetes'], 'selected="selected"');
            $this->tpl->setVariable('selPreso'.$this->config['read']['ant_preso'], 'selected="selected"');
            $this->tpl->setVariable('selTiempoPreso'.$this->config['read']['ant_tiempo_preso'], 'selected="selected"');
            $this->tpl->setVariable('valFechaPreso', helperString::toDateView($this->config['read']['ant_fecha_preso']));
            $this->tpl->setVariable('selDroga'.$this->config['read']['ant_drug'], 'selected="selected"');
            $this->tpl->setVariable('selAlcholismo'.$this->config['read']['ant_alcoholism'], 'selected="selected"');
            $this->tpl->setVariable('selSmoking'.$this->config['read']['ant_smoking'], 'selected="selected"');
            $this->tpl->setVariable('selSmoking'.$this->config['read']['ant_smoking'], 'selected="selected"');
            $this->tpl->setVariable('selMining'.$this->config['read']['ant_mining'], 'selected="selected"');
            $this->tpl->setVariable('selOvercrow'.$this->config['read']['ant_overcrowding'], 'selected="selected"');
            $this->tpl->setVariable('selIndigence'.$this->config['read']['ant_indigence'], 'selected="selected"');
            $this->tpl->setVariable('selDrinkable'.$this->config['read']['ant_drinkable'], 'selected="selected"');
            $this->tpl->setVariable('selSanitation'.$this->config['read']['ant_sanitation'], 'selected="selected"');
            $this->tpl->setVariable('selSanitation'.$this->config['read']['ant_sanitation'], 'selected="selected"');
            $this->tpl->setVariable('selContactposi'.$this->config['read']['ant_contactposi'], 'selected="selected"');
            $this->tpl->setVariable('selBCG'.$this->config['read']['ant_BCG'], 'selected="selected"');
            $this->tpl->setVariable('valWeight', $this->config['read']['ant_weight']);
            $this->tpl->setVariable('valHeight', $this->config['read']['ant_height']);
            
            //Metodo de diagnostico
            // BK
            $this->tpl->setVariable('valFechaBK1', helperString::toDateView($this->config['read']['mat_diag_fecha_BK1']));
            $this->tpl->setVariable('selResBK1'.$this->config['read']['mat_diag_resultado_BK1'], 'selected="selected"');
            $this->tpl->setVariable('selClasBK1'.$this->config['read']['id_clasificacion_BK1'], 'selected="selected"');
            
            $this->tpl->setVariable('valFechaBK2', helperString::toDateView($this->config['read']['mat_diag_fecha_BK2']));
            $this->tpl->setVariable('selResBK2'.$this->config['read']['mat_diag_resultado_BK2'], 'selected="selected"');
            $this->tpl->setVariable('selClasBK2'.$this->config['read']['id_clasificacion_BK2'], 'selected="selected"');            
            
            $this->tpl->setVariable('valFechaBK3', helperString::toDateView($this->config['read']['mat_diag_fecha_BK3']));
            $this->tpl->setVariable('selResBK3'.$this->config['read']['mat_diag_resultado_BK3'], 'selected="selected"');
            $this->tpl->setVariable('selClasBK3'.$this->config['read']['id_clasificacion_BK3'], 'selected="selected"');          
            
            // Cultivo
            $this->tpl->setVariable('selResCult'.$this->config['read']['mat_diag_res_cultivo'], 'selected="selected"'); 
            $this->tpl->setVariable('valFechaCultivo', helperString::toDateView($this->config['read']['mat_diag_fecha_res_cultivo']));

            // WRD
            $this->tpl->setVariable('selMetWRD'.$this->config['read']['mat_diag_metodo_WRD'], 'selected="selected"');
            $this->tpl->setVariable('selResWRD'.($this->config['read']['mat_diag_res_metodo_WRD'] >= 0  ?  $this->config['read']['mat_diag_res_metodo_WRD'] : 'S'  ), 'selected="selected"'); 
            $this->tpl->setVariable('valFechaWRD', helperString::toDateView($this->config['read']['mat_diag_fecha_res_WRD']));
            
            // Clinico
//            $this->tpl->setVariable('selResClinico'.$this->config['read']['mat_diag_res_clinico'], 'selected="selected"'); 
            $this->tpl->setVariable('selResClinico'.($this->config['read']['mat_diag_res_clinico'] >= 0  ?  $this->config['read']['mat_diag_res_clinico'] : 'S' ), 'selected="selected"');
            $this->tpl->setVariable('valFechaClinico', helperString::toDateView($this->config['read']['mat_diag_fecha_clinico']));

            // R-X
//            $this->tpl->setVariable('selRX'.$this->config['read']['mat_diag_res_R_X'], 'selected="selected"'); 
            $this->tpl->setVariable('selRX'.($this->config['read']['mat_diag_res_R_X'] >= 0  ? $this->config['read']['mat_diag_res_R_X'] : 'S' ), 'selected="selected"');
            $this->tpl->setVariable('valFechaRX', helperString::toDateView($this->config['read']['mat_diag_fecha_R_X']));
            
            // Histopatología
            $this->tpl->setVariable('selHistopa'.($this->config['read']['mat_diag_res_histopa'] >= 0  ?  $this->config['read']['mat_diag_res_histopa'] : 'S'  ), 'selected="selected"'); 
            $this->tpl->setVariable('valFechaHistopa', helperString::toDateView($this->config['read']['mat_diag_fecha_histopa']));
            
            
            // Clasificiacion
            
            $this->tpl->setVariable('chkEP'.$this->config['read']['clas_pulmonar_EP'], 'checked=checked"');
            $this->tpl->setVariable('selLugarEP'.$this->config['read']['clas_lugar_EP'], 'selected="selected"'); 
 
            $this->tpl->setVariable('chkHisto'.$this->config['read']['clas_trat_previo'], 'checked=checked"');
            
            $this->tpl->setVariable('selRecaida'.$this->config['read']['clas_recaida'], 'selected="selected"'); 
            $this->tpl->setVariable('selPostFra'.$this->config['read']['clas_postfracaso'], 'selected="selected"');
            $this->tpl->setVariable('selPerSeg'.$this->config['read']['clas_perdsegui'], 'selected="selected"');
            $this->tpl->setVariable('selOtrAT'.$this->config['read']['clas_otros_antestratado'], 'selected="selected"');
            
            $this->tpl->setVariable('selDiagVIH'.$this->config['read']['clas_diag_VIH'], 'selected="selected"');
            $this->tpl->setVariable('valFechaDiagVIH', helperString::toDateView($this->config['read']['clas_fecha_diag_VIH']));
            
            $this->tpl->setVariable('chkMetDiag'.$this->config['read']['clas_met_diag'], 'checked=checked"');
            
            
            $this->tpl->setVariable('selMonoR'.$this->config['read']['clas_esp_MonoR'], 'selected="selected"');
            
            if ($this->config['read']['clas_PoliR_H'] != "")
                $this->tpl->setVariable('chkPoliR_H', 'checked=checked"');

            if ($this->config['read']['clas_PoliR_R'] != "")
                $this->tpl->setVariable('chkPoliR_R', 'checked=checked"');
            
            if ($this->config['read']['clas_PoliR_Z'] != "")
                $this->tpl->setVariable('chkPoliR_Z', 'checked=checked"');
            
            if ($this->config['read']['clas_PoliR_E'] != "")
                $this->tpl->setVariable('chkPoliR_E', 'checked=checked"');
            
            if ($this->config['read']['clas_PoliR_S'] != "")
                $this->tpl->setVariable('chkPoliR_S', 'checked=checked"');
            
            if ($this->config['read']['clas_PoliR_fluoroquinolonas'] != "")
                $this->tpl->setVariable('PoliR_Fluoro', 'checked=checked"');

            if ($this->config['read']['clas_PoliR_2linea'] != "")
                $this->tpl->setVariable('PoliR_Inyec', 'checked=checked"');
            
            $this->tpl->setVariable('selFluoro'.$this->config['read']['clas_id_fluoroquinolonas'], 'selected="selected"');
            $this->tpl->setVariable('selIny2linea'.$this->config['read']['clas_id_2linea'], 'selected="selected"');
            
            // Tratamiento
            
            $this->tpl->setVariable('selReferido'.$this->config['read']['trat_referido'], 'selected="selected"');
            $this->tpl->setVariable('valInstSaludRef', $this->config['read']['trat_inst_salud_ref']);

            $this->tpl->setVariable('valFechaIniF1', helperString::toDateView($this->config['read']['trat_fecha_inicio_tratF1']));
            
            if ($this->config['read']['trat_med_H_F1'] != "")
                $this->tpl->setVariable('chkTratF1_H', 'checked=checked"');
            if ($this->config['read']['trat_med_Z_F1'] != "")
                $this->tpl->setVariable('chkTratF1_Z', 'checked=checked"');
            if ($this->config['read']['trat_med_R_F1'] != "")
                $this->tpl->setVariable('chkTratF1_R', 'checked=checked"');
            if ($this->config['read']['trat_med_E_F1'] != "")
                $this->tpl->setVariable('chkTratF1_E', 'checked=checked"');
            if ($this->config['read']['trat_med_S_F1'] != "")
                $this->tpl->setVariable('chkTratF1_S', 'checked=checked"');
            if ($this->config['read']['trat_med_otros_F1'] != "")
                $this->tpl->setVariable('chkTratF1_Otr', 'checked=checked"');
            
            $this->tpl->setVariable('valFechaFinF1', helperString::toDateView($this->config['read']['trat_fecha_fin_tratF1']));
            $this->tpl->setVariable('selAdmF1'.$this->config['read']['id_adm_tratamiento_F1'], 'selected="selected"');
            
            
            $this->tpl->setVariable('valFechaIniF2', helperString::toDateView($this->config['read']['trat_fecha_inicio_tratF2']));
            
            if ($this->config['read']['trat_med_H_F2'] != "")
                $this->tpl->setVariable('chkTratF2_H', 'checked=checked"');
            if ($this->config['read']['trat_med_R_F2'] != "")
                $this->tpl->setVariable('chkTratF2_R', 'checked=checked"');
            if ($this->config['read']['trat_med_E_F2'] != "")
                $this->tpl->setVariable('chkTratF2_E', 'checked=checked"');
            if ($this->config['read']['trat_med_otros_F2'] != "")
                $this->tpl->setVariable('chkTratF2_Otr', 'checked=checked"');
            
            $this->tpl->setVariable('valFechaFinF2', helperString::toDateView($this->config['read']['trat_fecha_fin_tratF2']));
            $this->tpl->setVariable('selAdmF2'.$this->config['read']['id_adm_tratamiento_F2'], 'selected="selected"');   
            
            


            
             // Visitas
            
              $this->tpl->setVariable('selSocial'.$this->config['read']['apoyo_social'], 'selected="selected"');  
              $this->tpl->setVariable('selNutricional'.$this->config['read']['apoyo_nutricional'], 'selected="selected"');  
              $this->tpl->setVariable('selEconomico'.$this->config['read']['apoyo_economico'], 'selected="selected"');  
 
            
              
            $Visitas_blk = $this->config["catalogos"]["visitas"];
            if (is_array($Visitas_blk)) {
                foreach ($Visitas_blk as $visita) {
                    $this->tpl->setCurrentBlock('blkVisitaDetalle');
                    $this->tpl->setVariable("visita_indice", $visita["id_tb_visita"]);
                    $this->tpl->setVariable("id_tipo_visita", $visita["id_tipo_visita"]);
                    $this->tpl->setVariable("id_tb_visita", $visita["id_tb_visita"]);
                    $this->tpl->setVariable("nombre_visita", htmlentities($visita["nombre_tipo_visita"]));
                    $this->tpl->setVariable("fecha_visita",helperString::toDateView($visita["fecha_visita"]) );
                    $this->tpl->parse('blkVisitaDetalle');
                }
            }
              
            //Egreso
            $this->tpl->setVariable('valFechaEgreso', helperString::toDateView($this->config['read']['egreso_fecha_egreso']));
            $this->tpl->setVariable('selCondEgreso'.$this->config['read']['egreso_cond_egreso'], 'selected="selected"');
            $this->tpl->setVariable('selMotExcl'.$this->config['read']['egreso_motivo_exclusion'], 'selected="selected"');
            
//
//            $this->tpl->setVariable('valFechaEgreso', helperString::toDateView($this->config['read']['fecha_egreso']));

//            $tipoMuestrasTotal = "";
//            if (isset($this->config['tipoMuestras'])) {
//                foreach ($this->config['tipoMuestras'] as $tipoMuestra) {
//                    $tipoMuestrasTotal .= $tipoMuestra['id_cat_muestra_laboratorio'] . "-" .
//                            $tipoMuestra['nombre_muestra_laboratorio'] . "-" .
//                            helperString::toDateView($tipoMuestra['fecha_toma']) . "-" .
//                            helperString::toDateView($tipoMuestra['fecha_envio']) . "-" .
//                            helperString::toDateView($tipoMuestra['fecha_recibo_laboratorio']) . "###";
//                }
//                $tipoMuestrasTotal = substr($tipoMuestrasTotal, 0, strlen($tipoMuestrasTotal) - 3);
//                $this->tpl->setVariable('valMuestrasUceti', $tipoMuestrasTotal);
////                echo $tipoMuestrasTotal;
//            }
//            $this->tpl->setVariable('valNombreTomaMuestra', htmlentities($this->config['read']['nombre_toma_muestra']));

//            $enfermedadTotal = "";
//            if (isset($this->config['enfermedades'])) {
//                foreach ($this->config['enfermedades'] as $enfermedad) {
//                    $enfermedadTotal .= $enfermedad['id_cat_enfermedad_cronica'] . "-" . $enfermedad['resultado'] . "-" . $enfermedad['nombre_enfermedad_cronica'] . "###";
//                }
//                $enfermedadTotal = substr($enfermedadTotal, 0, strlen($enfermedadTotal) - 3);
//                $this->tpl->setVariable('valEnfermedadesRelacionados', htmlentities($enfermedadTotal));
//                //echo $enfermedadTotal;
//            }


            $pendiente_silab = $this->config['read']['pendiente_silab'];
            $actualizacion_silab = $this->config['read']['actualizacion_silab'];

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
//        require_once ('libs/caus/clsCaus.php');
//        if (!$lectura) {
//            if (clsCaus::validarSeccion(ConfigurationCAUS::influenza, ConfigurationCAUS::Agregar)) {
//                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarUceti();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
//                $this->tpl->setVariable("botonGuardarPrevio", '<a href="javascript:guardadoPrevioUceti(0);" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardado Previo</a>&nbsp;');
//            }
//        } else {
//            if (clsCaus::validarSeccion(ConfigurationCAUS::influenza, ConfigurationCAUS::Agregar)) {
//                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarUceti();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Actualizar</a>&nbsp;');
//                $this->tpl->setVariable("botonGuardarPrevio", '<a href="javascript:guardadoPrevioUceti(0);" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardado Previo</a>&nbsp;');
//            }
//        }
//        if (clsCaus::validarSeccion(ConfigurationCAUS::influenza, ConfigurationCAUS::Consultar))
//            $this->tpl->setVariable("botonCancelar", '<a href="index.php" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');

        $this->tpl->setVariable("botonGuardar", '<a href="javascript:validartb();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
//        $this->tpl->setVariable("botonGuardar", '<a href="javascript:pruebadeguardar();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
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
