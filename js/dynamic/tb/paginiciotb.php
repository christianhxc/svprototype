<?php

require_once("libs/helper/helpertb.php");
require_once("libs/Configuration.php");
require_once('libs/dal/tb/daltb.php');

    // LOGIN
    require_once ('libs/caus/clsCaus.php');

    // Validar si ya ha iniciado sesion
    if (!clsCaus::validarSession()){
        header("location: ../../../login.php");
    }

$data = $_REQUEST["data"];

if ($data["pag_inicio"]["act"] == 'C') {
    
    $result = helpertb::buscarPagIni($data);
    echo json_encode($result);
    exit;
    
} else if ($data["pag_inicio"]["act"] == 'G'){
    
        $result = daltb::Guardartb_inicio($data);   
        echo $result;
}

            

//echo json_encode($data);

