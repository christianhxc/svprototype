<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helpertb.php');
require_once ('libs/caus/clsCaus.php');

class daltb {

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

    
    // Funcion para guardar datos en la tabla principal
    public static function Guardartb($data) {

        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        // ---------------------------------------------------------------
        // Datos del Individuo en un array
        // ---------------------------------------------------------------
//        echo "<pre>";
//        print_r($data);
//        echo "<br/>----+++++++++++<br/>";
        $individuo = helpertb::dataTblPersona($data);
        $tb = helpertb::datatbForm($data);

//        print_r($individuo);
//        print_r($tb);
//        echo "</pre>";
//        exit;
        
        $yaExisteTB = helpertb::yaExistetb($tb);
        
        if ($yaExisteTB == 0) {
            $param = array();

            $yaExistePersona = helpertb::yaExistePersona($individuo);
            
            
            
            if ($yaExistePersona == 0) {
                
                $param = self::GuardarTabla($conn, "tbl_persona", $individuo);
                $id = $param['id'];
                $ok = $param['ok'];
                //print_r($param);

                $param = self::GuardarBitacora($conn, "1", "tbl_persona");
                $id = $param['id'];
                $ok = $param['ok'];
                //print_r($param);
                
                
            } else {
                //echo "La persona ya existe";
                $totalFiltroIndividuo = helpertb::dataTblPersona($data);

                $filtro = array();
                $filtro["tipo_identificacion"] = $individuo["tipo_identificacion"];
                $filtro["numero_identificacion"] = $individuo["numero_identificacion"];

                //individuo + filtro esto es para los parametros, no importa el nombre de los filtros.
                $totalFiltroIndividuo["filter1"] = $filtro["tipo_identificacion"];
                $totalFiltroIndividuo["filter2"] = $filtro["numero_identificacion"];
                //print_r($totalFiltroIndividuo);
                
                $param = self::ActualizarTabla($conn, "tbl_persona", $individuo, $filtro, $totalFiltroIndividuo);
                $id = $param['id'];
                $ok = $param['ok'];
                //print_r($param);
                
//                echo "aqui Actualizar tbl_persona";

                $param = self::GuardarBitacora($conn, "2", "tbl_persona");
                $id = $param['id'];
                $ok = $param['ok'];
                //print_r($param);
                
            }

            
//            echo "aqui Guardar Tabla tb_form";
                            
            $param = self::GuardarTabla($conn, "tb_form", $tb);
            $idTB = $param['id'];
            $ok = $param['ok'];
//            print_r($param);

//            echo "aqui despues Guardar Tabla tb_form";
            
            $param = self::GuardarBitacora($conn, "1", "tb_form");
            $id = $param['id'];
            $ok = $param['ok'];
//            print_r($param);
            
//                    exit;
//
            self::GuardartbMDR($conn, $idTB, $data["antecedentes"]["MDR"]);
            self::GuardartbVisitas($conn, $idTB, $data["visita"]);
            self::GuardartbInmunodepresor($conn, $idTB, $data["antecedentes"]["inmunodepresor"]);
            self::GuardartbControles($conn, $idTB, $data["control"]);
            
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

    public static function Actualizartb($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $individuo = helpertb::dataTblPersona($data);
        
        $tb = helpertb::datatbForm($data);
//        $muestras = helpertb::datatbMuestrasSILAB($data);
//        $pruebas = helpertb::dataUcetiPruebasSILAB($data);
                
        $idTB = $data['formulario']['id_tb'];
//        echo "<br/><br/>Muestras ".$data["muestras"]["globalMuestras"]."<br/><br/>";
//        echo "<pre>",print_r($individuo),"</pre>";
//        exit;
//        echo "<br/><br/>Pruebas ".$data["muestras"]["globalPruebas"]."<br/><br/>";
//        echo "<pre>",print_r($pruebas),"</pre>";

        $param = array();

        $totalFiltroIndividuo = helpertb::dataTblPersona($data);

        $filtro = array();
        $filtro["tipo_identificacion"] = $individuo["tipo_identificacion"];
        $filtro["numero_identificacion"] = $individuo["numero_identificacion"];

        //individuo + filtro esto es para los parametros, no importa el nombre de los filtros.
        $totalFiltroIndividuo["filter1"] = $filtro["tipo_identificacion"];
        $totalFiltroIndividuo["filter2"] = $filtro["numero_identificacion"];
//        echo "<pre>";
//        print_r($totalFiltroIndividuo);
//        echo "</pre>";
//        exit;

        $param = self::ActualizarTabla($conn, "tbl_persona", $individuo, $filtro, $totalFiltroIndividuo);
        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);

        $param = self::GuardarBitacora($conn, "2", "tbl_persona");
        $id = $param['id'];
        $ok = $param['ok'];
        
        $totalFiltroUceti = helpertb::datatbForm($data);

        $filtro = array();
        $filtro["id_tb"] = $data['formulario']['id_tb'];
//        $filtro["tipo_identificacion"] = $individuo["tipo_identificacion"];
//        $filtro["numero_identificacion"] = $individuo["numero_identificacion"];

        //individuo + filtro esto es para los parametros, no importa el nombre de los filtros.
        $totalFiltroUceti["filter_1"] = $filtro["id_tb"];
//        $totalFiltroUceti["filter2"] = $filtro["tipo_identificacion"];
//        $totalFiltroUceti["filter3"] = $filtro["numero_identificacion"];


        self::TBform_Act($conn, $filtro["id_tb"]);
//        echo "<pre>";
//        print_r($tb);echo "<br> ++ ";
//        print_r($filtro);echo "<br> -- ";
//        print_r($totalFiltroUceti);echo "<br> ** ";
//        echo "</pre>";        
        $param = self::ActualizarTabla($conn, "tb_form", $tb, $filtro, $totalFiltroUceti);
        $id = $param['id'];
        $ok = $param['ok'];
        
//        echo "<pre>",print_r($tb),"</pre><br/>";
//        echo "<pre>",print_r($totalFiltroUceti),"</pre>";
//        exit;
        //print_r($param);
        
        $param = self::GuardarBitacora($conn, "2", "tb_form");
        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);

        self::GuardartbMDR($conn, $idTB, $data["antecedentes"]["MDR"]);
//        print_r($data["visita"]);
//        exit;
        self::GuardartbVisitas($conn, $idTB, $data["visita"]);
        self::GuardartbInmunodepresor($conn, $idTB, $data["antecedentes"]["inmunodepresor"]);
        self::GuardartbControles($conn, $idTB, $data["control"]);

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

    public static function Borrartb($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $param = array();

        $filtro = array();
        $filtro["id_flureg"] = $data['idUceti'];

        $param = self::BorrarTabla($conn, "flureg_form", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);

        $param = self::GuardarBitacora($conn, "3", "flureg_form");
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
        $conn->prepare($sql);
//        $comma_separated = "('" . implode("','", $objeto) . "')";
//        echo $sql . " values " . $comma_separated."<br/>";
//        exit;
        $conn->params($objeto);
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
            exit;
        }
        $param = array();
        $param['id'] = $id;
        $param['ok'] = $ok;
        return $param;
    }

