<?php
    require_once('libs/dal/vacunas/dalVacunas.php');
    require_once('libs/Connection.php');
    
    if (isset ($_POST["idEsqDetalle"])&&isset ($_POST["vacId"])){
        //echo "hola";exit;
        $allDosis = explode("###",$_POST["dosisRelacionadas"]); 
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $ok = true;
        $param = array();
        $sql2 = "DELETE from vac_dosis where id_esq_detalle = ".$_POST["idEsqDetalle"]." and id_vacuna = ".$_POST["vacId"];
        $sql2 .= " and id_dosis not in (select vac_registro_diario_dosis.id_dosis from vac_registro_diario_dosis)";
        
        $param = dalVacunas::ejecutarQuery($conn, $sql2);
        $ok = $param['ok'];
        $idEsqDetalle = $_POST["idEsqDetalle"];
        for ($i=0;$i<count($allDosis);$i++){
            if ($ok){
                if($allDosis[$i]!=0){
                    $dosis = explode(",",$allDosis[$i]);
                    $dosis[5] = ($dosis[5] == "" ) ? "null" : $dosis[5];
                    $dosis[6] = ($dosis[6] == "" ) ? "null" : $dosis[6];
                    $dosis[11] = ($dosis[11] == "" ) ? "null" : $dosis[11];
                    $dosis[12] = ($dosis[12] == "" ) ? "null" : $dosis[12];
                    $sql = "INSERT INTO vac_dosis (id_esq_detalle, id_vacuna,tipo_dosis, edad_vac_ini, tipo_edad_vac_ini,";
                    $sql .= " edad_vac_fin, tipo_edad_vac_fin, margen_vac_ini, tipo_margen_vac_ini, margen_vac_fin, ";
                    $sql .= " tipo_margen_vac_fin, num_dosis_refuerzo, repite_annio ) ";
                    $sql .= " VALUES (".$idEsqDetalle.",".$_POST["vacId"].",".$dosis[0].",".$dosis[2].",".$dosis[3].",".$dosis[5]
                            .",".$dosis[6].",".$dosis[8].",".$dosis[9].",".$dosis[11].",".$dosis[12].",'".$dosis[14]."',".$dosis[15].");";
                    
                    $param = dalVacunas::ejecutarQuery($conn, $sql);
                    $ok = $param['ok'];
                }
            }
        }
        dalVacunas::GuardarBitacora($conn, "2", "vac_esq_detalle");  
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