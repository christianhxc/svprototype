<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperVih.php');
require_once('libs/helper/helperSilab.php');
require_once('libs/caus/clsCaus.php');

class dalVih {

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
    
    public static function cargarDatosSilab(){
        
        $ok = true;
        $total = 0;
        $muestras = helperSilab::traerAllDatosSilab();
               
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        
        if ($muestras != NULL){
            foreach ($muestras as $muestra){
                $param = dalVih::GuardarTabla($conn, "vih_silab_temp", $muestra);
                $ok = $param['ok'];
            }
            
        }
        if ($ok){
            $total = helperVih::contarMuestrasSilab();
            $conn->commit();
        }
        else {
            $conn->rollback();
            $total = -1;
        }
        $conn->closeConn();
        return $total;
    }

    public static function GuardarVih($data) {

        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $data["individuo"]["region_diagnostico"] = $data["individuo"]["region"];
        $data["individuo"]["corregimiento_diagnostico"] = $data["individuo"]["corregimiento"];
        $data["individuo"]["lugar_poblado_diagnostico"] = $data["individuo"]["lugar_poblado"];

        $individuo = helperVih::dataTblPersona($data);
        $vih = helperVih::dataVihForm($data);
        $tarv = helperVih::dataVihTarvForm($data);
        $enfermedades = helperVih::dataVihEnfermedades($data);
        $factores = helperVih::dataVihFactorRiesgo($data);
        $muestras = helperVih::dataVihMuestrasSILAB($data);
        $pruebas = helperVih::dataVihPruebasSILAB($data);

        $vigilancia = $data["vigilancia"];
        $vih = array_merge($vih, $vigilancia);

        //print_r($factores);exit;

        $yaExisteVih = helperVih::yaExisteVih($vih);
        if ($yaExisteVih == 0) {
            $param = array();
            $yaExistePersona = helperVih::yaExistePersona($individuo);
            
            $param = dalVih::GuardarTabla($conn, "vih_form", $vih);
            $idFormVih = $param['id'];
            $param = dalVih::GuardarBitacora($conn, "1", "vih_form");
            
            if (is_array($enfermedades))
                dalVih::GuardarVihEnfermedad($conn, $idFormVih, $enfermedades);
            if (is_array($factores))
                dalVih::GuardarVihFactores($conn, $idFormVih, $factores);
            if ($muestras != NULL && isset($muestras[0]))
                $param = dalVih::GuardarVihMuestras($conn, $filtro["id_vih_form"], $muestras);
            if ($pruebas != NULL && isset($pruebas[0]))
                dalVih::GuardarVihPruebasSilab($conn, $filtro["id_vih_form"], $pruebas);
            
            if ($tarv != NULL && isset($tarv["tarv_fec_inicio"])){
                $tarv["id_vih_form"] = $idFormVih;
                dalVih::GuardarTabla($conn, "vih_tarv", $tarv);
                dalVih::GuardarBitacora($conn, "1", "vih_tarv");
            }
            
            if ($yaExistePersona == 0) {
                $idTipoTmp = $individuo["id_tipo_identidad"];
                unset($individuo["id_tipo_identidad"]);
                unset($individuo["id_region"]);
                $individuo["tipo_identificacion"] = $idTipoTmp;
                $param = dalVih::GuardarTabla($conn, "tbl_persona", $individuo);
                $id = $param['id'];
                $ok = $param['ok'];
                //print_r($param);

                $param = dalVih::GuardarBitacora($conn, "1", "tbl_persona");
                $id = $param['id'];
                $ok = $param['ok'];
                //print_r($param);
            } else {
                echo "La persona ya existe";
                $totalFiltroIndividuo = helperVih::dataTblPersona($data);
                //print_r($totalFiltroIndividuo);exit;
                $filtro = array();
                $filtro["tipo_identificacion"] = $individuo["id_tipo_identidad"];
                $filtro["numero_identificacion"] = $individuo["numero_identificacion"];

                //individuo + filtro esto es para los parametros, no importa el nombre de los filtros.
                unset($totalFiltroIndividuo["id_tipo_identidad"]);
                $totalFiltroIndividuo["tipo_identificacion"] = $filtro["tipo_identificacion"];
                $totalFiltroIndividuo["filter1"] = $filtro["tipo_identificacion"];
                $totalFiltroIndividuo["filter2"] = $filtro["numero_identificacion"];
                unset($individuo["id_tipo_identidad"]);
                $individuo["tipo_identificacion"] = $filtro["tipo_identificacion"];
                unset($individuo["id_region"]);
                unset($totalFiltroIndividuo["id_region"]);
                //print_r($totalFiltroIndividuo);
                $param = dalVih::ActualizarTabla($conn, "tbl_persona", $individuo, $filtro, $totalFiltroIndividuo);
                $id = $param['id'];
                $ok = $param['ok'];
                //print_r($param);

                $param = dalVih::GuardarBitacora($conn, "2", "tbl_persona");
                $id = $param['id'];
                $ok = $param['ok'];
                //print_r($param);
            }
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

    public static function ActualizarVih($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        unset($data["individuo"]["region_diagnostico"]);
        unset($data["individuo"]["corregimiento_diagnostico"]);
        unset($data["individuo"]["lugar_poblado_diagnostico"]);

        $individuo = helperVih::dataTblPersona($data);
        $vih = array();
        $vih = helperVih::dataVihForm($data);
        $vih = helperVih::dataVihVigilancia($vih,$data);

        $vih["silab"] = 0;
        if ($data["notificacion"]["silab"] == 1)
            $vih["silab"] = 2;
        else if ($data["notificacion"]["silab"] == 2)
            $vih["silab"] = 2;
        $vih["epiInfo"] = 0;
        if ($data["notificacion"]["epiInfo"] == 1)
            $vih["epiInfo"] = 2;
        else if ($data["notificacion"]["epiInfo"] == 2)
            $vih["epiInfo"] = 2;
                
        $enfermedades = helperVih::dataVihEnfermedades($data);
        $factores = helperVih::dataVihFactorRiesgo($data);
        $muestras = helperVih::dataVihMuestrasSILAB($data);
        $pruebas = helperVih::dataVihPruebasSILAB($data);
        $tarv = helperVih::dataVihTarvForm($data);
//        echo $data["factores"]["globalFactorRiesgoRelacionados"];
//        print_r($factores);
//        exit;
        
        $param = array();
        
        $totalFiltroVih = helperVih::dataVihForm($data);
        $totalFiltroVih = helperVih::dataVihVigilancia($totalFiltroVih,$data);

        $filtro = array();
        $filtro["id_vih_form"] = $data["formulario"]["idVihForm"];

        //individuo + filtro esto es para los parametros, no importa el nombre de los filtros.
        $totalFiltroVih["silab"] = 0;
        if ($data["notificacion"]["silab"] == 1)
            $totalFiltroVih["silab"] = 2;
        else if ($data["notificacion"]["silab"] == 2)
            $totalFiltroVih["silab"] = 2;
        $totalFiltroVih["epiInfo"] = 0;
        if ($data["notificacion"]["epiInfo"] == 1)
            $totalFiltroVih["epiInfo"] = 2;
        else if ($data["notificacion"]["epiInfo"] == 2)
            $totalFiltroVih["epiInfo"] = 2;
        
        $totalFiltroVih["filter1"] = $filtro["id_vih_form"];
       // print_r($totalFiltroVih);exit;

        $param = dalVih::ActualizarTabla($conn, "vih_form", $vih, $filtro, $totalFiltroVih);
        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);

        $param = dalVih::GuardarBitacora($conn, "2", "vih_form");
        $id = $param['id'];
        $ok = $param['ok'];
        if (is_array($enfermedades))
           $param = dalVih::GuardarVihEnfermedad($conn, $filtro["id_vih_form"], $enfermedades);
        if (isset($factores[0]))
            dalVih::GuardarVihFactores($conn, $filtro["id_vih_form"], $factores);
        if ($tarv!="")
            dalVih::GuardarVihTarv($conn, $filtro["id_vih_form"], $tarv);
        if ($muestras != NULL && isset($muestras[0]))
            dalVih::GuardarVihMuestras($conn, $filtro["id_vih_form"], $muestras);
        if ($pruebas != NULL && isset($pruebas[0]))
            dalVih::GuardarVihPruebasSilab($conn, $filtro["id_vih_form"], $pruebas);

        $totalFiltroIndividuo = helperVih::dataTblPersona($data);
        //primero actualizamos a la persona
        $filtro = array();
        $filtro["tipo_identificacion"] = $individuo["id_tipo_identidad"];
        $filtro["numero_identificacion"] = $individuo["numero_identificacion"];
        unset($totalFiltroIndividuo["id_tipo_identidad"]);
        $totalFiltroIndividuo["tipo_identificacion"] = $filtro["tipo_identificacion"];
        $totalFiltroIndividuo["filter1"] = $filtro["tipo_identificacion"];
        $totalFiltroIndividuo["filter2"] = $filtro["numero_identificacion"];
        unset($individuo["id_tipo_identidad"]);
        $individuo["tipo_identificacion"] = $filtro["tipo_identificacion"];
        unset($individuo["id_region"]);
        unset($totalFiltroIndividuo["id_region"]);

        $param = dalVih::ActualizarTabla($conn, "tbl_persona", $individuo, $filtro, $totalFiltroIndividuo);
        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);

        $param = dalVih::GuardarBitacora($conn, "2", "tbl_persona");
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

    public static function BorrarVih($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $param = array();

        $filtro = array();
        $filtro["id_vih_form"] = $data['idVih'];
        $param = dalVih::BorrarTabla($conn, "vih_enfermedad_oportunista", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        $param = dalVih::BorrarTabla($conn, "vih_factor_riesgo", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        $param = dalVih::BorrarTabla($conn, "vih_form", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);

        $param = dalVih::GuardarBitacora($conn, "3", "vih_form");
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

    public static function GuardarVihEnfermedad($conn, $idFormVih, $data) {
        $enfermedades = $data["enfermedades"];
        if ($enfermedades != NULL) {
            dalVih::BorrarVihEnfermedades($conn, $idFormVih);
            $max = sizeof($enfermedades);
            for ($i = 0; $i < $max; $i++) {
                if ($enfermedades[$i]!=""){
                    $enfermedad = $enfermedades[$i];
                    $insertarEnfermedad = array();
                    //list($idEnfermedad, $idResultado) = explode('-', $enfermedad);
                    $insertarEnfermedad["id_vih_form"] = $idFormVih;
                    $insertarEnfermedad["id_evento"] = $enfermedad;
                    dalVih::GuardarTabla($conn, "vih_enfermedad_oportunista", $insertarEnfermedad);
                    //echo "Enfermedad id: " . $enfermedad["id"] . " Resultado: " . $enfermedad["res"] . "<br/>";
                }
            }
            dalVih::GuardarBitacora($conn, "1", "vih_enfermedad_oportunista");
        }
    }

    public static function BorrarVihEnfermedades($conn, $idFormVih) {
        $filtro = array();
        $filtro["id_vih_form"] = $idFormVih;
        dalVih::BorrarTabla($conn, "vih_enfermedad_oportunista", $filtro);
        
    }

    public static function GuardarVihFactores($conn, $idFormVih, $factores) {
        //print_r($factores);exit;
        if (isset($factores[0])) {
            dalVih::BorrarVihFactores($conn, $idFormVih);
            foreach ($factores as $factor){
                $insertarFactor = array();
                if (isset($factor[0])){
                    if($factor[0]!=""){
                        $idFactor = $factor[0];
                        $idGrupoFactor = ($factor[1]==-1)?0:$factor[1];
                        $insertarFactor["id_vih_form"] = $idFormVih;
                        $insertarFactor["id_grupo_factor"] = $idGrupoFactor;
                        $insertarFactor['id_factor'] = $idFactor;
                        dalVih::GuardarTabla($conn, "vih_factor_riesgo", $insertarFactor);
                    }
                }
            }
            dalVih::GuardarBitacora($conn, "1", "vih_factor_riesgo");
        }
    }
    
    public static function BorrarVihFactores($conn, $idFormVih) {
        $filtro = array();
        $filtro["id_vih_form"] = $idFormVih;
        dalVih::BorrarTabla($conn, "vih_factor_riesgo", $filtro);
    }
    
    public static function GuardarVihTarv($conn, $idFormVih, $tarv) {
        
        if (isset($tarv)) {
            dalVih::BorrarVihTarv($conn, $idFormVih);
            if($tarv!=""){
                $tarv["id_vih_form"] = $idFormVih;
                dalVih::GuardarTabla($conn, "vih_tarv", $tarv);
            }
            dalVih::GuardarBitacora($conn, "2", "vih_tarv");
        }
    }

    public static function BorrarVihTarv($conn, $idFormVih) {
        $filtro = array();
        $filtro["id_vih_form"] = $idFormVih;
        dalVih::BorrarTabla($conn, "vih_tarv", $filtro);
    }
    
    public static function GuardarVihMuestras($conn, $idFormVih, $data) {
        $muestras = $data;
        if ($muestras != NULL) {
            dalVih::BorrarVihMuestras($conn, $idFormVih);
            $max = sizeof($muestras);
            for ($i = 0; $i < $max; $i++) {
                $muestra = $muestras[$i];
                if ($muestra["id_muestra"]!="" && sizeof($muestra)>1 ) {
                    $muestra["id_vih_form"] = $idFormVih;
                    dalVih::GuardarTabla($conn, "vih_muestra_silab", $muestra);
                }
            }
            dalVih::GuardarBitacora($conn, "1", "vih_muestra_silab");
        }
    }

    public static function BorrarVihMuestras($conn, $idFormVih) {
        $filtro = array();
        $filtro["id_vih_form"] = $idFormVih;
        dalVih::BorrarTabla($conn, "vih_muestra_silab", $filtro);
    }
    
    public static function GuardarVihPruebasSilab($conn, $idFormVih, $data) {
        $pruebas = $data;
        if (isset($pruebas[0])) {
            dalVih::BorrarVihPruebasSilab($conn, $idFormVih);
            $max = sizeof($pruebas);
            for ($i = 0; $i < $max; $i++) {
                if(isset($pruebas[$i])){
                    $prueba = $pruebas[$i];
                    if ($prueba["id_muestra"]!="" && sizeof($prueba)>1 ) {
                        $prueba["id_vih_form"] = $idFormVih;
                        dalVih::GuardarTabla($conn, "vih_muestra_prueba_silab", $prueba);
                    }
                }
            }
            dalVih::GuardarBitacora($conn, "1", "vih_muestra_prueba_silab");
        }
    }

    public static function BorrarVihPruebasSilab($conn, $idFormVih) {
        $filtro = array();
        $filtro["id_vih_form"] = $idFormVih;
        dalVih::BorrarTabla($conn, "vih_muestra_prueba_silab", $filtro);
    }
    
    public static function BorrarVihPruebaSilab($conn, $idMuestra) {
        $filtro = array();
        $filtro["id_muestra"] = $idMuestra;
        dalVih::BorrarTabla($conn, "vih_muestra_prueba_silab", $filtro);
        dalVih::GuardarBitacora($conn, "3", "vih_muestra_prueba_silab");
    }
    
    public static function BorrarVihMuestraSilab($conn, $idMuestra) {
        $filtro = array();
        $filtro["id_muestra"] = $idMuestra;
        dalVih::BorrarTabla($conn, "vih_muestra_silab", $filtro);
        dalVih::GuardarBitacora($conn, "3", "vih_muestra_silab");
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