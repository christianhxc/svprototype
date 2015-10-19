<?php
    set_time_limit(0);
    require_once("libs/dal/vih/dalVih.php");
    require_once('libs/Configuration.php');
    require_once('libs/Connection.php');
    require_once("libs/helper/helperVih.php");
    
    $servicio = "Archivo externo EpiInfo";
    //// TODO: Tomar el servicio al que pertenece el usuario
    $path = Configuration::bdEpiInfoPath.$servicio."-".date("dmYhis")." ";
    $path .= $_FILES['flArchivo']['name'];
    $partes = explode(".", $path);
    
    $extension = strtolower($partes[count($partes) - 1]);
    if ($extension != "csv"){
        echo "La extension del archivo no es valida, por favor verifique el archivo e intente de nuevo";
        exit;
    }
    if ($extension == "csv"){
        if(move_uploaded_file($_FILES['flArchivo']['tmp_name'], $path)) {
            $handle = @fopen($path, "r");
            $fp = fopen ( $path , "r" );
            $fila =0;
            $personasProcesadas = 0;
            $formulariosProcesados = 0;
            $factoresProcesados = 0;
            $conn = new Connection();
            $conn->initConn();
            $conn->begin();
            while (( $data = fgetcsv ( $fp , 1000 , "," )) !== FALSE ) { // Mientras hay líneas que leer...
                
                $i = 0;
                $registros = array();
                foreach($data as $row) {
                    $registros[$i] = $row;
                    $i++ ;
                }
                
                if($fila>0){
                    if (count($registros)==53){
                        $tipoId = (substr_count($registros[1], "-")==2 ? 1:2);
                        $vih = array();
                        $vih['id_tipo_identidad']= $tipoId;
                        $vih['numero_identificacion'] = $registros[1];
                        $existeVih = helperVih::yaExisteVih($vih);
                        if($existeVih==0){
                            $formulariosProcesados++;
                            $individuo = array();
                            $individuo['id_tipo_identidad']= $tipoId;
                            $individuo['numero_identificacion'] = $registros[1];
                            $persona = helperVih::dataTblPersonaEpiInfo($individuo,$registros);
                            $existePersona = helperVih::yaExistePersona($individuo);
                            if($existePersona==0){
                                $personasProcesadas++;
                                $param = dalVih::GuardarTabla($conn, "tbl_persona", $persona);
                                $ok = $param['ok'];
                                if($ok)
                                    $conn->commit();
                                else {
                                    $conn->rollback();
                                    $personasProcesadas--;
                                }
                            }
                            $formVih = helperVih::dataVihTblEpiInfo($vih,$persona,$registros);
//                            echo "<br/><br/>Form VIH:";
//                            print_r($formVih);
                            $param = dalVih::GuardarTabla($conn, "vih_form", $formVih);
                            $ok = $param['ok'];
                            if($ok){
                                $conn->commit();
                                $idVihForm = $param['id'];
                                $sql = helperVih::insertVihFactorRiesgoEpiInfo($idVihForm, $registros);
                                //echo "<br/>SQL:".$sql;
                                if ($sql!=""){
                                    $param = dalVih::ejecutarQuery($conn, $sql);
                                    $ok = $param['ok'];
                                    $factoresProcesados++;
                                    if ($ok)
                                        $conn->commit();
                                    else {
                                        $conn->rollback();
                                        $factoresProcesados--;
                                    } 
                                }
                            }
                            else {
                                $conn->rollback();
                                $formulariosProcesados--;
                            }
                        }
                    }
                    else
                        echo "<br/>   -> El CSV en el registro ".$fila." tiene un error en el n&uacute;mero de variables, estas tienen que ser 53, ni mas, ni menos<br/>";
                }
                $fila++;
            }

            fclose ( $fp );
            $mensaje = "<br/>   - Se encontr&oacute; un total de $fila registros que provenian de la BD de EpiInfo";
            $mensaje.= "<br/>   - De ese total se ingresaron un total de $personasProcesadas personas nuevas, algunas otras ya estaban en la base de datos de SISVIG";
            $mensaje.= "<br/>   - De ese total se ingresaron un total de $formulariosProcesados formularios de VIH/SIDA nuevos";
            $mensaje.= "<br/>   - Tambi&eacute;n se ingresaron un total de $factoresProcesados factores de riesgo";
            echo $mensaje;
            //echo "serId: ".$serId;exit;
//            $mensaje = terminarCarga();
//            header('Content-type: text/plain');
//            header('Content-Disposition: attachment; filename="logcargar'.date("dmYHis").'.txt"');
//            $mensaje = str_replace("\r\n", "<br/>", $mensaje);
//            echo $mensaje;
//            $sql = "TRUNCATE TABLE eno_transicion";
//            $bdSisvig->ejecutarSQL($sql);
//            echo "<br/>".$bdSisvig->getError()."<br/>";
        }

        else{
            echo '        <style type="text/css">        <!--        body {font: normal small "Trebuchet MS", Arial, Helvetica, sans-serif; color: #666666; font-size:0.9em;}        -->        </style>';
            echo "El archivo no se pudo cargar, por favor intentelo de nuevo mas tarde.";
        }
    }

        
    function formatoCedula($cedula){
        $patron = "/^\-{2}+$/";
        if (preg_match($patron, $cedula))
            return true;
        else
            return false;
    }
  
?>