<?php
require_once('libs/pages/pai/notificacion/gruposPage.php');
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

$config["jsfiles"][] = "angular/pai-notificacion/controllers/notificacionGrupoController.js";
$config["jsfiles"][] = "angular/pai-notificacion/services/notificacionGrupoService.js";
$config["jsfiles"][] = "angular/pai-notificacion/services/notificacionGrupoEditService.js";

$page = new gruposPage($config);
$page->displayPage();