    public static function ActualizarTabla($conn, $tabla, $objeto, $filtro, $total) {
        $ok = true;
        $sql = Actions::ActualizarQuery($tabla, $objeto, $filtro);
        $conn->prepare($sql);
        $conn->params($total);
        
        // query pare revisar
        
//        echo "</pre>";print_r($objeto);echo "</pre> <br/>";
//        $comma_separated = "('" . implode("','", $objeto) . "')";
//        echo $sql . " values " . $comma_separated."<br/>";

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
    
    public static function TBform_Act($conn, $ID_tb) {
        $TBfiltro = array();
        $TBfiltro["id_tb"] = $ID_tb;
        self::BorrarTabla($conn, "tb_form", $TBfiltro);
        self::GuardarTabla($conn, "tb_form", $TBfiltro);
    } 

    public static function BorrarTabla($conn, $tabla, $filtro) {
        $ok = true;
        $sql = Actions::BorrarQuery($tabla, $filtro);
//        echo $sql ."<br/>";
//        exit;
        $conn->prepare($sql);
        $conn->params($filtro);
//        print_r($total);
        $conn->execute() ? null : $ok = false;
        $error = $conn->getError();
        $conn->closeStmt();
        $conn->getID();

//        echo "Error: ".$error." y ID:".$id."-- SQL:".$sql;
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
//        $sql = "INSERT INTO bitacora(usuid,fecha,accion,tabla) 
//            VALUES(".$bitacora["usuid"].",".$bitacora["fecha"].",".$bitacora["accion"].",".$bitacora["tabla"].")";
//        echo $sql."<br/>";
        $conn->prepare($sql);
//        print_r($bitacora);
        $conn->params($bitacora);
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
            exit;
        }
        $param = array();
        $param['id'] = $id;
        $param['ok'] = $ok;
        return $param;
    }
    
    public static function GuardartbInmunodepresor($conn, $id_TB, $data) {
        $Inmunodepresores = $data;
        if (is_array($Inmunodepresores)) {
            self::BorrartbInmunodepresores($conn, $id_TB);
            foreach ($Inmunodepresores as $Inmunodepresor) {
                $insertarInmunodepresores;
                $insertarInmunodepresores["id_tb"] = $id_TB;
                $insertarInmunodepresores["id_inmunodepresor"] = $Inmunodepresor;
                self::GuardarTabla($conn, "tb_inmunodepresor", $insertarInmunodepresores);
            }
            self::GuardarBitacora($conn, "1", "tb_inmunodepresor");  
        }
    }

