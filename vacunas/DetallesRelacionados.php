<?php
    //Incluimos los ficheros necesarios
    require_once('libs/dal/vacunas/dalVacunas.php');
    require_once('libs/Connection.php');
    require_once('libs/caus/clsCaus.php');

    if (IsSET ($_POST["idVacunaForm"])){
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        
        $sql = "select t1.id_esq_detalle, t2.id_vacuna, t2.nombre_vacuna, t3.total_dosis
                from vac_esq_detalle t1
                inner join cat_vacuna t2 on t1.id_vacuna = t2.id_vacuna
                left join 
                (select count(id_dosis) as total_dosis, id_esq_detalle, id_vacuna
                from vac_dosis
                group by id_esq_detalle, id_vacuna
                ) t3 on t1.id_esq_detalle = t3.id_esq_detalle
                    and t1.id_vacuna = t3.id_vacuna 
                where id_esquema = ".$_POST["idVacunaForm"]." order by t1.id_esq_detalle";
        //echo $sql;exit;
        //Ejecutamos la query
        $data = dalVacunas::selectQuery($conn, $sql);
        //print_r($data);exit;
        //Si la consulta nos retorna algun dato es porque el usuario ya existe, se lo mostramos al usuario como un error
        if(count($data)>0){
            $array = array();
            $eveUd = "";
            $sexo = "";
            echo '<br/><table cellspacing="1" cellpadding="4" border="0" width="90%" align="center">';
            echo "<tr >";
            echo "<th rowspan='2' width='150px' class='dxgvHeader_PlasticBlue' style='text-align:center'>Vacuna</th>";
            echo "<th colspan='10' class='dxgvHeader_PlasticBlue' style='text-align:center'>Dosis</th>";
            echo "<th rowspan='2' class='dxgvHeader_PlasticBlue' style='text-align:center'>Repite</th>";
            echo "<th rowspan='2' class='dxgvHeader_PlasticBlue' style='text-align:center'>Acciones</th></tr>";
            echo "<tr >";
            echo "<th class='dxgvHeader_PlasticBlue'>N.</th><th class='dxgvHeader_PlasticBlue'>Tipo Dosis</th>
                  <th colspan='2' class='dxgvHeader_PlasticBlue'>Edad inicial a vacunar</th>
                  <th colspan='2' class='dxgvHeader_PlasticBlue'>Edad final</th>
                  <th colspan='2' class='dxgvHeader_PlasticBlue'>Margen inferior vac.</th>
                  <th colspan='2' class='dxgvHeader_PlasticBlue'>Margen superior</th>";
            echo "</tr>";
            $cont=0;
            $total=0;
            $flag = false;
            $flagAccion = false;
            $flagAccion = false;
            //$resultados2 = mysql_fetch_array($resultado2);
            
            if (is_array($data)) {
                $resultados = $data[0];
                while($resultados != null){
                    $sql = "select t2.id_vacuna, t2.nombre_vacuna, t3.id_dosis, t3.id_esq_detalle, 
                            case when t3.tipo_dosis = 1 then 'Dosis' else 'Refuerzo' end as tipo_dosis, t3.edad_vac_ini, 
                            case when t3.tipo_edad_vac_ini = 1 then 'Horas' when t3.tipo_edad_vac_ini = 2 then 'Dias' when 
                            t3.tipo_edad_vac_ini = 3 then 'Semanas' when t3.tipo_edad_vac_ini = 4 then 'Meses' when
                            t3.tipo_edad_vac_ini = 5 then 'Años' else '' end as tipo_edad_vac_ini, 
                            case when t3.edad_vac_fin is null then '' else t3.edad_vac_fin end as edad_vac_fin,
                            case when t3.tipo_edad_vac_fin = 1 then 'Horas' when t3.tipo_edad_vac_fin = 2 then 'Dias' when 
                            t3.tipo_edad_vac_fin = 3 then 'Semanas' when t3.tipo_edad_vac_fin = 4 then 'Meses' when
                            t3.tipo_edad_vac_fin = 5 then 'Años' else '' end as tipo_edad_vac_fin,
                            t3.margen_vac_ini, 
                            case when t3.tipo_margen_vac_ini = 1 then 'Dias' when 
                            t3.tipo_margen_vac_ini = 2 then 'Semanas' when t3.tipo_margen_vac_ini = 3 then 'Meses' when
                            t3.tipo_margen_vac_ini = 4 then 'Años' else '' end as tipo_margen_vac_ini, 
                            case when t3.margen_vac_fin is null then '' else t3.margen_vac_fin end as margen_vac_fin, 
                            case when t3.tipo_margen_vac_fin = 1 then 'Dias' when 
                            t3.tipo_margen_vac_fin = 2 then 'Semanas' when t3.tipo_margen_vac_fin = 3 then 'Meses' when
                            t3.tipo_margen_vac_fin = 4 then 'Años' else '' end as tipo_margen_vac_fin,
                            t3.num_dosis_refuerzo,
                            case when t3.repite_annio = 1 then 'Si' else 'No' end as repite_annio
                            from vac_esq_detalle t1
                            inner join cat_vacuna t2 on t1.id_vacuna = t2.id_vacuna
                            left join vac_dosis t3 on t1.id_esq_detalle = t3.id_esq_detalle
                            where t1.id_esquema = ".$_POST["idVacunaForm"]." and t2.id_vacuna = ".$resultados["id_vacuna"]."
                            order by t2.id_vacuna, t3.tipo_dosis, t3.num_dosis_refuerzo";
                    
                    $data1 = dalVacunas::selectQuery($conn, $sql);
                    //print_r($data);exit;
                    //Si la consulta nos retorna algun dato es porque el usuario ya existe, se lo mostramos al usuario como un error
                    if(count($data1)>0){
                        if (is_array($data1)) {
                            $resultados2 = $data1[0];
                            $contaDosis = 1;
                            echo "<tr class='fila'>";
                            echo "<td rowspan='".$resultados["total_dosis"]."'>".$resultados2["nombre_vacuna"]."</td>";
                            while($resultados2 != null){
                                if ($contaDosis>1)
                                    echo "<tr class='fila'>";
                                echo "<td >".$resultados2["num_dosis_refuerzo"]."</td>";
                                echo "<td >".$resultados2["tipo_dosis"]."</td>";
                                echo "<td >".$resultados2["edad_vac_ini"]."</td>";
                                echo "<td >".$resultados2["tipo_edad_vac_ini"]."</td>";
                                echo "<td >".$resultados2["edad_vac_fin"]."</td>";
                                echo "<td >".$resultados2["tipo_edad_vac_fin"]."</td>";
                                echo "<td >".$resultados2["margen_vac_ini"]."</td>";
                                echo "<td >".$resultados2["tipo_margen_vac_ini"]."</td>";
                                echo "<td >".$resultados2["margen_vac_fin"]."</td>";
                                echo "<td >".$resultados2["tipo_margen_vac_fin"]."</td>";
                                echo "<td >".$resultados2["repite_annio"]."</td>";
                                if ($contaDosis==1){
                                    echo '<td width="8%" align="center" rowspan="'.$resultados["total_dosis"].'">';
                                    if (clsCaus::validarSeccion(ConfigurationCAUS::VacFormulario, ConfigurationCAUS::Modificar))
                                        echo '<a href="javascript:actualizarVacuna('.$resultados["id_esq_detalle"].','.$resultados["id_vacuna"].')"><img src="../img/edit.png" width="16" height="16" title="Editar" /></a>';
                                    if (clsCaus::validarSeccion(ConfigurationCAUS::VacFormulario, ConfigurationCAUS::Borrar))
                                        echo '&nbsp&nbsp<a href="javascript:eliminarVacuna('.$resultados["id_esq_detalle"].','.$resultados["id_vacuna"].')"><img src="../img/Delete.png" width="16" height="16" title="Eliminar" /></a>';
                                    echo "</td>";
                                }
                                echo "</tr>";
                                $contaDosis++;
                                $resultados2 = next($data1);
                            }
                        }
                    }
                    $resultados = next($data);
                }
            }
            echo "</table>";
        }
    }
?>
