<?php
require_once ('libs/pages/vacunas/reportes/configuracionesPage.php');
require_once ('libs/caus/clsCaus.php');
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

$config["jsfiles"][] = "angular/vacunas/controllers/reportesConfiguracionesController.js";
$config["jsfiles"][] = "angular/vacunas/services/configuracionReporteVacunasService.js";
$config["jsfiles"][] = "angular/vacunas/services/configuracionReporteVacunasUpdateService.js";

$page = new configuracionesPage($config);
$page->displayPage();