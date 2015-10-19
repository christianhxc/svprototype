<?php
require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript');
$search = $_REQUEST['q'];
$eventos = helperCatalogos::getEventosAuto($search);

$result = "";

if (is_array($eventos)){
 foreach ($eventos as $evento){
            $result .= htmlentities($evento["cie_10_1"]." - ".$evento["nombre_evento"])."|".htmlentities($evento["id_evento"])."\n";
 }
}
echo str_replace(",]","]", $result);