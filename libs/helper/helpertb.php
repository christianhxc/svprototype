<?php

require_once('libs/Configuration.php');
require_once('libs/ConfigurationHospitalInfluenza.php');
require_once('libs/Connection.php');
require_once ('libs/caus/clsCaus.php');
require_once('libs/helper/helperString.php');

class helpertb {
    
    public function filtro_sql($config){

         $filtro = "";
                // Filtrar los resultados
                $lista = $config["ubicaciones"];
                if (is_array($lista)){
                    foreach ($lista as $elemento){
                        $temporal = "";
//                        if ($elemento[ConfigurationCAUS::Provincia] != "")
//                            $temporal .= "f.idas = '".$elemento[ConfigurationCAUS::Provincia]."' ";

                        if ($elemento[ConfigurationCAUS::Localidad] != "")
                        { 
                            $temporal .= ($temporal != '' ? "and " : "")."tb.id_un = '".$elemento[ConfigurationCAUS::Localidad]."' ";
                        } else if ($elemento[ConfigurationCAUS::Region] != "" && $elemento[ConfigurationCAUS::Localidad] == "")
                            {   
                            $temporal .= ($temporal != '' ? " and " : "")." re.id_region = ".$elemento[ConfigurationCAUS::Region]." ";
                            }
//                        if ($elemento[ConfigurationCAUS::Distrito] != "")
//                            $temporal .= ($temporal != '' ? "and " : "")."f.idts = '".$elemento[ConfigurationCAUS::Distrito]."' ";
//
//                        if ($elemento[ConfigurationCAUS::Corregimiento] != "")
//                            $temporal .= ($temporal != '' ? "and " : "")."f.idts = '".$elemento[ConfigurationCAUS::Corregimiento]."' ";


                        
                        $filtro .= ($filtro != '' ? "or " : "")."(".$temporal.") ";
                    }
                }

                if ($filtro != "")
                $filtro = " and (".$filtro.") ";
                
            return  $filtro;        
                   
	}

    public static function yaExistePersona($individuo) {
        $sql = "select count(*) from tbl_persona where tipo_identificacion='" . $individuo['tipo_identificacion'] . "' and numero_identificacion='" . $individuo['numero_identificacion'] . "'";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }

    public static function yaExistetb($tb) {
        //return 0;
        $sql = "select count(*) from tb_form where tipo_identificacion='" . $tb['tipo_identificacion'] . "' and numero_identificacion='" . $tb['numero_identificacion'] . "'";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }

    //Array ( [id_individuo] => [asegurado] => 1 [tipoId] => 4 [identificador] => RN22752378 [primer_nombre] => Diego [segundo_nombre] => Fernando [primer_apellido] => Troncoso [segundo_apellido] => Silva [fecha_nacimiento] => 17/05/1987 [tipo_edad] => 3 [edad] => 25 [sexo] => M [nombre_responsable] => Fernando Troncoso [provincia] => 8 [region] => 8 [distrito] => 53 [corregimiento] => 499 [direccion] => El Doncello caqueta - 3 cuadras al sur de la iglesia principal [punto_referencia] => CASA DONCELLO [telefono] => 4566 7777 ) 
    public static function dataTblPersona($data) {
        // DATOS DEL INDIVIDUO
        $individuo = array();
        $individuo["tipo_identificacion"] = (!isset($data["individuo"]["tipoId"]) ? NULL : $data["individuo"]["tipoId"]);
        $individuo["numero_identificacion"] = (!isset($data["individuo"]["identificador"]) ? NULL : $data["individuo"]["identificador"]);
        
        if ($individuo["tipo_identificacion"] == 1)
            $individuo["numero_identificacion"] = $data["individuo"]["identificador1"]."-".$data["individuo"]["identificador2"]."-".$data["individuo"]["identificador3"];

        $individuo["primer_nombre"] = (!isset($data["individuo"]["primer_nombre"]) ? NULL : strtoupper($data["individuo"]["primer_nombre"]));
        $individuo["segundo_nombre"] = (!isset($data["individuo"]["segundo_nombre"]) ? NULL : strtoupper($data["individuo"]["segundo_nombre"]));
        $individuo["primer_apellido"] = (!isset($data["individuo"]["primer_apellido"]) ? NULL : strtoupper($data["individuo"]["primer_apellido"]));
        $individuo["segundo_apellido"] = (!isset($data["individuo"]["segundo_apellido"]) ? NULL : strtoupper($data["individuo"]["segundo_apellido"]));
        $individuo["casada_apellido"] = (!isset($data["individuo"]["casada_apellido"]) ? NULL : strtoupper($data["individuo"]["casada_apellido"]));

        $individuo["fecha_nacimiento"] = (!isset($data["individuo"]["fecha_nacimiento"]) ? NULL : helperString::toDate($data["individuo"]["fecha_nacimiento"]));

        $individuo["edad"] = (!isset($data["individuo"]["edad"]) ? NULL : $data["individuo"]["edad"]);
        $individuo["tipo_edad"] = (!isset($data["individuo"]["tipo_edad"]) ? '4' : $data["individuo"]["tipo_edad"]);
        $individuo["sexo"] = (!isset($data["individuo"]["sexo"]) ? '0' : $data["individuo"]["sexo"]);

        $individuo["id_region"] = $data["individuo"]["region"];
        $individuo["id_corregimiento"] = $data["individuo"]["corregimiento"];

        $individuo["dir_referencia"] = (!isset($data["individuo"]["direccion"]) ? NULL : strtoupper($data["individuo"]["direccion"]));
        $individuo["dir_trabajo"] = (!isset($data["individuo"]["otra_direccion"]) ? NULL : strtoupper($data["individuo"]["otra_direccion"]));
        $individuo["id_pais"] = "174";

        $individuo["nombre_responsable"] = (!isset($data["individuo"]["nombre_responsable"]) ? NULL : strtoupper($data["individuo"]["nombre_responsable"]));
        $individuo["tel_residencial"] = (!isset($data["individuo"]["telefono"]) ? NULL : strtoupper($data["individuo"]["telefono"]));
        
        (!isset($data["individuo"]["estado_civil"]) ? NULL : $individuo["id_estado_civil"] = strtoupper($data["individuo"]["estado_civil"]));
        (!isset($data["individuo"]["escolaridad"]) ? NULL : $individuo["id_escolaridad"] = strtoupper($data["individuo"]["escolaridad"]));
        (!isset($data["individuo"]["poblacion"]) ? NULL : $individuo["id_gpopoblacional"] = strtoupper($data["individuo"]["poblacion"]));
        (!isset($data["individuo"]["etnia"]) || $data["individuo"]["etnia"]=="" ? NULL : $individuo["id_etnia"] = strtoupper($data["individuo"]["etnia"]));
        
        (!isset($data["individuo"]["profesion"]) ? NULL : $individuo["id_ocupacion"] = strtoupper($data["individuo"]["profesion"]));

        return $individuo;
    }

