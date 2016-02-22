<?php

require_once('libs/Configuration.php');
require_once('libs/ConfigurationHospitalInfluenza.php');
require_once('libs/Connection.php');
require_once('libs/caus/clsCaus.php');
require_once('libs/helper/helperString.php');

class helperLdbiNotas {

    const from = "envio.*, region.nombre_region";

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
//        echo $sql; exit;
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
        $sql = "select " . self::from . " from vac_LDBI_nota envio " .
                "left join cat_unidad_notificadora instalacion on envio.id_un = instalacion.id_un ".
                "left join cat_region_salud region on envio.id_region = region.id_region ".
                "WHERE envio.deleted = 0 ";

        $filtro = "";
        if ($config["filtro"] != "") {
            $filtro .= " AND (envio.no_nota LIKE '%" . $config["filtro"] . "%'" .
                " OR region.nombre_region LIKE '%" . $config["filtro"] . "%'";
            $filtro .= " OR EXISTS (select d.id_nota from vac_LDBI_nota_detalle d WHERE d.id_nota = envio.id_nota and d.no_lote like '%".$config["filtro"]."%')";
            $filtro .= ")";
        }

        if ($config["anio"] != "" && $config["anio"] != "-1") $filtro .= " AND YEAR(envio.fh_nota) = '" . $config["anio"] . "'";

        $sql .= $filtro . ' order by fh_nota desc';

        if (!$read) {
            $sql .= " limit " . $config["inicio"] . "," . $config["paginado"];
            return $sql;
        }

        return $sql;
    }

}