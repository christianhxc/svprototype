<?php
    require_once('libs/dal/vacunas/dalVacunas.php');
    require_once('libs/Connection.php');
    
    if (IsSET ($_POST["idVacunaForm"])&&IsSET ($_POST["vacId"])){
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        
        $sql = "select id_vacuna, tipo_dosis, case when tipo_dosis = 1 then 'Dosis' else 'Refuerzo' end as nombre_tipo_dosis,
                edad_vac_ini, tipo_edad_vac_ini, 
                case when tipo_edad_vac_ini = 1 then 'Horas' when tipo_edad_vac_ini = 2 then 'Dias' when 
                tipo_edad_vac_ini = 3 then 'Semanas' when tipo_edad_vac_ini = 4 then 'Meses' when
                tipo_edad_vac_ini = 5 then 'Años' else '' end as nombre_tipo_edad_ini, 
                case when edad_vac_fin is null then '' else edad_vac_fin end as edad_vac_fin, 
                case when tipo_edad_vac_fin is null then 0 else tipo_edad_vac_fin end as tipo_edad_vac_fin,
                case when tipo_edad_vac_fin = 1 then 'Horas' when tipo_edad_vac_fin = 2 then 'Dias' when 
                tipo_edad_vac_fin = 3 then 'Semanas' when tipo_edad_vac_fin = 4 then 'Meses' when
                tipo_edad_vac_fin = 5 then 'Años' else '' end as nombre_tipo_edad_fin,
                margen_vac_ini, tipo_margen_vac_ini, 
                case when tipo_margen_vac_ini = 1 then 'Dias' when 
                tipo_margen_vac_ini = 2 then 'Semanas' when tipo_margen_vac_ini = 3 then 'Meses' when
                tipo_margen_vac_ini = 4 then 'Años' else '' end as nombre_tipo_margen_ini,
                case when margen_vac_fin is null then '' else margen_vac_fin end as margen_vac_fin, 
                case when tipo_margen_vac_fin is null then 0 else tipo_margen_vac_fin end as tipo_margen_vac_fin,
                case when tipo_margen_vac_fin = 1 then 'Dias' when 
                tipo_margen_vac_fin = 2 then 'Semanas' when tipo_margen_vac_fin = 3 then 'Meses' when
                tipo_margen_vac_fin = 4 then 'Años' else '' end as nombre_tipo_margen_fin,
                num_dosis_refuerzo, case when repite_annio = 1 then 1 else 2 end as repite_annio,
                case when recordar_correo = 1 then 1 else 2 end as recordar_correo
                from vac_dosis  
                where id_esq_detalle = ".$_POST["idVacunaForm"]." AND id_vacuna = ".$_POST["vacId"].
                " and id_dosis not in (select id_dosis from vac_registro_diario_dosis)
                 order by vac_dosis.id_vacuna, vac_dosis.tipo_dosis, vac_dosis.num_dosis_refuerzo";
        //echo $sql;
        //Ejecutamos la query
        $data = dalVacunas::selectQuery($conn, $sql);
        //Preguntamos si hay algun error, si es asi lo muestra en la pantalla
        //$num_rows =  mysql_num_rows($resultado2);
        //Si la consulta nos retorna algun dato es porque el usuario ya existe, se lo mostramos al usuario como un error
        $cont = 0;
        if(count($data)>0){
            $array = array();
            $respuesta = "";
            //$resultados2 = mysql_fetch_array($resultado2);
            if (is_array($data)) {
                $resultados2 = $data[0];
                while($resultados2 != null){
                    if ($cont==0){
                        $respuesta = $resultados2["id_vacuna"]."%%%";
                        $respuesta .= $_POST["idVacunaForm"]."%%%";
                    }
                    $cont++;
                    $respuesta .= $resultados2["tipo_dosis"]."#-#";
                    $respuesta .= $resultados2["nombre_tipo_dosis"]."#-#";
                    $respuesta .= $resultados2["edad_vac_ini"]."#-#";
                    $respuesta .= $resultados2["tipo_edad_vac_ini"]."#-#";
                    $respuesta .= $resultados2["nombre_tipo_edad_ini"]."#-#";
                    $respuesta .= $resultados2["edad_vac_fin"]."#-#";
                    $respuesta .= $resultados2["tipo_edad_vac_fin"]."#-#";
                    $respuesta .= $resultados2["nombre_tipo_edad_fin"]."#-#";
                    $respuesta .= $resultados2["margen_vac_ini"]."#-#";
                    $respuesta .= $resultados2["tipo_margen_vac_ini"]."#-#";
                    $respuesta .= $resultados2["nombre_tipo_margen_ini"]."#-#";
                    $respuesta .= $resultados2["margen_vac_fin"]."#-#";
                    $respuesta .= $resultados2["tipo_margen_vac_fin"]."#-#";
                    $respuesta .= $resultados2["nombre_tipo_margen_fin"]."#-#";
                    $respuesta .= $resultados2["num_dosis_refuerzo"]."#-#";
                    $respuesta .= $resultados2["repite_annio"]."#-#";
                    $respuesta .= $resultados2["recordar_correo"]."%%%";
                    $resultados2 = next($data);
                    
                }
            }
            $respuesta = substr($respuesta, 0, strlen($respuesta) - 3);
            echo $respuesta;
        }
    }
?>