<?php
require_once("libs/dal/analista/dalAnalista.php");
require_once("libs/helper/helperString.php");
require_once("libs/Configuration.php");

    // LOGIN
    require_once ('libs/caus/clsCaus.php');
    require_once ('libs/Configuration.php');

    // Validar si ya ha iniciado sesion
    if (!clsCaus::validarSession()){
        header("location: ../login.php");
    }

$idMuestra = $_REQUEST["id"];
$evento = $_REQUEST["ev"];
$tipo = $_REQUEST["tip"];
$seccion = $_REQUEST["sec"];

$conteo = dalAnalista::yaExisteDerivacion($idMuestra, $evento, $tipo);

if(isset($_REQUEST["id"]) && isset($_REQUEST["ev"]) && isset($_REQUEST["tip"]))
{
    if($conteo[0]==0)
    {
        $insertado = dalAnalista::agregarDerivacion($idMuestra, $seccion, $evento, $tipo);
        if($insertado==-1)
            echo 0;
        else if($insertado>0)
        {
            // Agregado correctamente
            echo 1;
        }
    }
    else if($conteo[0]>0)
    {
        // Ya existe
        echo 2;
    }
    else if($conteo[0]==-1)
    {
        echo 0;
    }
}
else
    echo 0;