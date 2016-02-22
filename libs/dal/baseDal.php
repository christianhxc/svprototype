<?php
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperVacunas.php');
require_once('libs/caus/clsCaus.php');

class baseDal {
    public static function GuardarTabla($conn, $tabla, $objeto) {
        $ok = true;

        $now = new DateTime(null, new DateTimeZone("America/Panama"));
        $change['fh_modifica'] = $now->format('Y-m-d H:i:s');
        $change['codigoglobal'] = uniqid('', true);
        $objeto = array_merge($change,$objeto);

        $sql = Actions::AgregarQuery($tabla, $objeto);
        //echo "<br/><br/>".$sql."<br/><br/>";
        $conn->prepare($sql);
        $conn->params($objeto);
        $conn->execute() ? null : $ok = false;
        $error = $conn->getError();
        $conn->closeStmt();
        $id = $conn->getID();
        $param = array();
        if (!$ok) {
            $conn->rollback();
            $conn->closeConn();
            echo "<br/>Error: ".$error;
            return "SQL:" . $sql . "<br/>Error" . $error;
            exit;
        }
        $param['id'] = $id;
        $param['ok'] = $ok;
        return $param;
    }

    public static function GuardarBitacora($conn, $accion, $tabla) {
        $ok = true;
        // BITACORA
        $bitacora = array();
        $bitacora["usuid"] = clsCaus::obtenerID();
        $bitacora["fecha"] = date("Y-m-d H:i:s");
        $bitacora["accion"] = $accion;
        $bitacora["tabla"] = $tabla;

        $sql = Actions::AgregarQuery("bitacora", $bitacora);
        $conn->prepare($sql);
        $conn->params($bitacora);
        $conn->execute() ? null : $ok = false;
        $error = $conn->getError();
        $conn->closeStmt();
        $id = $conn->getID();
        if (!$ok) {
            $conn->rollback();
            $conn->closeConn();
            echo "<br/>Error";
            return "SQL:" . $sql . "<br/>Error" . $error;
            exit;
        }
        $param = array();
        $param['id'] = $id;
        $param['ok'] = $ok;
        return $param;
    }

    public static function ActualizarTabla($conn, $tabla, $objeto, $filtro, $total) {
        $ok = true;

        $now = new DateTime(null, new DateTimeZone("America/Panama"));
        $change['fh_modifica'] = $now->format('Y-m-d H:i:s');
        $objeto = array_merge($change,$objeto);
        $total = array_merge($change,$total);

        $sql = Actions::ActualizarQuery($tabla, $objeto, $filtro);
        $conn->prepare($sql);
        $conn->params(array_merge($total,$filtro));

        $conn->execute() ? null : $ok = false;
        $error = $conn->getError();
        $conn->closeStmt();
        $id = $conn->getID();

//        echo "Error: ".$error." y ID:".$id;
        if (!$ok) {
            $conn->rollback();
            $conn->closeConn();
            echo "<br/>Error";
            return "SQL:" . $sql . "<br/>Error" . $error;
        }

        $param = array();
        $param['id'] = $id;
        $param['ok'] = $ok;
        return $param;
    }

    public static function BorrarTabla($conn, $tabla, $filtro) {
        $objeto['deleted'] = 1;
        $total = array_merge($objeto,$filtro);
        return self::ActualizarTabla($conn, $tabla, $objeto, $filtro, $total);
    }
} 