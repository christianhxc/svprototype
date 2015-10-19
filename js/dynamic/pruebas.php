<?php
require_once("libs/helper/helperMuestra.php");
header('Content-type: text/javascript');

$evento = $_REQUEST["e"];
$tip = $_REQUEST["t"];

$pruebas = helperMuestra::getPruebas($evento, $tip);

$result .= "[";
if (is_array($pruebas)){
	foreach ($pruebas as $prueba){
		$result .= "{optionValue:".$prueba["PRU_ID"].",optionDisplay:'".htmlentities($prueba["PRU_NOMBRE"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);