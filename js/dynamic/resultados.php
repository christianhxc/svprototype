<?php
require_once("libs/helper/helperMuestra.php");
header('Content-type: text/javascript');

$evento = $_REQUEST["e"];
$tip = $_REQUEST["t"];
$prueba = $_REQUEST["p"];

$resultados = helperMuestra::getResultados($evento, $tip, $prueba);

$result .= "[";
if (is_array($resultados)){
	foreach ($resultados as $resultado){
		$result .= "{optionValue:".$resultado["RES_ID"].",optionDisplay:'".htmlentities($resultado["RES_NOMBRE"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);