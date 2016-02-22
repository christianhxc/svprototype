<?php
require_once('libs/pages/pai/notificacion/alertaPage.php');
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

$config["jsfiles"][] = "angular/pai-notificacion/controllers/notificacionAlertaEditController.js";
$config["jsfiles"][] = "angular/pai-notificacion/services/notificacionAlertaEditService.js";
$config["jsfiles"][] = "angular/pai-notificacion/services/notificacionAlertaService.js";
$config["jsfiles"][] = "angular/common/services/tipoAlertasService.js";
$config["jsfiles"][] = "angular/common/services/escenariosService.js";
$config["jsfiles"][] = "angular/common/services/escenarioVacunasService.js";

$page = new alertaPage($config);
$page->displayPage();