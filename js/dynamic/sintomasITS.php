<?php
require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript');

$sexo = $_REQUEST["sexo"];
$catalogos = helperCatalogos::getSintomasITS($sexo);

$result .= "[";
if (is_array($catalogos)){
	foreach ($catalogos as $catalogo){
		$result .= "{optionValue:".$catalogo["id_signo_sintoma"].",optionDisplay:'".htmlentities($catalogo["nombre_signo_sintoma"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);