<?php
require_once("libs/helper/helperLugar.php");
header('Content-type: text/javascript');

$iddep = $_REQUEST["iddep"];
$idmun = $_REQUEST["idmun"];
$zonas = helperLugar::getZonas($iddep, $idmun);
//echo "<pre>"; print_r($municipios); echo "</pre>";
//[{optionValue:20, optionDisplay: 'Aidan'}, {optionValue:21, optionDisplay:'Russell'}]

$result .= "[";
if (is_array($zonas)){
	foreach ($zonas as $zona){
		$result .= "{optionValue:".$zona["zona"].",optionDisplay:'".htmlentities($zona["descripcionzona"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);