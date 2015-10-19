<?php
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperMuestra.php');
require_once ('libs/caus/clsCaus.php');

class dalIngreso {
    private $search;
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
//                        if ($elemento[ConfigurationCAUS::AreaSalud] != "")
//                            $temporal .= "muestra.idas = '".$elemento[ConfigurationCAUS::AreaSalud]."' ";
//
//                        if ($elemento[ConfigurationCAUS::DistritoSalud] != "")
//                            $temporal .= ($temporal != '' ? "and " : "")."muestra.idds = '".$elemento[ConfigurationCAUS::DistritoSalud]."' ";

                        if ($elemento[ConfigurationCAUS::ServicioSalud] != "")
                            $temporal .= ($temporal != '' ? "and " : "")."muestra.idts = '".$elemento[ConfigurationCAUS::ServicioSalud]."' ";

                        $this->filtroUbicaciones .= ($this->filtroUbicaciones != '' ? "or " : "")."(".$temporal.") ";
                    }
                }

                if ($this->filtroUbicaciones != "")
                    $this->filtroUbicaciones = "and (".$this->filtroUbicaciones.")";
    }

    // Guarda una nueva muestra
    public static function Guardar($data)
    {
        //return print_r($data["muestra"]);exit;
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        // ---------------------------------------------------------------
        // Datos del Individuo en un array
        // ---------------------------------------------------------------
        //return print_r($data["individuo"]);exit;
        $individuo = helperMuestra::dataIndividuo($data);
        //return print_r($individuo);exit;
        // VERIFICAR SI MUESTRA NO EXISTE ANTES DE INSERTARLA
        $yaExiste = helperMuestra::yaExiste($individuo, $data["muestra"]["area_salud"],
                $data["muestra"]["distrito"], 
                $data["muestra"]["tipo_establecimiento"],
                $data["muestra"]["evento"]["id_evento"], $data["muestra"]["tipo"],
                (!isset($data["muestra"]["fecha_inicio_sintomas"])?NULL: helperString::toDate($data["muestra"]["fecha_inicio_sintomas"])),
                helperString::toDate($data["muestra"]["fecha_toma"]),'1');
        //echo $yaExiste;exit;
        if($yaExiste>0)
        {
            $conn->rollback();
            $conn->closeConn();
            $id = -2;
            return $id;
            exit;
        }
        //echo $data["individuo"]["id_individuo"];exit;
        // NUEVO = -1
        if($data["individuo"]["id_individuo"]=='-1')
        {
            if ($individuo["idlp"]==0)
                $individuo["idlp"] = "";
            $sql = Actions::AgregarQuery("individuo", $individuo);
            // Ingresa datos de individuo
            //echo $sql." <br/><br/>Parametros:<br/>".print_r($individuo);exit;
            $conn->prepare($sql);
            $conn->params($individuo);
            $conn->execute() ? null : $ok = false;
            
            $error = $conn->getError();
            $idIndividuo = $conn->getID();
            //echo "id del individuo: $idIndividuo";exit;
            $conn->closeStmt();

            if(!$ok)
            {
                $conn->rollback();
                $conn->closeConn();
                return "SQL:".$sql."<br/>Error".$error."<br/>Parametros: ".print_r($individuo);;
                exit;
            }
            
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
            $error = $conn->getError();
            $conn->closeStmt();
            $id = $conn->getID();
            if(!$ok)
            {
                $conn->rollback();
                $conn->closeConn();
                return "SQL:".$sql."<br/>Error".$error;
                exit;
            }
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
            $actualizar["IND_LOCALIDAD_DISPONIBLE"] = $individuo["IND_LOCALIDAD_DISPONIBLE"];
            $actualizar["iddep"] = $individuo["iddep"];
            $actualizar["idmun"] = $individuo["idmun"];
            $actualizar["idlp"] = $individuo["idlp"];

            $actualizar["IND_ES_HUMANO"] = $individuo["IND_ES_HUMANO"];
            $actualizar["IND_PRIMER_NOMBRE"] = strtoupper($individuo["IND_PRIMER_NOMBRE"]);
            $actualizar["IND_SEGUNDO_NOMBRE"] = strtoupper($individuo["IND_SEGUNDO_NOMBRE"]);
            $actualizar["IND_PRIMER_APELLIDO"] = strtoupper($individuo["IND_PRIMER_APELLIDO"]);
            $actualizar["IND_SEGUNDO_APELLIDO"] = strtoupper($individuo["IND_SEGUNDO_APELLIDO"]);
            $actualizar["IND_IDENTIFICADOR"] = $individuo["IND_IDENTIFICADOR"];
            $actualizar["IND_IDENTIFICADOR_DISPONIBLE"] = $individuo["IND_IDENTIFICADOR_DISPONIBLE"];
            $actualizar["IND_SEXO"] = $individuo["IND_SEXO"];
            $actualizar["TIP_VIG_ID"] = $individuo["TIP_VIG_ID"];
            $actualizar["IND_ANONIMO"] = $individuo["IND_ANONIMO"];
            $actualizar["IND_DIRECCION"] = strtoupper($individuo["IND_DIRECCION"]);
            $actualizar["IND_DIRECCION_DISPONIBLE"] = $individuo["IND_DIRECCION_DISPONIBLE"];
            $actualizar["IND_LOCALIDAD_DISPONIBLE"] = $individuo["IND_LOCALIDAD_DISPONIBLE"];
            $actualizar["IND_CASADA"] = strtoupper($individuo["IND_CASADA"]);

            // Actualiza datos de individuo
            $filtro = array();
            $filtro["IND_ID"] = $data["individuo"]["id_individuo"];
            $sql = Actions::ActualizarQuery("individuo", $actualizar, $filtro);
            $actualizar["IND_ID"] = $data["individuo"]["id_individuo"];
            $idIndividuo = $data["individuo"]["id_individuo"];
            $conn->prepare($sql);
            $conn->params($actualizar);
            $conn->execute() ? null : $ok = false;
            $error = $conn->getError();
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
            $error = $conn->getError();
            $conn->closeStmt();
            $id = $conn->getID();

            if(!$ok)
            {
                $conn->rollback();
                $conn->closeConn();
                return "SQL: ".$sql."<br/>Error: ".$error."<br/>Parametros: ".print_r($actualizar);
                exit;
            }
        }
        //echo "bien";exit;
        //return "ingreso el individuo";exit;
        // DATOS DE LA MUESTRA
        $codigoGlobal = helperMuestra::getCodigoGlobal();
        $codigoEvento = helperMuestra::getCodigoEvento($data["muestra"]["evento"]["id_evento"]);

        $muestra = array();
        $muestra["MUE_CODIGO_GLOBAL_ANIO"] = $codigoGlobal[0]["cod_glo_anio"];
        $muestra["MUE_CODIGO_GLOBAL_NUMERO"] = ((int)$codigoGlobal[0]["cod_glo_correlativo"])+1;
        $muestra["MUE_CODIGO_CORRELATIVO_NUMERO"] = ((int)$codigoEvento[0]["eve_correlativo"])+1;
        $muestra["MUE_CODIGO_CORRELATIVO_ALFA"] = $codigoEvento[0]["eve_codigo"];
        $muestra["MUE_FECHA_INICIO"] = (!isset($data["muestra"]["fecha_inicio_sintomas"])? NULL:helperString::toDate($data["muestra"]["fecha_inicio_sintomas"]));
        $muestra["MUE_FECHA_TOMA"] = (!isset($data["muestra"]["fecha_toma"])? NULL:helperString::toDate($data["muestra"]["fecha_toma"]));
        $muestra["MUE_FECHA_RECEPCION"] = (!isset($data["muestra"]["fecha_recepcion"])? NULL:helperString::toDate($data["muestra"]["fecha_recepcion"]));
        
        
        // Rechazada

        if(isset($data["muestra"]["no_rechazada"]))
        {
            if($data["muestra"]["no_rechazada"] == 'on')
                $muestra["MUE_RECHAZADA"] = '0';
            else
                $muestra["MUE_RECHAZADA"] = '1';
        }
        else if(isset($data["muestra"]["si_rechazada"]))
        {
            if($data["muestra"]["si_rechazada"] == 'on')
                $muestra["MUE_RECHAZADA"] = '1';
            else
                $muestra["MUE_RECHAZADA"] = '0';
        }

        // Razón de rechazo
        $muestra["RAZ_REC_ID"] = $data["muestra"]["razon_rechazo"];
        $muestra["MUE_COMENTARIOS"] = trim($data["muestra"]["comentarios_adicionales"]);

        // REQUIERE DE LOGIN
        $muestra["MUE_INGRESADA_POR"] = clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos();
        $muestra["MUE_INGRESADA_POR_ID"] = clsCaus::obtenerID();
        $muestra["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"] = (!isset($data["muestra"]["otro_establecimiento"])?NULL:trim(strtoupper($data["muestra"]["otro_establecimiento"])));
        $muestra["MUE_REFERIDA_POR"] = (!isset($data["muestra"]["referida_por"])?NULL:trim(strtoupper($data["muestra"]["referida_por"])));

        if($data["individuo"]["id_individuo"]=='-1')
            $muestra["IND_ID"] = $idIndividuo;
        else
            $muestra["IND_ID"] = $data["individuo"]["id_individuo"];

        $muestra["MUE_CARGA_VIRAL"] =($data["muestra"]["carga_viral_check"]=='on'?'1':'2');

        // DONADOR, PACIENTE O NINGUNO
        if($data["muestra"]["evento"]["donador"]==1)
        {
            if($data["muestra"]["chk_donador"]=='on')
                $muestra["MUE_DONADOR"] ='1';
            else
                $muestra["MUE_DONADOR"] ='2';                
        }
        else
            $muestra["MUE_DONADOR"] = '3';

        
        // MUESTRA SEROLOGICA
        $muestra["MUE_SEROLOGICA"] =($data["muestra"]["encuesta_serologica_check"]=='on'?'1':'2');
        $muestra["SIT_ID"] = Configuration::ventanilla;     
        $muestra["MUE_ANTECEDENTES_INTERES"] = (!isset($data["muestra"]["antecedentes"])?NULL:trim($data["muestra"]["antecedentes"]));
        $muestra["SIT_ID"] = Configuration::ventanilla;


        $tipo_muestra = helperMuestra::getIDEventoTipoMuestra($data["muestra"]["evento"]["id_evento"], $data["muestra"]["tipo"]);
        $muestra["TIP_MUE_EVE_ID"] = $tipo_muestra[0];


        // En este punto no ha sido enviada ni recibida
        $muestra["MUE_ENVIADA_POR"] = NULL;
        $muestra["MUE_RECIBIDA_POR"] = NULL;

        $muestra["MUE_RESULTADO_EXTERNO"] = (!isset($data["muestra"]["resultados_externos"])?NULL:trim($data["muestra"]["resultados_externos"]));
        $muestra["MUE_ACTIVA"] = 1;
        $muestra["EST_MOT_ID"] = 1;
        
        $muestra["MUE_FECHA_ENVIO"] = NULL;
        $muestra["MUE_FECHA_RECEPCION_ANALISIS"] = NULL;
        $muestra["MUE_SEMANA_EPI"] = (!isset($data["muestra"]["semana"])?0:$data["muestra"]["semana"]);
        $muestra["MUE_INGRESADA_POR"] = clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos();

        $muestra["MUE_SERVICIO_HOSPITALAR"] = (!isset($data["muestra"]["servicio"])?0:$data["muestra"]["servicio"]);

        $muestra["MUE_SEROLOGICA"] =($data["muestra"]["encuesta_serologica_check"]=='on'?'1':'2');

        // PROCEDENCIA
        $muestra["idas"] = $data["muestra"]["area_salud"];
        
        $muestra["idds"] = $data["muestra"]["distrito"];
        $muestra["MUE_DISTRITO_DISPONIBLE"] =($data["muestra"]["no_distrito_muestra"]=='on'?'0':'1');

        $muestra["idts"] = $data["muestra"]["tipo_establecimiento"];
        
        $muestra["MUE_TIPO_SERVICIO_DISPONIBLE"] =($data["muestra"]["tipo_establecimiento"]=='0'?'0':'1');

        $muestra["idtss"] = $data["muestra"]["establecimiento"];

        if($data["muestra"]["tipo_establecimiento"]=='0')
            $muestra["MUE_DESCRIPCION_SERVICIO_DISPONIBLE"] = '0';
        else
        {
            if($muestra["MUE_OTRO_ESTABLECIMIENTO"]!="")
            {
                $muestra["MUE_DESCRIPCION_SERVICIO_DISPONIBLE"] ='2';
                $muestra["idts"] = 0;
            }
            else
                $muestra["MUE_DESCRIPCION_SERVICIO_DISPONIBLE"] ='1';
        }
        
        $muestra["MUE_DESCRIPCION_SERVICIO_DISPONIBLE"] =($data["muestra"]["tipo_establecimiento"]=='0'?'0':'1');

        // Condicion del individuo Fallecido, Hospitalizado, Normal
        if($individuo["IND_ES_HUMANO"]==1)
            $muestra["MUE_SITUACION_HOSPITALAR"] = $data["muestra"]["hospitalario"];
        else
            $muestra["MUE_SITUACION_HOSPITALAR"] = '3';

        // Si es dengue calcula la tendencia serológica
        if($data["muestra"]["evento"]["id_evento"]== Configuration::dengue)
            $muestra["MUE_TENDENCIA"] = helperString::calcularTendencia($muestra["MUE_FECHA_INICIO"], $muestra["MUE_FECHA_TOMA"]);
        $muestra["MUE_PADRE"] = NULL;
        $muestra["MUE_TIPO"] = Configuration::tipoMuestra;
        $muestra["MUE_FECHA_INGRESO_SISTEMA"] = date("Y-m-d");

        if($data["muestra"]["evento"]["id_evento"]== Configuration::FLU)
            $muestra["MUE_TIPO_FLU"] = $data["muestra"]["flu"];
        else
            $muestra["MUE_TIPO_FLU"] = '0';
        $muestra["MUE_ARE_ANA_ID"] = $data["muestra"]["area_analisis"];

        // Datos del individuo para el caso
        $muestra = $muestra + $individuo;
        //print_r($muestra);exit;
        // Inserta datos de muestra
        $sql = Actions::AgregarQuery("muestra", $muestra);
        //echo "SQL:".$sql.print_r($muestra);exit;
        $conn->prepare($sql);
        $conn->params($muestra);
        
        $conn->execute() ? null : $ok = false;
        $error = $conn->getError();
        $conn->closeStmt();
        $id = $conn->getID();
        //echo "id de la muestra $id";exit;
        if(!$ok)
        {
            $conn->rollback();
            $conn->closeConn();
            return "SQL:".$sql."<br/>Error".$error.print_r($muestra);
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
        $error = $conn->getError();
        $conn->closeStmt();
        $conn->getID();

        if(!$ok)
        {
            $conn->rollback();
            $conn->closeConn();
            return "SQL:".$sql."<br/>Error".$error;
            exit;
        }


        // Agrega no prueba y no diagnóstico por default a la muestra
            // NO PRUEBA
            $noprueba = array();
            $noprueba["MUE_ID"] = $id;
            //print_r($tipo_muestra);exit;
            $noprueba["PRU_RES_ID"] = helperMuestra::getNopruebaNoresultado($tipo_muestra);
            //echo "No prueba, no resultado: ".$noprueba["PRU_RES_ID"];exit;
            
            $sql = Actions::AgregarQuery("analisis_muestra", $noprueba);
            $conn->prepare($sql);
            $conn->params($noprueba);
            $conn->execute() ? null : $ok = false;
            $error = $conn->getError();
            $conn->closeStmt();
            $conn->getID();

            if(!$ok)
            {
                $conn->rollback();
                $conn->closeConn();
                return "SQL:".$sql."<br/>Error".$error.  print_r($noprueba);
                exit;
            }

            // NO DIAGNOSTICO
            $nodiagnostico = array();
            $nodiagnostico["TIP_SUB_ID1"] = helperMuestra::getNoDiagnostico($data["muestra"]["evento"]["id_evento"]);
            //echo "No diagnostico: ".$nodiagnostico["TIP_SUB_ID1"];exit;
            $nodiagnostico["MUE_ID"] = $id;
            $nodiagnostico["TIP_SUB_ID2"] = $nodiagnostico["TIP_SUB_ID1"];
            $sql = Actions::AgregarQuery("conclusion_muestra", $nodiagnostico);
            //echo "SQL: ".$sql.print_r($nodiagnostico);
            $conn->prepare($sql);
            $conn->params($nodiagnostico);
            $conn->execute() ? null : $ok = false;
            $error = $conn->getError();
            $conn->closeStmt();
            $conn->getID();

            if(!$ok)
            {
                $conn->rollback();
                $conn->closeConn();
                return "SQL:".$sql."<br/>Error".$error;
                exit;
            }
            //echo "ahora estoy aqui";exit;

        // Actualiza código global activo
        $sql = helperMuestra::increaseCodigoGlobal();
        $conn->prepare($sql);
        $conn->execute() ? null : $ok = false;
        $error = $conn->getError();
        $conn->closeStmt();
        //echo $conn->getID();exit;

        if(!$ok)
        {
            $conn->rollback();
            $conn->closeConn();
            return "SQL:".$sql."<br/>Error".$error;
            exit;
        }

        // BITACORA
        $bitacora = array();
        $bitacora["BIT_USUARIO"] = clsCaus::obtenerID();
        $bitacora["BIT_FECHA"] = date("Y-m-d H:i:s");
        $bitacora["BIT_ACCION"] = '2';
        $bitacora["BIT_TABLA"] = "codigo_global";
        $sql = Actions::AgregarQuery("bitacora", $bitacora);
        $conn->prepare($sql);
        $conn->params($bitacora);
        $conn->execute() ? null : $ok = false;
        $error = $conn->getError();
        $conn->closeStmt();
        $conn->getID();

        if(!$ok)
        {
            $conn->rollback();
            $conn->closeConn();
            return "SQL:".$sql."<br/>Error".$error;
            exit;
        }

        // Actualiza evento (correlativo)
        $sql = helperMuestra::increaseCodigoMuestra($data["muestra"]["evento"]["id_evento"]);
        $conn->prepare($sql);
        $conn->execute() ? null : $ok = false;
        $error = $conn->getError();
        $conn->closeStmt();
        $conn->getID();

        if(!$ok)
        {
            $conn->rollback();
            $conn->closeConn();
            return "SQL:".$sql."<br/>Error".$error;
            exit;
        }

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
        $error = $conn->getError();
        $conn->closeStmt();
        $conn->getID();
        
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
    
    public static function getEstablecimientos($term = "", $idmun = 0){
        
        $conn = new Connection();
            $conn->initConn();
            $sql = "select idts, concat(COALESCE(tipo_ser.nombre,''),' - ',descripcion_servicio.nombre) as nombre, cod_referencia 
                    from descripcion_servicio 
                    left join tipo_servicio tipo_ser on descripcion_servicio.idtss = tipo_ser.idtss 
                    where idmun = $idmun ";
            if ($term != ""){
                $sql.=" and (concat(COALESCE(tipo_ser.nombre,''),' - ',descripcion_servicio.nombre) like '%".$conn->scapeString($term)."%'
                    or cod_referencia like '%".$conn->scapeString($term)."%')";
            }
            $sql.= " order by cod_referencia, tipo_ser.nombre, descripcion_servicio.nombre";
            //echo $sql;exit;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeStmt();
            $conn->closeConn();
            return $data;
        }

    // Edición de muestra y alícuota ingresada
    public static function Modificar($data, $idMuestra)
    {

        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        // ---------------------------------------------------------------
        // Individuo
        // ---------------------------------------------------------------
        //print_r($data);exit;
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

            if(!$ok)
            {
                $conn->rollback();
                $conn->closeConn();
                return $error;
                exit;
            }

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

            if(!$ok)
            {
                $conn->rollback();
                $conn->closeConn();
                return $error;
                exit;
            }
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
            $actualizar["IND_LOCALIDAD_DISPONIBLE"] = $individuo["IND_LOCALIDAD_DISPONIBLE"];
            $actualizar["iddep"] = $individuo["iddep"];
            $actualizar["idmun"] = $individuo["idmun"];
            $actualizar["idlp"] = $individuo["idlp"];

            $actualizar["IND_ES_HUMANO"] = $individuo["IND_ES_HUMANO"];
            $actualizar["IND_PRIMER_NOMBRE"] = strtoupper($individuo["IND_PRIMER_NOMBRE"]);
            $actualizar["IND_SEGUNDO_NOMBRE"] = strtoupper($individuo["IND_SEGUNDO_NOMBRE"]);
            $actualizar["IND_PRIMER_APELLIDO"] = strtoupper($individuo["IND_PRIMER_APELLIDO"]);
            $actualizar["IND_SEGUNDO_APELLIDO"] = strtoupper($individuo["IND_SEGUNDO_APELLIDO"]);
            $actualizar["IND_IDENTIFICADOR"] = $individuo["IND_IDENTIFICADOR"];
            $actualizar["IND_IDENTIFICADOR_DISPONIBLE"] = $individuo["IND_IDENTIFICADOR_DISPONIBLE"];
            $actualizar["IND_SEXO"] = $individuo["IND_SEXO"];
            $actualizar["TIP_VIG_ID"] = $individuo["TIP_VIG_ID"];
            $actualizar["IND_ANONIMO"] = $individuo["IND_ANONIMO"];
            $actualizar["IND_DIRECCION"] = strtoupper($individuo["IND_DIRECCION"]);
            $actualizar["IND_DIRECCION_DISPONIBLE"] = $individuo["IND_DIRECCION_DISPONIBLE"];
            $actualizar["IND_LOCALIDAD_DISPONIBLE"] = $individuo["IND_LOCALIDAD_DISPONIBLE"];
            $actualizar["IND_CASADA"] = strtoupper($individuo["IND_CASADA"]);

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

            if(!$ok)
            {
                $conn->rollback();
                $conn->closeConn();
                return $error;
                exit;
            }


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

            if(!$ok)
            {
                $conn->rollback();
                $conn->closeConn();
                return $error;
                exit;
            }
        }

        $tipoMuestraEditable = helperMuestra::tipoMuestra($idMuestra);

        // Tipo padre
        if($tipoMuestraEditable == Configuration::tipoMuestra)
        {
            $hijas = helperMuestra::getHijas($idMuestra);
            $conteoHijas = count($hijas);

            // Tiene muestras dependientes de ella
            if($conteoHijas>0)
            {               
                    $conteo = 0;
                    $ok = true;

                    // PADRE
                    $muestra = array();
                    $muestra["MUE_FECHA_INICIO"] = (!isset($data["muestra"]["fecha_inicio_sintomas"])? NULL:helperString::toDate($data["muestra"]["fecha_inicio_sintomas"]));
                    $muestra["MUE_FECHA_TOMA"] = (!isset($data["muestra"]["fecha_toma"])? NULL:helperString::toDate($data["muestra"]["fecha_toma"]));
                    $muestra["MUE_FECHA_RECEPCION"] = (!isset($data["muestra"]["fecha_recepcion"])? NULL:helperString::toDate($data["muestra"]["fecha_recepcion"]));

                    // Rechazada
                    if(isset($data["muestra"]["no_rechazada"]))
                    {
                        if($data["muestra"]["no_rechazada"] == 'on')
                            $muestra["MUE_RECHAZADA"] = '0';
//                        else
//                            $muestra["MUE_RECHAZADA"] = '1';
                    }
                    else if(isset($data["muestra"]["si_rechazada"]))
                    {
                        if($data["muestra"]["si_rechazada"] == 'on')
                            $muestra["MUE_RECHAZADA"] = '1';
//                            else
//                                $muestra["MUE_RECHAZADA"] = '0';
                    }

                    //$muestra["MUE_RECHAZADA"] =($data["muestra"]["no_rechazada"]=='on'?'0':'1');
                    $muestra["RAZ_REC_ID"] = $data["muestra"]["razon_rechazo"];
                    $muestra["MUE_COMENTARIOS"] = trim($data["muestra"]["comentarios_adicionales"]);
                    $muestra["MUE_REFERIDA_POR"] = (!isset($data["muestra"]["referida_por"])?NULL:trim(strtoupper($data["muestra"]["referida_por"])));

                    if($data["individuo"]["id_individuo"]=='-1')
                        $muestra["IND_ID"] = $idIndividuo;
                    else
                        $muestra["IND_ID"] = $data["individuo"]["id_individuo"];
                    $muestra["MUE_ANTECEDENTES_INTERES"] = (!isset($data["muestra"]["antecedentes"])?NULL:trim($data["muestra"]["antecedentes"]));
                    $muestra["MUE_RESULTADO_EXTERNO"] = (!isset($data["muestra"]["resultados_externos"])?NULL:trim($data["muestra"]["resultados_externos"]));
                    $muestra["MUE_SEMANA_EPI"] = (!isset($data["muestra"]["semana"])?0:$data["muestra"]["semana"]);
                    $muestra["MUE_SERVICIO_HOSPITALAR"] = (!isset($data["muestra"]["servicio"])?0:$data["muestra"]["servicio"]);

                    // PROCEDENCIA
                    $muestra["idas"] = $data["muestra"]["area_salud"];
                    $muestra["idds"] = $data["muestra"]["distrito"];
                    $muestra["MUE_DISTRITO_DISPONIBLE"] =($data["muestra"]["no_distrito_muestra"]=='on'?'0':'1');
                    $muestra["idts"] = $data["muestra"]["tipo_establecimiento"];
                    $muestra["MUE_TIPO_SERVICIO_DISPONIBLE"] =($data["muestra"]["tipo_establecimiento"]=='0'?'0':'1');
                    $muestra["idtss"] = $data["muestra"]["establecimiento"];
                    $muestra["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"] = (!isset($data["muestra"]["otro_establecimiento"])?NULL:trim(strtoupper($data["muestra"]["otro_establecimiento"])));
                    //print_r($muestra);exit;
                    if($$data["muestra"]["tipo_establecimiento"]=='0')
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

                    $muestra["MUE_DESCRIPCION_SERVICIO_DISPONIBLE"] =($data["muestra"]["tipo_establecimiento"]=='0'?'0':'1');

                    // Condicion del individuo Fallecido, Hospitalizado, Normal
                    if($individuo["IND_ES_HUMANO"]==1)
                        $muestra["MUE_SITUACION_HOSPITALAR"] = $data["muestra"]["hospitalario"];
                    else
                        $muestra["MUE_SITUACION_HOSPITALAR"] = '3';

                    // Si es dengue calcula la tendencia serológica
                    if($data["muestra"]["evento"]["id_evento"]== Configuration::dengue)
                        $muestra["MUE_TENDENCIA"] = helperString::calcularTendencia($muestra["MUE_FECHA_INICIO"], $muestra["MUE_FECHA_TOMA"]);
                    if($data["muestra"]["evento"]["id_evento"]== Configuration::FLU)
                        $muestra["MUE_TIPO_FLU"] = $data["muestra"]["flu"];
                    else
                        $muestra["MUE_TIPO_FLU"] = '0';

                    // Actualizar datos de caso
                    $muestra = $muestra + $individuo;

                    $filtro = array();
                    $filtro["MUE_ID"] = $idMuestra;
                    $sql = Actions::ActualizarQuery("muestra", $muestra, $filtro);
                    $muestra["MUE_ID"] = $idMuestra;
                    $conn->prepare($sql);
                    $conn->params($muestra);
                    $conn->execute() ? null : $ok = false;
                    $conn->closeStmt();
                    $conn->getID();

                    if(!$ok)
                    {
                        $conn->rollback();
                        $conn->closeConn();
                        return $error;
                        exit;
                    }

                    unset($muestra["MUE_RECHAZADA"]);
                    unset($muestra["RAZ_REC_ID"]);

                    // Actualiza cada una de las que dependen de ella
                    for($i=0; $i<$conteoHijas; $i++)
                    {
                        unset ($muestra["MUE_ID"]);
                        $filtro = array();
                        $filtro["MUE_ID"] = $hijas[$i];
                        $sql = Actions::ActualizarQuery("muestra", $muestra, $filtro);
                        $muestra["MUE_ID"] = $hijas[$i];

                        $conn->prepare($sql);
                        $conn->params($muestra);
                        $conn->execute() ? null : $ok = false;
                        $conn->closeStmt();
                        $conn->getID();

                        if(!$ok)
                        {
                            $conn->rollback();
                            $conn->closeConn();
                            return $error;
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
                        $conn->getID();

                        if($ok)
                            $conteo++;
                    }

                    if($conteoHijas!=$conteo)
                        $ok = false;
                    else
                        $id = $idMuestra;
                    
                    if(!$ok)
                    {
                        $conn->rollback();
                        $conn->closeConn();
                        return $error;
                        exit;
                    }
            }
            else
            {
                $situacion = helperMuestra::situacionMuestra($idMuestra);                
                if($situacion[0]==Configuration::ventanilla)
                {
                    // Está en ventanilla puede editar todos los campos
                    
                    // Correlativo por evento anterior
                    $codigoEventoAnterior = helperMuestra::correlativoAnterior($idMuestra);
                    $idEventoAnterior = helperMuestra::eventoAnterior($idMuestra);
                    $codigoGlobalAnterior = helperMuestra::globalAnterior($idMuestra);

                    $muestra = array();
                    // Conserva código global
                    $muestra["MUE_CODIGO_GLOBAL_ANIO"] = $codigoGlobalAnterior[0]["mue_codigo_global_anio"];
                    $muestra["MUE_CODIGO_GLOBAL_NUMERO"] = $codigoGlobalAnterior[0]["mue_codigo_global_numero"];

                    // Código de evento cambiará o se conservará
                    if($data["muestra"]["evento"]["id_evento"]!=$idEventoAnterior[0])
                    {
                        // YA NO TIENE EL MISMO CODIGO DEL EVENTO
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
                            return $error;
                            exit;
                        }

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

                        if(!$ok)
                        {
                            $conn->rollback();
                            $conn->closeConn();
                            return $error;
                            exit;
                        }

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

                        if(!$ok)
                        {
                            $conn->rollback();
                            $conn->closeConn();
                            return $error;
                            exit;
                        }

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

                    
                    // Rechazada
                    if(isset($data["muestra"]["no_rechazada"]))
                    {
                        if($data["muestra"]["no_rechazada"] == 'on')
                            $muestra["MUE_RECHAZADA"] = '0';
//                        else
//                            $muestra["MUE_RECHAZADA"] = '1';
                    }
                    else
                    {
                        if(isset($data["muestra"]["si_rechazada"]))
                        {
                            if($data["muestra"]["si_rechazada"] == 'on')
                                $muestra["MUE_RECHAZADA"] = '1';
//                            else
//                                $muestra["MUE_RECHAZADA"] = '0';
                        }
                    }
                    
                    //$muestra["MUE_RECHAZADA"] =($data["muestra"]["no_rechazada"]=='on'?'0':'1');
                    $muestra["RAZ_REC_ID"] = $data["muestra"]["razon_rechazo"];
                    $muestra["MUE_COMENTARIOS"] = trim($data["muestra"]["comentarios_adicionales"]);
                    $muestra["MUE_REFERIDA_POR"] = (!isset($data["muestra"]["referida_por"])?NULL:trim(strtoupper($data["muestra"]["referida_por"])));

                    if($data["individuo"]["id_individuo"]=='-1')
                        $muestra["IND_ID"] = $idIndividuo;
                    else
                        $muestra["IND_ID"] = $data["individuo"]["id_individuo"];

                    $muestra["MUE_CARGA_VIRAL"] =($data["muestra"]["carga_viral_check"]=='on'?'1':'2');
                    $muestra["MUE_ANTECEDENTES_INTERES"] = (!isset($data["muestra"]["antecedentes"])?NULL:trim($data["muestra"]["antecedentes"]));

                    $tipo_muestra = helperMuestra::getIDEventoTipoMuestra($data["muestra"]["evento"]["id_evento"], $data["muestra"]["tipo"]);
                    $muestra["TIP_MUE_EVE_ID"] = $tipo_muestra[0];                    
                    $muestra["MUE_RESULTADO_EXTERNO"] = (!isset($data["muestra"]["resultados_externos"])?NULL:trim($data["muestra"]["resultados_externos"]));
                    
                    $muestra["MUE_SEMANA_EPI"] = (!isset($data["muestra"]["semana"])?0:$data["muestra"]["semana"]);
                    $muestra["MUE_SERVICIO_HOSPITALAR"] = (!isset($data["muestra"]["servicio"])?0:$data["muestra"]["servicio"]);
                    $muestra["MUE_SEROLOGICA"] =($data["muestra"]["encuesta_serologica_check"]=='on'?'1':'2');

                    // PROCEDENCIA
                    $muestra["idas"] = $data["muestra"]["area_salud"];
                    $muestra["idds"] = $data["muestra"]["distrito"];
                    $muestra["MUE_DISTRITO_DISPONIBLE"] =($data["muestra"]["no_distrito_muestra"]=='on'?'0':'1');
                    $muestra["idts"] = $data["muestra"]["tipo_establecimiento"];
                    $muestra["MUE_TIPO_SERVICIO_DISPONIBLE"] =($data["muestra"]["tipo_establecimiento"]=='0'?'0':'1');
                    $muestra["idtss"] = $data["muestra"]["establecimiento"];
                    $muestra["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"] =(!isset($data["muestra"]["otro_establecimiento"])?NULL:trim(strtoupper($data["muestra"]["otro_establecimiento"])));

                    if($data["muestra"]["tipo_establecimiento"]=='0')
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

                    $muestra["MUE_DESCRIPCION_SERVICIO_DISPONIBLE"] =($data["muestra"]["tipo_establecimiento"]=='0'?'0':'1');

                    // Condicion del individuo Fallecido, Hospitalizado, Normal
                    if($individuo["IND_ES_HUMANO"]==1)
                        $muestra["MUE_SITUACION_HOSPITALAR"] = $data["muestra"]["hospitalario"];
                    else
                        $muestra["MUE_SITUACION_HOSPITALAR"] = '3';

                    // Si es dengue calcula la tendencia serológica
                    if($data["muestra"]["evento"]["id_evento"]== Configuration::dengue)
                        $muestra["MUE_TENDENCIA"] = helperString::calcularTendencia($muestra["MUE_FECHA_INICIO"], $muestra["MUE_FECHA_TOMA"]);

                    if($data["muestra"]["evento"]["id_evento"]== Configuration::FLU)
                        $muestra["MUE_TIPO_FLU"] = $data["muestra"]["flu"];
                    else
                        $muestra["MUE_TIPO_FLU"] = '0';

                    // Actualizar datos de caso
                    $muestra = $muestra + $individuo;

                    // Actualiza datos de muestra
                        $filtro = array();
                        $filtro["MUE_ID"] = $idMuestra;
                        $sql = Actions::ActualizarQuery("muestra", $muestra, $filtro);
                        $muestra["MUE_ID"] = $idMuestra;
                        $conn->prepare($sql);
                        $conn->params($muestra);
                        $conn->execute() ? null : $ok = false;
                        $conn->closeStmt();
                        $id = $conn->getID();

                        if(!$ok)
                        {
                            $conn->rollback();
                            $conn->closeConn();
                            return $error;
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
                        $conn->getID();
                        if(!$ok)
                        {
                            $conn->rollback();
                            $conn->closeConn();
                            return $error;
                            exit;
                        }
                        
                        // Borrar no prueba y no diagnostico previamente asignado
                        $sql = 'DELETE FROM analisis_muestra where MUE_ID='.$idMuestra;//.' AND PRU_RES_ID!='.$noPrueba;
                        $conn->prepare($sql);
                        $conn->execute() ? null : $ok = false;
                        $conn->closeStmt();
                        $conn->getID();

                        if(!$ok)
                        {
                            $conn->rollback();
                            $conn->closeConn();
                            return $error;
                            exit;
                        }

                        $sql = 'DELETE FROM conclusion_muestra where MUE_ID='.$idMuestra;
                        $conn->prepare($sql);
                        $conn->execute() ? null : $ok = false;
                        $conn->closeStmt();
                        $conn->getID();

                        if(!$ok)
                        {
                            $conn->rollback();
                            $conn->closeConn();
                            return $error;
                            exit;
                        }

                        // Asigna no prueba y no diagnóstico dependiendo del tipo de muestra
                            // NO PRUEBA
                            $noprueba = array();
                            $noprueba["MUE_ID"] = $idMuestra;
                            $noprueba["PRU_RES_ID"] = helperMuestra::getNopruebaNoresultado($data["muestra"]["tipo"]);
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
                                return $error;
                                exit;
                            }

                            // NO DIAGNOSTICO
                            $nodiagnostico = array();
                            $nodiagnostico["TIP_SUB_ID1"] = helperMuestra::getNoDiagnostico($data["muestra"]["evento"]["id_evento"]);
                            $nodiagnostico["MUE_ID"] = $idMuestra;
                            $nodiagnostico["TIP_SUB_ID2"] = $nodiagnostico["TIP_SUB_ID1"];
                            $sql = Actions::AgregarQuery("conclusion_muestra", $nodiagnostico);
                            $conn->prepare($sql);
                            $conn->params($nodiagnostico);
                            $conn->execute() ? null : $ok = false;
                            $conn->closeStmt();
                            $conn->getID();

                            if(!$ok)
                            {
                                $conn->rollback();
                                $conn->closeConn();
                                return $error;
                                exit;
                            }
                }
                else
                {
                    // Edición selectiva porque ya no está en ventanilla
                    // Correlativo por evento anterior
                    $codigoEventoAnterior = helperMuestra::correlativoAnterior($idMuestra);
                    $idEventoAnterior = helperMuestra::eventoAnterior($idMuestra);
                    $codigoGlobalAnterior = helperMuestra::globalAnterior($idMuestra);

                    $muestra = array();
                    // Conserva código global
                    $muestra["MUE_CODIGO_GLOBAL_ANIO"] = $codigoGlobalAnterior[0]["mue_codigo_global_anio"];
                    $muestra["MUE_CODIGO_GLOBAL_NUMERO"] = $codigoGlobalAnterior[0]["mue_codigo_global_numero"];

                    // Se conserva el código de muestra porque no cambió
                    $muestra["MUE_CODIGO_CORRELATIVO_NUMERO"] = $codigoEventoAnterior[0]["mue_codigo_correlativo_numero"];
                    $muestra["MUE_CODIGO_CORRELATIVO_ALFA"] = $codigoEventoAnterior[0]["mue_codigo_correlativo_alfa"];
                    $muestra["MUE_FECHA_INICIO"] = (!isset($data["muestra"]["fecha_inicio_sintomas"])?NULL:helperString::toDate($data["muestra"]["fecha_inicio_sintomas"]));
                    $muestra["MUE_FECHA_TOMA"] = (!isset($data["muestra"]["fecha_toma"])? NULL:helperString::toDate($data["muestra"]["fecha_toma"]));
                    $muestra["MUE_FECHA_RECEPCION"] = (!isset($data["muestra"]["fecha_recepcion"])? NULL:helperString::toDate($data["muestra"]["fecha_recepcion"]));

                    // Rechazada
                    if(isset($data["muestra"]["no_rechazada"]))
                    {
                        if($data["muestra"]["no_rechazada"] == 'on')
                            $muestra["MUE_RECHAZADA"] = '0';
//                        else
//                            $muestra["MUE_RECHAZADA"] = '1';
                    }
                    else
                    {
                        if(isset($data["muestra"]["si_rechazada"]))
                        {
                            if($data["muestra"]["si_rechazada"] == 'on')
                                $muestra["MUE_RECHAZADA"] = '1';
//                            else
//                                $muestra["MUE_RECHAZADA"] = '0';
                        }
                    }

                    //$muestra["MUE_RECHAZADA"] =($data["muestra"]["no_rechazada"]=='on'?'0':'1');
                    $muestra["RAZ_REC_ID"] = $data["muestra"]["razon_rechazo"];
                    $muestra["MUE_COMENTARIOS"] = trim($data["muestra"]["comentarios_adicionales"]);

                    $muestra["MUE_REFERIDA_POR"] = (!isset($data["muestra"]["referida_por"])?NULL:trim(strtoupper($data["muestra"]["referida_por"])));

                    if($data["individuo"]["id_individuo"]=='-1')
                        $muestra["IND_ID"] = $idIndividuo;
                    else
                        $muestra["IND_ID"] = $data["individuo"]["id_individuo"];
                    $muestra["MUE_CARGA_VIRAL"] =($data["muestra"]["carga_viral_check"]=='on'?'1':'2');

                    $muestra["MUE_ANTECEDENTES_INTERES"] = (!isset($data["muestra"]["antecedentes"])?NULL:trim($data["muestra"]["antecedentes"]));

                    $muestra["MUE_RESULTADO_EXTERNO"] = (!isset($data["muestra"]["resultados_externos"])?NULL:trim($data["muestra"]["resultados_externos"]));

                    $muestra["MUE_FECHA_ENVIO"] = NULL;
                    $muestra["MUE_FECHA_RECEPCION_ANALISIS"] = NULL;
                    $muestra["MUE_SEMANA_EPI"] = (!isset($data["muestra"]["semana"])?0:$data["muestra"]["semana"]);
                    $muestra["MUE_INGRESADA_POR"] = clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos();
                    $muestra["MUE_SERVICIO_HOSPITALAR"] = (!isset($data["muestra"]["servicio"])?0:$data["muestra"]["servicio"]);
                    $muestra["MUE_SEROLOGICA"] =($data["muestra"]["encuesta_serologica_check"]=='on'?'1':'2');

                    // PROCEDENCIA
                    $muestra["idas"] = $data["muestra"]["area_salud"];
                    $muestra["idds"] = $data["muestra"]["distrito"];
                    $muestra["MUE_DISTRITO_DISPONIBLE"] =($data["muestra"]["no_distrito_muestra"]=='on'?'0':'1');
                    $muestra["idts"] = $data["muestra"]["tipo_establecimiento"];
                    $muestra["MUE_TIPO_SERVICIO_DISPONIBLE"] =($data["muestra"]["tipo_establecimiento"]=='0'?'0':'1');
                    $muestra["idtss"] = $data["muestra"]["establecimiento"];
                    $muestra["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"] = (!isset($data["muestra"]["otro_establecimiento"])?NULL:trim(strtoupper($data["muestra"]["otro_establecimiento"])));


                    if($data["muestra"]["tipo_establecimiento"]=='0')
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

                    $muestra["MUE_DESCRIPCION_SERVICIO_DISPONIBLE"] =($data["muestra"]["tipo_establecimiento"]=='0'?'0':'1');

                    // Condicion del individuo Fallecido, Hospitalizado, Normal
                    if($individuo["IND_ES_HUMANO"]==1)
                        $muestra["MUE_SITUACION_HOSPITALAR"] = $data["muestra"]["hospitalario"];
                    else
                        $muestra["MUE_SITUACION_HOSPITALAR"] = '3';

                    // Si es dengue calcula la tendencia serológica
                    if($data["muestra"]["evento"]["id_evento"]== Configuration::dengue)
                        $muestra["MUE_TENDENCIA"] = helperString::calcularTendencia($muestra["MUE_FECHA_INICIO"], $muestra["MUE_FECHA_TOMA"]);

                    if($data["muestra"]["evento"]["id_evento"]== Configuration::FLU)
                        $muestra["MUE_TIPO_FLU"] = $data["muestra"]["flu"];
                    else
                        $muestra["MUE_TIPO_FLU"] = '0';

                    // Actualizar datos de caso
                    $muestra = $muestra + $individuo;

                    // Actualiza datos de muestra
                        $filtro = array();
                        $filtro["MUE_ID"] = $idMuestra;
                        $sql = Actions::ActualizarQuery("muestra", $muestra, $filtro);
                        $muestra["MUE_ID"] = $idMuestra;

                        $conn->prepare($sql);
                        $conn->params($muestra);
                        $conn->execute() ? null : $ok = false;
                        $conn->closeStmt();
                        $id = $conn->getID();

                        if(!$ok)
                        {
                            $conn->rollback();
                            $conn->closeConn();
                            return $error;
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
                        $conn->getID();

                        if(!$ok)
                        {
                            $conn->rollback();
                            $conn->closeConn();
                            return $error;
                            exit;
                        }

                }
            }
        }
        else if($tipoMuestraEditable == Configuration::tipoAlicuotaIngresada)
        {

            // Obtiene padre y hermanas
            $padre = helperMuestra::getPadre($idMuestra);
            $hermanas = helperMuestra::getHermanas($idMuestra, $padre);
            $hermanas[] = $padre;

            $conteoHermanas = count($hermanas);
            // Tiene muestras emparentadas a ella
            if($conteoHermanas>0)
            {
                    $conteo = 0;
                    $ok = true;

                    // HIJA
                    $muestra = array();
                    $muestra["MUE_FECHA_INICIO"] = (!isset($data["muestra"]["fecha_inicio_sintomas"])? NULL:helperString::toDate($data["muestra"]["fecha_inicio_sintomas"]));
                    $muestra["MUE_FECHA_TOMA"] = (!isset($data["muestra"]["fecha_toma"])? NULL:helperString::toDate($data["muestra"]["fecha_toma"]));
                    $muestra["MUE_FECHA_RECEPCION"] = (!isset($data["muestra"]["fecha_recepcion"])? NULL:helperString::toDate($data["muestra"]["fecha_recepcion"]));

                    // Rechazada
                    if(isset($data["muestra"]["no_rechazada"]))
                    {
                        if($data["muestra"]["no_rechazada"] == 'on')
                            $muestra["MUE_RECHAZADA"] = '0';
//                        else
//                            $muestra["MUE_RECHAZADA"] = '1';
                    }
                    else
                    {
                        if(isset($data["muestra"]["si_rechazada"]))
                        {
                            if($data["muestra"]["si_rechazada"] == 'on')
                                $muestra["MUE_RECHAZADA"] = '1';
//                            else
//                                $muestra["MUE_RECHAZADA"] = '0';
                        }
                    }

                    //$muestra["MUE_RECHAZADA"] =($data["muestra"]["no_rechazada"]=='on'?'0':'1');
                    $muestra["RAZ_REC_ID"] = $data["muestra"]["razon_rechazo"];
                    $muestra["MUE_COMENTARIOS"] = trim($data["muestra"]["comentarios_adicionales"]);
                    $muestra["MUE_REFERIDA_POR"] = (!isset($data["muestra"]["referida_por"])?NULL:trim(strtoupper($data["muestra"]["referida_por"])));

                    if($data["individuo"]["id_individuo"]=='-1')
                        $muestra["IND_ID"] = $idIndividuo;
                    else
                        $muestra["IND_ID"] = $data["individuo"]["id_individuo"];
                    $muestra["MUE_ANTECEDENTES_INTERES"] = (!isset($data["muestra"]["antecedentes"])?NULL:trim($data["muestra"]["antecedentes"]));
                    $muestra["MUE_RESULTADO_EXTERNO"] = (!isset($data["muestra"]["resultados_externos"])?NULL:trim($data["muestra"]["resultados_externos"]));
                    $muestra["MUE_SEMANA_EPI"] = (!isset($data["muestra"]["semana"])?0:$data["muestra"]["semana"]);
                    $muestra["MUE_SERVICIO_HOSPITALAR"] = (!isset($data["muestra"]["servicio"])?0:$data["muestra"]["servicio"]);

                    // PROCEDENCIA
                    $muestra["idas"] = $data["muestra"]["area_salud"];
                    $muestra["idds"] = $data["muestra"]["distrito"];
                    $muestra["MUE_DISTRITO_DISPONIBLE"] =($data["muestra"]["no_distrito_muestra"]=='on'?'0':'1');
                    $muestra["idts"] = $data["muestra"]["tipo_establecimiento"];
                    $muestra["MUE_TIPO_SERVICIO_DISPONIBLE"] =($data["muestra"]["tipo_establecimiento"]=='0'?'0':'1');
                    $muestra["idtss"] = $data["muestra"]["establecimiento"];
                    $muestra["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"] =(!isset($data["muestra"]["otro_establecimiento"])?NULL:trim(strtoupper($data["muestra"]["otro_establecimiento"])));

                    if($data["muestra"]["tipo_establecimiento"]=='0')
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

                    $muestra["MUE_DESCRIPCION_SERVICIO_DISPONIBLE"] =($data["muestra"]["tipo_establecimiento"]=='0'?'0':'1');

                    // Condicion del individuo Fallecido, Hospitalizado, Normal
                    if($individuo["IND_ES_HUMANO"]==1)
                        $muestra["MUE_SITUACION_HOSPITALAR"] = $data["muestra"]["hospitalario"];
                    else
                        $muestra["MUE_SITUACION_HOSPITALAR"] = '3';

                    // Si es dengue calcula la tendencia serológica
                    if($data["muestra"]["evento"]["id_evento"]== Configuration::dengue)
                        $muestra["MUE_TENDENCIA"] = helperString::calcularTendencia($muestra["MUE_FECHA_INICIO"], $muestra["MUE_FECHA_TOMA"]);

                    if($data["muestra"]["evento"]["id_evento"]== Configuration::FLU)
                        $muestra["MUE_TIPO_FLU"] = $data["muestra"]["flu"];
                    else
                        $muestra["MUE_TIPO_FLU"] = '0';

                    // Actualizar datos de caso
                    $muestra = $muestra + $individuo;

                    $filtro = array();
                    $filtro["MUE_ID"] = $idMuestra;
                    $sql = Actions::ActualizarQuery("muestra", $muestra, $filtro);
                    $muestra["MUE_ID"] = $idMuestra;
                    $conn->prepare($sql);
                    $conn->params($muestra);
                    $conn->execute() ? null : $ok = false;
                    $conn->closeStmt();
                    $conn->getID();

                        if(!$ok)
                        {
                            $conn->rollback();
                            $conn->closeConn();
                            return $error;
                            exit;
                        }


                    unset($muestra["MUE_RECHAZADA"]);
                    unset($muestra["RAZ_REC_ID"]);

                    // Actualiza cada una de las que dependen de ella
                    for($i=0; $i<$conteoHermanas; $i++)
                    {
                        unset ($muestra["MUE_ID"]);
                        $filtro = array();
                        $filtro["MUE_ID"] = $hermanas[$i];
                        $sql = Actions::ActualizarQuery("muestra", $muestra, $filtro);
                        $muestra["MUE_ID"] = $hermanas[$i];

                        $conn->prepare($sql);
                        $conn->params($muestra);
                        $conn->execute() ? null : $ok = false;
                        $conn->closeStmt();
                        $conn->getID();

                        if(!$ok)
                        {
                            $conn->rollback();
                            $conn->closeConn();
                            return $error;
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
                        $conn->getID();

                        if(!$ok)
                        {
                            $conn->rollback();
                            $conn->closeConn();
                            return $error;
                            exit;
                        }

                        if($ok)
                            $conteo++;
                    }

                    if($conteoHermanas!=$conteo)
                        $ok = false;
                    else
                        $id = $idMuestra;
                    
                    if(!$ok)
                    {
                        $conn->rollback();
                        $conn->closeConn();
                        return $error;
                        exit;
                    }
            }
        }        

        if ($ok)
        {
            $conn->commit();
        }else
        {
            $conn->rollback();
            $id = -1;
        }

        $conn->closeConn();
        return $id;
    }

    // ANULA LA MUESTRA/ALICUOTA INGRESADA
    public static function Borrar($id)
    {
        //$id = -1;
        $ok = true;
        $conn = new Connection();
        $conn->initConn();

        $conn->begin();
        
        // Si muestra no ha sido enviada ni posee pruebas, ni derivaciones, ni conclusion
        $pruebas = helperMuestra::pruebasAsignadas($id);
        $conclusion = helperMuestra::conclusionAsignada($id);
        $alicuotas = helperMuestra::alicuotasAsignadas($id);
        $estadoDerivacion = helperMuestra::situacionMuestra($id);

        // Puede anular una muestra ssi no tiene pruebas ni derivaciones, y su estado = ventanilla
        if($pruebas[0]==0 && $conclusion[0]==0 && $alicuotas[0]==0 && $estadoDerivacion[0]==Configuration::ventanilla)
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
            $id = $conn->getID();

            if(!$ok)
            {
                $conn->closeConn();
                return $error;
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

            if ($ok){
                    $conn->commit();
            }else{
                    $conn->rollback();
                    $id = -1;
            }
        }
        else
            $id = -1;
        $conn->closeConn();
        return $id;
    }

    // ANULA LA derivacion
    public static function BorrarDerivacion($deriv){
        $id = -1;
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        // Si muestra no ha sido enviada ni posee pruebas
        $pruebas = helperMuestra::pruebasAsignadas($deriv);
        $estadoDerivacion = helperMuestra::situacionMuestra($deriv);

        // Puede anular una muestra ssi no tiene pruebas, y su estado = en area de analisis
        if($pruebas[0]==0 && $estadoDerivacion[0]==Configuration::enAnalisisDer)
        {
            // Actualiza el estado de la muestra a anulado
            $filtro = array();
            $actualizar = array();
            $filtro["MUE_ID"] = $deriv;
            $actualizar["MUE_ACTIVA"] = Configuration::anulada;
            $sql = Actions::ActualizarQuery("muestra", $actualizar, $filtro);
            $actualizar["MUE_ID"] = $deriv;

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
        $conn->closeConn();
        return $id;
    }

    // Para búsqueda
    public function getAll($a,$b, $c, $data, $areas)
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
              evento.EVE_CODIGO as correlativo,
              DATE_FORMAT(muestra.MUE_FECHA_TOMA, '%d/%m/%Y') as ftoma,
              DATE_FORMAT(muestra.MUE_FECHA_RECEPCION, '%d/%m/%Y') as frecepcion,
              muestra.IND_PRIMER_NOMBRE,
              muestra.IND_SEGUNDO_NOMBRE,
              muestra.IND_PRIMER_APELLIDO,
              muestra.IND_SEGUNDO_APELLIDO,
              (CASE WHEN muestra.IND_IDENTIFICADOR_DISPONIBLE = 1 THEN muestra.IND_IDENTIFICADOR ELSE muestra.IND_HISTORIA_CLINICA END) as IDENTIFICADOR,
              evento.EVE_NOMBRE,
              area_analisis.ARE_ANA_NOMBRE,
              muestra.SIT_ID as ubicacion,
              muestra.MUE_TIPO as tipo
            FROM
              muestra
              INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
              INNER JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID)
              INNER JOIN evento_seccion es ON evento.EVE_ID = es.EVE_ID AND es.ARE_ANA_ID = muestra.MUE_ARE_ANA_ID
              INNER JOIN area_analisis ON (es.ARE_ANA_ID = area_analisis.ARE_ANA_ID)
              WHERE muestra.mue_activa = 1 AND muestra.mue_tipo<= 2 ".$filtro
              .($data["nombres"] != "" ? " AND CONCAT(muestra.IND_PRIMER_NOMBRE,' ',muestra.IND_SEGUNDO_NOMBRE)  LIKE '%".trim($data["nombres"])."%'" : "")
              .($data["apellidos"] != "" ? " AND CONCAT(muestra.IND_PRIMER_APELLIDO,' ',muestra.IND_SEGUNDO_APELLIDO)  LIKE '%".trim($data["apellidos"])."%'" : "")
              .($data["identificador"] != "" ? " AND muestra.IND_IDENTIFICADOR  LIKE '%".$data["identificador"]."%'" : "")
              .($data["historia_clinica"] != "" ? " AND muestra.IND_HISTORIA_CLINICA  LIKE '%".$data["historia_clinica"]."%'" : "")
              .($data["area"]==0?'':($data["area"] != "" ? " AND area_analisis.ARE_ANA_ID = ".$data["area"] : ""))
              .($data["evento"]==0?'':($data["evento"] != "" ? " AND evento.EVE_ID = ".$data["evento"] : ""))
              .($data["global_desde"] != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO >= ".$data["global_desde"]: "")
              .($data["global_hasta"] != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO <= ".$data["global_hasta"]: "")
              .($data["correlativo_desde"] != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO >= ".$data["correlativo_desde"]: "")
              .($data["correlativo_hasta"] != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO <= ".$data["correlativo_hasta"]: "")
        
              .($data["toma_desde"] != "" ? " AND muestra.MUE_FECHA_TOMA >= '".$conn->scapeString(helperString::toDate($data["toma_desde"]))."'": "")
              .($data["toma_hasta"] != "" ? " AND muestra.MUE_FECHA_TOMA <= '".$conn->scapeString(helperString::toDate($data["toma_hasta"]))."'": "")
              .($data["recepcion_desde"] != "" ? " AND muestra.MUE_FECHA_RECEPCION >= '".$conn->scapeString(helperString::toDate($data["recepcion_desde"]))."'": "")
              .($data["recepcion_hasta"] != "" ? " AND muestra.MUE_FECHA_RECEPCION <= '".$conn->scapeString(helperString::toDate($data["recepcion_hasta"]))."'": "")
              .($data["rechazada"]==''?'': ($data["rechazada"]==2?'':" AND muestra.MUE_RECHAZADA = ".$data["rechazada"]))                              
              .($data["ubicacion"]==''?'': ($data["ubicacion"]==0?'':($data["ubicacion"]==1?" AND muestra.SIT_ID = ".$data["ubicacion"]:" AND muestra.SIT_ID >= ".$data["ubicacion"])))
              .($data["tipo_muestra"]==''?'': ($data["tipo_muestra"]==0?'':" AND muestra.MUE_TIPO = ".$data["tipo_muestra"]))
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

    public function getCountAll($data, $areas)
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
            count(*) as cantidad
            FROM
              muestra
              INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
              INNER JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID)
              INNER JOIN evento_seccion es ON evento.EVE_ID = es.EVE_ID AND es.ARE_ANA_ID = muestra.MUE_ARE_ANA_ID
              INNER JOIN area_analisis ON (es.ARE_ANA_ID = area_analisis.ARE_ANA_ID)
              WHERE muestra.mue_activa = 1 AND muestra.mue_tipo<= 2 ".$filtro
              .($data["nombres"] != "" ? " AND CONCAT(muestra.IND_PRIMER_NOMBRE,' ',muestra.IND_SEGUNDO_NOMBRE)  LIKE '%".trim($data["nombres"])."%'" : "")
              .($data["apellidos"] != "" ? " AND CONCAT(muestra.IND_PRIMER_APELLIDO,' ',muestra.IND_SEGUNDO_APELLIDO)  LIKE '%".trim($data["apellidos"])."%'" : "")
              .($data["identificador"] != "" ? " AND muestra.IND_IDENTIFICADOR  LIKE '%".$data["identificador"]."%'" : "")
              .($data["historia_clinica"] != "" ? " AND muestra.IND_HISTORIA_CLINICA  LIKE '%".$data["historia_clinica"]."%'" : "")
              .($data["area"]==0?'':($data["area"] != "" ? " AND area_analisis.ARE_ANA_ID = ".$data["area"] : ""))
              .($data["evento"]==0?'':($data["evento"] != "" ? " AND evento.EVE_ID = ".$data["evento"] : ""))
              .($data["global_desde"] != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO >= ".$data["global_desde"]: "")
              .($data["global_hasta"] != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO <= ".$data["global_hasta"]: "")
              .($data["correlativo_desde"] != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO >= ".$data["correlativo_desde"]: "")
              .($data["correlativo_hasta"] != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO <= ".$data["correlativo_hasta"]: "")

              .($data["toma_desde"] != "" ? " AND muestra.MUE_FECHA_TOMA >= '".$conn->scapeString(helperString::toDate($data["toma_desde"]))."'": "")
              .($data["toma_hasta"] != "" ? " AND muestra.MUE_FECHA_TOMA <= '".$conn->scapeString(helperString::toDate($data["toma_hasta"]))."'": "")
              .($data["recepcion_desde"] != "" ? " AND muestra.MUE_FECHA_RECEPCION >= '".$conn->scapeString(helperString::toDate($data["recepcion_desde"]))."'": "")
              .($data["recepcion_hasta"] != "" ? " AND muestra.MUE_FECHA_RECEPCION <= '".$conn->scapeString(helperString::toDate($data["recepcion_hasta"]))."'": "")
              .($data["rechazada"]==''?'': ($data["rechazada"]==2?'':" AND muestra.MUE_RECHAZADA = ".$data["rechazada"]))
              .($data["ubicacion"]==''?'': ($data["ubicacion"]==0?'':($data["ubicacion"]==1?" AND muestra.SIT_ID = ".$data["ubicacion"]:" AND muestra.SIT_ID >= ".$data["ubicacion"])))
              .($data["tipo_muestra"]==''?'': ($data["tipo_muestra"]==0?'':" AND muestra.MUE_TIPO = ".$data["tipo_muestra"]))
              ." ".$this->filtroUbicaciones." ";
        //echo $sql;exit;
        $conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetch();
    	$conn->closeConn();
    	return $data[0];
    }

        public function getAllMuestra($a,$b, $c, $data, $areas)
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
              evento.EVE_CODIGO as correlativo,
              DATE_FORMAT(muestra.MUE_FECHA_TOMA, '%d/%m/%Y') as ftoma,
              DATE_FORMAT(muestra.MUE_FECHA_RECEPCION, '%d/%m/%Y') as frecepcion,
              muestra.IND_PRIMER_NOMBRE,
              muestra.IND_SEGUNDO_NOMBRE,
              muestra.IND_PRIMER_APELLIDO,
              muestra.IND_SEGUNDO_APELLIDO,
              evento.EVE_NOMBRE,
              area_analisis.ARE_ANA_NOMBRE,
              muestra.SIT_ID as ubicacion,
              muestra.MUE_TIPO as tipo
            FROM
              muestra
              INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
              INNER JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID)
              inner join evento_seccion on evento.EVE_ID = evento_seccion.EVE_ID
              INNER JOIN area_analisis ON (evento_seccion.ARE_ANA_ID = area_analisis.ARE_ANA_ID) 
              WHERE muestra.mue_activa = 1 ".$filtro
              .(trim($data["nombres"]) != "" ? " AND CONCAT(muestra.IND_PRIMER_NOMBRE,' ',muestra.IND_SEGUNDO_NOMBRE)  LIKE '%".trim($data["nombres"])."%'" : "")
              .(trim($data["apellidos"]) != "" ? " AND CONCAT(muestra.IND_PRIMER_APELLIDO,' ',muestra.IND_SEGUNDO_APELLIDO)  LIKE '%".trim($data["apellidos"])."%'" : "")
              .(trim($data["identificador"]) != "" ? " AND muestra.IND_IDENTIFICADOR  LIKE '%".trim($data["identificador"])."%'" : "")
              .(trim($data["historia_clinica"]) != "" ? " AND muestra.IND_HISTORIA_CLINICA  LIKE '%".trim($data["historia_clinica"])."%'" : "")
              .($data["area"]==0?'':($data["area"] != "" ? " AND area_analisis.ARE_ANA_ID = ".$data["area"] : ""))
              .($data["evento"]==0?'':($data["evento"] != "" ? " AND evento.EVE_ID = ".$data["evento"] : ""))
              .(trim($data["global_desde"]) != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO >= ".trim($data["global_desde"]): "")
              .(trim($data["global_hasta"]) != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO <= ".trim($data["global_hasta"]): "")
              .(trim($data["correlativo_desde"]) != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO >= ".trim($data["correlativo_desde"]): "")
              .(trim($data["correlativo_hasta"]) != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO <= ".trim($data["correlativo_hasta"]): "")

              .(trim($data["toma_desde"]) != "" ? " AND muestra.MUE_FECHA_TOMA >= '".$conn->scapeString(helperString::toDate($data["toma_desde"]))."'": "")
              .(trim($data["toma_hasta"]) != "" ? " AND muestra.MUE_FECHA_TOMA <= '".$conn->scapeString(helperString::toDate($data["toma_hasta"]))."'": "")
              .(trim($data["recepcion_desde"]) != "" ? " AND muestra.MUE_FECHA_RECEPCION >= '".$conn->scapeString(helperString::toDate($data["recepcion_desde"]))."'": "")
              .(trim($data["recepcion_hasta"]) != "" ? " AND muestra.MUE_FECHA_RECEPCION <= '".$conn->scapeString(helperString::toDate($data["recepcion_hasta"]))."'": "")
              .(trim($data["ubicacion"])==''?'': ($data["ubicacion"]==0?'':($data["ubicacion"]==1?" AND muestra.SIT_ID = ".$data["ubicacion"]:" AND muestra.SIT_ID >= ".$data["ubicacion"])))
              ." ".$this->filtroUbicaciones." "
              ." order by ".$c
              ." limit ".$a.",".$b;
        
        //echo $sql; exit;

        $conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetch();
    	$conn->closeConn();
    	return $data;
    }

    public function getCountAllMuestra($data, $areas)
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

        $sql = "SELECT count(*) AS cantidad
            FROM
              muestra
              INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
              INNER JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID)
              inner join evento_seccion on evento.EVE_ID = evento_seccion.EVE_ID
              INNER JOIN area_analisis ON (evento_seccion.ARE_ANA_ID = area_analisis.ARE_ANA_ID) 
              WHERE muestra.mue_activa = 1 ".$filtro
              .(trim($data["nombres"]) != "" ? " AND CONCAT(muestra.IND_PRIMER_NOMBRE,' ',muestra.IND_SEGUNDO_NOMBRE)  LIKE '%".trim($data["nombres"])."%'" : "")
              .(trim($data["apellidos"]) != "" ? " AND CONCAT(muestra.IND_PRIMER_APELLIDO,' ',muestra.IND_SEGUNDO_APELLIDO)  LIKE '%".trim($data["apellidos"])."%'" : "")
              .(trim($data["identificador"]) != "" ? " AND muestra.IND_IDENTIFICADOR  LIKE '%".trim($data["identificador"])."%'" : "")
              .(trim($data["historia_clinica"]) != "" ? " AND muestra.IND_HISTORIA_CLINICA  LIKE '%".trim($data["historia_clinica"])."%'" : "")
              .($data["area"]==0?'':($data["area"] != "" ? " AND area_analisis.ARE_ANA_ID = ".$data["area"] : ""))
              .($data["evento"]==0?'':($data["evento"] != "" ? " AND evento.EVE_ID = ".$data["evento"] : ""))
              .(trim($data["global_desde"]) != "" ? " AND muestra.mue_codigo_global_numero >= ".trim($data["global_desde"]): "")
              .(trim($data["global_hasta"]) != "" ? " AND muestra.mue_codigo_global_numero <= ".trim($data["global_hasta"]): "")
              .(trim($data["correlativo_desde"]) != "" ? " AND muestra.mue_codigo_correlativo_numero >= ".trim($data["correlativo_desde"]): "")
              .(trim($data["correlativo_hasta"]) != "" ? " AND muestra.mue_codigo_correlativo_numero <= ".trim($data["correlativo_hasta"]): "")

              .(trim($data["toma_desde"]) != "" ? " AND muestra.mue_fecha_toma >= '".$conn->scapeString(helperString::toDate($data["toma_desde"]))."'": "")
              .(trim($data["toma_hasta"]) != "" ? " AND muestra.mue_fecha_toma <= '".$conn->scapeString(helperString::toDate($data["toma_hasta"]))."'": "")
              .(trim($data["recepcion_desde"]) != "" ? " AND muestra.mue_fecha_recepcion >= '".$conn->scapeString(helperString::toDate($data["recepcion_desde"]))."'": "")
              .(trim($data["recepcion_hasta"]) != "" ? " AND muestra.mue_fecha_recepcion <= '".$conn->scapeString(helperString::toDate($data["recepcion_hasta"]))."'": "")
              .($data["ubicacion"]==''?'': ($data["ubicacion"]==0?'':($data["ubicacion"]==1?" AND muestra.SIT_ID = ".$data["ubicacion"]:" AND muestra.SIT_ID >= ".$data["ubicacion"])))
              ." ".$this->filtroUbicaciones." ";

        $conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetchOne();
    	$conn->closeConn();
    	return $data["cantidad"];
    }


    public static function GuardarAlicuotas($data)
    {
        $conteo = $data["alicuotas"]["conteo"];
        $actual = 0;
        $alicuotas = $data["alicuotas"]["alicuotas"];
        $alicuotas = explode('-', $alicuotas);
        $ids = null;

        $padre = dalIngreso::Guardar($data);
        if($padre==-1)
            return null;

        $codigosPadre = helperMuestra::getCodigos($padre);
        $ids[] = $padre;

        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();
        
        for($i=0; $i<$conteo; $i++)
        {
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
                $actualizar["IND_LOCALIDAD_DISPONIBLE"] = $individuo["IND_LOCALIDAD_DISPONIBLE"];
                $actualizar["iddep"] = $individuo["iddep"];
                $actualizar["idmun"] = $individuo["idmun"];
                $actualizar["idlp"] = $individuo["idlp"];

                $actualizar["IND_ES_HUMANO"] = $individuo["IND_ES_HUMANO"];
                $actualizar["IND_PRIMER_NOMBRE"] = $individuo["IND_PRIMER_NOMBRE"];
                $actualizar["IND_SEGUNDO_NOMBRE"] = $individuo["IND_SEGUNDO_NOMBRE"];
                $actualizar["IND_PRIMER_APELLIDO"] = $individuo["IND_PRIMER_APELLIDO"];
                $actualizar["IND_SEGUNDO_APELLIDO"] = $individuo["IND_SEGUNDO_APELLIDO"];
                $actualizar["IND_IDENTIFICADOR"] = $individuo["IND_IDENTIFICADOR"];
                $actualizar["IND_IDENTIFICADOR_DISPONIBLE"] = $individuo["IND_IDENTIFICADOR_DISPONIBLE"];
                $actualizar["IND_SEXO"] = $individuo["IND_SEXO"];
                $actualizar["TIP_VIG_ID"] = $individuo["TIP_VIG_ID"];
                $actualizar["IND_ANONIMO"] = $individuo["IND_ANONIMO"];
                $actualizar["IND_DIRECCION"] = $individuo["IND_DIRECCION"];
                $actualizar["IND_DIRECCION_DISPONIBLE"] = $individuo["IND_DIRECCION_DISPONIBLE"];
                $actualizar["IND_LOCALIDAD_DISPONIBLE"] = $individuo["IND_LOCALIDAD_DISPONIBLE"];
                $actualizar["IND_CASADA"] = $individuo["IND_CASADA"];

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

            if($ok==false)
            {
                $conn->closeConn();
                return null;
            }

            // DATOS DE LA MUESTRA
            $codigoGlobal["mue_codigo_global_numero"] = $codigosPadre[0]["mue_codigo_global_numero"];
            $codigoGlobal["mue_codigo_global_anio"] = $codigosPadre[0]["mue_codigo_global_anio"];
            $codigoEvento = helperMuestra::getCodigoEvento($alicuotas[$i]);

            $muestra = array();
            $muestra["MUE_CODIGO_GLOBAL_ANIO"] = $codigoGlobal["mue_codigo_global_anio"];
            $muestra["MUE_CODIGO_GLOBAL_NUMERO"] = ((int)$codigoGlobal["mue_codigo_global_numero"]);
            $muestra["MUE_CODIGO_CORRELATIVO_NUMERO"] = ((int)$codigoEvento[0]["eve_correlativo"])+1;
            $muestra["MUE_CODIGO_CORRELATIVO_ALFA"] = $codigoEvento[0]["eve_codigo"];
            $muestra["MUE_FECHA_INICIO"] = (!isset($data["muestra"]["fecha_inicio_sintomas"])? NULL:helperString::toDate($data["muestra"]["fecha_inicio_sintomas"]));
            $muestra["MUE_FECHA_TOMA"] = (!isset($data["muestra"]["fecha_toma"])? NULL:helperString::toDate($data["muestra"]["fecha_toma"]));
            $muestra["MUE_FECHA_RECEPCION"] = (!isset($data["muestra"]["fecha_recepcion"])? NULL:helperString::toDate($data["muestra"]["fecha_recepcion"]));

            $muestra["MUE_RECHAZADA"] =($data["muestra"]["no_rechazada"]=='on'?'0':'1');
            $muestra["RAZ_REC_ID"] = $data["muestra"]["razon_rechazo"];
            $muestra["MUE_COMENTARIOS"] = trim($data["muestra"]["comentarios_adicionales"]);

            // REQUIERE DE LOGIN
            $muestra["MUE_INGRESADA_POR"] = clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos();
            $muestra["MUE_OTRO_ESTABLECIMIENTO_NOMBRE"] = (!isset($data["muestra"]["otro_establecimiento"])?NULL:trim($data["muestra"]["otro_establecimiento"]));
            $muestra["MUE_REFERIDA_POR"] = (!isset($data["muestra"]["referida_por"])?NULL:trim($data["muestra"]["referida_por"]));

            if($data["individuo"]["id_individuo"]=='-1')
                $muestra["IND_ID"] = $idIndividuo;
            else
                $muestra["IND_ID"] = $data["individuo"]["id_individuo"];

            $muestra["MUE_CARGA_VIRAL"] =($data["muestra"]["carga_viral_check"]=='on'?'1':'2');

            // DONADOR, PACIENTE O NINGUNO
            if($data["muestra"]["evento"]["donador"]==1)
            {
                if($data["muestra"]["chk_donador"]=='on')
                    $muestra["MUE_DONADOR"] ='1';
                else
                    $muestra["MUE_DONADOR"] ='2';
            }
            else
                $muestra["MUE_DONADOR"] = 3;


            // MUESTRA SEROLOGICA
            $muestra["MUE_SEROLOGICA"] =($data["muestra"]["encuesta_serologica_check"]=='on'?'1':'2');

            $muestra["SIT_ID"] = Configuration::ventanilla;

            $muestra["MUE_ANTECEDENTES_INTERES"] = (!isset($data["muestra"]["antecedentes"])?NULL:trim($data["muestra"]["antecedentes"]));
            $muestra["SIT_ID"] = Configuration::ventanilla;

            $tipo_muestra = helperMuestra::getIDEventoTipoMuestra($alicuotas[$i], $data["muestra"]["tipo"]);
            $muestra["TIP_MUE_EVE_ID"] = $tipo_muestra[0];

            // En este punto no ha sido enviada ni recibida
            $muestra["MUE_ENVIADA_POR"] = NULL;
            $muestra["MUE_RECIBIDA_POR"] = NULL;

            $muestra["MUE_RESULTADO_EXTERNO"] = (!isset($data["muestra"]["resultados_externos"])?NULL:trim($data["muestra"]["resultados_externos"]));
            $muestra["MUE_ACTIVA"] = 1;
            $muestra["EST_MOT_ID"] = 1;

            $muestra["MUE_FECHA_ENVIO"] = NULL;
            $muestra["MUE_FECHA_RECEPCION_ANALISIS"] = NULL;
            $muestra["MUE_SEMANA_EPI"] = (!isset($data["muestra"]["semana"])?0:$data["muestra"]["semana"]);
            $muestra["MUE_INGRESADA_POR"] = clsCaus::obtenerNombres().' '.clsCaus::obtenerApellidos();

            $muestra["MUE_SERVICIO_HOSPITALAR"] = (!isset($data["muestra"]["servicio"])?0:$data["muestra"]["servicio"]);

            $muestra["MUE_SEROLOGICA"] =($data["muestra"]["encuesta_serologica_check"]=='on'?'1':'2');

            // PROCEDENCIA
            $muestra["idas"] = $data["muestra"]["area_salud"];

            $muestra["idds"] = $data["muestra"]["distrito"];
            $muestra["MUE_DISTRITO_DISPONIBLE"] =($data["muestra"]["no_distrito_muestra"]=='on'?'0':'1');

            $muestra["idtss"] = $data["muestra"]["tipo_establecimiento"];

            $muestra["MUE_TIPO_SERVICIO_DISPONIBLE"] =($data["muestra"]["tipo_establecimiento"]=='0'?'0':'1');

            $muestra["idts"] = $data["muestra"]["establecimiento"];

            if($data["muestra"]["tipo_establecimiento"]=='0')
                $muestra["MUE_DESCRIPCION_SERVICIO_DISPONIBLE"] = '0';
            else
            {
                if($muestra["MUE_OTRO_ESTABLECIMIENTO"]!="")
                {
                    $muestra["MUE_DESCRIPCION_SERVICIO_DISPONIBLE"] ='2';
                    $muestra["idts"] = 0;
                }
                else
                    $muestra["MUE_DESCRIPCION_SERVICIO_DISPONIBLE"] ='1';
            }

            $muestra["MUE_DESCRIPCION_SERVICIO_DISPONIBLE"] =($data["muestra"]["tipo_establecimiento"]=='0'?'0':'1');

            // Condicion del individuo Fallecido, Hospitalizado, Normal
            if($individuo["IND_ES_HUMANO"]==1)
                $muestra["MUE_SITUACION_HOSPITALAR"] = $data["muestra"]["hospitalario"];
            else
                $muestra["MUE_SITUACION_HOSPITALAR"] = '3';

            $muestra["MUE_PADRE"] = $padre;
            $muestra["MUE_TIPO"] = Configuration::tipoAlicuotaIngresada;
            $muestra["DER_FECHA"] = $muestra["MUE_FECHA_TOMA"];

            // Guarda datos de caso
            $muestra = $muestra + $individuo;

            // Inserta datos de muestra
            $sql = Actions::AgregarQuery("muestra", $muestra);
            $conn->prepare($sql);
            $conn->params($muestra);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $idAlicuota = $conn->getID();

            if($ok==false)
            {
//                echo 'error '.$i.'<br/>';
                $conn->closeConn();
                return null;
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

            if($ok==false)
            {
//                echo 'error '.$i.'<br/>';
                $conn->closeConn();
                return null;
            }

            // Actualiza evento (correlativo)
            $sql = helperMuestra::increaseCodigoMuestra($alicuotas[$i]);
            $conn->prepare($sql);
            $conn->execute() ? null : $ok = false;
            $conn->closeStmt();
            $conn->getID();

            if($ok==false)
            {
//                echo 'error '.$i.'<br/>';
                $conn->closeConn();
                return null;
            }

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
            $op = $conn->getID();

            if($ok==false)
            {
//                echo 'error '.$i.'<br/>';
                $conn->closeConn();
                return null;
            }
            else
            {
                $actual++;
                $ids[] = $idAlicuota;
            }
        }

        // Se pudieron ingresar todas las alicuotas
        if($conteo==$actual)
        {
            if(is_array($ids))
            {
                $conn->commit();
                $conn->closeConn();
                return $ids;
            }
        }
    }

    public function getAllRecibidos($a,$b, $c, $data, $areas)
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
              evento.EVE_CODIGO as correlativo,
              DATE_FORMAT(muestra.MUE_FECHA_TOMA, '%d/%m/%Y') as ftoma,
              DATE_FORMAT(muestra.MUE_FECHA_RECEPCION, '%d/%m/%Y') as frecepcion,
              muestra.IND_PRIMER_NOMBRE,
              muestra.IND_SEGUNDO_NOMBRE,
              muestra.IND_PRIMER_APELLIDO,
              muestra.IND_SEGUNDO_APELLIDO,
              (CASE WHEN muestra.IND_IDENTIFICADOR_DISPONIBLE = 1 THEN muestra.IND_IDENTIFICADOR ELSE muestra.IND_HISTORIA_CLINICA END) as IDENTIFICADOR,
              muestra.IND_HISTORIA_CLINICA,
              evento.EVE_NOMBRE,
              evento.EVE_ID,
              area_analisis.ARE_ANA_NOMBRE,
              muestra.SIT_ID as ubicacion
            FROM
              muestra
              INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
              LEFT JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID) 
              LEFT JOIN evento_seccion es ON evento.EVE_ID = es.EVE_ID AND es.ARE_ANA_ID = muestra.MUE_ARE_ANA_ID 
              LEFT JOIN area_analisis ON (es.ARE_ANA_ID = area_analisis.ARE_ANA_ID)
              WHERE muestra.mue_activa = 1 AND muestra.SIT_ID>=".Configuration::recibidaAnalisis.' AND muestra.MUE_TIPO=1 '.$filtro
              .($data["nombres"] != "" ? " AND CONCAT(muestra.IND_PRIMER_NOMBRE,' ',muestra.IND_SEGUNDO_NOMBRE)  LIKE '%".trim($data["nombres"])."%'" : "")
              .($data["apellidos"] != "" ? " AND CONCAT(muestra.IND_PRIMER_APELLIDO,' ',muestra.IND_SEGUNDO_APELLIDO)  LIKE '%".trim($data["apellidos"])."%'" : "")
              .($data["identificador"] != "" ? " AND muestra.IND_IDENTIFICADOR  LIKE '%".$data["identificador"]."%'" : "")
              .($data["historia_clinica"] != "" ? " AND muestra.IND_HISTORIA_CLINICA  LIKE '%".$data["historia_clinica"]."%'" : "")
              .($data["area"]==0?'':($data["area"] != "" ? " AND area_analisis.ARE_ANA_ID = ".$data["area"] : ""))
              .($data["evento"]==0?'':($data["evento"] != "" ? " AND evento.EVE_ID = ".$data["evento"] : ""))
              .($data["global_desde"] != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO >= ".$data["global_desde"]: "")
              .($data["global_hasta"] != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO <= ".$data["global_hasta"]: "")
              .($data["correlativo_desde"] != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO >= ".$data["correlativo_desde"]: "")
              .($data["correlativo_hasta"] != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO <= ".$data["correlativo_hasta"]: "")

              .($data["toma_desde"] != "" ? " AND muestra.MUE_FECHA_TOMA >= '".$conn->scapeString(helperString::toDate($data["toma_desde"]))."'": "")
              .($data["toma_hasta"] != "" ? " AND muestra.MUE_FECHA_TOMA <= '".$conn->scapeString(helperString::toDate($data["toma_hasta"]))."'": "")
              .($data["recepcion_desde"] != "" ? " AND muestra.MUE_FECHA_RECEPCION >= '".$conn->scapeString(helperString::toDate($data["recepcion_desde"]))."'": "")
              .($data["recepcion_hasta"] != "" ? " AND muestra.MUE_FECHA_RECEPCION <= '".$conn->scapeString(helperString::toDate($data["recepcion_hasta"]))."'": "")
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

    public function getCountAllRecibidos($data, $areas)
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

        $sql = "SELECT count(*) AS cantidad
            FROM
              muestra
              INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
              LEFT JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID) 
              LEFT JOIN evento_seccion es ON evento.EVE_ID = es.EVE_ID AND es.ARE_ANA_ID = muestra.MUE_ARE_ANA_ID 
              LEFT JOIN area_analisis ON (es.ARE_ANA_ID = area_analisis.ARE_ANA_ID)
              WHERE muestra.mue_activa = 1 AND muestra.SIT_ID>=".Configuration::recibidaAnalisis.' AND muestra.MUE_TIPO=1 '.$filtro
              .($data["nombres"] != "" ? " AND CONCAT(muestra.IND_PRIMER_NOMBRE,' ',muestra.IND_SEGUNDO_NOMBRE)  LIKE '%".trim($data["nombres"])."%'" : "")
              .($data["apellidos"] != "" ? " AND CONCAT(muestra.IND_PRIMER_APELLIDO,' ',muestra.IND_SEGUNDO_APELLIDO)  LIKE '%".trim($data["apellidos"])."%'" : "")
              .($data["identificador"] != "" ? " AND muestra.IND_IDENTIFICADOR  LIKE '%".$data["identificador"]."%'" : "")
              .($data["historia_clinica"] != "" ? " AND muestra.IND_HISTORIA_CLINICA  LIKE '%".$data["historia_clinica"]."%'" : "")
              .($data["area"]==0?'':($data["area"] != "" ? " AND area_analisis.ARE_ANA_ID = ".$data["area"] : ""))
              .($data["evento"]==0?'':($data["evento"] != "" ? " AND evento.EVE_ID = ".$data["evento"] : ""))
              .($data["global_desde"] != "" ? " AND muestra.mue_codigo_global_numero >= ".$data["global_desde"]: "")
              .($data["global_hasta"] != "" ? " AND muestra.mue_codigo_global_numero <= ".$data["global_hasta"]: "")
              .($data["correlativo_desde"] != "" ? " AND muestra.mue_codigo_correlativo_numero >= ".$data["correlativo_desde"]: "")
              .($data["correlativo_hasta"] != "" ? " AND muestra.mue_codigo_correlativo_numero <= ".$data["correlativo_hasta"]: "")

              .($data["toma_desde"] != "" ? " AND muestra.mue_fecha_toma >= '".$conn->scapeString(helperString::toDate($data["toma_desde"]))."'": "")
              .($data["toma_hasta"] != "" ? " AND muestra.mue_fecha_toma <= '".$conn->scapeString(helperString::toDate($data["toma_hasta"]))."'": "")
              .($data["recepcion_desde"] != "" ? " AND muestra.mue_fecha_recepcion >= '".$conn->scapeString(helperString::toDate($data["recepcion_desde"]))."'": "")
              .($data["recepcion_hasta"] != "" ? " AND muestra.mue_fecha_recepcion <= '".$conn->scapeString(helperString::toDate($data["recepcion_hasta"]))."'": "")
              ." ".$this->filtroUbicaciones." ";
        $conn->prepare($sql);
    	$conn->execute();
    	$data = $conn->fetchOne();
    	$conn->closeConn();
    	return $data["cantidad"];
    }
}
?>