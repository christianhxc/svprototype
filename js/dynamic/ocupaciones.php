<?php

require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript');
$search = $_REQUEST['q'];
$ocupaciones = helperCatalogos::getOcupaciones($search);

$result = "";

if (is_array($ocupaciones)) {
    foreach ($ocupaciones as $ocupacion) {
        $val1 = $ocupacion["nombre_ocupacion"];
        $val2 = $ocupacion["id_ocupacion"];
        $result .= htmlentities( $val1 ) . "|" . htmlentities( $val2 ) . "\n";        
    }
}
echo str_replace(",]", "]", $result);