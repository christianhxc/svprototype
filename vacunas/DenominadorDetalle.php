<?php
    require_once('libs/dal/vacunas/dalVacunas.php');
    require_once('libs/Connection.php');
    
    if (isset ($_POST["idForm"])){
        //print_r($_POST);exit;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $ok = true;
        //if (isset($_POST["eliminar"])){
        try {
            $sql = "DELETE from vac_denominador_detalle where id_vac_denominador = ".$_POST["idForm"];
            dalVacunas::ejecutarQuery($conn, $sql);
        } catch (Exception $exc) { }

        
        //}
        
        if (isset($_POST["grupos"]))
            $allGrupos = explode("###",$_POST["grupos"]); 
        for ($i=0;$i<count($allGrupos);$i++){
            if ($ok){
                if($allGrupos[$i]!=0){
                    $grupo = explode("#-#",$allGrupos[$i]);
                    $sql = "INSERT INTO vac_denominador_detalle (id_vac_denominador, tipo_poblacion, id_grupo_rango, num_hombre, num_mujer ) ";
                    $sql .= " VALUES (".$_POST["idForm"].",1,".$grupo[0].",".$grupo[1].",".$grupo[2].");";
                    $param = dalVacunas::ejecutarQuery($conn, $sql);
                    $ok = $param['ok'];
                }
            }
        }
        
        $casHombre = explode("##",$_POST["casosHombre"]);
        $casMujer = explode("##",$_POST["casosMujer"]);
        $rango = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17);
        for ($i=0;$i<count($casHombre)-1;$i++){
            if ($ok){
                if($casHombre[$i]!=0 || $casMujer[$i]!=0){
                    $sql = "INSERT INTO vac_denominador_detalle (id_vac_denominador, tipo_poblacion, id_grupo_rango, num_hombre, num_mujer ) ";
                    $sql .= "VALUES (".$_POST["idForm"].",2,".$rango[$i].",".$casHombre[$i].",".$casMujer[$i].")";
                    $param = dalVacunas::ejecutarQuery($conn, $sql);
                    $ok = $param['ok'];
                }
            }
        }
        
        if (isset($_POST["eliminar"]))        
            dalVacunas::GuardarBitacora($conn, "2", "vac_denominador_detalle"); 
        else
            dalVacunas::GuardarBitacora($conn, "1", "vac_denominador_detalle"); 
        
        if ($ok)
            $conn->commit();
        else {
            $conn->rollback();
            $idEsqDetalle = -1;
        }
        $conn->closeConn();
        //echo $idEsqDetalle;
    }
?>