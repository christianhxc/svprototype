<?php
require_once("libs/helper/helperMat.php");
header('Content-type: text/javascript');

$idProvincia = $_REQUEST["idProvincia"];
$regiones = helperMat::getRegiones($idProvincia);

$result .= "[";
if (is_array($regiones)){
	foreach ($regiones as $region){
		$result .= "{optionValue:".$region["id_nivel_geo2"].",optionDisplay:'".htmlentities($region["nombre_nivel_geo2"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);