<?php
require_once('libs/pages/vacunas/reportes/configuracionPage.php');
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

$config["jsfiles"][] = "angular/vacunas/controllers/reportesController.js";
$config["jsfiles"][] = "angular/common/services/escenariosService.js";
$config["jsfiles"][] = "angular/common/services/escenarioVacunasService.js";
$config["jsfiles"][] = "angular/common/services/escenarioVacunasDosisService.js";
$config["jsfiles"][] = "angular/vacunas/services/gruposEdadService.js";
$config["jsfiles"][] = "angular/vacunas/services/configuracionReporteVacunasService.js";
$config["jsfiles"][] = "angular/vacunas/services/configuracionReporteVacunasNewService.js";
$config["jsfiles"][] = "angular/vacunas/services/configuracionReporteVacunasUpdateService.js";
$config["jsfiles"][] = "angular/vacunas/services/configuracionReporteVacunasGetToEditService.js";


//$config["catalogos"]["escenarios"] = helperVacunas::buscarEsquema($config);
//echo json_encode($config["escenarios"]); exit;

$page = new configuracionPage($config);
$page->displayPage();