    public static function datatbForm($data) {
        $tb = array();

        //Notificacion
        if (isset($data["notificacion"]["unidad_disponible"])) {
            $tb["id_un"] = $data["notificacion"]["id_un"]; // se necesita para que pueda ver el hospital el user
//            $tb["id_un"] = NULL;
            $tb["unidad_disponible"] = "0";
        } else {
            $tb["id_un"] = $data["notificacion"]["id_un"];
            $tb["unidad_disponible"] = "1";
        }

        $tb["nombre_investigador"] = (!isset($data["notificacion"]["nombreInvestigador"]) ? NULL : $data["notificacion"]["nombreInvestigador"]);
        $tb["nombre_registra"] = (!isset($data["notificacion"]["nombreRegistra"]) ? NULL : $data["notificacion"]["nombreRegistra"]);
        $tb["fecha_formulario"] = (!isset($data["notificacion"]["fecha_formulario"]) ? NULL : helperString::toDate($data["notificacion"]["fecha_formulario"]));
        $tb["fecha_notificacion"] = (!isset($data["notificacion"]["fecha_notificacion"]) ? NULL : helperString::toDate($data["notificacion"]["fecha_notificacion"]));
        
        //Individuo
        $tb["per_asegurado"] = (!isset($data["individuo"]["asegurado"]) ? NULL : $data["individuo"]["asegurado"]);
        $tb["tipo_identificacion"] = (!isset($data["individuo"]["tipoId"]) ? NULL : $data["individuo"]["tipoId"]);
        $tb["numero_identificacion"] = (!isset($data["individuo"]["identificador"]) ? NULL : $data["individuo"]["identificador"]);
        if ($tb["tipo_identificacion"] == 1)
            $tb["numero_identificacion"] = $data["individuo"]["identificador1"]."-".$data["individuo"]["identificador2"]."-".$data["individuo"]["identificador3"];
        $tb["per_edad"] = (!isset($data["individuo"]["edad"]) ? NULL : $data["individuo"]["edad"]);
        $tb["per_tipo_edad"] = (!isset($data["individuo"]["tipo_edad"]) ? '4' : $data["individuo"]["tipo_edad"]);
        $tb["id_pais"] = "174";
        $tb["id_corregimiento"] = $data["individuo"]["corregimiento"];
        $tb["per_direccion"] = (!isset($data["individuo"]["direccion"]) ? NULL : strtoupper($data["individuo"]["direccion"]));
         (!isset($data["individuo"]["otra_direccion"]) ? NULL : $tb["per_direccion_otra"] = strtoupper($data["individuo"]["otra_direccion"]));
         (!isset($data["individuo"]["telefono"]) || $data["individuo"]["telefono"] == "" ? NULL : $tb["per_telefono"] = strtoupper($data["individuo"]["telefono"]));

        $sexo = (!isset($data["individuo"]["sexo"]) ? '0' : $data["individuo"]["sexo"]);
        if ($sexo != 'M') {
            $tb["riesgo_embarazo"] = (!isset($data["individuo"]["embarazo"]) ? NULL : $data["individuo"]["embarazo"]);
            if ($tb["riesgo_embarazo"] == '1')
                $tb["riesgo_semana"] = (!isset($data["individuo"]["semana_gestacional"]) ? NULL : $data["individuo"]["semana_gestacional"]);
            else
                $tb["riesgo_semana"] = NULL;
        } else {
            $tb["riesgo_embarazo"] = NULL;
            $tb["riesgo_semana"] = NULL;
        }

         (!isset($data["individuo"]["empleado"]) ? NULL : $tb["per_empleado"] = strtoupper($data["individuo"]["empleado"]));
        $tb["id_profesion"] = (!isset($data["individuo"]["profesion"]) ? NULL : strtoupper($data["individuo"]["profesion"]));
         (!isset($data["individuo"]["otrosprofesion"]) ? NULL : $tb["otrosprofesion"] = strtoupper($data["individuo"]["otrosprofesion"]));
         (!isset($data["individuo"]["nombre_responsable"]) ? NULL : $tb["per_nombre_referencia"] = strtoupper($data["individuo"]["nombre_responsable"]));
         (!isset($data["individuo"]["parentesco"]) ? NULL : $tb["per_parentesco"] = strtoupper($data["individuo"]["parentesco"]));
         (!isset($data["individuo"]["telefono_familiar"]) || $data["individuo"]["telefono_familiar"] == ""  ? NULL : $tb["per_telefono_referencia"] = strtoupper($data["individuo"]["telefono_familiar"]));
        
         (!isset($data["individuo"]["antes_preso"]) ? NULL : $tb["per_antes_preso"] = strtoupper($data["individuo"]["antes_preso"]));
         (!isset($data["individuo"]["fecha_antespreso"]) ? NULL :  $tb["per_fecha_antespreso"] = helperString::toDate($data["individuo"]["fecha_antespreso"]));
         
        //Antecedentes
        
        (!isset($data["antecedentes"]["diab"]) ? NULL : $tb["ant_diabetes"] = strtoupper($data["antecedentes"]["diab"]));
        (!isset($data["antecedentes"]["preso"]) ? NULL : $tb["ant_preso"] = strtoupper($data["antecedentes"]["preso"]));
        (!isset($data["antecedentes"]["tiempo_preso"]) ? NULL : $tb["ant_tiempo_preso"] = strtoupper($data["antecedentes"]["tiempo_preso"]));
        (!isset($data["antecedentes"]["fecha_preso"]) ? NULL : $tb["ant_fecha_preso"] = helperString::toDate($data["antecedentes"]["fecha_preso"]));
        (!isset($data["antecedentes"]["drug"]) ? NULL : $tb["ant_drug"] = strtoupper($data["antecedentes"]["drug"]));
        (!isset($data["antecedentes"]["alcoholism"]) ? NULL : $tb["ant_alcoholism"] = strtoupper($data["antecedentes"]["alcoholism"]));
        (!isset($data["antecedentes"]["smoking"]) ? NULL : $tb["ant_smoking"] = strtoupper($data["antecedentes"]["smoking"]));
        (!isset($data["antecedentes"]["mining"]) ? NULL : $tb["ant_mining"] = strtoupper($data["antecedentes"]["mining"]));
        (!isset($data["antecedentes"]["overcrowding"]) ? NULL : $tb["ant_overcrowding"] = strtoupper($data["antecedentes"]["overcrowding"]));
        (!isset($data["antecedentes"]["indigence"]) ? NULL : $tb["ant_indigence"] = strtoupper($data["antecedentes"]["indigence"]));
        (!isset($data["antecedentes"]["drinkable"]) ? NULL : $tb["ant_drinkable"] = strtoupper($data["antecedentes"]["drinkable"]));
        (!isset($data["antecedentes"]["sanitation"]) ? NULL :  $tb["ant_sanitation"] = strtoupper($data["antecedentes"]["sanitation"]));
        (!isset($data["antecedentes"]["contactposi"]) ? NULL : $tb["ant_contactposi"] = strtoupper($data["antecedentes"]["contactposi"]));
        (!isset($data["antecedentes"]["BCG"]) ? NULL : $tb["ant_BCG"] = strtoupper($data["antecedentes"]["BCG"]));
        (!isset($data["antecedentes"]["weight"]) ? NULL : $tb["ant_weight"] = strtoupper($data["antecedentes"]["weight"]));
        (!isset($data["antecedentes"]["height"]) ? NULL : $tb["ant_height"] = strtoupper($data["antecedentes"]["height"]));

// Metodo de diagnostico
        
        $tb["mat_diag_fecha_BK1"] = (!isset($data["met_diag"]["fecha_BK1"]) ? NULL : helperString::toDate($data["met_diag"]["fecha_BK1"]));
        (!isset($data["met_diag"]["resultado_BK1"]) || $data["met_diag"]["resultado_BK1"] == "" ? NULL : $tb["mat_diag_resultado_BK1"] = strtoupper($data["met_diag"]["resultado_BK1"]));
        (!isset($data["met_diag"]["clasificacion_BK1"]) || $data["met_diag"]["clasificacion_BK1"] == "" ? NULL : $tb["id_clasificacion_BK1"] = strtoupper($data["met_diag"]["clasificacion_BK1"]));
        $tb["mat_diag_fecha_BK2"] = (!isset($data["met_diag"]["fecha_BK2"]) ? NULL : helperString::toDate($data["met_diag"]["fecha_BK2"]));
        (!isset($data["met_diag"]["resultado_BK2"]) || $data["met_diag"]["resultado_BK2"] == "" ? NULL : $tb["mat_diag_resultado_BK2"] = strtoupper($data["met_diag"]["resultado_BK2"]));
        (!isset($data["met_diag"]["clasificacion_BK2"]) || $data["met_diag"]["clasificacion_BK2"] == "" ? NULL : $tb["id_clasificacion_BK2"] = strtoupper($data["met_diag"]["clasificacion_BK2"]));
        $tb["mat_diag_fecha_BK3"] = (!isset($data["met_diag"]["fecha_BK3"]) ? NULL : helperString::toDate($data["met_diag"]["fecha_BK3"]));
        (!isset($data["met_diag"]["resultado_BK3"]) || $data["met_diag"]["resultado_BK3"] =="" ? NULL : $tb["mat_diag_resultado_BK3"] = strtoupper($data["met_diag"]["resultado_BK3"]));
        (!isset($data["met_diag"]["clasificacion_BK3"]) || $data["met_diag"]["clasificacion_BK3"]=="" ? NULL : $tb["id_clasificacion_BK3"] =  strtoupper($data["met_diag"]["clasificacion_BK3"]));
        (!isset($data["met_diag"]["resultado_cultivo"]) || $data["met_diag"]["resultado_cultivo"]=="" ? NULL : $tb["mat_diag_res_cultivo"] = strtoupper($data["met_diag"]["resultado_cultivo"]));
        (!isset($data["met_diag"]["fecha_cultivo"]) ? NULL : $tb["mat_diag_fecha_res_cultivo"] = helperString::toDate($data["met_diag"]["fecha_cultivo"]));
        (!isset($data["met_diag"]["metodo_WRD"]) ? NULL : $tb["mat_diag_metodo_WRD"] = strtoupper($data["met_diag"]["metodo_WRD"]));
        (!isset($data["met_diag"]["resultado_WRD"]) || ($data["met_diag"]["resultado_WRD"] == "") ? NULL : $tb["mat_diag_res_metodo_WRD"] = strtoupper($data["met_diag"]["resultado_WRD"]));
        $tb["mat_diag_fecha_res_WRD"] = (!isset($data["met_diag"]["fecha_WRD"]) ? NULL : helperString::toDate($data["met_diag"]["fecha_WRD"]));
        (!isset($data["met_diag"]["resultado_clinico"]) || ($data["met_diag"]["resultado_clinico"] == "") ? NULL : $tb["mat_diag_res_clinico"] = strtoupper($data["met_diag"]["resultado_clinico"]));
        $tb["mat_diag_fecha_clinico"] = (!isset($data["met_diag"]["fecha_clinico"]) ? NULL : helperString::toDate($data["met_diag"]["fecha_clinico"]));
        (!isset($data["met_diag"]["resultado_R-X"]) || ($data["met_diag"]["resultado_R-X"] == "") ? NULL : $tb["mat_diag_res_R_X"] = strtoupper($data["met_diag"]["resultado_R-X"]));
        $tb["mat_diag_fecha_R_X"] = (!isset($data["met_diag"]["fecha_R-X"]) ? NULL : helperString::toDate($data["met_diag"]["fecha_R-X"]));
        (!isset($data["met_diag"]["resultado_histopa"]) || ($data["met_diag"]["resultado_histopa"] == "") ? NULL : $tb["mat_diag_res_histopa"] = strtoupper($data["met_diag"]["resultado_histopa"]));
        $tb["mat_diag_fecha_histopa"] = (!isset($data["met_diag"]["fecha_histopa"]) ? NULL : helperString::toDate($data["met_diag"]["fecha_histopa"]));

        if (!isset($data["met_diag"]["resultado_clinico"]) && ($data["met_diag"]["resultado_clinico"] == "1") )
            $tb["clasificacion_tb"] = 1;
        if (!isset($data["met_diag"]["resultado_R-X"]) && ($data["met_diag"]["resultado_R-X"] == "1") )
            $tb["clasificacion_tb"] = 1;
        if (!isset($data["met_diag"]["resultado_histopa"]) && ($data["met_diag"]["resultado_histopa"] == "1") )
            $tb["clasificacion_tb"] = 1;
        
        if ((isset($data["met_diag"]["resultado_BK1"]) && $data["met_diag"]["resultado_BK1"] == "1") )
            $tb["clasificacion_tb"] = 0;
        if ((isset($data["met_diag"]["resultado_BK2"]) && $data["met_diag"]["resultado_BK2"] == "1") )
            $tb["clasificacion_tb"] = 0;
        if ((isset($data["met_diag"]["resultado_BK3"]) && $data["met_diag"]["resultado_BK3"] == "1") )
            $tb["clasificacion_tb"] = 0;
        if ((isset($data["met_diag"]["resultado_cultivo"]) && $data["met_diag"]["resultado_cultivo"] == "2") )
            $tb["clasificacion_tb"] = 0;
        if ((isset($data["met_diag"]["resultado_WRD"]) && $data["met_diag"]["resultado_WRD"] == "1") )
            $tb["clasificacion_tb"] = 0;
        
// Clasificación

         (!isset($data["clasificacion"]["pulmonar_EP"]) ? NULL : $tb["clas_pulmonar_EP"] = strtoupper($data["clasificacion"]["pulmonar_EP"]));
         (!isset($data["clasificacion"]["lugar_EP"]) ? NULL : $tb["clas_lugar_EP"] = strtoupper($data["clasificacion"]["lugar_EP"]));
         (!isset($data["clasificacion"]["trat_previo"]) ? NULL : $tb["clas_trat_previo"] = strtoupper($data["clasificacion"]["trat_previo"]));

        (!isset($data["clasificacion"]["recaida"]) ? NULL : $tb["clas_recaida"] = strtoupper($data["clasificacion"]["recaida"]));
        (!isset($data["clasificacion"]["postfracaso"]) ? NULL : $tb["clas_postfracaso"] = strtoupper($data["clasificacion"]["postfracaso"]));
        (!isset($data["clasificacion"]["perdsegui"]) ? NULL : $tb["clas_perdsegui"] = strtoupper($data["clasificacion"]["perdsegui"]));

        (!isset($data["clasificacion"]["otros_antestratado"]) ? NULL : $tb["clas_otros_antestratado"] = strtoupper($data["clasificacion"]["otros_antestratado"]));
        (!isset($data["clasificacion"]["diag_VIH"]) ? NULL : $tb["clas_diag_VIH"] = strtoupper($data["clasificacion"]["diag_VIH"]));
        (!isset($data["clasificacion"]["fecha_diag_VIH"]) ? NULL : $tb["clas_fecha_diag_VIH"] = helperString::toDate($data["clasificacion"]["fecha_diag_VIH"]));
        (!isset($data["clasificacion"]["met_diag"]) ? NULL : $tb["clas_met_diag"] = strtoupper($data["clasificacion"]["met_diag"]));
        
        (!isset($data["clasificacion"]["esp_MonoR"]) ? NULL : $tb["clas_esp_MonoR"] = strtoupper($data["clasificacion"]["esp_MonoR"]));
        
        if (isset($data["clasificacion"]["PoliR"])) {

              (!isset($data["clasificacion"]["PoliR"]["H"]) ? NULL : $tb["clas_PoliR_H"] = strtoupper($data["clasificacion"]["PoliR"]["H"]));
              (!isset($data["clasificacion"]["PoliR"]["R"]) ? NULL : $tb["clas_PoliR_R"] = strtoupper($data["clasificacion"]["PoliR"]["R"]));
              (!isset($data["clasificacion"]["PoliR"]["Z"]) ? NULL : $tb["clas_PoliR_Z"] = strtoupper($data["clasificacion"]["PoliR"]["Z"]));
              (!isset($data["clasificacion"]["PoliR"]["E"]) ? NULL : $tb["clas_PoliR_E"] = strtoupper($data["clasificacion"]["PoliR"]["E"]));
              (!isset($data["clasificacion"]["PoliR"]["S"]) ? NULL : $tb["clas_PoliR_S"] = strtoupper($data["clasificacion"]["PoliR"]["S"]));
              (!isset($data["clasificacion"]["PoliR"]["Fluoro"]) ? NULL : $tb["clas_PoliR_fluoroquinolonas"] = strtoupper($data["clasificacion"]["PoliR"]["Fluoro"]));
              (!isset($data["clasificacion"]["PoliR"]["2linea"]) ? NULL : $tb["clas_PoliR_2linea"] = strtoupper($data["clasificacion"]["PoliR"]["2linea"]));
        }
        
        (!isset($data["clasificacion"]["fluoroquinolonas"]) ? NULL : $tb["clas_id_fluoroquinolonas"] = strtoupper($data["clasificacion"]["fluoroquinolonas"]));
        (!isset($data["clasificacion"]["2_linea"]) ? NULL : $tb["clas_id_2linea"] = strtoupper($data["clasificacion"]["2_linea"]));

//clasificacion_tb  -> Pendiente
        
        // Tratamiento
        $tb["trat_referido"] = (!isset($data["tratamiento"]["referido"]) ? NULL : strtoupper($data["tratamiento"]["referido"]));
        $tb["trat_inst_salud_ref"] = (!isset($data["tratamiento"]["id_inst_salud_referencia"]) ? NULL : strtoupper($data["tratamiento"]["id_inst_salud_referencia"]));
        
        $tb["trat_fecha_inicio_tratF1"] = (!isset($data["tratamiento"]["fecha_inicio_tratF1"]) ? NULL : helperString::toDate($data["tratamiento"]["fecha_inicio_tratF1"]));
        if (isset($data["tratamiento"]["med_indF1"])) {
            
             $tb["trat_med_H_F1"] = (!isset($data["tratamiento"]["med_indF1"]["H"]) ? NULL : strtoupper($data["tratamiento"]["med_indF1"]["H"]));
             $tb["trat_med_R_F1"] = (!isset($data["tratamiento"]["med_indF1"]["R"]) ? NULL : strtoupper($data["tratamiento"]["med_indF1"]["R"]));
             $tb["trat_med_Z_F1"] = (!isset($data["tratamiento"]["med_indF1"]["Z"]) ? NULL : strtoupper($data["tratamiento"]["med_indF1"]["Z"]));
             $tb["trat_med_E_F1"] = (!isset($data["tratamiento"]["med_indF1"]["E"]) ? NULL : strtoupper($data["tratamiento"]["med_indF1"]["E"]));
             $tb["trat_med_S_F1"] = (!isset($data["tratamiento"]["med_indF1"]["S"]) ? NULL : strtoupper($data["tratamiento"]["med_indF1"]["S"]));
             $tb["trat_med_otros_F1"] = (!isset($data["tratamiento"]["med_indF1"]["Otr"]) ? NULL : strtoupper($data["tratamiento"]["med_indF1"]["Otr"]));
        }
        $tb["trat_fecha_fin_tratF1"] = (!isset($data["tratamiento"]["fecha_fin_tratF1"]) ? NULL : helperString::toDate($data["tratamiento"]["fecha_fin_tratF1"]));
        $tb["id_adm_tratamiento_F1"] = (!isset($data["tratamiento"]["administracionF1"]) ? NULL : strtoupper($data["tratamiento"]["administracionF1"]));

        $tb["trat_fecha_inicio_tratF2"] = (!isset($data["tratamiento"]["fecha_inicio_tratF2"]) ? NULL : helperString::toDate($data["tratamiento"]["fecha_inicio_tratF2"]));
        if (isset($data["tratamiento"]["med_indF2"])) {
            
             (!isset($data["tratamiento"]["med_indF2"]["H"]) ? NULL : $tb["trat_med_H_F2"] = strtoupper($data["tratamiento"]["med_indF2"]["H"]));
             (!isset($data["tratamiento"]["med_indF2"]["R"]) ? NULL : $tb["trat_med_R_F2"] = strtoupper($data["tratamiento"]["med_indF2"]["R"]));
             (!isset($data["tratamiento"]["med_indF2"]["E"]) ? NULL : $tb["trat_med_E_F2"] = strtoupper($data["tratamiento"]["med_indF2"]["E"]));
             (!isset($data["tratamiento"]["med_indF2"]["Otr"]) ? NULL : $tb["trat_med_otros_F2"] = strtoupper($data["tratamiento"]["med_indF2"]["Otr"]));
        }
        $tb["trat_fecha_fin_tratF2"] = (!isset($data["tratamiento"]["fecha_fin_tratF2"]) ? NULL : helperString::toDate($data["tratamiento"]["fecha_fin_tratF2"]));
        $tb["id_adm_tratamiento_F2"] = (!isset($data["tratamiento"]["administracionF2"]) ? NULL : strtoupper($data["tratamiento"]["administracionF2"]));



// TB-VIH
        
        (!isset($data["VIH"]["solicitud_VIH"]) || ($data["VIH"]["solicitud_VIH"] == "") ? NULL : $tb["TB_VIH_solicitud_VIH"] = strtoupper($data["VIH"]["solicitud_VIH"]));
        (!isset($data["VIH"]["acepto_VIH"]) || ($data["VIH"]["acepto_VIH"] == "") ? NULL : $tb["TB_VIH_acepto_VIH"] = strtoupper($data["VIH"]["acepto_VIH"]));
        (!isset($data["VIH"]["realizada_VIH"]) || ($data["VIH"]["realizada_VIH"] == "") ? NULL : $tb["TB_VIH_realizada_VIH"] = strtoupper($data["VIH"]["realizada_VIH"]));
        (!isset($data["VIH"]["fecha_muestra_VIH"]) ? NULL : $tb["TB_VIH_fecha_muestra_VIH"] = helperString::toDate($data["VIH"]["fecha_muestra_VIH"]));

        (!isset($data["VIH"]["res_VIH"]) || ($data["VIH"]["res_VIH"] == "")  ? NULL : $tb["TB_VIH_res_VIH"] = strtoupper($data["VIH"]["res_VIH"]));
        (!isset($data["VIH"]["ref_TARV"]) || ($data["VIH"]["ref_TARV"] == "") ? NULL : $tb["TB_VIH_ref_TARV"] = strtoupper($data["VIH"]["ref_TARV"]));
        (!isset($data["VIH"]["fecha_ref_TARV"]) ? NULL : $tb["TB_VIH_fecha_ref_TARV"] = helperString::toDate($data["VIH"]["fecha_ref_TARV"]));

        (!isset($data["VIH"]["inicio_TARV"]) || ($data["VIH"]["inicio_TARV"] == "") ? NULL : $tb["TB_VIH_inicio_TARV"] = strtoupper($data["VIH"]["inicio_TARV"]));
        (!isset($data["VIH"]["aseso_VIH"]) || ($data["VIH"]["aseso_VIH"] == "") ? NULL : $tb["TB_VIH_aseso_VIH"] = strtoupper($data["VIH"]["aseso_VIH"]));
        (!isset($data["VIH"]["cotrimoxazol"]) ? NULL : $tb["TB_VIH_cotrimoxazol"] = strtoupper($data["VIH"]["cotrimoxazol"]));

        (!isset($data["VIH"]["fecha_cotrimoxazol"]) ? NULL : $tb["TB_VIH_fecha_cotrimoxazol"] = helperString::toDate($data["VIH"]["fecha_cotrimoxazol"]));
        (!isset($data["VIH"]["fecha_inicio_TARV"]) ? NULL : $tb["TB_VIH_fecha_inicio_TARV"] = helperString::toDate($data["VIH"]["fecha_inicio_TARV"]));
        
        (!isset($data["VIH"]["id_lug_adm_TARV"]) ? NULL : $tb["TB_VIH_lug_adm_TARV"] = strtoupper($data["VIH"]["id_lug_adm_TARV"]));
        (!isset($data["VIH"]["esq_ARV"]) || ($data["VIH"]["esq_ARV"] == "") ? NULL : $tb["TB_VIH_esq_ARV"] = strtoupper($data["VIH"]["esq_ARV"]));

        (!isset($data["VIH"]["fecha_prueba_VIH"]) ? NULL : $tb["TB_VIH_fecha_prueba_VIH"] = helperString::toDate($data["VIH"]["fecha_prueba_VIH"]));

        (!isset($data["VIH"]["res_previa_VIH"]) ? NULL : $tb["TB_VIH_res_previa_VIH"] = strtoupper($data["VIH"]["res_previa_VIH"]));
        (!isset($data["VIH"]["act_TARV"]) ? NULL : $tb["TB_VIH_act_TARV"] = strtoupper($data["VIH"]["act_TARV"]));
        (!isset($data["VIH"]["isoniacida"]) ? NULL : $tb["TB_VIH_isoniacida"] = strtoupper($data["VIH"]["isoniacida"]));



// Contactos

        (!isset($data["contactos"]["identificados5min"]) ? NULL : $tb["contacto_identificados_5min"] = strtoupper($data["contactos"]["identificados5min"]));
        (!isset($data["contactos"]["sinto_resp5min"]) ? NULL : $tb["contacto_sinto_resp_5min"] = strtoupper($data["contactos"]["sinto_resp5min"]));
        (!isset($data["contactos"]["evaluados5min"]) ? NULL : $tb["contacto_evaluados_5min"] = strtoupper($data["contactos"]["evaluados5min"]));
        (!isset($data["contactos"]["quimioprofilaxis5min"]) ? NULL : $tb["contacto_quimioprofilaxis_5min"] = strtoupper($data["contactos"]["quimioprofilaxis5min"]));
        (!isset($data["contactos"]["TB5min"]) ? NULL : $tb["contacto_TB_5min"] = strtoupper($data["contactos"]["TB5min"]));

        (!isset($data["contactos"]["identificados5pl"]) ? NULL : $tb["contacto_identificados_5pl"] = strtoupper($data["contactos"]["identificados5pl"]));
        (!isset($data["contactos"]["sinto_resp5pl"]) ? NULL : $tb["contacto_sinto_resp_5pl"] = strtoupper($data["contactos"]["sinto_resp5pl"]));
        (!isset($data["contactos"]["evaluados5pl"]) ? NULL : $tb["contacto_evaluados_5pl"] = strtoupper($data["contactos"]["evaluados5pl"]));
        (!isset($data["contactos"]["quimioprofilaxis5pl"]) ? NULL : $tb["contacto_quimioprofilaxis_5pl"] = strtoupper($data["contactos"]["quimioprofilaxis5pl"]));
        (!isset($data["contactos"]["TB5pl"]) ? NULL : $tb["contacto_TB_5pl"] = strtoupper($data["contactos"]["TB5pl"]));
        
// Apoyo
        
        (!isset($data["apoyo"]["social"]) ? NULL : $tb["apoyo_social"] = strtoupper($data["apoyo"]["social"]));
        (!isset($data["apoyo"]["nutricional"]) ? NULL : $tb["apoyo_nutricional"] = strtoupper($data["apoyo"]["nutricional"]));
        (!isset($data["apoyo"]["economico"]) ? NULL : $tb["apoyo_economico"] = strtoupper($data["apoyo"]["economico"]));
        
        
// Egreso
        
        (!isset($data["egreso"]["fecha_egreso"]) ? NULL : $tb["egreso_fecha_egreso"] = helperString::toDate($data["egreso"]["fecha_egreso"]));
        (!isset($data["egreso"]["cond_egreso"]) ? NULL : $tb["egreso_cond_egreso"] = strtoupper($data["egreso"]["cond_egreso"]));
        (!isset($data["egreso"]["motivo_exclusion"]) ? NULL : $tb["egreso_motivo_exclusion"] = strtoupper($data["egreso"]["motivo_exclusion"]));

// Faltan

//
//
//
//
//
//
//semana_epi
//anio
//nombre_toma_muestra
//pendiente_uceti
//pendiente_silab
//actualizacion_silab
//source_entry

        // Hasta aquí
        
        
        
//mat_diag_resis_ninguna
//mat_diag_mono_r
//mat_diag_esp_MonoR
//mat_diag_PoliR_H
//mat_diag_PoliR_R
//mat_diag_PoliR_Z
//mat_diag_PoliR_E
//mat_diag_PoliR_S
//id_un_mat
//id_inyect_2linea
//mat_diag_MDR
//mat_diag_XDR
//mat_diag_TB-RR
//mat_diag_desconocida  
        
        
 

        $tb["semana_epi"] = (!isset($data["datos_clinicos"]["semana_epi"]) ? NULL : $data["datos_clinicos"]["semana_epi"]);
        $tb["anio"] = (!isset($data["datos_clinicos"]["anio"]) ? NULL : $data["datos_clinicos"]["anio"]);

        return $tb;
    }

