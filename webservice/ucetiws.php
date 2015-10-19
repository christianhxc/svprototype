<?php

//header('Content-Type: text/html; charset=iso-8859-1');

require_once('libs/nusoap.php');
require_once('libs/DOMXML.php');
require_once('libs/EncodeDecode.php');
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/caus/ConfigurationCAUS.php');
require_once('libs/caus/ConnectionCAUS.php');
require_once('libs/dal/uceti/dalUceti.php');
require_once('libs/helper/helperString.php');

$ns = "";
$server = new nusoap_server();
$server->configureWSDL('ws', $ns);
$server->wsdl->schemaTargetNamespace = $ns;
$server->register('servicio', array('id' => 'xsd:string', 'root' => 'xsd:string', 'operacion' => 'xsd:string'), array('return' => 'xsd:string'), $ns);

function servicio($id, $root, $operacion) {
    $OPERACIONSELECTUSER = "SELECTUSER";
    $OPERACIONSELECT = "SELECT";
    $OPERACIONSELECTCOUNT = "SELECTCOUNT";
    $OPERACIONINSERT = "INSERT";
    $OPERACIONUPDATE = "UPDATE";
    
    if ($operacion == $OPERACIONSELECTCOUNT) {
        $conn = new Connection();
        $conn->initConn();
        $sql = $id;
        //$sql =  "";
        $node_ = $root;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        //print_r($data);
        $contenido = $data[0];
        //echo $contenido;
        $contenido = replaceCharacter($contenido);
        $contenido = base64_encode($contenido);
        $conn->closeStmt();
        $rta = new soapval('return', 'xsd:string', $contenido);
        return $rta;
    }

    if ($operacion == $OPERACIONSELECTUSER) {
        $conn = new ConnectionCAUS();
        $sql = $id;
        $conn->initConn();
        $node_ = $root;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $data = replaceCharacter($data);
        /* $contenido = '<?xml version="1.0" encoding="UTF-8"?>'; */
        $contenido.= DOM::arrayToXMLString($data, $node_, false);
        $contenido = replaceCharacter($contenido);
        $contenido = base64_encode($contenido);
        $conn->closeStmt();
        $rta = new soapval('return', 'xsd:string', $contenido);
        return $rta;
    }
    if ($operacion == $OPERACIONSELECT) {
        $conn = new Connection();
        $conn->initConn();
        $sql = $id;
        //$sql =  "";
        $node_ = $root;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $data = replaceCharacter($data);
        /* $contenido = '<?xml version="1.0" encoding="UTF-8"?>'; */
        $contenido.= DOM::arrayToXMLString($data, $node_);
        $contenido = replaceCharacter($contenido);
        $contenido = base64_encode($contenido);
        $conn->closeConn();
        $rta = new soapval('return', 'xsd:string', $contenido);
        return $rta;
    }
    if ($operacion == $OPERACIONINSERT) {
        $nodata = "-2";
        $sql = $id;
        $contenido = "Hola";
        $dataAll = DOM::xmlStringToArray($sql);
        $opcionInsertar = (int) $root;
        $encabezado = array();
        $contenido = "";
        if ($opcionInsertar == 0) {
            $contenido = "Hola opcion Aqui";
            $contenido = procesarDatos($dataAll, $nodata);
        } else if ($opcionInsertar == 1) {
            $contenido = procesarDatosEnfermedad($dataAll, $nodata);
        } else if ($opcionInsertar == 2) {
            $contenido = procesarDatosAntecedente($dataAll, $nodata);
        } else if ($opcionInsertar == 3) {
            $contenido = procesarDatosMuestraLab($dataAll, $nodata);
        }
        $contenido = base64_encode($contenido);
        $rta = new soapval('return', 'xsd:string', $contenido);
        return $rta;
    }
    if ($operacion == $OPERACIONUPDATE) {
        
    }
}

