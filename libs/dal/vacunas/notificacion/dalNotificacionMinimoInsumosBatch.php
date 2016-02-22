<?php

require_once('libs/dal/baseDal.php');
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperVacunas.php');
require_once('libs/caus/clsCaus.php');

class dalNotificacionMinimoInsumosBatch extends baseDal {

    private static function Sql(){
        $sql = "SELECT
                    n.id_notificacion,
                    v.id_insumo,
                    v.codigo_insumo,
                    v.nombre_insumo,
                    v.unidad_presentacion,
                    v.saldo_minimo,
                    SUM(CAST(i.cantidad * i.movimiento as SIGNED)) as saldo,
                    c.email, c.telefono, t.mensaje, c.id_notificacion_contacto
                FROM vac_LDBI_inventario i
                INNER JOIN cat_insumos_LDBI v ON i.id_insumo = v.id_insumo
                INNER JOIN vac_notificacion n ON n.id_insumo = i.id_insumo
                INNER JOIN vac_notificacion_contacto c on c.id_notificacion_contacto = n.id_notificacion_contacto
                INNER JOIN vac_notificacion_tipo t on t.id_notificacion_tipo = n.id_notificacion_tipo
                WHERE i.deleted = 0 and v.saldo_minimo > 0 AND v.deleted = 0
                AND n.status = 1 AND n.deleted = 0 AND n.id_notificacion_tipo = 2
                GROUP BY i.id_insumo
                HAVING SUM(CAST(i.cantidad * i.movimiento as SIGNED)) <= v.saldo_minimo
                UNION
                SELECT n.id_notificacion, s.*, gc.email, gc.telefono, t.mensaje, gc.id_notificacion_contacto
                FROM (
                SELECT
                    v.id_insumo,
                    v.codigo_insumo,
                    v.nombre_insumo,
                    v.unidad_presentacion,
                    v.saldo_minimo,
                    SUM(CAST(i.cantidad * i.movimiento as SIGNED)) as saldo
                FROM vac_LDBI_inventario i
                LEFT JOIN vac_LDBI_envio e ON i.id_envio = e.id_envio
                LEFT JOIN vac_LDBI_envio_bodega eb ON i.id_envio_bodega = eb.id_envio
                LEFT JOIN vac_LDBI_nota n ON i.id_nota = n.id_nota
                LEFT JOIN cat_insumos_LDBI v ON i.id_insumo = v.id_insumo
                WHERE i.deleted = 0 and v.saldo_minimo > 0 AND v.deleted = 0
                GROUP BY i.id_insumo
                HAVING SUM(CAST(i.cantidad * i.movimiento as SIGNED)) <= v.saldo_minimo
                ORDER BY v.nombre_insumo) s
                INNER JOIN vac_notificacion n ON n.id_insumo = s.id_insumo
                INNER JOIN vac_notificacion_tipo t on t.id_notificacion_tipo = n.id_notificacion_tipo
                INNER JOIN vac_notificacion_grupo g on g.id_notificacion_grupo = n.id_notificacion_grupo
                INNER JOIN vac_notificacion_grupo_detalle gd on gd.id_notificacion_grupo = g.id_notificacion_grupo
                INNER JOIN vac_notificacion_contacto gc on gc.id_notificacion_contacto = gd.id_notificacion_contacto
                WHERE n.status = 1 AND n.deleted = 0 AND n.id_notificacion_tipo = 2
                GROUP BY s.id_insumo, gc.email";
        return $sql;
    }

    public static function Total(){
        $conn = new Connection();
        $conn->initConn();

        $sql = "SELECT COUNT(*) as total FROM (";
        $sql .= self::Sql();
        $sql .= ") x";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data[0];
    }

    public static function Notificaciones($limit,$count){
        $conn = new Connection();
        $conn->initConn();

        $sql = self::Sql();
        $sql .= " LIMIT ".$limit.",".$count;

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function Log($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $param = array();
        $param = self::GuardarTabla($conn, "vac_notificacion_log", $data);
        $id = $param['id'];
        $ok = $param['ok'];

        $filtro["id_notificacion"] = $data["id_notificacion"];
        $campos["ultimo_envio"] = $data["fecha_envio"];

        $param = self::ActualizarTabla($conn, "vac_notificacion", $campos, $filtro, $campos);
        $id = $param['id'];
        $ok = $param['ok'];

        if ($ok)
            $conn->commit();
        else {
            $conn->rollback();
            $id = -1;
        }

        $conn->closeConn();
        return $id;
    }
}