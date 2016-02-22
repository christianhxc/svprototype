<?php
require_once('libs/pages/pai/notificacion/contactosPage.php');
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

$config["jsfiles"][] = "angular/pai-notificacion/controllers/notificacionContactoController.js";
$config["jsfiles"][] = "angular/pai-notificacion/services/notificacionContactoService.js";
$config["jsfiles"][] = "angular/pai-notificacion/services/notificacionContactoEditService.js";

$page = new contactosPage($config);
$page->displayPage();