function procesarDatosEnfermedad($dataAll, $nodata) {
    $index = 0;
    $contenido = "";
    $enfermedad_data = array();
    //print_r($dataAll);
    foreach ($dataAll as $value) {
        //print_r($value);
        foreach ($value as $valueUno) {
            if ($index == 0) {
                /* if ($valueUno["__content__"] != $nodata) {
                  $enfermedad_data["id_flureg_enfermedad"] = (int)$valueUno["__content__"];
                  $contenido.=$enfermedad_data["id_flureg_enfermedad"];
                  $contenido.=";";
                  } */
            }
            if ($index == 1) {
                if ($valueUno["__content__"] != $nodata) {
                    $enfermedad_data["tipo_identificacion"] = (int) $valueUno["__content__"];
                    $contenido.=$enfermedad_data["tipo_identificacion"];
                    $contenido.=";";
                }
            }
            if ($index == 2) {
                if ($valueUno["__content__"] != $nodata) {
                    $enfermedad_data["numero_identificacion"] = $valueUno["__content__"];
                    $contenido.=$enfermedad_data["numero_identificacion"];
                    $contenido.=";";
                }
            }
            if ($index == 3) {
                if ($valueUno["__content__"] != $nodata) {
                    $enfermedad_data["id_cat_enfermedad_cronica"] = (int) $valueUno["__content__"];
                    $contenido.=$enfermedad_data["id_cat_enfermedad_cronica"];
                    $contenido.=";";
                }
            }
            if ($index == 4) {
                if ($valueUno["__content__"] != $nodata) {
                    $enfermedad_data["resultado"] = (int) $valueUno["__content__"];
                    $contenido.=$enfermedad_data["resultado"];
                    $contenido.=";";
                }
            }
            $index++;
        }
    }
    $idVigilancia = Guardar($enfermedad_data, "flureg_enfermedad_cronica");
    //$params = dalUceti::GuardarTabla($conn, "flureg_form", $idVigilancia);
    return $idVigilancia;
}

function procesarDatosAntecedente($dataAll, $nodata) {
    $index = 0;
    $contenido = "";
    $antecedente_data = array();
    //print_r($dataAll);
    foreach ($dataAll as $value) {
        //print_r($value);
        foreach ($value as $valueUno) {
            if ($index == 0) {
                
            }
            if ($index == 1) {
                if ($valueUno["__content__"] != $nodata) {
                    $antecedente_data["tipo_identificacion"] = (int) $valueUno["__content__"];
                    $contenido.=$antecedente_data["tipo_identificacion"];
                    $contenido.=";";
                }
            }
            if ($index == 2) {
                if ($valueUno["__content__"] != $nodata) {
                    $antecedente_data["numero_identificacion"] = $valueUno["__content__"];
                    $contenido.=$antecedente_data["numero_identificacion"];
                    $contenido.=";";
                }
            }
            if ($index == 3) {
                if ($valueUno["__content__"] != $nodata) {
                    $antecedente_data["id_cat_antecendente_vacunal"] = (int) $valueUno["__content__"];
                    $contenido.=$antecedente_data["id_cat_antecendente_vacunal"];
                    $contenido.=";";
                }
            }
            if ($index == 4) {
                if ($valueUno["__content__"] != $nodata) {
                    $antecedente_data["dosis"] = (int) $valueUno["__content__"];
                    $contenido.=$antecedente_data["dosis"];
                    $contenido.=";";
                }
            }
            if ($index == 5) {
                if ($valueUno["__content__"] != $nodata) {
                    $antecedente_data["fecha"] = helperString::toDateTrim($valueUno["__content__"]);
                    $contenido.=$antecedente_data["fecha"];
                    $contenido.=";";
                }
            }
            $index++;
        }
    }
    $idVigilancia = Guardar($antecedente_data, "flureg_antecendente_vacunal");
    return $idVigilancia;
}

