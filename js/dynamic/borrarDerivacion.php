<?php
require_once("libs/dal/analista/dalAnalista.php");
require_once("libs/helper/helperString.php");
require_once("libs/helper/helperMuestra.php");
require_once("libs/Configuration.php");

$id = $_REQUEST["id"];
$estadoDerivacion = dalAnalista::situacionDerivacion($id);
$pruebas = helperMuestra::pruebasAsignadas($id);        
$conclusion = helperMuestra::conclusionAsignada($id);

// Puede anular una derivación si no la ha enviado
if($estadoDerivacion[0]==Configuration::enAreaGeneradora && $pruebas[0]==0 && $conclusion[0]==0)
{
    $borrado = dalAnalista::borrarDerivacion($id);
    if($borrado==1)
        echo 1;
    else
        echo 0;
}
else if($estadoDerivacion[0]==Configuration::enviadaDer)
    echo 2;