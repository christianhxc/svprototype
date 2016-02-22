<?php

require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperUceti.php');
require_once ('libs/caus/clsCaus.php');

class dalLdbi {

    private $search;
    private $filtroUbicaciones;

    public function __construct($search) {
        $this->search = $search;

        // Filtrar los resultados de búsquedas por permisos de procedencia
        // según división sanitaria del país            
        $lista = clsCaus::obtenerUbicacionesCascada();
        if (is_array($lista)) {
            foreach ($lista as $elemento) {
                $temporal = "";
//                        if ($elemento[ConfigurationCAUS::AreaSalud] != "")
//                            $temporal .= "muestra.idas = '".$elemento[ConfigurationCAUS::AreaSalud]."' ";
//
//                        if ($elemento[ConfigurationCAUS::DistritoSalud] != "")
//                            $temporal .= ($temporal != '' ? "and " : "")."muestra.idds = '".$elemento[ConfigurationCAUS::DistritoSalud]."' ";

                if ($elemento[ConfigurationCAUS::ServicioSalud] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.idts = '" . $elemento[ConfigurationCAUS::ServicioSalud] . "' ";

                $this->filtroUbicaciones .= ($this->filtroUbicaciones != '' ? "or " : "") . "(" . $temporal . ") ";
            }
        }

        if ($this->filtroUbicaciones != "")
            $this->filtroUbicaciones = "and (" . $this->filtroUbicaciones . ")";
    }

    public static function GuardarExistencia($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $envio = $data["envio"];
        unset($envio["id_envio"]);
        $envio["fh_envio"] = helperString::toDate($envio["fh_envio"]);
        $envio["fh_ingreso"] = helperString::toDate($envio["fh_ingreso"]);
        $envio_detalle = $data["existencias"];		
        
        $yaExiste = 0;

        if ($yaExiste == 0) {
            $param = array();
            $param = dalLdbi::GuardarTabla($conn, "vac_LDBI_envio", $envio);
            $idEnvio = $param['id'];
            $ok = $param['ok'];

            $param = dalLdbi::GuardarBitacora($conn, "1", "vac_LDBI_envio");
            $id = $param['id'];
            $ok = $param['ok'];

			//filtro["id_envio"] = $idEnvio;
			//$param = dalLdbi::BorrarTabla($conn, "vac_LDBI_envio_detalle", $filtro);
			//$ok = $param['ok'];
			
            if ($idEnvio != null && $ok){
                foreach ($envio_detalle as &$detalle) {
                    $detalle["id_envio"] = $idEnvio;
                    $detalle["fh_vencimiento"] = helperString::toDate($detalle["fh_vencimiento"]);
                    $detalle["fh_ingreso"] = $envio["fh_ingreso"];
                    $detalle["fh_inventario"] = $envio["fh_envio"];
                    $param = dalLdbi::GuardarTabla($conn, "vac_LDBI_inventario", $detalle);
                    $ok = $param['ok'];
                    if (!$ok) break;
                }

                $param = dalLdbi::GuardarBitacora($conn, "1", "vac_LDBI_inventario");
                $ok = $param['ok'];
            } else {
                $ok = false;
            }
            
            if ($ok)
                $conn->commit();
            else {
                $conn->rollback();
                $id = -1;
            }

            $conn->closeConn();
            return 1;
        } else {
            $conn->closeConn();
            return 2;
        }
    }

    public static function ActualizarExistencia($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $envio = $data["envio"];
        $envio["fh_envio"] = helperString::toDate($envio["fh_envio"]);
        $envio["fh_ingreso"] = helperString::toDate($envio["fh_ingreso"]);
        $envio_detalle = $data["existencias"];

        $yaExiste = 0;

        if ($yaExiste == 0) {
            $param = array();
            $filtro["id_envio"] = $envio["id_envio"];

            unset($envio["id_envio"]);
            $campos = $envio;
            $envio["id_envio"] = $filtro["id_envio"];
            $param = dalLdbi::ActualizarTabla($conn, "vac_LDBI_envio", $campos, $filtro, $envio);
            $ok = $param['ok'];

            $param = dalLdbi::GuardarBitacora($conn, "2", "vac_LDBI_envio");
            $ok = $param['ok'];

            $param = dalLdbi::BorrarTabla($conn, "vac_LDBI_inventario", $filtro);
            $ok = $param['ok'];

            if ($ok){
                foreach ($envio_detalle as &$detalle) {
                    $detalle["id_envio"] = $envio["id_envio"];
                    $detalle["fh_vencimiento"] = helperString::toDate($detalle["fh_vencimiento"]);
                    $detalle["fh_ingreso"] = $envio["fh_ingreso"];
                    $detalle["fh_inventario"] = $envio["fh_envio"];
                    $param = dalLdbi::GuardarTabla($conn, "vac_LDBI_inventario", $detalle);
                    $ok = $param['ok'];
                    if (!$ok) break;
                }

                $param = dalLdbi::GuardarBitacora($conn, "2", "vac_LDBI_inventario");
                $ok = $param['ok'];
            } else {
                $ok = false;
            }

            if ($ok)
                $conn->commit();
            else {
                $conn->rollback();
                $id = -1;
            }

            $conn->closeConn();
            return 1;
        } else {
            $conn->closeConn();
            return 2;
        }
    }

    public static function ActualizarRequesicion($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $envio = $data["requesicion"];
        $envio["fh_requesicion"] = helperString::toDate($envio["fh_requesicion"]);
        $envio["fh_ingreso"] = helperString::toDate($envio["fh_ingreso"]);
        $envio_detalle = $data["existencias"];

        $yaExiste = 0;

        if ($yaExiste == 0) {
            $param = array();
            $filtro["id_requesicion"] = $envio["id_requesicion"];

            unset($envio["id_requesicion"]);
            $campos = $envio;
            $envio["id_requesicion"] = $filtro["id_requesicion"];
            $param = dalLdbi::ActualizarTabla($conn, "vac_LDBI_requesicion", $campos, $filtro, $envio);
            $ok = $param['ok'];

            $param = dalLdbi::GuardarBitacora($conn, "2", "vac_LDBI_requesicion");
            $ok = $param['ok'];

            $param = dalLdbi::BorrarTabla($conn, "vac_LDBI_requesicion_detalle", $filtro);
            $ok = $param['ok'];

            if ($ok){
                foreach ($envio_detalle as &$detalle) {
                    $detalle["id_requesicion"] = $envio["id_requesicion"];
                    //$detalle["fh_vencimiento"] = helperString::toDate($detalle["fh_vencimiento"]);
                    $param = dalLdbi::GuardarTabla($conn, "vac_LDBI_requesicion_detalle", $detalle);
                    $ok = $param['ok'];
                    if (!$ok) break;
                }

                $param = dalLdbi::GuardarBitacora($conn, "2", "vac_LDBI_requesicion_detalle");
                $ok = $param['ok'];
            } else {
                $ok = false;
            }

            if ($ok)
                $conn->commit();
            else {
                $conn->rollback();
                $id = -1;
            }

            $conn->closeConn();
            return 1;
        } else {
            $conn->closeConn();
            return 2;
        }
    }

    public static function ActualizarEnvioBodega($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $envio = $data["envio"];
        $envio["fh_envio"] = helperString::toDate($envio["fh_envio"]);
        $envio["fh_despacho"] = helperString::toDate($envio["fh_despacho"]);
        $envio["fh_ingreso"] = helperString::toDate($envio["fh_ingreso"]);
        $envio_detalle = $data["existencias"];

        $yaExiste = 0;

        $id = $envio["id_envio"];

        if ($yaExiste == 0) {
            $param = array();
            $filtro = null;
            $filtro["id_envio"] = $envio["id_envio"];

            unset($envio["id_envio"]);
            unset($envio["no_requesicion"]);
            $param = dalLdbi::ActualizarTabla($conn, "vac_LDBI_envio_bodega", $envio, $filtro, array_merge($envio,$filtro));
            $ok = $param['ok'];

            $param = dalLdbi::GuardarBitacora($conn, "2", "vac_LDBI_envio_bodega");
            $ok = $param['ok'];

            $filtro = null;
            $filtro["id_requesicion"] = $envio["id_requesicion"];
            $requesicion["status"] = $envio["status"];
            $param = dalLdbi::ActualizarTabla($conn, "vac_LDBI_requesicion", $requesicion, $filtro, array_merge($requesicion,$filtro));
            $ok = $param['ok'];

            $param = dalLdbi::GuardarBitacora($conn, "2", "vac_LDBI_requesicion");
            $ok = $param['ok'];

            $param = dalLdbi::BorrarTabla($conn, "vac_LDBI_envio_bodega_detalle", array('id_envio' => $id));
            $ok = $param['ok'];

            $param = dalLdbi::BorrarTabla($conn, "vac_LDBI_inventario", array('id_envio_bodega' => $id));
            $ok = $param['ok'];

            if ($id != null && $ok){
                foreach ($envio_detalle as $key => $detalle) {
                    $filtro = null;
                    $filtro["id_requesicion"] = $envio["id_requesicion"];
                    $filtro["id_insumo"] = $key;
//                    $detalle["fh_vencimiento"] = helperString::toDate($detalle["fh_vencimiento"]);
                    if ($detalle["extra"] == 1){
                        unset($detalle["extra"]);
                        unset($filtro["id_requesicion"]);
                        $detalle["id_envio"] = $id;
                        $detalle["id_insumo"] = $key;
                        $param = dalLdbi::GuardarTabla($conn, "vac_LDBI_envio_bodega_detalle", $detalle);
                    } else {
                        unset($detalle["extra"]);
                        unset($filtro["id_envio"]);
                        $param = dalLdbi::ActualizarTabla($conn, "vac_LDBI_requesicion_detalle", $detalle, $filtro, array_merge($detalle,$filtro));
                    }
                    $ok = $param['ok'];

                    if ($ok){
                        $inventario = $detalle;
                        $inventario["id_envio"] = 0;
                        $inventario["id_envio_bodega"] = $id;
                        $inventario["id_insumo"] = $key;
                        $inventario["cantidad"] = $inventario["enviado"];
                        $inventario["fh_ingreso"] = $envio["fh_ingreso"];
                        $inventario["fh_inventario"] = $envio["fh_despacho"];
                        $inventario["bodega_central"] = 0;
                        $inventario["id_region"] = $envio["id_region_destino"];
                        $inventario["id_un"] = $envio["id_un_destino"];
                        unset($inventario["enviado"]);
                        $param = dalLdbi::GuardarTabla($conn, "vac_LDBI_inventario", $inventario);
                        $ok = $param['ok'];

                        $inventario["movimiento"] = -1;
                        $inventario["bodega_central"] = $envio["bodega_central"];
                        $inventario["id_region"] = $inventario["bodega_central"] == 1 ? 0 : $envio["id_region_origen"];
                        $inventario["id_un"] = $inventario["bodega_central"] == 1 ? 0 : $envio["id_un_origen"];
                        $param = dalLdbi::GuardarTabla($conn, "vac_LDBI_inventario", $inventario);
                        $ok = $param['ok'];
                    }

                    if (!$ok) break;
                }

                $param = dalLdbi::GuardarBitacora($conn, "2", "vac_LDBI_requesicion_detalle");
                $ok = $param['ok'];
            } else {
                $ok = false;
            }

            if ($ok)
                $conn->commit();
            else {
                $conn->rollback();
                $id = -1;
            }

            $conn->closeConn();
            return 1;
        } else {
            $conn->closeConn();
            return 2;
        }
    }

    public static function GuardarRequesicion($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $envio = $data["requesicion"];
        unset($envio["id_requesicion"]);
        $envio["fh_requesicion"] = helperString::toDate($envio["fh_requesicion"]);
        $envio["fh_ingreso"] = helperString::toDate($envio["fh_ingreso"]);
        $envio_detalle = $data["existencias"];

        $yaExiste = 0;

        if ($yaExiste == 0) {
            $param = array();
            $param = dalLdbi::GuardarTabla($conn, "vac_LDBI_requesicion", $envio);
            $id = $param['id'];
            $ok = $param['ok'];

            $param = dalLdbi::GuardarBitacora($conn, "1", "vac_LDBI_requesicion");
            $ok = $param['ok'];

            if ($id != null && $ok){
                foreach ($envio_detalle as &$detalle) {
                    $detalle["id_requesicion"] = $id;
                    //$detalle["fh_vencimiento"] = helperString::toDate($detalle["fh_vencimiento"]);
                    $param = dalLdbi::GuardarTabla($conn, "vac_LDBI_requesicion_detalle", $detalle);
                    $ok = $param['ok'];
                    if (!$ok) break;
                }

                $param = dalLdbi::GuardarBitacora($conn, "1", "vac_LDBI_requesicion_detalle");
                $ok = $param['ok'];
            } else {
                $ok = false;
            }

            if ($ok)
                $conn->commit();
            else {
                $conn->rollback();
                $id = -1;
            }

            $conn->closeConn();
            return 1;
        } else {
            $conn->closeConn();
            return 2;
        }
    }

    public static function GuardarEnvioBodega($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $envio = $data["envio"];
        $envio["fh_envio"] = helperString::toDate($envio["fh_envio"]);
        $envio["fh_despacho"] = helperString::toDate($envio["fh_despacho"]);
        $envio["fh_ingreso"] = helperString::toDate($envio["fh_ingreso"]);
        if ($envio["id_region_origen"] == -1) $envio["id_region_origen"] = 0;
        $envio_detalle = $data["existencias"];

        $yaExiste = 0;

        if ($yaExiste == 0) {
            $param = array();
            $param = dalLdbi::GuardarTabla($conn, "vac_LDBI_envio_bodega", $envio);
            $id = $param['id'];
            $ok = $param['ok'];

            $param = dalLdbi::GuardarBitacora($conn, "1", "vac_LDBI_envio_bodega");
            $ok = $param['ok'];

            $filtro = null;
            $filtro["id_requesicion"] = $envio["id_requesicion"];
            $requesicion["status"] = $envio["status"];
            $param = dalLdbi::ActualizarTabla($conn, "vac_LDBI_requesicion", $requesicion, $filtro, array_merge($requesicion,$filtro));
            $ok = $param['ok'];

            $param = dalLdbi::GuardarBitacora($conn, "2", "vac_LDBI_requesicion");
            $ok = $param['ok'];

            if ($id != null && $ok){
                foreach ($envio_detalle as $key => $detalle) {
                    $filtro = null;
                    $filtro["id_requesicion"] = $envio["id_requesicion"];
                    $filtro["id_insumo"] = $key;
//                    $detalle["fh_vencimiento"] = helperString::toDate($detalle["fh_vencimiento"]);
                    if ($detalle["extra"] == 1){
                        unset($detalle["extra"]);
                        unset($filtro["id_requesicion"]);
                        $detalle["id_envio"] = $id;
                        $detalle["id_insumo"] = $key;
                        $param = dalLdbi::GuardarTabla($conn, "vac_LDBI_envio_bodega_detalle", $detalle);
                    } else {
                        unset($detalle["extra"]);
                        unset($filtro["id_envio"]);
                        $param = dalLdbi::ActualizarTabla($conn, "vac_LDBI_requesicion_detalle", $detalle, $filtro, array_merge($detalle,$filtro));
                    }
                    $ok = $param['ok'];

                    if ($ok){
                        $inventario = $detalle;
                        $inventario["id_envio"] = 0;
                        $inventario["id_envio_bodega"] = $id;
                        $inventario["id_insumo"] = $key;
                        $inventario["cantidad"] = $inventario["enviado"];
                        $inventario["fh_ingreso"] = $envio["fh_ingreso"];
                        $inventario["fh_inventario"] = $envio["fh_despacho"];
                        $inventario["bodega_central"] = 0;
                        $inventario["id_region"] = $envio["id_region_destino"];
                        $inventario["id_un"] = $envio["id_un_destino"];
                        unset($inventario["enviado"]);
                        $param = dalLdbi::GuardarTabla($conn, "vac_LDBI_inventario", $inventario);
                        $ok = $param['ok'];

                        $inventario["movimiento"] = -1;
                        $inventario["bodega_central"] = $envio["bodega_central"];
                        $inventario["id_region"] = $inventario["bodega_central"] == 1 ? 0 : $envio["id_region_origen"];
                        $inventario["id_un"] = $inventario["bodega_central"] == 1 ? 0 : $envio["id_un_origen"];
                        $param = dalLdbi::GuardarTabla($conn, "vac_LDBI_inventario", $inventario);
                        $ok = $param['ok'];
                    }

                    if (!$ok) break;
                }

                $param = dalLdbi::GuardarBitacora($conn, "2", "vac_LDBI_requesicion_detalle");
                $ok = $param['ok'];
            } else {
                $ok = false;
            }

            if ($ok)
                $conn->commit();
            else {
                $conn->rollback();
                $id = -1;
            }

            $conn->closeConn();
            return 1;
        } else {
            $conn->closeConn();
            return 2;
        }
    }

    public static function Borrar($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $param = array();

        $filtro = array();
        $filtro["id_envio"] = $data['id'];

        $param = dalLdbi::BorrarTabla($conn, "vac_LDBI_inventario", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];

        $param = dalLdbi::GuardarBitacora($conn, "3", "vac_LDBI_inventario");
        $ok = $param['ok'];

        if ($ok){
            $param = dalLdbi::BorrarTabla($conn, "vac_LDBI_envio", $filtro);
            $id = $param['id'];
            $ok = $param['ok'];
            //print_r($param);

            $param = dalLdbi::GuardarBitacora($conn, "3", "vac_LDBI_envio");
        }

        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);
        if ($ok)
            $conn->commit();
        else {
            $conn->rollback();
            $id = -1;
        }

        $conn->closeConn();
        if (!$ok)
            return 2;
        return 1;
    }

