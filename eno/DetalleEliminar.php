<?php
    require_once('libs/dal/eno/dalEno.php');
    require_once('libs/Connection.php');
    
    $respuesta = "";//Incluimos el fichero de la clase Db
    $ok = true;
    //var valores = "enoId="+encId+"&eveId="+eveId+"&sexo="+sexo;
    if (isset ($_POST["enoId"])){
        $id = $_POST["enoId"];
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        $sql = "DELETE from eno_detalle where id_enc = ".$_POST["enoId"]." and id_evento = ".$_POST["eveId"];
        //Como en esta pagina tenemos dos list box ejecutamos la sentencia dos veces
        $param = dalEno::ejecutarQuery($conn, $sql);
        $ok = $param['ok'];

        // Agregar el registro de la accion en la bitacora
        dalEno::GuardarBitacora($conn, "3", "eno_detalle");  
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