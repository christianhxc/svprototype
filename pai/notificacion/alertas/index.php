<?php
require_once('libs/pages/pai/notificacion/alertasPage.php');
require_once('libs/caus/clsCaus.php');
require_once('libs/Configuration.php');

// Validar si ya ha iniciado sesion
if (!clsCaus::validarSession()){
    header("location: ".Configuration::getUrlprefix()."login.php");
}

$config = array();

// Archivos javascript necesarios
$config["jsfiles"][] = "comun.js";
$config["jsfiles"][] = "bienvenida.js";
$config["jsfiles"][] = "Utils.js";

$config["jsfiles"][] = "angular/pai-notificacion/controllers/notificacionAlertaController.js";
$config["jsfiles"][] = "angular/pai-notificacion/services/notificacionAlertaService.js";
$config["jsfiles"][] = "angular/pai-notificacion/services/notificacionAlertaEditService.js";

$page = new alertasPage($config);
$page->displayPage();