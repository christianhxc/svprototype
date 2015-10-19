<?php
require_once("libs/dal/ventanilla/dalIngreso.php");
header('Content-type: text/javascript');

$term = $_REQUEST["q"];
$idmun = $_REQUEST["idmun"];
//echo "id mun:".$idmun; 
$establecimientos = dalIngreso::getEstablecimientos($term,$idmun);

if (is_array($establecimientos)){
	foreach ($establecimientos as $establecimiento){
            $result .= htmlentities($establecimiento["cod_referencia"])." - ".htmlentities($establecimiento["nombre"])."|".htmlentities($establecimiento["idts"])."\n";
	}
}
echo str_replace(",]","]", $result);