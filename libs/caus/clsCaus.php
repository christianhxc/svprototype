<?php
/**
 * Esta clase abstrae toda la logica utilizada en el CAUS para el conocmiento de
 * los accesos, permisos y datos generales del usuario que utiliza el sistema.
 * La idea basica es proveer metodos basicos los cuales se encargan de interactuar
 * con el objeto creado en sesión por cada usuario que se conecta
 *
 * @author cmelendez
 */

@session_start();
require_once("Utils.php");
require_once("dalCaus.php");
require_once("ConfigurationCAUS.php");
require_once("libs/helper/helperLugar.php");

class clsCaus {

    /**
     * Obtener el id del usuario
     * @return <string>
     */
    public static function obtenerID(){
        return $_SESSION["user"]["general"]["idusuario"];
    }

    /**
     * Obtener el email del usuario
     * @return <string>
     */
    public static function obtenerEmail(){
        return $_SESSION["user"]["general"]["email"];
    }

    /**
     * Obtener los nombres del usuario
     * @return <string>
     */
    public static function obtenerNombres(){
        return $_SESSION["user"]["general"]["nombres"];
    }

    /**
     * Obtener los apellidos del usuario
     * @return <string>
     */
    public static function obtenerApellidos(){
        return $_SESSION["user"]["general"]["apellidos"];
    }

    public static function obtenerIdGrupo(){
        return $_SESSION["user"]["general"]["idusuario_grupo"];
    }

    public static function obtenerUsername(){
        return $_SESSION["user"]["general"]["username"];
    }

    public static function obtenerOrgCodigo(){
        return $_SESSION["user"]["general"]["codigo"];
    }
    /**
     * Verificar si el usuario ya ha iniciado sesion
     * @return <bool>
     */
    public static function validarSession(){
        if (isset($_SESSION["user"]) && $_SESSION["user"]["cambiar"] == false){
            // Si la sesion expira, validar que aún sea valida
            if (ConfigurationCAUS::tiempoSesion > 0){
                $ultimo = $_SESSION["user"]["sesion"]; // Ultimo ingreso al sistema
                $actual = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y")); // Hora y fecha actual
                $diferencia = floor(($actual-$ultimo)/60);
                
                // Validamos que aun tenga tiempo disponible para permancer en sesion
                if ($diferencia > ConfigurationCAUS::tiempoSesion){
                    return false;
                    exit;
                }
            }

            // Guardar la fecha y hora
            $_SESSION["user"]["sesion"] = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
            return true;
        }else{
            return false;
        }
    }

    /**
     * Validar si el usuario puede cambiar la clave despues de haber ingresado credenciales validas
     * @return <bool>
     */
    public function validarCambiar(){
        return $_SESSION["user"]["cambiar"];
    }

    /**
     * Validar si las credenciales del usuario son validas o si hay que cambiar clave
     * @param <string> $username
     * @param <string> $password
     */
    public static function validarLogin($username, $password){
        // Validar si el usuario ya ha iniciado sesion
        if (self::validarSession())
            return 1;
        
        // Consultar en la base de datos si es un usuario y password valido
        $datos = dalCaus::esValido(trim($username), md5(trim($password)));
        //return $datos;exit;
        // Verificar que sea un usuario valido y que la clave sea correcta
        if ($datos["idusuario"] != 0){
            // Verificar si es necesario cambiar la clave
            if ($datos["cambiar"] == 1){
                // Cambiar password
                $_SESSION["user"]["cambiar"] = true;
                
                return 2;
            }

            // Si la clave debe expirar, validar que aún sea valida
            if (ConfigurationCAUS::expiracion > 0){
                $fecha_clave = $datos["fecha"];
                $fecha_expiracion = Utils::sumarDias($fecha_clave, ConfigurationCAUS::expiracion);
                $fecha_hoy = date("Y-m-d");
                
                // Verificar si la fecha de hoy es mayor que la fecha de expiracion (ya es necesario cambiar la clave)
                if (Utils::compararFechas($fecha_hoy, $fecha_expiracion) > 0){
                    // Cambiar password
                    $_SESSION["user"]["cambiar"] = true;

                    return 2;
                }
            }
            // Guardar el objeto en sesion
            
            $_SESSION["user"]["cambiar"] = false;
            $_SESSION["user"]["general"] = $datos;
            $_SESSION["user"]["organizacion"] = dalCaus::obtenerOrganizacion($datos["idusuario"]);
            // Traer y guardar los demas datos del usuario
            $_SESSION["user"]["secciones"] = self::obtenerSeccionesCompletas();
            $ubicaciones = self::obtenerUbicacionCompleta();;
            $_SESSION["user"]["ubicaciones"] = $ubicaciones["completa"];
            $_SESSION["user"]["ubicaciones_cascada"] = $ubicaciones["cascada"];
            // Guardar la fecha y hora
            $_SESSION["user"]["sesion"] = mktime(date("H"),date("i"),date("s"),date("m"),date("d"),date("Y"));
            
            return 1;
        }else{
            return -1;
        }
    }

