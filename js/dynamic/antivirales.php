<?php

require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript');
$search = $_REQUEST['q'];
//$eventos = helperCatalogos::getEventosAuto($search);
$antivirales = helperCatalogos::getAntiviralesAuto($search);
//for ($i = 0; $i < 10; $i++) {
//    $antivirales[$i]["nombre_antiviral"] = "Antiviral " . $i;
//    $antivirales[$i]["id_antiviral"] = $i;
//}
$result = "";

if (is_array($antivirales)) {
    foreach ($antivirales as $antiviral) {
        $result .= htmlentities($antiviral["nombre_antiviral"]) . "|" . htmlentities($antiviral["id_antiviral"]) . "\n";
    }
}
echo str_replace(",]", "]", $result);