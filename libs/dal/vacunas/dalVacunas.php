<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperVacunas.php');
require_once('libs/caus/clsCaus.php'); 

class dalVacunas{

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
    
    public static function GuardarEsquema($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $esquema = helperVacunas::dataEsquemaForm($data);
        $condiciones = helperVacunas::dataCondiciones($data);
        
        //print_r($esquema);exit;
        $yaExisteEsquema = helperVacunas::yaExisteCodigo($esquema);
        if ($yaExisteEsquema == 0) {
            $param = array();
            $param = dalVacunas::GuardarTabla($conn, "vac_esquema", $esquema);
            $idFormEsquema = $param['id'];
            $ok = $param['ok'];
            if ($param['ok']){
                $param = dalVacunas::GuardarBitacora($conn, "1", "vac_esquema");
                $ok = $param['ok'];
                if (is_array($condiciones)){
                    if ($condiciones[0][0]!="")
                        $ok = dalVacunas::GuardarCondiciones($conn, $idFormEsquema, $condiciones);
                }
            }
            if ($ok)
                $conn->commit();
            else {
                $conn->rollback();
                $id = -1;
            }
            $conn->closeConn();
            return "1-".$idFormEsquema;
        } else {
            $conn->closeConn();
            return 2;
        }
    }

    public static function ActualizarFormEsquema($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $esquema = array();
        $esquema = helperVacunas::dataEsquemaForm($data);
        $condiciones = helperVacunas::dataCondiciones($data);
//        echo $data["factores"]["globalFactorRiesgoRelacionados"];
//        print_r($condiciones);
//        exit;
        
        $param = array();
        $totalFiltroEsquema = helperVacunas::dataEsquemaForm($data);

        $filtro = array();
        $filtro["id_esquema"] = $data["vacuna"]["idEsquemaForm"];
        $totalFiltroEsquema["filter1"] = $filtro["id_esquema"];
        //print_r($totalFiltroEsquema);exit;
        $param = dalVacunas::ActualizarTabla($conn, "vac_esquema", $esquema, $filtro, $totalFiltroEsquema);
        $id = $param['id'];
        $ok = $param['ok'];
        if (is_array($condiciones)){
            if ($condiciones[0][0]!="")
                $ok = dalVacunas::GuardarCondiciones($conn, $filtro["id_esquema"], $condiciones);
        }
        //print_r($param);

        $param = dalVacunas::GuardarBitacora($conn, "2", "vac_esquema");
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

    public static function BorrarFormEsquema($data) {
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $param = array();
        $filtro = array();
        $filtro["id_esquema"] = $data['idForm'];
        $sql = "delete from vac_dosis
                where id_esq_detalle in 
                (select id_esq_detalle from vac_esq_detalle where id_esquema = ".$data['idForm'].")";
        //echo $sql; exit;
        $param = dalVacunas::ejecutarQuery($conn,$sql);
        $id = $param['id'];
        $ok = $param['ok'];
        
        $param = dalVacunas::BorrarTabla($conn, "vac_esq_detalle", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        
        if ($ok){
            $param = dalVacunas::BorrarTabla($conn, "vac_esquema_condicion", $filtro);
            $id = $param['id'];
            $ok = $param['ok'];
            if ($ok){
                $param = dalVacunas::BorrarTabla($conn, "vac_esquema", $filtro);
                $id = $param['id'];
                $ok = $param['ok'];
                if ($ok){
                    $param = dalVacunas::GuardarBitacora($conn, "3", "vac_esquema");
                    $id = $param['id'];
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
    
    public static function BorrarRegistroDiario($data) {
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $param = array();
        $filtro = array();
        $filtro["id_vac_registro_diario"] = $data['idForm'];
        $param = dalVacunas::BorrarTabla($conn, "vac_registro_diario_dosis", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        
        if ($ok){
            $param = dalVacunas::BorrarTabla($conn, "vac_registro_diario", $filtro);
            $id = $param['id'];
            $ok = $param['ok'];
            if ($ok){
                $param = dalVacunas::GuardarBitacora($conn, "3", "vac_registro_diario");
                $id = $param['id'];
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
        if (!$ok)
            return 2;
        return 1;
    }
    
    public static function BorrarDenominador($data) {
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $param = array();
        $filtro = array();
        $filtro["id_vac_denominador"] = $data['idForm'];
        $param = dalVacunas::BorrarTabla($conn, "vac_denominador_detalle", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        
        if ($ok){
            $param = dalVacunas::BorrarTabla($conn, "vac_denominador", $filtro);
            $id = $param['id'];
            $ok = $param['ok'];
            if ($ok){
                $param = dalVacunas::GuardarBitacora($conn, "3", "vac_denominador");
                $id = $param['id'];
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
        if (!$ok)
            return 2;
        return 1;
    }
    
    public static function GuardarCondiciones($conn, $idForm, $data) {
        //print_r($data);exit;
        //echo $idForm;
        $ok = true;
        $param = array();
        $condiciones = $data;
        if ($condiciones != NULL) {
            dalVacunas::BorrarCondiciones($conn, $idForm);
            $max = sizeof($condiciones);
            for ($i = 0; $i < $max; $i++) {
                if ($condiciones[$i]!=""){
                    $condicion = $condiciones[$i];
                    $insertCondicion = array();
                    $insertCondicion["id_esquema"] = $idForm;
                    $insertCondicion["id_condicion"] = $condicion[0];
                    $param = dalVacunas::GuardarTabla($conn, "vac_esquema_condicion", $insertCondicion);
                    $ok = $param['ok'];
                }
            }
            dalVacunas::GuardarBitacora($conn, "1", "vac_esquema_condicion");
        }
        return $ok;
    }
    
    public static function ValidarEsquemas($esquema){
        $total = helperVacunas::yaExisteEsquema($esquema);
        return $total;
    }


    public static function BorrarCondiciones($conn, $idForm) {
        $filtro = array();
        $filtro["id_esquema"] = $idForm;
        dalVacunas::BorrarTabla($conn, "vac_esquema_condicion", $filtro);
    }
    
    public static function GuardarRegistroDiario($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $registro = helperVacunas::dataRegistroDiarioForm($data);
        //print_r($registro);exit;
        $individuo = helperVacunas::dataTblPersona($data);
        $condiciones = helperVacunas::dataCondicionesPersona($data);
        //print_r($condiciones);exit;
        $yaExisteEsquema = helperVacunas::yaExisteRegistroDiario($registro);
        
        if ($yaExisteEsquema == 0) {
            $param = array();
            $yaExistePersona = helperVacunas::yaExistePersona($individuo);
            //echo "ya existe persona: ".$yaExistePersona;exit;
            if ($yaExistePersona == 0) {
                $param = dalVacunas::GuardarTabla($conn, "tbl_persona", $individuo);
                if ($param['ok']){
                    $param = dalVacunas::GuardarBitacora($conn, "1", "tbl_persona");       
                    $ok = $param['ok'];
                }
            }
            else{
                $totalFiltroPersona = helperVacunas::dataTblPersona($data);
                $filtro = array();
                $filtro["tipo_identificacion"] = $data["individuo"]["tipoId"];
                $filtro["numero_identificacion"] = $data["individuo"]["identificador"];
                $totalFiltroPersona["filter1"] = $filtro["tipo_identificacion"];
                $totalFiltroPersona["filter2"] = $filtro["numero_identificacion"];
                unset($individuo['tipo_identificacion']);
                unset($individuo['numero_identificacion']);
                unset($totalFiltroPersona['tipo_identificacion']);
                unset($totalFiltroPersona['numero_identificacion']);
                //print_r($totalFiltroPersona); exit;
                $param = dalVacunas::ActualizarTabla($conn, "tbl_persona", $individuo, $filtro, $totalFiltroPersona);
                if ($param['ok']){
                    $param = dalVacunas::GuardarBitacora($conn, "2", "tbl_persona"); 
                    $ok = $param['ok'];
                }
            }
            $individuo = helperVacunas::dataTblPersona($data);
            //print_r($individuo);exit;
            $param = dalVacunas::GuardarTabla($conn, "vac_registro_diario", $registro);
            $idFormEsquema = $param['id'];
            $ok = $param['ok'];
            if ($param['ok']){
                $param = dalVacunas::GuardarBitacora($conn, "1", "vac_registro_diario");
                $ok = $param['ok'];
                if (is_array($condiciones)){
                    if ($condiciones[0][0]!="")
                        $ok = dalVacunas::GuardarCondicionesPersona($conn, $individuo, $condiciones);
                }
            }
            if ($ok)
                $conn->commit();
            else {
                $conn->rollback();
                $id = -1;
            }
            $conn->closeConn();
            return "1-".$idFormEsquema;
        } else {
            $conn->closeConn();
            return 2;
        }
    }
    
    public static function GuardarCondicionesPersona($conn, $persona, $data) {
        //print_r($data);exit;
        $ok = true;
        $param = array();
        $condiciones = $data;
        if ($condiciones != NULL) {
            dalVacunas::BorrarCondicionesPersona($conn, $persona);
            $max = sizeof($condiciones);
            for ($i = 0; $i < $max; $i++) {
                if ($condiciones[$i]!=""){
                    $condicion = $condiciones[$i];
                    $insertCondicion = array();
                    //$insertCondicion["id_esquema"] = $idForm;
                    $insertCondicion["tipo_identificacion"] = $persona["tipo_identificacion"];
                    $insertCondicion["numero_identificacion"] = $persona["numero_identificacion"];
                    $insertCondicion["id_condicion"] = $condicion[0];
                    
                    $param = dalVacunas::GuardarTabla($conn, "vac_persona_condicion", $insertCondicion);
                    //print_r($insertCondicion);exit;
                    $ok = $param['ok'];
                }
            }
            dalVacunas::GuardarBitacora($conn, "1", "vac_persona_condicion");
        }
        return $ok;
    }
    
    public static function BorrarCondicionesPersona($conn, $persona) {
        $filtro = array();
        $filtro["tipo_identificacion"] = $persona["tipo_identificacion"];
        $filtro["numero_identificacion"] = $persona["numero_identificacion"];
        dalVacunas::BorrarTabla($conn, "vac_persona_condicion", $filtro);
    }
    
    public static function ActualizarRegistroDiario($data) {
        //print_r($data);exit;
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $registro = array();
        $individuo = array();
        $registro = helperVacunas::dataRegistroDiarioForm($data);
        $individuo = helperVacunas::dataTblPersona($data);
        $condiciones = helperVacunas::dataCondicionesPersona($data);
        $param = array();
        $totalFiltroRegistro = helperVacunas::dataRegistroDiarioForm($data);
        $filtro = array();
        $filtro["id_vac_registro_diario"] = $data["formulario"]["idForm"];
        $totalFiltroRegistro["filter1"] = $filtro["id_vac_registro_diario"];
        //print_r($condiciones);exit;
        $param = dalVacunas::ActualizarTabla($conn, "vac_registro_diario", $registro, $filtro, $totalFiltroRegistro);
        $id = $param['id'];
        $ok = $param['ok'];
        if ($ok){
            $param = dalVacunas::GuardarBitacora($conn, "2", "vac_registro_diario");
            $id = $param['id'];
            $ok = $param['ok'];
            if ($ok){
                dalVacunas::BorrarCondicionesPersona($conn, $individuo);
                if (is_array($condiciones)){
                    if ($condiciones[0][0]!="")
                        $ok = dalVacunas::GuardarCondicionesPersona($conn, $individuo, $condiciones);
                }
                $filtro = array();
                $totalFiltroPersona = helperVacunas::dataTblPersona($data);
                $filtro["tipo_identificacion"] = $data["individuo"]["tipoId"];
                $filtro["numero_identificacion"] = $data["individuo"]["identificador"];
                $totalFiltroPersona["filter1"] = $filtro["tipo_identificacion"];
                $totalFiltroPersona["filter2"] = $filtro["numero_identificacion"];
                unset($individuo['tipo_identificacion']);
                unset($individuo['numero_identificacion']);
                unset($totalFiltroPersona['tipo_identificacion']);
                unset($totalFiltroPersona['numero_identificacion']);
                $param = dalVacunas::ActualizarTabla($conn, "tbl_persona", $individuo, $filtro, $totalFiltroPersona);
                if ($param['ok']){
                    $param = dalVacunas::GuardarBitacora($conn, "2", "tbl_persona"); 
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
    
    public static function GuardarDenominador($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $denominador= helperVacunas::dataDenominadoresForm($data);
        
        $param = array();
        $param = dalVacunas::GuardarTabla($conn, "vac_denominador", $denominador);
        $idForm = $param['id'];
        $ok = $param['ok'];
        if ($param['ok']){
            $param = dalVacunas::GuardarBitacora($conn, "1", "vac_denominador");
            $ok = $param['ok'];
        }
        if ($ok)
            $conn->commit();
        else {
            $conn->rollback();
            $id = 2;
        }
        $conn->closeConn();
        return "1-".$idForm;
    }
    
    public static function ActualizarDenominador($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $denominador= helperVacunas::dataDenominadoresForm($data);
        $totalFiltro= helperVacunas::dataDenominadoresForm($data);

        $filtro = array();
        $filtro["id_vac_denominador"] = $data["distribucion"]["idForm"];
        $totalFiltro["filter1"] = $filtro["id_vac_denominador"];
        //print_r($totalFiltroEsquema);exit;
        $param = dalVacunas::ActualizarTabla($conn, "vac_denominador", $denominador, $filtro, $totalFiltro);
        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);

        $param = dalVacunas::GuardarBitacora($conn, "2", "vac_denominador");
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

    public static function GuardarTabla($conn, $tabla, $objeto) {
        $ok = true;
        //print_r($objeto);
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

    public static function ActualizarTabla($conn, $tabla, $objeto, $filtro, $total) {
        $ok = true;
        $sql = Actions::ActualizarQuery($tabla, $objeto, $filtro);
//        print_r($total);
//        echo "SQL: ".$sql;exit;
        $conn->prepare($sql);
        $conn->params($total);
        $conn->execute() ? null : $ok = false;
        $error = $conn->getError();
        $conn->closeStmt();
        $conn->getID();

//        echo "Error: ".$error." y ID:".$id;
        if (!$ok) {
            $conn->rollback();
            $conn->closeConn();
            echo "<br/>Error";
            echo "SQL:" . $sql . "<br/>Error" . $error;
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
        $id = $conn->getID();
        $param = array();
        if (!$ok) {
            $conn->rollback();
            $conn->closeConn();
            $ok = "SQL:" . $sql . "<br/>Error" . $error;
        }
        $param['id'] = $id;
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
        //echo $sql."<br/>";
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
    
    public static function BuscarUbiDenoExcel($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
          $sql = "SELECT id_vac_denominador, c_r.id_region, c_d.id_distrito, c_d.id_provincia, c_c.id_corregimiento  
                    FROM  ((cat_region_salud c_r 
                    LEFT JOIN cat_distrito c_d ON c_r.id_region=c_d.id_region ) 
                    LEFT JOIN cat_corregimiento c_c ON c_d.id_distrito= c_c.id_distrito) 
                    LEFT JOIN `vac_denominador` v_d ON ((c_r.id_region=v_d.id_region AND c_d.id_distrito= v_d.id_distrito AND c_c.id_corregimiento= v_d.id_corregimiento) OR id_vac_denominador is NULL )
                    WHERE c_r.cod_ref_minsa='".$data["region"]."' AND c_d.cod_ref_minsa='".$data["distrito"]."' AND c_c.cod_ref_minsa='".$data["corregimiento"]."'";
          
        $conn->prepare($sql);
        $conn->execute();
        $resp = $conn->fetch();
        $conn->closeConn();
        return $resp;
    }
    
}

?>