<?php
require_once("libs/helper/helperMat.php");
header('Content-type: text/javascript');

$idDistrito = $_REQUEST["idDistrito"];
$corregimientos = helperMat::getCorregimientos($idDistrito);

$result .= "[";
if (is_array($corregimientos)){
	foreach ($corregimientos as $corregimiento){
		$result .= "{optionValue:".$corregimiento["id_nivel_geo4"].",optionDisplay:'".htmlentities($corregimiento["nombre_nivel_geo4"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);