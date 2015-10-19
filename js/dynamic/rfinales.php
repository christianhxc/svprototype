<?php
require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript');

$evento = $_REQUEST["e"];
$resultados = helperCatalogos::getResultadosFinales($evento);

$result .= "[";
if (is_array($resultados)){
	foreach ($resultados as $resultado){
		$result .= "{optionValue:".$resultado["id"].",optionDisplay:'".htmlentities($resultado["nombre"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);