<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once ('libs/caus/clsCaus.php');
//require_once ('libs/Utils.php');
require_once('libs/silab/ConfigurationSilab.php');
require_once('libs/silab/ConnectionSilab.php');

class helperVicIts {

    public static function yaExistePersona($individuo) {
        $sql = "select count(*) from tbl_persona where tipo_identificacion='" . $individuo['tipo_identificacion'] . "' and numero_identificacion='" . $individuo['numero_identificacion'] . "'";
//        echo $sql; exit; 
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }

    public static function yaExisteVicIts($vicIts) {
        //return 0;
        $sql = "select count(*) from vicits_form where id_tipo_identidad=" . $vicIts['id_tipo_identidad']
                . " and numero_identificacion='" . $vicIts['numero_identificacion'] . "'"
                . " and semana_epi='" . $vicIts['semana_epi'] . "'"
                . " and anio='" . $vicIts['anio'] . "'";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }

    public static function dataTblPersona($data) {
        // DATOS DEL INDIVIDUO
        $individuo = array();
        $individuo["tipo_identificacion"] = (!isset($data["individuo"]["tipoId"]) ? NULL : $data["individuo"]["tipoId"]);
        $individuo["numero_identificacion"] = (!isset($data["individuo"]["identificador"]) ? NULL : $data["individuo"]["identificador"]);

        $individuo["primer_nombre"] = (!isset($data["individuo"]["primer_nombre"]) ? NULL : strtoupper($data["individuo"]["primer_nombre"]));
        $individuo["segundo_nombre"] = (!isset($data["individuo"]["segundo_nombre"]) ? NULL : strtoupper($data["individuo"]["segundo_nombre"]));
        $individuo["primer_apellido"] = (!isset($data["individuo"]["primer_apellido"]) ? NULL : strtoupper($data["individuo"]["primer_apellido"]));
        $individuo["segundo_apellido"] = (!isset($data["individuo"]["segundo_apellido"]) ? NULL : strtoupper($data["individuo"]["segundo_apellido"]));

        $individuo["fecha_nacimiento"] = (!isset($data["individuo"]["fecha_nacimiento"]) ? NULL : helperString::toDate($data["individuo"]["fecha_nacimiento"]));

        $individuo["edad"] = (!isset($data["individuo"]["edad"]) ? NULL : $data["individuo"]["edad"]);
        $individuo["tipo_edad"] = (!isset($data["individuo"]["tipo_edad"]) ? '4' : $data["individuo"]["tipo_edad"]);
        $individuo["sexo"] = (!isset($data["individuo"]["sexo"]) ? '0' : $data["individuo"]["sexo"]);
        $individuo["id_pais"] = (!isset($data["individuo"]["pais"]) ? NULL : strtoupper($data["individuo"]["pais"]));
        //if ($individuo["id_pais"] == 174) {
            $individuo["id_region"] = $data["individuo"]["region"];
            $individuo["id_corregimiento"] = $data["individuo"]["corregimiento"];
            $individuo["dir_referencia"] = (!isset($data["individuo"]["lugar_poblado"]) ? NULL : strtoupper($data["individuo"]["lugar_poblado"]));
        //}

        $individuo["id_escolaridad"] = (!isset($data["individuo"]["escolaridad"]) ? NULL : strtoupper($data["individuo"]["escolaridad"]));
        $individuo["id_estado_civil"] = (!isset($data["individuo"]["estado_civil"]) ? NULL : strtoupper($data["individuo"]["estado_civil"]));
        $individuo["id_etnia"] = (!isset($data["individuo"]["etnia"]) ? NULL : strtoupper($data["individuo"]["etnia"]));
        $individuo["id_genero"] = (!isset($data["individuo"]["genero"]) ? NULL : strtoupper($data["individuo"]["genero"]));

        return $individuo;
    }

    public static function dataVicItsIts($data) {
        $vicits = array();
        $vicits["its"] = (!isset($data["its"]["globalITSRelacionados"]) ? NULL : explode("###",$data["its"]["globalITSRelacionados"]));
        return $vicits;
    }
    
    public static function dataVicItsConsumoDrogas($data) {
        $vicits = array();
        $drogasRel = array();
        //echo $data["drogas"]["globalDrogasRelacionadas"]; exit;
        $vicits["drogas"] = (!isset($data["drogas"]["globalDrogasRelacionadas"]) ? 0 : explode("###",$data["drogas"]["globalDrogasRelacionadas"]));
        $max = sizeof($vicits["drogas"]);
        for ($i = 0; $i < $max; $i++) {
            $droga= explode("#-#",$vicits["drogas"][$i]);
            $drogasRel[$i] = $droga;
        }
        return $drogasRel;
    }

    public static function dataVicItsSintomas($data) {
        $vicIts = array();
        $sintomas = (!isset($data["factores"]["globalFactorRiesgoRelacionados"]) ? 0 : explode("###", $data["factores"]["globalFactorRiesgoRelacionados"]));
        $max = sizeof($sintomas);
        for ($i = 0; $i < $max; $i++) {
            $sintoma = explode("#-#", $sintomas[$i]);
            $vicIts["sintomas"][$i] = $sintoma;
        }
        return $vicIts;
    }

    public static function dataVicItsTratamiento($data) {
        $vicIts = array();
        $vicIts["tratamientos"] = (!isset($data["tratamientos"]["globalTratamientosRelacionados"]) ? NULL : explode("###", $data["tratamientos"]["globalTratamientosRelacionados"]));
        return $vicIts;
    }

    public static function dataVicItsDiagnosticoTratamiento($data) {
        $vicIts = array();
        $vicIts["diagnostico_tratamiento"] = (!isset($data["diagnostico_tratamiento"]["globalDiagnosticosTratamientoRelacionados"]) ? NULL : explode("###", $data["diagnostico_tratamiento"]["globalDiagnosticosTratamientoRelacionados"]));
        return $vicIts;
    }

    public static function dataVicItsAntibioticos($data) {
        $vicIts = array();
        $vicIts["antibioticos"] = (!isset($data["otros_datos"]["globalAntibioticosRelacionados"]) ? NULL : explode("###", $data["otros_datos"]["globalAntibioticosRelacionados"]));
        return $vicIts;
    }

    public static function dataVicItsSintomasSignos($data) {
        $vicIts = array();
        $vicIts["sintomas_signos"] = (!isset($data["sintomas"]["globalSintomasSignosRelacionados"]) ? NULL : explode("###", $data["sintomas"]["globalSintomasSignosRelacionados"]));
        return $vicIts;
    }

    public static function validarString($cad) {
        return (!isset($cad) ? NULL : $cad);
    }

    public static function validarCombo($cad) {
        $cad2 = helperVicIts::validarString($cad);
        if ($cad2 != NULL)
            if ($cad2 == "0" || $cad == "-1")
                return NULL;
            else
                return $cad2;
        else
            return $cad2;
    }

    public static function validarCheck($cad) {
        $cad2 = helperVicIts::validarString($cad);
        if ($cad2 != NULL)
            if ($cad2 == "on")
                return "1";
            else
                return $cad2;
        else
            return $cad2;
    }

    public static function validarFecha($cad) {
        return (!isset($cad) ? NULL : helperString::toDate($cad));
    }

