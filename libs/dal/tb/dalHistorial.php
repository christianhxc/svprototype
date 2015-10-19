<?php
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');



class dalHistorial {
    public $search;
    private $filtroUbicaciones;

    public function __construct($search){
            $this->search = $search;

                // Filtrar los resultados de búsquedas por permisos de procedencia
                // según división sanitaria del país
                $lista = clsCaus::obtenerUbicacionesCascada();
                if (is_array($lista)){
                    foreach ($lista as $elemento)
                    {
                        $temporal = "";
                        if ($elemento[ConfigurationCAUS::AreaSalud] != "")
                            $temporal .= "muestra.idas = '".$elemento[ConfigurationCAUS::AreaSalud]."' ";

                        if ($elemento[ConfigurationCAUS::DistritoSalud] != "")
                            $temporal .= ($temporal != '' ? "and " : "")."muestra.idds = '".$elemento[ConfigurationCAUS::DistritoSalud]."' ";

                        if ($elemento[ConfigurationCAUS::ServicioSalud] != "")
                            $temporal .= ($temporal != '' ? "and " : "")."muestra.idts = '".$elemento[ConfigurationCAUS::ServicioSalud]."' ";

                        $this->filtroUbicaciones .= ($this->filtroUbicaciones != '' ? "or " : "")."(".$temporal.") ";
                    }
                }

                if ($this->filtroUbicaciones != "")
                    $this->filtroUbicaciones = "and (".$this->filtroUbicaciones.")";
    }

    // OBTENER INFORMES DE ENVIO
    public function getAll($a,$b, $c, $data, $tipo, $areas)
    {

        // Forma el filtro para que muestre sólo informes de las áreas
        // a las que el usuario tiene permiso.
        $areasQuery = '';
        $filtro = '';
        foreach($areas as $area)
        {
            $areasQuery.= ' area_analisis.ARE_ANA_ID = '.$area["ARE_ANA_ID"].' OR ';
        }

        if($areasQuery!='')
        {
            $areasQuery = substr($areasQuery,0,strlen($areasQuery)-3);
            $filtro = ' AND (';
            $filtro.= $areasQuery.' ) ';
        }

	$conn = new Connection();
    	$conn->initConn();

        $sql = " SELECT DISTINCT 
                 informe_envio.INF_ENV_ID as id,
                 area_analisis.ARE_ANA_NOMBRE,
                 informe_envio.INF_ENV_TOTAL,
                 informe_envio.INF_ENV_RESTANTES
                FROM
                  informe_envio
                  INNER JOIN historial_muestra ON (informe_envio.INF_ENV_ID = historial_muestra.INF_ENV_ID)
                  INNER JOIN muestra ON (historial_muestra.MUE_ID = muestra.MUE_ID)
                  INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
                  LEFT JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID) 
                  LEFT JOIN evento_seccion es ON evento.EVE_ID = es.EVE_ID AND es.ARE_ANA_ID = muestra.MUE_ARE_ANA_ID 
                  LEFT JOIN area_analisis ON (es.ARE_ANA_ID = area_analisis.ARE_ANA_ID)   
                  WHERE 1 AND informe_envio.INF_ENV_TIPO = ".$tipo.$filtro
        
