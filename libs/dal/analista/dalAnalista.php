<?php
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperMuestra.php');
require_once ('libs/caus/clsCaus.php');

class dalAnalista {
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

    public static function Revertir($idMuestra, $tipo)
    {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $status = ($tipo==1?Configuration::recibidaAnalisis:Configuration::enAnalisisDer);
        $sql = 'UPDATE muestra SET SIT_ID ='.$status.' WHERE MUE_ID ='.$idMuestra;
        $conn->prepare($sql);
        $conn->execute() ? null : $ok = false;
        $conn->closeStmt();
        $conn->getID();

        // BITACORA
        $bitacora = array();
        $bitacora["BIT_USUARIO"] = clsCaus::obtenerID();
        $bitacora["BIT_FECHA"] = date("Y-m-d H:i:s");
        $bitacora["BIT_ACCION"] = '3';
        $bitacora["BIT_TABLA"] = "muestra";
        $sql = Actions::AgregarQuery("bitacora", $bitacora);
        $conn->prepare($sql);
        $conn->params($bitacora);
        $conn->execute() ? null : $ok = false;
        $conn->closeStmt();
        $conn->getID();

        // No se produjo error alguno commit como transacción
        if($ok)
        {
            $conn->commit();
            $id = 1;
        }
        else
        {
            $conn->rollback();
            $id = -1;
        }
        $conn->closeConn();
        return $id;
    }

