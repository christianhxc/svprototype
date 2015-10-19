<?php
require_once("libs/dal/ventanilla/dalIngreso.php");
header('Content-type: text/javascript');

$term = $_REQUEST["q"];
$idmun = $_REQUEST["idmun"];
//echo "id mun:".$idmun; 
$establecimientos = dalIngreso::getEstablecimientos($term,$idmun);
$result .= "[";
if (is_array($establecimientos)){
	foreach ($establecimientos as $establecimiento){
            //$result .= ."|".htmlentities($establecimiento["idts"])."\n";
            
            $result .= "{optionValue:".htmlentities($establecimiento["idts"]).
                    ",optionDisplay:'".htmlentities($establecimiento["cod_referencia"])." - ".htmlentities($establecimiento["nombre"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);