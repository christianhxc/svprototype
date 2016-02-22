<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperUceti.php');
require_once ('libs/caus/clsCaus.php');

class dalReportes {

    public static function reporteBalanceExistenciasSaldos($config){
        $conn = new Connection();
        $conn->initConn();

        $sql = "
SELECT
    v.codigo_insumo,
    v.nombre_insumo,
    v.unidad_presentacion,
    SUM(IF(i.movimiento > 0, i.cantidad, 0)) as entradas,
     SUM(IF(i.movimiento < 0, i.cantidad, 0)) as salidas,
     SUM(CAST(i.cantidad * i.movimiento as SIGNED)) as saldo
FROM
    vac_LDBI_inventario i
        LEFT JOIN
    vac_LDBI_envio e ON i.id_envio = e.id_envio
        LEFT JOIN
    vac_LDBI_envio_bodega eb ON i.id_envio_bodega = eb.id_envio
        LEFT JOIN
    vac_LDBI_nota n ON i.id_nota = n.id_nota
        LEFT JOIN
    cat_insumos_LDBI v ON i.id_insumo = v.id_insumo
WHERE i.deleted = 0 ";
        $sql = self::extraQuery($config, $sql);
        $sql = self::fechaQuery($config, $sql);
        $sql .= ' GROUP BY i.id_insumo ORDER BY v.nombre_insumo';

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function reporteListadoDeTransacciones($config){
        $conn = new Connection();
        $conn->initConn();

        $sql = "
SELECT
    DATE_FORMAT(i.fh_inventario, '%d/%m/%Y') as fh_inventario,
    v.codigo_insumo,
    v.nombre_insumo,
    v.unidad_presentacion,
    e.no_envio as no_documento,
    eb.no_envio,
    n.no_nota,
    rd.numero_identificacion as no_registro_diario,
    i.no_lote,
    IF(i.movimiento > 0, i.cantidad, 0) as entradas,
     IF(i.movimiento < 0, i.cantidad, 0) as salidas,
    i.costo_unitario
FROM
    vac_LDBI_inventario i
    LEFT JOIN vac_LDBI_envio e ON i.id_envio = e.id_envio
    LEFT JOIN vac_LDBI_envio_bodega eb ON i.id_envio_bodega = eb.id_envio
    LEFT JOIN vac_LDBI_nota n ON i.id_nota = n.id_nota
    LEFT JOIN cat_insumos_LDBI v ON i.id_insumo = v.id_insumo
    LEFT JOIN vac_registro_diario_dosis rdd ON i.id_registro_diario_dosis = rdd.id_registro_diario_dosis
    LEFT JOIN vac_registro_diario rd ON rdd.id_vac_registro_diario = rd.id_vac_registro_diario
WHERE i.deleted = 0 ";
        $sql = self::extraQuery($config, $sql);
        $sql = self::fechaQuery($config, $sql);
        $sql .= ' ORDER BY i.fh_inventario';

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function reporteListadoVacunasSaldoMinimo($config){
        $conn = new Connection();
        $conn->initConn();

        $sql = "
SELECT
    v.codigo_insumo,
    v.nombre_insumo,
    v.unidad_presentacion,
     v.saldo_minimo,
     SUM(CAST(i.cantidad * i.movimiento as SIGNED)) as saldo
FROM
    vac_LDBI_inventario i
        LEFT JOIN
    vac_LDBI_envio e ON i.id_envio = e.id_envio
        LEFT JOIN
    vac_LDBI_envio_bodega eb ON i.id_envio_bodega = eb.id_envio
        LEFT JOIN
    vac_LDBI_nota n ON i.id_nota = n.id_nota
        LEFT JOIN
    cat_insumos_LDBI v ON i.id_insumo = v.id_insumo
WHERE i.deleted = 0 and v.saldo_minimo > 0";
        $sql = self::extraQuery($config, $sql);
        $sql = self::fechaQuery($config, $sql);
        $sql .= ' GROUP BY i.id_insumo';
        $sql .= ' HAVING SUM(CAST(i.cantidad * i.movimiento as SIGNED)) <= v.saldo_minimo';
        $sql .= ' ORDER BY v.nombre_insumo';

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function reporteExportacionVariables($config){
        $conn = new Connection();
        $conn->initConn();

        $sql = "
SELECT
    i.id,
	i.fh_inventario,

    i.id_envio,
	b.no_envio,
	b.id_proveedor,
	p.nombre_proveedor,
	b.fh_envio,
	b.fh_ingreso as fh_ingreso_envio,
	b.nombre_registra as nombre_registra_envio,
	b.no_referencia as no_documento_original,

    i.id_envio_bodega,
	eb.no_envio,
	eb.id_requesicion,
	r.no_requesicion,
	r.fh_requesicion,
	r.fh_ingreso as fh_ingreso_requesicion,
	r.nombre_registra as nombre_registra_requesicion,
	eb.fh_envio as fh_envio_bodega,
	eb.fh_despacho,
	eb.fh_ingreso as fh_ingreso_envio_bodega,
	eb.nombre_registra as nombre_registra_envio_bodega,

    i.id_nota,
	n.no_nota,
	n.fh_nota,
	n.fh_ingreso as fh_ingres_nota,
	n.nombre_registra as nombre_registra_nota,
	n.id_razon,
	ra.descripcion as nombre_razon,

    i.id_insumo,
	v.nombre_insumo,
	v.unidad_presentacion,
	v.codigo_insumo,
	v.saldo_minimo,
	v.saldo_maximo,

    i.cantidad,
    i.fh_vencimiento,
    i.no_lote,
    -- i.no_lote_original,
    i.costo_unitario,
    IF(i.movimiento = 1,'ENTRADA','SALIDA') as movimiento,
    i.fh_ingreso as fh_ingreso_inventario,
    i.comentario,
    IF(i.bodega_central = 1,'SI','NO') as bodega_central,

    i.id_region,
	reg.nombre_region,
	reg.cod_ref_minsa as cod_ref_minsa_region,

    i.id_un,
	u.nombre_un,
	u.cod_ref_minsa as cod_ref_minsa_unidad,

    i.id_registro_diario_dosis,
	rd.numero_identificacion as numero_registro_diario

FROM vac_LDBI_inventario i
LEFT JOIN vac_LDBI_envio b ON b.id_envio = i.id_envio AND b.status = 1
LEFT JOIN cat_proveedor_LDBI p ON p.id_proveedor = b.id_proveedor AND p.status = 1
LEFT JOIN vac_LDBI_envio_bodega eb ON eb.id_envio = i.id_envio_bodega AND eb.status = 1
LEFT JOIN vac_LDBI_requesicion r ON r.id_requesicion = eb.id_requesicion AND r.status = 1
LEFT JOIN vac_LDBI_nota n ON n.id_nota = i.id_nota AND n.status = 1
LEFT JOIN cat_razon_LDBI ra ON ra.id_razon = n.id_razon
LEFT JOIN cat_insumos_LDBI v ON v.id_insumo = i.id_insumo
LEFT JOIN cat_region_salud reg ON reg.id_region = i.id_region
LEFT JOIN cat_unidad_notificadora u ON u.id_un = i.id_un
LEFT JOIN vac_registro_diario_dosis rdd ON rdd.id_registro_diario_dosis = i.id_registro_diario_dosis
LEFT JOIN vac_registro_diario rd ON rd.id_vac_registro_diario = rdd.id_vac_registro_diario
WHERE i.deleted = 0 ";
        $sql = self::extraQuery($config, $sql);
        $sql = self::fechaQuery($config, $sql);
        $sql .= ' ORDER BY i.id';

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function reporteListadoVacunasVencer($config){
        $conn = new Connection();
        $conn->initConn();

        $sql = "
SELECT
    DATE_FORMAT(i.fh_vencimiento, '%d/%m/%Y') as fh_vencimiento,
     v.codigo_insumo,
    v.nombre_insumo,
    v.unidad_presentacion,
     i.no_lote
FROM
    vac_LDBI_inventario i
        LEFT JOIN
    vac_LDBI_envio e ON i.id_envio = e.id_envio
        LEFT JOIN
    vac_LDBI_envio_bodega eb ON i.id_envio_bodega = eb.id_envio
        LEFT JOIN
    vac_LDBI_nota n ON i.id_nota = n.id_nota
        LEFT JOIN
    cat_insumos_LDBI v ON i.id_insumo = v.id_insumo
WHERE i.deleted = 0 and i.fh_vencimiento IS NOT NULL";
        $sql = self::extraQuery($config, $sql);
        $sql .= " and i.fh_vencimiento between '".$config["fh_inicio"]."' and '".$config["fh_fin"]."'";
        $sql .= ' GROUP BY i.id_insumo,i.no_lote,i.fh_vencimiento ORDER BY v.nombre_insumo';

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function extraQuery($config, $sql) {
        $filtro = "";
        $filtro .= " AND i.bodega_central = '" . $config["bodega_central"] . "'";
        $filtro .= " AND i.id_region = '" . $config["id_region"] . "'";
        $filtro .= " AND i.id_un = '" . $config["id_un"] . "'";
        if ($config["id_insumo"] != "" && $config["id_insumo"] != "0") $filtro .= " AND i.id_insumo = '" . $config["id_insumo"] . "'";
        if ($config["no_lote"] != "") $filtro .= " AND i.no_lote = '" . $config["no_lote"] . "'";

        return $sql . $filtro;
    }

    public static function fechaQuery($config, $sql) {
        $filtro = "";
        $filtro .= " and i.fh_inventario between '".$config["fh_inicio"]."' and '".$config["fh_fin"]."'";

        return $sql . $filtro;
    }
}