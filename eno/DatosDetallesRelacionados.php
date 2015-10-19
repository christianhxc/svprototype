<?php
    require_once('libs/dal/eno/dalEno.php');
    require_once('libs/Connection.php');
    
    if (IsSET ($_POST["enoId"])){
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        
        $sql = "SELECT ed.id_evento, cie_10_1, nombre_evento, sexo, nombre_rango as rango, numero_casos, cr.id_rango ";
	$sql.= "FROM eno_detalle ed inner join cat_rango cr on cr.id_rango = ed.id_rango, cat_evento ce where ed.id_evento = ce.id_evento ";
        $sql.= "and id_enc = ".$_POST["enoId"]." and ed.id_evento = ".$_POST["eveId"]." order by sexo,  ed.id_rango";
        //echo $sql;
        //Ejecutamos la query
        $data = dalEno::selectQuery($conn, $sql);
        //Preguntamos si hay algun error, si es asi lo muestra en la pantalla
        //$num_rows =  mysql_num_rows($resultado2);
        //Si la consulta nos retorna algun dato es porque el usuario ya existe, se lo mostramos al usuario como un error
        $cont = 0;
        if(count($data)>0){
            $array = array();
            $sexo = "";
            //$resultados2 = mysql_fetch_array($resultado2);
            if (is_array($data)) {
                $resultados2 = $data[0];
                while($resultados2 != null){
                    $numHombre = 0;
                    $numMujer = 0;
                    if ($cont==0)
                        echo $resultados2["cie_10_1"]." - ".$resultados2["nombre_evento"]." -#- ".$_POST["eveId"]."%%%";
                    $cont++;
                    echo "F%%%";
                    for ($i=1;$i<=12;$i++){
                        //echo $i." = ".$resultados2["id_rango"]." - ".$resultados2["sexo"]." - evento:".$evento." = ".$resultados2["id_evento"]."<br/>";
                        if($i==$resultados2["id_rango"]&&$resultados2["sexo"]=='F'){
                            echo $resultados2["numero_casos"]."###";
                            $numMujer+=$resultados2["numero_casos"];
                            $resultados2 = next($data);
                        }
                        else
                            echo "0###";
                    }
                    echo "$numMujer###%%%";

                    if($resultados2!=null){
                        echo "M%%%";
                        for ($i=1;$i<=12;$i++){
                            //echo $i." = ".$resultados2["id_rango"]." - ".$resultados2["sexo"]." - evento:".$evento." = ".$resultados2["id_evento"]."<br/>";
                            if($i==$resultados2["id_rango"]&&$resultados2["sexo"]=='M'){
                                echo $resultados2["numero_casos"]."###";
                                $numHombre+=$resultados2["numero_casos"];
                                $resultados2 = next($data);
                            }
                            else
                                echo "0###";
                        }
                    }
                    else{
                        echo "M%%%";
                        for ($i=1;$i<=12;$i++)
                            echo "0###";
                    }
                    echo "$numHombre###%%%";    
                }
            }
        }
    }
?>