    // Asignar pruebas a muestra
    public static function guardarPruebas($filas, $idMuestra, $idEvento, $tipoMuestra, $estado, $flag, $conclusion, $e, $m)
    {
        // Obtener las pruebas por separado
        $pruebas = explode('*p#@', $filas);
        $completo = array();

        // Obtener cada parte de cada prueba
        foreach($pruebas as $prueba)
        {
            $completo[] = explode('x#*', $prueba);
        }

        if(count($completo)==0)
            return -1;

        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        // BORRA TODAS LAS PRUEBAS ASIGNADAS A LA MUESTRA
        //$noPrueba = helperMuestra::getNopruebaNoresultado(helperMuestra::getIDEventoTipoMuestra($idEvento, $tipoMuestra));
        $sql = 'DELETE FROM analisis_muestra where MUE_ID='.$idMuestra;//.' AND PRU_RES_ID!='.$noPrueba;
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
        $bitacora["BIT_ACCION"] = '3';
        $bitacora["BIT_TABLA"] = "analisis_muestra";
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

        // ACTUALIZA EL ESTADO DE LA MUESTRA A FINALIZADO
        if($estado == Configuration::recibidaAnalisis && $flag==1)
        {
            $sql = 'UPDATE muestra SET SIT_ID ='.Configuration::analisisFinalizado.' WHERE MUE_ID ='.$idMuestra;
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
            $bitacora["BIT_ACCION"] = '3';
            $bitacora["BIT_TABLA"] = "muestra";
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

            // Guardar la conclusión
                $resEspecifico1 = helperMuestra::getTipoSubtipo($idEvento, $conclusion["resultadoFinal"], $conclusion["tipo1"], $conclusion['subtipo1']);
                $resEspecifico2 = helperMuestra::getTipoSubtipo($idEvento, $conclusion["resultadoFinal"], $conclusion["tipo2"], $conclusion['subtipo2']);
                $comentarios = $conclusion["comentario"];

                $conclusion = array();
                $conclusion["TIP_SUB_ID1"] =  $resEspecifico1[0];
                $conclusion["CON_MUE_COMENTARIOS"] = ($comentarios==''?NULL:$comentarios);
                $conclusion["CON_MUE_FECHA"] = date("Y-m-d");
                $conclusion["MUE_ID"] = $idMuestra;
                $conclusion["TIP_SUB_ID2"] =  $resEspecifico2[0];
                $conclusion["CON_MUE_RESPONSABLE"] = clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos();

                // Primero borra la conclusión que ya tiene asignada
                $noDiag = helperMuestra::getNoDiagnostico($idEvento);
                $sql = 'DELETE FROM conclusion_muestra where MUE_ID='.$idMuestra;
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


                // Agrega la conclusión
                $sql = Actions::AgregarQuery("conclusion_muestra", $conclusion);
                $conn->prepare($sql);
                $conn->params($conclusion);
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
                $bitacora["BIT_TABLA"] = "conclusion_muestra";
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
        }

        // Actualiza el estado y motivo de la muestra
        if($e != Configuration::idEstado){
            $est_mot = helperMuestra::estadoMotivo($e, $m);
            $est_mot = $est_mot[0];
        }
        else
            $est_mot = Configuration::idEstadoMotivo;
        $sql = 'UPDATE muestra SET EST_MOT_ID ='.$est_mot.' WHERE MUE_ID ='.$idMuestra;
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

        if(($completo[0][0]!=""))
        {
            foreach($completo as $prueba)
            {
                $mueID = $prueba[0];
                $p = $prueba[1];
                $r = $prueba[2];
                $f = helperString::toDate($prueba[3]);
                $c = $prueba[4];
                $prueba_resultado = helperMuestra::getPruebaResultadoGeneral($idEvento, $tipoMuestra, $p, $r);

                // Inserta prueba a prueba
                $pruebaQuery = array();
                $pruebaQuery["MUE_ID"] = $mueID;
                $pruebaQuery["ANA_MUE_COMENTARIOS"] = trim($c);
                $pruebaQuery["ANA_MUE_ASIGNADO_POR"] = clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos();
                $pruebaQuery["ANA_MUE_FECHA"] = $f;
                $pruebaQuery["PRU_RES_ID"] = $prueba_resultado;
                $sql = Actions::AgregarQuery("analisis_muestra", $pruebaQuery);
                $conn->prepare($sql);
                $conn->params($pruebaQuery);
                $conn->execute() ? null : $ok = false;
                $conn->closeStmt();

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
                $bitacora["BIT_TABLA"] = "analisis_muestra";
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
            }
        }
        else
        {
            // AGREGA NO PRUEBA
            // PORQUE BORRO TODAS LAS PRUEBAS
            $noprueba = array();
            $noprueba["MUE_ID"] = $idMuestra;
            $noprueba["PRU_RES_ID"] = helperMuestra::getNopruebaNoresultado(helperMuestra::getIDEventoTipoMuestra($idEvento, $tipoMuestra));
            $sql = Actions::AgregarQuery("analisis_muestra", $noprueba);
            $conn->prepare($sql);
            $conn->params($noprueba);
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

        // No se produjo error alguno commit como transacción
        if($ok)
            $conn->commit();
        else
        {
            $conn->rollback();
            $id = -1;
        }
        $conn->closeConn();
        return $id;
    }

    // Asignar pruebas a derivación
    public static function guardarPruebasDerivacion($filas, $idMuestra, $idEvento, $tipoMuestra, $estado, $flag, $conclusion, $e, $m)
    {
        // Obtener las pruebas por separado
        $pruebas = explode('*p#@', $filas);
        $completo = array();

        // Obtener cada parte de cada prueba
        foreach($pruebas as $prueba)
        {
            $completo[] = explode('x#*', $prueba);
        }

        if(count($completo)==0)
            return -1;

        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        
        // BORRA TODAS LAS PRUEBAS ASIGNADAS A LA MUESTRA
        $noPrueba = helperMuestra::getNopruebaNoresultado(helperMuestra::getIDEventoTipoMuestra($idEvento, $tipoMuestra));
        //echo $noPrueba;exit;
       // if($noPrueba!=""){
            $sql = 'DELETE FROM analisis_muestra where MUE_ID='.$idMuestra;//.' AND PRU_RES_ID!='.$noPrueba;
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
     //   }

        // BITACORA
        $bitacora = array();
        $bitacora["BIT_USUARIO"] = clsCaus::obtenerID();
        $bitacora["BIT_FECHA"] = date("Y-m-d H:i:s");
        $bitacora["BIT_ACCION"] = '3';
        $bitacora["BIT_TABLA"] = "analisis_muestra";
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


        // ACTUALIZA EL ESTADO DE LA MUESTRA A FINALIZADO
        if($estado == Configuration::enAnalisisDer && $flag==1)
        {
            $sql = 'UPDATE muestra SET SIT_ID ='.Configuration::analisisFinalizadoDer.' WHERE MUE_ID ='.$idMuestra;
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
            $bitacora["BIT_ACCION"] = '3';
            $bitacora["BIT_TABLA"] = "muestra";
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
            
            // Guardar la conclusión
                $resEspecifico1 = helperMuestra::getTipoSubtipo($idEvento, $conclusion["resultadoFinal"], $conclusion["tipo1"], $conclusion['subtipo1']);
                $resEspecifico2 = helperMuestra::getTipoSubtipo($idEvento, $conclusion["resultadoFinal"], $conclusion["tipo2"], $conclusion['subtipo2']);
                $comentarios = $conclusion["comentario"];

                $conclusion = array();
                $conclusion["TIP_SUB_ID1"] =  $resEspecifico1[0];
                $conclusion["CON_MUE_COMENTARIOS"] = ($comentarios==''?NULL:$comentarios);
                $conclusion["CON_MUE_FECHA"] = date("Y-m-d");
                $conclusion["MUE_ID"] = $idMuestra;
                $conclusion["TIP_SUB_ID2"] =  $resEspecifico2[0];
                $conclusion["CON_MUE_RESPONSABLE"] = clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos();

                // Primero borra la conclusión que ya tiene asignada
                $noDiag = helperMuestra::getNoDiagnostico($idEvento);
                $sql = 'DELETE FROM conclusion_muestra where MUE_ID='.$idMuestra;
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

                // Agrega la conclusión
                $sql = Actions::AgregarQuery("conclusion_muestra", $conclusion);
                $conn->prepare($sql);
                $conn->params($conclusion);
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
                $bitacora["BIT_TABLA"] = "conclusion_muestra";
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
        }

        // Actualiza el estado y motivo de la derivación
        if($e != Configuration::idEstado){
            $est_mot = helperMuestra::estadoMotivo($e, $m);
            $est_mot = $est_mot[0];
        }
        else
            $est_mot = Configuration::idEstadoMotivo;
        $sql = 'UPDATE muestra SET EST_MOT_ID ='.$est_mot.' WHERE MUE_ID ='.$idMuestra;
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

        if(($completo[0][0]!=""))
        {
            foreach($completo as $prueba)
            {
                $mueID = $prueba[0];
                $p = $prueba[1];
                $r = $prueba[2];
                $f = helperString::toDate($prueba[3]);
                $c = $prueba[4];
                $prueba_resultado = helperMuestra::getPruebaResultadoGeneral($idEvento, $tipoMuestra, $p, $r);

                // Inserta prueba a prueba
                $pruebaQuery = array();
                $pruebaQuery["MUE_ID"] = $mueID;
                $pruebaQuery["ANA_MUE_COMENTARIOS"] = trim($c);
                $pruebaQuery["ANA_MUE_ASIGNADO_POR"] = clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos();
                $pruebaQuery["ANA_MUE_FECHA"] = $f;
                $pruebaQuery["PRU_RES_ID"] = $prueba_resultado;
                $sql = Actions::AgregarQuery("analisis_muestra", $pruebaQuery);
                $conn->prepare($sql);
                $conn->params($pruebaQuery);
                $conn->execute() ? null : $ok = false;
                $conn->closeStmt();

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
                $bitacora["BIT_TABLA"] = "analisis_muestra";
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

            }
        }
        else
        {
            // AGREGA NO PRUEBA
            // PORQUE BORRO TODAS LAS PRUEBAS
            $noprueba = array();
            $noprueba["MUE_ID"] = $idMuestra;
            $noprueba["PRU_RES_ID"] = helperMuestra::getNopruebaNoresultado(helperMuestra::getIDEventoTipoMuestra($idEvento, $tipoMuestra));
            $sql = Actions::AgregarQuery("analisis_muestra", $noprueba);
            $conn->prepare($sql);
            $conn->params($noprueba);
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
        // No se produjo error alguno commit como transacción
        if($ok)
            $conn->commit();
        else
        {
            $conn->rollback();
            $id = -1;
        }
        $conn->closeConn();
        return $id;

}

    // ANULA LA MUESTRA
    public static function Borrar($id){
        $ok = true;
        $conn = new Connection();
        $conn->initConn();

        $conn->begin();

        // Determina si la muestra posee derivaciones
        $derivaciones = helperMuestra::derivacionesAsignadas($id);
        $estadoDerivacion = helperMuestra::situacionMuestra($id);
        $pruebas = helperMuestra::pruebasAsignadas($id);
        $conclusion = helperMuestra::conclusionAsignada($id);

        // Puede anular una muestra ssi no tiene derivaciones y estado no es finalizado
        if($derivaciones[0]==0 && $estadoDerivacion[0]!=Configuration::analisisFinalizado && $pruebas[0]==0 && $conclusion[0]==0)
        {
            // Actualiza el estado de la muestra a anulado
            $filtro = array();
            $actualizar = array();
            $filtro["MUE_ID"] = $id;
            $actualizar["MUE_ACTIVA"] = Configuration::anulada;
            $sql = Actions::ActualizarQuery("muestra", $actualizar, $filtro);
            $actualizar["MUE_ID"] = $id;

            $conn->prepare($sql);
            $conn->params($actualizar);
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
            $bitacora["BIT_ACCION"] = '2';
            $bitacora["BIT_TABLA"] = "muestra";

            $sql = Actions::AgregarQuery("bitacora", $bitacora);
            $conn->prepare($sql);
            $conn->params($bitacora);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $id = $conn->getID();

            if ($ok){
                    $conn->commit();
            }else{
                    $conn->rollback();
                    $id = -1;
            }
        }
        else
            $id=-1;
        $conn->closeConn();
        return $id;
    }

    public static function Modificar($data, $idMuestra){
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        // ---------------------------------------------------------------
        // Individuo
        // ---------------------------------------------------------------
        $individuo = helperMuestra::dataIndividuo($data);

        // NUEVO = -1
        if($data["individuo"]["id_individuo"]=='-1')
        {
            $sql = Actions::AgregarQuery("individuo", $individuo);
            // Ingresa datos de individuo
            $conn->prepare($sql);
            $conn->params($individuo);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $idIndividuo = $conn->getID();

            // BITACORA
            $bitacora = array();
            $bitacora["BIT_USUARIO"] = clsCaus::obtenerID();
            $bitacora["BIT_FECHA"] = date("Y-m-d H:i:s");
            $bitacora["BIT_ACCION"] = '1';
            $bitacora["BIT_TABLA"] = "individuo";

            $sql = Actions::AgregarQuery("bitacora", $bitacora);
            $conn->prepare($sql);
            $conn->params($bitacora);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $id = $conn->getID();
        }
        else
        {
            // ACTUALIZA CIERTOS DATOS DEL INDIVIDUO SELECCIONADO
            $actualizar = array();
            $actualizar["IND_HISTORIA_CLINICA"] = trim($individuo["IND_HISTORIA_CLINICA"]);
            $actualizar["IND_EDAD"] = $individuo["IND_EDAD"];
            $actualizar["IND_TIPO_EDAD"] = $individuo["IND_TIPO_EDAD"];
            $actualizar["IND_FECHA_NACIMIENTO"] = $individuo["IND_FECHA_NACIMIENTO"];
            $actualizar["IND_DIRECCION"] = trim($individuo["IND_DIRECCION"]);
            $actualizar["IND_DIRECCION_DISPONIBLE"] = $individuo["IND_DIRECCION_DISPONIBLE"];
            $actualizar["iddep"] = $individuo["iddep"];
            $actualizar["idmun"] = $individuo["idmun"];
            $actualizar["idlp"] = $individuo["idlp"];

            // Actualiza datos de individuo
            $filtro = array();
            $filtro["IND_ID"] = $data["individuo"]["id_individuo"];
            $sql = Actions::ActualizarQuery("individuo", $actualizar, $filtro);
            $actualizar["IND_ID"] = $data["individuo"]["id_individuo"];

            $conn->prepare($sql);
            $conn->params($actualizar);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $conn->getID();

            // BITACORA
            $bitacora = array();
            $bitacora["BIT_USUARIO"] = clsCaus::obtenerID();
            $bitacora["BIT_FECHA"] = date("Y-m-d H:i:s");
            $bitacora["BIT_ACCION"] = '2';
            $bitacora["BIT_TABLA"] = "individuo";

            $sql = Actions::AgregarQuery("bitacora", $bitacora);
            $conn->prepare($sql);
            $conn->params($bitacora);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $id = $conn->getID();
        }

        // DATOS DE LA MUESTRA
        // Correlativo por evento anterior
        $codigoEventoAnterior = helperMuestra::correlativoAnterior($idMuestra);
        $idEventoAnterior = helperMuestra::eventoAnterior($idMuestra);
        $codigoGlobalAnterior = helperMuestra::globalAnterior($idMuestra);

        $muestra = array();
        // Conserva código global
        $muestra["MUE_CODIGO_GLOBAL_ANIO"] = $codigoGlobalAnterior[0]["mue_codigo_global_anio"];
        $muestra["MUE_CODIGO_GLOBAL_NUMERO"] = $codigoGlobalAnterior[0]["mue_codigo_global_numero"];

        // Código de evento cambiará o se conservará
        if($data["muestra"]["evento"]["id_evento"]!=$idEventoAnterior[0]){
            // YA NO TIENE EL MISMO CODIGO DEL EVENTO
            $sql = Actions::AgregarQuery("bitacora", $bitacora);
            $conn->prepare($sql);
            $conn->params($bitacora);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $conn->getID();

            // Obtener el nuevo codigo de evento que se asignaría
            $codigoEvento = helperMuestra::getCodigoEvento($data["muestra"]["evento"]["id_evento"]);
            $muestra["MUE_CODIGO_CORRELATIVO_NUMERO"] = ((int)$codigoEvento[0]["eve_correlativo"])+1;
            $muestra["MUE_CODIGO_CORRELATIVO_ALFA"] = $codigoEvento[0]["eve_codigo"];

            // Incrementar el codigo de evento actual en uno
            $sql = helperMuestra::increaseCodigoMuestra($data["muestra"]["evento"]["id_evento"]);
            $conn->prepare($sql);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $conn->getID();

            // BITACORA
            $bitacora = array();
            $bitacora["BIT_USUARIO"] = clsCaus::obtenerID();
            $bitacora["BIT_FECHA"] = date("Y-m-d H:i:s");
            $bitacora["BIT_ACCION"] = '2';
            $bitacora["BIT_TABLA"] = "evento";

            $sql = Actions::AgregarQuery("bitacora", $bitacora);
            $conn->prepare($sql);
            $conn->params($bitacora);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $conn->getID();
        }
        else
        {
            // Se conserva el código de muestra porque no cambió
            $muestra["MUE_CODIGO_CORRELATIVO_NUMERO"] = $codigoEventoAnterior[0]["mue_codigo_correlativo_numero"];
            $muestra["MUE_CODIGO_CORRELATIVO_ALFA"] = $codigoEventoAnterior[0]["mue_codigo_correlativo_alfa"];
        }

        $muestra["MUE_FECHA_INICIO"] = (!isset($data["muestra"]["fecha_inicio_sintomas"])? NULL:helperString::toDate($data["muestra"]["fecha_inicio_sintomas"]));
        $muestra["MUE_FECHA_TOMA"] = (!isset($data["muestra"]["fecha_toma"])? NULL:helperString::toDate($data["muestra"]["fecha_toma"]));
        $muestra["MUE_FECHA_RECEPCION"] = (!isset($data["muestra"]["fecha_recepcion"])? NULL:helperString::toDate($data["muestra"]["fecha_recepcion"]));

//        $muestra["MUE_RECHAZADA"] =($data["muestra"]["no_rechazada"]=='on'?'0':'1');
//        $muestra["RAZ_REC_ID"] = $data["muestra"]["razon_rechazo"];
        $muestra["MUE_COMENTARIOS"] = trim($data["muestra"]["comentarios_adicionales"]);

        // REQUIERE DE LOGIN
        $muestra["MUE_REFERIDA_POR"] = (!isset($data["muestra"]["referida_por"])?NULL:trim($data["muestra"]["referida_por"]));

        if($data["individuo"]["id_individuo"]=='-1')
            $muestra["IND_ID"] = $idIndividuo;
        else
            $muestra["IND_ID"] = $data["individuo"]["id_individuo"];

        $muestra["MUE_CARGA_VIRAL"] =($data["muestra"]["carga_viral_check"]=='on'?'1':'2');

        $muestra["MUE_ANTECEDENTES_INTERES"] = (!isset($data["muestra"]["antecedentes"])?NULL:trim($data["muestra"]["antecedentes"]));

        $tipo_muestra = helperMuestra::getIDEventoTipoMuestra($data["muestra"]["evento"]["id_evento"], $data["muestra"]["tipo"]);
        $muestra["TIP_MUE_EVE_ID"] = $tipo_muestra[0];

        $muestra["MUE_RESULTADO_EXTERNO"] = (!isset($data["muestra"]["resultados_externos"])?NULL:trim($data["muestra"]["resultados_externos"]));
        $muestra["MUE_ACTIVA"] = 1;
        $muestra["EST_MOT_ID"] = 1;
        $muestra["MUE_SEMANA_EPI"] = (!isset($data["muestra"]["semana"])?0:$data["muestra"]["semana"]);
        $muestra["MUE_INGRESADA_POR"] = clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos();
        $muestra["MUE_SERVICIO_HOSPITALAR"] = (!isset($data["muestra"]["servicio"])?0:$data["muestra"]["servicio"]);
        $muestra["MUE_SEROLOGICA"] =($data["muestra"]["encuesta_serologica_check"]=='on'?'1':'2');

        // PROCEDENCIA
        $muestra["idas"] = $data["muestra"]["area_salud"];
        $muestra["idds"] = $data["muestra"]["distrito"];
        $muestra["MUE_DISTRITO_DISPONIBLE"] =($data["muestra"]["no_distrito_muestra"]=='on'?'0':'1');
        $muestra["idtss"] = $data["muestra"]["tipo_establecimiento"];
        $muestra["MUE_TIPO_SERVICIO_DISPONIBLE"] =($data["muestra"]["no_establecimiento"]=='on'?'0':'1');
        $muestra["idts"] = $data["muestra"]["establecimiento"];
        $muestra["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"] = $muestra["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"] = (!isset($data["muestra"]["otro_establecimiento"])?NULL:trim($data["muestra"]["otro_establecimiento"]));


        if($data["muestra"]["no_establecimiento"]=='on')
            $muestra["MUE_DESCRIPCION_SERVICIO_DISPONIBLE"] = '0';
        else
        {
            if($muestra["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"]!="")
            {
                $muestra["MUE_DESCRIPCION_SERVICIO_DISPONIBLE"] ='2';
                $muestra["idts"] = 0;
            }
            else
                $muestra["MUE_DESCRIPCION_SERVICIO_DISPONIBLE"] ='1';
        }

        $muestra["MUE_DESCRIPCION_SERVICIO_DISPONIBLE"] =($data["muestra"]["no_establecimiento"]=='on'?'0':'1');
        $muestra["MUE_SITUACION_HOSPITALAR"] = $data["muestra"]["hospitalario"];

        // Si es dengue calcula la tendencia serológica
        if($data["muestra"]["evento"]["id_evento"]== Configuration::dengue)
            $muestra["MUE_TENDENCIA"] = helperString::calcularTendencia($inicio, $toma);

        // Actualiza datos de muestra
            $filtro = array();
            $filtro["MUE_ID"] = $idMuestra;
            $sql = Actions::ActualizarQuery("muestra", $muestra, $filtro);
            $muestra["MUE_ID"] = $idMuestra;

            $conn->prepare($sql);
            $conn->params($muestra);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $conn->getID();

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

        if ($ok){
                $conn->commit();
        }else{
                $conn->rollback();
                $id = -1;
        }

        $conn->closeConn();
        return $id;
    }

    public static function situacionDerivacion($derivacion)
    {
        $conn = new Connection();
        $conn->initConn();
        $sql = 'SELECT SIT_ID
        FROM
          muestra
          where muestra.MUE_ID='.$derivacion;

        $conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetch();
    	$conn->closeConn();
    	return $data;
    }

    public static function borrarDerivacion($derivacion)
    {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $sql = 'UPDATE muestra
        SET MUE_ACTIVA = 0
          where muestra.MUE_ID='.$derivacion;

        $conn->prepare($sql);
    	$conn->execute()? null:$ok=false;

        if(!$ok)
            $conn->rollback();
        else
        {
            $conn->commit();
            $id = 1;
        }
    	$conn->closeConn();
    	return $id;
    }

    public static function getAlicuotasMuestra($idMuestra)
    {
        $conn = new Connection();
        $conn->initConn();
        $sql = 'SELECT
          muestra.MUE_ID as muestra,
          muestra.MUE_CODIGO_CORRELATIVO_NUMERO as correlativo,
          muestra.MUE_CODIGO_CORRELATIVO_ALFA as alfa,
          muestra.MUE_CODIGO_GLOBAL_ANIO as anio,
          muestra.MUE_CODIGO_GLOBAL_NUMERO as global,
          evento.EVE_NOMBRE as evento,
          evento.EVE_Id as eveId,
          area_analisis.ARE_ANA_NOMBRE as area,
          area_analisis.ARE_ANA_ID as aId,
          muestra.MUE_TIPO as tipo,
          muestra.SIT_ID as status,
          muestra.MUE_RECHAZADA as recha
        FROM
          muestra
          INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
          LEFT JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID)
          LEFT JOIN evento_seccion es ON evento.EVE_ID = es.EVE_ID AND es.ARE_ANA_ID = muestra.MUE_ARE_ANA_ID
          LEFT JOIN area_analisis ON (es.ARE_ANA_ID = area_analisis.ARE_ANA_ID)
          where muestra.MUE_ACTIVA=1
          AND muestra.MUE_PADRE='.$idMuestra.' AND muestra.MUE_TIPO='.Configuration::tipoAlicuotaIngresada;
        //echo $sql;exit;
        $conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetch();
    	$conn->closeConn();
    	return $data;
    }


    public static function getDerivacionesMuestra($idMuestra)
    {
        $conn = new Connection();
        $conn->initConn();
        $sql = 'SELECT
          muestra.MUE_ID as muestra,
            MUE_CODIGO_GLOBAL_ANIO as anio,
            MUE_CODIGO_GLOBAL_NUMERO as global,
          muestra.MUE_CODIGO_CORRELATIVO_NUMERO as correlativo,
          muestra.MUE_CODIGO_CORRELATIVO_ALFA as alfa,
          evento.EVE_NOMBRE as evento,
          evento.EVE_Id as eveId,
          area_analisis.ARE_ANA_NOMBRE as area,
          area_analisis.ARE_ANA_ID as aId,
          muestra.MUE_TIPO as tipo,
          muestra.SIT_ID as status,
          muestra.MUE_RECHAZADA as recha
        FROM
          muestra
          INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
          LEFT JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID)
          LEFT JOIN evento_seccion es ON evento.EVE_ID = es.EVE_ID AND es.ARE_ANA_ID = muestra.MUE_ARE_ANA_ID
          LEFT JOIN area_analisis ON (es.ARE_ANA_ID = area_analisis.ARE_ANA_ID) 
          where muestra.MUE_ACTIVA=1
          AND muestra.MUE_PADRE='.$idMuestra.' AND muestra.MUE_TIPO >='.Configuration::tipoAlicuotaIngresada;

        $conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetch();
    	$conn->closeConn();
    	return $data;
    }

