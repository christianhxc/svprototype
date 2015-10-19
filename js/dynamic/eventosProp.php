<?php
require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript');

$idevento = $_REQUEST["idevento"];
$eventos = helperCatalogos::getEvento($idevento);

$result .= "[";
if (is_array($eventos)){
	foreach ($eventos as $evento){
		$result .= "{pacienteDonador:".$evento["eve_paciente_donador"].", confidencial:".$evento["eve_confidencial"].", cargaViral:".$evento["eve_carga_viral"]
                .", inicioSintomas:".$evento["eve_inicio_sintomas"]
                .", encuestaSerologica:".$evento["eve_serologica"]."},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);