function procesarDatosMuestraLab($dataAll, $nodata) {
    $index = 0;
    $contenido = "";
    $muestra_data = array();
    //print_r($dataAll);
    foreach ($dataAll as $value) {
        //print_r($value);
        foreach ($value as $valueUno) {
            if ($index == 0) {
                
            }
            if ($index == 1) {
                if ($valueUno["__content__"] != $nodata) {
                    $muestra_data["id_flureg"] = (int) $valueUno["__content__"];
                    $contenido.=$muestra_data["id_flureg"];
                    $contenido.=";";
                }
            }
            if ($index == 2) {
                if ($valueUno["__content__"] != $nodata) {
                    $muestra_data["id_cat_muestra_laboratorio"] = (int) $valueUno["__content__"];
                    $contenido.=$muestra_data["id_cat_muestra_laboratorio"];
                    $contenido.=";";
                }
            }
            if ($index == 3) {
                if ($valueUno["__content__"] != $nodata) {
                    $muestra_data["fecha_toma"] = helperString::toDateTrim($valueUno["__content__"]);
                    $contenido.=$muestra_data["fecha_toma"];
                    $contenido.=";";
                }
            }
            if ($index == 4) {
                if ($valueUno["__content__"] != $nodata) {
                    $muestra_data["fecha_envio"] = helperString::toDateTrim($valueUno["__content__"]);
                    $contenido.=$muestra_data["fecha_envio"];
                    $contenido.=";";
                }
            }
            if ($index == 5) {
                if ($valueUno["__content__"] != $nodata) {
                    $muestra_data["fecha_recibo_laboratorio"] = helperString::toDateTrim($valueUno["__content__"]);
                    $contenido.=$muestra_data["fecha_recibo_laboratorio"];
                    $contenido.=";";
                }
            }
            $index++;
        }
    }
    $idVigilancia = Guardar($muestra_data, "flureg_muestra_laboratorio");
    return $idVigilancia;
}

