<?php
require_once('Connection.php');

class Actions{
	
	function __construct()
	{
	}
	
	public static function AgregarQuery($tabla,$data){
		$values = array();
		$fields = array();
		$error = array(); 
		
		foreach($data as $key=>$value) {
			$fields[] = $key;
			$values[] = "?";
		}
		
		$fields = implode('`,`', $fields);
		$values = implode(",", $values);
		
		$sql = "INSERT INTO `".$tabla."`(`".$fields."`) VALUES(".$values.")";
    	
    	return $sql;
	}
	
	public static function ActualizarQuery($tabla,$data,$filtro){
		$values = array();
		$fields = array();
		$where = "";
		
		foreach($data as $key=>$value) {
			$values[] = " " . $key . " = ? ";
			//$values[] = " " . $key . " = '".$value."' ";
		}
		$values = implode(',', $values);
		
		foreach($filtro as $key=>$value){
			$where .= " AND ".$key." = ? ";
			//$where .= " AND ".$key." = ".$value." ";
		}
		
		$sql = "UPDATE ".$tabla." SET ".$values."
				WHERE 1 = 1 
				".$where;
		
		return $sql;
	}
	
	public static function BorrarQuery($tabla,$filtro){
        $where = "";
		foreach($filtro as $key=>$value)
			$where .= "AND ".$key." = ? ";
		
		$sql = "DELETE FROM ".$tabla." 
				WHERE 1
				".$where;
		
		return $sql;
	}
	
	public static function Agregar($tabla,$data){
		$sql = Actions::AgregarQuery($tabla,$data);
		
		$conn = new Connection();
    	$conn->initConn();
    	$exe = mysql_query($sql) or $error['message'] = 'Hubo un error al ingresar el registro, al parecer ya existe.';
    	$conn->closeConn();
    	
    	return $error;
	}
	
	public static function AgregarID($tabla,$data){
		$sql = Actions::AgregarQuery($tabla,$data);
		
		$conn = new Connection();
    	$conn->initConn();
    	$exe = mysql_query($sql) or $error['message'] = 'Hubo un error al ingresar el registro, intente de nuevo.';
    	$id = mysql_insert_id();
    	$conn->closeConn();
    	
    	if (isset($error['message'])) return $error;
    	else return $id;
	}
	
	public static function Actualizar($tabla,$data,$filtro){
		$sql = Actions::ActualizarQuery($tabla,$data,$filtro);
		
		$conn = new Connection();
    	$conn->initConn();
    	$exe = mysql_query($sql);
    	$conn->closeConn();
	}
	
	public static function Borrar($tabla,$filtro){
            $sql = self::BorrarQuery($tabla, $filtro);
            $conn = new Connection();
            $conn->initConn();
            $exe = mysql_query($sql) or $error['message'] = 'Hubo un error al borrar el registro, verifique que no se este usando en otra parte.';
            $conn->closeConn();

            if (isset($error['message'])) return $error; else return $exe;
	}
        
        public static function ejecutarQuery($sql){
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            //echo $sql;exit;
            $conn->execute() ? null : $error['message'] = 'Hubo un error al actualizar el registro.';
            $conn->closeStmt();
            $conn->closeConn();
            if (isset($error['message'])) return 0; else return 1;
	}
        
        public static function ejecutarQueryId($sql){
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            //echo $sql;exit;
            $conn->execute() ? null : $error['message'] = 'Hubo un error al actualizar el registro.';
            $id = $conn->getID();
            $conn->closeStmt();
            $conn->closeConn();
            if (isset($error['message'])) return 0; else return $id;
	}
        
        public static function selectQuery($sql){
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeStmt();
            $conn->closeConn();
            return $data;
        }
}
?>