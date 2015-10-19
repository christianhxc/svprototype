<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperMat.php');
require_once('libs/caus/clsCaus.php');

class dalMat {

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

    public static function GuardarEscenario($data) {

        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        
        $escenario = helperMat::dataEscenarioForm($data);
        $grupos = helperMat::dataGruposRelacionados($data);
        $yaExisteEscenario = helperMat::yaExisteEscenario($escenario);
        $datosSuficientesCanal = true;
        if ($escenario["tipo_algoritmo"]==2 ){
            $filtro = '';
            if ($escenario["nivel_geo"] == 2)
                $filtro = " and id_nivel_geo1 = ".$escenario["id_nivel_geo"];
            else if ($escenario["nivel_geo"] == 3)
                $filtro = " and id_nivel_geo2 = ".$escenario["id_nivel_geo"];
            else if ($escenario["nivel_geo"] == 4)
                $filtro = " and id_nivel_geo3 = ".$escenario["id_nivel_geo"];
            else if ($escenario["nivel_geo"] == 5)
                $filtro = " and id_nivel_geo4 = ".$escenario["id_nivel_geo"];
            $datosCanal = helperMat::datosSufientesParaCanalEndemico($escenario,$filtro);
            if (!isset($datosCanal[3])){
                //print_r($datosCanal);exit;
                $datosSuficientesCanal = false;
            }
        }
        if ($yaExisteEscenario == 0 && $datosSuficientesCanal==true) {
            $param = array();
            $param = dalMat::GuardarTabla($conn, "mat_escenario", $escenario);
            $ok = $param['ok'];
            $idForm = $param['id'];
            $param = dalMat::GuardarBitacora($conn, "1", "mat_escenario");
            if ($ok){
                if ($grupos != NULL && isset($grupos["grupos"]))
                    dalMat::GuardarGruposContacto($conn,$idForm, $grupos);
                $conn->commit();
            }
            else {
                $conn->rollback();
                $id = -1;
            }

            $conn->closeConn();
            return 1;
        } else if ($datosSuficientesCanal == false) {
            $conn->closeConn();
            return 3;
        } else {
            $conn->closeConn();
            return 2;
        }
    }
    
    public static function GuardarGruposContacto($conn, $idForm, $data) {
        $grupos = $data["grupos"];
        if ($grupos != NULL) {
            dalMat::BorrarGruposContacto($conn, $idForm);
            $max = sizeof($grupos);
            for ($i = 0; $i < $max; $i++) {
                if ($grupos[$i]!=""){
                    $grupo = $grupos[$i];
                    $sql = "INSERT INTO mat_escenario_grupo_contacto( id_escenario, id_grupo_contacto) VALUES($idForm,$grupo);";
                    dalMat::ejecutarQuery($conn, $sql);
                }
            }
        }
    }
    
    public static function BorrarGruposContacto($conn, $idForm) {
        $sql = "delete from mat_escenario_grupo_contacto where id_escenario = $idForm ;";
        dalMat::ejecutarQuery($conn, $sql);
    }

    public static function ActualizarEscenario($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $escenario = array();
        $escenario = helperMat::dataEscenarioForm($data);
        $grupos = helperMat::dataGruposRelacionados($data);
        unset($escenario["nombre_crear"]);
        unset($escenario["fecha_crear"]);
        $param = array();
        $totalFiltro = $escenario;
        $totalFiltro["id_escenario"] = $data["escenario"]["id_escenario"];   
        $sql = "UPDATE mat_escenario SET id_n1 = ".$totalFiltro["id_n1"].",tipo_algoritmo = ".$totalFiltro["tipo_algoritmo"].",nivel_geo = ".$totalFiltro["nivel_geo"].",
            id_nivel_geo = ".$totalFiltro["id_nivel_geo"].",tipo_alerta = ".$totalFiltro["tipo_alerta"].", dia_alerta = ".$totalFiltro["dia_alerta"].",
                mensaje = '".$totalFiltro["mensaje"]."' WHERE 1 AND id_escenario = ".$totalFiltro["id_escenario"];
        //echo $sql; 
        $param = dalMat::ejecutarQuery($conn, $sql);
        $ok = $param['ok'];
        if ($ok){
            $param = dalMat::GuardarBitacora($conn, "2", "mat_escenario");
            
            if ($grupos != NULL && isset($grupos["grupos"])){
                dalMat::GuardarGruposContacto($conn, $totalFiltro["id_escenario"], $grupos);
            }
            $conn->commit();
            return 1;
        }
        else {
            $conn->rollback();
            $conn->closeConn();
            return 2;
        }
    }

