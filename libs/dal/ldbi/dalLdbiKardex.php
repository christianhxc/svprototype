<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperUceti.php');
require_once ('libs/caus/clsCaus.php');

class dalLdbiKardex {

    public static function getInventario($config){
        $conn = new Connection();
        $conn->initConn();

        $sql = self::construirQuery($config);

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function construirQuery($config) {
        $sql = "SELECT DATE_FORMAT(i.fh_inventario,'%d/%m/%Y') as fh_inventario, e.no_envio as no_documento, ".
            "eb.no_envio, n.no_nota, i.no_lote, cast(i.cantidad * i.movimiento as SIGNED) as unidades, i.costo_unitario, rd.numero_identificacion as no_registro_diario  " .
            "FROM vac_LDBI_inventario i ".
            "LEFT JOIN vac_LDBI_envio e ON i.id_envio = e.id_envio ".
            "LEFT JOIN vac_LDBI_envio_bodega eb ON i.id_envio_bodega = eb.id_envio ".
            "LEFT JOIN vac_LDBI_nota n ON i.id_nota = n.id_nota ".
            "LEFT JOIN vac_registro_diario_dosis rdd ON i.id_registro_diario_dosis = rdd.id_registro_diario_dosis ".
            "LEFT JOIN vac_registro_diario rd ON rdd.id_vac_registro_diario = rd.id_vac_registro_diario ".
            "WHERE i.deleted = 0 and fh_inventario >= '".$config['fh_inicio']."' and fh_inventario <= '".$config['fh_fin']."' ";

        $filtro = "";
        $filtro .= " AND i.bodega_central = '" . $config["bodega_central"] . "'";
        $filtro .= " AND i.id_region = '" . $config["id_region"] . "'";
        $filtro .= " AND i.id_un = '" . $config["id_un"] . "'";
        $filtro .= " AND i.id_insumo = '" . $config["id_insumo"] . "'";
        if ($config["no_lote"] != "") $filtro .= " AND i.no_lote = '" . $config["no_lote"] . "'";

        $sql .= $filtro . ' order by i.fh_inventario';

        return $sql;
    }
}