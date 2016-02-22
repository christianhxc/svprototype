<?php
    set_time_limit(0);
    require_once ('libs/caus/clsCaus.php');
    
    //Validar si se desea cerrar sesion
    if ($_REQUEST["logout"]) {
        clsCaus::cerrarSesion();
        file_get_contents(Configuration::reportAddress . "remotelogin.php?logout=true");
    }

    // Validar si ya ha iniciado sesion
    if (!clsCaus::validarSession()) {
        header("location: ../index.php");
    }
    
    require_once('libs/dal/vacunas/dalVacunas.php');
    require_once('libs/Connection.php');
    require_once('DenominadorExcel.php');
    
    
    $target_file = "archivos/".date("dmYhis");
    $target_file .= $_FILES['flArchivo']['name'];
    $extension = pathinfo($target_file,PATHINFO_EXTENSION);
    if ($extension != "csv" &&  $extension != "xls" &&  $extension != "xlsx"){
        header("location: denominadores.php?info=La extension del archivo no es valida, por favor verifique el archivo e intente de nuevo");
        exit;
        
    }   
    
    move_uploaded_file($_FILES["flArchivo"]["tmp_name"], $target_file);
    
    $data = convert_excel_array($target_file,$_POST["anio_deno"]);
    
    if (count($data)==0){
        header("location: denominadores.php?info=El formato del archivo no es correcto, por favor verifique la integridad de los datos");
        exit;
    }
//    exit;
    
    foreach ($data as $_row)
    {
        $ubicacion = dalVacunas::BuscarUbiDenoExcel($_row);
//         echo "r= ".$_row["region"]." -- d=".$_row["distrito"]."<br/>";

            $conn = new Connection();
            $conn->initConn();
            $conn->begin();
            $ok = true;
            if ($ubicacion[0]["id_vac_denominador"] != ""){
                try {
                    $sql = "DELETE from vac_denominador_detalle where id_vac_denominador = ".$ubicacion[0]["id_vac_denominador"];
                    dalVacunas::ejecutarQuery($conn, $sql);
                    $conn->commit();
                    $sql = "DELETE from `vac_denominador` where id_vac_denominador = ".$ubicacion[0]["id_vac_denominador"];
                    dalVacunas::ejecutarQuery($conn, $sql);
                    $conn->commit();
                    $sql = "INSERT INTO `vac_denominador` (`id_vac_denominador`,`nivel`, `id_region`, `id_provincia`, `id_distrito`, `id_corregimiento`, `anio`) ";
                    $sql = $sql . " VALUES(".$ubicacion[0]["id_vac_denominador"].",".$_row["nivel"].",".$ubicacion[0]["id_region"].",".$ubicacion[0]["id_provincia"].",".$ubicacion[0]["id_distrito"].",".$ubicacion[0]["id_corregimiento"].",'".$_row["anio"]."');";
                    dalVacunas::ejecutarQuery($conn, $sql);
                    $conn->commit();
                    
                } catch (Exception $exc) { }
            } else
            {
                try {
                    
                    if ($ubicacion[0]["id_region"] != "" && $ubicacion[0]["id_provincia"] != ""  && $ubicacion[0]["id_distrito"] != "" && $ubicacion[0]["id_corregimiento"] != "" ){
                    
                        $sql = "INSERT INTO `vac_denominador` (`nivel`, `id_region`, `id_provincia`, `id_distrito`, `id_corregimiento`, `anio`) ";
                        $sql = $sql . " VALUES(".$_row["nivel"].",".$ubicacion[0]["id_region"].",".$ubicacion[0]["id_provincia"].",".$ubicacion[0]["id_distrito"].",".$ubicacion[0]["id_corregimiento"].",'".$_row["anio"]."');";
                        $id_nuevo=dalVacunas::ejecutarQuery($conn, $sql);
                        $conn->commit();
                        $ubicacion[0]["id_vac_denominador"] = $id_nuevo["id"];
                        
                    } else{
                        $ubicacion[0]["id_vac_denominador"] = "";
                    }
                } catch (Exception $exc) { }
            }
          
    
        if ( $ubicacion[0]["id_vac_denominador"] != "")
            {   
                if (isset($_row["grupos"]))
                    $allGrupos = explode("###",$_row["grupos"]);               

                foreach ($allGrupos as $_row_gp => $_key_gp)
                {
                    $grupo = explode("#-#",$_key_gp);
                    $sql = "INSERT INTO vac_denominador_detalle (id_vac_denominador, tipo_poblacion, id_grupo_rango, num_hombre, num_mujer ) ";
                    $sql .= " VALUES (".$ubicacion[0]["id_vac_denominador"].",1,".$grupo[0].",".$grupo[1].",".$grupo[2].");";
                    dalVacunas::ejecutarQuery($conn, $sql);
                }
                $conn->commit();
                
                $casHombre = explode("##",$_row["casosHombre"]);
                $casMujer = explode("##",$_row["casosMujer"]);
                $rango = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17);
                for ($i=0;$i<=count($casHombre)-1;$i++){
                    if ($ok){
                        if($casHombre[$i]!=0 || $casMujer[$i]!=0){
                            $sql = "INSERT INTO vac_denominador_detalle (id_vac_denominador, tipo_poblacion, id_grupo_rango, num_hombre, num_mujer ) ";
                            $sql .= "VALUES (".$ubicacion[0]["id_vac_denominador"].",2,".$rango[$i].",".$casHombre[$i].",".$casMujer[$i].")";
                            dalVacunas::ejecutarQuery($conn, $sql);
    //                        echo "<br/>".$sql;

                            $ok = true;
                        }
                    }
                }

                $conn->commit();
                $conn->closeConn(); 
        } 
    }
    
    echo "Fin del proceso";
    $conn = new Connection();
    $conn->initConn();
    $conn->begin();
    dalVacunas::GuardarBitacora($conn, "3", "vac_denominador_detalle"); 
    $conn->commit();
    $conn->closeConn(); 
    header("location: denominadores.php?info=Proceso de inserción realizado exitosamente");
    
?>