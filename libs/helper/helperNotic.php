<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once ('libs/caus/clsCaus.php');
require_once('libs/silab/ConfigurationSilab.php');
require_once('libs/silab/ConnectionSilab.php');
require_once('libs/helper/helperString.php');

class helperNotic {
           
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

    public static function yaExisteNotic($notic) {
        //print_r($notic);exit;
        $sql = "select count(*) from notic_form where id_un = ".$notic['notificacion']['id_un']." and semana_epi = ".$notic['clinica']['semana_epi']
                ." and anio = ".$notic['clinica']['anio_epi']." and id_diagnostico1 = ".$notic['clinica']['id_evento1']
                ." and tipo_identificacion = '".$notic['individuo']['tipoId']."' and numero_identificacion='".$notic['individuo']['identificador'] . "'";
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
        $individuo["dir_trabajo"] = (!isset($data["individuo"]["punto_ref"]) ? NULL : strtoupper($data["individuo"]["punto_ref"]));
        return $individuo;
    }

    public static function dataForm($data) {
        //print_r($data);exit;
        $notic = array();
        //Individuo
        $notic["tipo_identificacion"] = (!isset($data["individuo"]["tipoId"]) ? NULL : $data["individuo"]["tipoId"]);
        $notic["numero_identificacion"] = (!isset($data["individuo"]["identificador"]) ? NULL : $data["individuo"]["identificador"]);
        if ($notic["tipo_identificacion"] == 1)
            $notic["numero_identificacion"] = $data["individuo"]["identificador1"]."-".$data["individuo"]["identificador2"]."-".$data["individuo"]["identificador3"];
        $notic["per_asegurado"] = (!isset($data["individuo"]["asegurado"]) ? NULL : $data["individuo"]["asegurado"]);
        $notic["per_edad"] = (!isset($data["individuo"]["edad"]) ? NULL : $data["individuo"]["edad"]);
        $notic["per_tipo_edad"] = (!isset($data["individuo"]["tipo_edad"]) ? '4' : $data["individuo"]["tipo_edad"]);
        $notic["per_id_pais"] = (!isset($data["individuo"]["pais"]) ? NULL : $data["individuo"]["pais"]);
        $notic["per_id_corregimiento"] = 630;
        if ($notic["per_id_pais"] == 174)
            $notic["per_id_corregimiento"] = $data["individuo"]["corregimiento"];
        $notic["per_direccion"] = (!isset($data["individuo"]["lugar_poblado"]) ? NULL : $data["individuo"]["lugar_poblado"]);
        $notic["per_dir_referencia"] = (!isset($data["individuo"]["punto_ref"]) ? NULL : $data["individuo"]["punto_ref"]);
        $notic["per_contagio"] = (!isset($data["individuo"]["contagio"]) ? NULL : $data["individuo"]["contagio"]);
        $notic["per_nombre_contagio"] = (!isset($data["individuo"]["nombre_conta"]) ? NULL : $data["individuo"]["nombre_conta"]);
        if ($notic["per_contagio"] != NULL && $notic["per_contagio"] != 1 && $notic["per_contagio"] != 5){
            $notic["id_pais_contagio"] = (!isset($data["individuo"]["pais_conta"]) ? NULL : $data["individuo"]["pais_conta"]);
            if ($notic["id_pais_contagio"] == 174)
                $notic["id_corregimiento_contagio"] = (!isset($data["individuo"]["corregimiento_conta"]) ? NULL : $data["individuo"]["corregimiento_conta"]);
            $notic["dir_descripcion_contagio"] = (!isset($data["individuo"]["referencia_conta"]) ? NULL : $data["individuo"]["referencia_conta"]);
        }
        //Datos de la clinica
        $notic["id_diagnostico1"] = (!isset($data["clinica"]["id_evento1"]) ? NULL : $data["clinica"]["id_evento1"]);
        $notic["id_diagnostico2"] = (!isset($data["clinica"]["id_evento2"]) ? NULL : $data["clinica"]["id_evento2"]);
        $notic["id_diagnostico3"] = (!isset($data["clinica"]["id_evento3"]) ? NULL : $data["clinica"]["id_evento3"]);
        $notic["estado_diag1"] = (!isset($data["clinica"]["estado_eve1"]) ? NULL : $data["clinica"]["estado_eve1"]);
        $notic["estado_diag2"] = (!isset($data["clinica"]["estado_eve2"]) ? NULL : $data["clinica"]["estado_eve2"]);
        $notic["estado_diag3"] = (!isset($data["clinica"]["estado_eve3"]) ? NULL : $data["clinica"]["estado_eve3"]);
        $notic["condicion"] = (!isset($data["clinica"]["condicion"]) ? NULL : $data["clinica"]["condicion"]);
        $notic["fecha_inicio_sintomas"] = (!isset($data["clinica"]["fecha_ini_sintomas"]) ? NULL : helperString::toDate($data["clinica"]["fecha_ini_sintomas"]));
        $notic["semana_epi"] = (!isset($data["clinica"]["semana_epi"]) ? NULL : $data["clinica"]["semana_epi"]);
        $notic["anio"] = (!isset($data["clinica"]["anio_epi"]) ? NULL : $data["clinica"]["anio_epi"]);
        $notic["fecha_hospitalizacion"] = (!isset($data["clinica"]["fecha_hospitalizacion"]) ? NULL : helperString::toDate($data["clinica"]["fecha_hospitalizacion"]));
        $notic["fecha_defuncion"] = (!isset($data["clinica"]["fecha_defuncion"]) ? NULL : helperString::toDate($data["clinica"]["fecha_defuncion"]));
        $notic["fecha_toma_muestra"] = (!isset($data["clinica"]["fecha_muestra"]) ? NULL : helperString::toDate($data["clinica"]["fecha_muestra"]));
        $notic["id_tipo_muestra"] = (!isset($data["clinica"]["tipo_muestra"]) ? NULL : $data["clinica"]["tipo_muestra"]);
        $notic["criterio_caso_confirmado"] = (!isset($data["clinica"]["criterio"]) ? NULL : $data["clinica"]["criterio"]);
        
        //Datos notificacion
        $notic["id_un"] = $data["notificacion"]["id_un"];
        $notic["telefono"] = (!isset($data["notificacion"]["telefonoInvestigador"]) ? NULL : $data["notificacion"]["telefonoInvestigador"]);
        $notic["id_servicio"] = (!isset($data["notificacion"]["servicio"]) ? NULL : $data["notificacion"]["servicio"]);
        $notic["fecha_regional"] = (!isset($data["notificacion"]["fecha_regional"]) ? NULL : helperString::toDate($data["notificacion"]["fecha_regional"]));
        $notic["nombre_reporta"] = (!isset($data["notificacion"]["nombreInvestigador"]) ? NULL : $data["notificacion"]["nombreInvestigador"]);
        $notic["id_cargo"] = (!isset($data["notificacion"]["cargo"]) ? NULL : $data["notificacion"]["cargo"]);
        $notic["nombre_registra"] = (!isset($data["notificacion"]["nombreRegistra"]) ? NULL : $data["notificacion"]["nombreRegistra"]);
        $notic["fecha_notificacion"] = (!isset($data["notificacion"]["fecha_notificacion"]) ? NULL : helperString::toDate($data["notificacion"]["fecha_notificacion"]));
        
        if(isset($data["notificacion"]["fechaFormulario"])){
            if ($data["notificacion"]["fechaFormulario"]!= null && $data["notificacion"]["fechaFormulario"]!='')
                $notic["fecha_formulario"] = helperString::toDate($data["notificacion"]["fechaFormulario"]);
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
                $notic["hora_formulario"] = $varHoraCompleta;
            }
        }
        $notic["comentario"] = (!isset($data["notificacion"]["comentarios"]) ? NULL : $data["notificacion"]["comentarios"]);
        //print_r($notic);exit;
        return $notic;
    }
    
    // Obtiene el listado de uceti
    public static function buscarNotic($config) {
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
        //print_r($lista);exit;
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
            $sql = "select notic.*, per.*, un.*, cor.id_corregimiento as id_cor_persona, dis.id_distrito as id_dis_persona, dis.id_provincia as id_pro_persona,
                    reg.id_region as id_reg_persona, reg.nombre_region as nombre_region_persona,
                    cor1.id_corregimiento as id_cor_conta, dis1.id_distrito as id_dis_conta, dis1.id_provincia as id_pro_conta,
                    reg1.id_region as id_reg_conta,
                    eve1.id_evento as id_eve1, eve1.cie_10_1 as cie1, eve1.nombre_evento as nom_eve1,
                    eve2.id_evento as id_eve2, eve2.cie_10_1 as cie2, eve2.nombre_evento as nom_eve2,
                    eve3.id_evento as id_eve3, eve3.cie_10_1 as cie3, eve3.nombre_evento as nom_eve3
                    from notic_form notic 
                    inner join tbl_persona per on per.tipo_identificacion = notic.tipo_identificacion and per.numero_identificacion = notic.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = notic.id_un
                    left join cat_corregimiento cor on cor.id_corregimiento = notic.per_id_corregimiento
                    left join cat_distrito dis on dis.id_distrito = cor.id_distrito
                    left join cat_region_salud reg on reg.id_region = dis.id_region
                    left join cat_corregimiento cor1 on cor1.id_corregimiento = notic.id_corregimiento_contagio
                    left join cat_distrito dis1 on dis1.id_distrito = cor1.id_distrito
                    left join cat_region_salud reg1 on reg1.id_region = dis1.id_region
                    left join cat_evento eve1 on notic.id_diagnostico1 = eve1.id_evento
                    left join cat_evento eve2 on notic.id_diagnostico2 = eve2.id_evento
                    left join cat_evento eve3 on notic.id_diagnostico3 = eve3.id_evento where 1=1 ".$filtroUN;
        else {
            if (isset($config["filtro"])) {
                $filtro1 = " AND (un.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                        " OR reg.nombre_region LIKE '%" . $config["filtro"] . "%'" .
                        " OR notic.semana_epi LIKE '%" . $config["filtro"] . "%'" .
                        " OR notic.anio LIKE '%" . $config["filtro"] . "%'" .
                        " OR eve1.cie_10_1 LIKE '%" . $config["filtro"] . "%'" .
                        " OR eve1.nombre_evento LIKE '%" . $config["filtro"] . "%'" .
                        " OR notic.numero_identificacion LIKE '%" . $config["filtro"] . "%')";
            } else if (isset($config["id_form"])) {
                $filtro2 = " AND notic.id_notic= ".$config["id_form"];
                $read = true;
            }
            $sql = "select notic.*, per.*, un.*, cor.id_corregimiento as id_cor_persona, dis.id_distrito as id_dis_persona, dis.id_provincia as id_pro_persona,
                    reg.id_region as id_reg_persona, reg.nombre_region as nombre_region_persona,
                    cor1.id_corregimiento as id_cor_conta, dis1.id_distrito as id_dis_conta, dis1.id_provincia as id_pro_conta,
                    reg1.id_region as id_reg_conta,
                    eve1.id_evento as id_eve1, eve1.cie_10_1 as cie1, eve1.nombre_evento as nom_eve1,
                    eve2.id_evento as id_eve2, eve2.cie_10_1 as cie2, eve2.nombre_evento as nom_eve2,
                    eve3.id_evento as id_eve3, eve3.cie_10_1 as cie3, eve3.nombre_evento as nom_eve3
                    from notic_form notic 
                    inner join tbl_persona per on per.tipo_identificacion = notic.tipo_identificacion and per.numero_identificacion = notic.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = notic.id_un
                    left join cat_corregimiento cor on cor.id_corregimiento = notic.per_id_corregimiento
                    left join cat_distrito dis on dis.id_distrito = cor.id_distrito
                    left join cat_region_salud reg on reg.id_region = dis.id_region
                    left join cat_corregimiento cor1 on cor1.id_corregimiento = notic.id_corregimiento_contagio
                    left join cat_distrito dis1 on dis1.id_distrito = cor1.id_distrito
                    left join cat_region_salud reg1 on reg1.id_region = dis1.id_region
                    left join cat_evento eve1 on notic.id_diagnostico1 = eve1.id_evento
                    left join cat_evento eve2 on notic.id_diagnostico2 = eve2.id_evento
                    left join cat_evento eve3 on notic.id_diagnostico3 = eve3.id_evento WHERE 1 "
                    . $filtro1 . $filtro2 . $filtroUN.' order by id_notic desc';
            //. " limit " . $config["inicio"] . "," . $config["paginado"];
            if (!$read) {
                $sql.= " limit " . $config["inicio"] . "," . $config["paginado"];
            }
        }
        
        //echo $sql;exit;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function buscarNoticCantidad($config) {
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
                        " OR notic.semana_epi LIKE '%" . $config["filtro"] . "%'" .
                        " OR notic.anio LIKE '%" . $config["filtro"] . "%'" .
                        " OR eve1.cie_10_1 LIKE '%" . $config["filtro"] . "%'" .
                        " OR eve1.nombre_evento LIKE '%" . $config["filtro"] . "%'" .
                        " OR notic.numero_identificacion LIKE '%" . $config["filtro"] . "%')";
        }
        $sql = "select count(*) as total from notic_form notic 
                    inner join tbl_persona per on per.tipo_identificacion = notic.tipo_identificacion and per.numero_identificacion = notic.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = notic.id_un
                    left join cat_corregimiento cor on cor.id_corregimiento = per.id_corregimiento
                    left join cat_distrito dis on dis.id_distrito = cor.id_distrito
                    left join cat_region_salud reg on reg.id_region = dis.id_region 
                    left join cat_evento eve1 on notic.id_diagnostico1 = eve1.id_evento WHERE 1 ". $filtro1.$filtroUN;
        //echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }
    
    public static function dataNoticSintomas($data) {
        $notic = array();
        $sintomas = array();
        $notic["sintomas"] = (!isset($data["sintomas"]["globalSignoSintomaRelacionados"]) ? 0 : explode("###",$data["sintomas"]["globalSignoSintomaRelacionados"]));
        $max = sizeof($notic["sintomas"]);
        for ($i = 0; $i < $max; $i++) {
            $sintoma = explode("#-#",$notic["sintomas"][$i]);
            $sintomas[$i] = $sintoma;
        }
        return $sintomas;
    }
    
    public static function buscarNoticSintomas($notic) {
        $sql = "select t1.id_sintoma, t2.nombre_sintoma, 
                date_format(t1.fecha_sintoma, '%d/%m/%Y') as fecha_sintoma
                from notic_sintoma t1
                left join cat_sintoma t2 on t1.id_sintoma = t2.id_sintoma where t1.id_notic = ".$notic["id_form"];
        //echo $sql;exit;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
}