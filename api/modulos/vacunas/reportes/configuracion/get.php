<?php
require_once('libs/dal/vacunas/reportes/dalReportesConfiguracion.php');
require_once('libs/caus/clsCaus.php');
if (!clsCaus::validarSession()){
    header("location: ".Configuration::getUrlprefix()."login.php");
}

header('Content-Type: application/json');
$data = dalReportesConfiguracion::Leer();

echo json_encode($data);