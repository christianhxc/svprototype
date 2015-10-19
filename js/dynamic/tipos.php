<?php
require_once("libs/helper/helperMuestra.php");
header('Content-type: text/javascript');

$evento = $_REQUEST["e"];
$resultado = $_REQUEST["r"];

$tipos = helperMuestra::getTipos($evento, $resultado);

$result .= "[";
if (is_array($tipos)){
	foreach ($tipos as $tipo){
		$result .= "{optionValue:".$tipo["TIP_ID"].",optionDisplay:'".htmlentities($tipo["TIP_NOMBRE"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);