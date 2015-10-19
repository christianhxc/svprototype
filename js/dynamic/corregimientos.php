<?php
require_once("libs/helper/helperLugar.php");
header('Content-type: text/javascript');

$idDistrito = $_REQUEST["idDistrito"];
$corregimientos = helperLugar::getCorregimientoSaludPersona($idDistrito);

$result .= "[";
if (is_array($corregimientos)){
	foreach ($corregimientos as $corregimiento){
		$result .= "{optionValue:".$corregimiento["codigoCorregimiento"].",optionDisplay:'".htmlentities($corregimiento["nombreCorregimiento"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);