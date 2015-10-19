<?php
require_once("libs/helper/helperCatalogos.php");

$area_origen = $_REQUEST["a"];
$evento_origen = $_REQUEST["e"];
$tipo = $_REQUEST["t"];

$areas = helperCatalogos::getAreasAnalisisDerivacion($area_origen, $evento_origen, $tipo);

$result .= "[";
if (is_array($areas)){
	foreach ($areas as $area){
		$result .= "{optionValue:".$area["id_area_destino"].",optionDisplay:'".htmlentities($area["nombre_area_destino"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);