<?php

require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript; charset=charset=UTF-8');
$search = $_REQUEST['q'];
$unidades = helperCatalogos::getUnidadesNotificadorasAuto($search);

$result = "";

if (is_array($unidades)) {
    foreach ($unidades as $unidad) {
        $val1 = $unidad["nombre_un"];
        $val2 = $unidad["id_un"];
        $val3 = $unidad["nombre_region"];
        $result .= htmlentities( $val1 ) . "|" . htmlentities( $val2 ) . "|" . htmlentities($val3) . "\n";        
    }
}
echo str_replace(",]", "]", $result);