<?php

require_once('libs/nusoap.php');
require_once('libs/DOMXML.php');
require_once('libs/EncodeDecode.php');
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Connection.php');
require_once('libs/caus/ConfigurationCAUS.php');
require_once('libs/caus/ConnectionCAUS.php');
require_once('libs/caus/ConnectionCAUS.php');
require_once('libs/dal/uceti/dalUceti.php');


$id = '<?xml version="1.0" encoding="UTF-8"?><xml><tipo_identificacion>3</tipo_identificacion><numero_identificacion>1030554741</numero_identificacion><primer_nombre>juan</primer_nombre><segundo_nombre>carlos</segundo_nombre><primer_apellido>romero</primer_apellido><segundo_apellido>castrillon</segundo_apellido><sin_nombre>0</sin_nombre><fecha_nacimiento>1989-3-23</fecha_nacimiento><edad>23</edad><tipo_edad>2</tipo_edad><sexo>1</sexo><id_region>6</id_region><id_corregimiento>282</id_corregimiento><localidad>-2</localidad><dir_referencia>dir ref</dir_referencia><id_pais>174</id_pais><id_ocupacion>-2</id_ocupacion><nombre_responsable>jcromero</nombre_responsable><tel_residencial>4520091</tel_residencial><dir_trabajo>dir ref</dir_trabajo><tel_trabajo>-2</tel_trabajo><id_pais_nacimiento>-2</id_pais_nacimiento><id_corregimiento_nacimiento>-2</id_corregimiento_nacimiento><tipo_identificacion1>3</tipo_identificacion1><numero_identificacion1>1030554741</numero_identificacion1><id_un>34</id_un><unidad_disponible>0</unidad_disponible><per_tipo_paciente>0</per_tipo_paciente><per_hospitalizado>1</per_hospitalizado><per_hospitalizado_lugar>34</per_hospitalizado_lugar><nombre_investigador>juan</nombre_investigador><nombre_registra>juanc</nombre_registra><fecha_formulario>2012-12-9</fecha_formulario><per_asegurado>1</per_asegurado><per_edad>23</per_edad><per_tipo_edad>2</per_tipo_edad><id_pais1>174</id_pais1><id_corregimiento1>282</id_corregimiento1><per_direccion>dir ref</per_direccion><per_direccion_otra>dir ref</per_direccion_otra><per_telefono>0</per_telefono><vac_tarjeta>1</vac_tarjeta><vac_segun_esquema>1</vac_segun_esquema><vac_fecha_ultima_dosis>2012-12-5</vac_fecha_ultima_dosis><vac_fluB_dosis>0</vac_fluB_dosis><vac_fluB_desconoce>0</vac_fluB_desconoce><vac_fluB_fecha>2012-12-5</vac_fluB_fecha><vac_flu_dosis>2</vac_flu_dosis><vac_flu_desconoce>2</vac_flu_desconoce><vac_flu_fecha>2012-12-4</vac_flu_fecha><vac_neumo7_dosis>3</vac_neumo7_dosis><vac_neumo7_desconoce>2</vac_neumo7_desconoce><vac_neumo7_fecha>2012-12-3</vac_neumo7_fecha><vac_neumo10_dosis>4</vac_neumo10_dosis><vac_neumo10_desconoce>2</vac_neumo10_desconoce><vac_neumo10_fecha>2012-12-2</vac_neumo10_fecha><vac_neumo13_dosis>5</vac_neumo13_dosis><vac_neumo13_desconoce>2</vac_neumo13_desconoce><vac_neumo13_fecha>2012-12-1</vac_neumo13_fecha><vac_neumo23_dosis>6</vac_neumo23_dosis><vac_neumo23_desconoce>0</vac_neumo23_desconoce><vac_neumo23_fecha>2012-11-30</vac_neumo23_fecha><vac_menin_dosis>7</vac_menin_dosis><vac_menin_desconoce>2</vac_menin_desconoce><vac_menin_fecha>2012-11-29</vac_menin_fecha><vac_nombre_otra>otro</vac_nombre_otra><riesgo_embarazo>-2</riesgo_embarazo><riesgo_trimestre>-2</riesgo_trimestre><riesgo_enf_cronica>2</riesgo_enf_cronica><riesgo_profesional>1</riesgo_profesional><riesgo_pro_cual>1</riesgo_pro_cual><riesgo_viaje>1</riesgo_viaje><riesgo_contacto_confirmado>1</riesgo_contacto_confirmado><riesgo_contacto_tipo>2</riesgo_contacto_tipo><riesgo_aislamiento>2</riesgo_aislamiento><riesgo_contacto_nombre>crystina</riesgo_contacto_nombre><id_evento>23485</id_evento><eve_sindrome>0</eve_sindrome><eve_centinela>0</eve_centinela><eve_inusitado>1</eve_inusitado><eve_imprevisto>0</eve_imprevisto><eve_excesivo>0</eve_excesivo><eve_conglomerado>0</eve_conglomerado><eve_neumo_bacteriana>0</eve_neumo_bacteriana><fecha_inicio_sintoma>2012-10-10</fecha_inicio_sintoma><fecha_hospitalizacion>2012-10-9</fecha_hospitalizacion><fecha_notificacion>2012-10-8</fecha_notificacion><fecha_egreso>2012-10-7</fecha_egreso><fecha_defuncion>2012-10-6</fecha_defuncion><antibiotico>1</antibiotico><antibiotico_cual>advil</antibiotico_cual><antibiotico_fecha>2012-10-9</antibiotico_fecha><antiviral>1</antiviral><antiviral_cual>ibuprofeno</antiviral_cual><antiviral_fecha>2012-10-10</antiviral_fecha><sintoma_fiebre>1</sintoma_fiebre><sintoma_tos>1</sintoma_tos><sintoma_garganta>1</sintoma_garganta><sintoma_rinorrea>1</sintoma_rinorrea><sintoma_respiratoria>1</sintoma_respiratoria><sintoma_otro>2</sintoma_otro><sintoma_nombre_otro>otro</sintoma_nombre_otro><fecha_fiebre>2012-10-6</fecha_fiebre><fecha_tos>2012-10-5</fecha_tos><fecha_garganta>2012-10-4</fecha_garganta><fecha_rinorrea>2012-10-3</fecha_rinorrea><fecha_respiratoria>2012-10-2</fecha_respiratoria><fecha_otro>2012-10-2</fecha_otro><torax_condensacion>1</torax_condensacion><torax_derrame>2</torax_derrame><torax_broncograma>2</torax_broncograma><torax_infiltrado>-2</torax_infiltrado><torax_otro>2</torax_otro><torax_nombre_otro>desfribir</torax_nombre_otro><semana_epi>41</semana_epi><anio>2012</anio></xml>';
$id = '<?xml version="1.0" encoding="UTF-8"?><xml><tipo_identificacion>2</tipo_identificacion><numero_identificacion>1030554242</numero_identificacion><primer_nombre>juan</primer_nombre><segundo_nombre>carlos</segundo_nombre><primer_apellido>romero</primer_apellido><segundo_apellido>castrilon</segundo_apellido><sin_nombre>0</sin_nombre><fecha_nacimiento>1989-3-23</fecha_nacimiento><edad>23</edad><tipo_edad>2</tipo_edad><sexo>1</sexo><id_region>5</id_region><id_corregimiento>232</id_corregimiento><localidad>-2</localidad><dir_referencia>dir ref</dir_referencia><id_pais>174</id_pais><id_ocupacion>-2</id_ocupacion><nombre_responsable>crys</nombre_responsable><tel_residencial>4520091</tel_residencial><dir_trabajo>dir ref</dir_trabajo><tel_trabajo>-2</tel_trabajo><id_pais_nacimiento>-2</id_pais_nacimiento><id_corregimiento_nacimiento>-2</id_corregimiento_nacimiento><tipo_identificacion1>2</tipo_identificacion1><numero_identificacion1>1030554242</numero_identificacion1><id_un>3136</id_un><unidad_disponible>1</unidad_disponible><per_tipo_paciente>0</per_tipo_paciente><per_hospitalizado>1</per_hospitalizado><per_hospitalizado_lugar>3136</per_hospitalizado_lugar><nombre_investigador>juan</nombre_investigador><nombre_registra>carlos</nombre_registra><fecha_formulario>2012-12-10</fecha_formulario><per_asegurado>1</per_asegurado><per_edad>23</per_edad><per_tipo_edad>2</per_tipo_edad><id_pais1>174</id_pais1><id_corregimiento1>232</id_corregimiento1><per_direccion>dir ref</per_direccion><per_direccion_otra>dir ref</per_direccion_otra><per_telefono>0</per_telefono><vac_tarjeta>1</vac_tarjeta><vac_segun_esquema>1</vac_segun_esquema><vac_fecha_ultima_dosis>2012-12-9</vac_fecha_ultima_dosis><vac_fluB_dosis>0</vac_fluB_dosis><vac_fluB_desconoce>0</vac_fluB_desconoce><vac_fluB_fecha>2012-12-9</vac_fluB_fecha><vac_flu_dosis>2</vac_flu_dosis><vac_flu_desconoce>2</vac_flu_desconoce><vac_flu_fecha>2012-12-8</vac_flu_fecha><vac_neumo7_dosis>3</vac_neumo7_dosis><vac_neumo7_desconoce>2</vac_neumo7_desconoce><vac_neumo7_fecha>2012-12-7</vac_neumo7_fecha><vac_neumo10_dosis>4</vac_neumo10_dosis><vac_neumo10_desconoce>2</vac_neumo10_desconoce><vac_neumo10_fecha>2012-12-6</vac_neumo10_fecha><vac_neumo13_dosis>5</vac_neumo13_dosis><vac_neumo13_desconoce>2</vac_neumo13_desconoce><vac_neumo13_fecha>2012-12-1</vac_neumo13_fecha><vac_neumo23_dosis>6</vac_neumo23_dosis><vac_neumo23_desconoce>0</vac_neumo23_desconoce><vac_neumo23_fecha>2012-12-4</vac_neumo23_fecha><vac_menin_dosis>7</vac_menin_dosis><vac_menin_desconoce>2</vac_menin_desconoce><vac_menin_fecha>2012-12-3</vac_menin_fecha><vac_nombre_otra>otra esoecifique</vac_nombre_otra><riesgo_embarazo>-2</riesgo_embarazo><riesgo_trimestre>-2</riesgo_trimestre><riesgo_enf_cronica>1</riesgo_enf_cronica><riesgo_profesional>1</riesgo_profesional><riesgo_pro_cual>2</riesgo_pro_cual><riesgo_viaje>1</riesgo_viaje><riesgo_contacto_confirmado>2</riesgo_contacto_confirmado><riesgo_contacto_tipo>-2</riesgo_contacto_tipo><riesgo_aislamiento>2</riesgo_aislamiento><riesgo_contacto_nombre>jcromero</riesgo_contacto_nombre><id_evento>23430</id_evento><eve_sindrome>0</eve_sindrome><eve_centinela>0</eve_centinela><eve_inusitado>0</eve_inusitado><eve_imprevisto>0</eve_imprevisto><eve_excesivo>0</eve_excesivo><eve_conglomerado>0</eve_conglomerado><eve_neumo_bacteriana>0</eve_neumo_bacteriana><fecha_inicio_sintoma>2012-12-9</fecha_inicio_sintoma><fecha_hospitalizacion>2012-12-8</fecha_hospitalizacion><fecha_notificacion>2012-12-7</fecha_notificacion><fecha_egreso>2012-12-6</fecha_egreso><fecha_defuncion>2012-12-5</fecha_defuncion><antibiotico>1</antibiotico><antibiotico_cual>cual antibiotico</antibiotico_cual><antibiotico_fecha>2012-12-10</antibiotico_fecha><antiviral>1</antiviral><antiviral_cual>cual antiviral</antiviral_cual><antiviral_fecha>2012-12-10</antiviral_fecha><sintoma_fiebre>1</sintoma_fiebre><sintoma_tos>1</sintoma_tos><sintoma_garganta>1</sintoma_garganta><sintoma_rinorrea>1</sintoma_rinorrea><sintoma_respiratoria>1</sintoma_respiratoria><sintoma_otro>1</sintoma_otro><sintoma_nombre_otro>otra</sintoma_nombre_otro><fecha_fiebre>2012-12-5</fecha_fiebre><fecha_tos>2012-12-6</fecha_tos><fecha_garganta>2012-12-7</fecha_garganta><fecha_rinorrea>2012-12-8</fecha_rinorrea><fecha_respiratoria>2012-12-9</fecha_respiratoria><fecha_otro>2012-12-10</fecha_otro><torax_condensacion>1</torax_condensacion><torax_derrame>1</torax_derrame><torax_broncograma>1</torax_broncograma><torax_infiltrado>-2</torax_infiltrado><torax_otro>1</torax_otro><torax_nombre_otro>describir</torax_nombre_otro><semana_epi>50</semana_epi><anio>2012</anio></xml>';

