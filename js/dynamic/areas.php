<?php
require_once("libs/helper/helperLugar.php");
header('Content-type: text/javascript');

$iddep = $_REQUEST["iddep"];
$areas = helperLugar::getAreasSalud($iddep);

$result .= "[";
if (is_array($areas)){
	foreach ($areas as $area){
		$result .= "{optionValue:".$area["are_sal_id"].",optionDisplay:'".htmlentities($area["are_sal_nombre"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);