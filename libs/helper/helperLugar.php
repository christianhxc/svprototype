<?php

require_once('libs/Connection.php');
require_once('libs/Configuration.php');
require_once ('libs/caus/clsCaus.php');

class helperLugar {

    public static function getPaises() {
        $sql = "select id_pais as pais, nombre_pais as descripcionPais
				from cat_pais 
				order by id_pais";

        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    // Obtiene los provincia 
    public static function getProvincias($config) {

        // Filtrar los resultados
        $lista = clsCaus::obtenerUbicacion(ConfigurationCAUS::Provincia, $config["id_provincia"]);
        if (is_array($lista)) {
            $temporal = implode("','", $lista);
            if ($temporal != "") {
                $filtro .= " where id_provincia in ('" . $temporal . "')";
            }
        }
        $sql = "select id_provincia as provincia, nombre_provincia as descripcionProvincia
				from cat_provincia 
                                " . $filtro . " 
				order by id_provincia";
        //echo "sql: ".$sql;exit;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    
    // Obtiene los provincia 
    public static function getProvinciasUceti() {

        $sql = "select id_provincia as provincia, nombre_provincia as descripcionProvincia
				from cat_provincia 
                                order by id_provincia";

        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
	
    // Obtiene los provincia 
    public static function getProvinciastb($config) {

        $filtro = "";
        $sql = "select id_provincia as provincia, nombre_provincia as descripcionProvincia
				from cat_provincia 
                                " . $filtro . " 
				order by id_provincia";

        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    // Obtiene las áreas de salud
    public static function getRegionSalud($config) {
        // Filtrar los resultados
        $lista = clsCaus::obtenerUbicaciones(ConfigurationCAUS::Region, $config["data"]["individuo"]["idProvincia"]);

        if (is_array($lista)) {
            $temporal = implode("','", $lista);
            if ($temporal != "") {
                $filtro .= " and id_region in ('" . $temporal . "')";
            }
        }

        $sql = "select id_region as codigoRegion, id_provincia as codigoRegionProvincia, nombre_region as nombreRegion
                    from cat_region_salud
                    where id_provincia = " . $config["id_provincia"] . " 
                    " . $filtro . "
                    order by nombre_region";
        //echo $sql;exit;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    // Obtiene las áreas de salud
    public static function getRegionSaludPersona($idprovincia) {
        $sql = "select id_region as codigoRegion, id_provincia as codigoRegionProvincia, nombre_region as nombreRegion
                    from cat_region_salud
                    where id_provincia = " . $idprovincia . "
                    order by nombre_region";
        //echo $sql;exit;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getDistritoSaludPersona($idprovincia, $idregion) {
        $sql = "select id_distrito as codigoDistrito, id_provincia as codigoDistritoProvincia, nombre_distrito as nombreDistrito
                    from cat_distrito
                    where id_provincia = " . $idprovincia . " and
                    id_region = " . $idregion . "    
                    order by nombre_distrito";
        //echo $sql;exit;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getCorregimientoSaludPersona($distrito) {
        $sql = "select id_corregimiento as codigoCorregimiento, id_distrito as codigoCorregimientoDistrito, nombre_corregimiento as nombreCorregimiento
                    from cat_corregimiento
                    where id_distrito = " . $distrito . " 
                    order by nombre_corregimiento";
        //echo $sql;exit;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    // Obtiene los distritos según permisos (2do nivel cascada)
    public static function getDistritos($config) {
        // Filtrar los resultados
        $lista = clsCaus::obtenerUbicacion(ConfigurationCAUS::Distrito, $config["id_distrito"]);
        if (is_array($lista)) {
            $temporal = implode("','", $lista);
            if ($temporal != "") {
                $filtro .= "and id_distrito in ('" . $temporal . "')";
            }
        }

        $sql = "select id_distrito as codigoDistrito, nombre_distrito as nombreDistrito
                    from cat_distrito
                    where id_region = '" . $config["id_region"] . " '
                    " . $filtro . "
                    order by nombre_distrito";
        //echo $sql;exit;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    // Obtiene los corregimientos del distrito seleccionado
    public static function getCorregimientos($config) {
        // Filtrar los resultados
        $lista = clsCaus::obtenerUbicacion(ConfigurationCAUS::Corregimiento, $config["id_corregimiento"]);
        if (is_array($lista)) {
            $temporal = implode("','", $lista);
            if ($temporal != "") {
                $filtro .= "and id_corregimiento in ('" . $temporal . "')";
            }
        }

        $sql = "select id_corregimiento as corregimiento, nombre_corregimiento as descripcionCorregimiento
                            from cat_corregimiento
                            where id_distrito = '" . $config["id_distrito"] . " '
                            " . $filtro . "
                            order by nombre_corregimiento";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getUnidadNotificadoraNombre($id) {
        $conn = new Connection();
        $conn->initConn();

        $sql = "SELECT `nombre_un` FROM `cat_unidad_notificadora` WHERE `id_un` = " . $id;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["nombre_un"];
    }

    public static function getUnidadNotificadora($config) {
        // Filtrar los resultados
        //echo $config["idds"];exit;
        $lista = clsCaus::obtenerUbicacion(ConfigurationCAUS::UnidadNotificadora, $config["id_un"]);
        if (is_array($lista)) {
            $temporal = implode("','", $lista);
            if ($temporal != "") {
                $filtro .= "and id_un in ('" . $temporal . "')";
            }
        }

        $idOrganizacion = $_SESSION["user"]["organizacion"];
        if ($idOrganizacion != ConfigurationCAUS::orgCdc && $idOrganizacion != ConfigurationCAUS::orgMinsa) {
            $filtro .= " and idtipo_instalacion = " . $idOrganizacion;
        }

        $sql = "select id_un, nombre_un, idtipo_instalacion as tipo from cat_unidad_notificadora
                    where id_corregimiento =" . $config["id_corregimiento"] . "
                    and status = 1 " . $filtro . " order by nombre_un";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getTipoInstalacion() {
        $sql = "select idtipo_instalacion as codigoTipoInst, nombre as nombreTipoInst
				from tbl_tipo_instalacion
				order by nombre";
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
    public static function obtenerIdCorregimientos($disId){
        $data1=array();
        $cont=0;
        $sql = "SELECT id_corregimiento
                FROM cat_corregimiento
                WHERE id_distrito = ".$disId;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        foreach ($data as $resultado){
            $data1[$cont]=$resultado;
            $cont++;
        }
        return $data1;
    }
    
    public static function obtenerIdDistritos($regId){
        $data1=array();
        $cont=0;
        $sql = "SELECT id_distrito
                FROM cat_distrito
                WHERE id_region = ".$regId;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        foreach ($data as $resultado){
            $data1[$cont]=$resultado;
            $cont++;
        }
        return $data1;
    }
    public static function obtenerIdRegiones($proId){
        $data1=array();
        $cont=0;
        $sql = "SELECT id_region
                FROM cat_region
                WHERE id_provincia = ".$proId;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        foreach ($data as $resultado){
            $data1[$cont]=$resultado;
            $cont++;
        }
        return $data1;
    }

}
