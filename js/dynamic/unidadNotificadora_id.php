<?php

require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript; charset=charset=UTF-8');
$search = $_REQUEST['q'];
$unidades = helperCatalogos::getUnidadesNotificadoraCorregimiento($search);

$result = "";

if (is_array($unidades)) {
    foreach ($unidades as $unidad) {
        $val1 = $unidad["nombre_un"];
        $val2 = $unidad["id_un"];
        $val3 = $unidad["id_provincia"];
        $val4 = $unidad["id_region"];
        $val5 = $unidad["id_distrito"];
        $val6 = $unidad["id_corregimiento"];
        $result .= htmlentities( $val1 ) . "|" . htmlentities( $val2 ) . "|" . htmlentities($val3). "|" . htmlentities($val4). "|" . htmlentities($val5). "|" . htmlentities($val6) . "\n";        
    }
}
echo str_replace(",]", "]", $result);