<?php
require_once("libs/dal/analista/dalAnalista.php");
require_once("libs/helper/helperString.php");
require_once("libs/helper/helperMuestra.php");
require_once("libs/Configuration.php");

$derivacion = $_REQUEST["id"];
$situacion = dalAnalista::situacionDerivacion($derivacion);

if($situacion[0]==Configuration::ventanilla)
{
    $borrado = dalAnalista::borrarDerivacion($derivacion);
    if($borrado==1)
        echo 1;
    else
        echo 0;
}
else if($situacion[0]==Configuration::enviada)
    echo 2;