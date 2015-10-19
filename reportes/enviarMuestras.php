<?php
require_once ('libs/Configuration.php');
require_once ('libs/helper/helperMuestra.php');
require_once('reportes/reporteEnvio.php');

    // LOGIN
    require_once ('libs/caus/clsCaus.php');
    require_once ('libs/Configuration.php');

    // Validar si ya ha iniciado sesion
    if (!clsCaus::validarSession()){
        header("location: ../login.php");
    }


if(isset($_REQUEST["m"]))
{
    $muestras = explode('-', $_REQUEST["m"]);
    // Insertar en tabla de informe
    $idInforme = reporteEnvio::crearInforme($muestras);
    
    if($idInforme !=-1)
    {
        // verificar si el idInforme Existe
        $data = helperMuestra::informeExistente($idInforme);
        if($data[0]=='1')
            header("Location: ../ventanilla/historial.php?a=g&r=".$idInforme);
        else
            header("Location: ../ventanilla/historial.php?a=e%r=-1".$idInforme);
        exit;
    }
}

?>
