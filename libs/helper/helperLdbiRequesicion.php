<?php

require_once('libs/Configuration.php');
require_once('libs/ConfigurationHospitalInfluenza.php');
require_once('libs/Connection.php');
require_once('libs/caus/clsCaus.php');
require_once('libs/helper/helperString.php');

class helperLdbiRequesicion {

    const from = "requesicion.*, instalacion.nombre_un, region.nombre_region";

    // Obtiene el listado de uceti
    public static function buscar($config) {
        $conn = new Connection();
        $conn->initConn();
        $flag = 0;

        $read = false;
        foreach ($config as $valor) {
            if ($valor != "") $flag++;
        }

        $sql = self::construirQuery($config, $read);

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function buscarCantidad($config) {
        $conn = new Connection();
        $conn->initConn();

        $sql = str_replace(self::from,"count(*) as total", self::construirQuery($config, true));

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }

    public static function construirQuery($config, $read) {
        $sql = "select " . self::from . " from vac_LDBI_requesicion requesicion " .
                "left join cat_unidad_notificadora instalacion on requesicion.id_un = instalacion.id_un ".
                "left join cat_region_salud region on requesicion.id_region = region.id_region ".
                "WHERE requesicion.deleted = 0 ";

        $filtro = "";
        if ($config["filtro"] != "") {
            $filtro .= " AND (requesicion.no_requesicion LIKE '%" . $config["filtro"] . "%'" .
                " OR instalacion.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                ")";
        }

        if ($config["status"] != "" && $config["status"] != "-1") $filtro .= " AND requesicion.status = '" . $config["status"] . "'";
        if ($config["provincia"] != "" && $config["provincia"] != "-1") $filtro .= " AND region.id_provincia = '" . $config["provincia"] . "'";
        if ($config["region"] != "" && $config["region"] != "-1") $filtro .= " AND region.id_region = '" . $config["region"] . "'";
        if ($config["fecha_inicio"] != "") $filtro .= " AND requesicion.fh_requesicion >= '" . helperString::toDate($config["fecha_inicio"]) . "'";
        if ($config["fecha_final"] != "") $filtro .= " AND requesicion.fh_requesicion <= '" . helperString::toDate($config["fecha_final"]) . "'";

        $sql .= $filtro . ' order by fh_requesicion desc';

        if (!$read) {
            $sql .= " limit " . $config["inicio"] . "," . $config["paginado"];
            return $sql;
        }

        return $sql;
    }

}