    public static function yaExisteDerivacion($idMuestra, $evento, $tipo)
    {
        $conn = new Connection();
        $conn->initConn();

        $sql ='SELECT
                count(*)
                FROM
                  muestra
                  INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
                  INNER JOIN tipo_muestra ON (tipo_muestra_evento.TIPO_MUE_ID = tipo_muestra.TIP_MUE_ID)
                  WHERE muestra.MUE_ACTIVA = 1 AND muestra.MUE_PADRE ='.$idMuestra.' AND  tipo_muestra_evento.EVE_ID ='.$evento.' AND tipo_muestra.TIP_MUE_ID ='.$tipo;

        $conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetch();
    	$conn->closeConn();
    	return $data;
    }


    public static function agregarAlicuota($idMuestra, $seccion, $evento, $tipo, $rechazada, $razon)
    {
        // Comparte mismos datos que muestra padre
        $data = array();
        $data = helperMuestra::getDatosMuestra($idMuestra);

        $sql = 'INSERT INTO muestra(
            MUE_CODIGO_GLOBAL_ANIO,
            MUE_CODIGO_GLOBAL_NUMERO,
            MUE_CODIGO_CORRELATIVO_NUMERO,
            MUE_CODIGO_CORRELATIVO_ALFA,
            MUE_FECHA_INICIO,
            MUE_FECHA_TOMA,
            MUE_FECHA_RECEPCION,
            MUE_RECHAZADA,
            RAZ_REC_ID,
            MUE_COMENTARIOS,
            MUE_INGRESADA_POR,
            MUE_REFERIDA_POR,
            IND_ID,
            MUE_CARGA_VIRAL,
            MUE_DONADOR,
            MUE_SEROLOGICA,
            SIT_ID,
            TIP_MUE_EVE_ID,'
            .'MUE_RESULTADO_EXTERNO,
            MUE_ACTIVA,
            EST_MOT_ID,'
            .'MUE_SEMANA_EPI,
            MUE_INGRESADA_POR_ID,
            MUE_SERVICIO_HOSPITALAR,
            idas,
            idds,
            MUE_DISTRITO_DISPONIBLE,
            idtss,
            MUE_ANTECEDENTES_INTERES,
            MUE_TIPO_SERVICIO_DISPONIBLE,
            idts,
            MUE_DESCRIPCION_SERVICIO_DISPONIBLE,
            MUE_OTRO_ESTABLECIMIENTO_NOMBRE,
            MUE_SITUACION_HOSPITALAR,
            MUE_TENDENCIA,
            MUE_PADRE,
            MUE_TIPO,
            MUE_DERIVADA_POR,
            DER_FECHA,
            MUE_FECHA_INGRESO_SISTEMA,
            IND_ES_HUMANO,
            IND_PRIMER_NOMBRE,
            IND_SEGUNDO_NOMBRE,
            IND_PRIMER_APELLIDO,
            IND_SEGUNDO_APELLIDO,
            IND_IDENTIFICADOR,
            IND_IDENTIFICADOR_DISPONIBLE,
            IND_HISTORIA_CLINICA,
            IND_EDAD,
            IND_TIPO_EDAD,
            IND_FECHA_NACIMIENTO,
            IND_SEXO,
            TIP_VIG_ID,
            IND_ANONIMO,
            IND_DIRECCION,
            IND_DIRECCION_DISPONIBLE,
            iddep,
            idmun,
            idlp,
            IND_CASADA,
            IND_LOCALIDAD_DISPONIBLE,
            MUE_ARE_ANA_ID
            ) VALUES (';

        $sql.= "'".$data["MUE_CODIGO_GLOBAL_ANIO"]."', ";
        $sql.= "'".$data["MUE_CODIGO_GLOBAL_NUMERO"]."', ";
        $codigoEvento = helperMuestra::getCodigoEvento($evento);
        $numero = ((int)$codigoEvento[0]["eve_correlativo"])+1;
        $alfa = $codigoEvento[0]["eve_codigo"];

        $sql.= "'".$numero."', ";
        $sql.= "'".$alfa."', ";
        $sql.= ($data["MUE_FECHA_INICIO"]==""? mysql_escape_string("NULL") : "'".$data["MUE_FECHA_INICIO"]."'").", ";
        $sql.= ($data["MUE_FECHA_TOMA"]==""?mysql_escape_string("NULL") : "'".$data["MUE_FECHA_TOMA"]."'").", ";
        $sql.= ($data["MUE_FECHA_RECEPCION"]==""?mysql_escape_string("NULL") : "'".$data["MUE_FECHA_RECEPCION"]."'").", ";
        $sql.= "'".$rechazada."', ";
        $sql.= "'".$razon."', ";
        $sql.= "'".$data["MUE_COMENTARIOS"]."', ";
        $sql.= "'".clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos()."', ";
        $sql.= "'".$data["MUE_REFERIDA_POR"]."', ";
        $sql.= "'".$data["IND_ID"]."', ";
        $sql.= "'".$data["MUE_CARGA_VIRAL"]."', ";
        $sql.= "'".$data["MUE_DONADOR"]."', ";
        $sql.= "'".$data["MUE_SEROLOGICA"]."', ";
        $sql.= "'".Configuration::ventanilla."', ";
        $tip =  helperMuestra::getIDEventoTipoMuestra($evento, $tipo);
        $sql.= "'".$tip[0]."', ";
        $sql.= "'".$data["MUE_RESULTADO_EXTERNO"]."', ";
        $sql.= "'1', ";
        $sql.= "'".Configuration::idEstadoMotivo."', ";
        $sql.= "'".$data["MUE_SEMANA_EPI"]."', ";
        $sql.= "'".clsCaus::obtenerID()."', ";
        $sql.= "'".$data["MUE_SERVICIO_HOSPITALAR"]."', ";
        $sql.= "'".$data["idas"]."', ";
        $sql.= "'".$data["idds"]."', ";
        $sql.= "'".$data["MUE_DISTRITO_DISPONIBLE"]."', ";
        $sql.= "'".$data["idtss"]."', ";
        $sql.= "'".$data["MUE_ANTECEDENTES_INTERES"]."', ";
        $sql.= "'".$data["MUE_TIPO_SERVICIO_DISPONIBLE"]."', ";
        $sql.= "'".$data["idts"]."', ";
        $sql.= "'".$data["MUE_DESCRIPCION_SERVICIO_DISPONIBLE"]."', ";
        $sql.= "'".$data["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"]."', ";
        $sql.= "'".$data["MUE_SITUACION_HOSPITALAR"]."', ";

        if($evento == Configuration::dengue)
            $tendencia = helperString::calcularTendencia( $data['MUE_FECHA_INICIO'], $data['MUE_FECHA_TOMA']);
        else
            $tendencia = 'No aplica';
        $sql.= "'".$tendencia."', ";
        $sql.= "'".$idMuestra."', ";
        $sql.= "'".Configuration::tipoAlicuotaIngresada."', ";
        $sql.= "'".clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos()."', ";
        $sql.= "'".date("Y-m-d")."',";
        $sql.= "'".date("Y-m-d")."',";

        // DATOS DEL INDIVIDUO
        $sql.="'". $data["IND_ES_HUMANO"]."', ";
        $sql.="'". $data["IND_PRIMER_NOMBRE"]."', ";
        $sql.="'". $data["IND_SEGUNDO_NOMBRE"]."', ";
        $sql.="'". $data["IND_PRIMER_APELLIDO"]."', ";
        $sql.="'". $data["IND_SEGUNDO_APELLIDO"]."', ";
        $sql.="'". $data["IND_IDENTIFICADOR"]."', ";
        $sql.="'". $data["IND_IDENTIFICADOR_DISPONIBLE"]."', ";
        $sql.="'". $data["IND_HISTORIA_CLINICA"]."', ";
        $sql.="'". $data["IND_EDAD"]."', ";
        $sql.="'". $data["IND_TIPO_EDAD"]."', ";
        $sql.="'". $data["IND_FECHA_NACIMIENTO"]."', ";
        $sql.="'". $data["IND_SEXO"]."', ";
        $sql.="'". $data["TIP_VIG_ID"]."', ";
        $sql.="'". $data["IND_ANONIMO"]."', ";
        $sql.="'". $data["IND_DIRECCION"]."', ";
        $sql.="'". $data["IND_DIRECCION_DISPONIBLE"]."', ";
        $sql.="'". $data["iddep"]."', ";
        $sql.="'". $data["idmun"]."', ";
        $sql.="'". $data["idlp"]."', ";
        $sql.="'". $data["IND_CASADA"]."', ";
        $sql.="'". $data["IND_LOCALIDAD_DISPONIBLE"]."', ";
        $sql.=$seccion;
        $sql.=")";
        //echo $sql;exit;
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

            $conn->prepare($sql);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $id = $conn->getID();

            // BITACORA
            $bitacora = array();
            $bitacora["BIT_USUARIO"] =  clsCaus::obtenerID();
            $bitacora["BIT_FECHA"] = date("Y-m-d H:i:s");
            $bitacora["BIT_ACCION"] = '1';
            $bitacora["BIT_TABLA"] = "muestra";

            $sql = Actions::AgregarQuery("bitacora", $bitacora);
            $conn->prepare($sql);
            $conn->params($bitacora);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $conn->getID();

        // Actualiza el codigo de la muestra
            $sql = helperMuestra::increaseCodigoMuestra($evento);
            $conn->prepare($sql);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $conn->getID();

            // BITACORA
            $bitacora = array();
            $bitacora["BIT_USUARIO"] =  clsCaus::obtenerID();
            $bitacora["BIT_FECHA"] = date("Y-m-d H:i:s");
            $bitacora["BIT_ACCION"] = '2';
            $bitacora["BIT_TABLA"] = "evento";

            $sql = Actions::AgregarQuery("bitacora", $bitacora);
            $conn->prepare($sql);
            $conn->params($bitacora);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $conn->getID();

        // Agrega no prueba y no diagnóstico por default a la alicuota
            // NO PRUEBA
            $noprueba = array();
            $noprueba["MUE_ID"] = $id;
            $noprueba["PRU_RES_ID"] = helperMuestra::getNopruebaNoresultado($tipo);
            $sql = Actions::AgregarQuery("analisis_muestra", $noprueba);
            $conn->prepare($sql);
            $conn->params($noprueba);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $conn->getID();

            // NO DIAGNOSTICO
            $nodiagnostico = array();
            $nodiagnostico["TIP_SUB_ID1"] = helperMuestra::getNoDiagnostico($evento);
            $nodiagnostico["MUE_ID"] = $id;
            $nodiagnostico["TIP_SUB_ID2"] = $nodiagnostico["TIP_SUB_ID1"];
            $sql = Actions::AgregarQuery("conclusion_muestra", $nodiagnostico);
            $conn->prepare($sql);
            $conn->params($nodiagnostico);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $conn->getID();


        if(!$ok){
            $conn->rollback();
            $id = -1;
        }
        else{
            $conn->commit();
            $id = 1;
        }
        $conn->closeConn();
        return $id;
    }

