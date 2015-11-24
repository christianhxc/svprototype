<?php

require_once ('libs/caus/clsCaus.php');
require_once ('libs/pages/tb/formtbPage.php');
require_once ('libs/helper/helperLugar.php');
require_once ('libs/helper/helperCatalogos.php');
require_once ('libs/helper/helperSilab.php');
require_once ('libs/helper/helpertb.php');
require_once ('libs/helper/helperString.php');
require_once ('libs/Configuration.php');
require_once ('libs/Etiquetas.php');
require_once ('libs/dal/tb/daltb.php');
require_once ('libs/dal/tb/muestraSilab.php');
require_once("libs/ConfigurationHospitalInfluenza.php");
// Validar si se desea cerrar sesion

// Esto tiene que estar habilitado
if ($_REQUEST["logout"]) {
    clsCaus::cerrarSesion();
    file_get_contents(Configuration::reportAddress . "remotelogin.php?logout=true");
}


// Validar si ya ha iniciado sesion
if (!clsCaus::validarSession()) {
    header("location: ../index.php");
}

// hasta aquí

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
$config["jsfiles"][] = "tb/formulario.js";
$config["jsfiles"][] = "tb/busquedaSilab.js";

$config["search"]["search"]= $_REQUEST["search"];
$config["search"]["pag"]= $_REQUEST["pag"];
$config["search"]["search_comp"]= $_REQUEST["search"]["search_comp"];

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
        $config["data"] = $_POST["data"];