    public static function BorrarEscenario($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $param = array();
        $filtro = array();
        $filtro["id_escenario"] = $data['id'];
        dalMat::BorrarGruposContacto($conn, $data['id']);
        $param = dalMat::BorrarTabla($conn, "mat_escenario", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        $param = dalMat::GuardarBitacora($conn, "3", "mat_escenario");
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
    
    public static function GuardarContacto($data) {

        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        
        $contacto = helperMat::dataContactoForm($data);
        $yaExisteContacto = helperMat::yaExisteContacto($contacto);
        if ($yaExisteContacto == 0) {
            $param = array();
            $param = dalMat::GuardarTabla($conn, "mat_contacto", $contacto);
            $ok = $param['ok'];
            $param = dalMat::GuardarBitacora($conn, "1", "mat_contacto");
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
    
    public static function ActualizarContacto($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $contacto = array();
        $contacto = helperMat::dataContactoForm($data);
        $param = array();
        $totalFiltro = $contacto;
        $totalFiltro["id_contacto"] = $data["contacto"]["id_contacto"];   
        $sql = "UPDATE mat_contacto SET nombres = '".$totalFiltro["nombres"]."',apellidos = '".$totalFiltro["apellidos"]."',
            email = '".$totalFiltro["email"]."',telefono = '".$totalFiltro["telefono"]."', 
                status = ".$totalFiltro["status"]." WHERE 1 AND id_contacto = ".$totalFiltro["id_contacto"];
        //echo $sql; 
        $param = dalMat::ejecutarQuery($conn, $sql);
        $id = $param['id'];
        $ok = $param['ok'];
        if ($ok)
            $conn->commit();
        else {
            $conn->rollback();
            $id = -1;
        }
        $param = dalMat::GuardarBitacora($conn, "2", "mat_contacto");
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
    
    public static function BorrarContacto($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $param = array();
        $filtro = array();
        $filtro["id_contacto"] = $data['id'];
        $param = dalMat::BorrarTabla($conn, "mat_contacto_grupo_contacto", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        $param = dalMat::BorrarTabla($conn, "mat_contacto", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        $param = dalMat::GuardarBitacora($conn, "3", "mat_contacto");
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
        
    public static function GuardarGrupoContacto($nombre, $status) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $grupo["nombre_grupo_contacto"] = $nombre;
        $grupo["status"] = $status;
        $yaExisteGrupo = helperMat::yaExisteGrupo($nombre);
        if ($yaExisteGrupo == 0) {
            $param = array();
            $param = dalMat::GuardarTabla($conn, "mat_grupo_contacto", $grupo);
            $ok = $param['ok'];
            $param = dalMat::GuardarBitacora($conn, "1", "mat_grupo_contacto");
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
    
    public static function ActualizarGrupoContacto($idGrupo, $n, $s) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $param = array();
        $sql = "UPDATE mat_grupo_contacto SET nombre_grupo_contacto = '$n', status = $s WHERE id_grupo_contacto = $idGrupo";
        $param = dalMat::ejecutarQuery($conn, $sql);
        $id = $param['id'];
        $ok = $param['ok'];
        if ($ok)
            $conn->commit();
        else {
            $conn->rollback();
            $id = -1;
        }
        $param = dalMat::GuardarBitacora($conn, "2", "mat_grupo_contacto");
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
    
    public static function BorrarGrupoContacto($id) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $param = array();
        $filtro = array();
        $filtro["id_grupo_contacto"] = $id;
        $param = dalMat::BorrarTabla($conn, "mat_contacto_grupo_contacto", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        $param = dalMat::BorrarTabla($conn, "mat_grupo_contacto", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        $param = dalMat::GuardarBitacora($conn, "3", "mat_grupo_contacto");
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
    
    public static function traerTablaGrupoContacto(){
        $grupoContactos = helperMat::getGruposContacto();
        return $grupoContactos;
    }
    
    public static function totalGrupoContacto(){
        $total = helperMat::totalGruposContacto();
        return $total;
    }
    
    public static function traerTablaContactos($limite){
        $grupoContactos = helperMat::getContactos($limite);
        return $grupoContactos;
    }
    
    public static function totalContactos(){
        $total = helperMat::totalContactos();
        return $total;
    }
    
    public static function traerRelaciones($idGrupo, $idContacto){
        return helperMat::relContactosGrupo($idGrupo, $idContacto);
    }
    
    public static function existeRelacion($idGrupo, $idContacto){
        $total = helperMat::yaExisteRelacion($idGrupo, $idContacto);
        return $total;
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
        print_r($total);
       echo "<br/><br/>".$sql;exit;
        $conn->prepare($sql);
        $conn->params($total);
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
    
    public static function BorrarTabla($conn, $tabla, $filtro) {
        $ok = true;
        $sql = Actions::BorrarQuery($tabla, $filtro);
        $conn->prepare($sql);
        $conn->params($filtro);
       // print_r($filtro);exit;
        $conn->execute() ? null : $ok = false;
        $error = $conn->getError();
        $conn->closeStmt();
        $conn->getID();
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