<?php
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/caus/clsCaus.php');
require_once('libs/caus/ConfigurationCAUS.php');

class Bitacora {

    /*
     * Metodo para agregar un registro en la bitacora sobre las acciones que el usuario realiza
     * $accion, ID de la accion que esta generando el usuario, 1 = Agregar, 2 = Modificar y 3 = Eliminar
     * $tabla, Nombre de la tabla en la que esta efectuando la accion
     */
    public static function Guardar($accion, $tabla){
        $ok = true;
	$conn = new Connection();
    	$conn->initConn();
    	
    	$conn->begin();

    	// Ingresar los datos de la bitacora
        $data["usuid"] = clsCaus::obtenerID();
        $data["fecha"] = date("Y-m-d H:i:s");
        $data["accion"] = $accion;
        $data["tabla"] = $tabla;

    	$sql = Actions::AgregarQuery("bitacora",$data);
    	$conn->prepare($sql); 
    	$conn->params($data);
    	$conn->execute() ? null : $ok = false;
	$conn->closeStmt();
    	$id = $conn->getID();
    	
    	if ($ok){
            $conn->commit();
    	}else{
            $conn->rollback();
            $id = -1;
    	}
    	
    	$conn->closeConn();
    	
    	return $id;
    }
}
?>
