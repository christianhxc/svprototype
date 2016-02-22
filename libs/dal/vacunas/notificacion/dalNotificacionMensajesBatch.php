<?php

require_once('libs/dal/baseDal.php');
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperVacunas.php');
require_once('libs/caus/clsCaus.php');

class dalNotificacionMensajesBatch extends baseDal {

    private static function Sql(){
        $now = new DateTime(null, new DateTimeZone("America/Panama"));
        $fecha = $now->format('Y-m-d');
        $fecha_hora = $now->format('Y-m-d H:i:s');

        $sql = "SELECT n.id_notificacion, n.nombre, n.mensaje, c.email, c.telefono, c.id_notificacion_contacto FROM vac_notificacion n
                INNER JOIN vac_notificacion_contacto c on c.id_notificacion_contacto = n.id_notificacion_contacto
                WHERE n.status = 1 AND n.deleted = 0 AND n.id_notificacion_tipo = 4
                AND DATE_FORMAT(n.fecha_envio,'%Y-%m-%d') = '".$fecha."'
                AND (n.ultimo_envio IS NULL OR TIMESTAMPDIFF(HOUR, n.ultimo_envio, '".$fecha_hora."') >= 24)
                UNION
                SELECT n.id_notificacion, n.nombre, n.mensaje, gc.email, gc.telefono, gc.id_notificacion_contacto FROM vac_notificacion n
                INNER JOIN vac_notificacion_grupo g on g.id_notificacion_grupo = n.id_notificacion_grupo
                INNER JOIN vac_notificacion_grupo_detalle gd on gd.id_notificacion_grupo = g.id_notificacion_grupo
                INNER JOIN vac_notificacion_contacto gc on gc.id_notificacion_contacto = gd.id_notificacion_contacto
                WHERE n.status = 1 AND n.deleted = 0 AND n.id_notificacion_tipo = 4 AND gd.deleted = 0
                AND DATE_FORMAT(n.fecha_envio,'%Y-%m-%d') = '".$fecha."'
                AND (n.ultimo_envio IS NULL OR TIMESTAMPDIFF(HOUR, n.ultimo_envio, '".$fecha_hora."') >= 24)";
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