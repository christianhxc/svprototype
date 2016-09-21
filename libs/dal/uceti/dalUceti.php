<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperUceti.php');
require_once ('libs/caus/clsCaus.php');

class dalUceti {

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

    public static function GuardarUceti($data) {

//        return "Done!";
//        exit;
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        // ---------------------------------------------------------------
        // Datos del Individuo en un array
        // ---------------------------------------------------------------
        //return print_r($data["individuo"]);exit;
        $individuo = helperUceti::dataTblPersona($data);
        $uceti = helperUceti::dataUcetiForm($data);
        $muestras = helperUceti::dataUcetiMuestrasSILAB($data);
        $pruebas = helperUceti::dataUcetiPruebasSILAB($data);
        
        $yaExisteUceti = helperUceti::yaExisteUceti($uceti);

        if ($yaExisteUceti == 0) {
            $param = array();

            $yaExistePersona = helperUceti::yaExistePersona($individuo);
            if ($yaExistePersona == 0) {
                $param = dalUceti::GuardarTabla($conn, "tbl_persona", $individuo);
                $id = $param['id'];
                $ok = $param['ok'];
                //print_r($param);

                $param = dalUceti::GuardarBitacora($conn, "1", "tbl_persona");
                $id = $param['id'];
                $ok = $param['ok'];
                //print_r($param);
            } else {
                //echo "La persona ya existe";
                $totalFiltroIndividuo = helperUceti::dataTblPersona($data);

                $filtro = array();
                $filtro["tipo_identificacion"] = $individuo["tipo_identificacion"];
                $filtro["numero_identificacion"] = $individuo["numero_identificacion"];

                //individuo + filtro esto es para los parametros, no importa el nombre de los filtros.
                $totalFiltroIndividuo["filter1"] = $filtro["tipo_identificacion"];
                $totalFiltroIndividuo["filter2"] = $filtro["numero_identificacion"];
                //print_r($totalFiltroIndividuo);
                $param = dalUceti::ActualizarTabla($conn, "tbl_persona", $individuo, $filtro, $totalFiltroIndividuo);
                $id = $param['id'];
                $ok = $param['ok'];
                //print_r($param);

                $param = dalUceti::GuardarBitacora($conn, "2", "tbl_persona");
                $id = $param['id'];
                $ok = $param['ok'];
                //print_r($param);
            }

            $param = dalUceti::GuardarTabla($conn, "flureg_form", $uceti);
            $idUceti = $param['id'];
            $ok = $param['ok'];
            //print_r($param);

            $param = dalUceti::GuardarBitacora($conn, "1", "flureg_form");
            $id = $param['id'];
            $ok = $param['ok'];
            //print_r($param);

            dalUceti::GuardarUcetiVacunas($conn, $individuo["tipo_identificacion"], $individuo["numero_identificacion"], $data);
            dalUceti::GuardarUcetiEnfermedades($conn, $individuo["tipo_identificacion"], $individuo["numero_identificacion"], $data);
            dalUceti::GuardarUcetiTipoMuestras($conn, $idUceti, $data);
            
            if ($muestras != NULL && isset($muestras[0]))
                dalUceti::GuardarUcetiMuestras($conn, $idUceti, $muestras);
            if ($pruebas != NULL && isset($pruebas[0]))
                dalUceti::GuardarUcetiPruebasSilab($conn, $idUceti, $pruebas);
            //print_r($ucetiEnfermedades);
            
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

    public static function ActualizarUceti($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $individuo = helperUceti::dataTblPersona($data);
        $uceti = helperUceti::dataUcetiForm($data);
        $muestras = helperUceti::dataUcetiMuestrasSILAB($data);
        $pruebas = helperUceti::dataUcetiPruebasSILAB($data);
                
        $idUceti = $data['formulario']['id_uceti'];
//        echo "<br/><br/>Muestras ".$data["muestras"]["globalMuestras"]."<br/><br/>";
//        echo "<pre>",print_r($muestras),"</pre>";
//        echo "<br/><br/>Pruebas ".$data["muestras"]["globalPruebas"]."<br/><br/>";
//        echo "<pre>",print_r($pruebas),"</pre>";
//        exit;
//        
        $param = array();

        if ($muestras != NULL && isset($muestras[0]))
            dalUceti::GuardarUcetiMuestras($conn, $idUceti, $muestras);
        if ($pruebas != NULL && isset($pruebas[0]))
            dalUceti::GuardarUcetiPruebasSilab($conn, $idUceti, $pruebas);
//        exit;
        $totalFiltroIndividuo = helperUceti::dataTblPersona($data);

        $filtro = array();
        $filtro["tipo_identificacion"] = $individuo["tipo_identificacion"];
        $filtro["numero_identificacion"] = $individuo["numero_identificacion"];

        //individuo + filtro esto es para los parametros, no importa el nombre de los filtros.
        $totalFiltroIndividuo["filter1"] = $filtro["tipo_identificacion"];
        $totalFiltroIndividuo["filter2"] = $filtro["numero_identificacion"];
        //print_r($totalFiltroIndividuo);
        $param = dalUceti::ActualizarTabla($conn, "tbl_persona", $individuo, $filtro, $totalFiltroIndividuo);
        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);

        $param = dalUceti::GuardarBitacora($conn, "2", "tbl_persona");
        $id = $param['id'];
        $ok = $param['ok'];

        $totalFiltroUceti = helperUceti::dataUcetiForm($data);

        $filtro = array();
        $filtro["id_flureg"] = $data['formulario']['id_uceti'];
        $filtro["tipo_identificacion"] = $individuo["tipo_identificacion"];
        $filtro["numero_identificacion"] = $individuo["numero_identificacion"];

        //individuo + filtro esto es para los parametros, no importa el nombre de los filtros.
        $totalFiltroUceti["filter1"] = $filtro["id_flureg"];
        $totalFiltroUceti["filter2"] = $filtro["tipo_identificacion"];
        $totalFiltroUceti["filter3"] = $filtro["numero_identificacion"];
        //print_r($totalFiltroIndividuo);
        $param = dalUceti::ActualizarTabla($conn, "flureg_form", $uceti, $filtro, $totalFiltroUceti);
        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);

        $param = dalUceti::GuardarBitacora($conn, "2", "flureg_form");
        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);