function procesarDatos($dataAll, $nodata) {
    $index = 0;
    $contenido = "";
    $contenido2 = "";
    $persona_data = array();
    $ficha_influenza = array();
    //print_r($dataAll);
    foreach ($dataAll as $value) {
        //print_r($value);
        foreach ($value as $valueUno) {
            //$contenido.=();
            //print_r($valueUno["__content__"]);
            //$contenido.=$index. "----";
            if ($index == 0) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["tipo_identificacion"] = (int) $valueUno["__content__"];
                    $contenido.=$persona_data["tipo_identificacion"];
                    $contenido.=";";
                }
            }
            if ($index == 1) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["numero_identificacion"] = $valueUno["__content__"];
                    $contenido.=$persona_data["numero_identificacion"];
                    $contenido.=";";
                }
            }
            if ($index == 2) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["primer_nombre"] = $valueUno["__content__"];
                    $contenido.=$persona_data["primer_nombre"];
                    $contenido.=";";
                }
            }
            if ($index == 3) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["segundo_nombre"] = $valueUno["__content__"];
                    $contenido.=$persona_data["segundo_nombre"];
                    $contenido.=";";
                }
            }
            if ($index == 4) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["primer_apellido"] = $valueUno["__content__"];
                    $contenido.=$persona_data["primer_apellido"];
                    $contenido.=";";
                }
            }
            if ($index == 5) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["segundo_apellido"] = $valueUno["__content__"];
                    $contenido.=$persona_data["segundo_apellido"];
                    $contenido.=";";
                }
            }
            if ($index == 6) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["sin_nombre"] = $valueUno["__content__"];
                    $contenido.=$persona_data["sin_nombre"];
                    $contenido.=";";
                }
            }
            if ($index == 7) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["fecha_nacimiento"] = $valueUno["__content__"];
                    $contenido.=$persona_data["fecha_nacimiento"];
                    $contenido.=";";
                }
            }
            if ($index == 8) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["edad"] = (int) $valueUno["__content__"];
                    $contenido.=$persona_data["edad"];
                    $contenido.=";";
                }
            }
            if ($index == 9) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["tipo_edad"] = (int) $valueUno["__content__"];
                    $contenido.=$persona_data["tipo_edad"];
                    $contenido.=";";
                }
            }
            if ($index == 10) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["sexo"] = $valueUno["__content__"];
                    $contenido.=$persona_data["sexo"];
                    $contenido.=";";
                }
            }
            if ($index == 11) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["id_region"] = (int) $valueUno["__content__"];
                    $contenido.=$persona_data["id_region"];
                    $contenido.=";";
                }
            }
            if ($index == 12) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["id_corregimiento"] = (int) $valueUno["__content__"];
                    $contenido.=$persona_data["id_corregimiento"];
                    $contenido.=";";
                }
            }
            if ($index == 13) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["localidad"] = (int) $valueUno["__content__"];
                    $contenido.=$persona_data["localidad"];
                    $contenido.=";";
                }
            }
            if ($index == 14) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["dir_referencia"] = $valueUno["__content__"];
                    $contenido.=$persona_data["dir_referencia"];
                    $contenido.=";";
                }
            }
            if ($index == 15) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["id_pais"] = (int) $valueUno["__content__"];
                    $contenido.=$persona_data["id_pais"];
                    $contenido.=";";
                }
            }
            if ($index == 16) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["id_ocupacion"] = (int) $valueUno["__content__"];
                    $contenido.=$persona_data["id_ocupacion"];
                    $contenido.=";";
                }
            }
            if ($index == 17) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["nombre_responsable"] = $valueUno["__content__"];
                    $contenido.=$persona_data["nombre_responsable"];
                    $contenido.=";";
                }
            }
            if ($index == 18) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["tel_residencial"] = $valueUno["__content__"];
                    $contenido.=$persona_data["tel_residencial"];
                    $contenido.=";";
                }
            }
            if ($index == 19) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["dir_trabajo"] = $valueUno["__content__"];
                    $contenido.=$persona_data["dir_trabajo"];
                    $contenido.=";";
                }
            }
            if ($index == 20) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["tel_trabajo"] = $valueUno["__content__"];
                    $contenido.=$persona_data["tel_trabajo"];
                    $contenido.=";";
                }
            }
            if ($index == 21) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["id_pais_nacimiento"] = (int) $valueUno["__content__"];
                    $contenido.=$persona_data["id_pais_nacimiento"];
                    $contenido.=";";
                }
            }
            if ($index == 22) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["id_corregimiento_nacimiento"] = (int) $valueUno["__content__"];
                    $contenido.=$persona_data["id_corregimiento_nacimiento"];
                    $contenido.=";";
                }
            }
            /**
             * AQUI FICHA INFLUENZA 
             */
            //$contenido2.="--". $index."--";

            if ($index == 23) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["tipo_identificacion"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["tipo_identificacion"];
                    $contenido2.=";";
                }
            }
            if ($index == 24) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["numero_identificacion"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["numero_identificacion"];
                    $contenido2.=";";
                }
            }
            if ($index == 25) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["id_un"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["id_un"];
                    $contenido2.=";";
                }
            }
            if ($index == 26) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["unidad_disponible"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["unidad_disponible"];
                    $contenido2.=";";
                }
            }
            if ($index == 27) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["per_tipo_paciente"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["per_tipo_paciente"];
                    $contenido2.=";";
                }
            }
            if ($index == 28) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["per_hospitalizado"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["per_hospitalizado"];
                    $contenido2.=";";
                }
            }
            if ($index == 29) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["per_hospitalizado_lugar"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["per_hospitalizado_lugar"];
                    $contenido2.=";";
                }
            }
            if ($index == 30) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["nombre_investigador"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["nombre_investigador"];
                    $contenido2.=";";
                }
            }
            if ($index == 31) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["nombre_registra"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["nombre_registra"];
                    $contenido2.=";";
                }
            }
            if ($index == 32) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_formulario"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_formulario"];
                    $contenido2.=";";
                }
            }
            if ($index == 33) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["per_asegurado"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["per_asegurado"];
                    $contenido2.=";";
                }
            }
            if ($index == 34) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["per_edad"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["per_edad"];
                    $contenido2.=";";
                }
            }
            if ($index == 35) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["per_tipo_edad"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["per_tipo_edad"];
                    $contenido2.=";";
                }
            }
            if ($index == 36) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["id_pais"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["id_pais"];
                    $contenido2.=";";
                }
            }
            if ($index == 37) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["id_corregimiento"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["id_corregimiento"];
                    $contenido2.=";";
                }
            }
            if ($index == 38) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["per_direccion"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["per_direccion"];
                    $contenido2.=";";
                }
            }
            if ($index == 39) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["per_direccion_otra"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["per_direccion_otra"];
                    $contenido2.=";";
                }
            }
            if ($index == 40) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["per_telefono"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["per_telefono"];
                    $contenido2.=";";
                }
            }
            if ($index == 41) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_tarjeta"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_tarjeta"];
                    $contenido2.=";";
                }
            }
            if ($index == 42) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_segun_esquema"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_segun_esquema"];
                    $contenido2.=";";
                }
            }
            if ($index == 43) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_fecha_ultima_dosis"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_fecha_ultima_dosis"];
                    $contenido2.=";";
                }
            }
            if ($index == 44) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_embarazo"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_embarazo"];
                    $contenido2.=";";
                }
            }
            if ($index == 45) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_trimestre"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_trimestre"];
                    $contenido2.=";";
                }
            }
            if ($index == 46) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_enf_cronica"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_enf_cronica"];
                    $contenido2.=";";
                }
            }
            if ($index == 47) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_profesional"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_profesional"];
                    $contenido2.=";";
                }
            }
            if ($index == 48) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_pro_cual"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_pro_cual"];
                    $contenido2.=";";
                }
            }
            if ($index == 49) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_viaje"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_viaje"];
                    $contenido2.=";";
                }
            }
            if ($index == 50) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_contacto_confirmado"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_contacto_confirmado"];
                    $contenido2.=";";
                }
            }
            if ($index == 51) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_contacto_tipo"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_contacto_tipo"];
                    $contenido2.=";";
                }
            }
            if ($index == 52) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_aislamiento"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_aislamiento"];
                    $contenido2.=";";
                }
            }
            if ($index == 53) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_contacto_nombre"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_contacto_nombre"];
                    $contenido2.=";";
                }
            }
            if ($index == 54) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["id_evento"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["id_evento"];
                    $contenido2.=";";
                }
            }
            if ($index == 55) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["eve_sindrome"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["eve_sindrome"];
                    $contenido2.=";";
                }
            }
            if ($index == 56) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["eve_centinela"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["eve_centinela"];
                    $contenido2.=";";
                }
            }
            if ($index == 57) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["eve_inusitado"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["eve_inusitado"];
                    $contenido2.=";";
                }
            }
            if ($index == 58) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["eve_imprevisto"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["eve_imprevisto"];
                    $contenido2.=";";
                }
            }
            if ($index == 59) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["eve_excesivo"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["eve_excesivo"];
                    $contenido2.=";";
                }
            }
            if ($index == 60) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["eve_conglomerado"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["eve_conglomerado"];
                    $contenido2.=";";
                }
            }
            if ($index == 61) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["eve_neumo_bacteriana"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["eve_neumo_bacteriana"];
                    $contenido2.=";";
                }
            }
            if ($index == 62) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_inicio_sintoma"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_inicio_sintoma"];
                    $contenido2.=";";
                }
            }
            if ($index == 63) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_hospitalizacion"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_hospitalizacion"];
                    $contenido2.=";";
                }
            }
            if ($index == 64) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_notificacion"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_notificacion"];
                    $contenido2.=";";
                }
            }
            if ($index == 65) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_egreso"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_egreso"];
                    $contenido2.=";";
                }
            }
            if ($index == 66) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_defuncion"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_defuncion"];
                    $contenido2.=";";
                }
            }
            if ($index == 67) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["antibiotico"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["antibiotico"];
                    $contenido2.=";";
                }
            }
            if ($index == 68) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["antibiotico_cual"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["antibiotico_cual"];
                    $contenido2.=";";
                }
            }
            if ($index == 69) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["antibiotico_fecha"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["antibiotico_fecha"];
                    $contenido2.=";";
                }
            }
            if ($index == 70) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["antiviral"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["antiviral"];
                    $contenido2.=";";
                }
            }
            if ($index == 71) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["antiviral_cual"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["antiviral_cual"];
                    $contenido2.=";";
                }
            }
            if ($index == 72) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["antiviral_fecha"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["antiviral_fecha"];
                    $contenido2.=";";
                }
            }
            if ($index == 73) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["sintoma_fiebre"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["sintoma_fiebre"];
                    $contenido2.=";";
                }
            }
            if ($index == 74) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["sintoma_tos"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["sintoma_tos"];
                    $contenido2.=";";
                }
            }
            if ($index == 75) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["sintoma_garganta"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["sintoma_garganta"];
                    $contenido2.=";";
                }
            }
            if ($index == 76) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["sintoma_rinorrea"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["sintoma_rinorrea"];
                    $contenido2.=";";
                }
            }
            if ($index == 77) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["sintoma_respiratoria"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["sintoma_respiratoria"];
                    $contenido2.=";";
                }
            }
            if ($index == 78) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["sintoma_otro"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["sintoma_otro"];
                    $contenido2.=";";
                }
            }
            if ($index == 79) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["sintoma_nombre_otro"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["sintoma_nombre_otro"];
                    $contenido2.=";";
                }
            }
            if ($index == 80) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_fiebre"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_fiebre"];
                    $contenido2.=";";
                }
            }
            if ($index == 81) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_tos"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_tos"];
                    $contenido2.=";";
                }
            }
            if ($index == 82) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_garganta"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_garganta"];
                    $contenido2.=";";
                }
            }
            if ($index == 83) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_rinorrea"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_rinorrea"];
                    $contenido2.=";";
                }
            }
            if ($index == 84) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_respiratoria"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_respiratoria"];
                    $contenido2.=";";
                }
            }
            if ($index == 85) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_otro"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_otro"];
                    $contenido2.=";";
                }
            }
            if ($index == 86) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["torax_condensacion"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["torax_condensacion"];
                    $contenido2.=";";
                }
            }
            if ($index == 87) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["torax_derrame"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["torax_derrame"];
                    $contenido2.=";";
                }
            }
            if ($index == 88) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["torax_broncograma"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["torax_broncograma"];
                    $contenido2.=";";
                }
            }
            if ($index == 89) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["torax_infiltrado"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["torax_infiltrado"];
                    $contenido2.=";";
                }
            }
            if ($index == 90) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["torax_otro"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["torax_otro"];
                    $contenido2.=";";
                }
            }
            if ($index == 91) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["torax_nombre_otro"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["torax_nombre_otro"];
                    $contenido2.=";";
                }
            }
            if ($index == 92) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["semana_epi"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["semana_epi"];
                    $contenido2.=";";
                }
            }
            if ($index == 93) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["anio"] = (int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["anio"];
                    $contenido2.=";";
                }
            }

            $index++;
        }
    }
    $ficha_influenza["source_entry"] = 1;
    $ficha_influenza["pendiente_uceti"] = 1;

    if (consultarUsuario($persona_data["tipo_identificacion"], $persona_data["numero_identificacion"]) > 0) {
        $filtro = array();
        $filtro["tipo_identificacion"] = $persona_data["tipo_identificacion"];
        $filtro["numero_identificacion"] = $persona_data["numero_identificacion"];
        $totalFiltroIndividuo = $persona_data;
        $totalFiltroIndividuo["filter1"] = $filtro["tipo_identificacion"];
        $totalFiltroIndividuo["filter2"] = $filtro["numero_identificacion"];

        ActualizarTabla("tbl_persona", $persona_data, $filtro, $totalFiltroIndividuo);
    } else {
        Guardar($persona_data, "tbl_persona");
    }

    $idVigilancia = Guardar($ficha_influenza, "flureg_form");
    return $idVigilancia;
}

