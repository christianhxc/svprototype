<?php
require_once('libs/Connection.php');
require_once('libs/Configuration.php');
require_once('libs/Actions.php');
require_once ('libs/caus/clsCaus.php');
require_once ('libs/Configuration.php');

class reporteEnvio
{
    public static function crearInforme($muestras)
    {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        // CREA EL INFORME Y GUARDA EL ID
        $config["INF_ENV_FECHAHORA"] = date("Y-m-d H:i:s");
        $config["INF_ENV_RESPONSABLE"] = clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos();
        $config["INF_ENV_TOTAL"] = count($muestras);
        $config["INF_ENV_RESTANTES"] = count($muestras);
        $config["INF_ENV_TIPO"] = '1';

        $sql = Actions::AgregarQuery("informe_envio", $config);
        $conn->prepare($sql);
        $conn->params($config);
        $conn->execute() ? null : $ok = false;
        $conn->closeStmt();
        $id = $conn->getID();

        // BITACORA
        $bitacora = array();
        $bitacora["BIT_USUARIO"] = clsCaus::obtenerID();
        $bitacora["BIT_FECHA"] = date("Y-m-d H:i:s");
        $bitacora["BIT_ACCION"] = '1';
        $bitacora["BIT_TABLA"] = "informe_envio";

        $sql = Actions::AgregarQuery("bitacora", $bitacora);
        $conn->prepare($sql);
        $conn->params($bitacora);
        $conn->execute() ? null : $ok = false;
        $conn->closeStmt();
        $conn->getID();

        // INSERTA EN EL HISTORIAL DE MUESTRAS CON EL ID DEL INFORME

        $sql = "INSERT INTO historial_muestra values ";
        for($i=0; $i<count($muestras); $i++){
            $sql.= '('.$id.','.$muestras[$i].'),';
        }
        $sql = substr($sql, 0, strlen($sql)-1);       
        $conn->prepare($sql);
        $conn->execute() ? null : $ok = false;
        $conn->closeStmt();
        $conn->getID();

        // BITACORA
        $bitacora = array();
        $bitacora["BIT_USUARIO"] = clsCaus::obtenerID();
        $bitacora["BIT_FECHA"] = date("Y-m-d H:i:s");
        $bitacora["BIT_ACCION"] = '1';
        $bitacora["BIT_TABLA"] = "historial_muestra";

        $sql = Actions::AgregarQuery("bitacora", $bitacora);
        $conn->prepare($sql);
        $conn->params($bitacora);
        $conn->execute() ? null : $ok = false;
        $conn->closeStmt();
        $conn->getID();

        // Cambiar el estado de las muestras de ventanilla a envío
        for($i=0; $i<count($muestras); $i++)
        {
            $tipo = helperMuestra::tipoMuestra($muestras[$i]);
            $situacion = 0;

            if($tipo == Configuration::tipoMuestra)
                $situacion = Configuration::enviada;
            else if($tipo == Configuration::tipoAlicuotaIngresada)
                $situacion = Configuration::enviadaDer;

            $sql='';
            $sql = "UPDATE muestra SET muestra.SIT_ID =".$situacion.", muestra.MUE_ENVIADA_POR='".clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos()."', muestra.MUE_FECHA_ENVIO='".date("Y-m-d")."'
                where muestra.MUE_ID=".$muestras[$i];
            $conn->prepare($sql);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $conn->getID();

            if(!$ok)
                break;
        }

        // BITACORA
        $bitacora = array();
        $bitacora["BIT_USUARIO"] = clsCaus::obtenerID();
        $bitacora["BIT_FECHA"] = date("Y-m-d H:i:s");
        $bitacora["BIT_ACCION"] = '2';
        $bitacora["BIT_TABLA"] = "muestra";

        $sql = Actions::AgregarQuery("bitacora", $bitacora);
        $conn->prepare($sql);
        $conn->params($bitacora);
        $conn->execute() ? null : $ok = false;
        $conn->closeStmt();
        $conn->getID();

        if($ok)
            $conn->commit ();
        else
        {
            $conn->rollback();
            $id = -1;
        }
        $conn->closeConn();

        return $id;
    }

