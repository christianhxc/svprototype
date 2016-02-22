<?php
require_once('libs/dal/vacunas/notificacion/dalNotificacion.php');
require_once('libs/caus/clsCaus.php');
if (!clsCaus::validarSession()){
    header("location: ".Configuration::getUrlprefix()."login.php");
}

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);
$data = dalNotificacion::Actualizar($data);

echo json_encode($data);