//        echo "<pre>";
//        print_r($_POST);
//        echo "</pre>";
//        exit;
        $mensaje = daltb::Guardartb($_POST["data"]);
        
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfo;
            if ($config["search"]["search"] != '')
                $search_guardar = "&search=".$config["search"]["search"];
            $pag_guardar= "&pag=".$config["search"]["pag"];
            header("location: index.php?info=" . $config["info"].$search_guardar.$pag_guardar);
        } else if ($mensaje == 2) {
            $config["Merror"] = Etiquetas::mensajeError;
        }
        
    }
} else if ($_REQUEST["action"] == 'M') {
    $config["action"] = "M";
    if (count($_POST)) {
        //print_r($_POST);
        $config["data"] = $_POST["data"];
        $mensaje = daltb::Actualizartb($_POST["data"]);
        
        if ($mensaje == 1) {

            $config['tipo_identificacion'] = (!isset($config["data"]["individuo"]["tipoId"]) ? NULL : $config["data"]["individuo"]["tipoId"]);
            $config['numero_identificacion'] = (!isset($config["data"]["individuo"]["identificador"]) ? NULL : $config["data"]["individuo"]["identificador"]);
            if ($config["guardarPrevio"] == "2") {
                $config["info"] = Etiquetas::mensajeInfoActualizarMuestra;
                header("location: formulario.php?&action=R&tipo=" . $config['tipo_identificacion']
                        . "&id=" . $config['numero_identificacion'] . "&info=" . $config["info"] . "&guardarPrevio=" . $config["guardarPrevio"]);
            } else {
//                $config["info"] = Etiquetas::mensajeInfoActualizar;
//                header("location: formulario.php?&action=R&tipo=" . $config['tipo_identificacion']
//                        . "&id=" . $config['numero_identificacion'] . "&info=" . $config["info"]);
            }
        } else if ($mensaje == 2) {
            $config["Merror"] = Etiquetas::mensajeErrorActualizar;
            $config["action"] = "R";
        }
    }
} else if ($_REQUEST["action"] == 'D') {
    $config["action"] = "D";
    if (isset($_REQUEST["idtb"])) { // D borrar
        $config['idtb'] = $_REQUEST["idtb"];
        $mensaje = daltb::Borrartb($config);
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
            $data = helpertb::buscartb($config);
            $config['read'] = $data[0];

            $idTB = $config['read']['id_tb'];
            $MDR = helpertb::buscartbMDR($idTB);
            $config["MDR"]= $MDR;
            $Inmunodepresor = helpertb::buscartbInmunodepresor($idTB);
            $config["Inmunodepresor"]= $Inmunodepresor;

        } else {
            $config["data"] = $_POST["data"];
            $config["id_reg"] = $_REQUEST["reg"];
            if ($_REQUEST["reg"] !=  ""){
                $data = helpertb::buscartbID($config);
            } else
            {
                $config['tipo_identificacion'] = (!isset($config["data"]["individuo"]["tipoId"]) ? NULL : $config["data"]["individuo"]["tipoId"]);
                $config['numero_identificacion'] = (!isset($config["data"]["individuo"]["identificador"]) ? NULL : $config["data"]["individuo"]["identificador"]);
                $data = helpertb::buscartb($config);
            }    
            
            $config['read'] = $data[0];
            
            $idTB = $config['read']['id_tb'];
            $MDR = helpertb::buscartbMDR($idTB);
            $config["MDR"]= $MDR;
            $Inmunodepresor = helpertb::buscartbInmunodepresor($idTB);
            $config["Inmunodepresor"]= $Inmunodepresor;
//            $idUceti = $config['read']['id_tb'];
//            $enfermedades = helpertb::buscartbEnfermedad($config['tipo_identificacion'], $config['numero_identificacion']);
//            $config['enfermedades'] = $enfermedades;
////            $vacunas = helpertb::buscartbVacunas($config['tipo_identificacion'], $config['numero_identificacion']);
//            $config['vacunas'] = $vacunas;
////            $muestras = helpertb::buscartbMuestrasSilab($idUceti);
//            $config['muestras'] = $muestras;
////            $tipoMuestras = helpertb::buscartbTipoMuestras($idUceti);
//            $config['tipoMuestras'] = $tipoMuestras;
        }
    }
} else if (isset($config["action"])) {
    if ($config["action"] == "R") { //read
        $config["data"] = $_POST["data"];
//        print_r($config['data']);
//        exit;
        $config['tipo_identificacion'] = (!isset($config["data"]["individuo"]["tipoId"]) ? NULL : $config["data"]["individuo"]["tipoId"]);
        $config['numero_identificacion'] = (!isset($config["data"]["individuo"]["identificador"]) ? NULL : $config["data"]["individuo"]["identificador"]);
        $data = helpertb::buscartb($config);
        $config['read'] = $data[0];
        $idUceti = $config['read']['id_tb'];
//        $enfermedades = helpertb::buscartbEnfermedad($config['tipo_identificacion'], $config['numero_identificacion']);
        $config['enfermedades'] = $enfermedades;
//        $vacunas = helpertb::buscartbVacunas($config['tipo_identificacion'], $config['numero_identificacion']);
        $config['vacunas'] = $vacunas;
//        $muestras = helpertb::buscartbMuestrasSilab($idUceti);
        $config['muestras'] = $muestras;
//        $tipoMuestras = helpertb::buscartbTipoMuestras($idUceti);
        $config['tipoMuestras'] = $tipoMuestras;
    }
}

$config["catalogos"]["provincias"] = helperLugar::getProvinciastb($config);  //revisar aquí;
$config["catalogos"]["poblacion"]= helperCatalogos::getTipoPoblaciontb();
$config["catalogos"]["etnia"]= helperCatalogos::getEtniatb();
$config["catalogos"]["profesion"]= helperCatalogos::getProfesion();
$config["catalogos"]["MDR"]= helperCatalogos::getGrupoRiesgoMDR();
$config["catalogos"]["inmunodepresor"]= helperCatalogos::getInmunodepresor();
$config["catalogos"]["tipoId"] = helperCatalogos::getTipoIdentificacion();
if (isset($config['read']["id_tb"])){
    $config["catalogos"]["visitas"] = helperCatalogos::getVisitas($config['read']["id_tb"]);
    $config["catalogos"]["controles"] = helperCatalogos::getControles($config['read']["id_tb"]);
    
}
//print_r($config["catalogos"]["visitas"]);
//exit;

$config["catalogos"]["regiones"] = "";
$config["catalogos"]["distritos"] = "";
$config["catalogos"]["corregimientos"] = "";


//$config["laboratorio"]["muestra"] = helperSilab::getMuestra("1");
//print_r($config["laboratorio"]["muestra"]);

$page = new formtb($config);
$page->displayPage();
?>
