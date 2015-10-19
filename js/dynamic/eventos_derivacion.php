<?php
require_once("libs/helper/helperCatalogos.php");

$area_origen = $_REQUEST["a"];
$evento_origen = $_REQUEST["e"];
$area_destino = $_REQUEST["d"];
$tipo = $_REQUEST["t"];

$areas = helperCatalogos::getEventosDerivacion($area_origen, $evento_origen, $area_destino, $tipo);

$result .= "[";
if (is_array($areas)){
	foreach ($areas as $area){
		$result .= "{optionValue:".$area["id_destino"].",optionDisplay:'".htmlentities($area["nombre_destino"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);