<?php

require_once('libs/dal/baseDal.php');
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperVacunas.php');
require_once('libs/caus/clsCaus.php');

class dalNotificacionContacto extends baseDal {

    public static function Guardar($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $param = array();
        $param = self::GuardarTabla($conn, "vac_notificacion_contacto", $data["form"]);
        $id = $param['id'];
        $ok = $param['ok'];

        $param = self::GuardarBitacora($conn, "1", "vac_notificacion_contacto");
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
        $filtro["id_notificacion_contacto"] = $data["form"]["id_notificacion_contacto"];
        $campos = $data["form"];
        unset($campos["id_notificacion_contacto"]);
        $param = self::ActualizarTabla($conn, "vac_notificacion_contacto", $campos, $filtro, $campos);
        $ok = $param['ok'];

        $param = self::GuardarBitacora($conn, "2", "vac_notificacion_contacto");
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
        $filtro["id_notificacion_contacto"] = $data['id'];

        $param = self::BorrarTabla($conn, "vac_notificacion_contacto", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];

        $param = self::GuardarBitacora($conn, "3", "vac_notificacion_contacto");
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

        $sql = "select id_notificacion_contacto, nombre, email, telefono
                from vac_notificacion_contacto
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

        $sql = "select id_notificacion_contacto, nombre, email, telefono, status
                from vac_notificacion_contacto
                where id_notificacion_contacto = '".$id."' and deleted = 0 order by nombre asc ";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
}