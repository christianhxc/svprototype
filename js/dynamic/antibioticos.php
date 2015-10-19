<?php

require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript');
$search = $_REQUEST['q'];
//$eventos = helperCatalogos::getEventosAuto($search);
$antibioticos = helperCatalogos::getAntibioticosAuto($search);
//for ($i = 0; $i < 10; $i++) {
//    $antibioticos[$i]["nombre_antibiotico"] = "Antibiotico " . $i;
//    $antibioticos[$i]["id_antibiotico"] = $i;
//}
$result = "";

if (is_array($antibioticos)) {
    foreach ($antibioticos as $antibiotico) {
        $result .= htmlentities($antibiotico["nombre_antibiotico"]) . "|" . htmlentities($antibiotico["id_cat_antibiotico"]) . "\n";
    }
}
echo str_replace(",]", "]", $result);