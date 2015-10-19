<?php

require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript');
$search = $_REQUEST['idGrupoFactorRiesgo'];
$factores = helperCatalogos::getFactorRiesgo($search);


$result .= "[";
if (is_array($factores)){
	foreach ($factores as $factor){
		$result .= "{optionValue:".$factor["id_factor"].",optionDisplay:'".htmlentities($factor["factor_nombre"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);
