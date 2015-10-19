<?php
require_once("libs/helper/helperLugar.php");
require_once("libs/dal/ventanilla/dalHistorial.php");

$informe = $_REQUEST["i"];
$muestra = $_REQUEST["m"];
$resultado = dalHistorial::devolver($informe, $muestra);
echo $resultado;
//$result .= "[";
//$result .= "{optionValue:".$resultado."},";
//$result .= "]";
//echo str_replace(",]","]", $result);
