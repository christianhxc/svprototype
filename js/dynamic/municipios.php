<?php
require_once("libs/helper/helperLugar.php");
header('Content-type: text/javascript');

$iddep = $_REQUEST["iddep"];
$municipios = helperLugar::getMunicipios($iddep);
//echo "<pre>"; print_r($municipios); echo "</pre>";
//[{optionValue:20, optionDisplay: 'Aidan'}, {optionValue:21, optionDisplay:'Russell'}]

$result .= "[";
if (is_array($municipios)){
	foreach ($municipios as $municipio){
		$result .= "{optionValue:".$municipio["municipio"].",optionDisplay:'".htmlentities($municipio["descripcionmunicipio"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);