        if ($uceti["vac_tarjeta"] == '1') {
            dalUceti::GuardarUcetiVacunas($conn, $filtro["tipo_identificacion"], $filtro["numero_identificacion"], $data);
        } else {
            dalUceti::BorrarUcetiVacunas($conn, $filtro["tipo_identificacion"], $filtro["numero_identificacion"]);
        }
//        dalUceti::GuardarUcetiVacunas($conn, $filtro["tipo_identificacion"], $filtro["numero_identificacion"], $data);
        dalUceti::GuardarUcetiEnfermedades($conn, $filtro["tipo_identificacion"], $filtro["numero_identificacion"], $data);
        dalUceti::GuardarUcetiTipoMuestras($conn, $idUceti, $data);

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

    public static function BorrarUceti($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $param = array();

        $filtro = array();
        $filtro["id_flureg"] = $data['idUceti'];

        $param = dalUceti::BorrarTabla($conn, "flureg_form", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);

        $param = dalUceti::GuardarBitacora($conn, "3", "flureg_form");
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
//        $comma_separated = "('" . implode("', '", $objeto) . "')";
//        echo $sql . " values " . $comma_separated."<br/>";
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
        //echo $sql;
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
    
    public static function GuardarUcetiTipoMuestras($conn, $idUceti, $data) {
        $tipoMuestrasTotal = (!isset($data["muestras_laboratorio"]["globalMuestrasUceti"]) ? NULL : $data["muestras_laboratorio"]["globalMuestrasUceti"]);
        if ($tipoMuestrasTotal != NULL) {
            dalUceti::BorrarUcetiTipoMuestras($conn, $idUceti);
            $tipoMuestrasSplit = explode("###", $tipoMuestrasTotal);
            foreach ($tipoMuestrasSplit as $tipoMuestraSplit) {
                $tipoMuestra = explode("-", $tipoMuestraSplit);
                $insertarTipoMuestra["id_flureg"] = $idUceti;
                $insertarTipoMuestra["id_cat_muestra_laboratorio"] = $tipoMuestra[0];
                $insertarTipoMuestra["fecha_toma"] = (!isset($tipoMuestra[1]) ? NULL : helperString::toDate($tipoMuestra[1]));
                $insertarTipoMuestra["fecha_envio"] = (!isset($tipoMuestra[2]) ? NULL : helperString::toDate($tipoMuestra[2]));
                $insertarTipoMuestra["fecha_recibo_laboratorio"] = (!isset($tipoMuestra[3]) ? NULL : helperString::toDate($tipoMuestra[3]));
                dalUceti::GuardarTabla($conn, "flureg_muestra_laboratorio", $insertarTipoMuestra);
//                echo "<pre>",print_r($tipoMuestra),"</pre>";
            }
            dalUceti::GuardarBitacora($conn, "1", "flureg_muestra_laboratorio");  
        }
    }

    public static function BorrarUcetiTipoMuestras($conn, $idUceti) {
        $filtro = array();
        $filtro["id_flureg"] = $idUceti;
        dalUceti::BorrarTabla($conn, "flureg_muestra_laboratorio", $filtro);
    }

    public static function GuardarUcetiEnfermedades($conn, $tipoIdentificacion, $numeroIdentificacion, $data) {
        $enfermedades = $data["antecedentes"]["resultadoCronica"];
        if ($enfermedades != NULL) {
            //echo "Tipo id: " . $tipoIdentificacion . " Numero id: " . $numeroIdentificacion . "<br/>";
            dalUceti::BorrarUcetiEnfermedades($conn, $tipoIdentificacion, $numeroIdentificacion);
            $max = sizeof($enfermedades);
            $uceti["riesgo_enf_cronica"] = (!isset($data["antecedentes"]["cronica"]) ? NULL : $data["antecedentes"]["cronica"]);
            if (isset($uceti["riesgo_enf_cronica"])) {
                if ($uceti["riesgo_enf_cronica"] == "1") {
                    for ($i = 0; $i < $max; $i++) {
                        $enfermedad = $enfermedades[$i];
                        $insertarEnfermedad = array();
                        $idEnfermedad = $enfermedad["id"];
                        $idResultado = $enfermedad["res"];
                        //list($idEnfermedad, $idResultado) = explode('-', $enfermedad);
                        $insertarEnfermedad["tipo_identificacion"] = $tipoIdentificacion;
                        $insertarEnfermedad["numero_identificacion"] = $numeroIdentificacion;
                        $insertarEnfermedad['id_cat_enfermedad_cronica'] = $idEnfermedad;
                        $insertarEnfermedad['resultado'] = $idResultado;
                        dalUceti::GuardarTabla($conn, "flureg_enfermedad_cronica", $insertarEnfermedad);
                        //echo "Enfermedad id: " . $enfermedad["id"] . " Resultado: " . $enfermedad["res"] . "<br/>";
                    }
                    dalUceti::GuardarBitacora($conn, "1", "flureg_enfermedad_cronica");
                }
            }
        }
    }

    public static function BorrarUcetiEnfermedades($conn, $tipoIdentificacion, $numeroIdentificacion) {
        $filtro = array();
        $filtro["tipo_identificacion"] = $tipoIdentificacion;
        $filtro["numero_identificacion"] = $numeroIdentificacion;
        dalUceti::BorrarTabla($conn, "flureg_enfermedad_cronica", $filtro);
    }

    public static function GuardarUcetiVacunas($conn, $tipoIdentificacion, $numeroIdentificacion, $data) {
        $vacunas = $data["antecedentes"]["antecedenteVacunal"];
        if ($vacunas != NULL) {
            //echo "Tipo id: " . $tipoIdentificacion . " Numero id: " . $numeroIdentificacion . "<br/>";
            dalUceti::BorrarUcetiVacunas($conn, $tipoIdentificacion, $numeroIdentificacion);
            $max = sizeof($vacunas);
            for ($i = 0; $i < $max; $i++) {
                $vacuna = $vacunas[$i];
                $insertarVacuna = array();
                $idVacuna = $vacuna["id"];
                $dosis = (!isset($vacuna["dosis"]) ? NULL : $vacuna["dosis"]);
                //$desconoce = (!isset($vacuna["desc"]) ? NULL : $vacuna["desc"]);
                $fecha = (!isset($vacuna["fecha"]) ? NULL : helperString::toDate($vacuna["fecha"]));
                $insertarVacuna["tipo_identificacion"] = $tipoIdentificacion;
                $insertarVacuna["numero_identificacion"] = $numeroIdentificacion;
                $insertarVacuna['id_cat_antecendente_vacunal'] = $idVacuna;
                $insertarVacuna['dosis'] = $dosis;
                //$insertarVacuna['desconoce'] = $desconoce;
                $insertarVacuna['fecha'] = $fecha;
                dalUceti::GuardarTabla($conn, "flureg_antecendente_vacunal", $insertarVacuna);
                //echo "Vacuna id: " . vacuna["id"] . " Resultado: " . vacuna["dosis"] . "<br/>";
            }
            dalUceti::GuardarBitacora($conn, "1", "flureg_antecendente_vacunal");
        }
    }

    public static function BorrarUcetiVacunas($conn, $tipoIdentificacion, $numeroIdentificacion) {
        $filtro = array();
        $filtro["tipo_identificacion"] = $tipoIdentificacion;
        $filtro["numero_identificacion"] = $numeroIdentificacion;
        dalUceti::BorrarTabla($conn, "flureg_antecendente_vacunal", $filtro);
    }

    public static function GuardarUcetiMuestras($conn, $idFormUceti, $data) {
//        echo "id form ".$idFormUceti."<br/>";
//        print_r($data);
//        exit;
        $muestras = $data;
        if ($muestras != NULL) {
            dalUceti::BorrarUcetiMuestras($conn, $idFormUceti);
            $max = sizeof($muestras);
            for ($i = 0; $i < $max; $i++) {
                $muestra = $muestras[$i];
                if ($muestra["id_muestra"] != "" && sizeof($muestra) > 1) {
                    $muestra["id_flureg"] = $idFormUceti;
                    //print_r($muestra);Exit;
                    dalUceti::GuardarTabla($conn, "flureg_muestra_silab", $muestra);
                }
            }
            dalUceti::GuardarBitacora($conn, "1", "flureg_muestra_silab");
        }
    }

    public static function GetMuestras($conn, $idForm = "") {
        $sql = "SELECT id_flureg, resultado, tipo1, subtipo1 FROM flureg_muestra_silab ";
        if ($idForm != ""){
            $sql .= "WHERE id_flureg = '".$idForm."' ";
        }
        $sql .= "GROUP BY id_flureg, resultado, tipo1, subtipo1";
//        echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        return $data;
    }


    public static function BorrarUcetiMuestras($conn, $idFormUceti) {
        $filtro = array();
        $filtro["id_flureg"] = $idFormUceti;
        dalUceti::BorrarTabla($conn, "flureg_muestra_silab", $filtro);
    }

    public static function GuardarUcetiPruebasSilab($conn, $idFormUceti, $data) {
        $pruebas = $data;
        if ($pruebas != NULL) {
            dalUceti::BorrarUcetiPruebasSilab($conn, $idFormUceti);
            $max = sizeof($pruebas);
            for ($i = 0; $i < $max; $i++) {
                if (isset($pruebas[$i])) {
                    $prueba = $pruebas[$i];
                    if ($prueba["id_muestra"] != "" && sizeof($prueba) > 1) {
                        $prueba["id_flureg"] = $idFormUceti;
                        dalUceti::GuardarTabla($conn, "flureg_muestra_prueba_silab", $prueba);
                    }
                }
            }
            dalUceti::GuardarBitacora($conn, "1", "flureg_muestra_prueba_silab");
        }
    }

    public static function BorrarUcetiPruebasSilab($conn, $idFormUceti) {
        $filtro = array();
        $filtro["id_flureg"] = $idFormUceti;
        dalUceti::BorrarTabla($conn, "flureg_muestra_prueba_silab", $filtro);
    }

    public static function BorrarUcetiPruebaSilab($conn, $idMuestra) {
        $filtro = array();
        $filtro["id_muestra"] = $idMuestra;
        dalUceti::BorrarTabla($conn, "flureg_muestra_prueba_silab", $filtro);
        dalUceti::GuardarBitacora($conn, "3", "flureg_muestra_prueba_silab");
    }

    public static function BorrarUcetiMuestraSilab($conn, $idMuestra) {
        $filtro = array();
        $filtro["id_muestra"] = $idMuestra;
        dalUceti::BorrarTabla($conn, "flureg_muestra_silab", $filtro);
        dalUceti::GuardarBitacora($conn, "3", "flureg_muestra_silab");
    }

}

?>