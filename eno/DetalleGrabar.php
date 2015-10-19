<?php
    require_once('libs/dal/eno/dalEno.php');
    require_once('libs/Connection.php');
    
    if (isset ($_POST["enoId"])){
        $casHombre = explode("##",$_POST["casHombre"]);
        $casMujer = explode("##",$_POST["casMujer"]);
        $rango = array(1,2,3,4,5,6,7,8,9,10,11,12);
        
        $id = $_POST["enoId"];
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $ok = true;
        $param = array();
        for ($i=0;$i<count($casHombre);$i++){
            if ($ok){
                if($casHombre[$i]!=0){
                    $sql = "INSERT INTO eno_detalle (id_enc,id_evento, sexo, id_rango, numero_casos) ";
                    $sql .= "VALUES (".$_POST["enoId"].",".$_POST["eveId"].",'M',".$rango[$i].",".$casHombre[$i].")";
                    //echo $sql;
                    $param = dalEno::ejecutarQuery($conn, $sql);
                    $ok = $param['ok'];
                }
            }
        }
        for ($i=0;$i<count($casMujer);$i++){
            if ($ok){
                if($casMujer[$i]!=0){
                    $sql = "INSERT INTO eno_detalle (id_enc,id_evento, sexo, id_rango, numero_casos) ";
                    $sql .= "VALUES (".$_POST["enoId"].",".$_POST["eveId"].",'F',".$rango[$i].",".$casMujer[$i].")";
                    //echo $sql;
                    //Como en esta pagina tenemos dos list box ejecutamos la sentencia dos veces
                    $param = dalEno::ejecutarQuery($conn, $sql);
                    $ok = $param['ok'];
                }
            }
        }

        dalEno::GuardarBitacora($conn, "1", "eno_detalle");  
        if ($ok)
            $conn->commit();
        else {
            $conn->rollback();
            $id = -1;
        }
        $conn->closeConn();
        echo $id;
    }
?>