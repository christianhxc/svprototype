<?php
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/helper/helperString.php');
require_once('libs/caus/clsCaus.php');
require_once('libs/caus/ConfigurationCAUS.php');
require_once("libs/Bitacora.php");

class dalDenominador{
	private $search;
	
    public function __construct($search){
            $this->search = $search;

            // Filtrar los resultados
            $lista = clsCaus::obtenerUbicacionesCascada();
            if (is_array($lista)){
                foreach ($lista as $elemento){
                    $temporal = "";
                    if ($elemento[ConfigurationCAUS::AreaSalud] != "")
                        $temporal .= " dss.idas = '".$elemento[ConfigurationCAUS::AreaSalud]."' ";

                    if ($elemento[ConfigurationCAUS::DistritoSalud] != "")
                        $temporal .= ($temporal != '' ? " and " : "")." dss.idds = '".$elemento[ConfigurationCAUS::DistritoSalud]."' ";

                    if ($elemento[ConfigurationCAUS::ServicioSalud] != "")
                        $temporal .= ($temporal != '' ? " and " : "")." dss.idts = '".$elemento[ConfigurationCAUS::ServicioSalud]."' ";

                    $this->filtro .= ($this->filtro != '' ? " or " : "")."(".$temporal.") ";
                }
            }

            if ($this->filtro != "")
            $this->filtro = " and (".$this->filtro.")";                

    }
	
    public static function Guardar($data, $roles){
        $ok = true;
        $conn = new Connection();
    	$conn->initConn();
    	
    	$conn->begin();

        // Validar que solo pueda guardar los datos si tiene acceso
        if ($ok){
            // Ingresar los datos generales

            $sql = Actions::AgregarQuery("denominador",$data["encabezado"]);
            $conn->prepare($sql);
            $conn->params($data["encabezado"]);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $id = $conn->getID();

            // Guardar registro en la bitacora
            Bitacora::Guardar(1, "denominador");

            // Ingresar detalle de los denominadores

            if (is_array($data["detalle"]))
                {
                    $temp = array();
                    $temp["id_denominador"] = $id;
                    $idGrupoEdad=1;
                    foreach($data["detalle"] as $denominador)
                        {
                            $temp["hospitalizacion_m"]=$denominador["hospital"][0];
                            $temp["hospitalizacion_f"]=$denominador["hospital"][1];
                            $temp["hospitalizacion_m_irag"]=$denominador["hospital_irag"][0];
                            $temp["hospitalizacion_f_irag"]=$denominador["hospital_irag"][1];
                            $temp["uci_m"]=$denominador["uci"][0];
                            $temp["uci_f"]=$denominador["uci"][1];
                            $temp["uci_m_irag"]=$denominador["uci_irag"][0];
                            $temp["uci_f_irag"]=$denominador["uci_irag"][1];
                            $temp["defuncion_m"]=$denominador["defuncion"][0];
                            $temp["defuncion_f"]=$denominador["defuncion"][1];
                            $temp["defuncion_m_irag"]=$denominador["defuncion_irag"][0];
                            $temp["defuncion_f_irag"]=$denominador["defuncion_irag"][1];
                            $temp["id_grupo_edad"]=$idGrupoEdad;

                            $sql = Actions::AgregarQuery("denominador_detalle",$temp);                      
                            $conn->prepare($sql);
                            $conn->params($temp);
                            $conn->execute() ? null : $ok = false;
                            $conn->closeStmt();
                            $idGrupoEdad++;

                    }
                }
        }
      
    	if ($ok){
    		$conn->commit();
    	}else{
    		$conn->rollback();
    		$id = -1;
    	}
    	
    	$conn->closeConn();
    	
    	return $id;
	}
	
