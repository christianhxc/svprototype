<?php
    require_once('libs/dal/vacunas/dalVacunas.php');
    require_once('libs/Connection.php');
    
    $respuesta = "";//Incluimos el fichero de la clase Db
    $ok = true;
    if (isset ($_POST["esquemaId"])){
        $id = $_POST["esquemaId"];
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $sql = "DELETE from vac_dosis where id_esq_detalle = ".$_POST["esquemaId"]." and id_vacuna = ".$_POST["vacId"];
        //Como en esta pagina tenemos dos list box ejecutamos la sentencia dos veces
        $param = dalVacunas::ejecutarQuery($conn, $sql);
        $sql = "DELETE from vac_esq_detalle where id_esq_detalle = ".$_POST["esquemaId"];
        $param = dalVacunas::ejecutarQuery($conn, $sql);
        $ok = $param['ok'];
        
        // Agregar el registro de la accion en la bitacora
        dalVacunas::GuardarBitacora($conn, "3", "vac_esq_detalle");  
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