<?php

require_once("libs/helper/helperVicIts.php");
header('Content-type: text/javascript');

$config = array();
if (isset($_REQUEST["id"]) && isset($_REQUEST["tipo_id"])){
    $config['id_tipo_identidad'] = $_REQUEST["tipo_id"];
    $config['numero_identificacion'] = $_REQUEST["id"];
    $data = helperVicIts::buscarVicits($config);
    
    $config['read'] = $data[0];
    $idVicItsForm = $config['read']['id_vicits_form'];
    $arrayIts = helperVicIts::buscarVicitsIts($idVicItsForm);
    $arrayDrogas = helperVicIts::buscarVicitsDrogas($idVicItsForm);
        
    $result = '';
    if (is_array($data)) {
        foreach ($data as $antecedentes) {
            $result = htmlentities($antecedentes["antec_abuso_sexual"]) . "#-#" //0
                    . htmlentities($antecedentes["antec_edad_abuso_sexual"]) . "#-#" //1
                    . htmlentities($antecedentes["antec_abuso_ultimo"]) . "#-#" //2
                    . htmlentities($antecedentes["antec_edad_inicio_sexual"]) . "#-#" //3
                    . htmlentities($antecedentes["antec_ts_alguna_vez"]) . "#-#" //4
                    . htmlentities($antecedentes["antec_ts_actual"]) . "#-#" //5
                    . htmlentities($antecedentes["antec_ts_tiempo"]) . "#-#" //6
                    . htmlentities($antecedentes["antec_ts_tiempo_anios"]) . "#-#" //7
                    . htmlentities($antecedentes["antec_ts_otro_pais"]) . "#-#" //8
                    . htmlentities($antecedentes["antec_ts_id_pais"]) . "#-#" //9
                    . htmlentities($antecedentes["antec_relacion"]) . "#-#" //10
                    . htmlentities($antecedentes["antec_its_ultimo"]) . "#-#" //11
                    . htmlentities($antecedentes["antec_ts_nombre_lugar"]) . "#-#" //12
                    . htmlentities($antecedentes["antec_ts_tipo_lugar"]) . "#-#" //13
                    . htmlentities($antecedentes["antec_vih"]) . "#-#" //14
                    . htmlentities($antecedentes["antec_fecha_vih"]) . "#-#" //15
                    . htmlentities($antecedentes["antec_consejeria_pre"]) . "#-#" //16
                    . htmlentities($antecedentes["antec_consejeria_post"]) . "#-#" //17
                    . htmlentities($antecedentes["antec_referido_TARV"]) . "#-#" //18
                    . htmlentities($antecedentes["id_clinica_tarv"]) . "#-#" //19
                    . htmlentities($antecedentes["antec_consumo_alcohol"]) . "#-#" //20
                    . htmlentities($antecedentes["antec_consumo_alcohol_semana"]) . "#-#" //21
                    
                    // ****************** Solo Mujeres **********************
                    
                    . htmlentities($antecedentes["antec_anticonceptivo"]) . "#-#" //22
                    . htmlentities($antecedentes["antec_anticonceptivo_diu"]) . "#-#" //23
                    . htmlentities($antecedentes["antec_anticonceptivo_pildora"]) . "#-#" //24
                    . htmlentities($antecedentes["antec_anticonceptivo_condon"]) . "#-#" //25
                    . htmlentities($antecedentes["antec_anticonceptivo_inyeccion"]) . "#-#" //26
                    . htmlentities($antecedentes["antec_anticonceptivo_esteriliza"]) . "#-#" //27
                    . htmlentities($antecedentes["antec_anticonceptivo_otro"]) . "#-#" //28
                    . htmlentities($antecedentes["antec_anticonceptivo_nombre_otro"]) . "#-#" //29
                    . htmlentities($antecedentes["antec_obstetrico_menarquia"]) . "#-#" //30
                    . htmlentities($antecedentes["antec_obstetrico_abortos"]) . "#-#" //31
                    . htmlentities($antecedentes["antec_obstetrico_muertos"]) . "#-#" //32
                    . htmlentities($antecedentes["antec_obstetrico_vivos"]) . "#-#" //33
                    . htmlentities($antecedentes["antec_obstetrico_total"]). "#-#" //34
                    . htmlentities($antecedentes["per_sabe_leer"]). "#-#-#" ; 
        }
        //ITS relacionadas
        $itsTotal = "";
        if (is_array($arrayIts)) {
            foreach ($arrayIts as $its) {
                $itsTotal .= $its['id_ITS'] . "-" . $its['nombre_ITS'] . "###";
            }
            $itsTotal = substr($itsTotal, 0, strlen($itsTotal) - 3);
            $result .= htmlentities($itsTotal."#-#-#");
        }

        //Consumo de drogas relacionadas
        $drogaTotal = "";
        if (is_array($arrayDrogas)) {
            foreach ($arrayDrogas as $droga) {
                $nombreTiempo = ($droga["fecha_consumo"] == 1) ? "12 meses" : "30 dias";
                $drogaTotal .= $droga['id_droga'] . "#-#" . $droga['nombre_droga'] . "#-#" . $droga['fecha_consumo'] . "#-#" . $nombreTiempo . "###";
            }
            $drogaTotal = substr($drogaTotal, 0, strlen($drogaTotal) - 3);
            $result .= htmlentities($drogaTotal."#-#-#");
        }
    }
    
    
    
    else
        $result = "no";
    echo $result;
}