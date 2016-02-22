<?php

require_once('libs/Configuration.php');
require_once('libs/ConfigurationHospitalInfluenza.php');
require_once('libs/Connection.php');
require_once('libs/caus/clsCaus.php');
require_once('libs/helper/helperString.php');

class helperLdbi {

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

        $sql = str_replace("*","count(*) as total", self::construirQuery($config, true));

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }

    public static function construirQuery($config, $read) {
        $sql = "select * from vac_LDBI_envio envio " .
                "left join cat_proveedor_LDBI proveedor on envio.id_proveedor = proveedor.id_proveedor ".
                "WHERE deleted = 0 ";

        $filtro = "";
        if ($config["filtro"] != "") {
            $filtro .= " AND (envio.no_envio LIKE '%" . $config["filtro"] . "%'" .
                " OR proveedor.nombre_proveedor LIKE '%" . $config["filtro"] . "%'" .
                " OR envio.no_referencia LIKE '%" . $config["filtro"] . "%'";
            $filtro .= " OR EXISTS (select d.id_envio from vac_LDBI_inventario d WHERE d.id_envio = envio.id_envio and d.no_lote like '%".$config["filtro"]."%')";
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