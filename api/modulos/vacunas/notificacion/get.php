<?php
require_once('libs/dal/vacunas/notificacion/dalNotificacion.php');
require_once('libs/caus/clsCaus.php');
if (!clsCaus::validarSession()){
    header("location: ".Configuration::getUrlprefix()."login.php");
}

header('Content-Type: application/json');
$id = $_REQUEST["id"];
$data = $id == "" ? dalNotificacion::Leer() : dalNotificacion::LeerPorId($id);

echo json_encode($data);