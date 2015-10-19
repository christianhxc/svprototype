<?php
require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript');

$idevento = $_REQUEST["idevento"];
$tipomuestras = helperCatalogos::getTiposMuestra($idevento);

$result .= "[";
if (is_array($tipomuestras)){
	foreach ($tipomuestras as $tipomuestra){
		$result .= "{optionValue:".$tipomuestra["tip_mue_id"].",optionDisplay:'".htmlentities($tipomuestra["tip_mue_nombre"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);