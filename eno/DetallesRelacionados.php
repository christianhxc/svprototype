<?php
    //Incluimos los ficheros necesarios
    require_once('libs/dal/eno/dalEno.php');
    require_once('libs/Connection.php');
    require_once('libs/caus/clsCaus.php');

    if (IsSET ($_POST["enoId"])){
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        
        $sql = "SELECT ed.id_evento, nombre_evento, cie_10_1, sexo, nombre_rango as rango, numero_casos, cr.id_rango ";
	$sql.= "FROM eno_detalle ed inner join cat_rango cr on cr.id_rango = ed.id_rango, cat_evento ce where ed.id_evento = ce.id_evento ";
        $sql.= "and id_enc = ".$_POST["enoId"]." order by ed.id_evento, sexo DESC, ed.id_rango";
        //echo $sql;exit;
        //Ejecutamos la query
        $data = dalEno::selectQuery($conn, $sql);
        //print_r($data);exit;
        //Si la consulta nos retorna algun dato es porque el usuario ya existe, se lo mostramos al usuario como un error
        if(count($data)>0){
            $array = array();
            $eveUd = "";
            $sexo = "";
            echo "<table>";
            echo "<tr class='fila'>";
            echo "<td>cie 10</td><td>Evento</td><td>Sexo</td><td>&lt;de1</td><td>01-04</td><td>05-09</td><td>10-14</td><td>15-19</td><td>20-24</td><td>25-34</td><td>35-49</td>
                  <td>50-59</td><td>60-64</td><td>65o+</td><td>N/E</td><td>Total</td><td>Acciones</td>";
            echo "</tr>";
            $cont=0;
            $total=0;
            $flag = false;
            $flagAccion = false;
            $flagAccion = false;
            //$resultados2 = mysql_fetch_array($resultado2);
            
            if (is_array($data)) {
                $resultados2 = $data[0];
                while($resultados2 != null){
                    $numHombre = 0;
                    $numMujer = 0;
                    $evento = $resultados2["id_evento"];
                    $evento1 = $evento;
                    echo "<tr>";
                    echo "<td rowspan='2'>".$resultados2["cie_10_1"]."&nbsp&nbsp</td>";
                    echo "<td rowspan='2' width=\"35%\">".$resultados2["nombre_evento"]."</td>";
                    echo "<td>M</td>";
                    for ($i=1;$i<=12;$i++){
                        if($i==$resultados2["id_rango"]&&$resultados2["sexo"]=='M'&&$evento==$resultados2["id_evento"]&&$evento==$evento1){
                            $numHombre+=$resultados2["numero_casos"];
                            echo "<td>".$resultados2["numero_casos"]."</td>";
                            $resultados2 = next($data);
                            $evento = $resultados2["id_evento"];
                        }
                        else
                            echo "<td>0</td>";
                    }
                    echo "<td>$numHombre</td>";
                    echo '<td width="8%" align="center" rowspan="2">';
                    if (clsCaus::validarSeccion(ConfigurationCAUS::enoFormulario, ConfigurationCAUS::Modificar))
                        echo '<a href="javascript:actualizarDetalleEno('.$_POST["enoId"].','.$evento1.')"><img src="../img/edit.png" width="16" height="16" title="Editar" /></a>';
                    if (clsCaus::validarSeccion(ConfigurationCAUS::enoFormulario, ConfigurationCAUS::Borrar))
                        echo '&nbsp&nbsp<a href="javascript:eliminarDetalleENO('.$_POST["enoId"].','.$evento1.')"><img src="../img/Delete.png" width="16" height="16" title="Eliminar" /></a>';
                    echo "</td></tr><tr>";
                    echo "<td>F</td>";
                    if ($resultados2!=null&&$evento==$evento1){
                        for ($i=1;$i<=12;$i++){
                            if($i==$resultados2["id_rango"]&&$resultados2["sexo"]=='F'&&$evento==$resultados2["id_evento"]){
                                $numMujer+=$resultados2["numero_casos"];
                                echo "<td>".$resultados2["numero_casos"]."</td>";
                                $resultados2 = next($data);
                            }
                            else
                                echo "<td>0</td>";
                        }
                    }
                    else{
                        for ($i=1;$i<=12;$i++)
                            echo "<td>0</td>";
                    }
                    echo "<td>$numMujer</td>";
                    echo "</tr>";
                }
            }
            echo "</table>";
        }
    }

?>
