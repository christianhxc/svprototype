<?php
require_once('libs/dal/vacunas/notificacion/dalNotificacionContacto.php');
require_once('libs/caus/clsCaus.php');
if (!clsCaus::validarSession()){
    header("location: ".Configuration::getUrlprefix()."login.php");
}

header('Content-Type: application/json');
$id = $_REQUEST["id"];
$s = $_REQUEST["s"];
$data = $id == "" ? dalNotificacionContacto::Leer($s) : dalNotificacionContacto::LeerPorId($id);

echo json_encode($data);