    public static function BorrartbInmunodepresores($conn, $idTB) {
        $filtro = array();
        $filtro["id_tb"] = $idTB;
        self::BorrarTabla($conn, "tb_inmunodepresor", $filtro);
    }

    public static function GuardartbVisitas($conn, $id_TB, $data) {
        $visitas = $data;
        if (is_array($visitas)) {
            //echo "Tipo id: " . $tipoIdentificacion . " Numero id: " . $numeroIdentificacion . "<br/>";
            self::BorrartbVisitas($conn, $id_TB);

            foreach ($visitas as $visita) {
                $insertarVisita = array();
                
                $insertarVisita["id_tipo_visita"] = $visita["id_tipo_visita"];
                $insertarVisita["fecha_visita"] =  helperString::toDate($visita["fecha_visita"]);
                $insertarVisita["id_tb_form"] = $id_TB;
                self::GuardarTabla($conn, "tb_visitas", $insertarVisita);
            }
            
             self::GuardarBitacora($conn, "1", "tb_visitas");
        }
    }

    public static function BorrartbVisitas($conn, $ID_tb) {
        $filtro = array();
        $filtro["id_tb_form"] = $ID_tb;
        self::BorrarTabla($conn, "tb_visitas", $filtro);
    }

    public static function GuardartbControles($conn, $id_TB, $data) {
        $controles = $data;
        if (is_array($controles)) {
            //echo "Tipo id: " . $tipoIdentificacion . " Numero id: " . $numeroIdentificacion . "<br/>";
            self::BorrartbControles($conn, $id_TB);

            foreach ($controles as $control) {
                $insertarControl = array();
                $insertarControl["id_tb_form"]= $id_TB;
                (!isset($control["fecha_control"]) ? NULL : $insertarControl["fecha_control"] = helperString::toDate($control["fecha_control"]));
                (!isset($control["peso"]) ? NULL : $insertarControl["peso"] = $control["peso"]);
                (!isset($control["numero_dosis"]) ? NULL : $insertarControl["no_dosis_control"] = $control["numero_dosis"]);
                (!isset($control["fecha_BK"]) ? NULL : $insertarControl["fecha_BK_control"] = helperString::toDate($control["fecha_BK"]));
                (!isset($control["resultado_BK"]) ? NULL : $insertarControl["res_BK_control"] = $control["resultado_BK"]);
                (!isset($control["clas_BK"]) ? NULL : $insertarControl["id_clasificacion_BK"] = $control["clas_BK"]);
                (!isset($control["fecha_cultivo_control"]) ? NULL : $insertarControl["fecha_cultivo_control"] = helperString::toDate($control["fecha_cultivo_control"]));

                (!isset($control["res_cul_contr"]) ? NULL : $insertarControl["res_cultivo_control"] = $control["res_cul_contr"]);
                (!isset($control["control_H"]) ? NULL : $insertarControl["control_H"] = $control["control_H"]);
                (!isset($control["control_R"]) ? NULL : $insertarControl["control_R"] = $control["control_R"]);
                (!isset($control["control_Z"]) ? NULL : $insertarControl["control_Z"] = $control["control_Z"]);
                (!isset($control["control_E"]) ? NULL : $insertarControl["control_E"] = $control["control_E"]);
                (!isset($control["control_S"]) ? NULL : $insertarControl["control_S"] = $control["control_S"]);
                (!isset($control["control_Otros"]) ? NULL : $insertarControl["control_Otros"] = $control["control_Otros"]);
                (!isset($control["control_fluoroquinolonas"]) ? NULL : $insertarControl["fluoroquinolonas"] = $control["control_fluoroquinolonas"]);
                (!isset($control["control_2linea"]) ? NULL : $insertarControl["inyec_2_linea"] = $control["control_2linea"]);
                (!isset($control["reac_adv"]) ? NULL : $insertarControl["reac_adv"] = $control["reac_adv"]);

                (!isset($control["fecha_reac_adv"]) ? NULL : $insertarControl["fecha_reac_adv"] = helperString::toDate($control["fecha_reac_adv"]));

                (!isset($control["clasificacion"]) ? NULL : $insertarControl["id_clasi_reac_adv"] = $control["clasificacion"]);
                (!isset($control["conducta"]) ? NULL : $insertarControl["id_conducta"] = $control["conducta"]);
                (!isset($control["hospitalizado"]) ? NULL : $insertarControl["hospitalizado"] = $control["hospitalizado"]);
                (!isset($control["preso"]) ? NULL : $insertarControl["preso"] = $control["preso"]);

                (!isset($control["fecha_preso"]) ? NULL : $insertarControl["fecha_preso"] = helperString::toDate($control["fecha_preso"]));

                (!isset($control["usr_drogas"]) ? NULL : $insertarControl["usr_drogas"] = $control["usr_drogas"]);
                (!isset($control["alcoholismo"]) ? NULL : $insertarControl["alcoholismo"] = $control["alcoholismo"]);
                (!isset($control["tabaquismo"]) ? NULL : $insertarControl["tabaquismo"] = $control["tabaquismo"]);
                (!isset($control["mineria"]) ? NULL : $insertarControl["mineria"] = $control["mineria"]);
                (!isset($control["hacinamiento"]) ? NULL : $insertarControl["hacinamiento"] = $control["hacinamiento"]);
                (!isset($control["empleado"]) ? NULL : $insertarControl["empleado"] = $control["empleado"]);
         
                self::GuardarTabla($conn, "tb_control", $insertarControl);
            }
            
             self::GuardarBitacora($conn, "1", "tb_control");
        }
    }

