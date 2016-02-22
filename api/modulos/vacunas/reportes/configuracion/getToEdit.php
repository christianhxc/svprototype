<?php
require_once('libs/dal/vacunas/dalVacunas.php');
require_once('libs/Connection.php');
require_once('libs/caus/clsCaus.php');
if (!clsCaus::validarSession()){
    header("location: ".Configuration::getUrlprefix()."login.php");
}

header('Content-Type: application/json');

$id = $_GET["id"];

$conn = new Connection();
$conn->initConn();
$conn->begin();

$sql = "SELECT * FROM vac_registro_diario_reporte_configuracion WHERE id_configuracion = ".$id." AND deleted = 0";
$cabecera = dalVacunas::selectQuery($conn, $sql);
$sql = "SELECT * FROM vac_registro_diario_reporte_configuracion_vacuna WHERE id_configuracion = ".$id." AND deleted = 0";
$detalle = dalVacunas::selectQuery($conn, $sql);

echo json_encode(array("cabecera" => $cabecera, "detalle" => $detalle));