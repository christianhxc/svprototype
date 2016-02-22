<?php
require_once('libs/dal/vacunas/notificacion/dalNotificacionGrupo.php');
require_once('libs/caus/clsCaus.php');
if (!clsCaus::validarSession()){
    header("location: ".Configuration::getUrlprefix()."login.php");
}

header('Content-Type: application/json');
$id = $_REQUEST["id"];
$s = $_REQUEST["s"];
$data = $id == "" ? dalNotificacionGrupo::Leer($s) : dalNotificacionGrupo::LeerPorId($id);

echo json_encode($data);