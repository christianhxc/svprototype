<?php
require_once("libs/helper/helperLugar.php");
header('Content-type: text/javascript');

$idProvincia = $_REQUEST["idProvincia"];
$regiones = helperLugar::getRegionSaludPersona($idProvincia);

$result .= "[";
if (is_array($regiones)){
	foreach ($regiones as $region){
		$result .= "{optionValue:".$region["codigoRegion"].",optionDisplay:'".htmlentities($region["nombreRegion"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);