<?php

require_once('libs/dal/baseDal.php');
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperVacunas.php');
require_once('libs/caus/clsCaus.php');

class dalNotificacion extends baseDal {

    public static function Guardar($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $param = array();

        if ($data["form"]["fecha_envio"] != "")
            $data["form"]["fecha_envio"] = helperString::toDate($data["form"]["fecha_envio"]);

        $param = self::GuardarTabla($conn, "vac_notificacion", $data["form"]);
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

    public static function Actualizar($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $param = array();
        if ($data["form"]["fecha_envio"] != "")
            $data["form"]["fecha_envio"] = helperString::toDate($data["form"]["fecha_envio"]);

        $filtro["id_notificacion"] = $data["form"]["id_notificacion"];
        $campos = $data["form"];
        unset($campos["id_notificacion"]);

        $param = self::ActualizarTabla($conn, "vac_notificacion", $campos, $filtro, $campos);
        $ok = $param['ok'];

        $param = self::GuardarBitacora($conn, "2", "vac_notificacion");
        $ok = $param['ok'];

        if ($ok)
            $conn->commit();
        else {
            $conn->rollback();
            $id = -1;
        }

        $conn->closeConn();
        return 1;
    }

    public static function Borrar($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $param = array();

        $filtro = array();
        $filtro["id_notificacion"] = $data['id'];

        $param = self::BorrarTabla($conn, "vac_notificacion", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];

        $param = self::GuardarBitacora($conn, "3", "vac_notificacion");
        $id = $param['id'];
        $ok = $param['ok'];

        if ($ok)
            $conn->commit();
        else {
            $conn->rollback();
            $id = -1;
        }

        $conn->closeConn();
        if (!$ok)
            return 2;
        return 1;
    }

    public static function Leer(){
        $conn = new Connection();
        $conn->initConn();

        $sql = "select n.id_notificacion, n.nombre, nt.nombre as tipo, ng.nombre as grupo, nc.nombre as contacto, n.anticipacion_dias, n.fecha_envio, n.status
                from vac_notificacion n
                left join vac_notificacion_tipo nt on nt.id_notificacion_tipo = n.id_notificacion_tipo
                left join vac_notificacion_grupo ng on ng.id_notificacion_grupo = n.id_notificacion_grupo
                left join vac_notificacion_contacto nc on nc.id_notificacion_contacto = n.id_notificacion_contacto
                where n.deleted = 0 order by n.id_notificacion desc ";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function LeerPorId($id){
        $conn = new Connection();
        $conn->initConn();

        $sql = "select n.id_notificacion, n.id_notificacion_contacto, n.id_notificacion_grupo, n.id_insumo, n.id_vacuna, n.id_notificacion_tipo,
                        n.no_lote, DATE_FORMAT(n.fecha_envio,'%d/%m/%Y') as fecha_envio, n.mensaje, n.anticipacion_dias, n.nombre, n.status,
                        nt.nombre as tipo, ng.nombre as grupo, nc.nombre as contacto,
                        CONCAT('(', ni.codigo_insumo, ') ', ni.nombre_insumo, ' - ', ni.unidad_presentacion) as insumo,
                        CONCAT('(', nv.codigo_vacuna, ') ', nv.nombre_vacuna) as vacuna
                from vac_notificacion n
                left join vac_notificacion_tipo nt on nt.id_notificacion_tipo = n.id_notificacion_tipo
                left join vac_notificacion_grupo ng on ng.id_notificacion_grupo = n.id_notificacion_grupo
                left join vac_notificacion_contacto nc on nc.id_notificacion_contacto = n.id_notificacion_contacto
                left join cat_insumos_LDBI ni on ni.id_insumo = n.id_insumo
                left join cat_vacuna nv on nv.id_vacuna = n.id_vacuna
                where n.id_notificacion = '".$id."' and n.deleted = 0 order by n.id_notificacion desc ";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
}