<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperVicIts.php');
require_once('libs/caus/clsCaus.php');

class dalVicItsLaboratorio {

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
                if ($elemento[ConfigurationCAUS::ServicioSalud] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.idts = '" . $elemento[ConfigurationCAUS::ServicioSalud] . "' ";

                $this->filtroUbicaciones .= ($this->filtroUbicaciones != '' ? "or " : "") . "(" . $temporal . ") ";
            }
        }

        if ($this->filtroUbicaciones != "")
            $this->filtroUbicaciones = "and (" . $this->filtroUbicaciones . ")";
    }

    public static function GuardarVicItsLab($data) {

        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        
        $vicItsLab = helperVicIts::dataVicItsLabForm($data);
        $muestraRel = helperVicIts::dataVicItsLabMuestras($data);
        $pruebasRel = helperVicIts::dataVicItsLabPruebas($data);
        
//        echo "</br>Formulario VicITS:</br>";
//        print_r($vicItsLab);
//        echo "</br>Muestras:</br>";
//        print_r($muestraRel);
//        echo "</br>Pruebas:</br>";
//        print_r($pruebasRel);
//        exit;
        
        $yaExisteForm = helperVicIts::yaExisteVicItsLab($vicItsLab);
        if ($yaExisteForm == 0) {
            $param = array();
            $param = dalVicItsLaboratorio::GuardarTabla($conn, "vicits_laboratorio", $vicItsLab);
            $idFormVicItsLab = $param['id'];
            $ok = $param['ok'];
            $param = dalVicItsLaboratorio::GuardarBitacora($conn, "1", "vicits_laboratorio");
            
            
            if ($muestraRel != NULL && isset($muestraRel["muestras"]))
                dalVicItsLaboratorio::GuardarVicitsMuestras($conn, $idFormVicItsLab, $muestraRel);
            if ($pruebasRel != NULL && isset($pruebasRel["pruebas"]))
                dalVicItsLaboratorio::GuardarVicitsPruebas($conn, $idFormVicItsLab, $pruebasRel);
            
            if ($ok)
                $conn->commit();
            else {
                $conn->rollback();
                $id = -1;
            }

            $conn->closeConn();
            return 1;
        } else {
            $conn->closeConn();
            return 2;
        }
    }

    public static function ActualizarVicitsLab($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $vicItsLab = helperVicIts::dataVicItsLabForm($data);
        $muestraRel = helperVicIts::dataVicItsLabMuestras($data);
        $pruebasRel = helperVicIts::dataVicItsLabPruebas($data);

//        print_r($data);
//        exit;
        
        $param = array();
        
        $totalFiltro = helperVicIts::dataVicItsLabForm($data);

        $filtro = array();
        $filtro["id_vicits_laboratorio"] = $data["formulario"]["id_form_laboratorio"];
        $idFormVicItsLab = $data["formulario"]["id_form_laboratorio"];
        //individuo + filtro esto es para los parametros, no importa el nombre de los filtros.
        $totalFiltro["filter1"] = $filtro["id_vicits_laboratorio"];
        //print_r($totalFiltroIndividuo);
        $param = dalVicItsLaboratorio::ActualizarTabla($conn, "vicits_laboratorio", $vicItsLab, $filtro, $totalFiltro);
        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);

        $param = dalVicItsLaboratorio::GuardarBitacora($conn, "2", "vicits_laboratorio");
        $id = $param['id'];
        $ok = $param['ok'];
        if ($muestraRel != NULL && isset($muestraRel["muestras"]))
                dalVicItsLaboratorio::GuardarVicitsMuestras($conn, $idFormVicItsLab, $muestraRel);
        if ($pruebasRel != NULL && isset($pruebasRel["pruebas"]))
            dalVicItsLaboratorio::GuardarVicitsPruebas($conn, $idFormVicItsLab, $pruebasRel);
        
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

    public static function BorrarVicitsLab($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $param = array();

        $filtro = array();
        $filtro["id_vicits_laboratorio"] = $data["id_vicits_laboratorio"];
        $param = dalVicItsLaboratorio::BorrarTabla($conn, "vicits_lab_muestra", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        $param = dalVicItsLaboratorio::BorrarTabla($conn, "vicits_lab_prueba", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        $param = dalVicItsLaboratorio::BorrarTabla($conn, "vicits_laboratorio", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);

        $param = dalVicItsLaboratorio::GuardarBitacora($conn, "3", "vicits_laboratorio");
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

    public static function GuardarVicitsMuestras($conn, $idFormVicitsLab, $data) {
        $muestras = $data["muestras"];
        if ($muestras != NULL) {
            dalVicItsLaboratorio::BorrarVicitsLabMuestras($conn, $idFormVicitsLab);
            $max = sizeof($muestras);
            for ($i = 0; $i < $max; $i++) {
                if ($muestras[$i]!=""){
                    $muestra = $muestras[$i];
                    $insertarMuestra = array();
                    $insertarMuestra["id_vicits_laboratorio"] = $idFormVicitsLab;
                    $insertarMuestra["id_tipos_muestras"] = $muestra;
                    dalVicItsLaboratorio::GuardarTabla($conn, "vicits_lab_muestra", $insertarMuestra);
                }
            }
            dalVicItsLaboratorio::GuardarBitacora($conn, "1", "vicits_lab_muestra");
        }
    }

    public static function BorrarVicitsLabMuestras($conn, $idFormVicitsLab) {
        $filtro = array();
        $filtro["id_vicits_laboratorio"] = $idFormVicitsLab;
        dalVicItsLaboratorio::BorrarTabla($conn, "vicits_lab_muestra", $filtro);
    }

    public static function GuardarVicitsPruebas($conn, $idFormVicitsLab, $data) {
        $pruebas = $data["pruebas"];
        if ($pruebas != NULL) {
            dalVicItsLaboratorio::BorrarVicitsLabPruebas($conn, $idFormVicitsLab);
            $max = sizeof($pruebas);
            for ($i = 0; $i < $max; $i++) {
                if ($pruebas[$i]!=""){
                    $prueba = $pruebas[$i];
                    $insertarPrueba = array();
                    $insertarPrueba["id_vicits_laboratorio"] = $idFormVicitsLab;
                    $insertarPrueba["id_prueba"] = $prueba;
                    dalVicItsLaboratorio::GuardarTabla($conn, "vicits_lab_prueba", $insertarPrueba);
                }
            }
            dalVicItsLaboratorio::GuardarBitacora($conn, "1", "vicits_lab_prueba");
        }
    }

    public static function BorrarVicitsLabPruebas($conn, $idFormVicitsLab) {
        $filtro = array();
        $filtro["id_vicits_laboratorio"] = $idFormVicitsLab;
        dalVicItsLaboratorio::BorrarTabla($conn, "vicits_lab_prueba", $filtro);
    }
    
    public static function GuardarTabla($conn, $tabla, $objeto) {
        $ok = true;
        
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
//        echo "Error: ".$error." y ID:".$id;
        if (!$ok) {
            $conn->rollback();
            $conn->closeConn();
            echo "<br/>Error";
            return "SQL:" . $sql . "<br/>Error" . $error;
            exit;
        }
        $param = array();
        $param['ok'] = $ok;
        return $param;
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
            echo "<br/>Error";
            return "SQL:" . $sql . "<br/>Error" . $error;
            exit;
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