    public static function agregarDerivacion($idMuestra, $seccion, $evento, $tipo)
    {
        // Comparte mismos datos que muestra padre
        $data = array();
        $data = helperMuestra::getDatosMuestra($idMuestra);

        $sql = 'INSERT INTO muestra(
            MUE_CODIGO_GLOBAL_ANIO,
            MUE_CODIGO_GLOBAL_NUMERO,
            MUE_CODIGO_CORRELATIVO_NUMERO,
            MUE_CODIGO_CORRELATIVO_ALFA,
            MUE_FECHA_INICIO,
            MUE_FECHA_TOMA,
            MUE_FECHA_RECEPCION,
            MUE_RECHAZADA,
            RAZ_REC_ID,
            MUE_COMENTARIOS,
            MUE_INGRESADA_POR,
            MUE_REFERIDA_POR,
            IND_ID,
            MUE_CARGA_VIRAL,
            MUE_DONADOR,
            MUE_SEROLOGICA,
            SIT_ID,
            TIP_MUE_EVE_ID,
            MUE_RESULTADO_EXTERNO,
            MUE_ACTIVA,
            EST_MOT_ID,
            MUE_SEMANA_EPI,
            MUE_INGRESADA_POR_ID,
            MUE_SERVICIO_HOSPITALAR,
            idas,
            idds,
            MUE_DISTRITO_DISPONIBLE,
            idtss,
            MUE_ANTECEDENTES_INTERES,
            MUE_TIPO_SERVICIO_DISPONIBLE,
            idts,
            MUE_DESCRIPCION_SERVICIO_DISPONIBLE,
            MUE_OTRO_ESTABLECIMIENTO_NOMBRE,
            MUE_SITUACION_HOSPITALAR,
            MUE_TENDENCIA,
            MUE_PADRE,
            MUE_TIPO,
            MUE_DERIVADA_POR,
            DER_FECHA,
            MUE_FECHA_INGRESO_SISTEMA,
            IND_ES_HUMANO,
            IND_PRIMER_NOMBRE,
            IND_SEGUNDO_NOMBRE,
            IND_PRIMER_APELLIDO,
            IND_SEGUNDO_APELLIDO,
            IND_IDENTIFICADOR,
            IND_IDENTIFICADOR_DISPONIBLE,
            IND_HISTORIA_CLINICA,
            IND_EDAD,
            IND_TIPO_EDAD,
            IND_FECHA_NACIMIENTO,
            IND_SEXO,
            TIP_VIG_ID,
            IND_ANONIMO,
            IND_DIRECCION,
            IND_DIRECCION_DISPONIBLE,
            iddep,
            idmun,
            idlp,
            IND_CASADA,
            IND_LOCALIDAD_DISPONIBLE,
            MUE_ARE_ANA_ID
            ) VALUES (';

        $sql.= "'".$data["MUE_CODIGO_GLOBAL_ANIO"]."', ";
        $sql.= "'".$data["MUE_CODIGO_GLOBAL_NUMERO"]."', ";
        $codigoEvento = helperMuestra::getCodigoEvento($evento);
        $numero = ((int)$codigoEvento[0]["eve_correlativo"])+1;
        $alfa = $codigoEvento[0]["eve_codigo"];

        $sql.= "'".$numero."', ";
        $sql.= "'".$alfa."', ";
        $sql.= ($data["MUE_FECHA_INICIO"]==""?mysql_escape_string("NULL")  : "'".$data["MUE_FECHA_INICIO"]."'").", ";
        $sql.= ($data["MUE_FECHA_TOMA"]==""?mysql_escape_string("NULL")  : "'".$data["MUE_FECHA_TOMA"]."'").", ";
        $sql.= ($data["MUE_FECHA_RECEPCION"]==""?mysql_escape_string("NULL")  : "'".$data["MUE_FECHA_RECEPCION"]."'").", ";
        $sql.= "'0', ";
        $sql.= "'1', ";
        $sql.= "'".$data["MUE_COMENTARIOS"]."', ";
        $sql.= "'".clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos()."', ";
        $sql.= "'".$data["MUE_REFERIDA_POR"]."', ";
        $sql.= "'".$data["IND_ID"]."', ";
        $sql.= "'".$data["MUE_CARGA_VIRAL"]."', ";
        $sql.= "'".$data["MUE_DONADOR"]."', ";
        $sql.= "'".$data["MUE_SEROLOGICA"]."', ";
        $sql.= "'".Configuration::enAreaGeneradora."', ";
        $tip =  helperMuestra::getIDEventoTipoMuestra($evento, $tipo);
        $sql.= "'".$tip[0]."', ";
        $sql.= "'".$data["MUE_RESULTADO_EXTERNO"]."', ";
        $sql.= "'1', ";
        $sql.= "'".Configuration::idEstadoMotivo."', ";
        $sql.= "'".$data["MUE_SEMANA_EPI"]."', ";
        $sql.= "'1', ";
        $sql.= "'".$data["MUE_SERVICIO_HOSPITALAR"]."', ";
        $sql.= "'".$data["idas"]."', ";
        $sql.= "'".$data["idds"]."', ";
        $sql.= "'".$data["MUE_DISTRITO_DISPONIBLE"]."', ";
        $sql.= "'".$data["idtss"]."', ";
        $sql.= "'".$data["MUE_ANTECEDENTES_INTERES"]."', ";
        $sql.= "'".$data["MUE_TIPO_SERVICIO_DISPONIBLE"]."', ";
        $sql.= "'".$data["idts"]."', ";
        $sql.= "'".$data["MUE_DESCRIPCION_SERVICIO_DISPONIBLE"]."', ";
        $sql.= "'".$data["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"]."', ";
        $sql.= "'".$data["MUE_SITUACION_HOSPITALAR"]."', ";

        if($evento == Configuration::dengue)
            $tendencia = helperString::calcularTendencia( $data['MUE_FECHA_INICIO'], $data['MUE_FECHA_TOMA']);
        else
            $tendencia = 'No aplica';
        $sql.= "'".$tendencia."', ";
        $sql.= "'".$idMuestra."', ";
        $sql.= "'".Configuration::tipoDerivacion."', ";
        $sql.= "'".clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos()."', ";
        $sql.= "'".date("Y-m-d")."', ";
        $sql.= "'".date("Y-m-d")."', ";


        // DATOS DEL INDIVIDUO
        $sql.="'". $data["IND_ES_HUMANO"]."', ";
        $sql.="'". $data["IND_PRIMER_NOMBRE"]."', ";
        $sql.="'". $data["IND_SEGUNDO_NOMBRE"]."', ";
        $sql.="'". $data["IND_PRIMER_APELLIDO"]."', ";
        $sql.="'". $data["IND_SEGUNDO_APELLIDO"]."', ";
        $sql.="'". $data["IND_IDENTIFICADOR"]."', ";
        $sql.="'". $data["IND_IDENTIFICADOR_DISPONIBLE"]."', ";
        $sql.="'". $data["IND_HISTORIA_CLINICA"]."', ";
        $sql.="'". $data["IND_EDAD"]."', ";
        $sql.="'". $data["IND_TIPO_EDAD"]."', ";
        $sql.="'". $data["IND_FECHA_NACIMIENTO"]."', ";
        $sql.="'". $data["IND_SEXO"]."', ";
        $sql.="'". $data["TIP_VIG_ID"]."', ";
        $sql.="'". $data["IND_ANONIMO"]."', ";
        $sql.="'". $data["IND_DIRECCION"]."', ";
        $sql.="'". $data["IND_DIRECCION_DISPONIBLE"]."', ";
        $sql.="'". $data["iddep"]."', ";
        $sql.="'". $data["idmun"]."', ";
        $sql.="'". $data["idlp"]."', ";
        $sql.="'". $data["IND_CASADA"]."', ";
        $sql.="'". $data["IND_LOCALIDAD_DISPONIBLE"]."', ";
        $sql.= $seccion;
        $sql.=")";
        //echo $sql;exit;
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

            $conn->prepare($sql);
            //$conn->params($data);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $id = $conn->getID();

            // BITACORA
            $bitacora = array();
            $bitacora["BIT_USUARIO"] =  clsCaus::obtenerID();
            $bitacora["BIT_FECHA"] = date("Y-m-d H:i:s");
            $bitacora["BIT_ACCION"] = '1';
            $bitacora["BIT_TABLA"] = "muestra";

            $sql = Actions::AgregarQuery("bitacora", $bitacora);
            $conn->prepare($sql);
            $conn->params($bitacora);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $conn->getID();

        // Actualiza el codigo de la muestra
            $sql = helperMuestra::increaseCodigoMuestra($evento);
            $conn->prepare($sql);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $conn->getID();

            // BITACORA
            $bitacora = array();
            $bitacora["BIT_USUARIO"] =  clsCaus::obtenerID();
            $bitacora["BIT_FECHA"] = date("Y-m-d H:i:s");
            $bitacora["BIT_ACCION"] = '2';
            $bitacora["BIT_TABLA"] = "evento";

            $sql = Actions::AgregarQuery("bitacora", $bitacora);
            $conn->prepare($sql);
            $conn->params($bitacora);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $conn->getID();

        // Agrega no prueba y no diagnóstico por default a la alicuota
            // NO PRUEBA
            $noprueba = array();
            $noprueba["MUE_ID"] = $id;
            $noprueba["PRU_RES_ID"] = helperMuestra::getNopruebaNoresultado($tipo);
            $sql = Actions::AgregarQuery("analisis_muestra", $noprueba);
            $conn->prepare($sql);
            $conn->params($noprueba);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $conn->getID();

            // NO DIAGNOSTICO
            $nodiagnostico = array();
            $nodiagnostico["TIP_SUB_ID1"] = helperMuestra::getNoDiagnostico($evento);
            $nodiagnostico["MUE_ID"] = $id;
            $nodiagnostico["TIP_SUB_ID2"] = $nodiagnostico["TIP_SUB_ID1"];
            $sql = Actions::AgregarQuery("conclusion_muestra", $nodiagnostico);
            $conn->prepare($sql);
            $conn->params($nodiagnostico);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $conn->getID();


        if(!$ok){
            $conn->rollback();
            $id = -1;
        }
        else{
            $conn->commit();
            $id = 1;
        }
        $conn->closeConn();
        return $id;
    }

