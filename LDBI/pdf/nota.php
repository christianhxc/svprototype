<?php

require_once ('libs/caus/clsCaus.php');
require_once ('libs/pages/LDBI/formLDBINotaPdfPage.php');
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
$config["jsfiles"][] = "LDBI/pdf/nota.js";

if (isset($_REQUEST["id"])) {
    $config['id_nota'] = $_REQUEST["id"];
    $data = dalLdbi::buscarNotaAjuste($config);
    $config['data'] = $data;
}

$page = new formLDBINotaPdfPage($config);
$page->displayPage();
?>