    public static function crearInformeDerivaciones($muestras)
    {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        // CREA EL INFORME Y GUARDA EL ID
        $config["INF_ENV_FECHAHORA"] = date("Y-m-d H:i:s");
        $config["INF_ENV_RESPONSABLE"] = clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos();
        $config["INF_ENV_TOTAL"] = count($muestras);
        $config["INF_ENV_RESTANTES"] = count($muestras);
        $config["INF_ENV_TIPO"] = '2';

        $sql = Actions::AgregarQuery("informe_envio", $config);
        $conn->prepare($sql);
        $conn->params($config);
        $conn->execute() ? null : $ok = false;
        $conn->closeStmt();
        $id = $conn->getID();

            if(!$ok)
            {
                $conn->rollback();
                $conn->closeConn();
                return -1;
                exit;
            }

        // BITACORA
        $bitacora = array();
        $bitacora["BIT_USUARIO"] = clsCaus::obtenerID();
        $bitacora["BIT_FECHA"] = date("Y-m-d H:i:s");
        $bitacora["BIT_ACCION"] = '1';
        $bitacora["BIT_TABLA"] = "informe_envio";

        $sql = Actions::AgregarQuery("bitacora", $bitacora);
        $conn->prepare($sql);
        $conn->params($bitacora);
        $conn->execute() ? null : $ok = false;
        $conn->closeStmt();
        $conn->getID();

            if(!$ok)
            {
                $conn->rollback();
                $conn->closeConn();
                return -1;
                exit;
            }

        // INSERTA EN EL HISTORIAL DE MUESTRAS CON EL ID DEL INFORME

        $sql = "INSERT INTO historial_muestra values ";
        for($i=0; $i<count($muestras); $i++){
            $sql.= '('.$id.','.$muestras[$i].'),';
        }
        $sql = substr($sql, 0, strlen($sql)-1);
        $conn->prepare($sql);
        $conn->execute() ? null : $ok = false;
        $conn->closeStmt();
        $conn->getID();

            if(!$ok)
            {
                $conn->rollback();
                $conn->closeConn();
                return -1;
                exit;
            }

        // BITACORA
        $bitacora = array();
        $bitacora["BIT_USUARIO"] = clsCaus::obtenerID();
        $bitacora["BIT_FECHA"] = date("Y-m-d H:i:s");
        $bitacora["BIT_ACCION"] = '1';
        $bitacora["BIT_TABLA"] = "historial_muestra";

        $sql = Actions::AgregarQuery("bitacora", $bitacora);
        $conn->prepare($sql);
        $conn->params($bitacora);
        $conn->execute() ? null : $ok = false;
        $conn->closeStmt();
        $conn->getID();

            if(!$ok)
            {
                $conn->rollback();
                $conn->closeConn();
                return -1;
                exit;
            }

        // Cambiar el estado de las muestras de ventanilla a envío
        for($i=0; $i<count($muestras); $i++)
        {
            $situacion = Configuration::enviadaDer;
            $sql='';
            $sql = "UPDATE muestra SET muestra.SIT_ID =".$situacion.", muestra.MUE_ENVIADA_POR='".clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos()."', muestra.MUE_FECHA_ENVIO='".date("Y-m-d")."'
                where muestra.MUE_ID=".$muestras[$i];
            $conn->prepare($sql);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $conn->getID();

            if(!$ok)
            {
                $conn->rollback();
                $conn->closeConn();
                return -1;
                exit;
            }
        }

        // BITACORA
        $bitacora = array();
        $bitacora["BIT_USUARIO"] = clsCaus::obtenerID();
        $bitacora["BIT_FECHA"] = date("Y-m-d H:i:s");
        $bitacora["BIT_ACCION"] = '2';
        $bitacora["BIT_TABLA"] = "muestra";

        $sql = Actions::AgregarQuery("bitacora", $bitacora);
        $conn->prepare($sql);
        $conn->params($bitacora);
        $conn->execute() ? null : $ok = false;
        $conn->closeStmt();
        $conn->getID();

        if($ok)
            $conn->commit ();
        else
        {
            $conn->rollback();
            $id = -1;
        }
        $conn->closeConn();

        return $id;
    }

}

?>