    public function getAllDerivaciones($a, $b, $c, $data, $areas)
    {
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

        $sql = "SELECT
              muestra.MUE_ID,
              muestra.MUE_CODIGO_GLOBAL_ANIO as global,
              muestra.MUE_CODIGO_GLOBAL_NUMERO as gnumero,
              muestra.MUE_CODIGO_CORRELATIVO_NUMERO as cnumero,
              muestra.MUE_CODIGO_CORRELATIVO_ALFA as correlativo,
              evento.EVE_NOMBRE,
              area_analisis.ARE_ANA_NOMBRE,
              muestra.SIT_ID as ubicacion
            FROM
              muestra
              INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
              LEFT JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID)
              LEFT JOIN evento_seccion es ON evento.EVE_ID = es.EVE_ID AND es.ARE_ANA_ID = muestra.MUE_ARE_ANA_ID
              LEFT JOIN area_analisis ON (es.ARE_ANA_ID = area_analisis.ARE_ANA_ID) 
              WHERE muestra.mue_activa = 1 AND muestra.SIT_ID>=".Configuration::enAnalisisDer.' AND muestra.MUE_TIPO>='.Configuration::tipoAlicuotaIngresada.' '.$filtro

              .($data["area"]==0?'':($data["area"] != "" ? " AND area_analisis.ARE_ANA_ID = ".$data["area"] : ""))
              .($data["evento"]==0?'':($data["evento"] != "" ? " AND evento.EVE_ID = ".$data["evento"] : ""))
              .($data["global_desde"] != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO >= ".$data["global_desde"]: "")
              .($data["global_hasta"] != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO <= ".$data["global_hasta"]: "")
              .($data["correlativo_desde"] != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO >= ".$data["correlativo_desde"]: "")
              .($data["correlativo_hasta"] != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO <= ".$data["correlativo_hasta"]: "")
              .($data["fecha_derivacion_desde"] != "" ? " AND muestra.DER_FECHA >= '".$conn->scapeString(helperString::toDate($data["fecha_derivacion_desde"]))."'": "")
              .($data["fecha_derivacion_hasta"] != "" ? " AND muestra.DER_FECHA <= '".$conn->scapeString(helperString::toDate($data["fecha_derivacion_hasta"]))."'": "")
              ." ".$this->filtroUbicaciones." "
              ." order by ".$c
              ." limit ".$a.",".$b;

        $conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetch();
    	$conn->closeConn();
    	return $data;
    }

    public function getCountAllDerivaciones($data, $areas)
    {
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
            FROM
              muestra
              INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
              LEFT JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID)
              LEFT JOIN evento_seccion es ON evento.EVE_ID = es.EVE_ID AND es.ARE_ANA_ID = muestra.MUE_ARE_ANA_ID
              LEFT JOIN area_analisis ON (es.ARE_ANA_ID = area_analisis.ARE_ANA_ID) 
              WHERE muestra.mue_activa = 1 AND muestra.SIT_ID>=".Configuration::enAnalisisDer.' AND muestra.MUE_TIPO>='.Configuration::tipoAlicuotaIngresada.' '.$filtro

              .($data["area"]==0?'':($data["area"] != "" ? " AND area_analisis.ARE_ANA_ID = ".$data["area"] : ""))
              .($data["evento"]==0?'':($data["evento"] != "" ? " AND evento.EVE_ID = ".$data["evento"] : ""))
              .($data["global_desde"] != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO >= ".$data["global_desde"]: "")
              .($data["global_hasta"] != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO <= ".$data["global_hasta"]: "")
              .($data["correlativo_desde"] != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO >= ".$data["correlativo_desde"]: "")
              .($data["correlativo_hasta"] != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO <= ".$data["correlativo_hasta"]: "")
              .($data["fecha_derivacion_desde"] != "" ? " AND muestra.DER_FECHA >= '".$conn->scapeString(helperString::toDate($data["fecha_derivacion_desde"]))."'": "")
              .($data["fecha_derivacion_hasta"] != "" ? " AND muestra.DER_FECHA <= '".$conn->scapeString(helperString::toDate($data["fecha_derivacion_hasta"]))."'": "")
              ." ".$this->filtroUbicaciones." ";

        $conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetchOne();
    	$conn->closeConn();
    	return $data["cantidad"];
    }
}
?>