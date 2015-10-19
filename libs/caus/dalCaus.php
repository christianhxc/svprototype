<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of dalCaus
 *
 * @author cmelendez
 */

require_once('ConnectionCAUS.php');
require_once('ConfigurationCAUS.php');
require_once('libs/Actions.php');

class dalCaus {

    public function esValido($username, $password){
        $params[] = $username;
        $params[] = $password;

        $sql = "select u.idusuario, u.username, u.nombres, u.apellidos, u.email, uc.fecha, uc.cambiar, u.idusuario_grupo
                from tbl_usuario u
                inner join tbl_usuario_clave uc on u.idusuario = uc.idusuario
                where u.status = 1
                and uc.status = 1
                and u.username = ?
                and uc.clave = ?
                order by uc.fecha desc
                limit 0,1";
        
        $conn = new ConnectionCAUS();
    	$conn->initConn();
        $conn->prepare($sql);
    	$conn->params($params);
    	$conn->execute();
        $data = $conn->fetchOne();
	$conn->closeStmt();

        return $data;
    }

    public function datosUsuario($username){

        $sql = "select u.idusuario, u.username, u.nombres, u.apellidos, u.email, uc.fecha, uc.cambiar
                from tbl_usuario u
                inner join tbl_usuario_clave uc on u.idusuario = uc.idusuario
                where u.status = 1
                and uc.status = 1
                and u.username = ?
                order by uc.fecha desc
                limit 0,1";

        $conn = new ConnectionCAUS();
    	$conn->initConn();
        $conn->prepare($sql);
    	$conn->param($username);
    	$conn->execute();
        $data = $conn->fetchOne();
	$conn->closeStmt();

        return $data;
    }

    public static function obtenerOrganizacion($idusuario){
        $sql = "select ts.idseccion
                FROM tbl_seccion_usuario su
                inner join tbl_seccion ts ON su.idseccion = ts.idseccion
                where su.idaccion = ".ConfigurationCAUS::Consultar." and ts.idseccion_padre = ".ConfigurationCAUS::idSeccionPadreOrg." and su.idusuario = ?";
        //return $sql;exit;
        $conn = new ConnectionCAUS();
    	$conn->initConn();
        $conn->prepare($sql);
    	$conn->param($idusuario);
    	$conn->execute();
        $data = $conn->fetchOne();
	$conn->closeStmt();
        $idSeccion = (isset($data["idseccion"]))?$data["idseccion"]:-1;
        return $idSeccion;
    }

    public function obtenerSecciones($idusuario, $caus = false){
        $params[] = $idusuario;
        $params[] = $idusuario;

        // Secciones tanto de grupo como usuario individual
        $sql = "select s.idseccion as id, s.nombre, s.codigo, a.idaccion, ss.idseccion, s.idseccion_padre, s.idseccion_tipo as tipo
                from tbl_seccion s
                inner join tbl_seccion_usuario su on s.idseccion = su.idseccion
                left join tbl_accion a on a.idaccion = su.idaccion
                left join tbl_seccion ss on ss.idseccion = s.idseccion_padre
                where s.status = 1
                and su.idusuario = ?
                and s.caus = ".($caus ? 1 : 0)."
                union
                select s.idseccion as id, s.nombre, s.codigo, a.idaccion, ss.idseccion, s.idseccion_padre, s.idseccion_tipo as tipo
                from tbl_seccion s
                inner join tbl_seccion_usuario_grupo sug on s.idseccion = sug.idseccion
                inner join tbl_usuario u on u.idusuario_grupo = sug.idusuario_grupo
                left join tbl_accion a on a.idaccion = sug.idaccion
                left join tbl_seccion ss on ss.idseccion = s.idseccion_padre
                where s.status = 1
                and u.idusuario = ?
                and s.caus = ".($caus ? 1 : 0)."
                group by s.idseccion, s.nombre, s.codigo, a.idaccion, ss.idseccion, s.idseccion_padre, s.idseccion_tipo";
           
        $conn = new ConnectionCAUS();
    	$conn->initConn();
        $conn->prepare($sql);
    	$conn->params($params);
    	$conn->execute() ? null : $ok = false;
        $data = $conn->fetch();
	$conn->closeStmt();

        return $data;
    }

    public function obtenerUbicacionesTipo(){

        $sql = "select ut.idubicacion_tipo as tipo, ut.nombre
                from tbl_ubicacion_tipo ut
                where ut.status = 1
                order by ut.orden desc";

        $conn = new ConnectionCAUS();
    	$conn->initConn();
        $conn->prepare($sql);
    	$conn->execute() ? null : $ok = false;
        $data = $conn->fetch();
	$conn->closeStmt();

        return $data;
    }
    
    public function obtenerUbicaciones($idusuario){
        $tipos = self::obtenerUbicacionesTipo();
        unset($tipos[0]); // Quitar el ultimo tipo pues este no tiene registros hijo
        $data["tipos"] = $tipos;

        $anterior = "";
        if (is_array($tipos)){
            foreach ($tipos as $tipo){
                $mostrar .= "u".$tipo["tipo"].".codigo as codigo".$tipo["tipo"].", u".$tipo["tipo"].".extra as extra".$tipo["tipo"].",";
                $condicion .= "left join tbl_ubicacion u".$tipo["tipo"]." 
                                on (u".$tipo["tipo"].".idubicacion = u.idubicacion_padre or u".$tipo["tipo"].".idubicacion = u".$anterior.".idubicacion_padre)
                                and u".$tipo["tipo"].".idubicacion_tipo = ".$tipo["tipo"]." ";
                $anterior = $tipo["tipo"];
            }
        }

        $sql = "select
                    ".$mostrar."
                    u.codigo, u.extra, u.idubicacion_tipo as tipo
                from tbl_ubicacion u
                inner join tbl_ubicacion_usuario uu on u.idubicacion = uu.idubicacion
                ".$condicion."
                where u.status = 1
                and uu.idusuario = ?";
        
        $conn = new ConnectionCAUS();
    	$conn->initConn();
        $conn->prepare($sql);
    	$conn->param($idusuario);
    	$conn->execute() ? null : $ok = false;
        $data["ubicaciones"] = $conn->fetch();
	$conn->closeStmt();
        
        return $data;
    }

    public static function agregarClave($idusuario, $clave, $cambiar = 0){
        $ok = true;
        
        // Armar el arreglo con los datos a ingresar
        $data["idusuario"] = $idusuario;
        $data["clave"] = $clave;
        $data["fecha"] = date("Y-m-d h:i:s");
        $data["cambiar"] = $cambiar;

        $conn = new ConnectionCAUS();
    	$conn->initConn();
    	
        // Ingresar los datos generales
        $actualizar["status"] = 0;
        $filtro["idusuario"] = $idusuario;
        $sql = Actions::ActualizarQuery("tbl_usuario_clave",$actualizar,$filtro);
    	$conn->prepare($sql);
    	$conn->params(array_merge($actualizar,$filtro));
    	$conn->execute() ? null : $ok = false;
	$conn->closeStmt();
               
    	$sql = Actions::AgregarQuery("tbl_usuario_clave",$data);
    	$conn->prepare($sql);
    	$conn->params($data);
    	$conn->execute() ? null : $ok = false;
        $conn->closeStmt();

        if ($ok){
            $conn->commit();
    	}else{
            $data["error"] = "No se pudo cambiar la clave";
            $conn->rollback();
            $id = -1;

            return $data;
    	}
    }
}
?>
