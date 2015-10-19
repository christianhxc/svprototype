<?php
require_once("libs/helper/helperMuestra.php");
header('Content-type: text/javascript');

$estado = $_REQUEST["e"];
$motivos = helperMuestra::getMotivos($estado);

$result .= "[";
if (is_array($motivos)){
	foreach ($motivos as $motivo){
		$result .= "{optionValue:".$motivo["MOT_ID"].",optionDisplay:'".htmlentities($motivo["MOT_NOMBRE"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);