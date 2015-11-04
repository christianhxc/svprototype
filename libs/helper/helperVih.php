<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once ('libs/caus/clsCaus.php');
require_once('libs/silab/ConfigurationSilab.php');
require_once('libs/silab/ConnectionSilab.php');
require_once('libs/helper/helperString.php');

class helperVih {
    
    public static function contarMuestrasSilab() {
        $sql = "select count(*) as total from vih_silab_temp";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }
    
    public static function contarFactoresSilab() {
        $sql = "select count(*) as total from vih_silab_temp_factor_riesgo";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }
    
    public static function traerDatosSilabNOSisvig() {
        $sql = "select temp.id_vih_silab_temp, temp.MUE_ID, temp.MUE_CODIGO_GLOBAL_ANIO, temp.MUE_CODIGO_GLOBAL_NUMERO, 
                temp.IND_PRIMER_NOMBRE, temp.IND_SEGUNDO_NOMBRE, temp.IND_PRIMER_APELLIDO, temp.IND_SEGUNDO_APELLIDO, 
                temp.IND_IDENTIFICADOR, temp.IND_IDENTIFICADOR_TIPO, temp.nombre_tipo, temp.IND_PROC_PROVINCIA, temp.IND_PROC_REGION, 
                temp.IND_PROC_DISTRITO, temp.IND_PROC_CORREGIMIENTO, temp.IND_DIRECCION, temp.IND_TELEFONO, temp.IND_FECHA_NACIMIENTO, 
                temp.IND_EDAD, temp.IND_TIPO_EDAD, temp.IND_SEXO, temp.MUE_FECHA_INICIO, temp.condicion, temp.MUE_PROC_INST_SALUD, 
                temp.cod_ref_minsa, temp.MUE_OTRO_ESTABLECIMIENTO_NOMBRE, temp.MUE_REFERIDA_POR, temp.MUE_FECHA_INGRESO_SISTEMA, 
                temp.MUE_SEMANA_EPI
                from vih_silab_temp temp
                where not exists
                (
                    select 
                    vih.id_tipo_identidad, vih.numero_identificacion
                    from vih_form vih
                    where vih.id_tipo_identidad = temp.IND_IDENTIFICADOR_TIPO and vih.numero_identificacion = temp.IND_IDENTIFICADOR
                )";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
    public static function traerDatosFactoresSilab(){
        $sql = "select vih.IND_IDENTIFICADOR, vih.IND_IDENTIFICADOR_TIPO,
                fac.id_vih_silab_temp_factor_riesgo, fac.MUE_ID, fac.VP, fac.TS, fac.UDI, fac.UDO, fac.no_preservativo, fac.exp_perinatal, 
                fac.desconocido, fac.embarazo, fac.donante, fac.ITS, fac.transfusion, fac.cont_vih
                from vih_silab_temp_factor_riesgo fac
                inner join vih_silab_temp vih on fac.MUE_ID = vih.MUE_ID";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
        
    public static function contarMuestrasSilabFactores() {
        $sql = "select count(*) as total from vih_silab_temp_factor_riesgo";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }
    
    public static function yaExistePersona($individuo) {
        $sql = "select count(*) from tbl_persona where tipo_identificacion='" . $individuo['id_tipo_identidad'] . "' and numero_identificacion='" . $individuo['numero_identificacion'] . "'";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }
    
    public static function yaExistePersonaSilab($individuo) {
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

    public static function yaExisteVih($vih) {
        //return 0;
        $sql = "select count(*) from vih_form where id_tipo_identidad=" . $vih['id_tipo_identidad'] . " and numero_identificacion='" . $vih['numero_identificacion'] . "'";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }
    
    public static function yaExisteFactorRiesgo($factor) {
        //return 0;
        $sql = "select count(*) from vih_factor_riesgo where id_vih_form=".$factor['id_vih_form']." and id_grupo_factor='".$factor['id_grupo_factor']."' and id_factor='".$factor['id_factor']."'";
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
        $individuo["id_tipo_identidad"] = (!isset($data["individuo"]["tipoId"]) ? NULL : $data["individuo"]["tipoId"]);
        $individuo["numero_identificacion"] = (!isset($data["individuo"]["identificador"]) ? NULL : $data["individuo"]["identificador"]);

        $individuo["primer_nombre"] = (!isset($data["individuo"]["primer_nombre"]) ? NULL : strtoupper($data["individuo"]["primer_nombre"]));
        $individuo["segundo_nombre"] = (!isset($data["individuo"]["segundo_nombre"]) ? NULL : strtoupper($data["individuo"]["segundo_nombre"]));
        $individuo["primer_apellido"] = (!isset($data["individuo"]["primer_apellido"]) ? NULL : strtoupper($data["individuo"]["primer_apellido"]));
        $individuo["segundo_apellido"] = (!isset($data["individuo"]["segundo_apellido"]) ? NULL : strtoupper($data["individuo"]["segundo_apellido"]));

        $individuo["fecha_nacimiento"] = (!isset($data["individuo"]["fecha_nacimiento"]) ? NULL : helperString::toDate($data["individuo"]["fecha_nacimiento"]));
        $individuo["fecha_parto"] = (!isset($data["individuo"]["fecha_parto"]) ? NULL : helperString::toDate($data["individuo"]["fecha_parto"]));

        $individuo["edad"] = (!isset($data["individuo"]["edad"]) ? NULL : $data["individuo"]["edad"]);
        $individuo["tipo_edad"] = (!isset($data["individuo"]["tipo_edad"]) ? '4' : $data["individuo"]["tipo_edad"]);
        $individuo["sexo"] = (!isset($data["individuo"]["sexo"]) ? '0' : $data["individuo"]["sexo"]);

        $individuo["id_region"] = $data["individuo"]["region"];
        $individuo["id_corregimiento"] = $data["individuo"]["corregimiento"];

        $individuo["dir_referencia"] = (!isset($data["individuo"]["lugar_poblado"]) ? NULL : strtoupper($data["individuo"]["lugar_poblado"]));
        $individuo["id_pais"] = "174";

        $individuo["nombre_responsable"] = (!isset($data["individuo"]["nombre_responsable"]) ? NULL : strtoupper($data["individuo"]["nombre_responsable"]));
        $individuo["tel_residencial "] = (!isset($data["individuo"]["telefono"]) ? NULL : strtoupper($data["individuo"]["telefono"]));
        
        $individuo["id_escolaridad "] = (!isset($data["individuo"]["escolaridad"]) ? NULL : strtoupper($data["individuo"]["escolaridad"]));
        $individuo["id_estado_civil "] = (!isset($data["individuo"]["estado_civil"]) ? NULL : strtoupper($data["individuo"]["estado_civil"]));
        $individuo["id_etnia "] = (!isset($data["individuo"]["etnia"]) ? NULL : strtoupper($data["individuo"]["etnia"]));
        $individuo["id_genero "] = (!isset($data["individuo"]["genero"]) ? NULL : strtoupper($data["individuo"]["genero"]));
        $individuo["id_ocupacion "] = (!isset($data["individuo"]["ocupacion_id"]) ? NULL : strtoupper($data["individuo"]["ocupacion_id"]));

        if (isset($data["individuo"]["region_diagnostico"])) $individuo["id_region_diagnostico"] = $data["individuo"]["region_diagnostico"];
        if (isset($data["individuo"]["corregimiento_diagnostico"])) $individuo["id_corregimiento_diagnostico"] = $data["individuo"]["corregimiento_diagnostico"];
        if (isset($data["individuo"]["lugar_poblado_diagnostico"])) $individuo["dir_referencia_diagnostico"] = (!isset($data["individuo"]["lugar_poblado_diagnostico"]) ? NULL : strtoupper($data["individuo"]["lugar_poblado_diagnostico"]));

        return $individuo;
    }
    
    public static function dataTblPersonaSilab($data) {
        // DATOS DEL INDIVIDUO
        $individuo = array();
        $individuo["tipo_identificacion"] = (!isset($data["IND_IDENTIFICADOR_TIPO"]) ? NULL : $data["IND_IDENTIFICADOR_TIPO"]);
        $individuo["numero_identificacion"] = (!isset($data["IND_IDENTIFICADOR"]) ? NULL : $data["IND_IDENTIFICADOR"]);
        $individuo["primer_nombre"] = (!isset($data["IND_PRIMER_NOMBRE"]) ? NULL : strtoupper($data["IND_PRIMER_NOMBRE"]));
        $individuo["segundo_nombre"] = (!isset($data["IND_SEGUNDO_NOMBRE"]) ? NULL : strtoupper($data["IND_SEGUNDO_NOMBRE"]));
        $individuo["primer_apellido"] = (!isset($data["IND_PRIMER_APELLIDO"]) ? NULL : strtoupper($data["IND_PRIMER_APELLIDO"]));
        $individuo["segundo_apellido"] = (!isset($data["IND_SEGUNDO_APELLIDO"]) ? NULL : strtoupper($data["IND_SEGUNDO_APELLIDO"]));
        $individuo["fecha_nacimiento"] = (!isset($data["IND_FECHA_NACIMIENTO"]) ? NULL : $data["IND_FECHA_NACIMIENTO"]);
        $individuo["edad"] = (!isset($data["IND_EDAD"]) ? NULL : $data["IND_EDAD"]);
        $individuo["tipo_edad"] = (!isset($data["IND_TIPO_EDAD"]) ? NULL : $data["IND_TIPO_EDAD"]);
        $individuo["sexo"] = (!isset($data["IND_SEXO"]) ? NULL : $data["IND_SEXO"]);
        $individuo["id_corregimiento"] = (!isset($data["IND_PROC_CORREGIMIENTO"]) ? 630 : $data["IND_PROC_CORREGIMIENTO"]);
        $individuo["dir_referencia"] = (!isset($data["IND_DIRECCION"]) ? NULL : $data["IND_DIRECCION"]);
        $individuo["tel_residencial"] = (!isset($data["IND_TELEFONO"]) ? NULL : $data["IND_TELEFONO"]);
        return $individuo;
    }
            
    public static function dataTblMuestraSilab($data) {
        // DATOS DEL INDIVIDUO
        $muestra = array();
        $muestra["mue_id"] = (!isset($data["MUE_ID"]) ? NULL : $data["MUE_ID"]);
        $muestra["codigo_global"] = $data["MUE_CODIGO_GLOBAL_ANIO"]." - ".str_pad((int)$data["MUE_CODIGO_GLOBAL_NUMERO"], 7, "0", STR_PAD_LEFT) ;
               
        $muestra["id_tipo_identidad"] = (!isset($data["IND_IDENTIFICADOR_TIPO"]) ? NULL : strtoupper($data["IND_IDENTIFICADOR_TIPO"]));
        $muestra["numero_identificacion"] = (!isset($data["IND_IDENTIFICADOR"]) ? NULL : strtoupper($data["IND_IDENTIFICADOR"]));
        $muestra["per_edad"] = (!isset($data["IND_EDAD"]) ? NULL : strtoupper($data["IND_EDAD"]));
        $muestra["per_tipo_edad"] = (!isset($data["IND_TIPO_EDAD"]) ? NULL : strtoupper($data["IND_TIPO_EDAD"]));
        $muestra["per_id_corregimiento"] = (!isset($data["IND_PROC_CORREGIMIENTO"]) ? 630 : $data["IND_PROC_CORREGIMIENTO"]);
        $muestra["per_localidad"] = (!isset($data["IND_DIRECCION"]) ? NULL : $data["IND_DIRECCION"]);
        $muestra["cond_vih"] = 1;
        $muestra["cond_fecha_vih"] = (!isset($data["MUE_FECHA_INICIO"]) ? NULL : $data["MUE_FECHA_INICIO"]);
        $muestra["cond_condicion_paciente"] = (!isset($data["condicion"]) ? NULL : $data["condicion"]);
        
        //id_un
        if($data["MUE_PROC_INST_SALUD"]!=0){
            $sql = "select id_un from cat_unidad_notificadora where cod_ref_minsa = '".$data["cod_ref_minsa"]."'";
            //echo $sql;
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $un = $conn->fetchOne();
            $conn->closeConn();
        }
        
        $muestra["id_un"] = (!isset($un["id_un"]) ? NULL : $un["id_un"]);
        $muestra["unidad_disponible"] = 1;
        if ($muestra["id_un"] == NULL)
            $muestra["unidad_disponible"] = 0;
        
        $muestra["otro_nombre_un"] = (!isset($data["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"]) ? NULL : $data["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"]);
        $muestra["nombre_registra"] = "SILAB-Lab. Gorgas";
        $muestra["nombre_notifica"] = (!isset($data["MUE_REFERIDA_POR"]) ? NULL : $data["MUE_REFERIDA_POR"]);
        $muestra["fecha_notificacion"] = (!isset($data["MUE_FECHA_INGRESO_SISTEMA"]) ? NULL : $data["MUE_FECHA_INGRESO_SISTEMA"]);
        $muestra["semana_epi "] = (!isset($data["MUE_SEMANA_EPI"]) ? NULL : $data["MUE_SEMANA_EPI"]);
        $muestra["anio "] = (!isset($data["MUE_CODIGO_GLOBAL_ANIO"]) ? NULL : $data["MUE_CODIGO_GLOBAL_ANIO"]);
        $fechaActual = date("Y-m-d H:i:s"); 
        $muestra["fecha_formulario "] = $fechaActual;      
        $muestra["silab "] = 1;
        $muestra["comp_per_preso "] = 0;
        $muestra["per_asegurado"] = 0;
        
        return $muestra;
    }
    
    public static function dataActualizarTblVihSilab($data) {
        // DATOS DEL INDIVIDUO
        $vih = array();
        $vih["comp_donante_sangre"] = ($data["donante"] == 0 ? 2 : 1);
        $vih["comp_uso_condon"] = ($data["no_preservativo"] == 0 ? 2 : 1);
        $vih["comp_trabajador_sexual"] = ($data["TS"] == 0 ? 2 : 1);
        $vih["comp_its_ultimo"] = ($data["ITS"] == 0 ? 2 : 1);
        $vih["comp_embarazada"] = ($data["embarazo"] == 0 ? 2 : 1);
        $vih["id_tipo_identidad"] = (!isset($data["IND_IDENTIFICADOR_TIPO"]) ? NULL : strtoupper($data["IND_IDENTIFICADOR_TIPO"]));
        $vih["numero_identificacion"] = (!isset($data["IND_IDENTIFICADOR"]) ? NULL : strtoupper($data["IND_IDENTIFICADOR"]));
        return $vih;
    }
    
    public static function insertVihFactorRiesgo($data) {
        $idVihForm = 0;
        $sqlValues = "";
        $sql = "select id_vih_form from vih_form 
            where id_tipo_identidad = ".$data["IND_IDENTIFICADOR_TIPO"]." and numero_identificacion = '".$data["IND_IDENTIFICADOR"]."'";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $dataForm = $conn->fetchOne();
        $conn->closeConn();
        $idVihForm = $dataForm["id_vih_form"];
        
        
        if ($data["VP"]>0){
            $factor = array();
            $factor["id_vih_form"] =  $idVihForm;
            $factor["id_grupo_factor"] =  6;
            $factor["id_factor"] =  15;
            $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
            if($yaExisteFactor==0)
                $sqlValues .= "($idVihForm, 6, 15)";
        }
        if ($data["UDI"]>0){
            $factor = array();
            $factor["id_vih_form"] =  $idVihForm;
            $factor["id_grupo_factor"] =  2;
            $factor["id_factor"] =  10;
            $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
            if($yaExisteFactor==0)
                $sqlValues .= "($idVihForm, 2, 10)";
        }
        if ($data["UDO"]>0){
            $factor = array();
            $factor["id_vih_form"] =  $idVihForm;
            $factor["id_grupo_factor"] =  6;
            $factor["id_factor"] =  12;
            $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
            if($yaExisteFactor==0)
                $sqlValues .= "($idVihForm, 6, 12)";
        }
        if ($data["transfusion"]>0){
            $factor = array();
            $factor["id_vih_form"] =  $idVihForm;
            $factor["id_grupo_factor"] =  2;
            $factor["id_factor"] =  8;
            $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
            if($yaExisteFactor==0)
                $sqlValues .= "($idVihForm, 2, 8)";
        }
        if ($data["exp_perinatal"]>0){
            $factor = array();
            $factor["id_vih_form"] =  $idVihForm;
            $factor["id_grupo_factor"] =  3;
            $factor["id_factor"] =  0;
            $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
            if($yaExisteFactor==0)
                $sqlValues .= "($idVihForm, 3, 0)";
        }
        if ($data["desconocido"]>0){
            $factor = array();
            $factor["id_vih_form"] =  $idVihForm;
            $factor["id_grupo_factor"] =  5;
            $factor["id_factor"] =  0;
            $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
            if($yaExisteFactor==0)
                $sqlValues .= "($idVihForm, 5, 0)";
        }
        
        $sql = "";
        if ($sqlValues != "" && $idVihForm != 0){
            $sql = "INSERT INTO vih_factor_riesgo (id_vih_form, id_grupo_factor, id_factor) VALUES ".$sqlValues.";";
            $sql = str_replace(")(", "),(", $sql);
        }
        return $sql;
    }

    public static function dataVihEnfermedades($data) {
        $vih = array();
        $vih["enfermedades"] = (!isset($data["enfermedades"]["globalEnfOportunistaRelacionados"]) ? NULL : explode("###",$data["enfermedades"]["globalEnfOportunistaRelacionados"]));
        return $vih;
    }

    public static function dataVihVigilancia($vih, $data){
        $vigilancia = $data["vigilancia"];
        $vih = array_merge($vih, $vigilancia);

        $vih["nacimiento_1"] = !isset($vih["nacimiento_1"]) ? NULL : helperString::toDate($vih["nacimiento_1"]);
        $vih["pcr1_fecha_1"] = !isset($vih["pcr1_fecha_1"]) ? NULL : helperString::toDate($vih["pcr1_fecha_1"]);
        $vih["pcr2_fecha_1"] = !isset($vih["pcr2_fecha_1"]) ? NULL : helperString::toDate($vih["pcr2_fecha_1"]);
        $vih["nacimiento_2"] = !isset($vih["nacimiento_2"]) ? NULL : helperString::toDate($vih["nacimiento_2"]);
        $vih["pcr1_fecha_2"] = !isset($vih["pcr1_fecha_2"]) ? NULL : helperString::toDate($vih["pcr1_fecha_2"]);
        $vih["pcr2_fecha_2"] = !isset($vih["pcr2_fecha_2"]) ? NULL : helperString::toDate($vih["pcr2_fecha_2"]);
        $vih["nacimiento_3"] = !isset($vih["nacimiento_3"]) ? NULL : helperString::toDate($vih["nacimiento_3"]);
        $vih["pcr1_fecha_3"] = !isset($vih["pcr1_fecha_3"]) ? NULL : helperString::toDate($vih["pcr1_fecha_3"]);
        $vih["pcr2_fecha_3"] = !isset($vih["pcr2_fecha_3"]) ? NULL : helperString::toDate($vih["pcr2_fecha_3"]);

        return $vih;
    }
    
    public static function dataVihFactorRiesgo($data) {
        $vih = array();
        $factorRiesgo = array();
        $vih["factores"] = (!isset($data["factores"]["globalFactorRiesgoRelacionados"]) ? 0 : explode("###",$data["factores"]["globalFactorRiesgoRelacionados"]));
        $max = sizeof($vih["factores"]);
        for ($i = 0; $i < $max; $i++) {
            $factor = explode("#-#",$vih["factores"][$i]);
            $factorRiesgo[$i] = $factor;
        }
        return $factorRiesgo;
    }
    
    public static function dataVihMuestrasSILAB($data) {
        $vih = array();
        $muestras = array();
        $muestra = array();
        $muestras= (!isset($data["muestras"]["globalMuestras"]) ? 0 : explode("},{",$data["muestras"]["globalMuestras"]));
        $max = sizeof($muestras);
        for ($i = 0; $i < $max; $i++) {
            $muestra = explode("#-#",$muestras[$i]);
            $muestra[0] = str_replace("{","",$muestra[0]);
            $vih[$i]["id_muestra"] = $muestra[0];
            $vih[$i]["codigo_global"] = $muestra[1];
            $vih[$i]["codigo_correlativo"] = $muestra[2];
            $vih[$i]["tipo_muestra"] = $muestra[3];
            $vih[$i]["fecha_inicio_sintoma"] = $muestra[4];
            $vih[$i]["fecha_toma"] = $muestra[5];
            $vih[$i]["fecha_recepcion"] = $muestra[6];
            $vih[$i]["unidad_notificadora"] = $muestra[7];
            $vih[$i]["estado_muestra"] = $muestra[8];
            $vih[$i]["resultado"] = $muestra[9];
            $vih[$i]["tipo1"] = $muestra[10];
            $vih[$i]["subtipo1"] = $muestra[11];
            $vih[$i]["tipo2"] = $muestra[12];
            $vih[$i]["subtipo2"] = $muestra[13];
            $muestra[14] = str_replace("}","",$muestra[14]);
            $vih[$i]["comentario_resultado"] = $muestra[14];
        }
        return $vih;
    }
    
    public static function dataVihPruebasSILAB($data) {
        $vih = array();
        $pruebas = array();
        $prueba = array();
        $pos = 0;
        $pruebas= (!isset($data["muestras"]["globalPruebas"]) ? 0 : explode("},{",$data["muestras"]["globalPruebas"]));
        $max = sizeof($pruebas);
        for ($i = 0; $i < $max; $i++) {
            $prueba = explode("#-#",$pruebas[$i]);
            $prueba[0] = str_replace("{","",$prueba[0]);
            if ($prueba[0]!="no"){
                $vih[$pos]["id_muestra"] = $prueba[0];
                $vih[$pos]["nombre_prueba"] = $prueba[1];
                $vih[$pos]["resultado_prueba"] = $prueba[2];
                $vih[$pos]["fecha_prueba"] = $prueba[3];
                $prueba[4] = str_replace("},","",$prueba[4]);
                $vih[$pos]["Comentario_prueba"] = $prueba[4];
                $pos++;
            }
        }
        return $vih;
    }

    public static function dataVihForm($data) {
        //print_r($data);exit;
        $vih = array();
        //Individuo
        $vih["id_tipo_identidad"] = (!isset($data["individuo"]["tipoId"]) ? NULL : $data["individuo"]["tipoId"]);
        $vih["numero_identificacion"] = (!isset($data["individuo"]["identificador"]) ? NULL : $data["individuo"]["identificador"]);
        $vih["per_asegurado"] = (!isset($data["individuo"]["asegurado"]) ? NULL : $data["individuo"]["asegurado"]);
        $vih["per_edad"] = (!isset($data["individuo"]["edad"]) ? NULL : $data["individuo"]["edad"]);
        $vih["per_tipo_edad"] = (!isset($data["individuo"]["tipo_edad"]) ? '4' : $data["individuo"]["tipo_edad"]);
        $vih["per_id_corregimiento"] = $data["individuo"]["corregimiento"];
        $vih["per_localidad"] = (!isset($data["individuo"]["lugar_poblado"]) ? NULL : $data["individuo"]["lugar_poblado"]);
        $vih["per_estado_civil"] = (!isset($data["individuo"]["estado_civil"]) ? NULL : $data["individuo"]["estado_civil"]);
        //Comportamiento
        $vih["comp_its_ultimo"] = (!isset($data["individuo"]["itsUltimo"]) ? NULL : $data["individuo"]["itsUltimo"]);
        $vih["comp_its_ulcerativa"] = (!isset($data["individuo"]["itsUlcerativa"]) ? NULL : $data["individuo"]["itsUlcerativa"]);
        $vih["comp_edad_inicio_sexual"] = (!isset($data["individuo"]["vida_sexual"]) ? NULL : $data["individuo"]["vida_sexual"]);
        //$vih["comp_uso_condon"] = (!isset($data["individuo"]["condonRel"]) ? NULL : $data["individuo"]["condonRel"]);
        $vih["comp_trabajador_sexual"] = (!isset($data["individuo"]["trabajoSexual"]) ? NULL : $data["individuo"]["trabajoSexual"]);
        $vih["comp_donante_sangre"] = (!isset($data["individuo"]["donante"]) ? NULL : $data["individuo"]["donante"]);
        if ($data["individuo"]["donante"] == '1') {
            $vih["comp_donante_fecha"] = (!isset($data["individuo"]["fecha_donacion"]) ? NULL : $data["individuo"]["fecha_donacion"]);
            $vih["comp_donante_instalacion"] = (!isset($data["individuo"]["instalacion_donante"]) ? NULL : $data["individuo"]["instalacion_donante"]);
        }
        else{
            $vih["comp_donante_fecha"] = NULL;
            $vih["comp_donante_instalacion"] = NULL;
        }
        $vih["comp_per_preso"] = (!isset($data["individuo"]["carcel"]) ? NULL : $data["individuo"]["carcel"]);
        $sexo = (!isset($data["individuo"]["sexo"]) ? '0' : $data["individuo"]["sexo"]);
        $vih["comp_embarazada"] = NULL;
        $vih["comp_fecha_parto"] = NULL;
        if ($sexo == 'F') {
            $vih["comp_embarazada"] = (!isset($data["individuo"]["embarazada"]) ? NULL : $data["individuo"]["embarazada"]);
            if ($vih["comp_embarazada"] == '1')
                $vih["comp_fecha_parto"] = (!isset($data["individuo"]["fecha_parto"]) ? NULL : helperString::toDate($data["individuo"]["fecha_parto"]));
        }   
        //Condicion del paciente
        $vih["cond_vih"] = 0;
        $vih["cond_sida"] = 0;
        $vih["razon_sida"] = 0;
        $condicion = (!isset($data["condicion"]["caso"]) ? NULL : $data["condicion"]["caso"]);
        if ($condicion != NULL){
            if ($condicion == 1){
                $vih["cond_vih"] = 0;
                $vih["cond_sida"] = 1;
                $vih["razon_sida"] = $data["condicion"]["razon_sida"];
            }
            else if ($condicion == 2){
                $vih["cond_vih"] = 1;
                $vih["cond_sida"] = 0;
            }
        }
        
        //$vih["cond_vih"] = (isset($data["condicion"]["check_vih"]) ? '1' : '0');
        $vih["cond_fecha_vih"] = (!isset($data["condicion"]["fecha_vih"]) ? NULL : helperString::toDate($data["condicion"]["fecha_vih"]));
        //$vih["cond_sida"] = (isset($data["condicion"]["check_sida"]) ? '1' : '0');
        $vih["cond_fecha_sida"] = (!isset($data["condicion"]["fecha_sida"]) ? NULL : helperString::toDate($data["condicion"]["fecha_sida"]));
        $vih["cond_sobrevida"] = (!isset($data["condicion"]["sobrevida"]) ? NULL : $data["condicion"]["sobrevida"]);
        $vih["cond_condicion_paciente"] = (!isset($data["condicion"]["condicion"]) ? NULL : $data["condicion"]["condicion"]);
        $vih["cond_fecha_defuncion"] = NULL;
        //$vih["cond_lugar_defuncion"] = NULL;
        $vih["cond_sobrevida_sida"] = NULL;
        if ($vih["cond_condicion_paciente"] != '1') {
            $vih["cond_fecha_defuncion"] = (!isset($data["condicion"]["fecha_defuncion"]) ? NULL : helperString::toDate($data["condicion"]["fecha_defuncion"]));
            $vih["cond_otro_defuncion"] = (!isset($data["condicion"]["lugar_defuncion"]))? NULL : $data["condicion"]["lugar_defuncion"];
            $vih["cond_id_un_defuncion"] = (isset($data["condicion"]["otro_defuncion"]) ? NULL : $data["condicion"]["id_un_defuncion"]);
            //$vih["cond_lugar_defuncion"] = (!isset($data["condicion"]["lugar_defuncion"]) ? NULL : $data["condicion"]["lugar_defuncion"]);
            if ($vih["cond_sida"]==1&&$vih["cond_fecha_sida"]!= NULL){
                $vih["cond_sobrevida_sida"] = (!isset($data["condicion"]["sobrevida_sida"]) ? NULL : $data["condicion"]["sobrevida_sida"]);
            }
        } 
        $vih["cond_lugar_diagnostico"] = (!isset($data["condicion"]["lugar_diagnostico"]) ? NULL : $data["condicion"]["lugar_diagnostico"]);
        //Notificacion
        if (isset($data["notificacion"]["unidad_disponible"])) {
            $vih["id_un"] = NULL;
            $vih["unidad_disponible"] = "0";
            $vih["otro_nombre_un"] = NULL;
        } else {
            $vih["id_un"] = $data["notificacion"]["id_un"];
            $vih["unidad_disponible"] = "1";
            $vih["otro_nombre_un"] = (!isset($data["notificacion"]["otra_unidad"]) ? NULL : $data["notificacion"]["otra_unidad"]);
        }
        $vih["nombre_notifica"] = (!isset($data["notificacion"]["nombreInvestigador"]) ? NULL : $data["notificacion"]["nombreInvestigador"]);
        $vih["fecha_notificacion"] = (!isset($data["notificacion"]["fecha_notificacion"]) ? NULL : helperString::toDate($data["notificacion"]["fecha_notificacion"]));
        
        $vih["semana_epi"] = (!isset($data["notificacion"]["semana_epi"]) ? NULL : $data["notificacion"]["semana_epi"]);
        $vih["anio"] = (!isset($data["notificacion"]["anio"]) ? NULL : $data["notificacion"]["anio"]);
        $vih["nombre_registra"] = (!isset($data["notificacion"]["nombreRegistra"]) ? NULL : $data["notificacion"]["nombreRegistra"]);
        $vih["fecha_formulario"] = (!isset($data["notificacion"]["fechaFormularioVih"]) ? NULL : helperString::toDate($data["notificacion"]["fechaFormularioVih"]));
        
        $vih["comp_emb_captada"] = (!isset($data["individuo"]["embarazada_captada"]) ? NULL : $data["individuo"]["embarazada_captada"]);
        $vih["comp_emb_1"] = (!isset($data["individuo"]["anio_emb1"]) ? NULL : $data["individuo"]["anio_emb1"]);
        $vih["comp_emb_2"] = (!isset($data["individuo"]["anio_emb2"]) ? NULL : $data["individuo"]["anio_emb2"]);
        $vih["comp_emb_3"] = (!isset($data["individuo"]["anio_emb3"]) ? NULL : $data["individuo"]["anio_emb3"]);
        $vih["cond_edad_vih"] = (!isset($data["condicion"]["edad_vih"]) ? NULL : $data["condicion"]["edad_vih"]);
        $vih["cond_edad_sida"] = (!isset($data["condicion"]["edad_sida"]) ? NULL : $data["condicion"]["edad_sida"]);
        $vih["cond_lugar_diagnostico_sida"] = (!isset($data["condicion"]["lugar_diagnostico_sida"]) ? NULL : $data["condicion"]["lugar_diagnostico_sida"]);
        return $vih;
    }
    
    public static function dataVihTarvForm($data) {
        //print_r($data);exit;
        $tarv = array();
        //Individuo
        $tarv["tarv_fec_ingreso"] = (!isset($data["tarv"]["fecha_ingreso"]) ? NULL : helperString::toDate($data["tarv"]["fecha_ingreso"]));
        $tarv["id_clinica_tarv"] = (!isset($data["tarv"]["clinica_tarv"]) ? NULL : $data["tarv"]["clinica_tarv"]);
        $tarv["tarv_fec_inicio"] = (!isset($data["tarv"]["fecha_inicio"]) ? NULL : helperString::toDate($data["tarv"]["fecha_inicio"]));
        $tarv["tarv_fec_cd4"] = (!isset($data["tarv"]["fecha_cd4"]) ? NULL : helperString::toDate($data["tarv"]["fecha_cd4"]));
        $tarv["tarv_res_cd4"] = (!isset($data["tarv"]["resultado_cd4"]) ? NULL : $data["tarv"]["resultado_cd4"]);
        $tarv["tarv_fec_cd4_350"] = (!isset($data["tarv"]["recuento1_cd4"]) ? NULL : helperString::toDate($data["tarv"]["recuento1_cd4"]));
        $tarv["tarv_res_cd4_350"] = (!isset($data["tarv"]["resultado_recuento1_cd4"]) ? NULL : $data["tarv"]["resultado_recuento1_cd4"]);
        $tarv["tarv_fec_cd4_200"] = (!isset($data["tarv"]["recuento2_cd4"]) ? NULL : helperString::toDate($data["tarv"]["recuento2_cd4"]));
        $tarv["tarv_res_cd4_200"] = (!isset($data["tarv"]["resultado_recuento2_cd4"]) ? NULL : $data["tarv"]["resultado_recuento2_cd4"]);
        $tarv["tarv_fec_carga_viral"] = (!isset($data["tarv"]["carga_viral"]) ? NULL : helperString::toDate($data["tarv"]["carga_viral"]));
        $tarv["tarv_res_carga_viral"] = (!isset($data["tarv"]["resultado_carga_viral"]) ? NULL : $data["tarv"]["resultado_carga_viral"]);
        return $tarv;
    }

    // Obtiene el listado de uceti
    public static function buscarVih($config) {
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
            $sql = "select * from vih_form vih 
                    inner join tbl_persona per on per.tipo_identificacion = vih.id_tipo_identidad and per.numero_identificacion = vih.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = vih.id_un
                    left join cat_corregimiento cor on cor.id_corregimiento = per.id_corregimiento
                    left join cat_distrito dis on dis.id_distrito = cor.id_distrito
                    left join cat_region_salud reg on reg.id_region = dis.id_region ";
        else {
            if ($config["filtro"] != "") {
                $filtro1 = " AND (un.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                        " OR reg.nombre_region LIKE '%" . $config["filtro"] . "%'" .
                        " OR vih.semana_epi LIKE '%" . $config["filtro"] . "%'" .
                        " OR vih.anio LIKE '%" . $config["filtro"] . "%'" .
                        " OR vih.numero_identificacion LIKE '%" . $config["filtro"] . "%')";
            } else if (isset($config["id_tipo_identidad"]) && isset($config["numero_identificacion"])) {
                $filtro2 = " AND vih.id_tipo_identidad='" . $config["id_tipo_identidad"] . "'" .
                        " AND vih.numero_identificacion='" . $config["numero_identificacion"] . "'";
                $read = true;
            }
            $sql = "select * from vih_form vih 
                    inner join tbl_persona per on per.tipo_identificacion = vih.id_tipo_identidad and per.numero_identificacion = vih.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = vih.id_un
                    left join cat_corregimiento cor on cor.id_corregimiento = per.id_corregimiento
                    left join cat_distrito dis on dis.id_distrito = cor.id_distrito
                    left join cat_region_salud reg on reg.id_region = dis.id_region WHERE 1 "
                    . $filtro1 . $filtro2 . ' order by id_vih_form desc';
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

    public static function buscarVihCantidad($config) {
        $conn = new Connection();
        $conn->initConn();
        $filtro1 = "";
        if ($config["filtro"] != "") {
            $filtro1 = " AND (un.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                        " OR reg.nombre_region LIKE '%" . $config["filtro"] . "%'" .
                        " OR vih.semana_epi LIKE '%" . $config["filtro"] . "%'" .
                        " OR vih.anio LIKE '%" . $config["filtro"] . "%'" .
                        " OR vih.numero_identificacion LIKE '%" . $config["filtro"] . "%')";
        }
        $sql = "select count(*) as total from vih_form vih 
                    inner join tbl_persona per on per.tipo_identificacion = vih.id_tipo_identidad and per.numero_identificacion = vih.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = vih.id_un
                    left join cat_corregimiento cor on cor.id_corregimiento = per.id_corregimiento
                    left join cat_distrito dis on dis.id_distrito = cor.id_distrito
                    left join cat_region_salud reg on reg.id_region = dis.id_region WHERE 1 ". $filtro1;
        //echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }

    public static function buscarVihEnfermedad($formVih) {
        if (isset($formVih)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = "";
            $filtro = " AND id_vih_form=" . $formVih . "";
            $sql = "select * from vih_enfermedad_oportunista vih_eo
                    inner join cat_evento eve on eve.id_evento = vih_eo.id_evento
                    WHERE 1 ". $filtro;
            //echo $sql;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;
    }
    
    public static function buscarVihFactores($formVih) {
        if (isset($formVih)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = "";
            $filtro = " AND id_vih_form=" . $formVih . "";
            $sql = "select vih_fr.id_grupo_factor, gfr.grupo_factor_nombre, vih_fr.id_factor, cat_factor_riesgo.factor_nombre
                    from vih_factor_riesgo vih_fr
                    inner join cat_grupo_factor_riesgo gfr on gfr.id_grupo_factor = vih_fr.id_grupo_factor
                    left join cat_factor_riesgo ON cat_factor_riesgo.id_factor = vih_fr.id_factor
                    WHERE 1 ". $filtro;
            //echo $sql;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;
    }
    
    public static function buscarVihMuestrasSilab($formVih) {
        if (isset($formVih)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = " AND id_vih_form=" . $formVih . "";
            $sql = "select * from vih_muestra_silab 
                    WHERE 1 ". $filtro;
            //echo $sql;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;
    }
    
    public static function buscarVihPruebasSilab($idMuestra) {
        if (isset($idMuestra)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = " and id_muestra = ".$idMuestra;
            $sql = "select * from vih_muestra_prueba_silab 
                    WHERE 1 ". $filtro;
            //echo $sql;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;
    }
    
    public static function buscarVihTarv($formVih) {
        if (isset($formVih)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = "";
            $filtro = " AND id_vih_form=" . $formVih . "";
            $sql = "select * from vih_tarv
                    inner join cat_clinica_tarv tarv on tarv.id_clinica_tarv = vih_tarv.id_clinica_tarv
                    WHERE 1 ". $filtro;
            //echo $sql;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;
    }
    
    public static function dataTblPersonaEpiInfo($persona,$data) {
        // DATOS DEL INDIVIDUO
        $individuo = array();
        $individuo["tipo_identificacion"] = (!isset($persona['id_tipo_identidad']) ? NULL : $persona['id_tipo_identidad']);
        $individuo["numero_identificacion"] = (!isset($persona['numero_identificacion']) ? NULL : $persona['numero_identificacion']);
        $nombres = explode(" ",$data[3]);
        $nombre2 = !isset($nombres[1])? "":$nombres[1];
        $nombre2.= !isset($nombres[2])? "":" ".$nombres[2];
        $nombre2.= !isset($nombres[3])? "":" ".$nombres[3];
        $apellidos = explode(" ",$data[4]);
        $apellido2 = !isset($apellidos[1])? "":$apellidos[1];
        $apellido2.= !isset($apellidos[2])? "":" ".$apellidos[2];
        $apellido2.= !isset($apellidos[3])? "":" ".$apellidos[3];
        $individuo["primer_nombre"] = (!isset($nombres[0]) ? NULL : strtoupper($nombres[0]));
        $individuo["segundo_nombre"] = $nombre2;
        $individuo["primer_apellido"] = (!isset($apellidos[0]) ? NULL : strtoupper($apellidos[0]));
        $individuo["segundo_apellido"] = $apellido2;
        $individuo["fecha_nacimiento"] = null;
        $individuo["edad"] = $data[5];
        $individuo["tipo_edad"] = 3;
        $individuo["sexo"] = $data[6];
        if(is_numeric($data[7])){
            if ($data[7]<=6)
                $individuo["id_escolaridad"] = ($data[7]==6) ? 2:1;
            else if ($data[7]<=12)
                $individuo["id_escolaridad"] = ($data[7]==12) ? 4:3;
            else if ($data[7]<=16)
                $individuo["id_escolaridad"] = ($data[7]==16) ? 6:5;           
        }
        $individuo["id_genero"] = 9;
        if($data[12]!="N.E."){
            if ($data[12]=='HETEROSEX/CON'||$data[12]=='HETEROSEXUAL'||$data[12]=='HETEROSEX/PRO'||$data[12]=='HETEROSEX/PRO/UD')
                $individuo["id_genero"] = 1;
            if ($data[12]=='HOMOSEX/CON'||$data[12]=='HOMOSEXUAL'||$data[12]=='HOMOSEX/PRO')
                $individuo["id_genero"] = 6;
            if ($data[12]=='BISEXUAL')
                $individuo["id_genero"] = 7;
        }
        $individuo["id_corregimiento"] = 630;
        if($data[9]!=""){
            $sql = "select id_region from cat_region_salud where nombre_region LIKE '%".$data[9]."%'";
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $region = $conn->fetchOne();
            $conn->closeConn();
            if(isset($region["id_region"])){
                $sql = "select id_distrito from cat_distrito where id_region = ".$region["id_region"]." and nombre_distrito LIKE '%".$data[10]."%'";
                $conn = new Connection();
                $conn->initConn();
                $conn->prepare($sql);
                $conn->execute();
                $distrito = $conn->fetchOne();
                $conn->closeConn();
                if(isset($distrito["id_distrito"])){
                    $sql = "select id_corregimiento, nombre_corregimiento from cat_corregimiento where id_distrito = ".$distrito["id_distrito"]." and nombre_corregimiento LIKE '%".$data[11]."%'";
                    $conn = new Connection();
                    $conn->initConn();
                    $conn->prepare($sql);
                    $conn->execute();
                    $corre = $conn->fetchOne();
                    $conn->closeConn();
                    if(isset($corre["id_corregimiento"])){
                        $idCorr = $corre["id_corregimiento"];
                    }
                }
            }
        }
        if ($idCorr>0&&$idCorr<=630)
            $individuo["id_corregimiento"] = $idCorr;
        
        $individuo["dir_referencia"] = null;
        $individuo["tel_residencial"] = null;
        return $individuo;
    }
    
    public static function dataVihTblEpiInfo($vih,$persona,$data) {
        // DATOS DEL formulario de VIH/SIDA
        $formVih = array();
        $formVih["id_tipo_identidad"] = (!isset($vih["id_tipo_identidad"]) ? NULL : $vih["id_tipo_identidad"]);
        $formVih["numero_identificacion"] = (!isset($vih["numero_identificacion"]) ? NULL : $vih["numero_identificacion"]);
        $formVih["per_edad"] = $persona["edad"];
        $formVih["per_tipo_edad"] = $persona["tipo_edad"];
        $formVih["per_id_corregimiento"] = $persona["id_corregimiento"];
        $formVih["per_localidad"] = null;
        $formVih["per_asegurado"] = 0;
        $formVih["cond_condicion_paciente"] = ($data[16]=="Missing") ? 1 : 2;
        $formVih["cond_fecha_defuncion"] = ($data[16]=="Missing") ? null : helperString::toDateIngles($data[16]);        
        $formVih["fecha_notificacion"] = helperString::toDateIngles($data[17]);        
        $formVih["cond_vih"] = 1;
        if ($data[18]=="SIDA"){
            $formVih["cond_sida"] = 1;
            $formVih["cond_fecha_sida"] = $data[22]!="Missing" ? helperString::toDateIngles($data[22]):null;
            if($formVih["cond_fecha_defuncion"] != null && $formVih["cond_fecha_sida"] != null){
                $dias = (strtotime($formVih["cond_fecha_defuncion"]) - strtotime($formVih["cond_fecha_sida"])) / 86400;
                $formVih["cond_sobrevida_sida"] = $dias." dias";
            }
        }
        else 
            $formVih["cond_fecha_vih"] = $data[22]!="Missing" ? helperString::toDateIngles($data[22]):null;
        $datosEpi = helperString::calcularSemanaEpi($data[22]);
        $formVih["semana_epi"] = $datosEpi["semana"];
        $formVih["anio"] = $datosEpi["anio"];     
        $formVih["comp_per_preso"] = 0;
        if($data[29]!="Missing"||$data[47]==5)
            $formVih["comp_per_preso"] =  1;
        $formVih["comp_donante_sangre"] = 0;
        if($data[30]!="Missing"||$data[47]==2)
            $formVih["comp_donante_sangre"] =  1;        
        $fechaActual = date("m/d/Y"); 
        $formVih["fecha_formulario "] = helperString::toDateIngles($fechaActual);           
        $formVih["id_un"] == NULL;
        $formVih["unidad_disponible"] = 0;        
        $formVih["nombre_registra"] = "Base de datos Anterior - EpiInfo";
        $formVih["comp_embarazada"] = $data[49]!="Missing" ? 1:2;
        $formVih["epiInfo"] = 1;
        $formVih["comp_trabajador_sexual"] = ($data[47]==4) ? 1 : 0;
        $formVih["comp_its_ultimo"] = ($data[47]==6) ? 1 : 0;
                
        return $formVih;
    }
    
    public static function insertVihFactorRiesgoEpiInfo($idVihForm, $data) {
        
        $sqlValues = "";
        if ($data[26]==1){
            $factor = array();
            $factor["id_vih_form"] =  $idVihForm;
            $factor["id_grupo_factor"] =  6;
            $factor["id_factor"] =  16;
            $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
            if($yaExisteFactor==0)
                $sqlValues .= "($idVihForm, 6, 16)";
        }
        if ($data[27]==1){
            $factor = array();
            $factor["id_vih_form"] =  $idVihForm;
            $factor["id_grupo_factor"] =  4;
            $factor["id_factor"] =  0;
            $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
            if($yaExisteFactor==0)
                $sqlValues .= "($idVihForm, 4, 0)";
        }
        if ($data[28]==1){
            $factor = array();
            $factor["id_vih_form"] =  $idVihForm;
            $factor["id_grupo_factor"] =  6;
            $factor["id_factor"] =  14;
            $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
            if($yaExisteFactor==0)
                $sqlValues .= "($idVihForm, 6, 14)";
        }
        if ($data[32]=='TRANSMISION SEXUAL'){
            if ($data[34]==1){
                $factor = array();
                $factor["id_vih_form"] =  $idVihForm;
                $factor["id_grupo_factor"] =  1;
                $factor["id_factor"] =  1;
                $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
                if($yaExisteFactor==0)
                    $sqlValues .= "($idVihForm, 1, 1)";
            }
            else if ($data[34]==2){
                $factor = array();
                $factor["id_vih_form"] =  $idVihForm;
                $factor["id_grupo_factor"] =  1;
                $factor["id_factor"] =  2;
                $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
                if($yaExisteFactor==0)
                    $sqlValues .= "($idVihForm, 1, 2)";
            }
            else if ($data[34]==3){
                $factor = array();
                $factor["id_vih_form"] =  $idVihForm;
                $factor["id_grupo_factor"] =  1;
                $factor["id_factor"] =  3;
                $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
                if($yaExisteFactor==0)
                    $sqlValues .= "($idVihForm, 1, 3)";
            }
        }
        if ($data[37]==1){
            $factor = array();
            $factor["id_vih_form"] =  $idVihForm;
            $factor["id_grupo_factor"] =  3;
            $factor["id_factor"] =  0;
            $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
            if($yaExisteFactor==0)
                $sqlValues .= "($idVihForm, 3, 0)";
        }
        if ($data[38]==1){
            $factor = array();
            $factor["id_vih_form"] =  $idVihForm;
            $factor["id_grupo_factor"] =  6;
            $factor["id_factor"] =  15;
            $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
            if($yaExisteFactor==0)
                $sqlValues .= "($idVihForm, 6, 15)";
        }
        if ($data[40]==1){
            $factor = array();
            $factor["id_vih_form"] =  $idVihForm;
            $factor["id_grupo_factor"] =  2;
            $factor["id_factor"] =  10;
            $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
            if($yaExisteFactor==0)
                $sqlValues .= "($idVihForm, 2, 10)";
        }
        if ($data[40]==2||$data[42]==1){
            $factor = array();
            $factor["id_vih_form"] =  $idVihForm;
            $factor["id_grupo_factor"] =  6;
            $factor["id_factor"] =  12;
            $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
            if($yaExisteFactor==0)
                $sqlValues .= "($idVihForm, 6, 12)";
        }
        if ($data[41]==1){
            $factor = array();
            $factor["id_vih_form"] =  $idVihForm;
            $factor["id_grupo_factor"] =  2;
            $factor["id_factor"] =  8;
            $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
            if($yaExisteFactor==0)
                $sqlValues .= "($idVihForm, 2, 8)";
        }
        if ($data[48]==1){
            $factor = array();
            $factor["id_vih_form"] =  $idVihForm;
            $factor["id_grupo_factor"] =  2;
            $factor["id_factor"] =  9;
            $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
            if($yaExisteFactor==0)
                $sqlValues .= "($idVihForm, 2, 9)";
        }
        else if ($data[48]==2){
            $factor = array();
            $factor["id_vih_form"] =  $idVihForm;
            $factor["id_grupo_factor"] =  2;
            $factor["id_factor"] =  0;
            $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
            if($yaExisteFactor==0)
                $sqlValues .= "($idVihForm, 2, 0)";
        }
        else if ($data[48]==3){
            $factor = array();
            $factor["id_vih_form"] =  $idVihForm;
            $factor["id_grupo_factor"] =  2;
            $factor["id_factor"] =  13;
            $yaExisteFactor = helperVih::yaExisteFactorRiesgo($factor);
            if($yaExisteFactor==0)
                $sqlValues .= "($idVihForm, 2, 13)";
        }
        
        $sql = "";
        if ($sqlValues != "" && $idVihForm != 0){
            $sql = "INSERT INTO vih_factor_riesgo (id_vih_form, id_grupo_factor, id_factor) VALUES ".$sqlValues.";";
            $sql = str_replace(")(", "),(", $sql);
        }
        return $sql;
    }
    
}