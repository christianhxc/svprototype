<?php

require_once("libs/helper/helperSilab.php");
require_once("libs/helper/helperVih.php");
require_once("libs/dal/vih/dalVih.php");
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
header('Content-type: text/javascript');

    $ok = true;
    $total = 0;
    $totalFactores = 0;
    $muestras = helperSilab::traerAllDatosSilab();
    $factores = helperSilab::traerAllDatosSilabFactores();
    $conn = new Connection();
    $conn->initConn();
    $conn->begin();

    if ($muestras != NULL){
        foreach ($muestras as $muestra){
            if($muestra["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"]=="HOSP. R.N.A.SOLANO" || $muestra["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"]=="HOSPITAL REGIONAL NICOLAS A. SOLANO"){
                $muestra["cod_ref_minsa"]="0807160401";
                $muestra["MUE_PROC_INST_SALUD"]=1;
            }
            else if($muestra["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"]=="HOSPITAL SANTO TOMAS" || $muestra["HOSPITAL SANTO TOMAS"]=="BANCO DE SANGRE HOSPITAL SANTO TOMAS"){
                $muestra["cod_ref_minsa"]="0808040201";
                $muestra["MUE_PROC_INST_SALUD"]=1;
            }
            else if($muestra["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"]=="HOSP. DR. RAFAEL ESTEVEZ"){
                $muestra["cod_ref_minsa"]="0201012501";
                $muestra["MUE_PROC_INST_SALUD"]=1;
            }
            else if($muestra["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"]=="CENTRO DE SALUD NUEVO VERANILLO"){
                $muestra["cod_ref_minsa"]="0810021801";
                $muestra["MUE_PROC_INST_SALUD"]=1;
            }
            else if($muestra["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"]=="ALBERGUE DE MARIA"){
                $muestra["cod_ref_minsa"]="0301021602";
                $muestra["MUE_PROC_INST_SALUD"]=1;
            }
            else if($muestra["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"]=="HOSPITAL GUSTAVO NELSON COLLADO"){
                $muestra["cod_ref_minsa"]="601040401";
                $muestra["MUE_PROC_INST_SALUD"]=1;
            }
            $param = dalVih::GuardarTabla($conn, "vih_silab_temp", $muestra);
            $ok = $param['ok'];
        }
        if ($factores != NULL){
            foreach ($factores as $factor){
                $param = dalVih::GuardarTabla($conn, "vih_silab_temp_factor_riesgo", $factor);
                $ok = $param['ok'];
            }
        }
    }
    if ($ok){
        $conn->commit();
        $total = helperVih::contarMuestrasSilab();
        $totalFactores = helperVih::contarMuestrasSilabFactores();
        $conn->commit();
    }
    else {
        $conn->rollback();
        $total = -1;
    }
    $conn->closeConn();
    echo $total."$$$".$totalFactores;
//print ( '<pre>' )  ;  
//print_r($pruebas);
//print ( '</pre>' ) ;
//echo "Conclusi&oacute;n Muestra<br/>";

//print ( '<pre>' )  ;  
//print_r($conclusion);
//print ( '</pre>' ) ;
?>