    /**
     * Verificar si el usuario tiene acceso a una seccion e incluso si puede realizar X accion
     * @param <type> $idseccion
     * @param <type> $idaccion
     * @return <bool>
     */
    public function validarSeccion($idseccion, $idaccion = null){
        $permiso = $_SESSION["user"]["secciones"][$idseccion];

        if ($idaccion == null){
            return isset($permiso);
        }else{
            return isset($permiso["acciones"][$idaccion]);
        }
    }

    /**
     * Parsear las secciones en un arreglo facil de accesar
     * @return <array>
     */
    private function obtenerSeccionesCompletas(){
        $secciones = array();
        $datos = dalCaus::obtenerSecciones(self::obtenerID());
        
        if (is_array($datos)){
            foreach ($datos as $dato){
                $secciones[$dato["id"]]["tipo"] = $dato["tipo"];
                $secciones[$dato["id"]]["nombre"] = $dato["nombre"];
                $secciones[$dato["id"]]["codigo"] = $dato["codigo"];
                $secciones[$dato["id"]]["acciones"][$dato["idaccion"]] = 1;
                if ($dato["idseccion_padre"] != "")
                $secciones[$dato["idseccion_padre"]]["secciones"][$dato["id"]] = $dato["codigo"];
            }
        }

        return $secciones;
    }

    /**
     * Obtener el listado de sub secciones, es decir, secciones que tengan como idseccion_padre el idseccion
     * @param <int> $idseccion
     */
    public function obtenerSecciones($idseccion){
        return $_SESSION["user"]["secciones"][$idseccion]["secciones"];
    }

    /**
     * Obtener el listado completo de las ubicaciones que tien asigandas el usuario
     * @return <array>
     */
    private function obtenerUbicacionCompleta(){
        $ubicaciones = array();
        $data = dalCaus::obtenerUbicaciones(self::obtenerID());

        if (is_array($data["ubicaciones"])){
            foreach ($data["ubicaciones"] as $ubicacion){
                $cascada = array();
                $temporal["codigo"] = $ubicacion["codigo"];
                $temporal["extra"] = $ubicacion["extra"];
                $ubicaciones["completa"][$ubicacion["tipo"]][$ubicacion["codigo".($ubicacion["tipo"] - 1)]][$temporal["codigo"]] = $temporal;
                $cascada[$ubicacion["tipo"]] = $temporal["codigo"];

                if (is_array($data["tipos"])){
                    foreach ($data["tipos"] as $tipo){
                        if ($ubicacion["codigo".$tipo["tipo"]] != ""){
                            $temporal["codigo"] = $ubicacion["codigo".$tipo["tipo"]];
                            $temporal["extra"] = $ubicacion["extra".$tipo["tipo"]];
                            $ubicaciones["completa"][$tipo["tipo"]][$ubicacion["codigo".($tipo["tipo"] - 1)]][$temporal["codigo"]] = $temporal;
                            $cascada[$tipo["tipo"]] = $temporal["codigo"];
                        }
                    }
                }

                $ubicaciones["cascada"][] = $cascada;
            }
        }

        return $ubicaciones;
    }

    /**
     * Obtener el listado de ubicaciones que tiene asignadas el usuario a cierto nivel
     * con la capacidad de devolver incluso solo ubicaciones de alguna ubicacion en especifico (sub ubicacion)
     * @param <int> $idtipo, es el nivel del cual se desean obtener las ubicaciones
     * @param <string> $subtipo, es el codigo de referencia del cual se desean obtener las ubicaciones
     * @return <array>
     */
    public function obtenerUbicacion($idtipo, $subtipo = ""){
        $data = array();
        $lista = $_SESSION["user"]["ubicaciones"][$idtipo][$subtipo];
        
        if (is_array($lista)){
            foreach ($lista as $ubicaciones){
                $data[] = $ubicaciones[(!$extra) ? "codigo" : "extra"];
            }
        }

        return $data;
    }

