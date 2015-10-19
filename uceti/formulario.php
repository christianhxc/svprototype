<?php

require_once ('libs/caus/clsCaus.php');
require_once ('libs/pages/formUcetiPage.php');
require_once ('libs/helper/helperLugar.php');
require_once ('libs/helper/helperCatalogos.php');
require_once ('libs/helper/helperSilab.php');
require_once ('libs/helper/helperUceti.php');
require_once ('libs/helper/helperString.php');
require_once ('libs/Configuration.php');
require_once ('libs/Etiquetas.php');
require_once ('libs/dal/uceti/dalUceti.php');
require_once ('libs/dal/uceti/muestraSilab.php');
require_once("libs/ConfigurationHospitalInfluenza.php");
// Validar si se desea cerrar sesion
if ($_REQUEST["logout"]) {
    clsCaus::cerrarSesion();
    file_get_contents(Configuration::reportAddress . "remotelogin.php?logout=true");
}

//echo $_SESSION["user"]["general"]["idusuario"];

//$lista = clsCaus::obtenerUbicaciones(ConfigurationCAUS::Localidad);
////        echo $search["id_un"];
//print_r($_SESSION["user"]["ubicaciones"]);
//if (is_array($lista)) {
//    $temporal = implode("','", $lista);
//    if ($temporal != "") {
//        $filtro .= "and id_un in ('" . $temporal . "')";
//    }
//}
//
////$idOrganizacion = $_SESSION["user"]["organizacion"];
////if ($idOrganizacion != ConfigurationCAUS::orgCdc && $idOrganizacion != ConfigurationCAUS::orgMinsa) {
////    $filtro .= " and idtipo_instalacion = " . $idOrganizacion;
////}
//
//echo "<br/>" . $filtro;


// Validar si ya ha iniciado sesion
if (!clsCaus::validarSession()) {
    header("location: ../index.php");
}

$config = array();
$config["info"] = $_REQUEST["info"];

// Archivos javascript necesarios
$config["jsfiles"][] = "comun.js";
$config["jsfiles"][] = "Utils.js";
//    $config["jsfiles"][] = "Etiquetas.js";
$config["jsfiles"][] = "ajax.js";
$config["jsfiles"][] = "jquery.qtip-1.0.0.min.js";
$config["jsfiles"][] = "jquery.autocomplete.js";
$config["cssfiles"][] = "jquery.autocomplete";
$config["jsfiles"][] = "uceti/formulario.js";
$config["jsfiles"][] = "uceti/busquedaSilab.js";
$config["jsfiles"][] = "uceti/muestra_manual.js";

$config["info"] = $_REQUEST["info"];
$config["action"] = "";
$config["guardarPrevio"] = $_REQUEST["data"]["GuardarPrevio"]["Guardar"];

