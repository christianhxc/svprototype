<?php
require_once("libs/helper/helperLugar.php");
require_once("libs/dal/ventanilla/dalHistorial.php");

$informe = $_REQUEST["i"];
$muestra = $_REQUEST["m"];
$cantidad = dalHistorial::getCountAllSelectivas($informe, $muestra);
echo $cantidad;
//$result .= "[";
//$result .= "{optionValue:".$resultado."},";
//$result .= "]";
//echo str_replace(",]","]", $result);
