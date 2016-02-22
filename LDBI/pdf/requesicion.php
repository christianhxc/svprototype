<?php

require_once ('libs/caus/clsCaus.php');
require_once ('libs/pages/LDBI/formLDBIRequesicionPdfPage.php');
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
$config["jsfiles"][] = "LDBI/requesicion.js";

$config["info"] = $_REQUEST["info"];
$config["action"] = "";

$config["action"] = "M";
$config["preselect"] = true;
if (isset($_REQUEST["id"])) { // en M se pierde falta ponerle
    $config['id_requesicion'] = $_REQUEST["id"];
    $data = dalLdbi::buscarRequesicion($config);
    $config['data'] = $data;
}

$page = new formLDBIRequesicionPdfPage($config);
$page->displayPage();

require_once ('libs/Pdf/html2pdf.class.php');
$content = $_POST["datos_a_enviar"];
?>
