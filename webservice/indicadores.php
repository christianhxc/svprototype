<?php

    require_once ('libs/caus/clsCaus.php');
    require_once('libs/DOMXMLv2.php');
    require_once('libs/dal/tb/dalIndicadores.php');

    

    
   if (isset($_REQUEST["n1"]) || isset($_REQUEST["n2"]) || isset($_REQUEST["n3"]) || isset($_REQUEST["n4"]) || isset($_REQUEST["n5"]) ) {
       if ( !isset($_REQUEST["fecha_inicio"]) ){
            $dal = new dalIndicadores($ubi["search"]);
                $config["n1"]=$_REQUEST["n1"];
                $config["n2"]=$_REQUEST["n2"];
                $config["n3"]=$_REQUEST["n3"];
                $config["n4"]=$_REQUEST["n4"];
                $config["n5"]=$_REQUEST["n5"];

                if ($config["n1"]!= "" && $config["n1"]!= "N"){
                    $resultado["n1"] = $dal->ubi_n1($config);
                }

                if ($config["n2"]!= "" ){
                    $resultado["n2"] = $dal->ubi_n2($config);
                }

                if ($config["n3"]!= ""){
                    $resultado["n3"] = $dal->ubi_n3($config);
                }

                if ($config["n4"]!= ""){
                    $resultado["n4"] = $dal->ubi_n4($config);
                }

                if ($config["n5"]!= ""){
                    $resultado["n5"] = $dal->ubi_n5($config);
                }

            echo base64_encode(DOMv2::principal($resultado));
       }
   }   
    
    
   if (isset($_REQUEST["user"])) {
        clsCaus::validarLogin($_REQUEST["user"], $_REQUEST["pass"]);
        echo base64_encode(DOMv2::principal($_SESSION["user"]));
   }
   
   if (isset($_REQUEST["fecha_inicio"]))
   {
       if (isset($_REQUEST["n1"]) || isset($_REQUEST["n2"]) || isset($_REQUEST["n3"]) || isset($_REQUEST["n4"]) || isset($_REQUEST["n5"]) ) {
           
           $respuesta["search"]="";
           
           if ($_REQUEST["n5"] != "") {
               
               $respuesta["search"] = " AND id_un in (".$_REQUEST["n5"].")";
               
           } elseif ($_REQUEST["n4"]!=""){
               $search_uni ["sql"] = " `cat_unidad_notificadora` ";
               $search_uni ["sql"] .= " WHERE id_corregimiento = ".$_REQUEST["n4"];
               $resp = dalIndicadores::buscar_unidad_notificadora($search_uni);
           } elseif ($_REQUEST["n3"]!=""){
               $search_uni ["sql"] = " `cat_unidad_notificadora` notif LEFT JOIN `cat_distrito` dist ON notif.id_region = dist.id_region ";
               $search_uni ["sql"] .= " WHERE id_distrito = ".$_REQUEST["n3"];
               if ($_REQUEST["n2"] != "") {
                $search_uni ["sql"] .= " AND notif.id_region = ".$_REQUEST["n2"];
               }
               $resp = dalIndicadores::buscar_unidad_notificadora($search_uni);
           } elseif ($_REQUEST["n2"]!=""){
               $search_uni ["sql"] = " `cat_unidad_notificadora` ";
               $search_uni ["sql"] .= " WHERE id_region = ".$_REQUEST["n2"];
               $resp = dalIndicadores::buscar_unidad_notificadora($search_uni);
           }
           else {

               if ($_REQUEST["n1"] != "N") {

                    $search_uni ["sql"] = " `cat_unidad_notificadora` notif LEFT JOIN `cat_distrito` dist ON notif.id_region = dist.id_region ";
                    $search_uni ["sql"] .= " WHERE id_provincia = ".$_REQUEST["n1"];
                    $resp = dalIndicadores::buscar_unidad_notificadora($search_uni);
               } else
               {
                   $resp = "N";
               }
               
           }
           
           if ($respuesta["search"] == "" && $resp != "N") {
                if (is_array($resp)) {
                    $tempo = "" ;
                    $lastItem = end( $resp );
                    foreach ($resp as $elemento) {
                        $comas = $elemento != $lastItem ? " , " : "";
                        $tempo .= $elemento.$comas;
                    }
                }
                
                $respuesta["search"] = " AND id_un in (".$tempo.")  ";
           }
        
       }
       
       $dal = new dalIndicadores($respuesta["search"]);
       $config ["fecha_inicio"] = $_REQUEST["fecha_inicio"];
       $config ["fecha_fin"] = $_REQUEST["fecha_fin"];
       
       $resultado["sr_registrados"] = $dal->SR_registrado($config);
       $resultado["sr_examinados"] = $dal->SR_examinado($config);
       $resultado["positivo_basiloscopia"] = $dal->positivo_basiloscopia($config);
       $resultado["positivo_cultivo"] = $dal->positivo_cultivo($config);
       $resultado["positivo_p_r"] = $dal->positivo_p_r($config);
       $resultado["total_positivos"] = $dal->total_positivo($config);
       $resultado["diag_b3"] = $dal->diag_b_3($config);
       $resultado["curados_n"] = $dal->Curados_n($config);
       $resultado["trat_terminado_n"] = $dal->Trat_terminado_n($config);
       $resultado["perd_seguimiento_n"] = $dal->Perd_seguimiento_n($config);
       $resultado["fracaso_n"] = $dal->Fracaso_n($config);
       $resultado["fallecidos_n"] = $dal->Fallecidos_n($config);
       $resultado["no_evaluados_n_l"] = $dal->No_evaluados_n_locales($config);
       $resultado["no_evaluados_n_ext"] = $dal->No_evaluados_n_extranjeros($config);
       $resultado["exito_n"] = $dal->exito_n($config);
       $resultado["curados_at"] = $dal->Curados_at($config);
       $resultado["trat_terminado_at"] = $dal->Trat_terminado_at($config);
       $resultado["perd_seguimiento_at"] = $dal->Perd_seguimiento_at($config);
       $resultado["fracaso_at"] = $dal->Fracaso_at($config);
       $resultado["fallecidos_at"] = $dal->Fallecidos_at($config);
       $resultado["no_evaluados_at_l"] = $dal->No_evaluados_at_locales($config);
       $resultado["no_evaluados_at_ext"] = $dal->No_evaluados_at_extranjeros($config);
       $resultado["exito_at"] = $dal->exito_at($config);
       $resultado["pruebas_VIH"] = $dal->VIH_pruebas($config);
       $resultado["positivo_VIH"] = $dal->VIH_positivo($config);
       $resultado["TARV_VIH"] = $dal->VIH_TARV($config);
       $resultado["cotrimoxazol_VIH"] = $dal->VIH_cotrimaxazol($config);
       $resultado["MDR_trab_salud"] = $dal->MDR_trab_salud($config);
       $resultado["MDR_VIH"] = $dal->MDR_VIH($config);
       $resultado["MDR_CDR"] = $dal->MDR_CDR($config);
       $resultado["MDR_PPL"] = $dal->MDR_PPL($config);
       $resultado["MDR_Recaidas"] = $dal->MDR_Recaidas($config);
       $resultado["MDR_Reingresos"] = $dal->MDR_Reingresos($config);
       $resultado["MDR_Otros"] = $dal->MDR_Otros($config);

       
       $resultado["total_cohorte"] = $dal->Total_Cohorte($config);
       $resultado["total_cohorte_quarter"] = $dal->Total_Cohorte_Quarter($config);
       
       echo base64_encode(DOMv2::principal($resultado));
       
   }
//    $templateData =  $_POST['data'];
 

//clsCaus::cerrarSesion();

