<?php
require_once("libs/helper/helperLugar.php");
require_once("libs/Configuration.php");
header('Content-type: text/javascript');

$config["idas"] = $_REQUEST["idas"];
$config["idds"] = $_REQUEST["idds"];
$datos = helperLugar::getServiciosSalud($config);
//print_r($datos);exit;
$result .= "[";
if (is_array($datos)){
	foreach ($datos as $dato){
		$result .= "{optionValue:".$dato["idts"].",optionDisplay:'".htmlentities($dato["nombre"])."', tipo:'".$dato["tipo"]."', hosp:'".
                (esHospital($dato["tipo"])?"h":"o")."'},";
	}
}
$result .= "]";
echo str_replace(",]","]", $result);

/*
 * @tipo: tipo del hospital
 * Retorna true si es de tipo hospital definidos en Configuration.php
 */
function esHospital($tipo)
{
    $tipos = explode('-',Configuration::tiposHospitales);
    if(in_array($tipo, $tipos))
            return true;
    else
        return false;
}