	public static function Modificar($data, $filtro, $roles){
        $ok = true;
		$conn = new Connection();
    	$conn->initConn();
    	$conn->begin();
    	$id = $filtro["id_denominador"];
        // Validar que solo pueda guardar los datos si tiene acceso

    	// Ingresar los datos generales
    	$sql = Actions::ActualizarQuery("denominador",$data["encabezado"],$filtro);
    	$conn->prepare($sql);
    	$conn->params(array_merge($data["encabezado"],$filtro));
    	$conn->execute() ? null : $ok = false;
		$conn->closeStmt();

        // Guardar registro en la bitacora
        Bitacora::Guardar(2, "denominador");
        
        if (is_array($data["detalle"])){
    		$temp = array();
            $tempfiltro=array();
    		$tempfiltro["id_denominador"] = $id;
            
    		foreach($data["detalle"] as $denominador){
                $tempfiltro["id_denominador_detalle"]=$denominador["id_detalle"];
                $temp["hospitalizacion_m"]=$denominador["hospital"][0];
                $temp["hospitalizacion_f"]=$denominador["hospital"][1];
                $temp["hospitalizacion_m_irag"]=$denominador["hospital_irag"][0];
                $temp["hospitalizacion_f_irag"]=$denominador["hospital_irag"][1];                
                $temp["uci_m"]=$denominador["uci"][0];
                $temp["uci_f"]=$denominador["uci"][1];
                $temp["uci_m_irag"]=$denominador["uci_irag"][0];
                $temp["uci_f_irag"]=$denominador["uci_irag"][1];                
                $temp["defuncion_m"]=$denominador["defuncion"][0];
                $temp["defuncion_f"]=$denominador["defuncion"][1];
                $temp["defuncion_m_irag"]=$denominador["defuncion_irag"][0];
                $temp["defuncion_f_irag"]=$denominador["defuncion_irag"][1];                

    			$sql = Actions::ActualizarQuery("denominador_detalle",$temp,$tempfiltro);
    			$conn->prepare($sql);
		    	$conn->params(array_merge($temp,$tempfiltro));
		    	$conn->execute() ? null : $ok = false;
				$conn->closeStmt();                
    		}
    	}

        // Guardar registro en la bitacora
        Bitacora::Guardar(2, "denominador_detalle");

    	if ($ok){
    		$conn->commit();
    	}else{
    		$conn->rollback();
    		$id = -1;
    	}

    	$conn->closeConn();

    	return $id;    	
    }

	public static function Borrar($filtro){
        $ok = true;
        $conn = new Connection();
        $conn->initConn();

        $conn->begin();

        // Borrar Detalle de denominadores
        $sql = Actions::BorrarQuery("denominador_detalle",$filtro);
        $conn->prepare($sql);
        $conn->params($filtro);
        $conn->execute() ? null : $ok = false;
        $conn->closeStmt();

        // Guardar registro en la bitacora
        Bitacora::Guardar(3, "denominador_detalle");

        // Borrar maestro de denominadores
        $sql = Actions::BorrarQuery("denominador",$filtro);
        $conn->prepare($sql);
        $conn->params($filtro);
        $conn->execute() ? null : $ok = false;
        $conn->closeStmt();

        // Guardar registro en la bitacora
        Bitacora::Guardar(3, "denominador");
        
        if ($ok){
            $conn->commit();
        }else{
            $conn->rollback();
            $id = -1;
        }

        $conn->closeConn();

        return $id;
	}
	
	public function getAll(){
		$conn = new Connection();
    	$conn->initConn();

    	// Listar las entradas en el sistema
    	$sql = "select  d.id_denominador,
        d.id_denominador as no_denominador,
				d.fecha_notificacion,
				d.responsable,
				d.semana_epidemilogica,
				d.anio,
				c_u_n.nombre_un as unidad_notificadora
                from denominador d LEFT JOIN cat_unidad_notificadora c_u_n ON d.id_un=c_u_n.id_un;";

        $sql.=($this->search["semana_epidemiologica"] != "" ? " and d.semana_epidemilogica = ".$conn->scapeString($this->search["semana_epidemiologica"]) : "");
        $sql.=($this->search["semana_anio"] != "" ? " and d.anio = ".$conn->scapeString($this->search["semana_anio"]) : "");
        $sql.=($this->search["desde"] != "" ? " and d.fecha_notificacion >= '".$conn->scapeString(helperString::toDate($this->search["desde"]))." 00:00:00'" : "");
    	$sql.=($this->search["hasta"] != "" ? " and d.fecha_notificacion <= '".$conn->scapeString(helperString::toDate($this->search["hasta"]))." 23:59:59'" : "");
//        Esto es para filtrarlo por el tipo de usuario y ubicación
        $sql.=$this->filtro;

        if ($_SESSION["user"]["username"] == "ditrosi"){
            echo $sql; exit;};
    	$conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetch();

    	$conn->closeConn();

    	return $data;
	}
	
	public function getCountAll(){
		$conn = new Connection();
    	$conn->initConn();

    	// Listar las entradas en el sistema
    	$sql = "select count(1) as cantidad from denominador";
        
    	$conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetchOne();

    	$conn->closeConn();

    	return $data["cantidad"];
	}

    public static function getDatos($id){
            $conn = new Connection();
            $conn->initConn();

            // Datos Generales
            $sql = "select d.*, c_u_n.nombre_un as unidad_notificadora
                            from denominador d LEFT JOIN cat_unidad_notificadora c_u_n ON d.id_un=c_u_n.id_un 
                            where id_denominador =  ?";
            $conn->prepare($sql);
            $conn->param($id);
            $conn->execute();

            $general = $conn->fetchOne();
            $data["encabezado"] = $general;
            $conn->closeStmt();

            // Detalle denominador
            $sql = "select *
                    from denominador_detalle
                    where id_denominador = ?";
            $conn->prepare($sql);
            $conn->param($id);
            $conn->execute();

            $detalledenominador = $conn->fetch();
            $data["detalle"] = $detalledenominador;
            $conn->closeStmt();

            return $data;
	}
}