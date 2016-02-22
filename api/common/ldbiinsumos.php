<?php
require_once("libs/helper/helperVacunas.php");
require_once('libs/caus/clsCaus.php');
if (!clsCaus::validarSession()){
    header("location: ".Configuration::getUrlprefix()."login.php");
}

header('Content-Type: application/json');
$s = $_REQUEST["s"];
echo json_encode(helperVacunas::ldbiInsumos($s, true));