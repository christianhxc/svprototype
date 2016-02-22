<?php

require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript; charset=charset=UTF-8');
$search = $_REQUEST['q'];
$unidades = helperCatalogos::getInsumosLDBI($search);

$result = "";

if (is_array($unidades)) {
    foreach ($unidades as $unidad) {
        $val1 = ($unidad["nombre_insumo"]);
        $val2 = $unidad["id_insumo"];
        $val3 = $unidad["unidad_presentacion"];
        $val4 = $unidad["codigo_insumo"];
        $result .= htmlentities( $val1 ) . "|" . htmlentities( $val2 ) . "|" . htmlentities( $val3 ) . "|" . htmlentities( $val4 ) . "\n";
    }
}
echo str_replace(",]", "]", $result);