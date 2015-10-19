<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once ('libs/caus/clsCaus.php');
require_once('libs/silab/ConfigurationSilab.php');
require_once('libs/silab/ConnectionSilab.php');
require_once('libs/helper/helperString.php');

class helperVigmor {
    
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

    public static function yaExisteVigmor($vigmor) {
        //print_r($vigmor);exit;
        $sql = "select count(*) from vm_form where tipo_identificacion = '" . $vigmor['tipo_identificacion'] . "' and numero_identificacion='" . $vigmor['numero_identificacion'] . "'";
        //echo $sql; exit;
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
        if (isset($data["individuo"]["fecha_nacimiento"])){
            if ($data["individuo"]["fecha_nacimiento"]!=null && $data["individuo"]["fecha_nacimiento"]!='')
                $individuo["fecha_nacimiento"] = helperString::toDate($data["individuo"]["fecha_nacimiento"]);
        }
        $individuo["edad"] = (!isset($data["individuo"]["edad"]) ? NULL : $data["individuo"]["edad"]);
        $individuo["tipo_edad"] = (!isset($data["individuo"]["tipo_edad"]) ? '4' : $data["individuo"]["tipo_edad"]);
        $individuo["sexo"] = (!isset($data["individuo"]["sexo"]) ? '0' : $data["individuo"]["sexo"]);
        $individuo["id_pais"] = (!isset($data["individuo"]["pais"]) ? NULL : strtoupper($data["individuo"]["pais"]));
        $individuo["id_region"] = 1;
        $individuo["id_corregimiento"] = 630;
        if ($individuo["id_pais"] == 174)  {     
            $individuo["id_region"] = (!isset($data["individuo"]["region"]) ? 1 : $data["individuo"]["region"]);
            $individuo["id_corregimiento"] = (!isset($data["individuo"]["corregimiento"]) ? 630 : $data["individuo"]["corregimiento"]);
        }
        $individuo["dir_referencia"] = (!isset($data["individuo"]["lugar_poblado"]) ? NULL : strtoupper($data["individuo"]["lugar_poblado"]));
        $individuo["tel_residencial"] = (!isset($data["individuo"]["telefono"]) ? NULL : strtoupper($data["individuo"]["telefono"]));  
        $individuo["dir_trabajo"] = (!isset($data["individuo"]["dir_laboral"]) ? NULL : strtoupper($data["individuo"]["dir_laboral"]));
        $individuo["tel_trabajo"] = (!isset($data["individuo"]["tel_laboral"]) ? NULL : strtoupper($data["individuo"]["tel_laboral"]));  
        return $individuo;
    }

