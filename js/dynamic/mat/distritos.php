<?php
require_once("libs/helper/helperMat.php");
header('Content-type: text/javascript');

$idProvincia = $_REQUEST["idProvincia"];
$idRegion = $_REQUEST["idRegion"];
$distritos = helperMat::getDistritos($idProvincia,$idRegion);

$result .= "[";
if (is_array($distritos)){
	foreach ($distritos as $distrito){
		$result .= "{optionValue:".$distrito["id_nivel_geo3"].",optionDisplay:'".htmlentities($distrito["nombre_nivel_geo3"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);
