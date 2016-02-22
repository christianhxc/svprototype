<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/caus/clsCaus.php');
require_once('libs/helper/helperString.php');

class helperVacunas {
    
    public static function yaExisteCodigo($esquema) {
        //return 0;
        $sql = "select count(*) from vac_esquema where codigo_esquema='".$esquema['codigo_esquema']."'";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }
        
    public static function dataEsquemaForm($data) {
        //print_r($data);
        $esquema = array();
        $esquema["nombre_esquema"] = (!isset($data["vacuna"]["nombre"]) ? NULL : $data["vacuna"]["nombre"]);
        $esquema["codigo_esquema"] = (!isset($data["vacuna"]["codigo"]) ? NULL : $data["vacuna"]["codigo"]);
        $esquema["fecha_vigencia"] = (!isset($data["vacuna"]["fecha_vigencia"]) ? NULL : helperString::toDate($data["vacuna"]["fecha_vigencia"]));
        $esquema["rango_edad_ini"] = (!isset($data["vacuna"]["edad_ini"]) ? NULL : $data["vacuna"]["edad_ini"]);
        $esquema["tipo_rango_ini"] = (!isset($data["vacuna"]["tipo_edad_ini"]) ? NULL : $data["vacuna"]["tipo_edad_ini"]);
        $esquema["rango_edad_fin"] = (!isset($data["vacuna"]["edad_fin"]) ? NULL : $data["vacuna"]["edad_fin"]);
        $esquema["tipo_rango_fin"] = (!isset($data["vacuna"]["tipo_edad_fin"]) ? NULL : $data["vacuna"]["tipo_edad_fin"]);
        $esquema["sexo"] = (!isset($data["vacuna"]["sexo"]) ? NULL : $data["vacuna"]["sexo"]);
        $esquema["status"] = (!isset($data["vacuna"]["status"]) ? NULL : $data["vacuna"]["status"]);
        $esquema["tomar_fecha_vigencia"] = (!isset($data["vacuna"]["toma_fecha_vigencia"]) ? NULL : $data["vacuna"]["toma_fecha_vigencia"]);
        $esquema["comentarios"] = (!isset($data["vacuna"]["indicaciones"]) ? NULL : $data["vacuna"]["indicaciones"]);
        //print_r($esquema);exit;
        return $esquema;
    }
    
    public static function dataDenominadoresForm($data) {
        //print_r($data);
        $denominador = array();
        $denominador["nivel"] = (!isset($data["distribucion"]["nivel"]) ? NULL : $data["distribucion"]["nivel"]);
        $denominador["id_provincia"] = (!isset($data["distribucion"]["provincia"]) ? NULL : $data["distribucion"]["provincia"]);
        $denominador["id_region"] = (!isset($data["distribucion"]["region"]) ? NULL : $data["distribucion"]["region"]);
        $denominador["id_distrito"] = (!isset($data["distribucion"]["distrito"]) ? NULL : $data["distribucion"]["distrito"]);
        $denominador["id_corregimiento"] = (!isset($data["distribucion"]["corregimiento"]) ? NULL : $data["distribucion"]["corregimiento"]);
        $denominador["id_un"] = (!isset($data["distribucion"]["id_un"]) ? NULL : $data["distribucion"]["id_un"]);
        $denominador["anio"] = (!isset($data["distribucion"]["anio"]) ? NULL : $data["distribucion"]["anio"]);
        //print_r($esquema);exit;
        return $denominador;
    }
    
    public static function dataCondiciones($data) {
        $esquema = array();
        $condiciones = array();
        $esquema["condiciones"] = (!isset($data["relaciones"]["globalCondicionesRelacionados"]) ? 0 : explode("###",$data["relaciones"]["globalCondicionesRelacionados"]));
        $max = sizeof($esquema["condiciones"]);
        for ($i = 0; $i < $max; $i++) {
            $condicion = explode("#-#",$esquema["condiciones"][$i]);
            $condiciones[$i] = $condicion;
        }
        return $condiciones;
    }
    
    public static function dataCondicionesPersona($data) {
        $esquema = array();
        $condiciones = array();
        $esquema["condiciones"] = (!isset($data["formulario"]["globalCondicionesRelacionados"]) ? 0 : explode("###",$data["formulario"]["globalCondicionesRelacionados"]));
        $max = sizeof($esquema["condiciones"]);
        $num=0;
        for ($i = 0; $i < $max; $i++) {
            if($esquema["condiciones"][$i]!==""){
                $condicion = explode("#-#",$esquema["condiciones"][$i]);
                $condiciones[$num] = $condicion;
                $num++;
            }
        }
        return $condiciones;
    }
    