    public static function BorrarRequesicion($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $param = array();

        $filtro = array();
        $filtro["id_requesicion"] = $data['id'];

        $param = dalLdbi::BorrarTabla($conn, "vac_LDBI_requesicion_detalle", $filtro);
        $id = $param['id'];
        $ok = $param['ok'];

        $param = dalLdbi::GuardarBitacora($conn, "3", "vac_LDBI_requesicion_detalle");

        if ($ok){
            $param = dalLdbi::BorrarTabla($conn, "vac_LDBI_requesicion", $filtro);
            $id = $param['id'];
            $ok = $param['ok'];
            //print_r($param);

            $param = dalLdbi::GuardarBitacora($conn, "3", "vac_LDBI_requesicion");
        }

        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);
        if ($ok)
            $conn->commit();
        else {
            $conn->rollback();
            $id = -1;
        }

        $conn->closeConn();
        if (!$ok)
            return 2;
        return 1;
    }

    public static function BorrarEnvioBodega($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $param = array();

        $filtro = array();
        $filtro["id_envio"] = $data['id'];

        $param = dalLdbi::BorrarTabla($conn, "vac_LDBI_envio_bodega_detalle", $filtro);
        $ok = $param['ok'];

        $param = dalLdbi::GuardarBitacora($conn, "3", "vac_LDBI_envio_bodega_detalle");
        $ok = $param['ok'];

        $param = dalLdbi::BorrarTabla($conn, "vac_LDBI_envio_bodega", $filtro);
        $ok = $param['ok'];

        $param = dalLdbi::GuardarBitacora($conn, "3", "vac_LDBI_envio_bodega");
        $ok = $param['ok'];

        $param = dalLdbi::BorrarTabla($conn, "vac_LDBI_inventario", $filtro);
        $ok = $param['ok'];

        $param = dalLdbi::GuardarBitacora($conn, "3", "vac_LDBI_inventario");
        $ok = $param['ok'];

        if ($ok){
            $filtro = null;
            $filtro["id_requesicion"] = $data["req"];
            $detalle["enviado"] = '0';
            $detalle["fh_vencimiento"] = '';
            $detalle["no_lote"] = '';
            $param = dalLdbi::ActualizarTabla($conn, "vac_LDBI_requesicion_detalle", $detalle, $filtro, array_merge($detalle,$filtro));
            $ok = $param['ok'];

            $param = dalLdbi::GuardarBitacora($conn, "2", "vac_LDBI_requesicion_detalle");
        }

        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);
        if ($ok)
            $conn->commit();
        else {
            $conn->rollback();
            $id = -1;
        }

        $conn->closeConn();
        if (!$ok)
            return 2;
        return 1;
    }

    public static function GuardarTabla($conn, $tabla, $objeto) {
        $ok = true;

        $now = new DateTime(null, new DateTimeZone("America/Panama"));
        $change['fh_modifica'] = $now->format('Y-m-d H:i:s');
        $change['codigoglobal'] = uniqid('', true);
        $objeto = array_merge($change,$objeto);

        $sql = Actions::AgregarQuery($tabla, $objeto);
        $conn->prepare($sql);
//        $comma_separated = "('" . implode("', '", $objeto) . "')";
//        echo $sql . " values " . $comma_separated."<br/>";
        $conn->params($objeto);
        $conn->execute() ? null : $ok = false;
        $error = $conn->getError();
        $conn->closeStmt();
        $id = $conn->getID();
//        echo "Error: ".$error." y ID:".$id;
        if (!$ok) {
            $conn->rollback();
            $conn->closeConn();
            echo "<br/>Error";
            return "SQL:" . $sql . "<br/>Error" . $error;
            exit;
        }
        $param = array();
        $param['id'] = $id;
        $param['ok'] = $ok;
        return $param;
    }

    public static function ActualizarTabla($conn, $tabla, $objeto, $filtro, $total) {
        $ok = true;

        $now = new DateTime(null, new DateTimeZone("America/Panama"));
        $change['fh_modifica'] = $now->format('Y-m-d H:i:s');
        $objeto = array_merge($change,$objeto);
        $total = array_merge($change,$total);

        $sql = Actions::ActualizarQuery($tabla, $objeto, $filtro);
        $conn->prepare($sql);
        $conn->params($total);
        $conn->execute() ? null : $ok = false;
        $error = $conn->getError();
        $conn->closeStmt();
        $conn->getID();
//        echo "Error: ".$error; exit;
        if (!$ok) {
            $conn->rollback();
            $conn->closeConn();
            echo "<br/>Error";
            return "SQL:" . $sql . "<br/>Error" . $error;
            exit;
        }
        $param = array();
        $param['id'] = $id;
        $param['ok'] = $ok;
        return $param;
    }

    public static function BorrarTabla($conn, $tabla, $filtro) {
        $objeto['deleted'] = 1;
        $total = array_merge($objeto,$filtro);
        return dalLdbi::ActualizarTabla($conn, $tabla, $objeto, $filtro, $total);
    }

    public static function GuardarBitacora($conn, $accion, $tabla) {
        $ok = true;
        // BITACORA
        $bitacora = array();
        $bitacora["usuid"] = clsCaus::obtenerID();
        $bitacora["fecha"] = date("Y-m-d H:i:s");
        $bitacora["accion"] = $accion;
        $bitacora["tabla"] = $tabla;

        $sql = Actions::AgregarQuery("bitacora", $bitacora);
//        $sql = "INSERT INTO bitacora(usuid,fecha,accion,tabla) 
//            VALUES(".$bitacora["usuid"].",".$bitacora["fecha"].",".$bitacora["accion"].",".$bitacora["tabla"].")";
//        echo $sql."<br/>";
        $conn->prepare($sql);
//        print_r($bitacora);
        $conn->params($bitacora);
        $conn->execute() ? null : $ok = false;
        $error = $conn->getError();
        $conn->closeStmt();
        $id = $conn->getID();
//        echo "Error: ".$error." y ID:".$id;
        if (!$ok) {
            $conn->rollback();
            $conn->closeConn();
            echo "<br/>Error";
            return "SQL:" . $sql . "<br/>Error" . $error;
            exit;
        }
        $param = array();
        $param['id'] = $id;
        $param['ok'] = $ok;
        return $param;
    }

    public static function buscarEnvio($config){
        $conn = new Connection();
        $conn->initConn();
        $flag = 0;

        $sql = "select * from vac_LDBI_envio where id_envio = '" . $config["id_envio"] . "' and deleted = 0 ";

        $conn->prepare($sql);
        $conn->execute();
        $envio = $conn->fetch();
        $conn->closeConn();
        $data["envio"] = $envio[0];
        $data["existencias"] = self::buscarEnvioDetalle($config);

        return $data;
    }

    public static function buscarEnvioDetalle($config){
        $conn = new Connection();
        $conn->initConn();

        $sql = "select envio.*, DATE_FORMAT(envio.fh_vencimiento,'%d/%m/%Y') as fh_vencimiento, insumo.nombre_insumo as nombre_prod, insumo.id_insumo as id_prod, insumo.unidad_presentacion, codigo_insumo
                from vac_LDBI_inventario envio
                left join cat_insumos_LDBI insumo ON envio.id_insumo =  insumo.id_insumo
                where envio.deleted = 0 and envio.id_envio = '" . $config["id_envio"] . "' ";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function buscarRequesicion($config){
        $conn = new Connection();
        $conn->initConn();
        $flag = 0;

        $sql = "select r.*, r.id_requesicion as id, r.no_requesicion as numero, r.nombre_registra as registra, e.*, u.nombre_un, reg.nombre_region
                from vac_LDBI_requesicion r
                left join cat_unidad_notificadora u ON u.id_un = r.id_un
                left join cat_region_salud reg ON r.id_region = reg.id_region
                left join vac_LDBI_envio_bodega e ON e.id_requesicion = r.id_requesicion
                where r.deleted = 0 and r.id_requesicion = '" . $config["id_requesicion"] . "' ";

        $conn->prepare($sql);
        $conn->execute();
        $requesicion = $conn->fetch();
        $conn->closeConn();
        $data["requesicion"] = $requesicion[0];
        $data["existencias"] = self::buscarRequesicionDetalle($config);

        return $data;
    }

    public static function buscarRequesicionDetalle($config){
        $conn = new Connection();
        $conn->initConn();

        $sql = "select requesicion.*, DATE_FORMAT(requesicion.fh_vencimiento,'%d/%m/%Y') as fh_vencimiento, insumo.id_insumo as id_prod,
                insumo.nombre_insumo as nombre_prod, insumo.unidad_presentacion, codigo_insumo
                from vac_LDBI_requesicion_detalle requesicion
                left join cat_insumos_LDBI insumo ON requesicion.id_insumo =  insumo.id_insumo
                where requesicion.deleted = 0 and requesicion.id_requesicion = '" . $config["id_requesicion"] . "' ";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function buscarEnvioBodegaDetalle($config){
        $conn = new Connection();
        $conn->initConn();

        $sql = "select envio.*, DATE_FORMAT(envio.fh_vencimiento,'%d/%m/%Y') as fh_vencimiento, insumo.id_insumo as id_prod,
                insumo.nombre_insumo as nombre_prod, insumo.unidad_presentacion, codigo_insumo
                from vac_LDBI_envio_bodega_detalle envio
                left join cat_insumos_LDBI insumo ON envio.id_insumo =  insumo.id_insumo
                where envio.deleted = 0 and envio.id_envio = '" . $config["id_envio"] . "' ";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function buscarRequesicionPorNumero($config){
        $conn = new Connection();
        $conn->initConn();

        $sql = "select requesicion.*, DATE_FORMAT(requesicion.fh_vencimiento,'%m/%d/%Y') as fh_vencimiento, insumo.id_insumo as id_prod,
                insumo.nombre_insumo as nombre_prod, insumo.unidad_presentacion, codigo_insumo
                from vac_LDBI_requesicion_detalle requesicion
                inner join vac_LDBI_requesicion r on r.id_requesicion = requesicion.id_requesicion
                left join cat_insumos_LDBI insumo ON requesicion.id_insumo =  insumo.id_insumo
                where requesicion.deleted = 0 and r.no_requesicion = '" . $config["numero"] . "'
                and requesicion.enviado <= 0";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function buscarEnvioBodega($config){
        $conn = new Connection();
        $conn->initConn();
        $flag = 0;

        $sql = "select r.*, uo.nombre_un as nombre_un_origen, ud.nombre_un as nombre_un_destino, re.no_requesicion, rego.nombre_region as nombre_region_origen, regd.nombre_region as nombre_region_destino
                from vac_LDBI_envio_bodega r
                left join vac_LDBI_requesicion re ON re.id_requesicion = r.id_requesicion
                left join cat_unidad_notificadora uo ON uo.id_un = r.id_un_origen
                left join cat_unidad_notificadora ud ON ud.id_un = r.id_un_destino
                left join cat_region_salud rego ON rego.id_region = r.id_region_origen
                left join cat_region_salud regd ON regd.id_region = r.id_region_destino
                where r.deleted = 0 and id_envio = '" . $config["id_envio"] . "' ";

        $conn->prepare($sql);
        $conn->execute();
        $requesicion = $conn->fetch();
        $conn->closeConn();
        $data["requesicion"] = $requesicion[0];
        $data["existencias"] = self::buscarRequesicionDetalle($data["requesicion"]);
        if ($data["existencias"] == null) $data["existencias"] = array();
        $detalle = self::buscarEnvioBodegaDetalle($config);
        if ($detalle != null) {
            $data["existencias"] = array_merge($data["existencias"], self::buscarEnvioBodegaDetalle($config));
        }

        return $data;
    }

    public static function GuardarNotaAjuste($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $envio = $data["envio"];
        $envio["fh_nota"] = helperString::toDate($envio["fh_nota"]);
        $envio["fh_ingreso"] = helperString::toDate($envio["fh_ingreso"]);
        $razon = explode("_",$envio["id_razon"]);
        $envio["id_razon"] = $razon[0];
        $movimiento = $razon[1];
        if ($envio["id_region"] == -1) $envio["id_region"] = 0;
        $envio_detalle = $data["existencias"];

        $yaExiste = 0;

        if ($yaExiste == 0) {
            $param = array();
            $param = dalLdbi::GuardarTabla($conn, "vac_LDBI_nota", $envio);
            $id = $param['id'];
            $ok = $param['ok'];

            $param = dalLdbi::GuardarBitacora($conn, "1", "vac_LDBI_nota");
            $ok = $param['ok'];

            if ($id != null && $ok){
                foreach ($envio_detalle as $key => $detalle) {
                    $filtro = null;
                    $detalle["id_nota"] = $id;
                    $detalle["id_insumo"] = $key;
                    $param = dalLdbi::GuardarTabla($conn, "vac_LDBI_nota_detalle", $detalle);
                    $ok = $param['ok'];

                    if ($ok){
                        $inventario = $detalle;
                        $inventario["id_envio"] = 0;
                        $inventario["id_envio_bodega"] = 0;
                        $inventario["id_insumo"] = $key;
                        $inventario["fh_ingreso"] = $envio["fh_ingreso"];
                        $inventario["fh_inventario"] = $envio["fh_nota"];
                        $inventario["bodega_central"] = $envio["bodega_central"];
                        $inventario["id_region"] = $envio["id_region"];
                        $inventario["id_un"] = $envio["id_un"];
                        $inventario["movimiento"] = $movimiento;
                        $param = dalLdbi::GuardarTabla($conn, "vac_LDBI_inventario", $inventario);
                        $ok = $param['ok'];
                    }

                    if (!$ok) break;
                }

                $param = dalLdbi::GuardarBitacora($conn, "2", "vac_LDBI_nota_detalle");
                $ok = $param['ok'];
            } else {
                $ok = false;
            }

            if ($ok)
                $conn->commit();
            else {
                $conn->rollback();
                $id = -1;
            }

            $conn->closeConn();
            return 1;
        } else {
            $conn->closeConn();
            return 2;
        }
    }
    public static function ActualizarNotaAjuste($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $envio = $data["envio"];
        $envio["fh_nota"] = helperString::toDate($envio["fh_nota"]);
        $envio["fh_ingreso"] = helperString::toDate($envio["fh_ingreso"]);

        $razon = explode("_",$envio["id_razon"]);
        $envio["id_razon"] = $razon[0];
        $movimiento = $razon[1];
        if ($envio["id_region"] == -1) $envio["id_region"] = 0;

        $envio_detalle = $data["existencias"];

        $yaExiste = 0;

        $id = $envio["id_nota"];

        if ($yaExiste == 0) {
            $param = array();
            $filtro = null;
            $filtro["id_nota"] = $envio["id_nota"];

            unset($envio["id_nota"]);
            $param = dalLdbi::ActualizarTabla($conn, "vac_LDBI_nota", $envio, $filtro, array_merge($envio,$filtro));
            $ok = $param['ok'];

            $param = dalLdbi::GuardarBitacora($conn, "2", "vac_LDBI_nota");
            $ok = $param['ok'];

            $param = dalLdbi::BorrarTabla($conn, "vac_LDBI_nota_detalle", array('id_nota' => $id));
            $ok = $param['ok'];

            $param = dalLdbi::BorrarTabla($conn, "vac_LDBI_inventario", array('id_nota' => $id));
            $ok = $param['ok'];

            if ($id != null && $ok){
                foreach ($envio_detalle as $key => $detalle) {
                    $filtro = null;
                    $detalle["id_nota"] = $id;
                    $detalle["id_insumo"] = $key;
                    $param = dalLdbi::GuardarTabla($conn, "vac_LDBI_nota_detalle", $detalle);
                    $ok = $param['ok'];

                    if ($ok){
                        $inventario = $detalle;
                        $inventario["id_envio"] = 0;
                        $inventario["id_envio_bodega"] = 0;
                        $inventario["id_insumo"] = $key;
                        $inventario["fh_ingreso"] = $envio["fh_ingreso"];
                        $inventario["fh_inventario"] = $envio["fh_nota"];
                        $inventario["bodega_central"] = $envio["bodega_central"];
                        $inventario["id_region"] = $envio["id_region"];
                        $inventario["id_un"] = $envio["id_un"];
                        $inventario["movimiento"] = $movimiento;
                        $param = dalLdbi::GuardarTabla($conn, "vac_LDBI_inventario", $inventario);
                        $ok = $param['ok'];
                    }

                    if (!$ok) break;
                }

                $param = dalLdbi::GuardarBitacora($conn, "2", "vac_LDBI_nota_detalle");
                $ok = $param['ok'];
            } else {
                $ok = false;
            }

            if ($ok)
                $conn->commit();
            else {
                $conn->rollback();
                $id = -1;
            }

            $conn->closeConn();
            return 1;
        } else {
            $conn->closeConn();
            return 2;
        }
    }


    public static function buscarNotaAjuste($config){
        $conn = new Connection();
        $conn->initConn();
        $flag = 0;

        $sql = "select r.*, u.nombre_un as nombre_un, reg.nombre_region, ra.descripcion as nombre_razon from vac_LDBI_nota r
                left join cat_unidad_notificadora u ON u.id_un = r.id_un
                left join cat_region_salud reg ON reg.id_region = r.id_region
                left join cat_razon_LDBI ra ON ra.id_razon = r.id_razon
                where r.deleted = 0 and id_nota = '" . $config["id_nota"] . "' ";

        $conn->prepare($sql);
        $conn->execute();
        $requesicion = $conn->fetch();
        $conn->closeConn();
        $data["requesicion"] = $requesicion[0];
        $data["existencias"] = self::buscarNotaAjusteDetalle($data["requesicion"]);

        return $data;
    }

    public static function buscarNotaAjusteDetalle($config){
        $conn = new Connection();
        $conn->initConn();

        $sql = "select envio.*, insumo.id_insumo as id_prod,
                insumo.nombre_insumo as nombre_prod, insumo.unidad_presentacion, codigo_insumo
                from vac_LDBI_nota_detalle envio
                left join cat_insumos_LDBI insumo ON envio.id_insumo =  insumo.id_insumo
                where envio.deleted = 0 and envio.id_nota = '" . $config["id_nota"] . "' ";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function BorrarNotaAjuste($data) {
        $ok = true;
        $conn = new Connection();
        $conn->initConn();
        $conn->begin();

        $param = array();

        $filtro = array();
        $filtro["id_nota"] = $data['id'];

        $param = dalLdbi::BorrarTabla($conn, "vac_LDBI_nota_detalle", $filtro);
        $ok = $param['ok'];

        $param = dalLdbi::GuardarBitacora($conn, "3", "vac_LDBI_nota_detalle");
        $ok = $param['ok'];

        $param = dalLdbi::BorrarTabla($conn, "vac_LDBI_nota", $filtro);
        $ok = $param['ok'];

        $param = dalLdbi::GuardarBitacora($conn, "3", "vac_LDBI_nota");
        $ok = $param['ok'];

        $param = dalLdbi::BorrarTabla($conn, "vac_LDBI_inventario", $filtro);
        $ok = $param['ok'];

        $param = dalLdbi::GuardarBitacora($conn, "3", "vac_LDBI_inventario");
        $ok = $param['ok'];

        $id = $param['id'];
        $ok = $param['ok'];
        //print_r($param);
        if ($ok)
            $conn->commit();
        else {
            $conn->rollback();
            $id = -1;
        }

        $conn->closeConn();
        if (!$ok)
            return 2;
        return 1;
    }
}

?>