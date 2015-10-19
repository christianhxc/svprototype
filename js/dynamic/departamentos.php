<?php
require_once("libs/helper/helperLugar.php");
header('Content-type: text/javascript');

$idpais = $_REQUEST["idpais"];
$departamentos = helperLugar::getDepartamentosIndividuo($idpais);

$result .= "[";
if (is_array($departamentos)){
	foreach ($departamentos as $departamento){
		$result .= "{optionValue:".$departamento["DEP_ID"].",optionDisplay:'".htmlentities($departamento["DEP_NOMBRE"])."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);