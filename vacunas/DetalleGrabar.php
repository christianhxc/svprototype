<?php
    require_once('libs/dal/vacunas/dalVacunas.php');
    require_once('libs/Connection.php');
    
    if (isset ($_POST["idEsquema"])&&isset ($_POST["vacId"])){
        $allDosis = explode("###",$_POST["dosisRelacionadas"]); 
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $ok = true;
        $param = array();
        
        $esquema["idEsquema"] = $_POST["idEsquema"];
        $esquema["idVacuna"] = $_POST["vacId"];
        
        $total = dalVacunas::ValidarEsquemas($esquema);
        
        //echo $total;
        
        if($total === 0){
            $sql = "INSERT INTO vac_esq_detalle (id_esquema, id_vacuna) ";
            $sql .= "VALUES (".$_POST["idEsquema"].",".$_POST["vacId"].");";        
            $param = dalVacunas::ejecutarQuery($conn, $sql);
            $ok = $param['ok'];
            $idEsqDetalle = $param['id'];
        }
        else {
            $sql = "select id_esq_detalle from vac_esq_detalle 
                    where id_esquema = ".$esquema["idEsquema"]." and id_vacuna = ".$esquema["idVacuna"];
            $data = dalVacunas::selectQuery($conn, $sql);
            $idEsqDetalle = $data[0];
        }
        
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
                    $sql .= " tipo_margen_vac_fin, num_dosis_refuerzo, repite_annio, recordar_correo ) ";
                    $sql .= " VALUES (".$idEsqDetalle.",".$_POST["vacId"].",".$dosis[0].",".$dosis[2].",".$dosis[3].",".$dosis[5]
                            .",".$dosis[6].",".$dosis[8].",".$dosis[9].",".$dosis[11].",".$dosis[12].",'".$dosis[14]."',".$dosis[15].",".$dosis[16].");";
                    //echo $sql;exit;
                    $param = dalVacunas::ejecutarQuery($conn, $sql);
                    $ok = $param['ok'];
                    
                }
            }
        }
        dalVacunas::GuardarBitacora($conn, "1", "vac_esq_detalle");  
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