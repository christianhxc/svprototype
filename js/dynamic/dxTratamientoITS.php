<?php
require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript');

$sexo = $_REQUEST["sexo"];
$sindromico = $_REQUEST["sindromico"];
$catalogos = helperCatalogos::getTratamientoITS($sexo, $sindromico);

$result .= "[";
if (is_array($catalogos)){
	foreach ($catalogos as $catalogo){
		$result .= "{optionValue:".$catalogo["id_tratamiento"].",optionDisplay:'".htmlentities($catalogo["nombre_tratamiento"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);