$operacion = "INSERT";

$node = '0';

servicio($id, $node, $operacion);

function servicio($id, $root, $operacion) {
    $OPERACIONSELECTUSER = "SELECTUSER";
    $OPERACIONSELECT = "SELECT";
    $OPERACIONINSERT = "INSERT";
    $OPERACIONUPDATE = "UPDATE";

    if ($operacion == $OPERACIONSELECTUSER) {
        $conn = new ConnectionCAUS();
        $sql = $id;
        $conn->initConn();
        $node_ = $root;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $data = replaceCharacter($data);
        $contenido = '<?xml version="1.0" encoding="UTF-8"?>';
        $contenido.= DOM::arrayToXMLString($data, $node_);
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
        $contenido = '<?xml version="1.0" encoding="UTF-8"?>';
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
        }
    }
    if ($operacion == $OPERACIONUPDATE) {
        
    }
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
                    $persona_data["tipo_identificacion"] = (int)$valueUno["__content__"];
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
                    $persona_data["edad"] = (int)$valueUno["__content__"];
                    $contenido.=$persona_data["edad"];
                    $contenido.=";";
                }
            }
            if ($index == 9) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["tipo_edad"] = (int)$valueUno["__content__"];
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
                    $persona_data["id_region"] = (int)$valueUno["__content__"];
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
                    $persona_data["id_ocupacion"] = (int)$valueUno["__content__"];
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
                    $persona_data["id_pais_nacimiento"] = (int)$valueUno["__content__"];
                    $contenido.=$persona_data["id_pais_nacimiento"];
                    $contenido.=";";
                }
            }
            if ($index == 22) {
                if ($valueUno["__content__"] != $nodata) {
                    $persona_data["id_corregimiento_nacimiento"] = (int)$valueUno["__content__"];
                    $contenido.=$persona_data["id_corregimiento_nacimiento"];
                    $contenido.=";";
                }
            }                     
            /**
             *AQUI FICHA INFLUENZA 
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
                    $ficha_influenza["id_un"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["id_un"];
                    $contenido2.=";";
                }
            }
            if ($index == 26) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["unidad_disponible"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["unidad_disponible"];
                    $contenido2.=";";
                }
            }
            if ($index == 27) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["per_tipo_paciente"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["per_tipo_paciente"];
                    $contenido2.=";";
                }
            }
            if ($index == 28) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["per_hospitalizado"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["per_hospitalizado"];
                    $contenido2.=";";
                }
            }
            if ($index == 29) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["per_hospitalizado_lugar"] = (int)$valueUno["__content__"];
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
                    $ficha_influenza["per_asegurado"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["per_asegurado"];
                    $contenido2.=";";
                }
            }
            if ($index == 34) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["per_edad"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["per_edad"];
                    $contenido2.=";";
                }
            }
            if ($index == 35) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["per_tipo_edad"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["per_tipo_edad"];
                    $contenido2.=";";
                }
            }
            if ($index == 36) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["id_pais"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["id_pais"];
                    $contenido2.=";";
                }
            }
            if ($index == 37) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["id_corregimiento"] = (int)$valueUno["__content__"];
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
                    $ficha_influenza["per_telefono"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["per_telefono"];
                    $contenido2.=";";
                }
            }
            if ($index == 41) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_tarjeta"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_tarjeta"];
                    $contenido2.=";";
                }
            }
            if ($index == 42) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_segun_esquema"] = (int)$valueUno["__content__"];
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
                    $ficha_influenza["vac_fluB_dosis"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_fluB_dosis"];
                    $contenido2.=";";
                }
            }
            if ($index == 45) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_fluB_desconoce"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_fluB_desconoce"];
                    $contenido2.=";";
                }
            }
            if ($index == 46) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_fluB_fecha"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_fluB_fecha"];
                    $contenido2.=";";
                }
            }
            if ($index == 47) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_flu_dosis"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_flu_dosis"];
                    $contenido2.=";";
                }
            }
            if ($index == 48) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_flu_desconoce"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_flu_desconoce"];
                    $contenido2.=";";
                }
            }
            if ($index == 49) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_flu_fecha"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_flu_fecha"];
                    $contenido2.=";";
                }
            }
            if ($index == 50) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_neumo7_dosis"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_neumo7_dosis"];
                    $contenido2.=";";
                }
            }
            if ($index == 51) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_neumo7_desconoce"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_neumo7_desconoce"];
                    $contenido2.=";";
                }
            }
            if ($index == 52) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_neumo7_fecha"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_neumo7_fecha"];
                    $contenido2.=";";
                }
            }
            if ($index == 53) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_neumo10_dosis"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_neumo10_dosis"];
                    $contenido2.=";";
                }
            }
            if ($index == 54) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_neumo10_desconoce"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_neumo10_desconoce"];
                    $contenido2.=";";
                }
            }
            if ($index == 55) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_neumo10_fecha"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_neumo10_fecha"];
                    $contenido2.=";";
                }
            }
            if ($index == 56) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_neumo13_dosis"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_neumo13_dosis"];
                    $contenido2.=";";
                }
            }
            if ($index == 57) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_neumo13_desconoce"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_neumo13_desconoce"];
                    $contenido2.=";";
                }
            }
            if ($index == 58) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_neumo13_fecha"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_neumo13_fecha"];
                    $contenido2.=";";
                }
            }
            if ($index == 59) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_neumo23_dosis"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_neumo23_dosis"];
                    $contenido2.=";";
                }
            }
            if ($index == 60) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_neumo23_desconoce"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_neumo23_desconoce"];
                    $contenido2.=";";
                }
            }
            if ($index == 61) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_neumo23_fecha"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_neumo23_fecha"];
                    $contenido2.=";";
                }
            }
            if ($index == 62) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_menin_dosis"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_menin_dosis"];
                    $contenido2.=";";
                }
            }
            if ($index == 63) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_menin_desconoce"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_menin_desconoce"];
                    $contenido2.=";";
                }
            }
            if ($index == 64) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_menin_fecha"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_menin_fecha"];
                    $contenido2.=";";
                }
            }
            if ($index == 65) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["vac_nombre_otra"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["vac_nombre_otra"];
                    $contenido2.=";";
                }
            }
            if ($index == 66) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_embarazo"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_embarazo"];
                    $contenido2.=";";
                }
            }
            if ($index == 67) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_trimestre"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_trimestre"];
                    $contenido2.=";";
                }
            }
            if ($index == 68) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_enf_cronica"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_enf_cronica"];
                    $contenido2.=";";
                }
            }
            if ($index == 69) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_profesional"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_profesional"];
                    $contenido2.=";";
                }
            }
            if ($index == 70) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_pro_cual"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_pro_cual"];
                    $contenido2.=";";
                }
            }
            if ($index == 71) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_viaje"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_viaje"];
                    $contenido2.=";";
                }
            }
            if ($index == 72) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_contacto_confirmado"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_contacto_confirmado"];
                    $contenido2.=";";
                }
            }
            if ($index == 73) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_contacto_tipo"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_contacto_tipo"];
                    $contenido2.=";";
                }
            }
            if ($index == 74) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_aislamiento"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_aislamiento"];
                    $contenido2.=";";
                }
            }
            if ($index == 75) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["riesgo_contacto_nombre"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["riesgo_contacto_nombre"];
                    $contenido2.=";";
                }
            }
            if ($index == 76) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["id_evento"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["id_evento"];
                    $contenido2.=";";
                }
            }
            if ($index == 77) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["eve_sindrome"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["eve_sindrome"];
                    $contenido2.=";";
                }
            }
            if ($index == 78) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["eve_centinela"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["eve_centinela"];
                    $contenido2.=";";
                }
            }
            if ($index == 79) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["eve_inusitado"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["eve_inusitado"];
                    $contenido2.=";";
                }
            }
            if ($index == 80) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["eve_imprevisto"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["eve_imprevisto"];
                    $contenido2.=";";
                }
            }
            if ($index == 81) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["eve_excesivo"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["eve_excesivo"];
                    $contenido2.=";";
                }
            }
            if ($index == 82) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["eve_conglomerado"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["eve_conglomerado"];
                    $contenido2.=";";
                }
            }
            if ($index == 83) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["eve_neumo_bacteriana"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["eve_neumo_bacteriana"];
                    $contenido2.=";";
                }
            }
            if ($index == 84) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_inicio_sintoma"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_inicio_sintoma"];
                    $contenido2.=";";
                }
            }
            if ($index == 85) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_hospitalizacion"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_hospitalizacion"];
                    $contenido2.=";";
                }
            }
            if ($index == 86) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_notificacion"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_notificacion"];
                    $contenido2.=";";
                }
            }
            if ($index == 87) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_egreso"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_egreso"];
                    $contenido2.=";";
                }
            }
            if ($index == 88) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_defuncion"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_defuncion"];
                    $contenido2.=";";
                }
            }
            if ($index == 89) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["antibiotico"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["antibiotico"];
                    $contenido2.=";";
                }
            }
            if ($index == 90) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["antibiotico_cual"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["antibiotico_cual"];
                    $contenido2.=";";
                }
            }
            if ($index == 91) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["antibiotico_fecha"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["antibiotico_fecha"];
                    $contenido2.=";";
                }
            }
            if ($index == 92) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["antiviral"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["antiviral"];
                    $contenido2.=";";
                }
            }
            if ($index == 93) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["antiviral_cual"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["antiviral_cual"];
                    $contenido2.=";";
                }
            }
            if ($index == 94) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["antiviral_fecha"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["antiviral_fecha"];
                    $contenido2.=";";
                }
            }
            if ($index == 95) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["sintoma_fiebre"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["sintoma_fiebre"];
                    $contenido2.=";";
                }
            }
            if ($index == 96) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["sintoma_tos"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["sintoma_tos"];
                    $contenido2.=";";
                }
            }
            if ($index == 97) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["sintoma_garganta"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["sintoma_garganta"];
                    $contenido2.=";";
                }
            }
            if ($index == 98) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["sintoma_rinorrea"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["sintoma_rinorrea"];
                    $contenido2.=";";
                }
            }
            if ($index == 99) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["sintoma_respiratoria"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["sintoma_respiratoria"];
                    $contenido2.=";";
                }
            }
            if ($index == 100) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["sintoma_otro"] =(int) $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["sintoma_otro"];
                    $contenido2.=";";
                }
            }
            if ($index == 101) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["sintoma_nombre_otro"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["sintoma_nombre_otro"];
                    $contenido2.=";";
                }
            }
            if ($index == 102) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_fiebre"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_fiebre"];
                    $contenido2.=";";
                }
            }
            if ($index == 103) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_tos"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_tos"];
                    $contenido2.=";";
                }
            }
            if ($index == 104) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_garganta"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_garganta"];
                    $contenido2.=";";
                }
            }
            if ($index == 105) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_rinorrea"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_rinorrea"];
                    $contenido2.=";";
                }
            }
            if ($index == 106) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_respiratoria"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_respiratoria"];
                    $contenido2.=";";
                }
            }
            if ($index == 107) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["fecha_otro"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["fecha_otro"];
                    $contenido2.=";";
                }
            }
            if ($index == 108) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["torax_condensacion"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["torax_condensacion"];
                    $contenido2.=";";
                }
            }
            if ($index == 109) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["torax_derrame"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["torax_derrame"];
                    $contenido2.=";";
                }
            }
            if ($index == 110) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["torax_broncograma"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["torax_broncograma"];
                    $contenido2.=";";
                }
            }
            if ($index == 111) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["torax_infiltrado"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["torax_infiltrado"];
                    $contenido2.=";";
                }
            }
            if ($index == 112) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["torax_otro"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["torax_otro"];
                    $contenido2.=";";
                }
            }
            if ($index == 113) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["torax_nombre_otro"] = $valueUno["__content__"];
                    $contenido2.=$ficha_influenza["torax_nombre_otro"];
                    $contenido2.=";";
                }
            }
            if ($index == 114) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["semana_epi"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["semana_epi"];
                    $contenido2.=";";
                }
            }
            if ($index == 115) {
                if ($valueUno["__content__"] != $nodata) {
                    $ficha_influenza["anio"] = (int)$valueUno["__content__"];
                    $contenido2.=$ficha_influenza["anio"];
                    $contenido2.=";";
                }
            }
            
            $index++;
        }
    }    
    echo $contenido;
    //$param = dalUceti::GuardarTabla($conn, "tbl_persona", $persona_data);
    $param = Guardar($persona_data, "tbl_persona");
    echo '---------------------------------\n';
    echo $contenido2;
    
    $idVigilancia = Guardar($ficha_influenza, "flureg_form");
    //$params = dalUceti::GuardarTabla($conn, "flureg_form", $idVigilancia);
    return $idVigilancia;
}


function Guardar($data, $nombreTabla){
    $ok = true;
    $conn = new Connection();
    $conn->initConn();
    $conn->begin();
    $sql = AgregarQuery($nombreTabla ,$data);
    echo $sql;
    $conn->prepare($sql);
    $conn->params($data);
    $conn->execute() ? null : $ok = false;
    $conn->closeStmt();
    $id = $conn->getID();
    if ($ok){
        $conn->commit();
    }else{
        $conn->rollback();
        $id = -1;
    }
    $conn->closeConn();
    return $id;     
}
    
    function AgregarQuery($tabla,$data){
        $values = array();
        $fields = array();
        $error = array(); 
		
        foreach($data as $key=>$value) {
            $fields[] = $key;
            $values[] = "?";
        }
	
        $fields = implode(',', $fields);
        $values = implode(',', $values);
    	
        $sql = "INSERT INTO ".$tabla."(".$fields.") VALUES(".$values.")";
    	
    	return $sql;
        
    }


function replaceCharacter($contenido) {

    $contenido = str_replace("á", "a", $contenido);
    $contenido = str_replace("é", "e", $contenido);
    $contenido = str_replace("í", "i", $contenido);
    $contenido = str_replace("ó", "o", $contenido);
    $contenido = str_replace("ú", "u", $contenido);

    $contenido = str_replace("[ÁÀÂÃ]", "A", $contenido);
    $contenido = str_replace("[ÉÈÊ]", "E", $contenido);
    $contenido = str_replace("[ÍÌÎ]", "I", $contenido);
    $contenido = str_replace("[ÓÒÔÕ]", "O", $contenido);
    $contenido = str_replace("[ÚÙÛ]", "U", $contenido);

    $contenido = str_replace("ä", "a", $contenido);
    $contenido = str_replace("ë", "e", $contenido);
    $contenido = str_replace("ï", "i", $contenido);
    $contenido = str_replace("ö", "o", $contenido);
    $contenido = str_replace("ü", "u", $contenido);

    $contenido = str_replace("ñ", "n", $contenido);
    $contenido = str_replace("Ñ", "N", $contenido);
    return $contenido;
}
?>
