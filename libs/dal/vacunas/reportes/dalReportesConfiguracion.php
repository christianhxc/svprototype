<?php
require_once('libs/dal/baseDal.php');
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperVacunas.php');
require_once('libs/caus/clsCaus.php');

class dalReportesConfiguracion extends baseDal {

    public static function Guardar($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $param = array();
        $param = self::GuardarTabla($conn, "vac_registro_diario_reporte_configuracion", $data["form"]);
        $id = $param['id'];
        $ok = $param['ok'];

        if ($id != null && $ok){
            foreach ($data["vacunas"] as &$vacuna) {
                $vacuna["id_configuracion"] = $id;
                $param = self::GuardarTabla($conn, "vac_registro_diario_reporte_configuracion_vacuna", $vacuna);
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
        $filtro["id_configuracion"] = $data["form"]["id_configuracion"];
        $campos = $data["form"];
        unset($campos["id_configuracion"]);

        $param = self::ActualizarTabla($conn, "vac_registro_diario_reporte_configuracion", $campos, $filtro, $campos);
        $ok = $param['ok'];

        $param = self::GuardarBitacora($conn, "2", "vac_registro_diario_reporte_configuracion");
        $ok = $param['ok'];

        $param = self::BorrarTabla($conn, "vac_registro_diario_reporte_configuracion_vacuna", $filtro);
        $ok = $param['ok'];

        if ($ok){
            foreach ($data["vacunas"] as &$vacuna) {
                $vacuna["id_configuracion"] = $filtro["id_configuracion"];
                $param = self::GuardarTabla($conn, "vac_registro_diario_reporte_configuracion_vacuna", $vacuna);
                $ok = $param['ok'];
                if (!$ok) break;
            }

            $param = self::GuardarBitacora($conn, "2", "vac_registro_diario_reporte_configuracion_vacuna");
            $ok = $param['ok'];
        } else {
            $ok = false;
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
        $filtro["id_configuracion"] = $data["id"];

        $param = self::BorrarTabla($conn, "vac_registro_diario_reporte_configuracion", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];

        $param = self::GuardarBitacora($conn, "3", "vac_registro_diario_reporte_configuracion");

        if ($ok){
            $param = self::BorrarTabla($conn, "vac_registro_diario_reporte_configuracion_vacuna", $filtro);
            $id = $param['id'];
            $ok = $param['ok'];
            //print_r($param);

            $param = self::GuardarBitacora($conn, "3", "vac_registro_diario_reporte_configuracion_vacuna");
        }

        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);
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

        $sql = "select * from vac_registro_diario_reporte_configuracion where deleted = 0 order by nombre ";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
} 