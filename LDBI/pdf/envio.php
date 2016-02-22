<?php

require_once ('libs/caus/clsCaus.php');
require_once ('libs/pages/LDBI/formLDBIEnvioPdfPage.php');
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
$config["jsfiles"][] = "LDBI/pdf/envio.js";

$config["action"] = "M";
$config["preselect"] = true;
if (isset($_REQUEST["id"])) { // en M se pierde falta ponerle
    $config['id_envio'] = $_REQUEST["id"];
    $data = dalLdbi::buscarEnvioBodega($config);
    $config['data'] = $data;
}

$config["catalogos"]["status"] = array(
    array("id" => "1", "status" => "En transito"),
    array("id" => "2", "status" => "Entregados")
);

$page = new formLDBIEnvioPdfPage($config);
$page->displayPage();
?>