    // Obtiene el listado de tb
    public static function buscartb($config) {
        $conn = new Connection();
        $conn->initConn();
        $flag = 0;
        $filtro1 = "";
        $filtro2 = "";
        $read = false;
        
        foreach ($config as $valor) {
            if ($valor != "")
                $flag++;
        }

        if ($flag == 0)
            $sql = "select * from tb_form tb
                    inner join tbl_persona per on per.tipo_identificacion = tb.tipo_identificacion and per.numero_identificacion = tb.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = tb.id_un
                    left join cat_region_salud re on re.id_region = per.id_region
                    WHERE 1 ".self::filtro_sql($config);
        else {
            //echo "filtro ".$config["silab"];
            if ($config["filtro"] != "") {
                $filtro1 = " AND (un.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                        " OR re.nombre_region LIKE '%" . $config["filtro"] . "%'" .
                        " OR tb.semana_epi LIKE '%" . $config["filtro"] . "%'" .
                        " OR tb.anio LIKE '%" . $config["filtro"] . "%'" .
                        " OR tb.numero_identificacion LIKE '%" . $config["filtro"] . "%')";
            } else if (isset($config["tipo_identificacion"]) && isset($config["numero_identificacion"])) {
                $filtro2 = " AND tb.tipo_identificacion='" . $config["tipo_identificacion"] . "'" .
                        " AND tb.numero_identificacion='" . $config["numero_identificacion"] . "'";
                $read = true;
            } 

            $sql = "select * from tb_form tb
                    inner join tbl_persona per on per.tipo_identificacion = tb.tipo_identificacion and per.numero_identificacion = tb.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = tb.id_un
                    left join cat_region_salud re on re.id_region = per.id_region WHERE 1 "
                    . $filtro1 . $filtro2 . self::filtro_sql($config). ' order by id_tb desc';
            //. " limit " . $config["inicio"] . "," . $config["paginado"];
            if (!$read) {
                $sql.= " limit " . $config["inicio"] . "," . $config["paginado"];
            }
        }

//        print_r($sql);
//        exit;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function buscartbCantidad($config) {
        $conn = new Connection();
        $conn->initConn();
        $filtro1 = "";



        if ($config["filtro"] != "") {
            $filtro1 = " AND (un.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                    " OR re.nombre_region LIKE '%" . $config["filtro"] . "%'" .
                    " OR tb.semana_epi LIKE '%" . $config["filtro"] . "%'" .
                    " OR tb.anio LIKE '%" . $config["filtro"] . "%'" .
                    " OR tb.numero_identificacion LIKE '%" . $config["filtro"] . "%')";
        }

        $sql = "select count(*) as total from tb_form tb
                    inner join tbl_persona per on per.tipo_identificacion = tb.tipo_identificacion and per.numero_identificacion = tb.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = tb.id_un
                    left join cat_region_salud re on re.id_region = per.id_region WHERE 1 "
                . $filtro1 . self::filtro_sql($config);
//        echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }
    
    public static function buscarqtrSintRes($config) {
        $conn = new Connection();
        $conn->initConn();
        $filtro1 = "";



//        if ($config["filtro"] != "") {
//            $filtro1 = " AND (un.nombre_un LIKE '%" . $config["filtro"] . "%'" .
//                    " OR re.nombre_region LIKE '%" . $config["filtro"] . "%'" .
//                    " OR ev.cie_10_1 LIKE '%" . $config["filtro"] . "%'" .
//                    " OR ev.nombre_evento LIKE '%" . $config["filtro"] . "%'" .
//                    " OR tb.semana_epi LIKE '%" . $config["filtro"] . "%'" .
//                    " OR tb.anio LIKE '%" . $config["filtro"] . "%'" .
//                    " OR tb.numero_identificacion LIKE '%" . $config["filtro"] . "%')";
//        }

        $sql = "  SELECT quarter(trat_fecha_inicio_tratF1) as qua, count(*) as cantidad FROM `tb_form`
                   WHERE 1
                GROUP BY qua  "
                . $filtro1;
//        echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function buscartbMDR($id_TB) {
        if (isset($id_TB) ) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = "";
            $filtro = " AND id_tb=" . $id_TB . "";
            $sql = "select tb.id_grupo_riesgo_MDR from tb_grupo_riesgo_mdr tb 
                    left join cat_grupo_riesgo_mdr cm on cm.id_grupo_riesgo_MDR = tb.id_grupo_riesgo_MDR
                    WHERE status = 1  "
                    . $filtro;

            //echo $sql;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;
    }
    
    public static function buscartbInmunodepresor($id_TB) {
        if (isset($id_TB) ) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = "";
            $filtro = " AND id_tb=" . $id_TB . "";
            $sql = "select tb.id_inmunodepresor from tb_inmunodepresor tb 
                    left join cat_inmunodepresor cm on cm.id_inmunodepresor = tb.id_inmunodepresor
                    WHERE status = 1  "
                    . $filtro;

            //echo $sql;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;
    }

    public static function buscartbVacunas($tipoIdentificacion, $numeroIdentificacion) {
        if (isset($tipoIdentificacion) && isset($numeroIdentificacion)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = "";
            $filtro = " AND tipo_identificacion='" . $tipoIdentificacion . "'";
            $filtro .= " AND numero_identificacion='" . $numeroIdentificacion . "'";
            $sql = "select * from flureg_antecendente_vacunal uceti 
                    left join cat_antecendente_vacunal ce on ce.id_cat_antecendente_vacunal = uceti.id_cat_antecendente_vacunal
                    WHERE status = 1 and influenza = 1 "
                    . $filtro;

            //echo $sql;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;
    }

    public static function buscartbTipoMuestras($idUceti) {
        if (isset($idUceti)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = " AND id_flureg=" . $idUceti;
            $sql = "select * from flureg_muestra_laboratorio uceti 
                    left join cat_muestra_laboratorio ce on ce.id_cat_muestra_laboratorio = uceti.id_cat_muestra_laboratorio
                    WHERE status = 1 and influenza = 1 "
                    . $filtro;

            //echo $sql;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;
    }

    public static function buscartbMuestrasSilab($formUceti) {
        if (isset($formUceti)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = " AND id_flureg=" . $formUceti . "";
            $sql = "select * from flureg_muestra_silab 
                    WHERE 1 " . $filtro;
            //echo $sql;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;
    }

    public static function buscartbPruebasSilab($idMuestra) {
        if (isset($idMuestra)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = " and id_muestra = " . $idMuestra;
            $sql = "select * from flureg_muestra_prueba_silab 
                    WHERE 1 " . $filtro;
            //echo $sql;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;
    }

}