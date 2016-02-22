<?php

require_once ('libs/caus/clsCaus.php');
require_once ('libs/pages/vacunas/formVacRegistroDiarioPage.php');
require_once ('libs/helper/helperLugar.php');
require_once ('libs/helper/helperCatalogos.php');
require_once ('libs/helper/helperString.php');
require_once ('libs/Configuration.php');
require_once ('libs/Etiquetas.php');
require_once ('libs/dal/vacunas/dalVacunas.php');

$config = array();
//$config["info"] = $_REQUEST["info"];

// Archivos javascript necesarios
$config["jsfiles"][] = "comun.js";
$config["jsfiles"][] = "Utils.js";
$config["jsfiles"][] = "semana_epi.js";
$config["jsfiles"][] = "ajax.js";
$config["jsfiles"][] = "jquery.autocomplete.js";
$config["cssfiles"][] = "jquery.autocomplete";
$config["jsfiles"][] = "vacunas/registroDiario.js";

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
            case ConfigurationCAUS::VacRegistroDiario:
                $permiso = 1;
                break;
        }
    }
}

if ($permiso == 0) {
    header("location: ../index.php");
}

$config["info"] = $_REQUEST["info"];
$config["data_new"] = $_REQUEST["data_new"];
$config["action"] = "";

//print_r($config["data_new"]);
//echo "//// <br>";
//print_r($_POST);
//echo "=]=== <br>";
//print_r($_REQUEST);

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
        $mensaje = dalVacunas::GuardarRegistroDiario($_POST["data"]);
        //echo $mensaje; exit;
        if ($mensaje == 2) {
            $config["Merror"] = "Ya existe un formulario ingresado al sistema con estos datos ";
        }        
        else {
            $respuesta = explode("-",$mensaje);
            if ($respuesta[0] == 1){ 
                $config["info"] = Etiquetas::mensajeInfo;
                header("location: registroDiario.php?action=R&idform=".$respuesta[1]."&info=" . $config["info"]);
            }
        } 
    }
}

//Modificar formulario
else if ($_REQUEST["action"] == 'M') {
    $config["action"] = "M";
    if (count($_POST)) {
        //print_r($_POST);exit;
        $config["data"] = $_POST["data"];
        $mensaje = dalVacunas::ActualizarRegistroDiario($_POST["data"]);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfoActualizar;
            header("location: registroDiario.php?action=R&idform=".$config["data"]["formulario"]["idForm"]."&info=" . $config["info"]);
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
        $mensaje = dalVacunas::BorrarRegistroDiario($config);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfoBorrar;
            header("location: listadoRegistroDiario.php?info=" . $config["info"]);
        } else {
            $config["Merror"] = Etiquetas::mensajeErrorBorrar;
            header("location: listadoRegistroDiario.php?info=" . $config["Merror"]);
        }
    }
}

// Funcion lectura, traer datos para la lectura
if (isset($_REQUEST["action"])) {
    if ($_REQUEST["action"] == 'R' || $config["action"] == "R") { //read
        $config["action"] = "R";
        if (isset($_REQUEST["idform"])) { // en M se pierde falta ponerle
            $config['id_vac_registro_diario'] = $_REQUEST["idform"];
        } else {
            $config["data"] = $_POST["data"];
            $config['id_vac_registro_diario'] = (!isset($config["data"]["formulario"]["idForm"]) ? NULL : $config["data"]["formulario"]["idForm"]);
        }
        $data = helperVacunas::buscarRegistroDiario($config);
        $config['read'] = $data[0];
        
        $condiciones = helperVacunas::buscarVacCondicionesPersona($config);
        //print_r($condiciones);exit;
        $config['read']['condiciones'] = $condiciones;
    }
}

//print_r($config['read']);exit;

$config["catalogos"]["tipoId"] = helperCatalogos::getTipoIdentificacion();
$config["catalogos"]["provincias"] = helperLugar::getProvincias($config);
$config["catalogos"]["paises"] = helperLugar::getPaises();
$config["catalogos"]["cargos"] = helperCatalogos::getCargo();
$config["catalogos"]["condiciones"] = helperCatalogos::getCondiciones();
$config["catalogos"]["modalidades"] = helperCatalogos::getModalidades();
$config["catalogos"]["zonas"] = helperCatalogos::getZonas();
$page = new formVacRegistroDiario($config);
$page->displayPage();
?>
