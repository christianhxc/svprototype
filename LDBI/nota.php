<?php

require_once ('libs/caus/clsCaus.php');
require_once ('libs/pages/LDBI/formLDBINotaPage.php');
require_once ('libs/helper/helperLugar.php');
require_once ('libs/dal/ldbi/dalLdbi.php');
require_once ('libs/Configuration.php');
require_once ('libs/Etiquetas.php');

// Validar si ya ha iniciado sesion
if (!clsCaus::validarSession()) {
    header("location: ../index.php");
}

$config = array();
$config["info"] = $_REQUEST["info"];

// Archivos javascript necesarios
$config["jsfiles"][] = "comun.js";
$config["jsfiles"][] = "Utils.js";
$config["jsfiles"][] = "ajax.js";
$config["jsfiles"][] = "jquery.qtip-1.0.0.min.js";
$config["jsfiles"][] = "jquery.autocomplete.js";
$config["cssfiles"][] = "jquery.autocomplete";
$config["jsfiles"][] = "LDBI/nota.js";

$config["info"] = $_REQUEST["info"];
$config["action"] = "";
$config["guardarPrevio"] = $_REQUEST["data"]["GuardarPrevio"]["Guardar"];

if (isset($_REQUEST["guardarPrevio"])) {
    $config["guardarPrevio"] = $_REQUEST["guardarPrevio"];
}

// NUEVO COMPLETAMENTE
if (!isset($_REQUEST["action"])) {
    // No lleva datos que cargar/mostrar
    $config["action"] = "N";
    $config["error"] = false;
    $config["preselect"] = false;
} else if ($_REQUEST["action"] == 'N') {
    if (count($_POST)) {
        $config["data"] = $_POST["data"];
        $mensaje = dalLdbi::GuardarNotaAjuste($_POST["data"]);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfo;
            header("location: notas.php?info=" . $config["info"]);
        } else if ($mensaje == 2) {
            $config["error"] = Etiquetas::mensajeError;
        }
    }
} else if ($_REQUEST["action"] == 'M') {
    if (count($_POST)) {
        $config["data"] = $_POST["data"];
        $mensaje = dalLdbi::ActualizarNotaAjuste($_POST["data"]);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfoActualizar;
            header("location: notas.php?info=" . $config["info"]);
        } else if ($mensaje == 2) {
            $config["error"] = Etiquetas::mensajeErrorActualizar;
        }
    }
} else if ($_REQUEST["action"] == 'D') {
    $config["action"] = "D";
    if (isset($_REQUEST["id"])) { // D borrar
        $config['id'] = $_REQUEST["id"];
        $config['req'] = $_REQUEST["req"];
        $mensaje = dalLdbi::BorrarNotaAjuste($config);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfoBorrar;
            header("location: notas.php?info=" . $config["info"]);
        } else {
            $config["Merror"] = Etiquetas::mensajeErrorBorrar;
            header("location: notas.php?info=" . $config["Merror"]);
        }
    }
} else if ($_REQUEST["action"] == 'R' || $config["action"] == "R") { //read
    $config["action"] = "M";
    $config["preselect"] = true;
    if (isset($_REQUEST["id"])) { // en M se pierde falta ponerle
        $config['id_nota'] = $_REQUEST["id"];
        $data = dalLdbi::buscarNotaAjuste($config);
        $config['data'] = $data;
    }
}

$config["catalogos"]["razones"] = helperLugar::getRazones($config);
$config["catalogos"]["regiones"] = helperLugar::getRegionSalud($config);
$config["catalogos"]["status"] = array(
    array("id" => "1", "status" => "En transito"),
    array("id" => "2", "status" => "Entregados")
);

$page = new formLDBINotaPage($config);
$page->displayPage();
?>
