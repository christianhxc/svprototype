<?php
require_once("libs/helper/helperLugar.php");
header('Content-type: text/javascript');

$idProvincia = $_REQUEST["idProvincia"];
$idRegion = $_REQUEST["idRegion"];
$distritos = helperLugar::getDistritoSaludPersona($idProvincia,$idRegion);

$result .= "[";
if (is_array($distritos)){
	foreach ($distritos as $distrito){
		$result .= "{optionValue:".$distrito["codigoDistrito"].",optionDisplay:'".htmlentities($distrito["nombreDistrito"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);

//require_once("libs/helper/helperLugar.php");
//header('Content-type: text/javascript');
//
////$config["idas"] = $_REQUEST["idas"];
//$idProvincia = $_REQUEST["idProvincia"];
////$config["iddep"] = $_REQUEST["iddep"] != 0 ? $_REQUEST["iddep"] : 0;
////$config["idmun"] = $_REQUEST["idmun"] != 0 ? $_REQUEST["idmun"] : 0;
//$datos = helperLugar::getDistritosSaludPersona($config);
////echo "<pre>"; print_r($datos); echo "</pre>";
////[{optionValue:20, optionDisplay: 'Aidan'}, {optionValue:21, optionDisplay:'Russell'}]
//
//$result .= "[";
//if (is_array($datos)){
//	foreach ($datos as $dato){
//		$result .= "{optionValue:".$dato["codigods"].",optionDisplay:'".htmlentities($dato["nombreds"])."'},";
//	}
//}
//$result .= "]";
//echo str_replace(",]","]", $result);