    public static function dataVicItsForm($data) {
        $vicIts = array();
        
//////////////////////////////////////////////// Individuo ////////////////////////////////////////////////
        
        $vicIts["id_tipo_identidad"] = (!isset($data["individuo"]["tipoId"]) ? NULL : $data["individuo"]["tipoId"]);
        $vicIts["numero_identificacion"] = (!isset($data["individuo"]["identificador"]) ? NULL : $data["individuo"]["identificador"]);

        $vicIts["per_nombre_trans"] = (!isset($data["individuo"]["nombre_identidad"]) ? NULL : $data["individuo"]["nombre_identidad"]);
        $vicIts["per_edad"] = (!isset($data["individuo"]["edad"]) ? NULL : $data["individuo"]["edad"]);
        $vicIts["per_tipo_edad"] = (!isset($data["individuo"]["tipo_edad"]) ? '4' : $data["individuo"]["tipo_edad"]);
        $vicIts["per_id_pais"] = (!isset($data["individuo"]["pais"]) ? NULL : $data["individuo"]["pais"]);
        if ($vicIts["per_id_pais"] == 174) {
            $vicIts["per_id_corregimiento"] = $data["individuo"]["corregimiento"];
            $vicIts["per_localidad"] = (!isset($data["individuo"]["lugar_poblado"]) ? NULL : $data["individuo"]["lugar_poblado"]);
        }
        $vicIts["per_estado_civil"] = (!isset($data["individuo"]["estado_civil"]) ? NULL : $data["individuo"]["estado_civil"]);
        $vicIts["per_nombre_trans"] = (!isset($data["individuo"]["nombre_identidad"]) ? NULL : $data["individuo"]["nombre_identidad"]);
        $vicIts["per_sabe_leer"] = (!isset($data["antecedentes"]["sabeLeer"]) ? NULL : $data["antecedentes"]["sabeLeer"]);        

//////////////////////////////////////////////Antecedentes generales///////////////////////////////////////////////////////
        $vicIts["antec_abuso_sexual"] = (!isset($data["antecedentes"]["abusoSexual"]) ? NULL : $data["antecedentes"]["abusoSexual"]);
        $vicIts["antec_edad_abuso_sexual"] = (!isset($data["antecedentes"]["edad_AS"]) ? NULL : $data["antecedentes"]["edad_AS"]);
        $vicIts["antec_abuso_ultimo"] = (!isset($data["antecedentes"]["abusoSexual12"]) ? NULL : $data["antecedentes"]["abusoSexual12"]);
        $vicIts["antec_edad_inicio_sexual"] = (!isset($data["antecedentes"]["vida_sexual"]) ? NULL : $data["antecedentes"]["vida_sexual"]);
        //$vicIts["comp_uso_condon"] = (!isset($data["individuo"]["condonRel"]) ? NULL : $data["individuo"]["condonRel"]);
        $vicIts["antec_ts_alguna_vez"] = (!isset($data["antecedentes"]["TS"]) ? NULL : $data["antecedentes"]["TS"]);
        $vicIts["antec_ts_actual"] = (!isset($data["antecedentes"]["actual_TS"]) ? NULL : $data["antecedentes"]["actual_TS"]);
        $vicIts["antec_ts_tiempo"] = (!isset($data["antecedentes"]["tiempo_TS"]) ? NULL : $data["antecedentes"]["tiempo_TS"]);
        $vicIts["antec_ts_tiempo_anios"] = (!isset($data["antecedentes"]["cuanto_TS"]) ? NULL : $data["antecedentes"]["cuanto_TS"]);
        $vicIts["antec_ts_otro_pais"] = (!isset($data["antecedentes"]["otroPais_TS"]) ? NULL : $data["antecedentes"]["otroPais_TS"]);
        if($vicIts["antec_ts_otro_pais"] == 1 )
            $vicIts["antec_ts_id_pais"] = (!isset($data["antecedentes"]["pais_TS"]) ? NULL : $data["antecedentes"]["pais_TS"]);
        $vicIts["antec_relacion"] = (!isset($data["antecedentes"]["relacionSexual"]) ? NULL : $data["antecedentes"]["relacionSexual"]);
        $vicIts["antec_its_ultimo"] = (!isset($data["antecedentes"]["itsUltimo"]) ? NULL : $data["antecedentes"]["itsUltimo"]);
        $vicIts["antec_ts_nombre_lugar"] = (!isset($data["antecedentes"]["nombreLugar_TS"]) ? NULL : $data["antecedentes"]["nombreLugar_TS"]);
        $vicIts["antec_ts_tipo_lugar"] = (!isset($data["antecedentes"]["tipoLugar_TS"]) ? NULL : $data["antecedentes"]["tipoLugar_TS"]);
        $vicIts["antec_vih"] = (!isset($data["antecedentes"]["vih"]) ? NULL : $data["antecedentes"]["vih"]);
        $vicIts["antec_fecha_vih"] = (!isset($data["antecedentes"]["fecha_vih"]) ? NULL : helperVicIts::validarFecha($data["antecedentes"]["fecha_vih"]));
        $vicIts["antec_consejeria_pre"] = (!isset($data["antecedentes"]["pre_vih"]) ? NULL : $data["antecedentes"]["pre_vih"]);
        $vicIts["antec_consejeria_post"] = (!isset($data["antecedentes"]["pos_vih"]) ? NULL : $data["antecedentes"]["pos_vih"]);
        $vicIts["antec_referido_TARV"] = (!isset($data["antecedentes"]["tarv"]) ? NULL : $data["antecedentes"]["tarv"]);
        $vicIts["id_clinica_tarv"] = (!isset($data["antecedentes"]["clinica_tarv"]) ? NULL : $data["antecedentes"]["clinica_tarv"]);
        $vicIts["antec_consumo_alcohol"] = (!isset($data["antecedentes"]["consumoAlcohol"]) ? NULL : $data["antecedentes"]["consumoAlcohol"]);
        $vicIts["antec_consumo_alcohol_semana"] = (!isset($data["antecedentes"]["frecuenciaAlcohol"]) ? NULL : $data["antecedentes"]["frecuenciaAlcohol"]);
        $vicIts["antec_anticonceptivo"] = (!isset($data["antecedentes"]["anticonceptivo"]) ? NULL : $data["antecedentes"]["anticonceptivo"]);
        $vicIts["antec_anticonceptivo_diu"] = (!isset($data["antecedentes"]["diu"]) ? 0 : 1);
        $vicIts["antec_anticonceptivo_pildora"] = (!isset($data["antecedentes"]["pildora"]) ? 0 : 1);
        $vicIts["antec_anticonceptivo_condon"] = (!isset($data["antecedentes"]["condon"]) ? 0 : 1);
        $vicIts["antec_anticonceptivo_inyeccion"] = (!isset($data["antecedentes"]["inyeccion"]) ? 0 : 1);
        $vicIts["antec_anticonceptivo_esteriliza"] = (!isset($data["antecedentes"]["esteril"]) ? 0 : 1);
        $vicIts["antec_anticonceptivo_otro"] = (!isset($data["antecedentes"]["otro"]) ? 0 : 1);
        $vicIts["antec_anticonceptivo_nombre_otro"] = (!isset($data["antecedentes"]["otro_anti"]) ? 0 : 1);
        $vicIts["antec_obstetrico_menarquia"] = (!isset($data["antecedentes"]["ginecoMenarquia"]) ? NULL : $data["antecedentes"]["ginecoMenarquia"]);
        $vicIts["antec_obstetrico_abortos"] = (!isset($data["antecedentes"]["ginecoAbortos"]) ? NULL : $data["antecedentes"]["ginecoAbortos"]);
        $vicIts["antec_obstetrico_muertos"] = (!isset($data["antecedentes"]["ginecoVivos"]) ? NULL : $data["antecedentes"]["ginecoVivos"]);
        $vicIts["antec_obstetrico_vivos"] = (!isset($data["antecedentes"]["ginecoMuertos"]) ? NULL : $data["antecedentes"]["ginecoMuertos"]);
        $vicIts["antec_obstetrico_total"] = (!isset($data["antecedentes"]["ginecoEmbarazos"]) ? NULL : $data["antecedentes"]["ginecoEmbarazos"]);
        
//        return $vicIts;
        
/////////////////////////////////////BEGIN PARTE A - PARTE B////////////////////////////////////////////////////////        
//PARTE A
//[sintomas] => Array ( 
//	[antec_motivo_consulta] => 0 
        $vicIts["antec_motivo_consulta"] = helperVicIts::validarCombo($data["sintomas"]["antec_motivo_consulta"]);
//	[globalSintomasSignosRelacionados] => 1-22 )
//[otros_datos] => Array ( 
//	[otro_antibiotico] => -1 
        $vicIts["otro_antibiotico"] = helperVicIts::validarCombo($data["otros_datos"]["otro_antibiotico"]);
//	[otro_ovulos_vagina] => -1 
        $vicIts["otro_ovulos_vagina"] = helperVicIts::validarCombo($data["otros_datos"]["otro_ovulos_vagina"]);
//	[otro_ducha_vagina] => -1 
        $vicIts["otro_ducha_vagina"] = helperVicIts::validarCombo($data["otros_datos"]["otro_ducha_vagina"]);
//	[otro_fecha_citologia] => 
        $vicIts["otro_fecha_citologia"] = helperVicIts::validarFecha($data["otros_datos"]["otro_fecha_citologia"]);
//	[otro_citologia_resultado] => 
        $vicIts["otro_citologia_resultado"] = helperVicIts::validarString($data["otros_datos"]["otro_citologia_resultado"]);
//	[globalAntibioticosRelacionados] => ) 
//[condon] => Array ( 
//	[condon_rel_anal_otro] => -1 
        $vicIts["condon_rel_anal_otro"] = helperVicIts::validarCombo($data["condon"]["condon_rel_anal_otro"]);
//	[condon_rel_sexual] => -1 
        $vicIts["condon_rel_sexual"] = helperVicIts::validarCombo($data["condon"]["condon_rel_sexual"]);
//	[condon_rel_anal] => -1 
        $vicIts["condon_rel_anal"] = helperVicIts::validarCombo($data["condon"]["condon_rel_anal"]);
//	[condon_tipo_rel_anal] => -1 
        $vicIts["condon_tipo_rel_anal"] = helperVicIts::validarCombo($data["condon"]["condon_tipo_rel_anal"]);
//	[condon_sexo_oral] => -1 
        $vicIts["condon_sexo_oral"] = helperVicIts::validarCombo($data["condon"]["condon_sexo_oral"]);
//	[condon_ult_rel_uso_condon] => -1 
        $vicIts["condon_ult_rel_uso_condon"] = helperVicIts::validarCombo($data["condon"]["condon_ult_rel_uso_condon"]);
//	[par_hombre_fija] => -1 
        $vicIts["par_hombre_fija"] = helperVicIts::validarCombo($data["condon"]["par_hombre_fija"]);
        if ($vicIts["par_hombre_fija"] == '1') {
//	[par_hombre_fija_uso_condon] => -1 
            $vicIts["par_hombre_fija_uso_condon"] = helperVicIts::validarCombo($data["condon"]["par_hombre_fija_uso_condon"]);
//	[par_hombre_fija_ult_usu_condon] => -1 
            $vicIts["par_hombre_fija_ult_usu_condon"] = helperVicIts::validarCombo($data["condon"]["par_hombre_fija_ult_usu_condon"]);
        }
//	[par_hombre_casual] => -1 
        $vicIts["par_hombre_casual"] = helperVicIts::validarCombo($data["condon"]["par_hombre_casual"]);
        if ($vicIts["par_hombre_casual"] == '1') {
//	[par_hombre_casual_uso_condon] => -1 
            $vicIts["par_hombre_casual_uso_condon"] = helperVicIts::validarCombo($data["condon"]["par_hombre_casual_uso_condon"]);
//	[par_hombre_casual_ult_usu_condon] => -1 
            $vicIts["par_hombre_casual_ult_usu_condon"] = helperVicIts::validarCombo($data["condon"]["par_hombre_casual_ult_usu_condon"]);
        }
//	[par_mujer_fija] => -1 
        $vicIts["par_mujer_fija"] = helperVicIts::validarCombo($data["condon"]["par_mujer_fija"]);
        if ($vicIts["par_mujer_fija"] == '1') {
//	[par_mujer_fija_uso_condon] => -1 
            $vicIts["par_mujer_fija_uso_condon"] = helperVicIts::validarCombo($data["condon"]["par_mujer_fija_uso_condon"]);
//	[par_mujer_fija_ult_usu_condon] => -1 
            $vicIts["par_mujer_fija_ult_usu_condon"] = helperVicIts::validarCombo($data["condon"]["par_mujer_fija_ult_usu_condon"]);
        }
//	[par_mujer_casual] => -1 
        $vicIts["par_mujer_casual"] = helperVicIts::validarCombo($data["condon"]["par_mujer_casual"]);
        if ($vicIts["par_mujer_casual"] == '1') {
//	[par_mujer_casual_uso_condon] => -1 
            $vicIts["par_mujer_casual_uso_condon"] = helperVicIts::validarCombo($data["condon"]["par_mujer_casual_uso_condon"]);
//	[par_mujer_casual_ult_usu_condon] => 1 
            $vicIts["par_mujer_casual_ult_usu_condon"] = helperVicIts::validarCombo($data["condon"]["par_mujer_casual_ult_usu_condon"]);
        }
//	[ts_cliente_quincena] => [ts_cliente_semana] => 
        $vicIts["ts_cliente_quincena"] = helperVicIts::validarString($data["condon"]["ts_cliente_quincena"]);
        $vicIts["ts_cliente_semana"] = helperVicIts::validarString($data["condon"]["ts_cliente_semana"]);
//	[ts_uso_condon] => -1 
        $vicIts["ts_uso_condon"] = helperVicIts::validarCombo($data["condon"]["ts_uso_condon"]);
//      [ts_ultimo_usu_condon] => -1 ) 
        $vicIts["ts_ultimo_usu_condon"] = helperVicIts::validarCombo($data["condon"]["ts_ultimo_usu_condon"]);

//[examen_general] => Array (  // 
//	[exa_realizado] => -1 
        $vicIts["exa_realizado"] = helperVicIts::validarCombo($data["examen_general"]["exa_realizado"]);
        if ($vicIts["exa_realizado"] == '1') {
//	[exa_temperatura] => 
            $vicIts["exa_temperatura"] = helperVicIts::validarString($data["examen_general"]["exa_temperatura"]);
//	[exa_libras] => 
            $vicIts["exa_libras"] = helperVicIts::validarString($data["examen_general"]["exa_libras"]);
//	[exa_PA] => 
            $vicIts["exa_PA"] = helperVicIts::validarString($data["examen_general"]["exa_PA"]);
//	[exa_ganglio] => -1 
            $vicIts["exa_ganglio"] = helperVicIts::validarCombo($data["examen_general"]["exa_ganglio"]);
            if ($vicIts["exa_ganglio"] == '2') {
                $vicIts["exa_ganglio_cuello"] = helperVicIts::validarCheck($data["examen_general"]["exa_ganglio_cuello"]);
                $vicIts["exa_ganglio_axilar"] = helperVicIts::validarCheck($data["examen_general"]["exa_ganglio_axilar"]);
                $vicIts["exa_ganglio_inguinal"] = helperVicIts::validarCheck($data["examen_general"]["exa_ganglio_inguinal"]);
            }
//	[exa_rash] => -1 
            $vicIts["exa_rash"] = helperVicIts::validarCombo($data["examen_general"]["exa_rash"]);
            if ($vicIts["exa_rash"] == '2') {
                $vicIts["exa_rash_opcion"] = helperVicIts::validarCombo($data["examen_general"]["exa_rash_opcion"]);
            }
//	[exa_boca] => -1 
            $vicIts["exa_boca"] = helperVicIts::validarCombo($data["examen_general"]["exa_boca"]);
            if ($vicIts["exa_boca"] == '2') {
                $vicIts["exa_boca_monilia"] = helperVicIts::validarCheck($data["examen_general"]["exa_boca_monilia"]);
                $vicIts["exa_boca_ulcera"] = helperVicIts::validarCheck($data["examen_general"]["exa_boca_ulcera"]);
                $vicIts["exa_boca_amigdalas"] = helperVicIts::validarCheck($data["examen_general"]["exa_boca_amigdalas"]);
                $vicIts["exa_boca_irritacion_faringea"] = helperVicIts::validarCheck($data["examen_general"]["exa_boca_irritacion_faringea"]);
                $vicIts["exa_boca_otro"] = helperVicIts::validarCheck($data["examen_general"]["exa_boca_otro"]);
            }
//	[exa_pene] => -1 
            $vicIts["exa_pene"] = helperVicIts::validarCombo($data["examen_general"]["exa_pene"]);
            if ($vicIts["exa_pene"] == '2') {
                $vicIts["exa_pene_ulcera"] = helperVicIts::validarCheck($data["examen_general"]["exa_pene_ulcera"]);
                $vicIts["exa_pene_verruga"] = helperVicIts::validarCheck($data["examen_general"]["exa_pene_verruga"]);
                $vicIts["exa_pene_ampolla"] = helperVicIts::validarCheck($data["examen_general"]["exa_pene_ampolla"]);
                $vicIts["exa_pene_otro"] = helperVicIts::validarCheck($data["examen_general"]["exa_pene_otro"]);
            }
//	[exa_testiculo] => -1 
            $vicIts["exa_testiculo"] = helperVicIts::validarCombo($data["examen_general"]["exa_testiculo"]);
            if ($vicIts["exa_testiculo"] == '2') {
                $vicIts["exa_testiculo_ulcera"] = helperVicIts::validarCheck($data["examen_general"]["exa_testiculo_ulcera"]);
                $vicIts["exa_testiculo_verruga"] = helperVicIts::validarCheck($data["examen_general"]["exa_testiculo_verruga"]);
                $vicIts["exa_testiculo_ampolla"] = helperVicIts::validarCheck($data["examen_general"]["exa_testiculo_ampolla"]);
                $vicIts["exa_testiculo_otro"] = helperVicIts::validarCheck($data["examen_general"]["exa_testiculo_otro"]);
            }
//	[exa_abdomen] => -1 
            $vicIts["exa_abdomen"] = helperVicIts::validarCombo($data["examen_general"]["exa_abdomen"]);
            if ($vicIts["exa_abdomen"] == '2') {
                $vicIts["exa_abdomen_fosa_izq"] = helperVicIts::validarCheck($data["examen_general"]["exa_abdomen_fosa_izq"]);
                $vicIts["exa_abdomen_hipogastrico"] = helperVicIts::validarCheck($data["examen_general"]["exa_abdomen_hipogastrico"]);
                $vicIts["exa_abdomen_fosa_der"] = helperVicIts::validarCheck($data["examen_general"]["exa_abdomen_fosa_der"]);
            }
//	[exa_vulva] => -1 
            $vicIts["exa_vulva"] = helperVicIts::validarCombo($data["examen_general"]["exa_vulva"]);
            if ($vicIts["exa_vulva"] == '2') {
                $vicIts["exa_vulva_ulcera"] = helperVicIts::validarCheck($data["examen_general"]["exa_vulva_ulcera"]);
                $vicIts["exa_vulva_verruga"] = helperVicIts::validarCheck($data["examen_general"]["exa_vulva_verruga"]);
                $vicIts["exa_vulva_vesicula"] = helperVicIts::validarCheck($data["examen_general"]["exa_vulva_vesicula"]);
                $vicIts["exa_vulva_otro"] = helperVicIts::validarCheck($data["examen_general"]["exa_vulva_otro"]);
            }
//	[exa_meato] => -1 
            $vicIts["exa_meato"] = helperVicIts::validarCombo($data["examen_general"]["exa_meato"]);
            if ($vicIts["exa_meato"] == '2') {
                $vicIts["exa_meato_hiperemia"] = helperVicIts::validarCheck($data["examen_general"]["exa_meato_hiperemia"]);
                $vicIts["exa_meato_secrecion"] = helperVicIts::validarCheck($data["examen_general"]["exa_meato_secrecion"]);
                $vicIts["exa_meato_verruga"] = helperVicIts::validarCheck($data["examen_general"]["exa_meato_verruga"]);
            }
//	[exa_ano] => -1 )
            $vicIts["exa_ano"] = helperVicIts::validarCombo($data["examen_general"]["exa_ano"]);
            if ($vicIts["exa_ano"] == '2') {
                $vicIts["exa_ano_ulcera"] = helperVicIts::validarCheck($data["examen_general"]["exa_ano_ulcera"]);
                $vicIts["exa_ano_verruga"] = helperVicIts::validarCheck($data["examen_general"]["exa_ano_verruga"]);
                $vicIts["exa_ano_secrecion"] = helperVicIts::validarCheck($data["examen_general"]["exa_ano_secrecion"]);
                $vicIts["exa_ano_vesicula"] = helperVicIts::validarCheck($data["examen_general"]["exa_ano_vesicula"]);
                $vicIts["exa_ano_otro"] = helperVicIts::validarCheck($data["examen_general"]["exa_ano_otro"]);
            }
        }
//[examen_especulo] => Array ( 
//	[exa_especulo_realizado] => -1 
        $vicIts["exa_especulo_realizado"] = helperVicIts::validarCombo($data["examen_especulo"]["exa_especulo_realizado"]);
        if ($vicIts["exa_especulo_realizado"] == '1') {
//	[exa_vagina] => -1 
            $vicIts["exa_vagina"] = helperVicIts::validarCombo($data["examen_especulo"]["exa_vagina"]);
            if ($vicIts["exa_vagina"] == '2') {
                $vicIts["exa_vagina_ulcera"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_vagina_ulcera"]);
                $vicIts["exa_vagina_hiperamia"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_vagina_hiperamia"]);
                $vicIts["exa_vagina_menstruacion"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_vagina_menstruacion"]);
                $vicIts["exa_vagina_atrofia"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_vagina_atrofia"]);
                $vicIts["exa_vagina_otro"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_vagina_otro"]);
            }
//	[exa_flujo] => -1 
            $vicIts["exa_flujo"] = helperVicIts::validarCombo($data["examen_especulo"]["exa_flujo"]);
            if ($vicIts["exa_flujo"] == '1') {
                $vicIts["exa_flujo_asp_sanguinolento"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_flujo_asp_sanguinolento"]);
                $vicIts["exa_flujo_asp_grumoso"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_flujo_asp_grumoso"]);
                $vicIts["exa_flujo_asp_espumoso"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_flujo_asp_espumoso"]);
                $vicIts["exa_flujo_asp_mucoso"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_flujo_asp_mucoso"]);
                $vicIts["exa_flujo_olor"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_flujo_olor"]);
            }
//	[exa_flujo_cantidad] => -1
            $vicIts["exa_flujo_cantidad"] = helperVicIts::validarCombo($data["examen_especulo"]["exa_flujo_cantidad"]);
//	[exa_flujo_color] => -1 
            $vicIts["exa_flujo_color"] = helperVicIts::validarCombo($data["examen_especulo"]["exa_flujo_color"]);
//	[exa_cervix] => -1 
            $vicIts["exa_cervix"] = helperVicIts::validarCombo($data["examen_especulo"]["exa_cervix"]);
            if ($vicIts["exa_cervix"] == '2') {
                $vicIts["exa_cervix_ulcera"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_cervix_ulcera"]);
                $vicIts["exa_cervix_hiperamia"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_cervix_hiperamia"]);
                $vicIts["exa_cervix_friable"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_cervix_friable"]);
                $vicIts["exa_cervix_tumor"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_cervix_tumor"]);
                $vicIts["exa_cervix_pus"] = helperVicIts::validarCheck($data["examen_especulo"]["exa_cervix_pus"]);
            }
        }
//[examen_bimanual] => Array ( 
//	[exa_bimanual_realizado] => -1 ) 
        $vicIts["exa_bimanual_realizado"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bimanual_realizado"]);
        if ($vicIts["exa_bimanual_realizado"] == '1') {
//	[exa_bi_anexo] => -1 
            $vicIts["exa_bi_anexo"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_anexo"]);
//	[exa_bi_anexo_sangrado] => -1 
            $vicIts["exa_bi_anexo_sangrado"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_anexo_sangrado"]);
//	[exa_bi_anexo_dolor] => -1 
            $vicIts["exa_bi_anexo_dolor"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_anexo_dolor"]);
//	[exa_bi_anexo_tumor] => -1 
            $vicIts["exa_bi_anexo_tumor"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_anexo_tumor"]);
//	[exa_bi_hipogastrico] => -1 
            $vicIts["exa_bi_hipogastrico"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_hipogastrico"]);
//	[exa_bi_cervix] => -1 
            $vicIts["exa_bi_cervix"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_cervix"]);
//	[exa_bi_cervix_anormal] => -1  ALGUIEN LA QUITO
//            $vicIts["exa_bi_cervix_anormal"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_cervix_anormal"]);
            $vicIts["exa_bi_cervix_ausente"] = helperVicIts::validarCheck($data["examen_bimanual"]["exa_bi_cervix_ausente"]);
            $vicIts["exa_bi_cervix_dolor"] = helperVicIts::validarCheck($data["examen_bimanual"]["exa_bi_cervix_dolor"]);
//	[exa_bi_utero] => -1 
            $vicIts["exa_bi_utero"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_utero"]);
//	[exa_bi_utero_anormal] => -1 ) 
            $vicIts["exa_bi_utero_anormal"] = helperVicIts::validarCombo($data["examen_bimanual"]["exa_bi_utero_anormal"]);
        }

//PARTE B
//[muestras_laboratorio] => Array ( 
//	[usuario_sano] => -1 
        $vicIts["usuario_sano"] = helperVicIts::validarCombo($data["muestras_laboratorio"]["usuario_sano"]);
//	[muestra_ninguna] => -1 
        $vicIts["muestra_ninguna"] = helperVicIts::validarCombo($data["muestras_laboratorio"]["muestra_ninguna"]);
        if ($vicIts["muestra_ninguna"] == '1') {
            $vicIts["muestra_sangre_ts"] = helperVicIts::validarCheck($data["muestras_laboratorio"]["muestra_sangre_ts"]);
            $vicIts["muestra_flujo_vaginal"] = helperVicIts::validarCheck($data["muestras_laboratorio"]["muestra_flujo_vaginal"]);
            $vicIts["muestra_endocervix"] = helperVicIts::validarCheck($data["muestras_laboratorio"]["muestra_endocervix"]);
            $vicIts["muestra_citologia"] = helperVicIts::validarCheck($data["muestras_laboratorio"]["muestra_citologia"]);
            $vicIts["muestra_ulcera_ts"] = helperVicIts::validarCheck($data["muestras_laboratorio"]["muestra_ulcera_ts"]);
            $vicIts["muestra_sangre_hsh"] = helperVicIts::validarCheck($data["muestras_laboratorio"]["muestra_sangre_hsh"]);
            $vicIts["muestra_ulcera"] = helperVicIts::validarCheck($data["muestras_laboratorio"]["muestra_ulcera"]);
            $vicIts["muestra_secrecion_uretral"] = helperVicIts::validarCheck($data["muestras_laboratorio"]["muestra_secrecion_uretral"]);
            $vicIts["muestra_secrecion_anal"] = helperVicIts::validarCheck($data["muestras_laboratorio"]["muestra_secrecion_anal"]);
        }
//	[fecha_menstruacion] => 
        $vicIts["fecha_menstruacion"] = helperVicIts::validarFecha($data["muestras_laboratorio"]["fecha_menstruacion"]);
//	[embarazo] => -1 
        $vicIts["embarazo"] = helperVicIts::validarCombo($data["muestras_laboratorio"]["embarazo"]);
        if ($vicIts["embarazo"] == '1')
//	[embarazo_semanas] => ) 
            $vicIts["embarazo_semanas"] = helperVicIts::validarString($data["muestras_laboratorio"]["embarazo_semanas"]);

//[diagnostico_tratamiento] => Array ( 
//	[otro_tratamiento] => -1 
        $vicIts["otro_tratamiento"] = helperVicIts::validarCombo($data["diagnostico_tratamiento"]["otro_tratamiento"]);
        if ($vicIts["otro_tratamiento"] == '1') {
            $vicIts["tx_sulfato"] = helperVicIts::validarCheck($data["diagnostico_tratamiento"]["tx_sulfato"]);
            $vicIts["tx_acido_folico"] = helperVicIts::validarCheck($data["diagnostico_tratamiento"]["tx_acido_folico"]);
            $vicIts["tx_prenatales"] = helperVicIts::validarCheck($data["diagnostico_tratamiento"]["tx_prenatales"]);
            $vicIts["tx_toxoide"] = helperVicIts::validarCheck($data["diagnostico_tratamiento"]["tx_toxoide"]);            
//	[diag_otro] => 
            $vicIts["diag_otro"] = helperVicIts::validarString($data["diagnostico_tratamiento"]["diag_otro"]);
//	[diag_otro_medicamento] => 
            $vicIts["diag_otro_medicamento"] = helperVicIts::validarString($data["diagnostico_tratamiento"]["diag_otro_medicamento"]);
        }
        $vicIts["intervencion"] = helperVicIts::validarCombo($data["diagnostico_tratamiento"]["intervencion"]);
//	[globalDiagnosticosTratamientoRelacionados] => 1-1-1###3-5-7 )
//[notificacion] => Array ( 
//	[noti_referencia_pareja] => -1 
        $vicIts["noti_referencia_pareja"] = helperVicIts::validarCombo($data["notificacion"]["noti_referencia_pareja"]);
//	[noti_preservativos] => -1 
        $vicIts["noti_preservativos"] = helperVicIts::validarCombo($data["notificacion"]["noti_preservativos"]);
        if ($vicIts["noti_preservativos"] == '1')
            $vicIts["noti_preservativos_cuantos"] = helperVicIts::validarString($data["notificacion"]["noti_preservativos_cuantos"]);
//	[noti_medicamento1] => 
        $vicIts["noti_medicamento1"] = helperVicIts::validarString($data["notificacion"]["noti_medicamento1"]);
//	[noti_medicamento2] => 
        $vicIts["noti_medicamento2"] = helperVicIts::validarString($data["notificacion"]["noti_medicamento2"]);
//	[noti_medicamento3] => 
        $vicIts["noti_medicamento3"] = helperVicIts::validarString($data["notificacion"]["noti_medicamento3"]);
//	[noti_numero] => ) 
        $vicIts["id_un"] = helperVicIts::validarString($data["notificacion"]["noti_id_un"]);
        $vicIts["noti_nombre_medico"] = helperVicIts::validarString($data["notificacion"]["noti_nombre_medico"]);
        $vicIts["fecha_consulta"] = helperVicIts::validarFecha($data["notificacion"]["fecha_consulta"]);
        $vicIts["nombre_registra"] = helperVicIts::validarString($data["notificacion"]["nombre_registra"]);
        $vicIts["fecha_formulario"] = helperVicIts::validarFecha($data["notificacion"]["fecha_form_vicits"]);
        
        $semanaanio = helperString::calcularSemanaEpiOtroFormato($data["notificacion"]["fecha_consulta"]);
        $vicIts["semana_epi"] = $semanaanio["semana"];
        $vicIts["anio"] = $semanaanio["anio"];
/////////////////////////////////////END PARTE A - PARTE B////////////////////////////////////////////////////////      
        return $vicIts;
    }
    
    public static function calcularSemanaEpi($time){
        $data = array();
        $unDia = 86400;
        $varlist = strtotime($time);
        $anioActual = strftime("%Y", $varlist);

        $first_day = strtotime("01/01/".$anioActual);
        $wkday = strftime("%w", $first_day);
        $fwb = ($wkday<=3) ? ($first_day-($wkday*$unDia)) : ($first_day+(7*$unDia)-($wkday*$unDia));
        if ($varlist < $fwb){
            $first_day = strtotime("01/01/".($anioActual-1));
            $wkday = strftime("%w", $first_day);
            $fwb = ($wkday<=3) ? $first_day-($wkday*$unDia) : $first_day+(7*$unDia)-($wkday*$unDia);
        }

        $last_day = strtotime("12/31/".$anioActual);
        $wkday = strftime("%w", $last_day);
        $ewb = ($wkday<3) ? ($last_day-($wkday*$unDia))-(1*$unDia) : $last_day+(6*$unDia)-($wkday*$unDia);
        if ($varlist > $ewb)
            $fwb = $ewb+(1*$unDia);

        $semanaEpi = floor((($varlist-$fwb)/(7*$unDia))+1);
        $anioEpi = strftime("%Y", $fwb+(180*$unDia));
        
        $data["semana"] = $semanaEpi;
        $data["anio"] = $anioEpi;
        
        return $data;
    }

    // Obtiene el listado de uceti
    public static function buscarVicits($config) {
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
            $sql = "select * from vicits_form its 
                    inner join tbl_persona per on per.tipo_identificacion = its.id_tipo_identidad and per.numero_identificacion = its.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = its.id_un
                    left join cat_region_salud re on re.id_region = un.id_region";
        else {
            if ($config["filtro"] != "") {
                $filtro1 = " AND (un.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                        " OR re.nombre_region LIKE '%" . $config["filtro"] . "%'" .
                        " OR its.semana_epi LIKE '%" . $config["filtro"] . "%'" .
                        " OR its.anio LIKE '%" . $config["filtro"] . "%'" .
                        " OR its.numero_identificacion LIKE '%" . $config["filtro"] . "%')";
            } else if (isset($config["id_tipo_identidad"]) && isset($config["numero_identificacion"])) {
                $filtro2 = " AND its.id_tipo_identidad='" . $config["id_tipo_identidad"] . "'" .
                        " AND its.numero_identificacion='" . $config["numero_identificacion"] . "'";
              if (isset($config["id_vicits_form"]))  
                $filtro2 .= " AND its.id_vicits_form='" . $config["id_vicits_form"] . "'";
                $read = true;
            }
            $sql = "select * from vicits_form its 
                    inner join tbl_persona per on per.tipo_identificacion = its.id_tipo_identidad and per.numero_identificacion = its.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = its.id_un
                    left join cat_region_salud re on re.id_region = un.id_region WHERE 1 "
                    . $filtro1 . $filtro2 . ' order by id_vicits_form desc';
            //. " limit " . $config["inicio"] . "," . $config["paginado"];
            if (!$read) {
                $sql.= " limit " . $config["inicio"] . "," . $config["paginado"];
            }
        }
        //echo $sql; exit;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
    public static function buscarVicitsTabla($config) {
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
            $sql = "select T1.id_tipo_identidad, T1.id_vicits_form, DATE_FORMAT(T1.fecha_consulta ,'%d-%m-%Y') as fecha_consulta, T1.numero_identificacion, concat(T2.primer_nombre,' ',T2.primer_apellido) as nombre, 
                    case when T3.nombre_un is null then 'No disponible' else T3.nombre_un end as nombre_un, T1.semana_epi,
                    case when T1.antec_motivo_consulta = 1 then 'Nuevo' 
                         when T1.antec_motivo_consulta = 2 then 'Molestias'
                         when T1.antec_motivo_consulta = 3 then 'Con. trimestral'
                         when T1.antec_motivo_consulta = 4 then 'Con. semestral' else 'No disponible' end as motivo_consulta, T2.sexo
                    FROM vicits_form T1
                    inner join tbl_persona T2 on T1.numero_identificacion = T2.numero_identificacion and T1.id_tipo_identidad = T2.tipo_identificacion
                    left join cat_unidad_notificadora T3 on T1.id_un = T3.id_un
                    order by T1.id_vicits_form desc";
        else {
            if ($config["filtro"] != "") {
                $filtro1 = " AND (T3.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                        " OR concat(T2.primer_nombre,' ',T2.primer_apellido) LIKE '%" . $config["filtro"] . "%'" .
                        " OR T1.semana_epi LIKE '%" . $config["filtro"] . "%'" .
                        " OR DATE_FORMAT(T1.fecha_consulta ,'%d-%m-%Y') LIKE '%" . $config["filtro"] . "%'" .
                        " OR T1.numero_identificacion LIKE '%" . $config["filtro"] . "%')";
            } else if (isset($config["id_tipo_identidad"]) && isset($config["numero_identificacion"])) {
                $filtro2 = " AND T1.id_tipo_identidad='" . $config["id_tipo_identidad"] . "'" .
                        " AND T1.numero_identificacion='" . $config["numero_identificacion"] . "'";
                $read = true;
            }
            $sql = "select T1.id_tipo_identidad, T1.id_vicits_form, DATE_FORMAT(T1.fecha_consulta ,'%d-%m-%Y') as fecha_consulta, T1.numero_identificacion, concat(T2.primer_nombre,' ',T2.primer_apellido) as nombre, 
                    case when T3.nombre_un is null then 'No disponible' else T3.nombre_un end as nombre_un, T1.semana_epi,
                    case when T1.antec_motivo_consulta = 1 then 'Nuevo' 
                         when T1.antec_motivo_consulta = 2 then 'Molestias'
                         when T1.antec_motivo_consulta = 3 then 'Con. trimestral'
                         when T1.antec_motivo_consulta = 4 then 'Con. semestral' else 'No disponible' end as motivo_consulta, T2.sexo
                    FROM vicits_form T1
                    inner join tbl_persona T2 on T1.numero_identificacion = T2.numero_identificacion and T1.id_tipo_identidad = T2.tipo_identificacion
                    left join cat_unidad_notificadora T3 on T1.id_un = T3.id_un WHERE 1=1 "
                    . $filtro1 . $filtro2 . ' order by T1.id_vicits_form desc';
            //. " limit " . $config["inicio"] . "," . $config["paginado"];
            if (!$read) {
                $sql.= " limit " . $config["inicio"] . "," . $config["paginado"];
            }
        }
        //echo $sql; exit;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    

    public static function buscarVicitsCantidad($config) {
        $conn = new Connection();
        $conn->initConn();
        $filtro1 = "";
        if ($config["filtro"] != "") {
            $filtro1 = " AND (T3.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                        " OR concat(T2.primer_nombre,' ',T2.primer_apellido) LIKE '%" . $config["filtro"] . "%'" .
                        " OR DATE_FORMAT(T1.fecha_consulta ,'%d-%m-%Y') LIKE '%" . $config["filtro"] . "%'" .
                        " OR T1.semana_epi LIKE '%" . $config["filtro"] . "%'" .
                        " OR T1.numero_identificacion LIKE '%" . $config["filtro"] . "%')";
        }
        $sql = "select count(*) as total FROM vicits_form T1
                inner join tbl_persona T2 on T1.numero_identificacion = T2.numero_identificacion and T1.id_tipo_identidad = T2.tipo_identificacion
                left join cat_unidad_notificadora T3 on T1.id_un = T3.id_un
                WHERE 1 " . $filtro1;
        //echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }

    public static function buscarVicitsIts($formVicIts) {
        if (isset($formVicIts)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = "";
            $filtro = " AND id_vicits_form=" . $formVicIts . "";
            $sql = "select * from vicits_its vicits_its
                    inner join cat_ITS its on its.id_ITS = vicits_its.id_ITS
                    WHERE 1 " . $filtro;
            //echo $sql; exit;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;
    }
    
    public static function buscarVicitsSignosSintomas($formVicIts) {
        if (isset($formVicIts)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = "";
            $filtro = " AND id_vicits_form=" . $formVicIts . "";
            $sql = "select * from vicits_sintoma vicits_sintoma
                    inner join cat_signo_sintoma val on val.id_signo_sintoma = vicits_sintoma.id_signo_sintoma
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
    
    public static function buscarVicitsAntibioticos($formVicIts) {
        if (isset($formVicIts)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = "";
            $filtro = " AND id_vicits_form=" . $formVicIts . "";
            $sql = "select * from vicits_antibiotico
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
    
    public static function buscarVicitsDiagnosticoTratamiento($formVicIts) {
        if (isset($formVicIts)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = "";
            $filtro = " AND id_vicits_form=" . $formVicIts . "";
            $sql = "select * from vicits_tratamiento vt
                    left join cat_diag_sindromico ON vt.id_diag_sindromico = cat_diag_sindromico.id_diag_sindromico
                    left join cat_diag_etiologico ON vt.id_diag_etiologico = cat_diag_etiologico.id_diag_etiologico
                    left join cat_tratamiento ON vt.id_tratamiento = cat_tratamiento.id_tratamiento
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

    public static function buscarVicitsDrogas($formVicIts) {
        if (isset($formVicIts)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = "";
            $filtro = " AND id_vicits_form=" . $formVicIts . "";
            $sql = "select * from vicits_droga vicits_droga
                    inner join cat_droga droga on droga.id_droga = vicits_droga.id_droga
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
    
    public static function dataVicItsLabForm($data) {
        $vicItsLab = array();
        
        $vicItsLab["formulario_tipo_consulta"] = (!isset($data["formulario"]["tipoConsulta"]) ? NULL : $data["formulario"]["tipoConsulta"]);
        $vicItsLab["formulario_fecha_consulta"] = helperVicIts::validarFecha($data["formulario"]["fecha_consulta"]);
        $vicItsLab["id_tipo_identidad"] = (!isset($data["formulario"]["tipoId"]) ? NULL : $data["formulario"]["tipoId"]);
        $vicItsLab["numero_identificacion"] = (!isset($data["formulario"]["identificador"]) ? NULL : $data["formulario"]["identificador"]);
        $vicItsLab["id_un"] = (!isset($data["formulario"]["id_un"]) ? NULL : $data["formulario"]["id_un"]);
        $vicItsLab["formulario_pre_prueba"] = (!isset($data["formulario"]["pre_prueba"]) ? NULL : $data["formulario"]["pre_prueba"]);
        $vicItsLab["id_grupo_poblacion"] = (!isset($data["formulario"]["poblacion"]) ? '4' : $data["formulario"]["poblacion"]);
        $vicItsLab["formulario_nombre_medico"] = (!isset($data["formulario"]["nombre_medico"]) ? NULL : $data["formulario"]["nombre_medico"]);
        
        $vicItsLab["resultado_poliformos"] = (!isset($data["resultados"]["poliformos"]) ? NULL : $data["resultados"]["poliformos"]);
        $vicItsLab["resultados_celulas"] = (!isset($data["resultados"]["celulas"]) ? NULL : $data["resultados"]["celulas"]);
        $vicItsLab["resultados_diplocco"] = (!isset($data["resultados"]["dipocloco"]) ? NULL : $data["resultados"]["dipocloco"]);
        $vicItsLab["resultados_levaduras"] = (!isset($data["resultados"]["levaduras"]) ? NULL : $data["resultados"]["levaduras"]);
        $vicItsLab["resultados_otros"] = (!isset($data["resultados"]["otros"]) ? NULL : $data["resultados"]["otros"]);
        $vicItsLab["resultados_flora"] = (!isset($data["resultados"]["flora"]) ? NULL : $data["resultados"]["flora"]);
        $vicItsLab["resultados_exa_levaduras"] = (!isset($data["resultados"]["levadura"]) ? '4' : $data["resultados"]["levadura"]);
        $vicItsLab["resultados_exa_trichomonas"] = (!isset($data["resultados"]["tricho"]) ? NULL : $data["resultados"]["tricho"]);
        $vicItsLab["resultados_exa_esperma"] = (!isset($data["resultados"]["esperma"]) ? NULL : $data["resultados"]["esperma"]);
        $vicItsLab["resultados_pcr_neisseria"] = (!isset($data["resultados"]["gonorrea"]) ? NULL : $data["resultados"]["gonorrea"]);
        $vicItsLab["resultados_pcr_chlamydia"] = (!isset($data["resultados"]["clamidia"]) ? '4' : $data["resultados"]["clamidia"]);
        $vicItsLab["resultados_pcr_lactamasa"] = (!isset($data["resultados"]["beta"]) ? NULL : $data["resultados"]["beta"]);
        $vicItsLab["resultados_vdrl_titulacion"] = (!isset($data["resultados"]["VDRL_titulacion"]) ? NULL : $data["resultados"]["VDRL_titulacion"]);
        $vicItsLab["resultados_vdrl"] = (!isset($data["resultados"]["VDRL"]) ? NULL : $data["resultados"]["VDRL"]);
        $vicItsLab["resultados_rpr_titulacion"] = (!isset($data["resultados"]["RPR_titulacion"]) ? '4' : $data["resultados"]["RPR_titulacion"]);
        $vicItsLab["resultados_rpr"] = (!isset($data["resultados"]["RPR"]) ? NULL : $data["resultados"]["RPR"]);
        $vicItsLab["resultados_tp_titulacion"] = (!isset($data["resultados"]["TP_titulacion"]) ? NULL : $data["resultados"]["TP_titulacion"]);
        $vicItsLab["resultados_tp"] = (!isset($data["resultados"]["TP"]) ? NULL : $data["resultados"]["TP"]);
        $vicItsLab["resultados_vih"] = (!isset($data["resultados"]["VIH"]) ? NULL : $data["resultados"]["VIH"]);
        $vicItsLab["resultados_pos_prueba"] = (!isset($data["resultados"]["pos"]) ? '4' : $data["resultados"]["pos"]);
        $vicItsLab["resultados_referido_tarv"] = (!isset($data["resultados"]["TARV"]) ? NULL : $data["resultados"]["TARV"]);
        
        return $vicItsLab;
    }
    
    public static function dataVicItsLabMuestras($data) {
        $vicItsLab = array();
        $vicItsLab["muestras"] = (!isset($data["tipo_muestra"]["globalMuestrasRelacionadas"]) ? NULL : explode("###",$data["tipo_muestra"]["globalMuestrasRelacionadas"]));
        return $vicItsLab;
    }
    
    public static function dataVicItsLabPruebas($data) {
        $vicItsLab = array();
        $vicItsLab["pruebas"] = (!isset($data["prueba_solicitada"]["globalPruebasRelacionadas"]) ? NULL : explode("###",$data["prueba_solicitada"]["globalPruebasRelacionadas"]));
        return $vicItsLab;
    }
    
    public static function buscarPersonaVicits($tipoId, $numeroId){
        if (isset($tipoId) && isset($numeroId)) {
            $conn = new Connection();
            $conn->initConn();
            $sql = "select  per.primer_nombre, per.segundo_nombre, per.primer_apellido, per.segundo_apellido
                    FROM tbl_persona per
                    inner join vicits_form vicits on per.numero_identificacion = vicits.numero_identificacion and per.tipo_identificacion = vicits.id_tipo_identidad
                    where per.tipo_identificacion =  $tipoId AND per.numero_identificacion = '$numeroId'";
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;        
    }
    
    public static function yaExisteVicItsLab($vicIts) {
        //return 0;
        $sql = "select count(*) from vicits_laboratorio where id_tipo_identidad=" . $vicIts['id_tipo_identidad']
                . " and numero_identificacion='" . $vicIts['numero_identificacion'] . "'"
                . " and id_un='" . $vicIts['id_un'] . "'"
                . " and formulario_nombre_medico='" . $vicIts['formulario_nombre_medico'] . "'";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }
    
    public static function buscarVicitsLab($config) {
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
            $sql = "select * from vicits_laboratorio its 
                    inner join tbl_persona per on per.tipo_identificacion = its.id_tipo_identidad and per.numero_identificacion = its.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = its.id_un
                    left join cat_region_salud re on re.id_region = un.id_region";
        else {
            if ($config["filtro"] != "") {
                $filtro1 = " AND (un.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                        " OR re.nombre_region LIKE '%" . $config["filtro"] . "%'" .
                        " OR its.numero_identificacion LIKE '%" . $config["filtro"] . "%')";
            } else if (isset($config["id_tipo_identidad"]) && isset($config["numero_identificacion"])) {
                $filtro2 = " AND its.id_tipo_identidad='" . $config["id_tipo_identidad"] . "'" .
                        " AND its.numero_identificacion='" . $config["numero_identificacion"] . "'";
                $read = true;
            }
            $sql = "select * from vicits_laboratorio its 
                    inner join tbl_persona per on per.tipo_identificacion = its.id_tipo_identidad and per.numero_identificacion = its.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = its.id_un
                    left join cat_region_salud re on re.id_region = un.id_region WHERE 1 "
                    . $filtro1 . $filtro2 . ' order by id_vicits_laboratorio desc';
            //. " limit " . $config["inicio"] . "," . $config["paginado"];
            if (!$read) {
                $sql.= " limit " . $config["inicio"] . "," . $config["paginado"];
            }
        }
        //echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function buscarVicitsLabCantidad($config) {
        $conn = new Connection();
        $conn->initConn();
        $filtro1 = "";
        if ($config["filtro"] != "") {
            $filtro1 = " AND (un.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                        " OR re.nombre_region LIKE '%" . $config["filtro"] . "%'" .
                        " OR its.numero_identificacion LIKE '%" . $config["filtro"] . "%')";
        }
        $sql = "select count(*) as total from vicits_laboratorio its 
                    inner join tbl_persona per on per.tipo_identificacion = its.id_tipo_identidad and per.numero_identificacion = its.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = its.id_un
                    left join cat_region_salud re on re.id_region = un.id_region WHERE 1 " . $filtro1;
        //echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }
    
    public static function buscarVicitsLabMuestras($idFormVicItsLab) {
        if (isset($idFormVicItsLab)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = "";
            $filtro = " AND id_vicits_laboratorio=" . $idFormVicItsLab . "";
            $sql = "select * from vicits_lab_muestra lab
                    inner join cat_tipos_muestras muestra on lab.id_tipos_muestras = muestra.id_tipos_muestras
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
    
    public static function buscarVicitsLabPruebas($idFormVicItsLab) {
        if (isset($idFormVicItsLab)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = "";
            $filtro = " AND id_vicits_laboratorio=" . $idFormVicItsLab . "";
            $sql = "select * from vicits_lab_prueba lab
                    inner join cat_prueba prueba on lab.id_prueba = prueba.id_prueba
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