    public static function BorrartbControles($conn, $ID_tb) {
        $filtro = array();
        $filtro["id_tb_form"] = $ID_tb;
        self::BorrarTabla($conn, "tb_control", $filtro);
    }    
    
    // Ya modificado para guardar los MDR
    public static function GuardartbMDR($conn, $id_TB, $data) {
        $MDR = $data;
        if (is_array($MDR)) {
            //echo "Tipo id: " . $tipoIdentificacion . " Numero id: " . $numeroIdentificacion . "<br/>";
            self::BorrartbMDR($conn, $id_TB);

            foreach ($MDR as $one_MDR) {
                $insertarMDR = array();
                $insertarMDR["id_tb"] = $id_TB;
                $insertarMDR["id_grupo_riesgo_MDR"] = $one_MDR;
                self::GuardarTabla($conn, "tb_grupo_riesgo_mdr", $insertarMDR);
            }
            self::GuardarBitacora($conn, "1", "tb_grupo_riesgo_mdr");
        }
    }

    public static function BorrartbMDR($conn, $idTB) {
        $filtro = array();
        $filtro["id_tb"] = $idTB;
        self::BorrarTabla($conn, "tb_grupo_riesgo_mdr", $filtro);
    }

    public static function GuardartbMuestras($conn, $idFormUceti, $data) {
//        echo "id form ".$idFormUceti."<br/>";
//        print_r($data);
//        exit;
        $muestras = $data;
        if ($muestras != NULL) {
            self::BorrartbMuestras($conn, $idFormUceti);
            $max = sizeof($muestras);
            for ($i = 0; $i < $max; $i++) {
                $muestra = $muestras[$i];
                if ($muestra["id_muestra"] != "" && sizeof($muestra) > 1) {
                    $muestra["id_flureg"] = $idFormUceti;
                    //print_r($muestra);Exit;
                    self::GuardarTabla($conn, "flureg_muestra_silab", $muestra);
                }
            }
            self::GuardarBitacora($conn, "1", "flureg_muestra_silab");
        }
    }

    public static function BorrartbMuestras($conn, $idFormUceti) {
        $filtro = array();
        $filtro["id_flureg"] = $idFormUceti;
        self::BorrarTabla($conn, "flureg_muestra_silab", $filtro);
    }

    public static function GuardartbPruebasSilab($conn, $idFormUceti, $data) {
        $pruebas = $data;
        if ($pruebas != NULL) {
            self::BorrarUcetiPruebasSilab($conn, $idFormUceti);
            $max = sizeof($pruebas);
            for ($i = 0; $i < $max; $i++) {
                if (isset($pruebas[$i])) {
                    $prueba = $pruebas[$i];
                    if ($prueba["id_muestra"] != "" && sizeof($prueba) > 1) {
                        $prueba["id_flureg"] = $idFormUceti;
                        self::GuardarTabla($conn, "flureg_muestra_prueba_silab", $prueba);
                    }
                }
            }
            self::GuardarBitacora($conn, "1", "flureg_muestra_prueba_silab");
        }
    }

    public static function BorrartbPruebasSilab($conn, $idFormUceti) {
        $filtro = array();
        $filtro["id_flureg"] = $idFormUceti;
        self::BorrarTabla($conn, "flureg_muestra_prueba_silab", $filtro);
    }

    public static function BorrartbPruebaSilab($conn, $idMuestra) {
        $filtro = array();
        $filtro["id_muestra"] = $idMuestra;
        self::BorrarTabla($conn, "flureg_muestra_prueba_silab", $filtro);
        self::GuardarBitacora($conn, "3", "flureg_muestra_prueba_silab");
    }

    public static function BorrartbMuestraSilab($conn, $idMuestra) {
        $filtro = array();
        $filtro["id_muestra"] = $idMuestra;
        self::BorrarTabla($conn, "flureg_muestra_silab", $filtro);
        self::GuardarBitacora($conn, "3", "flureg_muestra_silab");
    }

}

?>