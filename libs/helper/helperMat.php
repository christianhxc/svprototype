<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once ('libs/caus/clsCaus.php');
require_once('libs/silab/ConfigurationSilab.php');
require_once('libs/silab/ConnectionSilab.php');
require_once('libs/helper/helperString.php');

class helperMat {
    
    
    public static function getGrupoContaco(){
        $sql = "select id_grupo_contacto, nombre_grupo_contacto 
                from mat_grupo_contacto
                where status = 1 order by nombre_grupo_contacto";
        $conn = new Connection();
    	$conn->initConn();
    	$conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetch();
    	$conn->closeConn();
    	return $data;
    }
    
    // Obtiene los provincia 
    public static function getProvincias(){
        $sql = "select distinct mat.id_nivel_geo1 as provincia, mat.nombre_nivel_geo1 as descripcionProvincia
                from tbl_mat_diagnostico mat
                order by mat.id_nivel_geo1";
        $conn = new Connection();
    	$conn->initConn();
    	$conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetch();
    	$conn->closeConn();
    	return $data;
    }
    
    public static function getRegiones($idprovincia){
        $sql = "select distinct mat.id_nivel_geo2, mat.nombre_nivel_geo2
                from tbl_mat_diagnostico mat
                where mat.id_nivel_geo1 = ".$idprovincia."
                order by mat.id_nivel_geo2";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
    public static function getDistritos($idprovincia,$idregion){
        $sql = "select distinct mat.id_nivel_geo3, mat.nombre_nivel_geo3
                from tbl_mat_diagnostico mat
                where mat.id_nivel_geo1 = ".$idprovincia." and mat.id_nivel_geo2 = ".$idregion."    
                order by mat.id_nivel_geo3";   
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getCorregimientos($distrito){
        $sql = "select distinct mat.id_nivel_geo4, mat.nombre_nivel_geo4
                from tbl_mat_diagnostico mat
                where mat.id_nivel_geo3 = ".$distrito." 
                order by mat.id_nivel_geo4";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
    public static function yaExisteContacto($individuo) {
        $sql = "select count(*) from mat_contacto where nombres='".$individuo['nombres']."' and apellidos='".$individuo['apellidos']."' and email='".$individuo['email']."'";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }
    
    public static function yaExisteGrupo($nombre) {
        $sql = "select count(*) from mat_grupo_contacto where nombre_grupo_contacto = '$nombre'";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }

    public static function yaExisteEscenario($data) {
        //return 0;
        $sql = "select count(*) from mat_escenario where id_n1=".$data['id_n1'] . " and tipo_algoritmo=".$data['tipo_algoritmo']; 
        if ($data["nivel_geo"]>=2)
            $sql.= " and id_nivel_geo = ".$data["id_nivel_geo"];
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }
    
    public static function yaExisteRelacion($idGrupo, $idContacto) {
        //return 0;
        $sql = "select count(*) as total from mat_contacto_grupo_contacto where id_contacto = $idContacto and id_grupo_contacto = $idGrupo";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }
    
    public static function datosSufientesParaCanalEndemico($data, $filtro) {
        $semanaEpi = helperString::calcularSemanaEpi(date("m/d/Y"));
        $sql = "select mat.id_diagnostico, mat.nombre_diagnostico, mat.semana_epi, mat.anio
                from tbl_mat_diagnostico mat
                where mat.id_diagnostico = ".$data["id_n1"]." and mat.semana_epi = ".$semanaEpi["semana"].$filtro."
                group by mat.anio, mat.semana_epi
                order by mat.anio desc"; 
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    //Array ( [id_individuo] => [asegurado] => 1 [tipoId] => 4 [identificador] => RN22752378 [primer_nombre] => Diego [segundo_nombre] => Fernando [primer_apellido] => Troncoso [segundo_apellido] => Silva [fecha_nacimiento] => 17/05/1987 [tipo_edad] => 3 [edad] => 25 [sexo] => M [nombre_responsable] => Fernando Troncoso [provincia] => 8 [region] => 8 [distrito] => 53 [corregimiento] => 499 [direccion] => El Doncello caqueta - 3 cuadras al sur de la iglesia principal [punto_referencia] => CASA DONCELLO [telefono] => 4566 7777 ) 
    public static function dataContactoForm($data) {
        // DATOS DEL INDIVIDUO
        $individuo = array();
        $individuo["nombres"] = (!isset($data["contacto"]["nombres"]) ? NULL : strtoupper($data["contacto"]["nombres"]));
        $individuo["apellidos"] = (!isset($data["contacto"]["apellidos"]) ? NULL : strtoupper($data["contacto"]["apellidos"]));
        $individuo["email"] = (!isset($data["contacto"]["email"]) ? NULL : $data["contacto"]["email"]);
        $individuo["telefono"] = (!isset($data["contacto"]["telefono"]) ? NULL : $data["contacto"]["telefono"]);
        $individuo["status"] = (!isset($data["contacto"]["status"]) ? NULL : $data["contacto"]["status"]);
        
        return $individuo;
    }
    
    public static function dataGruposRelacionados($data) {
        $mat = array();
        $mat["grupos"] = (!isset($data["grupos"]["globalGruposRelacionados"]) ? NULL : explode("###",$data["grupos"]["globalGruposRelacionados"]));
        return $mat;
    }
                
    public static function dataEscenarioForm($data) {
        $escenario = array();
        //Individuo
        $escenario["id_n1"] = (!isset($data["escenario"]["eve_id"]) ? NULL : $data["escenario"]["eve_id"]);
        $escenario["tipo_algoritmo"] = (!isset($data["escenario"]["algoritmo"]) ? NULL : $data["escenario"]["algoritmo"]);
        $escenario["nivel_geo"] = (!isset($data["escenario"]["nivel_geo"]) ? NULL : $data["escenario"]["nivel_geo"]);
        $escenario["id_nivel_geo"] = 'null';
        if ($escenario["nivel_geo"] == 2)
            $escenario["id_nivel_geo"] = (!isset($data["escenario"]["provincia"]) ? NULL : $data["escenario"]["provincia"]);
        else if ($escenario["nivel_geo"] == 3)
            $escenario["id_nivel_geo"] = (!isset($data["escenario"]["region"]) ? NULL : $data["escenario"]["region"]);
        else if ($escenario["nivel_geo"] == 4)
            $escenario["id_nivel_geo"] = (!isset($data["escenario"]["distrito"]) ? NULL : $data["escenario"]["distrito"]);
        else if ($escenario["nivel_geo"] == 5)
            $escenario["id_nivel_geo"] = (!isset($data["escenario"]["corregimiento"]) ? NULL : $data["escenario"]["corregimiento"]);
        $escenario["tipo_alerta"] = (!isset($data["escenario"]["tiempo"]) ? NULL : $data["escenario"]["tiempo"]);
        $escenario["dia_alerta"] = 'null';
        if ($escenario["tipo_alerta"] == 2 )
            $escenario["dia_alerta"] = $data["escenario"]["diaCorte"];
        $escenario["mensaje"] = (!isset($data["escenario"]["parteEmail"]) ? NULL : $data["escenario"]["parteEmail"]);
        
        $escenario["nombre_crear"] = (!isset($data["escenario"]["nombreCrear"]) ? NULL : $data["escenario"]["nombreCrear"]);
        $escenario["fecha_crear"] = (!isset($data["escenario"]["fechaCrear"]) ? NULL : helperString::toDate($data["escenario"]["fechaCrear"]));
        
        return $escenario;
    }

    // Obtiene el listado de escenarios
    public static function buscarEscenario($config) {
        $conn = new Connection();
        $conn->initConn();
        $filtro1 = "";
        
        $sql = "select distinct esc.id_escenario, esc.id_n1, eve.nombre_evento, eve.cie_10_1,
                case when esc.tipo_algoritmo = 1 then 'Caso individual' else 'Casos agrupados' end as algoritmo, esc.nivel_geo,
                (case when esc.nivel_geo = 1 then 'No aplica'  
                when esc.nivel_geo = 2 then pro.nombre_provincia
                when esc.nivel_geo = 3 then reg.nombre_region
                when esc.nivel_geo = 4 then dis.nombre_distrito else cor.nombre_corregimiento end) as nivel_geografico
                from mat_escenario esc
                inner join cat_evento eve on eve.id_evento = esc.id_n1
                left join cat_provincia pro on pro.id_provincia = esc.id_nivel_geo
                left join cat_region_salud reg on reg.id_region = esc.id_nivel_geo
                left join cat_distrito dis on dis.id_distrito = esc.id_nivel_geo
                left join cat_corregimiento cor on cor.id_corregimiento = esc.id_nivel_geo
                where esc.status = 1";
        
        if (isset($config["filtro"])) {
            if ($config["filtro"]!=""){
            $filtro1 = " AND (pro.nombre_provincia LIKE '%".$config["filtro"]."%' OR reg.nombre_region LIKE '%".$config["filtro"]."%' OR dis.nombre_distrito LIKE '%".
                    $config["filtro"]."%' OR cor.nombre_corregimiento LIKE '%".$config["filtro"]."%'" .
                    " OR eve.nombre_evento LIKE '%" . $config["filtro"] . "%'" .
                    " OR eve.cie_10_1 LIKE '%" . $config["filtro"] . "%')";
            }
        } 
        $sql .=  $filtro1 . ' order by id_escenario desc';
        if (isset($config["inicio"])) {
            $sql.= " limit " . $config["inicio"] . "," . $config["paginado"];
        }
        //echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function buscarEscenarioCantidad($config) {
        $conn = new Connection();
        $conn->initConn();
        $filtro1 = "";
        if (isset($config["filtro"])) {
            if ($config["filtro"]!=""){
            $filtro1 = " AND (pro.nombre_provincia LIKE '%".$config["filtro"]."%' OR reg.nombre_region LIKE '%".$config["filtro"]."%' OR dis.nombre_distrito LIKE '%".
                    $config["filtro"]."%' OR cor.nombre_corregimiento LIKE '%".$config["filtro"]."%'" .
                    " OR eve.nombre_evento LIKE '%" . $config["filtro"] . "%'" .
                    " OR eve.cie_10_1 LIKE '%" . $config["filtro"] . "%')";
            }
        } 
        $sql = "select distinct count(*) as total from mat_escenario esc
                inner join cat_evento eve on eve.id_evento = esc.id_n1
                left join cat_provincia pro on pro.id_provincia = esc.id_nivel_geo
                left join cat_region_salud reg on reg.id_region = esc.id_nivel_geo
                left join cat_distrito dis on dis.id_distrito = esc.id_nivel_geo
                left join cat_corregimiento cor on cor.id_corregimiento = esc.id_nivel_geo
                where esc.status = 1 ". $filtro1;
        //echo $sql; exit;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }

    public static function datosEscenario($idEscenario) {
        $conn = new Connection();
        $conn->initConn();
        
        $sql = "select distinct esc.id_escenario, esc.id_n1, eve.nombre_evento, eve.cie_10_1,
                esc.tipo_algoritmo, esc.nivel_geo,
                (case when esc.nivel_geo = 2 then pro.id_provincia 
                when esc.nivel_geo = 3 then concat(reg.id_region,'##',reg.id_provincia) 
                when esc.nivel_geo = 4 then concat(dis.id_distrito,'##',dis.id_region,'##',dis.id_provincia)
                when esc.nivel_geo = 5 then concat(cor.id_corregimiento,'##',cor.id_distrito,'##',(select concat(dis.id_region,'##',dis.id_provincia) from cat_distrito dis where dis.id_distrito = cor.id_distrito ) ) else 0 end) as id_nivel_geo,
                esc.tipo_alerta, esc.dia_alerta, esc.mensaje, esc.nombre_crear, esc.fecha_crear
                from mat_escenario esc
                inner join cat_evento eve on eve.id_evento = esc.id_n1
                left join tbl_mat_diagnostico mat on mat.id_diagnostico = esc.id_n1
                left join cat_provincia pro on pro.id_provincia = esc.id_nivel_geo
                left join cat_region_salud reg on reg.id_region = esc.id_nivel_geo
                left join cat_distrito dis on dis.id_distrito = esc.id_nivel_geo
                left join cat_corregimiento cor on cor.id_corregimiento = esc.id_nivel_geo
                where esc.status = 1 and id_escenario = ".$idEscenario;
        //echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data;
    }
    
    public static function buscarGruposRelacionados($idForm) {
        if (isset($idForm)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = "";
            $filtro = " AND id_escenario=" . $idForm . "";
            $sql = "select * from mat_escenario_grupo_contacto mat
                    inner join mat_grupo_contacto gru on gru.id_grupo_contacto = mat.id_grupo_contacto
                    WHERE 1 ". $filtro;
            //echo $sql;  exit;
             $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;
    }
    
    // Contactos
    public static function buscarContacto($config) {
        $conn = new Connection();
        $conn->initConn();
        $filtro1 = "";
        $sql = "select * from mat_contacto where 1";
        if (isset($config["filtro"])) {
            if ($config["filtro"]!=""){
            $filtro1 = " AND (nombres LIKE '%".$config["filtro"]."%' OR apellidos LIKE '%".$config["filtro"]."%' OR email LIKE '%" . $config["filtro"] . "%')";
            }
        } 
        $sql .=  $filtro1 . ' order by id_contacto desc';
        if (isset($config["inicio"])) {
            $sql.= " limit " . $config["inicio"] . "," . $config["paginado"];
        }
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
    public static function buscarContactoCantidad($config) {
        $conn = new Connection();
        $conn->initConn();
        $filtro1 = "";
        if (isset($config["filtro"])) {
            if ($config["filtro"]!=""){
            $filtro1 = " AND (nombres LIKE '%".$config["filtro"]."%' OR apellidos LIKE '%".$config["filtro"]."%' OR email LIKE '%" . $config["filtro"] . "%')";
            }
        } 
        $sql = "select count(*) as total from mat_contacto
                where 1 ". $filtro1;
        //echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }
    
    public static function datosContacto($idContacto) {
        $conn = new Connection();
        $conn->initConn();
        
        $sql = "select * from mat_contacto
                where id_contacto = ".$idContacto;
        //echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data;
    }
    
    public static function getGruposContacto(){
        $sql = "select mat.id_grupo_contacto, mat.nombre_grupo_contacto, mat.status 
                from mat_grupo_contacto mat
                order by mat.nombre_grupo_contacto";
        $conn = new Connection();
    	$conn->initConn();
    	$conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetch();
    	$conn->closeConn();
    	return $data;
    }
    
    public static function totalGruposContacto(){
        $sql = "select count(*) as total from mat_grupo_contacto mat";
        $conn = new Connection();
    	$conn->initConn();
    	$conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetchOne();
    	$conn->closeConn();
    	return $data["total"];
    }
    
    public static function getContactos() {
        $conn = new Connection();
        $conn->initConn();
        $sql = "select * from mat_contacto where status = 1 order by id_contacto desc $limite";
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
    public static function totalContactos() {
        $conn = new Connection();
        $conn->initConn();
        $sql = "select count(*) as total from mat_contacto where status = 1";
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }
    
    public static function relContactosGrupo($idGrupo, $idContacto) {
        $conn = new Connection();
        $conn->initConn();
        $sql = "select count(*) as total from mat_contacto_grupo_contacto where id_contacto = $idContacto and id_grupo_contacto = $idGrupo";
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        if ($data["total"]==0)
            return false;
        else 
            return true;
    }
    
    
}