<?php
require_once("libs/helper/helperMuestra.php");
header('Content-type: text/javascript');

$evento = $_REQUEST["e"];
$resultado = $_REQUEST["r"];
$tipo = $_REQUEST["tp"];

$subtipos = helperMuestra::getSubtipos($evento, $resultado, $tipo);

$result .= "[";
if (is_array($subtipos)){
	foreach ($subtipos as $subtipo){
		$result .= "{optionValue:".$subtipo["SUB_ID"].",optionDisplay:'".htmlentities($subtipo["SUB_NOMBRE"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);