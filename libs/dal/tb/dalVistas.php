<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/helper/helperString.php');
require_once ('libs/helper/helperCatalogos.php');

class dalVistas {

    private $ficha;
    private $vista;
    private $filtro;
    private $search;
//    public function  __construct($data) {
//
//        // Guardar datos globales
//        $this->ficha = $data["ficha"];
//        $this->filtro = $data["filtro"];
//        $this->search = $data["search"];
//        $this->esLab = true;
//
//        // En base a la ficha guardar el nombre de la vista que le corresponde
//        $this->setVista();
//    }

    public function __construct($data) {
        $this->vista = "view_flureg";
        $this->filtro = $data["filtro"];
        $this->search = $data["search"];
    }

    private function getColumnas() {
        $sql = "show columns from " . $this->vista;
        $conn = new Connection();
        $conn->initConn();

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        $extras = array();
//
//        $antecedentes = helperCatalogos::getAntecedentesVacunalesInfluenza();
//        if (is_array($antecedentes)) {
//            foreach ($antecedentes as $antecedente) {
//                $columna["Field"] = $antecedente["nombre_antecendente_vacunal"] . "_dosis";
//                $extras[] = $columna;
//                $columna["Field"] = $antecedente["nombre_antecendente_vacunal"] . "_desconoce";
//                $extras[] = $columna;
//                $columna["Field"] = $antecedente["nombre_antecendente_vacunal"] . "_fecha";
//                $extras[] = $columna;
//            }
//        }
//
//        // Posicionar en las columnas correctas
//        $data = $this->posicionarColumnas($data, "vac_fecha_ultima_dosis", $extras);
//
//        $extras2 = array();
//        $enfermedades = helperCatalogos::getEnfermedadesCronicasInfluenza();
//        if (is_array($enfermedades)) {
//            foreach ($enfermedades as $enfermedad) {
//                $columna["Field"] = $enfermedad["nombre_enfermedad_cronica"] . "_resultado";
//                $extras2[] = $columna;
//            }
//        }
//
//        // Posicionar en las columnas correctas
//        $data = $this->posicionarColumnas($data, "riesgo_enf_cronica", $extras2);
//
//        //echo "<pre>"; print_r($data); echo "</pre>"; exit;

        return $data;
    }

    private function getDatos() {
        $sql = "select * from " . $this->vista . " v ";

        $sql .= "where 1 ";
        if ($this->filtro != "") 
            $sql .= $this->filtro;
        
//        if ($this->ficha != Configuration::vih){
//            $sql .= "and v.anio_epi = ? ";
//            $sql .= "and v.Semana >= ? ";
//            $sql .= "and v.Semana <= ? ";    
//        }
//        echo $sql;
//        exit;
//        $sql .= $this->filtro;

        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();

        $conn->closeConn();

        return $data;
    }
    
    private function getDatosAntecedentes($data) {
        $filtroTotal = " ";
        if (is_array($data["data"])) {
            foreach ($data["data"] as $fila) {
                $filtroTotal .= " or (tipo_identificacion = '" . $fila["tipo_identificacion"] . "' and ";
                $filtroTotal .= " numero_identificacion = '" . $fila["numero_identificacion"] . "') ";
            }
        }
        $sql = "select fav.*,av.nombre_antecendente_vacunal from flureg_antecendente_vacunal fav 
            inner join cat_antecendente_vacunal av ON fav.id_cat_antecendente_vacunal = av.id_cat_antecendente_vacunal
            where status = 1 ";

        
//        $sql .= "where v.anio_epi = ? ";
//        $sql .= "and v.semana >= ? ";
//        $sql .= "and v.semana <= ? ";
//        $sql .= "and v.idficha = " . $this->ficha . " ";
        $sql .= $filtroTotal;

        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
//        $conn->params($this->search);
        $conn->execute();
        $dataTotal = $conn->fetch();

        $conn->closeConn();

        return $dataTotal;
    }
    