    public static function dataRegistroDiarioForm($data) {
        //print_r($data);
        $registro = array();
        $registro["tipo_identificacion"] = (!isset($data["individuo"]["tipoId"]) ? NULL : $data["individuo"]["tipoId"]);
        $registro["numero_identificacion"] = (!isset($data["individuo"]["identificador"]) ? NULL : $data["individuo"]["identificador"]);
        if ($registro["tipo_identificacion"] == 1)
            $registro["numero_identificacion"] = $data["individuo"]["identificador1"]."-".$data["individuo"]["identificador2"]."-".$data["individuo"]["identificador3"];
        $registro["per_edad"] = (!isset($data["individuo"]["edad"]) ? NULL : $data["individuo"]["edad"]);
        $registro["per_tipo_edad"] = (!isset($data["individuo"]["tipo_edad"]) ? NULL : $data["individuo"]["tipo_edad"]);
        $registro["per_id_pais"] = (!isset($data["individuo"]["pais"]) ? NULL : $data["individuo"]["pais"]);
        $registro["per_id_corregimiento"] = (!isset($data["individuo"]["corregimiento"]) ? NULL : $data["individuo"]["corregimiento"]);
        $registro["per_direccion"] = (!isset($data["individuo"]["lugar_poblado"]) ? NULL : $data["individuo"]["lugar_poblado"]);
        $registro["asegurado"] = (!isset($data["individuo"]["tipo_paciente"]) ? NULL : $data["individuo"]["tipo_paciente"]);
        //print_r($registro);exit;
        return $registro;
    }
    
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
        $individuo["nombre_responsable"] = (!isset($data["individuo"]["nombre_responsable"]) ? NULL : strtoupper($data["individuo"]["nombre_responsable"]));
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
        $individuo["correo_electronico"] = (!isset($data["individuo"]["correo_electronico"]) ? NULL : $data["individuo"]["correo_electronico"]); 
        return $individuo;
    }
    
    public static function yaExisteRegistroDiario($registro) {
        //return 0;
        $sql = "select count(*) from vac_registro_diario where tipo_identificacion='".$registro['tipo_identificacion']."'and numero_identificacion='".$registro['numero_identificacion']."'";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }
    
    public static function yaExisteEsquema($esquema) {
        $sql = "select count(*) from vac_esq_detalle where id_esquema='" . $esquema['idEsquema'] . "' and id_vacuna='" . $esquema['idVacuna'] . "'";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
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

    public static function rnvVacunas($s, $read = false) {
        $conn = new Connection();
        $conn->initConn();

        $sql = "SELECT id_vacuna, CONCAT('(', codigo_vacuna, ') ', nombre_vacuna) as nombre FROM cat_vacuna WHERE status = 1";
        if ($s != "") $sql .= " AND (codigo_vacuna LIKE '%".$s."%' OR nombre_vacuna LIKE '%".$s."%')";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function ldbiInsumos($s, $read = false) {
        $conn = new Connection();
        $conn->initConn();

        $sql = "SELECT id_insumo, CONCAT('(', codigo_insumo, ') ', nombre_insumo, ' - ', unidad_presentacion) as nombre FROM cat_insumos_LDBI WHERE status = 1";
        if ($s != "") $sql .= " AND (codigo_insumo LIKE '%".$s."%' OR nombre_insumo LIKE '%".$s."%' OR unidad_presentacion LIKE '%".$s."%')";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function tipoAlertas($config, $read = false) {
        $conn = new Connection();
        $conn->initConn();

        $sql = "SELECT id_notificacion_tipo, nombre FROM vac_notificacion_tipo WHERE status = 1";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
       
    public static function buscarEsquema($config, $read = false) {
        $conn = new Connection();
        $conn->initConn();
        $flag = 0;
        $filtro1 = "";
        $whereId = "";
        foreach ($config as $valor) {
            if ($valor != "")
                $flag++;
        }
        if (isset($config["id_esquema"])){
            $sql = "select T1.id_esquema, T1.codigo_esquema, T1.nombre_esquema, 
                    DATE_FORMAT(T1.fecha_vigencia,'%d/%m/%Y') as fecha_vigencia,
                    T1.rango_edad_ini, T1.tipo_rango_ini, T1.rango_edad_fin, T1.tipo_rango_fin, T1.sexo, T1.status, T1.tomar_fecha_vigencia, 
                    T1.comentarios
                    from vac_esquema T1 ";
            $whereId = " AND T1.id_esquema = ".$config["id_esquema"];
        }
        else
            $sql = "select * from vac_esquema T1 ";
        
        if ($config["filtro"] != "") {
            $filtro1 = " AND (T1.codigo_esquema LIKE '%" . $config["filtro"] . "%'" .
                       " OR T1.nombre_esquema LIKE '%" . $config["filtro"] . "%')";
        }             
        $sql .= " WHERE 1 ". $filtro1 ." ".$whereId." order by id_esquema desc";
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
    
    public static function buscarDenominador($config) {
        $conn = new Connection();
        $conn->initConn();
        $flag = 0;
        $filtro1 = "";
        $whereId = "";
        $read = false;
        foreach ($config as $valor) {
            if ($valor != "")
                $flag++;
        }
        $sql = "select T1.id_vac_denominador, T1.nivel,
                T1.id_provincia, case when T1.id_un <> 0 THEN T10.nombre_provincia ELSE T3.nombre_provincia END nombre_provincia,
                T1.id_region, case when T1.id_un <> 0 THEN T9.nombre_region ELSE T4.nombre_region END nombre_region,
                T1.id_distrito, case when T1.id_un <> 0 THEN T8.nombre_distrito ELSE T5.nombre_distrito END nombre_distrito, 
                T1.id_corregimiento, case when T1.id_un <> 0 THEN T7.nombre_corregimiento ELSE T6.nombre_corregimiento END nombre_corregimiento,
                T1.id_un, T2.nombre_un, T1.anio
                from vac_denominador T1
                left join cat_unidad_notificadora T2 on T1.id_un = T2.id_un
                left join cat_provincia T3 on T1.id_provincia = T3.id_provincia
                left join cat_region_salud T4 on T1.id_region = T4.id_region
                left join cat_distrito T5 on T1.id_distrito = T5.id_distrito
                left join cat_corregimiento T6 on T1.id_corregimiento = T6.id_corregimiento 
                left join cat_corregimiento T7 on T2.id_corregimiento = T7.id_corregimiento
                left join cat_distrito T8 on T7.id_distrito = T8.id_distrito
                left join cat_region_salud T9 on T2.id_region = T9.id_region
                left join cat_provincia T10 on T8.id_provincia = T10.id_provincia ";
        
        if (isset($config["id_vac_denominador"])){
            $whereId = " AND T1.id_vac_denominador = ".$config["id_vac_denominador"];
        }
        
        if ($config["filtro"] != "") {
            $filtro1 = " AND (T2.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                       " OR T3.nombre_provincia LIKE '%" . $config["filtro"] . "%'" .
                       " OR T4.nombre_region LIKE '%" . $config["filtro"] . "%'" .
                       " OR T5.nombre_distrito LIKE '%" . $config["filtro"] . "%'" .
                       " OR T6.nombre_corregimiento LIKE '%" . $config["filtro"] . "%')";
        }             
        $sql .= " WHERE 1 ". $filtro1 ." ".$whereId." order by T1.id_vac_denominador desc";
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
    
    public static function buscarDenominadorCantidad($config) {
        $conn = new Connection();
        $conn->initConn();
        $filtro1 = "";
        if ($config["filtro"] != "") {
            $filtro1 = " AND (T2.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                       " OR T3.nombre_provincia LIKE '%" . $config["filtro"] . "%'" .
                       " OR T4.nombre_region LIKE '%" . $config["filtro"] . "%'" .
                       " OR T5.nombre_distrito LIKE '%" . $config["filtro"] . "%'" .
                       " OR T6.nombre_corregimiento LIKE '%" . $config["filtro"] . "%')";
        }
        $sql = "select count(*) as total from vac_denominador T1
                    left join cat_unidad_notificadora T2 on T1.id_un = T2.id_un
                    left join cat_provincia T3 on T1.id_provincia = T3.id_provincia
                    left join cat_region_salud T4 on T1.id_region = T4.id_region
                    left join cat_distrito T5 on T1.id_distrito = T5.id_distrito
                    left join cat_corregimiento T6 on T1.id_corregimiento = T6.id_corregimiento WHERE 1 ". $filtro1;
        //echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }
    
    public static function buscarRegistroDiario($config) {
        $conn = new Connection();
        $conn->initConn();
        $flag = 0;
        $filtro1 = "";
        $whereId = "";
        $read = false;
        foreach ($config as $valor) {
            if ($valor != "")
                $flag++;
        }
        if (isset($config["id_vac_registro_diario"])){
            $sql = "SELECT
                    T1.id_vac_registro_diario, T1.tipo_identificacion, T1.numero_identificacion, 
                    T1.per_edad, T1.per_tipo_edad, T1.per_id_corregimiento, T1.per_direccion, T1.per_id_pais,
                    T3.primer_nombre, T3.segundo_nombre, T3.primer_apellido, T3.segundo_apellido, T3.nombre_responsable,
                    DATE_FORMAT(T3.fecha_nacimiento,'%d/%m/%Y') as fecha_nacimiento, T3.sexo, T3.localidad, T3.dir_referencia, T3.tel_residencial,
                    T7.id_provincia, T7.id_region, T5.id_distrito, T5.nombre_corregimiento, T7.nombre_region as per_region, asegurado
                    FROM vac_registro_diario T1
                    LEFT JOIN tbl_persona T3 on T1.numero_identificacion = T3.numero_identificacion and T1.tipo_identificacion = T3.tipo_identificacion
                    LEFT JOIN cat_corregimiento T5 ON T5.id_corregimiento = T1.per_id_corregimiento
                    LEFT JOIN cat_distrito T6 ON T6.id_distrito = T5.id_distrito
                    LEFT JOIN cat_region_salud T7 ON T7.id_region = T6.id_region ";
            $whereId = " AND T1.id_vac_registro_diario = ".$config["id_vac_registro_diario"];
        }
        else
            $sql = "select T1.*, T3.*, T5.*, T6.*, T7.nombre_region as per_region FROM vac_registro_diario T1
                    LEFT JOIN tbl_persona T3 on T1.numero_identificacion = T3.numero_identificacion and T1.tipo_identificacion = T3.tipo_identificacion
                    LEFT JOIN cat_corregimiento T5 ON T5.id_corregimiento = T1.per_id_corregimiento
                    LEFT JOIN cat_distrito T6 ON T6.id_distrito = T5.id_distrito
                    LEFT JOIN cat_region_salud T7 ON T7.id_region = T6.id_region ";
        
        if ($config["filtro"] != "") {
            $filtro1 = " AND (" . $config["filtro"] . ")";
        }             
        $sql .= " WHERE 1 ". $filtro1 ." ".$whereId." order by T1.id_vac_registro_diario desc";
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
    
    public static function buscarVacCondiciones($config) {
        $sql = "select t2.id_condicion, t2.nombre_condicion
                from vac_esquema_condicion t1
                inner join cat_vac_condicion t2 on t1.id_condicion = t2.id_condicion
                where t1.id_esquema = ".$config["id_esquema"];
        //echo $sql;exit;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
    public static function buscarVacCondicionesPersona($config) {
        //print_r($config);exit;
        $sql = "select t2.id_condicion, t2.nombre_condicion
                from vac_persona_condicion t1
                inner join cat_vac_condicion t2 on t1.id_condicion = t2.id_condicion
                where t1.tipo_identificacion = '".$config["read"]["tipo_identificacion"]."' AND t1.numero_identificacion = '".$config["read"]["numero_identificacion"]."'";
        //echo $sql;exit;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function buscarEsqCantidad($config) {
        $conn = new Connection();
        $conn->initConn();
        $filtro1 = "";
        if ($config["filtro"] != "") {
            $filtro1 = " AND (T1.codigo_esquema LIKE '%" . $config["filtro"] . "%'" .
                       " OR T1.nombre_esquema LIKE '%" . $config["filtro"] . "%')";
        }
        $sql = "select count(*) as total from vac_esquema T1 WHERE 1 ". $filtro1;
        //echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }
    
    public static function buscarRegisgtroCantidad($config) {
        $conn = new Connection();
        $conn->initConn();
        $filtro1 = "";
        if ($config["filtro"] != "") {
            $filtro1 = " AND (" . $config["filtro"] . ")";
        }
        $sql = "select count(*) as total FROM vac_registro_diario T1 
                LEFT JOIN tbl_persona T3 on T1.numero_identificacion = T3.numero_identificacion and T1.tipo_identificacion = T3.tipo_identificacion 
                LEFT JOIN cat_corregimiento T5 ON T5.id_corregimiento = T1.per_id_corregimiento 
                LEFT JOIN cat_distrito T6 ON T6.id_distrito = T5.id_distrito 
                LEFT JOIN cat_region_salud T7 ON T7.id_region = T6.id_region 
                LEFT JOIN vac_registro_diario_dosis T4 ON T1.id_vac_registro_diario = T4.id_vac_registro_diario 
                LEFT JOIN cat_unidad_notificadora T2 ON T4.id_un = T2.id_un WHERE 1 ". $filtro1;
        //echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }
    
    public static function obtenerDosis($Esquemas){
        $sql="";
        $sql = "select T1.*
                from vac_dosis T1
                inner join vac_esq_detalle T2 on T1.id_esq_detalle = T2.id_esq_detalle
                WHERE T2.id_esquema IN (";
        foreach ($idEsquemas as $esquema)
            $sql .= $esquema.",";
        $sql .= "0);";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $error = $conn->getError();
        $data = $conn->fetch();
        $conn->closeConn();
        if ($error==""){
            return $data;
        }
        else
            return '-1';
    }
    
    public static function agregarRegistroDiarioDosis($dosis){
        $sql = " INSERT INTO vac_registro_diario_dosis
                (id_vac_registro_diario, id_dosis, fecha_dosis, id_un, nombre_reporta, nombre_registra, id_modalidad, nombre_modalidad_otro, id_zona, numero_lote_1, 
                numero_lote_2, numero_lote_3, per_edad, per_tipo_edad, id_rango, id_esquema) 
                VALUES (".$dosis["id_form_registro"].", ".$dosis["id_dosis"].", '".helperString::toDate($dosis["fecha_dosis"])."', ".$dosis["id_un"].", '".$dosis["nombre_reporta"]."', 
                '".$dosis["nombre_registra"]."',".$dosis["id_modalidad"].", '".$dosis["nombre_modalidad_otro"]."', '".$dosis["id_zona"]."', '".$dosis["numero_lote_1"]."', 
                '".$dosis["numero_lote_2"]."', '".$dosis["numero_lote_3"]."', ".$dosis["per_edad"].", ".$dosis["per_tipo_edad"].", ".$dosis["id_rango"].", ".$dosis["id_esquema"].")";
        //echo $sql; exit;        
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $id = $conn->getID();
        $error = $conn->getError();
        //echo "error: ".$error;exit;
        $conn->closeConn();
        if ($error != ""){
            return $id;
        }
        else
            return '-1';
    }
    
    public static function editarRegistroDiarioDosis($dosis){
        $sql = "update vac_registro_diario_dosis
               set fecha_dosis  = '".helperString::toDate(trim($dosis["fecha_dosis"]))."', numero_lote_1  = '".trim($dosis["numero_lote_1"])."', numero_lote_2  = '".trim($dosis["numero_lote_2"])."', 
               numero_lote_3  = '".trim($dosis["numero_lote_3"])."', id_un = ".$dosis["id_un"].", id_modalidad = ".$dosis["id_modalidad"].", id_zona = '".$dosis["id_zona"]."',
               nombre_modalidad_otro = '".$dosis["nombre_modalidad_otro"]."', nombre_reporta = '".$dosis["nombre_registra"]."',
               per_edad = ".$dosis["per_edad"].", per_tipo_edad = ".$dosis["per_tipo_edad"]."    
               where id_vac_registro_diario = ".$dosis["id_form_registro"]." and id_dosis = " . $dosis["id_dosis"];
        //echo $sql; exit;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $error = $conn->getError();
        $conn->closeConn();
        if ($error==""){
            return '1';
        }
        else
            return '-1';
    }
    
    public static function obtenerRangoEdad($meses){
        $sql="";
        $sql = "SELECT id_rango
                FROM `cat_vac_rango`
                WHERE limite_inferior_meses <= ".$meses."
                ORDER BY id_rango DESC
                LIMIT 0,1 ";

        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $error = $conn->getError();
        $data = $conn->fetchOne();
        $conn->closeConn();
        if ($error==""){
            return $data;
        }
        else
            return '-1';
    }
    
        public static function borrarRegistroDiarioDosis($dosis){
        $sql = " DELETE FROM vac_registro_diario_dosis
                 WHERE id_registro_diario_dosis = " . $dosis;
        //echo $sql; exit;        
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $error = $conn->getError();
        //echo "error: ".$error;exit;
        $conn->closeConn();
        if ($error==""){
            return '1';
        }
        else
            return '-1';
    }
}