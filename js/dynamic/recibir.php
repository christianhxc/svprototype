<?php
require_once("libs/helper/helperLugar.php");
require_once("libs/dal/ventanilla/dalHistorial.php");
header('Content-type: text/javascript');

$informe = $_REQUEST["i"];
$muestra = $_REQUEST["m"];
$resultado = dalHistorial::devolver($i, $m);

$result .= "[";
if (is_array($areas)){
	foreach ($areas as $area){
		$result .= "{optionValue:".$area["are_sal_id"].",optionDisplay:'".htmlentities($area["are_sal_nombre"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);