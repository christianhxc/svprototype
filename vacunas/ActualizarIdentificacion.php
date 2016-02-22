<?php
    require_once('libs/dal/vacunas/dalVacunas.php');
    require_once('libs/Connection.php');
    require_once('libs/helper/helperVacunas.php');
    
    $resultado = null;    
    
    if (isset ($_POST["numActual"]) && isset ($_POST["tipoActual"]) && isset ($_POST["numNuevo"]) && isset ($_POST["tipoNuevo"])){
        $sql = "select * from tbl_persona 
                where numero_identificacion = '".$_POST["numActual"]."' AND tipo_identificacion = ".$_POST["tipoActual"];
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        $persona = ($data!=null) ? $data : null;
        $persona = $persona[0];
        $algoritmo = new accionesBD($persona);
        // Nombre de las tablas donde pueden existir datos de esta persona 
        $tablas = array("rae_form","vm_form","flureg_form","vih_form");
        $otroRegistro = 0;
        $nombreTabla = "";
        foreach ($tablas as $tabla){
            $data = $algoritmo->getOtrasTablas($tabla, $_POST["numActual"]);
            if ($data[0] !== 0){
                $otroRegistro = $data[0];
                $nombreTabla = $tabla;
                //break 1;
            }
        }
        $sql = "";
        if ($otroRegistro === 0){
            $tablasModificar = array("vac_persona_condicion","vac_registro_diario","tbl_persona");
            $conn = new Connection();
            $conn->initConn();
            $conn->begin();
//            $sql = " ALTER TABLE vac_registro_diario
//                    DROP FOREIGN KEY fk_reg_diario_persona;";
            foreach ($tablasModificar as $tabla){
                $sql = " UPDATE $tabla T1
                         SET T1.numero_identificacion = '".$_POST["numNuevo"]."', T1.tipo_identificacion = '".$_POST["tipoNuevo"]."'
                         WHERE T1.numero_identificacion = '".$_POST["numActual"]."' AND T1.tipo_identificacion = '".$_POST["tipoActual"]."'";
                //echo $sql;exit;
                $param = dalVacunas::ejecutarQuery($conn, $sql);
                if (!$param['ok']) {
                    $conn->rollback();
                    $conn->closeConn();
                    echo "Error: ";
                    echo "SQL:" . $sql;
                    exit;
                }
            }
//            $sql .= " ALTER TABLE vac_registro_diario
//                     ADD CONSTRAINT fk_reg_diario_persona FOREIGN KEY (tipo_identificacion, numero_identificacion) REFERENCES tbl_persona (tipo_identificacion, numero_identificacion) ON UPDATE NO ACTION ON DELETE NO ACTION;";
            //echo $sql;exit;
            
            $conn->commit();
                $conn->closeConn();
                exit;
            
        }
        else{
            echo "Error: ";
            echo "No se pudo realizar el cambio, porque esta persona tiene un registro en la tabla: ".$nombreTabla;
            exit;
        }
    }
        
    class accionesBD {
        private $persona;
        
        function __construct($persona){
            $this->persona = $persona;
	}
                
        public function getOtrasTablas($nombreTabla, $numId){
            $sql = "select count(*) as total
                    from ".$nombreTabla." T
                    where T.numero_identificacion like '%".$numId."%'";
            $conn = new Connection();
            $conn->initConn();
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            $condicionEsquema = ($data!=null) ? $data : null;
            return $condicionEsquema;
        }
        
    }

