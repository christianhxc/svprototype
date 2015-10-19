<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperEno.php');
require_once('libs/caus/clsCaus.php');

class dalEno{

    private $search;
    private $filtroUbicaciones;

    public function __construct($search) {
        $this->search = $search;

        // Filtrar los resultados de búsquedas por permisos de procedencia
        // según división sanitaria del país            
        $lista = clsCaus::obtenerUbicacionesCascada();
        if (is_array($lista)) {
            foreach ($lista as $elemento) {
                $temporal = "";
//                        if ($elemento[ConfigurationCAUS::AreaSalud] != "")
//                            $temporal .= "muestra.idas = '".$elemento[ConfigurationCAUS::AreaSalud]."' ";
//
//                        if ($elemento[ConfigurationCAUS::DistritoSalud] != "")
//                            $temporal .= ($temporal != '' ? "and " : "")."muestra.idds = '".$elemento[ConfigurationCAUS::DistritoSalud]."' ";

                if ($elemento[ConfigurationCAUS::ServicioSalud] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.idts = '" . $elemento[ConfigurationCAUS::ServicioSalud] . "' ";

                $this->filtroUbicaciones .= ($this->filtroUbicaciones != '' ? "or " : "") . "(" . $temporal . ") ";
            }
        }

        if ($this->filtroUbicaciones != "")
            $this->filtroUbicaciones = "and (" . $this->filtroUbicaciones . ")";
    }    
    
    public static function GuardarEno($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $eno = helperEno::dataEnoForm($data);
        //print_r($eno);exit;
        $yaExisteEno = helperEno::yaExisteEno($eno);
        if ($yaExisteEno == 0) {
            $param = array();
            $param = dalEno::GuardarTabla($conn, "eno_encabezado", $eno);
            $idFormEno = $param['id'];
            $param = dalEno::GuardarBitacora($conn, "1", "eno_encabezado");
            if ($ok)
                $conn->commit();
            else {
                $conn->rollback();
                $id = -1;
            }
            $conn->closeConn();
            return "1-".$idFormEno;
        } else {
            $conn->closeConn();
            return 2;
        }
    }

    public static function ActualizarEno($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $eno = array();
        $eno = helperEno::dataEnoForm($data);
//        echo $data["factores"]["globalFactorRiesgoRelacionados"];
//        print_r($factores);
//        exit;
        
        $param = array();
        
        $totalFiltroEno = helperEno::dataEnoForm($data);

        $filtro = array();
        $filtro["id_enc"] = $data["encabezado"]["idEnoForm"];

        
        $totalFiltroEno["filter1"] = $filtro["id_enc"];
       // print_r($totalFiltroEno);exit;
        $param = dalEno::ActualizarTabla($conn, "eno_encabezado", $eno, $filtro, $totalFiltroEno);
        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);

        $param = dalEno::GuardarBitacora($conn, "2", "eno_encabezado");
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

    public static function BorrarEno($data) {
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $param = array();

        $filtro = array();
        $filtro["id_enc"] = $data['idform'];
        $param = dalEno::BorrarTabla($conn, "eno_detalle", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        $param = dalEno::BorrarTabla($conn, "eno_encabezado", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);

        $param = dalEno::GuardarBitacora($conn, "3", "eno_encabezado");
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

    public static function GuardarTabla($conn, $tabla, $objeto) {
        $ok = true;
        //print_r($objeto);
        $sql = Actions::AgregarQuery($tabla, $objeto);
        //echo "<br/><br/>".$sql;exit;
        
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

    public static function ActualizarTabla($conn, $tabla, $objeto, $filtro, $total) {
        $ok = true;
        $sql = Actions::ActualizarQuery($tabla, $objeto, $filtro);
//        print_r($total);
//        echo "<br/><br/>".$sql;exit;
        $conn->prepare($sql);
        $conn->params($total);
//        print_r($total);
        $conn->execute() ? null : $ok = false;
        $error = $conn->getError();
        $conn->closeStmt();
        $conn->getID();

//        echo "Error: ".$error." y ID:".$id;
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

    public static function ejecutarQuery ($conn, $sql) {
        $ok = true;
        $conn->prepare($sql);
        $conn->execute() ? null : $ok = false;
        $error = $conn->getError();
        $conn->closeStmt();
        $param = array();
        if (!$ok) {
            $conn->rollback();
            $conn->closeConn();
            $ok = "SQL:" . $sql . "<br/>Error" . $error;
        }
        $param['ok'] = $ok;
        return $param;
    }
    
    public static function selectQuery ($conn, $sql) {
        $ok = true;
        $conn->prepare($sql);
        $conn->execute() ? null : $ok = false;
        $data = $conn->fetch();
        $error = $conn->getError();
        $conn->closeStmt();
        if (!$ok) {
            $conn->rollback();
            $conn->closeConn();
            $data = "SQL:" . $sql . "<br/>Error" . $error;
        }
        return $data;
    }
    
    public static function BorrarTabla($conn, $tabla, $filtro) {
        $ok = true;
        $sql = Actions::BorrarQuery($tabla, $filtro);
        $conn->prepare($sql);
        $conn->params($filtro);
//        print_r($total);
        $conn->execute() ? null : $ok = false;
        $error = $conn->getError();
        $conn->closeStmt();
        $conn->getID();

//        echo "Error: ".$error." y ID:".$id;
        if (!$ok) {
            $conn->rollback();
            $conn->closeConn();
//            echo "<br/>Error";
//            return "SQL:" . $sql . "<br/>Error" . $error;
//            exit;
        }
        $param = array();
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

}

?>