              .($data["area"] != "" ? ($data["area"]==0?"":" AND  area_analisis.ARE_ANA_ID =".$data["area"]):"")
              .($data["idd"] != "" ? " AND informe_envio.INF_ENV_ID >= ".$data["idd"]: "")
              .($data["idh"] != "" ? " AND informe_envio.INF_ENV_ID <= ".$data["idh"]: "")
              ." ".$this->filtroUbicaciones." "
              ." order by ".$c
              ." limit ".$a.",".$b;        
        //echo $sql;exit;
        $conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetch();
    	$conn->closeConn();
    	return $data;
    }

    public function getCountAll($data, $tipo, $areas)
    {

        // Forma el filtro para que muestre sólo informes de las áreas
        // a las que el usuario tiene permiso.

        $areasQuery = '';
        $filtro = '';
        foreach($areas as $area)
        {
            $areasQuery.= ' area_analisis.ARE_ANA_ID = '.$area["ARE_ANA_ID"].' OR ';
        }

        if($areasQuery!='')
        {
            $areasQuery = substr($areasQuery,0,strlen($areasQuery)-3);
            $filtro = ' AND (';
            $filtro.= $areasQuery.' ) ';
        }

	$conn = new Connection();
    	$conn->initConn();        

        $sql = "SELECT count(*) as cantidad
                FROM (
                  select DISTINCT
                  informe_envio.INF_ENV_ID,
		  area_analisis.ARE_ANA_NOMBRE
                  FROM
                  informe_envio
                  INNER JOIN historial_muestra ON (informe_envio.INF_ENV_ID = historial_muestra.INF_ENV_ID)
                  INNER JOIN muestra ON (historial_muestra.MUE_ID = muestra.MUE_ID)
                  INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
                  LEFT JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID) 
                  LEFT JOIN evento_seccion es ON evento.EVE_ID = es.EVE_ID AND es.ARE_ANA_ID = muestra.MUE_ARE_ANA_ID 
                  LEFT JOIN area_analisis ON (es.ARE_ANA_ID = area_analisis.ARE_ANA_ID)   
                  WHERE 1 AND informe_envio.INF_ENV_TIPO = ".$tipo.$filtro
              ." ".$this->filtroUbicaciones." "
              .($data["area"] != "" ? ($data["area"]==0?"":" AND  area_analisis.ARE_ANA_ID =".$data["area"]):"")
              .($data["idd"] != "" ? " AND informe_envio.INF_ENV_ID >= ".$data["idd"]: "")
              .($data["idh"] != "" ? " AND informe_envio.INF_ENV_ID <= ".$data["idh"]: "").") as temp";

        $conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetchOne();
    	$conn->closeConn();
    	return $data["cantidad"];
    }

    public static function recibirTodas($informe)
    {
        // Obtener cuales son las del informe
        $conn = new Connection();
    	$conn->initConn();
        $conteo = 0;
        $ok = true;

        // Obtiene las que no han sido recibidas y el conteo de estas
        $sql = 'SELECT
                  historial_muestra.MUE_ID,
                  muestra.MUE_TIPO
                FROM
                  informe_envio
                  INNER JOIN historial_muestra ON (informe_envio.INF_ENV_ID = historial_muestra.INF_ENV_ID)
                  INNER JOIN muestra ON (historial_muestra.MUE_ID = muestra.MUE_ID)
                  WHERE informe_envio.INF_ENV_ID='.$informe;
                  //WHERE (muestra.SIT_ID='.Configuration::enviada.' or muestra.SIT_ID = '.Configuration::enviadaDer.') AND informe_envio.INF_ENV_ID='.$informe;

        $conn->prepare($sql);
    	$conn->execute()? null : $ok = false;
    	$muestras = $conn->fetch();

        if(!$ok)
        {
            $conn->rollback();
            $conn->closeConn();
            return -1;
            exit;
        }

        $conteo = count($muestras);
        $actual=0;
        $status = 0;       

        if($conteo>0)
        {
            for($i=0; $i<count($muestras); $i++)
            {
                if($muestras[$i]["MUE_TIPO"]==Configuration::tipoMuestra)
                    $status = Configuration::recibidaAnalisis;
                else if($muestras[$i]["MUE_TIPO"]==Configuration::tipoAlicuotaIngresada)
                    $status = Configuration::enAnalisisDer;

                $sql = "UPDATE muestra SET SIT_ID='".$status."', MUE_RECIBIDA_POR='".clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos()."',
                    MUE_FECHA_RECEPCION_ANALISIS='".date("Y-m-d")."' WHERE MUE_ID=".$muestras[$i]["MUE_ID"];
                $conn->prepare($sql);
                $conn->execute()? $actual++ : $ok = false;

                if(!$ok)
                {
                    $conn->rollback();
                    $conn->closeConn();
                    return -1;
                    exit;
                }
            }

            if($actual == count($muestras))
            {
                // Actualiza el conteo de muestras recibidas del informe
                $sql = "UPDATE informe_envio SET INF_ENV_RESTANTES = '0', INF_ENV_RECIBIDO_POR='".clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos()."' WHERE INF_ENV_ID=".$informe;
                $conn->prepare($sql);
                $conn->execute()? null : $ok = false;
                
                if(!$ok)
                {
                    $conn->rollback();
                    $conn->closeConn();
                    return -1;
                    exit;
                }
            }

            // No se produjo error alguno commit como transacción
            if($conteo == $actual && $ok)
            {
                $conn->commit();
                $conn->closeConn();
                return $informe;
            }
            else
            {
                $conn->rollback();
                $conn->closeConn();
                return -1;
            }
        }
        else
        {
            $conn->rollback();
            $conn->closeConn();
            return -1;
        }
    }

    public static function recibirTodasDerivaciones($informe)
    {
        // Obtener cuales son las del informe
        $conn = new Connection();
    	$conn->initConn();
        $conteo = 0;
        $ok = true;

        // Obtiene las que no han sido recibidas y el conteo de estas
        $sql = 'SELECT
                  historial_muestra.MUE_ID,
                  muestra.MUE_TIPO
                FROM
                  informe_envio
                  INNER JOIN historial_muestra ON (informe_envio.INF_ENV_ID = historial_muestra.INF_ENV_ID)
                  INNER JOIN muestra ON (historial_muestra.MUE_ID = muestra.MUE_ID)
                  WHERE informe_envio.INF_ENV_ID='.$informe;
                  //WHERE (muestra.SIT_ID='.Configuration::enviadaDer.') AND informe_envio.INF_ENV_ID='.$informe;

        $conn->prepare($sql);
    	$conn->execute()? null : $ok = false;
    	$muestras = $conn->fetch();

            if(!$ok)
            {
                $conn->rollback();
                $conn->closeConn();
                return -1;
                exit;
            }

        $conteo = count($muestras);
        $actual=0;
        $status = 0;

        
        if($conteo>0)
        {
            for($i=0; $i<count($muestras); $i++)
            {
                $status = Configuration::enAnalisisDer;
                $sql = "UPDATE muestra SET SIT_ID='".$status."', MUE_RECIBIDA_POR='".clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos()."', MUE_FECHA_RECEPCION_ANALISIS='".date("Y-m-d")."' WHERE MUE_ID=".$muestras[$i]["MUE_ID"];
                $conn->prepare($sql);
                $conn->execute()? $actual++ : $ok = false;

                if(!$ok)
                {
                    $conn->rollback();
                    $conn->closeConn();
                    return -1;
                    exit;
                }
            }

            if($actual == count($muestras))
            {
                // Actualiza el conteo de muestras recibidas del informe
                $sql = "UPDATE informe_envio SET INF_ENV_RESTANTES = '0', INF_ENV_RECIBIDO_POR='".clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos()."' WHERE INF_ENV_ID=".$informe;
                $conn->prepare($sql);
                $conn->execute()? null : $ok = false;

                if(!$ok)
                {
                    $conn->rollback();
                    $conn->closeConn();
                    return -1;
                    exit;
                }
            }

            // No se produjo error alguno commit como transacción
            if($conteo == $actual && $ok)
            {
                $conn->commit();
                $conn->closeConn();
                return $informe;
            }
            else
            {
                $conn->rollback();
                $conn->closeConn();
                return -1;
            }
        }
        else
        {
            $conn->rollback();
            $conn->closeConn();
            return -1;
        }
    }

    public static function getDatos($informe)
    {
        $conn = new Connection();
    	$conn->initConn();
        $sql = "SELECT DISTINCT
                  informe_envio.INF_ENV_ID as numero,
                  DATE_FORMAT(informe_envio.INF_ENV_FECHAHORA,'%d-%m-%Y %h:%i %p') as fecha,
                  informe_envio.INF_ENV_RESPONSABLE as quien,
                  area_analisis.ARE_ANA_NOMBRE as area
                FROM
                  informe_envio
                  INNER JOIN historial_muestra ON (informe_envio.INF_ENV_ID = historial_muestra.INF_ENV_ID)
                  INNER JOIN muestra ON (historial_muestra.MUE_ID = muestra.MUE_ID)
                  INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
                  INNER JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID)
                  INNER JOIN area_analisis ON (evento.ARE_ANA_ID = area_analisis.ARE_ANA_ID)
                  WHERE informe_envio.INF_ENV_ID=".$informe;

        $conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetch();
    	$conn->closeConn();
    	return $data;
    }

    public static function getAllSelectivas($informe, $a, $b)
    {
        $conn = new Connection();
    	$conn->initConn();
        $sql = "SELECT 
                  muestra.MUE_CODIGO_GLOBAL_ANIO as global,
                  muestra.MUE_CODIGO_GLOBAL_NUMERO as gnumero,
                  muestra.MUE_CODIGO_CORRELATIVO_NUMERO as cnumero,
                  muestra.MUE_CODIGO_CORRELATIVO_ALFA as correlativo,
                  evento.EVE_NOMBRE as evento,
                  muestra.MUE_ID as id
                FROM
                  informe_envio
                  INNER JOIN historial_muestra ON (informe_envio.INF_ENV_ID = historial_muestra.INF_ENV_ID)
                  INNER JOIN muestra ON (historial_muestra.MUE_ID = muestra.MUE_ID)
                  INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
                  INNER JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID) WHERE informe_envio.INF_ENV_ID=".$informe.
                  ' AND muestra.SIT_ID='.Configuration::enviada.
                  ' ORDER BY
                  muestra.MUE_CODIGO_GLOBAL_ANIO,
                  muestra.MUE_CODIGO_GLOBAL_NUMERO'
                  ." limit ".$a.",".$b;
        $conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetch();
    	$conn->closeConn();
    	return $data;
    }

    public static function getCountAllSelectivas($informe)
    {
        $conn = new Connection();
    	$conn->initConn();
        $sql = "SELECT count(*) as total
                FROM
                  informe_envio
                  INNER JOIN historial_muestra ON (informe_envio.INF_ENV_ID = historial_muestra.INF_ENV_ID)
                  INNER JOIN muestra ON (historial_muestra.MUE_ID = muestra.MUE_ID)
                  INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
                  INNER JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID) WHERE informe_envio.INF_ENV_ID=".$informe.
                  ' AND muestra.SIT_ID='.Configuration::enviada;
        $conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetchOne();
    	$conn->closeConn();
    	return $data["total"];
    }

    public static function devolver($informe, $muestra)
    {
        $conn = new Connection();
    	$conn->initConn();
        $conteo = 0;
        $ok = true;

        // Cambia el estado de la muestra devuelta
        $sql = "UPDATE muestra
                SET
                  SIT_ID='".Configuration::ventanilla."' WHERE MUE_ID=".$muestra;

        $conn->prepare($sql);
    	$conn->execute()? null : $ok = false;

        if($ok==false)
        {
            $conn->closeConn();
            return -1;
        }

        // Actualiza los contadores del informe 
        $sql = 'UPDATE informe_envio
                SET
                  informe_envio.INF_ENV_TOTAL=informe_envio.INF_ENV_TOTAL-1,
                  informe_envio.INF_ENV_RESTANTES=informe_envio.INF_ENV_RESTANTES-1
                  WHERE informe_envio.INF_ENV_ID='.$informe;

        $conn->prepare($sql);
    	$conn->execute()? null : $ok = false;

        if($ok==false)
        {
            $conn->closeConn();
            return -1;
        }

        // Saca la muestra del informe
        $sql = 'DELETE FROM historial_muestra
            WHERE historial_muestra.INF_ENV_ID='.$informe.' AND historial_muestra.MUE_ID='.$muestra;

        $conn->prepare($sql);
    	$conn->execute()? null : $ok = false;


        if($ok==false)
        {
            $conn->closeConn();
            return -1;
        }
        else
        {
            $conn->commit();
            $conn->closeConn();
            return 1;
        }
    }
}
?>