    public static function dataVigmorForm($data) {
        //print_r($data);exit;
        $vigmor = array();
        //Individuo
        $vigmor["tipo_identificacion"] = (!isset($data["individuo"]["tipoId"]) ? NULL : $data["individuo"]["tipoId"]);
        $vigmor["numero_identificacion"] = (!isset($data["individuo"]["identificador"]) ? NULL : $data["individuo"]["identificador"]);
        if ($vigmor["tipo_identificacion"] == 1)
            $vigmor["numero_identificacion"] = $data["individuo"]["identificador1"]."-".$data["individuo"]["identificador2"]."-".$data["individuo"]["identificador3"];
        $vigmor["per_edad"] = (!isset($data["individuo"]["edad"]) ? NULL : $data["individuo"]["edad"]);
        $vigmor["per_tipo_edad"] = (!isset($data["individuo"]["tipo_edad"]) ? '4' : $data["individuo"]["tipo_edad"]);
        $vigmor["per_id_region"] = (!isset($data["individuo"]["region"]) ? NULL : $data["individuo"]["region"]);
        $vigmor["per_id_corregimiento"] = $data["individuo"]["corregimiento"];
        $vigmor["per_localidad"] = (!isset($data["individuo"]["lugar_poblado"]) ? NULL : $data["individuo"]["lugar_poblado"]);
        //Datos de la defuncion
        $vigmor["fecha_hospitalizacion"] = (!isset($data["defuncion"]["fecha_hospitalizacion"]) ? NULL : helperString::toDate($data["defuncion"]["fecha_hospitalizacion"]));
        $vigmor["fecha_defuncion"] = (!isset($data["defuncion"]["fecha_defuncion"]) ? NULL : helperString::toDate($data["defuncion"]["fecha_defuncion"]));
        $vigmor["semana_epi"] = (!isset($data["defuncion"]["semana_epi"]) ? NULL : $data["defuncion"]["semana_epi"]);
        $vigmor["anio"] = (!isset($data["defuncion"]["anio_epi"]) ? NULL : $data["defuncion"]["anio_epi"]);
        $varHoraCompleta = null;
        $varHora = (!isset($data["defuncion"]["def_hora"]) ? NULL : $data["defuncion"]["def_hora"]);
        if ($varHora!=NULL){
            if ($data["defuncion"]["tipo_hora"] == 0)
                $varHora = $varHora+12;
            $varMinutos = (!isset($data["defuncion"]["def_minutos"]) ? NULL : $data["defuncion"]["def_minutos"]);
            if ($varHora!="" && $varMinutos!="")
                $varHoraCompleta = $varHora.":".$varMinutos;
            $vigmor["hora_defuncion"] = $varHoraCompleta; 
        }
        $vigmor["fecha_morgue"] = (!isset($data["defuncion"]["fecha_morgue"]) ? NULL : helperString::toDate($data["defuncion"]["fecha_morgue"]));
        $vigmor["id_diagnostico1"] = (!isset($data["defuncion"]["id_evento1"]) ? NULL : $data["defuncion"]["id_evento1"]);
        $vigmor["id_diagnostico2"] = (!isset($data["defuncion"]["id_evento2"]) ? NULL : $data["defuncion"]["id_evento2"]);
        $vigmor["id_diagnostico3"] = (!isset($data["defuncion"]["id_evento3"]) ? NULL : $data["defuncion"]["id_evento3"]);
        $vigmor["id_diagnostico_final"] = (!isset($data["defuncion"]["id_eve_cierre1"]) ? NULL : $data["defuncion"]["id_eve_cierre1"]);
        $vigmor["id_diagnostico_final2"] = (!isset($data["defuncion"]["id_eve_cierre2"]) ? NULL : $data["defuncion"]["id_eve_cierre2"]);
        $vigmor["estado_diag1"] = (!isset($data["defuncion"]["estado_eve1"]) ? NULL : $data["defuncion"]["estado_eve1"]);
        $vigmor["estado_diag2"] = (!isset($data["defuncion"]["estado_eve2"]) ? NULL : $data["defuncion"]["estado_eve2"]);
        $vigmor["estado_diag3"] = (!isset($data["defuncion"]["estado_eve3"]) ? NULL : $data["defuncion"]["estado_eve3"]);
        $vigmor["estado_diag_final"] = (!isset($data["defuncion"]["estado_eve_cierre1"]) ? NULL : $data["defuncion"]["estado_eve_cierre1"]);
        $vigmor["estado_diag_final2"] = (!isset($data["defuncion"]["estado_eve_cierre2"]) ? NULL : $data["defuncion"]["estado_eve_cierre2"]);
        //Datos notificacion
        $vigmor["id_un"] = $data["notificacion"]["id_un"];
        $vigmor["id_servicio"] = (!isset($data["notificacion"]["servicio"]) ? NULL : $data["notificacion"]["servicio"]);
        $vigmor["nombre_sala"] = (!isset($data["notificacion"]["sala"]) ? NULL : $data["notificacion"]["sala"]);
        $vigmor["persona_notifica"] = (!isset($data["notificacion"]["nombreInvestigador"]) ? NULL : $data["notificacion"]["nombreInvestigador"]);
        $vigmor["id_cargo"] = (!isset($data["notificacion"]["cargo"]) ? NULL : $data["notificacion"]["cargo"]);
        $vigmor["telefono"] = (!isset($data["notificacion"]["telefonoInvestigador"]) ? NULL : $data["notificacion"]["telefonoInvestigador"]);
        $vigmor["nombre_registra"] = (!isset($data["notificacion"]["nombreRegistra"]) ? NULL : $data["notificacion"]["nombreRegistra"]);
        $vigmor["fecha_notificacion"] = (!isset($data["notificacion"]["fecha_notificacion"]) ? NULL : helperString::toDate($data["notificacion"]["fecha_notificacion"]));
        if(isset($data["notificacion"]["fechaFormularioVigmor"])){
            if ($data["notificacion"]["fechaFormularioVigmor"]!= null && $data["notificacion"]["fechaFormularioVigmor"]!='')
                $vigmor["fecha_formulario"] = helperString::toDate($data["notificacion"]["fechaFormularioVigmor"]);
        }
        if(isset($data["notificacion"]["form_hora"])){
            if ($data["notificacion"]["form_hora"]!=null && $data["notificacion"]["form_hora"]!=''){
                $varHoraCompleta = null;
                $varHora = (!isset($data["notificacion"]["form_hora"]) ? NULL : $data["notificacion"]["form_hora"]);
                if ($data["notificacion"]["form_tipo_hora"] == 0)
                    $varHora = $varHora+12;
                $varMinutos = (!isset($data["notificacion"]["form_minutos"]) ? NULL : $data["notificacion"]["form_minutos"]);
                if ($varHora!="" && $varMinutos!="")
                    $varHoraCompleta = $varHora.":".$varMinutos;
                $vigmor["hora_formulario"] = $varHoraCompleta;
            }
        }
        return $vigmor;
    }
    
