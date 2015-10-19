<?php
    require_once("libs/Configuration.php");
    require_once('libs/Connection.php');
    require_once("libs/dal/dalMat.php");

    if (isset($_POST["gruId"])){
        $mensaje = "";
        $contactos = explode(",",$_POST["idSeleccionados"]);
        
        for ($i=0;$i<count($contactos);$i++){
            $conn = new Connection();
            $conn->initConn();
            $conn->begin();
            if ($contactos[$i]!=""&&$contactos[$i]!=0){
                $filtro = array();
                $filtro["id_contacto"]= $i;
                $filtro["id_grupo_contacto"]= $_POST["gruId"];
                $param = array();
                if ($contactos[$i]==2){
                    $param = dalMat::BorrarTabla($conn, "mat_contacto_grupo_contacto", $filtro);
                    $ok = $param['ok'];
                    if ($ok){
                        $param = dalMat::GuardarBitacora($conn, "3", "mat_contacto_grupo_contacto");
                        $ok = $param['ok'];
                    }
                }
                else{
                    if(dalMat::existeRelacion($filtro["id_grupo_contacto"], $filtro["id_contacto"])==0){
                        $param = dalMat::GuardarTabla($conn, "mat_contacto_grupo_contacto", $filtro);
                        $ok = $param['ok'];
                        if ($ok){
                            $param = dalMat::GuardarBitacora($conn, "1", "mat_contacto_grupo_contacto");
                            $ok = $param['ok'];
                        }
                    }
                }
                if ($ok){
                    $conn->commit();
                    $mensaje = 1;
                }
                else {
                    $conn->rollback();
                    $mensaje = 0;
                }
            }
            $conn->closeConn();
        }
        echo $mensaje;
    }
?>
