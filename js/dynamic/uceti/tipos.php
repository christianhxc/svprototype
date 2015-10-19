<?php
require_once("libs/helper/helperSilabRemoto.php");
header('Content-type: text/javascript');

$evento = $_REQUEST["e"];
$resultado = $_REQUEST["r"];

$tipos = helperSilabRemoto::getTiposNombre($evento, $resultado);

$result .= "[";
if (is_array($tipos)){
	foreach ($tipos as $tipo){
//		$result .= "{optionValue:".$tipo["TIP_ID"].",optionDisplay:'".htmlentities($tipo["TIP_NOMBRE"])."'},";
            $result .= "{optionValue:'".$tipo["TIP_NOMBRE"]."',optionDisplay:'".htmlentities($tipo["TIP_NOMBRE"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);