    private function getDatosEnfermedadesCronicas($data) {
        $filtroTotal = " ";
        if (is_array($data["data"])) {
            foreach ($data["data"] as $fila) {
                $filtroTotal .= " or (tipo_identificacion = '" . $fila["tipo_identificacion"] . "' and ";
                $filtroTotal .= " numero_identificacion = '" . $fila["numero_identificacion"] . "') ";
            }
        }
        $sql = "select fec.*, ec.nombre_enfermedad_cronica from flureg_enfermedad_cronica fec
            inner join cat_enfermedad_cronica ec ON fec.id_cat_enfermedad_cronica = ec.id_cat_enfermedad_cronica 
            where status = 1 ";
        
//        $sql .= "where v.anio_epi = ? ";
//        $sql .= "and v.semana >= ? ";
//        $sql .= "and v.semana <= ? ";
//        $sql .= "and v.idficha = " . $this->ficha . " ";
        $sql .= $filtroTotal;

        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
//        $conn->params($this->search);
        $conn->execute();
        $dataTotal = $conn->fetch();

        $conn->closeConn();

        return $dataTotal;
    }

    public function getData() {

        $data["columnas"] = $this->getColumnas();
        $data["data"] = $this->getDatos();
        $antecedentes = $this->getDatosAntecedentes($data);
        $enfermedades = $this->getDatosEnfermedadesCronicas($data);
        //$laboratorio = $this->getDatosLaboratorio();

        $id = "";
        if (is_array($data["data"])) {
            foreach ($data["data"] as $fila) {
                if ($fila["id"] != $id) {
                    $registro = array();
                    $id = $fila["id_formulario"];
                }
                if (is_array($data["columnas"])) {
                    foreach ($data["columnas"] as $columna) {
                        if (isset($fila[$columna["Field"]]))
                            $registro[$columna["Field"]] = $fila[$columna["Field"]];
                    }
                }

                if (is_array($antecedentes)) {
                    foreach ($antecedentes as $antecedente) {
                        if( $fila["numero_identificacion"] == $antecedente["numero_identificacion"]
                            && $fila["tipo_identificacion"] == $antecedente["tipo_identificacion"]){
                            $registro[$antecedente["nombre_antecendente_vacunal"] . "_dosis"] = $antecedente["dosis"];
                            $registro[$antecedente["nombre_antecendente_vacunal"] . "_desconoce"] = $antecedente["desconoce"];
                            $registro[$antecedente["nombre_antecendente_vacunal"] . "_fecha"] = $antecedente["fecha"];
                        }
                    }
                }
                if (is_array($enfermedades)) {
                    foreach ($enfermedades as $enfermedad) {
                        if( $fila["numero_identificacion"] == $enfermedad["numero_identificacion"]
                            && $fila["tipo_identificacion"] == $enfermedad["tipo_identificacion"]){
                            $registro[$enfermedad["nombre_enfermedad_cronica"] . "_resultado"] = $enfermedad["resultado"];
                        }
                    }
                }
                $tupla[$fila["id_formulario"]] = $registro;
            }
        }

        $data["data"] = $tupla;
        //print_r($data["data"]); 
//
//        //echo "<pre>"; print_r($data); echo "</pre>"; exit;
//
//        // Borrar los campos qu[e no se necesitan
//        unset($data["columnas"][0]);
//        unset($data["columnas"][1]); // idas
//        unset($data["columnas"][2]); // idds
//        unset($data["columnas"][3]); // idts
//        unset($data["columnas"][4]); // anio_epi
//        
        return $data;
    }

    private function posicionarColumnas($columnas, $punto, $extra) {
        $result = array();

        if (is_array($extra)) {
            if (is_array($columnas)) {
                foreach ($columnas as $columna) {
                    $result[] = $columna;
                    if (strtolower($columna["Field"]) == strtolower($punto)) {
                        $result = array_merge($result, $extra);
                    }
                }
            }
        } else {
            $result = $columnas;
        }

        return $result;
    }

}

?>