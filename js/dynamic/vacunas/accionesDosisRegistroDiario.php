<?php

require_once("libs/helper/helperVacunas.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");


if (isset($_REQUEST["mode"])){
    $mode = $_REQUEST["mode"];
    
    if ($mode == "delete") {
        $id_dosis_delete = 0 ;
        $id_dosis_delete =   $_REQUEST["id_dosis"];
        
        if ($id_dosis_delete > 0){
            $error = helperVacunas::borrarRegistroDiarioDosis($id_dosis_delete);
            echo ($error == "") ? 'Imposible borrar el registro, por favor intente nuevamente.' : 'La dosis fue borrada correctamente.';
        }

    }else
    {
        if(isset($_REQUEST["id_dosis"])&&isset($_REQUEST["id_form_registro"])&&isset($_REQUEST["fecha_reporte"])){
            // Guardar nuevo
            $dosis = array();
            $dosis["id_form_registro"] = $_REQUEST["id_form_registro"];
            $dosis["id_dosis"] = $_REQUEST["id_dosis"];
            $dosis["fecha_dosis"] = $_REQUEST["fecha_reporte"];
            $dosis["numero_lote_1"] = $_REQUEST["lote1"];
            $dosis["numero_lote_2"] = $_REQUEST["lote2"];
            $dosis["numero_lote_3"] = $_REQUEST["lote3"];
            $dosis["id_un"] = $_REQUEST["id_un"];
            $dosis["nombre_reporta"] = $_REQUEST["nombre_funcionario"];
            $dosis["nombre_registra"] = $_REQUEST["nombre_registra"];
            $dosis["id_modalidad"] = $_REQUEST["id_modalidad"];
            $dosis["nombre_modalidad_otro"] = $_REQUEST["otro_modalidad"];
            $dosis["id_zona"] = $_REQUEST["id_zona"];
            $dosis["per_edad"] = $_REQUEST["edad"];
            $dosis["per_tipo_edad"] = $_REQUEST["tipo_edad"];
            $meses = 0;
            if ($dosis["per_tipo_edad"] == "3") {
                $meses =  $dosis["per_edad"] * 12;
            } else {
                if ($dosis["per_tipo_edad"] == "2") {
                $meses =  $dosis["per_edad"];  
                } else
                {
                    $meses = 0;
                }
            }

            $rango = helperVacunas::obtenerRangoEdad($meses);

            $dosis["id_rango"] = $rango["id_rango"];
            $dosis["id_esquema"] = $_REQUEST["esquema"];

    //        print_r($dosis);
    //        exit;

            if ($mode == "save_new") {
                //$res = dalVacunas::GuardarTabla($conn, "vac_registro_diario_dosis", $dosis);
                $error = helperVacunas::agregarRegistroDiarioDosis($dosis);
                echo ($error == "") ? 'Imposible guardar, por favor intente nuevamente.' : 'Se guardo la dosis correctamente.';
            }

            // Guardar edición
            else if ($mode == "update_data") {
                $error = helperVacunas::editarRegistroDiarioDosis($dosis);
                echo ($error == "") ? 'Imposible editar, por favor intente nuevamente.' : 'Se actualizo la dosis correctamente.';
            }

        }
    }

}

//<a href="formulario.php?action=D&idUceti=' . $data["id_flureg"] . '" class="" title="Borrar">'