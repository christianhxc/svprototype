<?php
require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript');

$sexo = $_REQUEST["sexo"];
$catalogos = helperCatalogos::getDxSindromicoITS($sexo);

$result .= "[";
if (is_array($catalogos)){
	foreach ($catalogos as $catalogo){
		$result .= "{optionValue:".$catalogo["id_diag_sindromico"].",optionDisplay:'".htmlentities($catalogo["nombre_diag_sindromico"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);