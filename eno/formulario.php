<?php

require_once ('libs/caus/clsCaus.php');
require_once ('libs/pages/eno/formEnoPage.php');
require_once ('libs/helper/helperLugar.php');
require_once ('libs/helper/helperCatalogos.php');
require_once ('libs/helper/helperString.php');
require_once ('libs/Configuration.php');
require_once ('libs/Etiquetas.php');
require_once ('libs/dal/eno/dalEno.php');

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
$config["jsfiles"][] = "eno/formulario.js";

//Validar si se desea cerrar sesion
if ($_REQUEST["logout"]) {
    clsCaus::cerrarSesion();
    file_get_contents(Configuration::reportAddress . "remotelogin.php?logout=true");
}

// Validar si ya ha iniciado sesion
if (!clsCaus::validarSession()) {
    header("location: ../index.php");
}

$secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::eno);
if (count($secciones)) {
    $permiso = 0;
    foreach ($secciones as $c => $seccion) {
        switch ($c) {
            case ConfigurationCAUS::enoFormulario:
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

//Guardar un encabezado nuevo
else if ($_REQUEST["action"] == 'N') {
    if (count($_POST)) {
        $config["data"] = $_POST["data"];
        //print_r($config["data"]);exit;
        $mensaje = dalEno::GuardarEno($_POST["data"]);
        if ($mensaje == 2) {
            $config["Merror"] = "Ya existe un formulario ingresado al sistema con los siguientes datos<br/>
                    - Semana y anio Epi: ".$config["data"]["encabezado"]["semanaEpi"] ." - ".$config["data"]["encabezado"]["anioEpi"] . "<br/>                
                    - Intalacion de salud: " . $config["data"]["encabezado"]["un_nombre"];
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
        //print_r($_POST);
        $config["data"] = $_POST["data"];
        $mensaje = dalEno::ActualizarEno($_POST["data"]);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfoActualizar;
            header("location: formulario.php?action=R&idform=".$config["data"]["encabezado"]["idEnoForm"]."&info=" . $config["info"]);
        } else if ($mensaje == 2) {
            $config["Merror"] = Etiquetas::mensajeErrorActualizar;
            $config["action"] = "R";
        }
    }
}

//Eliminar un formulario
else if ($_REQUEST["action"] == 'D') {
    $config["action"] = "D";
    if (isset($_REQUEST["idform"])) { // D borrar
        $config['idform'] = $_REQUEST["idform"];
        $mensaje = dalEno::BorrarEno($config);
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
            $config['id_enc'] = $_REQUEST["idform"];
        } else {
            $config["data"] = $_POST["data"];
            $config['id_enc'] = (!isset($config["data"]["encabezado"]["idEnoForm"]) ? NULL : $config["data"]["encabezado"]["idEnoForm"]);
        }
        $data = helperEno::buscarEno($config);
        $config['read'] = $data[0];
        
    }
}



$config["catalogos"]["provincias"] = helperLugar::getProvincias($config);
//echo "aqui voy"; exit;
$config["catalogos"]["servicio"] = helperCatalogos::getServicios();
$config["catalogos"]["regiones"] = "";
if (isset($config["read"]["id_provincia"]))
    $config["catalogos"]["regiones"] = helperLugar::getRegionSaludPersona($config["read"]["id_provincia"]);
$config["catalogos"]["distritos"] = "";
if (isset($config["read"]["id_provincia"])&&isset($config["read"]["id_region"]))
    $config["catalogos"]["distritos"] = helperLugar::getDistritoSaludPersona($config["read"]["id_provincia"], $config["read"]["id_region"]);
$config["catalogos"]["corregimientos"] = "";
if (isset($config["read"]["id_distrito"]))
    $config["catalogos"]["corregimientos"] = helperLugar::getCorregimientoSaludPersona($config["read"]["id_distrito"]);

$page = new formEno($config);
$page->displayPage();
?>
