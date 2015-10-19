<?php
require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript');

$codigoGlobal = helperCatalogos::getCodigoGlobal();

$result .= "[";
if (is_array($codigoGlobal)){
	foreach ($codigoGlobal as $codigo){
		$result .= "{optionValue:".$codigo["cod_glo_anio"].",optionDisplay:'".htmlentities($codigo["cod_glo_correlativo"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);