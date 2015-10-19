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
    $error = 0;
    $conn = new Connection();
    $conn->initConn();
    $conn->begin();
    
    $registros = helperVih::traerDatosFactoresSilab();
    if ($registros != NULL){
        foreach ($registros as $registro){
            $procesadas++;
            $formActualizar = helperVih::dataActualizarTblVihSilab($registro); 
            $filtro = array();
            $filtro["id_tipo_identidad"] = $formActualizar["id_tipo_identidad"];
            $filtro["numero_identificacion"] = $formActualizar["numero_identificacion"];
            unset($formActualizar["id_tipo_identidad"]);
            unset($formActualizar["numero_identificacion"]);
            $totalFormFiltro = $formActualizar;
            $totalFormFiltro["filter1"] = $filtro["id_tipo_identidad"];
            $totalFormFiltro["filter2"] = $filtro["numero_identificacion"];
            $param = dalVih::ActualizarTabla($conn, "vih_form", $formActualizar, $filtro, $totalFormFiltro);
            $ok = $param['ok'];
            
            if($ok){
                $sql = helperVih::insertVihFactorRiesgo($registro);
                if ($sql!=""){
                    $param = dalVih::ejecutarQuery($conn, $sql);
                    $error .= $param['ok']==true?'':$param['ok'];
                }
            }
        }
    }
    if ($ok){
        $total = helperVih::contarFactoresSilab();
        $sql = "TRUNCATE TABLE vih_silab_temp;";
        $param = dalVih::ejecutarQuery($conn, $sql);
        $sql = "TRUNCATE TABLE vih_silab_temp_factor_riesgo;";
        $param = dalVih::ejecutarQuery($conn, $sql);
        $ok = $param['ok'];
        $conn->commit();
    }
    else {
        $conn->rollback();
        $total = -1;
    }
    $conn->closeConn();
    echo $total."$$$".$procesadas."$$$".$error;
?>