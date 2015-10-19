<?php

require_once ('libs/caus/clsCaus.php');
require_once ('libs/pages/notic/formNoticPage.php');
require_once ('libs/helper/helperLugar.php');
require_once ('libs/helper/helperCatalogos.php');
require_once ('libs/helper/helperString.php');
require_once ('libs/Configuration.php');
require_once ('libs/Etiquetas.php');
require_once ('libs/dal/notic/dalNotic.php');

$config = array();
$config["info"] = $_REQUEST["info"];

// Archivos javascript necesarios
$config["jsfiles"][] = "comun.js";
$config["jsfiles"][] = "Utils.js";
$config["jsfiles"][] = "semana_epi.js";
//    $config["jsfiles"][] = "Etiquetas.js";
$config["jsfiles"][] = "ajax.js";
$config["jsfiles"][] = "jquery.autocomplete.js";
$config["cssfiles"][] = "jquery.autocomplete";
$config["jsfiles"][] = "notic/formulario.js";

//Validar si se desea cerrar sesion
if ($_REQUEST["logout"]) {
    clsCaus::cerrarSesion();
    file_get_contents(Configuration::reportAddress . "remotelogin.php?logout=true");
}

// Validar si ya ha iniciado sesion
if (!clsCaus::validarSession()) {
    header("location: ../index.php");
}

$secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::notic );
if (count($secciones)) {
    $permiso = 0;
    foreach ($secciones as $c => $seccion) {
        switch ($c) {
            case ConfigurationCAUS::noticFormulario:
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
        $mensaje = dalNotic::GuardarNotic($_POST["data"]);
        if ($mensaje == 2) {
            $config["Merror"] = "Ya existe un formulario ingresado al sistema desde esa instalacion de salud y con los siguientes datos<br/>
                    - Semana: ".$config["data"]["clinica"]["semana_epi"]." y el anio: ".$config["data"]["clinica"]["anio_epi"]."<br/>                
                    - Numero de identificacion: " . $config["data"]["individuo"]["identificador"];
        }        
        else {
            $respuesta = explode("-",$mensaje);
            if ($respuesta[0] == 1){ 
                $config["info"] = Etiquetas::mensajeInfo;
                header("location: formulario.php?action=R&idForm=".$respuesta[1]);
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
        $mensaje = dalNotic::ActualizarForm($_POST["data"]);
        if ($mensaje == 2) {
            $config["Merror"] = Etiquetas::mensajeErrorActualizar;
            $config["action"] = "R";
        }
        else {
            $respuesta = explode("-",$mensaje);
            if ($respuesta[0] == 1){ 
                $config["info"] = Etiquetas::mensajeInfo;
                header("location: formulario.php?action=R&idForm=".$respuesta[1]);
            }
        } 
    }
}

//Eliminar un formulario
else if ($_REQUEST["action"] == 'D') {
    $config["action"] = "D";
    if (isset($_REQUEST["idForm"])) { // D borrar
        $config['idform'] = $_REQUEST["idForm"];
        $mensaje = dalNotic::BorrarForm($config);
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
        if (isset($_REQUEST["idForm"])) { // en M se pierde falta ponerle
            $config['id_form'] = $_REQUEST["idForm"];
        } else {
            $config["data"] = $_POST["data"];
            $config['id_form'] = (!isset($config["data"]["formulario"]["idForm"]) ? NULL : $config["data"]["formulario"]["idForm"]);
        }
        if (isset($config['id_form'])){
            $data = helperNotic::buscarNotic($config);
            $config['read'] = $data[0];
            $sintomas = helperNotic::buscarNoticSintomas($config);
            $config['read']['sintomas'] = $sintomas;
            //print_r($config['read']);exit;
        }
    }
}

$config["catalogos"]["tipoId"] = helperCatalogos::getTipoIdentificacion();
$config["catalogos"]["provincias"] = helperLugar::getProvinciasUceti();
$config["catalogos"]["paises"] = helperLugar::getPaises();
$config["catalogos"]["cargos"] = helperCatalogos::getCargo();
$config["catalogos"]["servicios"] = helperCatalogos::getServicios();
$config["catalogos"]["signosSintomas"] = helperCatalogos::getSintomas();
$config["catalogos"]["tipoMuestras"] = helperCatalogos::getTipoMuestra();

$page = new formNotic($config);
$page->displayPage();
?>
