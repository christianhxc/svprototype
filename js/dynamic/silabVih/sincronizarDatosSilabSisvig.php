<?php

require_once("libs/helper/helperSilab.php");
require_once("libs/helper/helperVih.php");
require_once("libs/dal/vih/dalVih.php");
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
header('Content-type: text/javascript');

    $ok = false;
    $total = 0;
    $procesadas = 0;
    $personasProcesadas = 0;
    $formProcesados = 0;
    $conn = new Connection();
    $conn->initConn();
    $conn->begin();
    
    $muestras = helperVih::traerDatosSilabNOSisvig();
    if ($muestras != NULL){
        foreach ($muestras as $muestra){
            $procesadas++;
            $individuo = helperVih::dataTblPersonaSilab($muestra);  
            $yaExistePersona = helperVih::yaExistePersonaSilab($individuo);
            if($yaExistePersona==0){
                $personasProcesadas++;
                $param = dalVih::GuardarTabla($conn, "tbl_persona", $individuo);
                $ok = $param['ok'];
                if($ok)
                    $conn->commit();
                else {
                    $conn->rollback();
                    $personasProcesadas--;
                }
            }
            $formulario = helperVih::dataTblMuestraSilab($muestra);
            $yaExisteVih = helperVih::yaExisteVih($formulario);
            if($yaExisteVih == 0){
                $formProcesados++;
                $param = dalVih::GuardarTabla($conn, "vih_form", $formulario);
                $ok = $param['ok'];
                if($ok)
                    $conn->commit();
                else {
                    $conn->rollback();
                    $formProcesados--;
                }
                
            }
//            $conn->closeConn();
//            $conn = new Connection();
//            $conn->initConn();
//            $conn->begin();
        }
    }
//    if ($ok){
        $total = helperVih::contarMuestrasSilab();
        $conn->commit();
//    }
//    else {
//        $conn->rollback();
//        $total = -1;
//    }
    $conn->closeConn();
    echo $total."$$$".$procesadas."$$$".$personasProcesadas."$$$".$formProcesados;
?>