    // Obtiene el listado de uceti
    public static function buscarVigmor($config) {
        $conn = new Connection();
        $conn->initConn();
        $flag = 0;
        $filtro1 = "";
        $filtro2 = "";
        $filtroUN = "";
        $read = false;
        foreach ($config as $valor) {
            if ($valor != "")
                $flag++;
        }
        
        $lista = clsCaus::obtenerUbicaciones(ConfigurationCAUS::Localidad);
        if (is_array($lista)) {
            $temporal = implode("','", $lista);
            if ($temporal != "") {
                $filtroUN .= " and un.id_un in ('" . $temporal . "') ";
            }
        }
        if ($filtroUN===""){
            $corregimientos = clsCaus::obtenerCorUsuario();
            if ($corregimientos != "")
            $filtroUN = " and un.id_corregimiento in ( ".$corregimientos." ) ";
        }
        
        if ($flag == 0)
            $sql = "select vigmor.*, un.*, cor.*, dis.*, reg.*,
                    eve1.id_evento as id_eve1, eve1.cie_10_1 as cie1, eve1.nombre_evento as nom_eve1,
                    eve2.id_evento as id_eve2, eve2.cie_10_1 as cie2, eve2.nombre_evento as nom_eve2,
                    eve3.id_evento as id_eve3, eve3.cie_10_1 as cie3, eve3.nombre_evento as nom_eve3,
                    eve_fin1.id_evento as id_eve_fin1, eve_fin1.cie_10_1 as cie_fin1, eve_fin1.nombre_evento as nom_eve_fin1,
                    eve_fin2.id_evento as id_eve_fin2, eve_fin2.cie_10_1 as cie_fin2, eve_fin2.nombre_evento as nom_eve_fin2
                    from vm_form vigmor 
                    inner join tbl_persona per on per.tipo_identificacion = vigmor.tipo_identificacion and per.numero_identificacion = vigmor.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = vigmor.id_un
                    left join cat_corregimiento cor on cor.id_corregimiento = per.id_corregimiento
                    left join cat_distrito dis on dis.id_distrito = cor.id_distrito
                    left join cat_region_salud reg on reg.id_region = dis.id_region
                    left join cat_evento eve1 on vigmor.id_diagnostico1 = eve1.id_evento
                    left join cat_evento eve2 on vigmor.id_diagnostico2 = eve2.id_evento
                    left join cat_evento eve3 on vigmor.id_diagnostico3 = eve3.id_evento
                    left join cat_evento eve_fin1 on vigmor.id_diagnostico_final = eve_fin1.id_evento
                    left join cat_evento eve_fin2 on vigmor.id_diagnostico_final2 = eve_fin2.id_evento where 1=1 ".$filtroUN;
        else {
            if ($config["filtro"] != "") {
                $filtro1 = " AND (un.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                        " OR reg.nombre_region LIKE '%" . $config["filtro"] . "%'" .
                        " OR vigmor.semana_epi LIKE '%" . $config["filtro"] . "%'" .
                        " OR vigmor.anio LIKE '%" . $config["filtro"] . "%'" .
                        " OR eve1.cie_10_1 LIKE '%" . $config["filtro"] . "%'" .
                        " OR eve1.nombre_evento LIKE '%" . $config["filtro"] . "%'". 
                        " OR vigmor.numero_identificacion LIKE '%" . $config["filtro"] . "%')";
            } else if (isset($config["tipo_identificacion"]) && isset($config["numero_identificacion"])) {
                $filtro2 = " AND vigmor.tipo_identificacion='" . $config["tipo_identificacion"] . "'" .
                        " AND vigmor.numero_identificacion='" . $config["numero_identificacion"] . "'";
                $read = true;
            }
            $sql = "select vigmor.*, un.*, cor.*, dis.*, reg.*,
                    eve1.id_evento as id_eve1, eve1.cie_10_1 as cie1, eve1.nombre_evento as nom_eve1,
                    eve2.id_evento as id_eve2, eve2.cie_10_1 as cie2, eve2.nombre_evento as nom_eve2,
                    eve3.id_evento as id_eve3, eve3.cie_10_1 as cie3, eve3.nombre_evento as nom_eve3,
                    eve_fin1.id_evento as id_eve_fin1, eve_fin1.cie_10_1 as cie_fin1, eve_fin1.nombre_evento as nom_eve_fin1,
                    eve_fin2.id_evento as id_eve_fin2, eve_fin2.cie_10_1 as cie_fin2, eve_fin2.nombre_evento as nom_eve_fin2
                    from vm_form vigmor 
                    inner join tbl_persona per on per.tipo_identificacion = vigmor.tipo_identificacion and per.numero_identificacion = vigmor.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = vigmor.id_un
                    left join cat_corregimiento cor on cor.id_corregimiento = per.id_corregimiento
                    left join cat_distrito dis on dis.id_distrito = cor.id_distrito
                    left join cat_region_salud reg on reg.id_region = dis.id_region
                    left join cat_evento eve1 on vigmor.id_diagnostico1 = eve1.id_evento
                    left join cat_evento eve2 on vigmor.id_diagnostico2 = eve2.id_evento
                    left join cat_evento eve3 on vigmor.id_diagnostico3 = eve3.id_evento
                    left join cat_evento eve_fin1 on vigmor.id_diagnostico_final = eve_fin1.id_evento
                    left join cat_evento eve_fin2 on vigmor.id_diagnostico_final2 = eve_fin2.id_evento WHERE 1 "
                    . $filtro1 . $filtro2 . $filtroUN . ' order by id_form desc';
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

    public static function buscarVigmorCantidad($config) {
        $conn = new Connection();
        $conn->initConn();
        $filtro1 = "";
        $filtroUN = "";
        
        $lista = clsCaus::obtenerUbicaciones(ConfigurationCAUS::Localidad);
        if (is_array($lista)) {
            $temporal = implode("','", $lista);
            if ($temporal != "") {
                $filtroUN .= " and un.id_un in ('" . $temporal . "') ";
            }
        }
        if ($filtroUN===""){
            $corregimientos = clsCaus::obtenerCorUsuario();
            if ($corregimientos != "")
            $filtroUN = " and un.id_corregimiento in ( ".$corregimientos." ) ";
        }
        
        if ($config["filtro"] != "") {
            $filtro1 = " AND (un.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                        " OR reg.nombre_region LIKE '%" . $config["filtro"] . "%'" .
                        " OR vigmor.semana_epi LIKE '%" . $config["filtro"] . "%'" .
                        " OR vigmor.anio LIKE '%" . $config["filtro"] . "%'" .
                        " OR eve1.cie_10_1 LIKE '%" . $config["filtro"] . "%'" .
                        " OR eve1.nombre_evento LIKE '%" . $config["filtro"] . "%'" .
                        " OR vigmor.numero_identificacion LIKE '%" . $config["filtro"] . "%')";
        }
        $sql = "select count(*) as total from vm_form vigmor 
                    inner join tbl_persona per on per.tipo_identificacion = vigmor.tipo_identificacion and per.numero_identificacion = vigmor.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = vigmor.id_un
                    left join cat_corregimiento cor on cor.id_corregimiento = per.id_corregimiento
                    left join cat_distrito dis on dis.id_distrito = cor.id_distrito
                    left join cat_region_salud reg on reg.id_region = dis.id_region 
                    left join cat_evento eve1 on vigmor.id_diagnostico1 = eve1.id_evento WHERE 1 ". $filtro1.$filtroUN;
        //echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }
    
}