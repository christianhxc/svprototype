<?php

require_once('libs/dal/baseDal.php');
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperVacunas.php');
require_once('libs/caus/clsCaus.php');

class dalNotificacionGrupo extends baseDal {

    public static function Guardar($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $param = array();
        $param = self::GuardarTabla($conn, "vac_notificacion_grupo", $data["form"]);
        $id = $param['id'];
        $ok = $param['ok'];

        $param = self::GuardarBitacora($conn, "1", "vac_notificacion_grupo");
        $ok = $param['ok'];

        if ($ok){
            foreach ($data["contactos"] as $contacto){
                $detalle["id_notificacion_grupo"] = $id;
                $detalle["id_notificacion_contacto"] = $contacto["id_notificacion_contacto"];
                $param = self::GuardarTabla($conn, "vac_notificacion_grupo_detalle", $detalle);
                $ok = $param['ok'];
                if (!$ok) break;
            }
        }

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
        $filtro["id_notificacion_grupo"] = $data["form"]["id_notificacion_grupo"];
        $campos = $data["form"];
        unset($campos["id_notificacion_grupo"]);

        $param = self::ActualizarTabla($conn, "vac_notificacion_grupo", $campos, $filtro, $campos);
        $ok = $param['ok'];

        $param = self::GuardarBitacora($conn, "2", "vac_notificacion_grupo");
        $ok = $param['ok'];

        $param = self::BorrarTabla($conn, "vac_notificacion_grupo_detalle", $filtro);
        $ok = $param['ok'];

        if ($ok){
            foreach ($data["contactos"] as $contacto){
                $detalle["id_notificacion_grupo"] = $data["form"]["id_notificacion_grupo"];
                $detalle["id_notificacion_contacto"] = $contacto["id_notificacion_contacto"];
                $param = self::GuardarTabla($conn, "vac_notificacion_grupo_detalle", $detalle);
                $ok = $param['ok'];
                if (!$ok) break;
            }
        }

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
        $filtro["id_notificacion_grupo"] = $data['id'];

        $param = self::BorrarTabla($conn, "vac_notificacion_grupo", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];

        $param = self::GuardarBitacora($conn, "3", "vac_notificacion_grupo");
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

    public static function Leer($s){
        $conn = new Connection();
        $conn->initConn();

        $sql = "select id_notificacion_grupo, nombre
                from vac_notificacion_grupo
                where deleted = 0 ";
        if ($s != "") $sql .= "and nombre like '%".$s."%'";
        $sql .= "order by nombre asc ";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function LeerPorId($id){
        $conn = new Connection();
        $conn->initConn();

        $sql = "select id_notificacion_grupo, nombre, status
                from vac_notificacion_grupo
                where id_notificacion_grupo = '".$id."' and deleted = 0 order by nombre asc ";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        $data[0]["contactos"] = self::LeerDetalle($id);
        return $data;
    }

    private static function LeerDetalle($id){
        $conn = new Connection();
        $conn->initConn();

        $sql = "select ngd.id_notificacion_contacto, nc.nombre, nc.email, nc.telefono
                from vac_notificacion_grupo_detalle ngd
                inner join vac_notificacion_contacto nc on ngd.id_notificacion_contacto = nc.id_notificacion_contacto
                where ngd.id_notificacion_grupo = '".$id."' and ngd.deleted = 0 order by nc.nombre asc ";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
}