function consultarUsuario($tipo_identificacion, $numero_identificacion) {
    $SQLEncabezado = "Select count(*) from tbl_persona  where tipo_identificacion = " . $tipo_identificacion . " and numero_identificacion = '" . $numero_identificacion . "'";
    $conn = new Connection();
    $conn->initConn();
    $conn->prepare($SQLEncabezado);
    $conn->execute();
    $data = $conn->fetch();
    $conn->closeStmt();
    return (int) $data[0];
}

function ActualizarTabla($tabla, $objeto, $filtro, $datos) {
    $ok = true;
    $conn = new Connection();
    $conn->initConn();
    $conn->begin();
    $sql = ActualizarQuery($tabla, $objeto, $filtro);
    $conn->prepare($sql);
    //print_r($datos);
    $conn->params($datos);
    $conn->execute() ? null : $ok = false;
    $error = $conn->getError();
    $conn->closeStmt();
    $conn->getID();
    if (!$ok) {
        $conn->rollback();
        $conn->closeConn();
        exit;
    } else {
        $conn->commit();
    }
    $param = array();
    $param['id'] = $id;
    $param['ok'] = $ok;
    return $param;
}

function Guardar($data, $nombreTabla) {
    $ok = true;
    $conn = new Connection();
    $conn->initConn();
    $conn->begin();
    $sql = AgregarQuery($nombreTabla, $data);
    //echo $sql;
    $conn->prepare($sql);
    $conn->params($data);
    $conn->execute() ? null : $ok = false;
    $conn->closeStmt();
    $id = $conn->getID();
    if ($ok) {
        $conn->commit();
    } else {
        $conn->rollback();
        $id = -1;
    }
    $conn->closeConn();
    return $id;
}

