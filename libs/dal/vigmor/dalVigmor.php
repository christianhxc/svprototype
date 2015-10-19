<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperVigmor.php');
require_once('libs/caus/clsCaus.php');

class dalVigmor{

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
    
    public static function GuardarVigmor($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $vigmor = helperVigmor::dataVigmorForm($data);
        $individuo = helperVigmor::dataTblPersona($data);
        $yaExistePersona = helperVigmor::yaExistePersona($individuo);
        $yaExisteVigmor = helperVigmor::yaExisteVigmor($vigmor);
        if ($yaExisteVigmor == 0) {
            $param = array();
            if ($yaExistePersona == 0) {
                $param = dalVigmor::GuardarTabla($conn, "tbl_persona", $individuo);
                if ($param['ok']){
                    $param = dalVigmor::GuardarBitacora($conn, "1", "tbl_persona");       
                    $ok = $param['ok'];
                }
            }
            else{
                $totalFiltroPersona = helperVigmor::dataTblPersona($data);
                $filtro = array();
                $filtro["tipo_identificacion"] = $data["individuo"]["tipoId"];
                $filtro["numero_identificacion"] = $data["individuo"]["identificador"];
                $totalFiltroPersona["filter1"] = $filtro["tipo_identificacion"];
                $totalFiltroPersona["filter2"] = $filtro["numero_identificacion"];
                unset($individuo['tipo_identificacion']);
                unset($individuo['numero_identificacion']);
                unset($totalFiltroPersona['tipo_identificacion']);
                unset($totalFiltroPersona['numero_identificacion']);
                $param = dalVigmor::ActualizarTabla($conn, "tbl_persona", $individuo, $filtro, $totalFiltroPersona);
                if ($param['ok']){
                    $param = dalVigmor::GuardarBitacora($conn, "2", "tbl_persona"); 
                    $ok = $param['ok'];
                }
            }
            if ($ok){
                $param = dalVigmor::GuardarTabla($conn, "vm_form", $vigmor);
                $idFormVigmor = $param['id'];
                if ($param['ok']){
                    $param = dalVigmor::GuardarBitacora($conn, "1", "vm_form");
                    $ok = $param['ok'];
                }
            }
            if ($ok)
                $conn->commit();
            else {
                $conn->rollback();
                $id = -1;
            }
            $conn->closeConn();
            return "1-".$idFormVigmor;
        } else {
            $conn->closeConn();
            return 2;
        }
    }

    public static function ActualizarVigmor($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $vigmor = helperVigmor::dataVigmorForm($data);
        $individuo = helperVigmor::dataTblPersona($data);
        $filtro = array();
        $filtro["id_form"] = $data["formulario"]["idVigmorForm"];
        $totalFiltroVigmor = helperVigmor::dataVigmorForm($data);
        $totalFiltroVigmor["filter1"] = $filtro["id_form"];
        $totalFiltroPersona = helperVigmor::dataTblPersona($data);
        $filtro2 = array();
        $filtro2["tipo_identificacion"] = $data["individuo"]["tipoId"];
        $filtro2["numero_identificacion"] = $data["individuo"]["identificador"];
        $totalFiltroPersona["filter1"] = $filtro2["tipo_identificacion"];
        $totalFiltroPersona["filter2"] = $filtro2["numero_identificacion"];
       // print_r($totalFiltroEno);exit;
        $param = dalVigmor::ActualizarTabla($conn, "vm_form", $vigmor, $filtro, $totalFiltroVigmor);
        if ($param['ok']){
            $param = dalVigmor::GuardarBitacora($conn, "2", "vm_form");
            if ($param['ok']){
                unset($individuo['tipo_identificacion']);
                unset($individuo['numero_identificacion']);
                unset($totalFiltroPersona['tipo_identificacion']);
                unset($totalFiltroPersona['numero_identificacion']);
                $param = dalVigmor::ActualizarTabla($conn, "tbl_persona", $individuo, $filtro2, $totalFiltroPersona);
                if ($param['ok']){
                    $param = dalVigmor::GuardarBitacora($conn, "2", "tbl_persona"); 
                    $ok = $param['ok'];
                }
            }
        }
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

    public static function BorrarVigmor($data) {
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $param = array();
        $filtro = array();
        $filtro["id_form"] = $data['idform'];
        $param = dalVigmor::BorrarTabla($conn, "vm_form", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        $param = dalVigmor::GuardarBitacora($conn, "3", "vm_form");
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
//        if($tabla == "tbl_persona"){
//            print_r($total); 
//            echo "<br/><br/>".$sql;exit;
//        }
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