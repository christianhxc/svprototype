<?php

require_once ('libs/caus/clsCaus.php');
require_once ('libs/pages/vacunas/formVacunasPage.php');
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
$config["jsfiles"][] = "vacunas/formulario.js";

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
            case ConfigurationCAUS::VacFormulario:
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
        $mensaje = dalVacunas::GuardarEsquema($_POST["data"]);
        //echo $mensaje; exit;
        if ($mensaje == 2) {
            $config["Merror"] = "Ya existe un formulario ingresado al sistema con el codigo: <br/>
                    - ".$config["data"]["vacuna"]["codigo"];
        }        
        else {
            $respuesta = explode("-",$mensaje);
            if ($respuesta[0] == 1){ 
                $config["info"] = Etiquetas::mensajeInfo;
                header("location: formulario.php?action=R&idform=".$respuesta[1]."&info=" . $config["info"]);
            }
        } 
    }
}

//Modificar formulario
else if ($_REQUEST["action"] == 'M') {
    $config["action"] = "M";
    if (count($_POST)) {
        //print_r($_POST); exit;
        $config["data"] = $_POST["data"];
        $mensaje = dalVacunas::ActualizarFormEsquema($_POST["data"]);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfoActualizar;
            header("location: formulario.php?action=R&idform=".$config["data"]["vacuna"]["idEsquemaForm"]."&info=" . $config["info"]);
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
        $mensaje = dalVacunas::BorrarFormEsquema($config);
//        $mensaje .= "hola";
//        echo $mensaje;exit;
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfoBorrar;
            header("location: index.php?info=" . $config["info"]);
        } else {
            $config["Merror"] = Etiquetas::mensajeErrorBorrar;
            header("location: index.php?info=" . $config["Merror"]);
        }
    }
}

// Funcion lectura, traer datos para la lectura
if (isset($_REQUEST["action"])) {
    if ($_REQUEST["action"] == 'R' || $config["action"] == "R") { //read
        $config["action"] = "R";
        if (isset($_REQUEST["idform"])) { // en M se pierde falta ponerle
            $config['id_esquema'] = $_REQUEST["idform"];
        } else {
            $config["data"] = $_POST["data"];
            $config['id_esquema'] = (!isset($config["data"]["vacuna"]["idEsquemaForm"]) ? NULL : $config["data"]["vacuna"]["idEsquemaForm"]);
        }
        $data = helperVacunas::buscarEsquema($config);
        $config['read'] = $data[0];
        $condiciones = helperVacunas::buscarVacCondiciones($config);
        $config['read']['condiciones'] = $condiciones;
    }
}

$config["catalogos"]["condiciones"] = helperCatalogos::getCondiciones();
$config["catalogos"]["vacunas"] = helperCatalogos::getVacunas();
$config["catalogos"]["tipo_denominador"] = helperCatalogos::getTipoDenominador();

$page = new formVacunas($config);
$page->displayPage();
?>
