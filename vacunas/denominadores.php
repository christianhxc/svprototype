<?php

require_once ('libs/caus/clsCaus.php');
require_once ('libs/pages/vacunas/formDenominadoresPage.php');
require_once ('libs/helper/helperLugar.php');
require_once ('libs/helper/helperCatalogos.php');
require_once ('libs/helper/helperString.php');
require_once ('libs/Configuration.php');
require_once ('libs/Etiquetas.php');
require_once ('libs/dal/vacunas/dalVacunas.php');

$config = array();
$config["info"] = $_REQUEST["info"];

// Archivos javascript necesarios
$config["jsfiles"][] = "comun.js";
$config["jsfiles"][] = "Utils.js";
$config["jsfiles"][] = "semana_epi.js";
$config["jsfiles"][] = "ajax.js";
$config["jsfiles"][] = "jquery.autocomplete.js";
$config["cssfiles"][] = "jquery.autocomplete";
$config["jsfiles"][] = "vacunas/denominadores.js";

//Validar si se desea cerrar sesion
if ($_REQUEST["logout"]) {
    clsCaus::cerrarSesion();
    file_get_contents(Configuration::reportAddress . "remotelogin.php?logout=true");
}

// Validar si ya ha iniciado sesion
if (!clsCaus::validarSession()) {
    header("location: ../index.php");
}

$secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::Vacunas);
if (count($secciones)) {
    $permiso = 0;
    foreach ($secciones as $c => $seccion) {
        switch ($c) {
            case ConfigurationCAUS::VacDenominadores:
                $permiso = 1;
                break;
        }
    }
}

if ($permiso == 0) {
    header("location: ../index.php");
}

$config["info"] = $_REQUEST["info"];
$config["action"] = "";

//print_r($config);exit;

if (!isset($_REQUEST["action"])) {
    // No lleva datos que cargar/mostrar
    $config["action"] = "N";
    $config["error"] = false;
    $config["preselect"] = false;
} 

//Guardar un nuevo formulario
else if ($_REQUEST["action"] == 'N') {
    if (count($_POST)) {
        $config["data"] = $_POST["data"];
        //print_r($config["data"]);exit;
        $mensaje = dalVacunas::GuardarDenominador($_POST["data"]);
        //echo $mensaje; exit;
        if ($mensaje == 2) {
            $config["Merror"] = "Se produjo un error al intentar guardar, verificar los datos e intentarlo de nuevo";
        }        
        else {
            $respuesta = explode("-",$mensaje);
            if ($respuesta[0] == 1){ 
                $config["info"] = Etiquetas::mensajeInfo;
                header("location: denominadores.php?action=R&idform=".$respuesta[1]."&info=" . $config["info"]);
            }
        } 
    }
}

//Modificar formulario
else if ($_REQUEST["action"] == 'M') {
    $config["action"] = "M";
    if (count($_POST)) {
        //print_r($_POST);
        $config["data"] = $_POST["data"];
        $mensaje = dalVacunas::ActualizarDenominador($_POST["data"]);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfoActualizar;
            header("location: denominadores.php?action=R&idform=".$config["data"]["distribucion"]["idForm"]."&info=" . $config["info"]);
        } else if ($mensaje == 2) {
            $config["Merror"] = Etiquetas::mensajeErrorActualizar;
            $config["action"] = "R";
        }
    }
}

//Eliminar un formulario
else if ($_REQUEST["action"] == 'D') {
    $config["action"] = "D";
    if (isset($_REQUEST["idForm"])) { // D borrar
        $config['idForm'] = $_REQUEST["idForm"];
        $mensaje = dalVacunas::BorrarDenominador($config);
//        $mensaje .= "hola";
//        echo $mensaje;exit;
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfoBorrar;
            header("location: listadoDenominadores.php?info=" . $config["info"]);
        } else {
            $config["Merror"] = Etiquetas::mensajeErrorBorrar;
            header("location: listadoDenominadores.php?info=" . $config["Merror"]);
        }
    }
}

// Funcion lectura, traer datos para la lectura
if (isset($_REQUEST["action"])) {
    if ($_REQUEST["action"] == 'R' || $config["action"] == "R") { //read
        $config["action"] = "R";
        
        if (isset($_REQUEST["idform"])) { // en M se pierde falta ponerle
            $config['id_vac_denominador'] = $_REQUEST["idform"];
        } else {
            $config["data"] = $_POST["data"];
            $config['id_vac_denominador'] = (!isset($config["data"]["distribucion"]["idForm"]) ? NULL : $config["data"]["distribucion"]["idForm"]);
        }
        $data = helperVacunas::buscarDenominador($config);
        $config['read'] = $data[0];
    }
}

$config["catalogos"]["provincias"] = helperLugar::getProvincias($config);
$config["catalogos"]["grupos"] = helperCatalogos::getGrupoEspPoblacion();
$page = new formDenominadores($config);
$page->displayPage();
?>