function AgregarQuery($tabla, $data) {
    $values = array();
    $fields = array();
    $error = array();

    foreach ($data as $key => $value) {
        $fields[] = $key;
        $values[] = "?";
    }
    $fields = implode(',', $fields);
    $values = implode(',', $values);
    $sql = "INSERT INTO " . $tabla . "(" . $fields . ") VALUES(" . $values . ")";
    return $sql;
}

function ActualizarQuery($tabla, $data, $filtro) {
    $values = array();
    $fields = array();
    $where = "";

    foreach ($data as $key => $value)
        $values[] = $key . " = ?";
    $values = implode(',', $values);

    foreach ($filtro as $key => $value) {
        $where .= "AND " . $key . " = ? ";
    }

    $sql = "UPDATE " . $tabla . " SET " . $values . "
                    WHERE 1
                    " . $where;

    return $sql;
}

function replaceCharacter(&$contenido) {
    foreach ($contenido as &$value) {
        $value = array_map('utf8_encode', $value);
    }
//        $contenido=str_replace("á","a",$contenido);
//        $contenido=str_replace("é","e",$contenido);
//        $contenido=str_replace("í","i",$contenido);
//        $contenido=str_replace("ó","o",$contenido);
//        $contenido=str_replace("ú","u",$contenido);
//        
//        $contenido=str_replace("[ÁÀÂÃ]","A",$contenido);
//        $contenido=str_replace("[ÉÈÊ]","E",$contenido);
//        $contenido=str_replace("[ÍÌÎ]","I",$contenido);
//        $contenido=str_replace("[ÓÒÔÕ]","O",$contenido);
//        $contenido=str_replace("[ÚÙÛ]","U",$contenido);
//        
//        $contenido=str_replace("ä","a",$contenido);
//        $contenido=str_replace("ë","e",$contenido);
//        $contenido=str_replace("ï","i",$contenido);
//        $contenido=str_replace("ö","o",$contenido);
//        $contenido=str_replace("ü","u",$contenido);
//        
//        $contenido=str_replace("ñ", "n", $contenido);
//        $contenido=str_replace("Ñ", "N", $contenido);
    return $contenido;
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>
