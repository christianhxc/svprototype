<?php
require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript');

$sexo = $_REQUEST["sexo"];
$sindromico = $_REQUEST["sindromico"];
$catalogos = helperCatalogos::getDxEtiologicoITS($sexo, $sindromico);

$result .= "[";
if (is_array($catalogos)){
	foreach ($catalogos as $catalogo){
		$result .= "{optionValue:".$catalogo["id_diag_etiologico"].",optionDisplay:'".htmlentities($catalogo["nombre_diag_etiologico"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);