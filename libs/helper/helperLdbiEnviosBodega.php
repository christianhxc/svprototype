<?php

require_once('libs/Configuration.php');
require_once('libs/ConfigurationHospitalInfluenza.php');
require_once('libs/Connection.php');
require_once('libs/caus/clsCaus.php');
require_once('libs/helper/helperString.php');

class helperLdbiEnviosBodega {

    const from = "envio.*, requesicion.*, region.nombre_region";

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
        $sql = "select " . self::from . " from vac_LDBI_envio_bodega envio " .
                "inner join vac_LDBI_requesicion requesicion on envio.id_requesicion = requesicion.id_requesicion ".
                "left join cat_unidad_notificadora instalacion on requesicion.id_un = instalacion.id_un ".
                "left join cat_region_salud region on instalacion.id_region = region.id_region ".
                "WHERE envio.deleted = 0 ";

        $filtro = "";
        if ($config["filtro"] != "") {
            $filtro .= " AND (requesicion.no_requesicion LIKE '%" . $config["filtro"] . "%'" .
                " OR envio.no_envio LIKE '%" . $config["filtro"] . "%'" .
                " OR region.nombre_region LIKE '%" . $config["filtro"] . "%'";
            $filtro .= " OR EXISTS (select d.id_envio_bodega from vac_LDBI_inventario d WHERE d.id_envio_bodega = envio.id_envio and d.no_lote like '%".$config["filtro"]."%')";
            $filtro .= ")";
        }

        if ($config["status"] != "" && $config["status"] != "-1") $filtro .= " AND envio.status = '" . $config["status"] . "'";
        if ($config["anio"] != "" && $config["anio"] != "-1") $filtro .= " AND YEAR(envio.fh_envio) = '" . $config["anio"] . "'";

        $sql .= $filtro . ' order by fh_envio desc';

        if (!$read) {
            $sql .= " limit " . $config["inicio"] . "," . $config["paginado"];
            return $sql;
        }

        return $sql;
    }

}