    /**
     * Obtener el listado completo de ubicaciones de algun nivel sin ningun filtro
     * @param <int> $idtipo, es el nivel del cual se desean obtener las ubicaciones
     * @param <bool> $extra, se utiliza para saber si retorna el listado de codigos o los datos extra de cada ubicacion
     * @return <array>
     */
    public function obtenerUbicaciones($idtipo, $extra = false){
        $data = array();
        $lista = $_SESSION["user"]["ubicaciones"][$idtipo];
        if (is_array($lista)){
            foreach ($lista as $ubicaciones){
                if (is_array($ubicaciones)){
                    foreach ($ubicaciones as $ubicacion){
                        $data[] = $ubicacion[(!$extra) ? "codigo" : "extra"];
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Obtener el listado de ubicaciones en cascada para el filtro de datos por ubicacion
     */
    public static function obtenerUbicacionesCascada(){
        return $_SESSION["user"]["ubicaciones_cascada"];
    }

    /**
     * 1. Validar que la clave anterior sea correcta
     * 2. Agregar nueva clave
     * @param <type> $username
     * @param <type> $clave_anterior
     * @param <type> $clave_nueva
     * @return <int> -2 = Error al cambiar la clave, -1 = Clave anterior no es correcta, 1 = Clave cambiada
     */
    public static function cambiarClave($username, $clave_anterior, $clave_nueva){
        // Consultar en la base de datos si es un usuario y password valido
        $datos = dalCaus::esValido(trim($username), md5(trim($clave_anterior)));
        //print_r($datos);exit;
        // Verificar que sea un usuario valido y que la clave sea correcta
        if ($datos["idusuario"] != 0){
            $resultado = dalCaus::agregarClave($datos["idusuario"], md5(trim($clave_nueva)));

            // Verificar que no exista nigun error al agregar la clave
            if (!isset($resultado["error"])){
                $_SESSION["user"]["cambiar"] = false;
                return 1;
            }else{
                return -2;
            }
        }else{
            echo $username." claveanterior: ".$clave_anterior." clavenueva: ".$clave_nueva;
            return -1;
        }
    }

    /**
     * Generar una clave random, agregarla a la BD y devolverla al front end (para mostrarla al usuario)
     * @return <string> 
     */
    public function recuperarClave($username){
        $usuario = dalCaus::datosUsuario($username);
        
        $clave_nueva = Utils::crearClaveRandom();
        
        $resultado = dalCaus::agregarClave($usuario["idusuario"], md5(trim($clave_nueva)), 1);
        if ($resultado["error"] != ""){
            return -1;
            exit;
        }
        
        // Enviar email
        $subject = ConfigurationCAUS::emailAsunto;
        
        $message = "La recuperacion de la clave de su usuario para el sistema ha sido solicitada y le estamos enviando la nueva clave que ha sido generada, al ingresar al sistema se le solicitara que la cambie por motivos de seguridad.\n\n";
        $message .= "La nueva clave es: ".$clave_nueva."\n\n";
        $message .= "Si tiene algun problema o dificultad por favor contactenos";

        $headers = 'From: '.ConfigurationCAUS::emailDe."\r\n";
        $headers .= 'Reply-To: '.ConfigurationCAUS::emailResponder. "\r\n";
        
        $sent = mail($usuario["email"], $subject, $message, $headers);
        if (!$sent){
            //return -1;
            exit;
        }

        return 1;
    }

    /**
     * Borrar el objeto en sesion
     */
    public function cerrarSesion(){
        unset($_SESSION["user"]);
    }
    
    public static function obtenerCorUsuario(){
        $ubicaciones = clsCaus::obtenerUbicacionesCascada();
        //print_r($ubicaciones);exit;
        $corId="";
        if (is_array($ubicaciones)){
            foreach ($ubicaciones as $ubicacion){
                $ubicacion = array_reverse($ubicacion, true);
                $numReg = count($ubicacion);
                $ultimoDato = array_pop($ubicacion);
                if ($numReg==4)
                    $corId.=$ultimoDato." , ";
                else if ($numReg==3)
                    $corId.= self::corrPorDistrito ($ultimoDato);
                else if ($numReg==2)
                    $corId.= self::corrPorRegion ($ultimoDato);
                else if ($numReg==1)
                    $corId.= self::corrPorProvincia ($ultimoDato);
            }
            $corId .= "fin";
            $corId = str_replace(", fin","",$corId);
        }
        return $corId;
    }
        
    private static function corrPorDistrito($disId){
        $datos = helperLugar::obtenerIdCorregimientos($disId);
        $corId = "";
        for ($i=0;$i<count($datos);$i++){
            $corId .= $datos[$i]." , ";
        }
        return $corId;
    }

    private static function corrPorRegion($regId){
        $corId = "";
        $datos = helperLugar::obtenerIdDistritos($regId);
        for ($i=0;$i<count($datos);$i++){
            $datos1 = helperLugar::obtenerIdCorregimientos($datos[$i]);
            for ($j=0;$j<count($datos1);$j++){
                    $corId .= $datos1[$j]." , ";
            }
        }
        return $corId;
    }

    private static function corrPorProvincia($proId){
        $corId = "";
        $datos = helperLugar::obtenerIdRegiones($proId);
        if (is_array($datos)){
            foreach ($datos as $dato){
                $datos1 = helperLugar::obtenerIdDistritos($dato["id_region"]);
                if(is_array($datos1)){
                    foreach ($datos1 as $dato1){
                        $datos2 = helperLugar::obtenerIdCorregimientos($dato1["id_distrito"]);
                        if(is_array($datos2)){
                            foreach ($datos2 as $dato2){
                                $corId .= $dato2["id_corregimineto"]." , ";
                            }
                        }
                    }
                }
            }
        }
        return $corId;
    }
    
}

?>
