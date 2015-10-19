<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperNotic.php');
require_once('libs/caus/clsCaus.php');

class dalNotic{

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
    
    public static function GuardarNotic($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $notic = helperNotic::dataForm($data);
        $sintomas = helperNotic::dataNoticSintomas($data);
        $individuo = helperNotic::dataTblPersona($data);
        $yaExisteNotic = helperNotic::yaExisteNotic($data);
        $yaExistePersona = helperNotic::yaExistePersona($individuo);
        
//        echo "notic:</br>";
//        print_r($notic);
//        echo "</br>sintomas:</br>";
//        print_r($sintomas);
//        echo "</br>persona:</br>";
//        print_r($individuo);
//        echo "</br>Ya existe: ".$yaExisteNotic."</br>";
//        echo "</br>Ya existe persona: ".$yaExisteNotic."</br>";
//        exit;
        if ($yaExisteNotic == 0) {
            $param = array();
            if ($yaExistePersona == 0) {
                $param = dalNotic::GuardarTabla($conn, "tbl_persona", $individuo);
                if ($param['ok']){
                    $param = dalNotic::GuardarBitacora($conn, "1", "tbl_persona");       
                    $ok = $param['ok'];
                }
            }
            else{
                $totalFiltroPersona = helperNotic::dataTblPersona($data);
                $filtro = array();
                $filtro["tipo_identificacion"] = $data["individuo"]["tipoId"];
                $filtro["numero_identificacion"] = $data["individuo"]["identificador"];
                $totalFiltroPersona["filter1"] = $filtro["tipo_identificacion"];
                $totalFiltroPersona["filter2"] = $filtro["numero_identificacion"];
                unset($individuo['tipo_identificacion']);
                unset($individuo['numero_identificacion']);
                unset($totalFiltroPersona['tipo_identificacion']);
                unset($totalFiltroPersona['numero_identificacion']);
                $param = dalNotic::ActualizarTabla($conn, "tbl_persona", $individuo, $filtro, $totalFiltroPersona);
                if ($param['ok']){
                    $param = dalNotic::GuardarBitacora($conn, "2", "tbl_persona"); 
                    $ok = $param['ok'];
                }
            }
            if ($ok){
                $param = dalNotic::GuardarTabla($conn, "notic_form", $notic);
                $idForm = $param['id'];
                $ok = $param['ok'];
                if ($param['ok']){
                    $param = dalNotic::GuardarBitacora($conn, "1", "notic_form");
                    $ok = $param['ok'];
                    if (is_array($sintomas)){
                        $ok = dalNotic::GuardarSintomas($conn, $idForm, $sintomas);
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
            return "1-".$idForm;
        } else {
            $conn->closeConn();
            return 2;
        }
    }
    
    public static function GuardarSintomas($conn, $idForm, $data) {
        //print_r($data);exit;
        $ok = true;
        $param = array();
        $sintomas = $data;
        if ($sintomas != NULL) {
            dalNotic::BorrarSintomas($conn, $idForm);
            $max = sizeof($sintomas);
            for ($i = 0; $i < $max; $i++) {
                if ($sintomas[$i]!=""){
                    $sintoma = $sintomas[$i];
                    $insertarSintoma = array();
                    //list($idEnfermedad, $idResultado) = explode('-', $sintoma);
                    $insertarSintoma["id_notic"] = $idForm;
                    $insertarSintoma["id_sintoma"] = $sintoma[0];
                    $insertarSintoma["fecha_sintoma"] = helperString::toDate($sintoma[1]);
                    if ($insertarSintoma["id_notic"] != "" && $insertarSintoma["id_sintoma"] != "" && $insertarSintoma["fecha_sintoma"] != ""){
                        $param = dalNotic::GuardarTabla($conn, "notic_sintoma", $insertarSintoma);
                        $ok = $param['ok'];
                    }
                    //echo "Enfermedad id: " . $sintoma["id"] . " Resultado: " . $sintoma["res"] . "<br/>";
                }
            }
            dalNotic::GuardarBitacora($conn, "1", "notic_sintoma");
        }
        return $ok;
    }
    
    public static function BorrarSintomas($conn, $idForm) {
        $filtro = array();
        $filtro["id_notic"] = $idForm;
        dalNotic::BorrarTabla($conn, "notic_sintoma", $filtro);
    }

    public static function ActualizarForm($data) {
        //print_r($data);exit;
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $notic = helperNotic::dataForm($data);
        $sintomas = helperNotic::dataNoticSintomas($data);
        $individuo = helperNotic::dataTblPersona($data);
        $filtro = array();
        $filtro["id_notic"] = $data["formulario"]["idForm"];
        $totalFiltro = helperNotic::dataForm($data);
        $totalFiltro["filter1"] = $filtro["id_notic"];
        $totalFiltroPersona = helperNotic::dataTblPersona($data);
        $filtro2 = array();
        $filtro2["tipo_identificacion"] = $data["individuo"]["tipoId"];
        $filtro2["numero_identificacion"] = $data["individuo"]["identificador"];
        $totalFiltroPersona["filter1"] = $filtro2["tipo_identificacion"];
        $totalFiltroPersona["filter2"] = $filtro2["numero_identificacion"];
       // print_r($totalFiltroEno);exit;
        $param = dalNotic::ActualizarTabla($conn, "notic_form", $notic, $filtro, $totalFiltro);
        if ($param['ok']){
            $param = dalNotic::GuardarBitacora($conn, "2", "notic_form");
            if ($param['ok']){
                unset($individuo['tipo_identificacion']);
                unset($individuo['numero_identificacion']);
                unset($totalFiltroPersona['tipo_identificacion']);
                unset($totalFiltroPersona['numero_identificacion']);
                $param = dalNotic::ActualizarTabla($conn, "tbl_persona", $individuo, $filtro2, $totalFiltroPersona);
                if ($param['ok']){
                    $param = dalNotic::GuardarBitacora($conn, "2", "tbl_persona"); 
                    $ok = $param['ok'];
                }
                if ($param['ok']){
                    if (is_array($sintomas)){
                        $ok = dalNotic::GuardarSintomas($conn, $data["formulario"]["idForm"], $sintomas);
                    }
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
        return "1-".$filtro["id_notic"];
    }

    public static function BorrarForm($data) {
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $param = array();
        $filtro = array();
        $filtro["id_notic"] = $data['idform'];
        dalNotic::BorrarSintomas($conn, $filtro["id_notic"]);
        $param = dalNotic::BorrarTabla($conn, "notic_form", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        $param = dalNotic::GuardarBitacora($conn, "3", "notic_form");
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
        $sql = Actions::AgregarQuery($tabla, $objeto);
        //echo "Sql: ".$sql;exit;     
        $conn->prepare($sql);
        $conn->params($objeto);
        $conn->execute() ? null : $ok = false;
        $error = $conn->getError();
        $conn->closeStmt();
        $id = $conn->getID();
        $param = array();
        //echo "SQL:" . $sql . "<br/>Error" . $error;exit;
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