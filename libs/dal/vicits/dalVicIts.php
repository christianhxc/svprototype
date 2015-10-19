<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperVicIts.php');
require_once('libs/caus/clsCaus.php');

class dalVicIts {

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

    public static function GuardarVicIts($data) {

        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $individuo = helperVicIts::dataTblPersona($data);
        $vicIts = helperVicIts::dataVicItsForm($data);
        $itsRel = helperVicIts::dataVicItsIts($data);
        $signosSintomas = helperVicIts::dataVicItsSintomasSignos($data);
        $antibioticos = helperVicIts::dataVicItsAntibioticos($data);
        $dxtx = helperVicIts::dataVicItsDiagnosticoTratamiento($data);
        $drogasRel = helperVicIts::dataVicItsConsumoDrogas($data);
//        echo "<pre>";
//        print_r($data);
//        echo "</pre>";
//        echo "<pre>";
//        print_r($signosSintomas);
//        echo "</pre>";
//        exit;
//        $itsRel = helperVicIts::dataVicItsIts($data);
//        $consumoDrogas = helperVicIts::dataVicItsConsumoDrogas($data);
//        echo "Individuo:</br>";
//        print_r($individuo);
//        echo "</br>Formulario VicITS:</br>";
//        print_r($vicIts);
//        echo "</br>ITS:</br>";
//        print_r($itsRel);
//        echo "</br>Drogas:</br>";
//        print_r($drogasRel);
//        exit;
//        $muestras = helperVicIts::dataVihMuestrasSILAB($data);
//        $pruebas = helperVicIts::dataVihPruebasSILAB($data);
//        print_r($drogas);
//        exit;

        $yaExisteForm = helperVicIts::yaExisteVicIts($vicIts);
        if ($yaExisteForm == 0) {
            $param = array();
            $yaExistePersona = helperVicIts::yaExistePersona($individuo);
            $param = dalVicIts::GuardarTabla($conn, "vicits_form", $vicIts);
            $idFormVicIts = $param['id'];
            $param = dalVicIts::GuardarBitacora($conn, "1", "vicits_form");

            if ($itsRel != NULL && isset($itsRel["its"]))
                dalVicIts::GuardarItsRelacion($conn, $idFormVicIts, $itsRel);
            if ($drogasRel != NULL && isset($drogasRel))
                dalVicIts::GuardarDrogasRelacion($conn, $idFormVicIts, $drogasRel);
            if ($signosSintomas != NULL && isset($signosSintomas["sintomas_signos"]))
                dalVicIts::GuardarSignosSintomasRelacion($conn, $idFormVicIts, $signosSintomas);
            if ($antibioticos != NULL && isset($antibioticos["antibioticos"]))
                dalVicIts::GuardarAntibioticosRelacion($conn, $idFormVicIts, $antibioticos);
            if ($dxtx != NULL && isset($dxtx["diagnostico_tratamiento"]))
                dalVicIts::GuardarDiagnosticosTratamientosRelacion($conn, $idFormVicIts, $dxtx);

//            if ($muestras != NULL && isset($muestras[0]))
//                $param = dalVicIts::GuardarVihMuestras($conn, $filtro["id_vih_form"], $muestras);
//            if ($pruebas != NULL && isset($pruebas[0]))
//                dalVicIts::GuardarVihPruebasSilab($conn, $filtro["id_vih_form"], $pruebas);

            if ($yaExistePersona == 0) {
//                $idTipoTmp = $individuo["id_tipo_identidad"];
//                unset($individuo["id_tipo_identidad"]);
                unset($individuo["id_region"]);
//                $individuo["tipo_identificacion"] = $idTipoTmp;
                $param = dalVicIts::GuardarTabla($conn, "tbl_persona", $individuo);
                $id = $param['id'];
                $ok = $param['ok'];
                //print_r($param);

                $param = dalVicIts::GuardarBitacora($conn, "1", "tbl_persona");
                $id = $param['id'];
                $ok = $param['ok'];
                //print_r($param);
            } else {
                echo "La persona ya existe";
                $totalFiltroIndividuo = helperVicIts::dataTblPersona($data);
                //print_r($totalFiltroIndividuo);exit;
                $filtro = array();
                $filtro["tipo_identificacion"] = $individuo["tipo_identificacion"];
                $filtro["numero_identificacion"] = $individuo["numero_identificacion"];

                //individuo + filtro esto es para los parametros, no importa el nombre de los filtros.
//                unset($totalFiltroIndividuo["id_tipo_identidad"]);
                $totalFiltroIndividuo["tipo_identificacion"] = $filtro["tipo_identificacion"];
                $totalFiltroIndividuo["filter1"] = $filtro["tipo_identificacion"];
                $totalFiltroIndividuo["filter2"] = $filtro["numero_identificacion"];
                unset($individuo["id_tipo_identidad"]);
                $individuo["tipo_identificacion"] = $filtro["tipo_identificacion"];
                unset($individuo["id_region"]);
                unset($totalFiltroIndividuo["id_region"]);
                //print_r($totalFiltroIndividuo);
                $param = dalVicIts::ActualizarTabla($conn, "tbl_persona", $individuo, $filtro, $totalFiltroIndividuo);
                $id = $param['id'];
                $ok = $param['ok'];
                //print_r($param);

                $param = dalVicIts::GuardarBitacora($conn, "2", "tbl_persona");
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

    public static function ActualizarVicIts($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $individuo = helperVicIts::dataTblPersona($data);
        $vicIts = helperVicIts::dataVicItsForm($data);
        $itsRel = helperVicIts::dataVicItsIts($data);
        $signosSintomas = helperVicIts::dataVicItsSintomasSignos($data);
        $antibioticos = helperVicIts::dataVicItsAntibioticos($data);
        $dxtx = helperVicIts::dataVicItsDiagnosticoTratamiento($data);
        $drogasRel = helperVicIts::dataVicItsConsumoDrogas($data);

        $param = array();

        $totalFiltroVicits = helperVicIts::dataVicItsForm($data);

        $filtro = array();
        //echo $data["formulario"]["idVicIts"]."<br/>";
        $filtro["id_vicits_form"] = $data["formulario"]["idVicItsForm"];
        
        $idFormVicIts = $data["formulario"]["idVicItsForm"];

        $totalFiltroVicits["filter1"] = $filtro["id_vicits_form"];

        $param = dalVicIts::ActualizarTabla($conn, "vicits_form", $vicIts, $filtro, $totalFiltroVicits);
        $id = $param['id'];
        $ok = $param['ok'];

        $param = dalVicIts::GuardarBitacora($conn, "2", "vicits_form");
        $id = $param['id'];
        $ok = $param['ok'];

        if ($itsRel != NULL && isset($itsRel["its"]))
            dalVicIts::GuardarItsRelacion($conn, $idFormVicIts, $itsRel);
        if ($drogasRel != NULL && isset($drogasRel))
            dalVicIts::GuardarDrogasRelacion($conn, $idFormVicIts, $drogasRel);
        if ($signosSintomas != NULL && isset($signosSintomas["sintomas_signos"]))
            dalVicIts::GuardarSignosSintomasRelacion($conn, $idFormVicIts, $signosSintomas);
        if ($antibioticos != NULL && isset($antibioticos["antibioticos"]))
            dalVicIts::GuardarAntibioticosRelacion($conn, $idFormVicIts, $antibioticos);
        if ($dxtx != NULL && isset($dxtx["diagnostico_tratamiento"]))
            dalVicIts::GuardarDiagnosticosTratamientosRelacion($conn, $idFormVicIts, $dxtx);

        $totalFiltroIndividuo = helperVicIts::dataTblPersona($data);
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

        $param = dalVicIts::ActualizarTabla($conn, "tbl_persona", $individuo, $filtro, $totalFiltroIndividuo);
        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);

        $param = dalVicIts::GuardarBitacora($conn, "2", "tbl_persona");
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

    public static function BorrarVicIts($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $param = array();

        $filtro = array();
        $filtro["id_vicits_form"] = $data['idVicIts'];
        $param = dalVicIts::BorrarTabla($conn, "vicits_antibiotico", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        $param = dalVicIts::BorrarTabla($conn, "vicits_sintoma", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        $param = dalVicIts::BorrarTabla($conn, "vicits_droga", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        $param = dalVicIts::BorrarTabla($conn, "vicits_tratamiento", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        $param = dalVicIts::BorrarTabla($conn, "vicits_its", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        $param = dalVicIts::BorrarTabla($conn, "vicits_form", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);

        $param = dalVicIts::GuardarBitacora($conn, "3", "vicits_form");
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

    public static function GuardarItsRelacion($conn, $idFormVicIts, $data) {
        $its = $data["its"];
        if ($its != NULL) {
            dalVicIts::BorrarItsRelacion($conn, $idFormVicIts);
            $max = sizeof($its);
            for ($i = 0; $i < $max; $i++) {
                if ($its[$i] != "") {
                    $enfermedad = $its[$i];
                    $insertarIts = array();
                    //list($idEnfermedad, $idResultado) = explode('-', $enfermedad);
                    $insertarIts["id_vicits_form"] = $idFormVicIts;
                    $insertarIts["id_ITS"] = $enfermedad;
                    dalVicIts::GuardarTabla($conn, "vicits_its", $insertarIts);
                    //echo "Enfermedad id: " . $enfermedad["id"] . " Resultado: " . $enfermedad["res"] . "<br/>";
                }
            }
            dalVicIts::GuardarBitacora($conn, "1", "vicits_its");
        }
    }

    public static function BorrarItsRelacion($conn, $idFormVicIts) {
        $filtro = array();
        $filtro["id_vicits_form"] = $idFormVicIts;
        dalVicIts::BorrarTabla($conn, "vicits_its", $filtro);
    }

    public static function GuardarSignosSintomasRelacion($conn, $idFormVicIts, $data) {
        $datos = $data["sintomas_signos"];
//        print_r($datos);
//        exit;
        if ($datos != NULL) {
            dalVicIts::BorrarSignosSintomasRelacion($conn, $idFormVicIts);
            $max = sizeof($datos);
            for ($i = 0; $i < $max; $i++) {
                if ($datos[$i] != "") {
                    $dato = $datos[$i];
                    $sintomaDia = (!isset($dato) ? NULL : explode("-", $dato));
                    $insertar = array();
                    //list($idEnfermedad, $idResultado) = explode('-', $enfermedad);
                    $insertar["id_vicits_form"] = $idFormVicIts;
                    $insertar["id_signo_sintoma"] = $sintomaDia[0];
                    $insertar["dias"] = helperVicIts::validarString($sintomaDia[1]);
                    dalVicIts::GuardarTabla($conn, "vicits_sintoma", $insertar);
                    //echo "Enfermedad id: " . $enfermedad["id"] . " Resultado: " . $enfermedad["res"] . "<br/>";
                }
            }
            dalVicIts::GuardarBitacora($conn, "1", "vicits_sintoma");
        }
    }

    public static function BorrarSignosSintomasRelacion($conn, $idFormVicIts) {
        $filtro = array();
        $filtro["id_vicits_form"] = $idFormVicIts;
        dalVicIts::BorrarTabla($conn, "vicits_sintoma", $filtro);
    }

    public static function GuardarAntibioticosRelacion($conn, $idFormVicIts, $data) {
        $datos = $data["antibioticos"];
//        print_r($datos);
//        exit;
        if ($datos != NULL) {
            dalVicIts::BorrarAntibioticosRelacion($conn, $idFormVicIts);
            $max = sizeof($datos);
            for ($i = 0; $i < $max; $i++) {
                if ($datos[$i] != "") {
                    $dato = $datos[$i];
                    $antibiotico = (!isset($dato) ? NULL : explode("-", $dato));
                    $insertar = array();
                    //list($idEnfermedad, $idResultado) = explode('-', $enfermedad);
                    $insertar["id_vicits_form"] = $idFormVicIts;
                    $insertar["nombre"] = helperVicIts::validarString($antibiotico[0]);
                    $insertar["motivo"] = helperVicIts::validarString($antibiotico[1]);
                    $insertar["fecha"] = helperVicIts::validarFecha($antibiotico[2]);

                    dalVicIts::GuardarTabla($conn, "vicits_antibiotico", $insertar);
                    //echo "Enfermedad id: " . $enfermedad["id"] . " Resultado: " . $enfermedad["res"] . "<br/>";
                }
            }
            dalVicIts::GuardarBitacora($conn, "1", "vicits_antibiotico");
        }
    }

    public static function BorrarAntibioticosRelacion($conn, $idFormVicIts) {
        $filtro = array();
        $filtro["id_vicits_form"] = $idFormVicIts;
        dalVicIts::BorrarTabla($conn, "vicits_antibiotico", $filtro);
    }

    public static function GuardarDiagnosticosTratamientosRelacion($conn, $idFormVicIts, $data) {
        $datos = $data["diagnostico_tratamiento"];
//        print_r($datos);
//        exit;
        if ($datos != NULL) {
            dalVicIts::BorrarDiagnosticosTratamientosRelacion($conn, $idFormVicIts);
            $max = sizeof($datos);
            for ($i = 0; $i < $max; $i++) {
                if ($datos[$i] != "") {
                    $dato = $datos[$i];
                    $tratamiento = (!isset($dato) ? NULL : explode("-", $dato));
                    $insertar = array();
                    //list($idEnfermedad, $idResultado) = explode('-', $enfermedad);
                    $insertar["id_vicits_form"] = $idFormVicIts;
                    $insertar["id_diag_sindromico"] = helperVicIts::validarString($tratamiento[0]);
                    $insertar["id_diag_etiologico"] = helperVicIts::validarString($tratamiento[1]);
                    $insertar["id_tratamiento"] = helperVicIts::validarString($tratamiento[1]);

                    dalVicIts::GuardarTabla($conn, "vicits_tratamiento", $insertar);
                    //echo "Enfermedad id: " . $enfermedad["id"] . " Resultado: " . $enfermedad["res"] . "<br/>";
                }
            }
            dalVicIts::GuardarBitacora($conn, "1", "vicits_tratamiento");
        }
    }

    public static function BorrarDiagnosticosTratamientosRelacion($conn, $idFormVicIts) {
        $filtro = array();
        $filtro["id_vicits_form"] = $idFormVicIts;
        dalVicIts::BorrarTabla($conn, "vicits_tratamiento", $filtro);
    }

    public static function GuardarDrogasRelacion($conn, $idFormVicIts, $data) {
        $drogas = $data;
        if ($drogas != NULL) {
            dalVicIts::BorrarDrogasRelacion($conn, $idFormVicIts);
            $max = sizeof($drogas);
            for ($i = 0; $i < $max; $i++) {
                $droga = $drogas[$i];
                $insertarRelDrogas = array();
                if ($droga[0] != "") {
                    $idDroga = $droga[0];
                    $tiempoConsumo = $droga[1];
                    $insertarRelDrogas["id_vicits_form"] = $idFormVicIts;
                    $insertarRelDrogas["id_droga"] = $idDroga;
                    $insertarRelDrogas['fecha_consumo'] = $tiempoConsumo;
                    dalVicIts::GuardarTabla($conn, "vicits_droga", $insertarRelDrogas);
                }
            }
            dalVicIts::GuardarBitacora($conn, "1", "vicits_droga");
        }
    }

    public static function BorrarDrogasRelacion($conn, $idFormVicIts) {
        $filtro = array();
        $filtro["id_vicits_form"] = $idFormVicIts;
        dalVicIts::BorrarTabla($conn, "vicits_droga", $filtro);
    }

    public static function GuardarVihMuestras($conn, $idFormVicIts, $data) {
        $muestras = $data;
        if ($muestras != NULL) {
            dalVicIts::BorrarVihMuestras($conn, $idFormVicIts);
            $max = sizeof($muestras);
            for ($i = 0; $i < $max; $i++) {
                $muestra = $muestras[$i];
                if ($muestra["id_muestra"] != "" && sizeof($muestra) > 1) {
                    $muestra["id_vih_form"] = $idFormVicIts;
                    //print_r($muestra);Exit;
                    dalVicIts::GuardarTabla($conn, "vih_muestra_silab", $muestra);
                }
            }
            dalVicIts::GuardarBitacora($conn, "1", "vih_muestra_silab");
        }
    }

    public static function BorrarVihMuestras($conn, $idFormVicIts) {
        $filtro = array();
        $filtro["id_vih_form"] = $idFormVicIts;
        dalVicIts::BorrarTabla($conn, "vih_muestra_silab", $filtro);
    }

    public static function GuardarVihPruebasSilab($conn, $idFormVicIts, $data) {
        $pruebas = $data;
        if ($pruebas != NULL) {
            dalVicIts::BorrarVihPruebasSilab($conn, $idFormVicIts);
            $max = sizeof($pruebas);
            for ($i = 0; $i < $max; $i++) {
                if (isset($pruebas[$i])) {
                    $prueba = $pruebas[$i];
                    if ($prueba["id_muestra"] != "" && sizeof($prueba) > 1) {
                        $prueba["id_vih_form"] = $idFormVicIts;
                        dalVicIts::GuardarTabla($conn, "vih_muestra_prueba_silab", $prueba);
                    }
                }
            }
            dalVicIts::GuardarBitacora($conn, "1", "vih_muestra_prueba_silab");
        }
    }

    public static function BorrarVihPruebasSilab($conn, $idFormVicIts) {
        $filtro = array();
        $filtro["id_vih_form"] = $idFormVicIts;
        dalVicIts::BorrarTabla($conn, "vih_muestra_prueba_silab", $filtro);
    }

    public static function BorrarVihPruebaSilab($conn, $idMuestra) {
        $filtro = array();
        $filtro["id_muestra"] = $idMuestra;
        dalVicIts::BorrarTabla($conn, "vih_muestra_prueba_silab", $filtro);
        dalVicIts::GuardarBitacora($conn, "3", "vih_muestra_prueba_silab");
    }

    public static function BorrarVihMuestraSilab($conn, $idMuestra) {
        $filtro = array();
        $filtro["id_muestra"] = $idMuestra;
        dalVicIts::BorrarTabla($conn, "vih_muestra_silab", $filtro);
        dalVicIts::GuardarBitacora($conn, "3", "vih_muestra_silab");
    }

    public static function GuardarTabla($conn, $tabla, $objeto) {
        $ok = true;
        $sql = Actions::AgregarQuery($tabla, $objeto);
//        echo $sql;exit;
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
            echo "<br/>Error: " . $error;
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
        // echo "<br/><br/>".$sql;exit;
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

    public static function ejecutarQuery($conn, $sql) {
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