if (isset($_REQUEST["guardarPrevio"])) {
    $config["guardarPrevio"] = $_REQUEST["guardarPrevio"];
}
//echo "Guardar Previo Antes ".$config["guardarPrevio"];
//print_r($_POST["data"]["GuardarPrevio"]);
//echo $_REQUEST["action"];
// NUEVO COMPLETAMENTE
if (!isset($_REQUEST["action"])) {
    // No lleva datos que cargar/mostrar
    $config["action"] = "N";
    $config["error"] = false;
    $config["preselect"] = false;
    $config["codigos"] = false;
} else if ($_REQUEST["action"] == 'N') {
    if (count($_POST)) {
        //print_r($_POST);
        $config["data"] = $_POST["data"];
//        $mensaje = 1;
        $mensaje = dalUceti::GuardarUceti($_POST["data"]);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfo;
            header("location: index.php?info=" . $config["info"]);
        } else if ($mensaje == 2) {
            $config["Merror"] = Etiquetas::mensajeError;
        }
    }
} else if ($_REQUEST["action"] == 'M') {
    $config["action"] = "M";
    if (count($_POST)) {
        //print_r($_POST);
        $config["data"] = $_POST["data"];
        $mensaje = dalUceti::ActualizarUceti($_POST["data"]);
        if ($mensaje == 1) {

            $config['tipo_identificacion'] = (!isset($config["data"]["individuo"]["tipoId"]) ? NULL : $config["data"]["individuo"]["tipoId"]);
            $config['numero_identificacion'] = (!isset($config["data"]["individuo"]["identificador"]) ? NULL : $config["data"]["individuo"]["identificador"]);
            if ($config["guardarPrevio"] == "2") {
                $config["info"] = Etiquetas::mensajeInfoActualizarMuestra;
                header("location: formulario.php?&action=R&tipo=" . $config['tipo_identificacion']
                        . "&id=" . $config['numero_identificacion'] . "&info=" . $config["info"] . "&guardarPrevio=" . $config["guardarPrevio"]);
            } else {
                $config["info"] = Etiquetas::mensajeInfoActualizar;
                header("location: formulario.php?&action=R&tipo=" . $config['tipo_identificacion']
                        . "&id=" . $config['numero_identificacion'] . "&info=" . $config["info"]);
            }
        } else if ($mensaje == 2) {
            $config["Merror"] = Etiquetas::mensajeErrorActualizar;
            $config["action"] = "R";
        }
    }
} else if ($_REQUEST["action"] == 'D') {
    $config["action"] = "D";
    if (isset($_REQUEST["idUceti"])) { // D borrar
        $config['idUceti'] = $_REQUEST["idUceti"];
        $mensaje = dalUceti::BorrarUceti($config);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfoBorrar;
            header("location: index.php?info=" . $config["info"]);
        } else {
            $config["Merror"] = Etiquetas::mensajeErrorBorrar;
            header("location: index.php?info=" . $config["Merror"]);
        }
    }
}
if (isset($_REQUEST["action"])) {
    if ($_REQUEST["action"] == 'R' || $config["action"] == "R") { //read
        $config["action"] = "R";
        if (isset($_REQUEST["tipo"]) && isset($_REQUEST["id"])) { // en M se pierde falta ponerle
            $config['tipo_identificacion'] = $_REQUEST["tipo"];
            $config['numero_identificacion'] = $_REQUEST["id"];
            $data = helperUceti::buscarUceti($config);
            $config['read'] = $data[0];

            $idUceti = $config['read']['id_flureg'];
            $enfermedades = helperUceti::buscarUcetiEnfermedad($config['tipo_identificacion'], $config['numero_identificacion']);
            $config['enfermedades'] = $enfermedades;
            $vacunas = helperUceti::buscarUcetiVacunas($config['tipo_identificacion'], $config['numero_identificacion']);
            $config['vacunas'] = $vacunas;
            $muestras = helperUceti::buscarUcetiMuestrasSilab($idUceti);
            $config['muestras'] = $muestras;
            $tipoMuestras = helperUceti::buscarUcetiTipoMuestras($idUceti);
            $config['tipoMuestras'] = $tipoMuestras;
//            print_r($config['enfermedades']);
//            exit;
        } else {
            $config["data"] = $_POST["data"];
            $config['tipo_identificacion'] = (!isset($config["data"]["individuo"]["tipoId"]) ? NULL : $config["data"]["individuo"]["tipoId"]);
            $config['numero_identificacion'] = (!isset($config["data"]["individuo"]["identificador"]) ? NULL : $config["data"]["individuo"]["identificador"]);
            $data = helperUceti::buscarUceti($config);
            $config['read'] = $data[0];
            $idUceti = $config['read']['id_flureg'];
            $enfermedades = helperUceti::buscarUcetiEnfermedad($config['tipo_identificacion'], $config['numero_identificacion']);
            $config['enfermedades'] = $enfermedades;
            $vacunas = helperUceti::buscarUcetiVacunas($config['tipo_identificacion'], $config['numero_identificacion']);
            $config['vacunas'] = $vacunas;
            $muestras = helperUceti::buscarUcetiMuestrasSilab($idUceti);
            $config['muestras'] = $muestras;
            $tipoMuestras = helperUceti::buscarUcetiTipoMuestras($idUceti);
            $config['tipoMuestras'] = $tipoMuestras;
        }
    }
} else if (isset($config["action"])) {
    if ($config["action"] == "R") { //read
        $config["data"] = $_POST["data"];
//        print_r($config['data']);
//        exit;
        $config['tipo_identificacion'] = (!isset($config["data"]["individuo"]["tipoId"]) ? NULL : $config["data"]["individuo"]["tipoId"]);
        $config['numero_identificacion'] = (!isset($config["data"]["individuo"]["identificador"]) ? NULL : $config["data"]["individuo"]["identificador"]);
        $data = helperUceti::buscarUceti($config);
        $config['read'] = $data[0];
        $idUceti = $config['read']['id_flureg'];
        $enfermedades = helperUceti::buscarUcetiEnfermedad($config['tipo_identificacion'], $config['numero_identificacion']);
        $config['enfermedades'] = $enfermedades;
        $vacunas = helperUceti::buscarUcetiVacunas($config['tipo_identificacion'], $config['numero_identificacion']);
        $config['vacunas'] = $vacunas;
        $muestras = helperUceti::buscarUcetiMuestrasSilab($idUceti);
        $config['muestras'] = $muestras;
        $tipoMuestras = helperUceti::buscarUcetiTipoMuestras($idUceti);
        $config['tipoMuestras'] = $tipoMuestras;
    }
}

$config["catalogos"]["provincias"] = helperLugar::getProvinciasUceti($config);
//print_r($config["catalogos"]["provincias"]);exit;
//    $config["catalogos"]["regiones"] = helperLugar::getRegionSalud($config);
//    $config["catalogos"]["distritos"] = helperLugar::getDistritos($config);
//    $config["catalogos"]["corregimientos"] = helperLugar::getCorregimientos($config);
$config["catalogos"]["tipoId"] = helperCatalogos::getTipoIdentificacion();
$config["catalogos"]["regiones"] = "";
$config["catalogos"]["distritos"] = "";
$config["catalogos"]["corregimientos"] = "";

//$config["laboratorio"]["muestra"] = helperSilab::getMuestra("1");
//print_r($config["laboratorio"]["muestra"]);

$page = new formUceti($config);
$page->displayPage();
?>
