<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/caus/clsCaus.php');
require_once('libs/helper/helperString.php');

class helperEno {
    
    public static function yaExisteEno($eno) {
        //return 0;
        $sql = "select count(*) from eno_encabezado where anio=".$eno['anio']." and semana_epi='".$eno['semana_epi']."' and id_un='".$eno['id_un']."'";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }
    
//    public static function dataActualizarTblVihSilab($data) {
//        // DATOS DEL INDIVIDUO
//        $vih = array();
//        $vih["comp_donante_sangre"] = ($data["donante"] == 0 ? 2 : 1);
//        $vih["comp_uso_condon"] = ($data["no_preservativo"] == 0 ? 2 : 1);
//        $vih["comp_trabajador_sexual"] = ($data["TS"] == 0 ? 2 : 1);
//        $vih["comp_its_ultimo"] = ($data["ITS"] == 0 ? 2 : 1);
//        $vih["comp_embarazada"] = ($data["embarazo"] == 0 ? 2 : 1);
//        $vih["id_tipo_identidad"] = (!isset($data["IND_IDENTIFICADOR_TIPO"]) ? NULL : strtoupper($data["IND_IDENTIFICADOR_TIPO"]));
//        $vih["numero_identificacion"] = (!isset($data["IND_IDENTIFICADOR"]) ? NULL : strtoupper($data["IND_IDENTIFICADOR"]));
//        return $vih;
//    }
    
    public static function dataEnoForm($data) {
        //print_r($data);exit;
        $eno = array();
        //Individuo
        $eno["anio"] = (!isset($data["encabezado"]["anioEpi"]) ? NULL : $data["encabezado"]["anioEpi"]);
        $eno["fecha_fin"] = (!isset($data["encabezado"]["fechaFin"]) ? NULL : helperString::toDateTrim($data["encabezado"]["fechaFin"]));
        $eno["fecha_inic"] = (!isset($data["encabezado"]["fechaIni"]) ? NULL : helperString::toDateTrim($data["encabezado"]["fechaIni"]));
        $eno["id_servicio"] = (!isset($data["encabezado"]["servicio"]) ? NULL : $data["encabezado"]["servicio"]);
        $eno["id_un"] = $data["encabezado"]["un_id"];
        $eno["semana_epi"] = (!isset($data["encabezado"]["semanaEpi"]) ? NULL : $data["encabezado"]["semanaEpi"]);
        return $eno;
    }
       
    public static function buscarEno($config) {
        $conn = new Connection();
        $conn->initConn();
        $flag = 0;
        $filtro1 = "";
        $filtroUN = "";
        $whereId = "";
        $read = false;
        foreach ($config as $valor) {
            if ($valor != "")
                $flag++;
        }
        
        $lista = clsCaus::obtenerUbicaciones(ConfigurationCAUS::Localidad);
        if (is_array($lista)) {
            $temporal = implode("','", $lista);
            if ($temporal != "") {
                if (isset($config["id_enc"]))
                    $filtroUN .= " and T1.id_un in ('" . $temporal . "') ";
                else
                    $filtroUN .= " and eno.id_un in ('" . $temporal . "') ";
            }
        }
        
        if ($filtroUN===""){
            $corregimientos = clsCaus::obtenerCorUsuario();
            if ($corregimientos != ""){
                if (isset($config["id_enc"]))
                    $filtroUN = " and T2.id_corregimiento in ( ".$corregimientos." ) ";
                else
                    $filtroUN = " and un.id_corregimiento in ( ".$corregimientos." ) ";
            }
        }
                
        if (isset($config["id_enc"])){
            $sql = "select T1.id_enc, T1.id_un, T2.nombre_un, T3.id_corregimiento, T4.id_distrito, T4.id_region, 
                    T4.id_provincia, T1.semana_epi, DATE_FORMAT(T1.fecha_inic, '%d-%m-%Y') as fecha_inic, 
                    DATE_FORMAT(T1.fecha_fin,'%d-%m-%Y') as fecha_fin, T1.anio, T1.id_servicio 
                    FROM eno_encabezado T1
                    LEFT JOIN cat_unidad_notificadora T2 ON T1.id_un = T2.id_un
                    LEFT JOIN cat_corregimiento T3 ON T3.id_corregimiento = T2.id_corregimiento
                    LEFT JOIN cat_distrito T4 ON T4.id_distrito = T3.id_distrito
                    left join cat_region_salud reg on reg.id_region = T4.id_region 
                    LEFT JOIN cat_servicio T5 ON T1.id_servicio = T5.id_servicio";
            $whereId = " AND T1.id_enc = ".$config["id_enc"];
        }
        else
            $sql = "select * from eno_encabezado eno 
                    left join cat_unidad_notificadora un on un.id_un = eno.id_un
                    left join cat_corregimiento cor on cor.id_corregimiento = un.id_corregimiento
                    left join cat_distrito dis on dis.id_distrito = cor.id_distrito
                    left join cat_region_salud reg on reg.id_region = dis.id_region 
                    LEFT JOIN cat_servicio T5 ON eno.id_servicio = T5.id_servicio";
        
        if ($config["filtro"] != "") {
            $filtro1 = " AND (un.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                    " OR reg.nombre_region LIKE '%" . $config["filtro"] . "%'" .
                    " OR eno.semana_epi LIKE '%" . $config["filtro"] . "%'" .
                    " OR eno.anio LIKE '%" . $config["filtro"] . "%') ";
        }             
        $sql .= " WHERE 1 ". $filtro1 ." ".$filtroUN." ".$whereId." order by id_enc desc";
        if (!$read) {
            if (isset($config["inicio"])&&isset($config["paginado"]))
            $sql.= " limit " . $config["inicio"] . "," . $config["paginado"];
        }
        
        //echo $sql; Exit;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function buscarEnoCantidad($config) {
        $conn = new Connection();
        $conn->initConn();
        $filtro1 = "";
        $filtroUN = "";
        
        $lista = clsCaus::obtenerUbicaciones(ConfigurationCAUS::Localidad);
        if (is_array($lista)) {
            $temporal = implode("','", $lista);
            if ($temporal != "") 
                $filtroUN .= " and eno.id_un in ('" . $temporal . "') ";
        }
        
        if ($filtroUN===""){
            $corregimientos = clsCaus::obtenerCorUsuario();
            if ($corregimientos != "")
            $filtroUN = " and un.id_corregimiento in ( ".$corregimientos." ) ";
        }
        
        if ($config["filtro"] != "") {
            $filtro1 = " AND (un.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                        " OR reg.nombre_region LIKE '%" . $config["filtro"] . "%'" .
                        " OR eno.semana_epi LIKE '%" . $config["filtro"] . "%'" .
                        " OR eno.anio LIKE '%" . $config["filtro"] . "%')";
        }
        $sql = "select count(*) as total from eno_encabezado eno
                    left join cat_unidad_notificadora un on un.id_un = eno.id_un
                    left join cat_corregimiento cor on cor.id_corregimiento = un.id_corregimiento
                    left join cat_distrito dis on dis.id_distrito = cor.id_distrito
                    left join cat_region_salud reg on reg.id_region = dis.id_region 
                    LEFT JOIN cat_servicio T5 ON eno.id_servicio = T5.id_servicio WHERE 1 ". $filtro1.$filtroUN;
        //echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }
}