<?php

require_once('libs/Configuration.php');
require_once('libs/ConfigurationHospitalInfluenza.php');
require_once('libs/Connection.php');
require_once ('libs/caus/clsCaus.php');
require_once('libs/helper/helperString.php');

class helperUceti {

    public static function yaExistePersona($individuo) {
        $sql = "select count(*) from tbl_persona where tipo_identificacion='" . $individuo['tipo_identificacion'] . "' and numero_identificacion='" . $individuo['numero_identificacion'] . "'";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }

    public static function yaExisteUceti($uceti) {
        //return 0;
        $sql = "select count(*) from flureg_form where tipo_identificacion='" . $uceti['tipo_identificacion'] . "' and numero_identificacion='" . $uceti['numero_identificacion'] . "'";
        //echo $sql;
        $conn = new Connection();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }

    //Array ( [id_individuo] => [asegurado] => 1 [tipoId] => 4 [identificador] => RN22752378 [primer_nombre] => Diego [segundo_nombre] => Fernando [primer_apellido] => Troncoso [segundo_apellido] => Silva [fecha_nacimiento] => 17/05/1987 [tipo_edad] => 3 [edad] => 25 [sexo] => M [nombre_responsable] => Fernando Troncoso [provincia] => 8 [region] => 8 [distrito] => 53 [corregimiento] => 499 [direccion] => El Doncello caqueta - 3 cuadras al sur de la iglesia principal [punto_referencia] => CASA DONCELLO [telefono] => 4566 7777 ) 
    public static function dataTblPersona($data) {
        // DATOS DEL INDIVIDUO
        $individuo = array();
        $individuo["tipo_identificacion"] = (!isset($data["individuo"]["tipoId"]) ? NULL : $data["individuo"]["tipoId"]);
        $individuo["numero_identificacion"] = (!isset($data["individuo"]["identificador"]) ? NULL : $data["individuo"]["identificador"]);

        $individuo["primer_nombre"] = (!isset($data["individuo"]["primer_nombre"]) ? NULL : strtoupper($data["individuo"]["primer_nombre"]));
        $individuo["segundo_nombre"] = (!isset($data["individuo"]["segundo_nombre"]) ? NULL : strtoupper($data["individuo"]["segundo_nombre"]));
        $individuo["primer_apellido"] = (!isset($data["individuo"]["primer_apellido"]) ? NULL : strtoupper($data["individuo"]["primer_apellido"]));
        $individuo["segundo_apellido"] = (!isset($data["individuo"]["segundo_apellido"]) ? NULL : strtoupper($data["individuo"]["segundo_apellido"]));

        $individuo["fecha_nacimiento"] = (!isset($data["individuo"]["fecha_nacimiento"]) ? NULL : helperString::toDate($data["individuo"]["fecha_nacimiento"]));

        $individuo["edad"] = (!isset($data["individuo"]["edad"]) ? NULL : $data["individuo"]["edad"]);
        $individuo["tipo_edad"] = (!isset($data["individuo"]["tipo_edad"]) ? '4' : $data["individuo"]["tipo_edad"]);
        $individuo["sexo"] = (!isset($data["individuo"]["sexo"]) ? '0' : $data["individuo"]["sexo"]);

        $individuo["id_region"] = $data["individuo"]["region"];
        $individuo["id_corregimiento"] = $data["individuo"]["corregimiento"];

        $individuo["dir_referencia"] = (!isset($data["individuo"]["direccion"]) ? NULL : strtoupper($data["individuo"]["direccion"]));
        $individuo["dir_trabajo"] = (!isset($data["individuo"]["otra_direccion"]) ? NULL : strtoupper($data["individuo"]["otra_direccion"]));
        $individuo["id_pais"] = "174";

        $individuo["nombre_responsable"] = (!isset($data["individuo"]["nombre_responsable"]) ? NULL : strtoupper($data["individuo"]["nombre_responsable"]));
        $individuo["tel_residencial"] = (!isset($data["individuo"]["telefono"]) ? NULL : strtoupper($data["individuo"]["telefono"]));

        return $individuo;
    }

    public static function dataUcetiEnfermedades($data) {
        $uceti = array();
        $uceti["enfermedades"] = (!isset($data["enfermedades"]["globalEnfermedadesRelacionados"]) ? NULL : $data["enfermedades"]["globalEnfermedadesRelacionados"]);
        return $uceti;
    }

    public static function dataUcetiMuestrasSILAB($data) {
        $uceti = array();
        $muestras = array();
        $muestra = array();
        $muestras = (!isset($data["muestras"]["globalMuestras"]) ? 0 : explode("},{", $data["muestras"]["globalMuestras"]));
        $max = sizeof($muestras);
        for ($i = 0; $i < $max; $i++) {
            $muestra = explode("#-#", $muestras[$i]);
            $muestra[0] = str_replace("{", "", $muestra[0]);
            $uceti[$i]["id_muestra"] = $muestra[0];
            $uceti[$i]["codigo_global"] = $muestra[1];
            $uceti[$i]["codigo_correlativo"] = $muestra[2];
            $uceti[$i]["tipo_muestra"] = $muestra[3];
            $uceti[$i]["fecha_inicio_sintoma"] = $muestra[4];
            $uceti[$i]["fecha_toma"] = $muestra[5];
            $uceti[$i]["fecha_recepcion"] = $muestra[6];
            $uceti[$i]["unidad_notificadora"] = $muestra[7];
            $uceti[$i]["estado_muestra"] = $muestra[8];
            $uceti[$i]["resultado"] = $muestra[9];
            $uceti[$i]["tipo1"] = $muestra[10];
            $uceti[$i]["subtipo1"] = $muestra[11];
            $uceti[$i]["tipo2"] = $muestra[12];
            $uceti[$i]["subtipo2"] = $muestra[13];
            $uceti[$i]["tipo3"] = $muestra[14];
            $uceti[$i]["subtipo3"] = $muestra[15];
            $uceti[$i]["local_remoto"] = $muestra[16];
            $uceti[$i]["comentario_resultado"] = $muestra[17];
            $muestra[18] = str_replace("}", "", $muestra[18]);
            $muestra[18] = str_replace("},", "", $muestra[18]);
            $muestra[18] = str_replace(",,", "", $muestra[18]);
            $muestra[18] = str_replace(",,,", "", $muestra[18]);
            $uceti[$i]["lab_proceso"] = $muestra[18];
        }
        return $uceti;
    }

    public static function dataUcetiPruebasSILAB($data) {
        $uceti = array();
        $pruebas = array();
        $prueba = array();
        $pos = 0;
        $pruebas = (!isset($data["muestras"]["globalPruebas"]) ? 0 : explode("},{", str_replace("},,{", "},{", $data["muestras"]["globalPruebas"])
                        ));
        $max = sizeof($pruebas);
        for ($i = 0; $i < $max; $i++) {
            $prueba = explode("#-#", $pruebas[$i]);
//            print_r($prueba);
//            echo "<br/>";
            $prueba[0] = str_replace("{", "", $prueba[0]);
            if ($prueba[0] != "no") {
                $uceti[$pos]["id_muestra"] = $prueba[0];
                $uceti[$pos]["nombre_prueba"] = $prueba[1];
                $uceti[$pos]["resultado_prueba"] = $prueba[2];
                $uceti[$pos]["fecha_prueba"] = $prueba[3];
                $uceti[$pos]["presencia_prueba"] = $prueba[4];
                $uceti[$pos]["ag_prueba"] = $prueba[5];
                $uceti[$pos]["if_prueba"] = $prueba[6];
                $prueba[7] = str_replace("},", "", $prueba[7]);
                $uceti[$pos]["Comentario_prueba"] = $prueba[7];

                $pos++;
            }
        }
        return $uceti;
    }

    public static function dataUcetiForm($data) {
        //print_r($data);
        $uceti = array();

        $muestras = array();
        $muestras = (!isset($data["muestras"]["globalMuestras"]) ? 0 : explode("},{", $data["muestras"]["globalMuestras"]));
        if ($muestras != NULL && isset($muestras[0])) {
            if ($muestras[0] != "") {
//            print_r($muestras);
                $uceti["pendiente_silab"] = "1";
                $uceti["actualizacion_silab"] = date("Y-m-d h:i:s");
            } else {
                $uceti["pendiente_silab"] = "0";
                $uceti["actualizacion_silab"] = NULL;
            }
        }

        if ($data["GuardarPrevio"]["Guardar"] != "") {
            if ($data["GuardarPrevio"]["Guardar"] != "2")
                $uceti["pendiente_uceti"] = $data["GuardarPrevio"]["Guardar"];
        }
        //Notificacion
        if (isset($data["notificacion"]["unidad_disponible"])) {
            $uceti["id_un"] = $data["notificacion"]["id_un"]; // se necesita para que pueda ver el hospital el user
//            $uceti["id_un"] = NULL;
            $uceti["unidad_disponible"] = "0";
        } else {
            $uceti["id_un"] = $data["notificacion"]["id_un"];
            $uceti["unidad_disponible"] = "1";
        }

        $uceti["per_tipo_paciente"] = (!isset($data["notificacion"]["tipoPaciente"]) ? '4' : $data["notificacion"]["tipoPaciente"]);
        $uceti["per_hospitalizado"] = (!isset($data["notificacion"]["hospitalizado"]) ? NULL : $data["notificacion"]["hospitalizado"]);
        $uceti["per_hospitalizado_lugar"] = (!isset($data["notificacion"]["hospitalizado"]) ? NULL : $data["notificacion"]["hospitalizado_lugar"]);
        if ($uceti["per_hospitalizado_lugar"] == '0') {
            $uceti["per_hospitalizado_lugar"] = NULL;
        }
        $uceti["nombre_investigador"] = (!isset($data["notificacion"]["nombreInvestigador"]) ? NULL : $data["notificacion"]["nombreInvestigador"]);
        $uceti["nombre_registra"] = (!isset($data["notificacion"]["nombreRegistra"]) ? NULL : $data["notificacion"]["nombreRegistra"]);
        $uceti["fecha_formulario"] = (!isset($data["notificacion"]["fecha_formulario"]) ? NULL : helperString::toDate($data["notificacion"]["fecha_formulario"]));
        //Individuo
        $uceti["per_asegurado"] = (!isset($data["individuo"]["asegurado"]) ? NULL : $data["individuo"]["asegurado"]);
        $uceti["tipo_identificacion"] = (!isset($data["individuo"]["tipoId"]) ? NULL : $data["individuo"]["tipoId"]);
        $uceti["numero_identificacion"] = (!isset($data["individuo"]["identificador"]) ? NULL : $data["individuo"]["identificador"]);
        $uceti["per_edad"] = (!isset($data["individuo"]["edad"]) ? NULL : $data["individuo"]["edad"]);
        $uceti["per_tipo_edad"] = (!isset($data["individuo"]["tipo_edad"]) ? '4' : $data["individuo"]["tipo_edad"]);
        $uceti["id_pais"] = "174";
        $uceti["id_corregimiento"] = $data["individuo"]["corregimiento"];
        $uceti["per_direccion"] = (!isset($data["individuo"]["direccion"]) ? NULL : strtoupper($data["individuo"]["direccion"]));
        $uceti["per_direccion_otra"] = (!isset($data["individuo"]["otra_direccion"]) ? NULL : strtoupper($data["individuo"]["otra_direccion"]));
        $uceti["per_telefono"] = (!isset($data["individuo"]["telefono"]) ? NULL : strtoupper($data["individuo"]["telefono"]));

        //Antecedentes
        $uceti["vac_tarjeta"] = (!isset($data["antecedentes"]["tarVacuna"]) ? NULL : $data["antecedentes"]["tarVacuna"]);
        if ($uceti["vac_tarjeta"] == '1') {
            $uceti["vac_segun_esquema"] = (!isset($data["antecedentes"]["vacEsquema"]) ? NULL : $data["antecedentes"]["vacEsquema"]);
            $uceti["vac_fecha_ultima_dosis"] = (!isset($data["antecedentes"]["fecha_ult_dosis"]) ? NULL : helperString::toDate($data["antecedentes"]["fecha_ult_dosis"]));
            $uceti["vac_fecha_anio_previo"] = (!isset($data["antecedentes"]["fecha_anio_previo"]) ? NULL : helperString::toDate($data["antecedentes"]["fecha_anio_previo"]));
        } else {
            $uceti["vac_segun_esquema"] = NULL;
            $uceti["vac_fecha_ultima_dosis"] = NULL;
            $uceti["vac_fecha_anio_previo"] = NULL;
        }
//        $uceti["vac_nombre_otra"] = (!isset($data["antecedentes"]["nombre_otra"]) ? NULL : $data["antecedentes"]["nombre_otra"]);
        //Antecedentes Factor de riesgo
        $sexo = (!isset($data["individuo"]["sexo"]) ? '0' : $data["individuo"]["sexo"]);
        if ($sexo != 'M') {
            $uceti["riesgo_embarazo"] = (!isset($data["antecedentes"]["embarazo"]) ? NULL : $data["antecedentes"]["embarazo"]);
            if ($uceti["riesgo_embarazo"] == '1')
                $uceti["riesgo_trimestre"] = (!isset($data["antecedentes"]["trimestre"]) ? NULL : $data["antecedentes"]["trimestre"]);
            else
                $uceti["riesgo_trimestre"] = NULL;
        } else {
            $uceti["riesgo_embarazo"] = NULL;
            $uceti["riesgo_trimestre"] = NULL;
        }
        $uceti["riesgo_enf_cronica"] = (!isset($data["antecedentes"]["cronica"]) ? NULL : $data["antecedentes"]["cronica"]);
        $uceti["riesgo_profesional"] = (!isset($data["antecedentes"]["riesgo"]) ? NULL : $data["antecedentes"]["riesgo"]);
        if ($uceti["riesgo_profesional"] == '1') {
            $uceti["riesgo_pro_cual"] = (!isset($data["antecedentes"]["riesgo_cual"]) ? NULL : $data["antecedentes"]["riesgo_cual"]);
        } else {
            $uceti["riesgo_pro_cual"] = NULL;
        }
        $uceti["riesgo_viaje"] = (!isset($data["antecedentes"]["viaje"]) ? NULL : $data["antecedentes"]["viaje"]);
        if ($uceti["riesgo_viaje"] == '1') {
            $uceti["riesgo_viaje_donde"] = (!isset($data["antecedentes"]["viaje_donde"]) ? NULL : $data["antecedentes"]["viaje_donde"]);
        } else {
            $uceti["riesgo_viaje_donde"] = NULL;
        }
        $uceti["riesgo_contacto_confirmado"] = (!isset($data["antecedentes"]["contacto"]) ? NULL : $data["antecedentes"]["contacto"]);
        $uceti["riesgo_contacto_tipo"] = (!isset($data["antecedentes"]["contacto_tipo"]) ? NULL : $data["antecedentes"]["contacto_tipo"]);
        $uceti["riesgo_aislamiento"] = (!isset($data["antecedentes"]["aislamiento"]) ? NULL : $data["antecedentes"]["aislamiento"]);
        $uceti["riesgo_contacto_nombre"] = (!isset($data["antecedentes"]["nombre_contacto"]) ? NULL : $data["antecedentes"]["nombre_contacto"]);

        //Datos clinicos
        $uceti["id_evento"] = (!isset($data["datos_clinicos"]["evento_id"]) ? NULL : $data["datos_clinicos"]["evento_id"]);
        $uceti["eve_sindrome"] = (isset($data["datos_clinicos"]["check_gripal"]) ? '1' : '0');
        $uceti["eve_centinela"] = (isset($data["datos_clinicos"]["check_centinela"]) ? '1' : '0');
        $uceti["eve_inusitado"] = (isset($data["datos_clinicos"]["check_inusitado"]) ? '1' : '0');
        $uceti["eve_imprevisto"] = (isset($data["datos_clinicos"]["check_imprevisto"]) ? '1' : '0');
        $uceti["eve_excesivo"] = (isset($data["datos_clinicos"]["check_excesivo"]) ? '1' : '0');
        $uceti["eve_conglomerado"] = (isset($data["datos_clinicos"]["check_conglomerado"]) ? '1' : '0');
        $uceti["eve_neumo_bacteriana"] = (isset($data["datos_clinicos"]["check_neumonia"]) ? '1' : '0');
        $uceti["fecha_inicio_sintoma"] = (!isset($data["datos_clinicos"]["fecha_inicio_sintomas"]) ? NULL : helperString::toDate($data["datos_clinicos"]["fecha_inicio_sintomas"]));
        $uceti["fecha_hospitalizacion"] = (!isset($data["datos_clinicos"]["fecha_hospitalizacion"]) ? NULL : helperString::toDate($data["datos_clinicos"]["fecha_hospitalizacion"]));
        $uceti["fecha_notificacion"] = (!isset($data["datos_clinicos"]["fecha_notificacion"]) ? NULL : helperString::toDate($data["datos_clinicos"]["fecha_notificacion"]));
        $uceti["fecha_egreso"] = (!isset($data["datos_clinicos"]["fecha_egreso"]) ? NULL : helperString::toDate($data["datos_clinicos"]["fecha_egreso"]));
        $uceti["fecha_defuncion"] = (!isset($data["datos_clinicos"]["fecha_defuncion"]) ? NULL : helperString::toDate($data["datos_clinicos"]["fecha_defuncion"]));
        $uceti["antibiotico"] = (!isset($data["datos_clinicos"]["antibioticos_ultima_semana"]) ? NULL : $data["datos_clinicos"]["antibioticos_ultima_semana"]);
        if ($uceti["antibiotico"] == '1') {
            $uceti["antibiotico_cual"] = (!isset($data["datos_clinicos"]["antibioticos_cual"]) ? NULL : $data["datos_clinicos"]["antibioticos_cual"]);
            $uceti["antibiotico_fecha"] = (!isset($data["datos_clinicos"]["antibioticos_fecha"]) ? NULL : helperString::toDate($data["datos_clinicos"]["antibioticos_fecha"]));
        } else {
            $uceti["antibiotico_cual"] = NULL;
            $uceti["antibiotico_fecha"] = NULL;
        }
        $uceti["antiviral"] = (!isset($data["datos_clinicos"]["antivirales"]) ? NULL : $data["datos_clinicos"]["antivirales"]);
        if ($uceti["antiviral"] == '1') {
            $uceti["antiviral_cual"] = (!isset($data["datos_clinicos"]["antivirales_cual"]) ? NULL : $data["datos_clinicos"]["antivirales_cual"]);
            $uceti["antiviral_fecha"] = (!isset($data["datos_clinicos"]["antivirales_fecha"]) ? NULL : helperString::toDate($data["datos_clinicos"]["antivirales_fecha"]));
        } else {
            $uceti["antiviral_cual"] = NULL;
            $uceti["antiviral_fecha"] = NULL;
        }
        $uceti["sintoma_fiebre"] = (!isset($data["datos_clinicos"]["fiebre"]) ? NULL : $data["datos_clinicos"]["fiebre"]);
        if ($uceti["sintoma_fiebre"] == '1') {
            $uceti["fecha_fiebre"] = (!isset($data["datos_clinicos"]["fecha_fiebre"]) ? NULL : helperString::toDate($data["datos_clinicos"]["fecha_fiebre"]));
        } else {
            $uceti["fecha_fiebre"] = NULL;
        }

        $uceti["sintoma_tos"] = (!isset($data["datos_clinicos"]["tos"]) ? NULL : $data["datos_clinicos"]["tos"]);
        if ($uceti["sintoma_tos"] == '1') {
            $uceti["fecha_tos"] = (!isset($data["datos_clinicos"]["fecha_tos"]) ? NULL : helperString::toDate($data["datos_clinicos"]["fecha_tos"]));
        } else {
            $uceti["fecha_tos"] = NULL;
        }

        $uceti["sintoma_garganta"] = (!isset($data["datos_clinicos"]["garganta"]) ? NULL : $data["datos_clinicos"]["garganta"]);
        if ($uceti["sintoma_tos"] == '1') {
            $uceti["fecha_garganta"] = (!isset($data["datos_clinicos"]["fecha_garganta"]) ? NULL : helperString::toDate($data["datos_clinicos"]["fecha_garganta"]));
        } else {
            $uceti["fecha_garganta"] = NULL;
        }

        $uceti["sintoma_rinorrea"] = (!isset($data["datos_clinicos"]["rinorrea"]) ? NULL : $data["datos_clinicos"]["rinorrea"]);
        if ($uceti["sintoma_rinorrea"] == '1') {
            $uceti["fecha_rinorrea"] = (!isset($data["datos_clinicos"]["fecha_rinorrea"]) ? NULL : helperString::toDate($data["datos_clinicos"]["fecha_rinorrea"]));
        } else {
            $uceti["fecha_rinorrea"] = NULL;
        }

        $uceti["sintoma_respiratoria"] = (!isset($data["datos_clinicos"]["respiratoria"]) ? NULL : $data["datos_clinicos"]["respiratoria"]);
        if ($uceti["sintoma_respiratoria"] == '1') {
            $uceti["fecha_respiratoria"] = (!isset($data["datos_clinicos"]["fecha_respiratoria"]) ? NULL : helperString::toDate($data["datos_clinicos"]["fecha_respiratoria"]));
        } else {
            $uceti["fecha_respiratoria"] = NULL;
        }

        $uceti["sintoma_otro"] = (!isset($data["datos_clinicos"]["hallazgo_otro"]) ? NULL : $data["datos_clinicos"]["hallazgo_otro"]);
        if ($uceti["sintoma_otro"] == '1') {
            $uceti["fecha_otro"] = (!isset($data["datos_clinicos"]["fecha_hallazgo_otro"]) ? NULL : helperString::toDate($data["datos_clinicos"]["fecha_hallazgo_otro"]));
            $uceti["sintoma_nombre_otro"] = (!isset($data["datos_clinicos"]["hallazgo_otro_nombre"]) ? NULL : $data["datos_clinicos"]["hallazgo_otro_nombre"]);
        } else {
            $uceti["fecha_otro"] = NULL;
            $uceti["sintoma_nombre_otro"] = NULL;
        }

        $uceti["torax_condensacion"] = (!isset($data["datos_clinicos"]["condensacion"]) ? NULL : $data["datos_clinicos"]["condensacion"]);
        $uceti["torax_derrame"] = (!isset($data["datos_clinicos"]["pleural"]) ? NULL : $data["datos_clinicos"]["pleural"]);
        $uceti["torax_broncograma"] = (!isset($data["datos_clinicos"]["broncograma"]) ? NULL : $data["datos_clinicos"]["broncograma"]);
        $uceti["torax_infiltrado"] = (!isset($data["datos_clinicos"]["infiltrado"]) ? NULL : $data["datos_clinicos"]["infiltrado"]);
        $uceti["torax_otro"] = (!isset($data["datos_clinicos"]["resultado_otro"]) ? NULL : $data["datos_clinicos"]["resultado_otro"]);
        $uceti["torax_nombre_otro"] = (!isset($data["datos_clinicos"]["resultado_otro_nombre"]) ? NULL : $data["datos_clinicos"]["resultado_otro_nombre"]);

        $uceti["semana_epi"] = (!isset($data["datos_clinicos"]["semana_epi"]) ? NULL : $data["datos_clinicos"]["semana_epi"]);
        $uceti["anio"] = (!isset($data["datos_clinicos"]["anio"]) ? NULL : $data["datos_clinicos"]["anio"]);
        //[muestras_laboratorio] => Array ( [fecha_hisopado_toma] => [fecha_hisopado_envio] => [fecha_hisopado_lab] => [fecha_aspirado_toma] => [fecha_aspirado_envio] => [fecha_aspirado_lab] => [fecha_sangreuno_toma] => [fecha_sangreuno_envio] => [fecha_sangreuno_lab] => [fecha_sangredos_toma] => [fecha_sangredos_envio] => [fecha_sangredos_lab] => [fecha_liquido_toma] => [fecha_liquido_envio] => [fecha_liquido_lab] => [fecha_tejidouno_toma] => [fecha_tejidouno_envio] => [fecha_tejidouno_lab] => [fecha_tejidodos_toma] => [fecha_tejidodos_envio] => [fecha_tejidodos_lab] => [fecha_muestra_otro_toma] => [fecha_muestra_otro_envio] => [fecha_muestra_otro_lab] => [nombre_toma_muestra] => ) ) Array ( [notificacion] => Array ( [id_un] => 3136 [unidad_disponible] => [tipoPaciente] => 1 [hospitalizado_lugar] => 2 [nombreInvestigador] => Jose [nombreRegistra] => Uceti Uceti ) [individuo] => Array ( [hospitalizado] => 1 [id_individuo] => [asegurado] => 1 [tipoId] => 1 [identificador] => 16016573 [primer_nombre] => JOSE [segundo_nombre] => LUIS [primer_apellido] => BUSTOS [segundo_apellido] => MEJIA [fecha_nacimiento] => 28/08/1985 [tipo_edad] => 3 [edad] => 27 [sexo] => M [nombre_responsable] => JOSE BUSTOS [provincia] => 6 [region] => 6 [distrito] => 35 [corregimiento] => 107 [direccion] => LA DORADA, CALDAS [otra_direccion] => COLOMBIA [telefono] => 3136757664 ) [loaded] => Array ( [tipoId] => 1 [idPro] => 6 [idReg] => 6 [idDis] => 35 [idCor] => 107 ) [antecedentes] => Array ( [tarVacuna] => 1 [vacEsquema] => 1 [fecha_ult_dosis] => 07/11/2012 [embarazo] => -1 [trimestre] => -1 [num_dosis_fluB] => 5 [desconoce_fluB] => 0 [fecha_vac_fluB] => 07/11/2012 [cronica] => 1 [num_dosis_flu] => 5 [desconoce_flu] => 0 [fecha_vac_flu] => 07/11/2012 [enfermedadCronica] => 1 [resultadoCronica] => 1 [num_dosis_neumo7] => 5 [desconoce_neumo7] => 0 [fecha_vac_neumo7] => 07/11/2012 [num_dosis_neumo10] => 5 [desconoce_neumo10] => 0 [fecha_vac_neumo10] => 07/11/2012 [num_dosis_neumo13] => 5 [desconoce_neumo13] => 0 [fecha_vac_neumo13] => 07/11/2012 [num_dosis_neumo23] => 5 [desconoce_neumo23] => 0 [fecha_vac_neumo23] => 07/11/2012 [num_dosis_menin] => 5 [desconoce_menin] => 0 [fecha_vac_menin] => 07/11/2012 [nombre_otra] => Otra [riesgo] => 1 [riesgo_cual] => 1 [viaje] => 1 [contacto] => 1 [contacto_tipo] => 0 [aislamiento] => 1 [nombre_contacto] => Otro ) [datos_clinicos] => Array ( [evento_nombre] => A09.X - Diarrea y gastroenteritis de presunto origen infeccioso [evento_id] => 23485 [check_gripal] => [antibioticos_ultima_semana] => 1 [fecha_inicio_sintomas] => 07/11/2012 [antibioticos_cual] => otro [fecha_hospitalizacion] => 07/11/2012 [antibioticos_fecha] => 08/11/2012 [fecha_notificacion] => 07/11/2012 [antivirales] => 1 [fecha_egreso] => 07/11/2012 [antivirales_cual] => otro [fecha_defuncion] => 07/11/2012 [antivirales_fecha] => 14/11/2012 [fiebre] => 1 [fecha_fiebre] => 07/11/2012 [condensacion] => 1 [tos] => 1 [fecha_tos] => 07/11/2012 [pleural] => 1 [garganta] => 1 [fecha_garganta] => 07/11/2012 [broncograma] => 1 [rinorrea] => 1 [fecha_rinorrea] => 07/11/2012 [infiltrado] => 1 [respiratoria] => 1 [fecha_respiratoria] => 07/11/2012 [resultado_otro] => 1 [hallazgo_otro] => 1 [fecha_hallazgo_otro] => 07/11/2012 [resultado_otro_nombre] => otro [hallazgo_otro_nombre] => otro ) [muestras_laboratorio] => Array ( [fecha_hisopado_toma] => [fecha_hisopado_envio] => [fecha_hisopado_lab] => [fecha_aspirado_toma] => [fecha_aspirado_envio] => [fecha_aspirado_lab] => [fecha_sangreuno_toma] => [fecha_sangreuno_envio] => [fecha_sangreuno_lab] => [fecha_sangredos_toma] => [fecha_sangredos_envio] => [fecha_sangredos_lab] => [fecha_liquido_toma] => [fecha_liquido_envio] => [fecha_liquido_lab] => [fecha_tejidouno_toma] => [fecha_tejidouno_envio] => [fecha_tejidouno_lab] => [fecha_tejidodos_toma] => [fecha_tejidodos_envio] => [fecha_tejidodos_lab] => [fecha_muestra_otro_toma] => [fecha_muestra_otro_envio] => [fecha_muestra_otro_lab] => [nombre_toma_muestra] => ) ) 

        $uceti["nombre_toma_muestra"] = $data["muestras_laboratorio"]["nombre_toma_muestra"];

        $uceti["vigilancia_tipo"] = $data["vigilancia"]["tipo"];
        $uceti["vigilancia_inusual"] = (isset($data["vigilancia"]["inusual"]) ? '1' : '0');

        return $uceti;
    }

    // Obtiene el listado de uceti
    public static function buscarUceti($config) {
        $conn = new Connection();
        $conn->initConn();
        $flag = 0;
        $filtro1 = "";
        $filtro2 = "";
        $read = false;
        foreach ($config as $valor) {
            if ($valor != "")
                $flag++;
        }

        $filtroHospital = "";

        switch (ConfigurationHospitalInfluenza::HospitalActual) {
            case ConfigurationHospitalInfluenza::HospitalChicho:
                $filtroHospital = " and uceti.id_flureg BETWEEN '1000000' AND '1999999' ";
                break;
            case ConfigurationHospitalInfluenza::HospitalJoseDomingo:
                $filtroHospital = " and uceti.id_flureg BETWEEN '1' AND '999999' ";
                break;
            case ConfigurationHospitalInfluenza::HospitalNino:
                $filtroHospital = " and uceti.id_flureg BETWEEN '2000000' AND '2999999' ";
                break;
            case ConfigurationHospitalInfluenza::HospitalDefault:
                if ($config["hospital"] == ConfigurationHospitalInfluenza::HospitalChicho)
                    $filtroHospital = " and uceti.id_flureg BETWEEN '1000000' AND '1999999' ";
                else if ($config["hospital"] == ConfigurationHospitalInfluenza::HospitalJoseDomingo)
                    $filtroHospital = " and uceti.id_flureg BETWEEN '1' AND '999999' ";
                else if ($config["hospital"] == ConfigurationHospitalInfluenza::HospitalNino)
                    $filtroHospital = " and uceti.id_flureg BETWEEN '2000000' AND '2999999' ";
                else if ($config["hospital"] == ConfigurationHospitalInfluenza::HospitalNivelNacional)
                    $filtroHospital = " and uceti.id_flureg >= '3000000' ";
                break;
        }
        
        if (isset($config["id_un"]))
            $filtroHospital .= " and uceti.id_un = ".$config["id_un"];

        if ($flag == 0)
            $sql = "select * from flureg_form uceti
                    inner join tbl_persona per on per.tipo_identificacion = uceti.tipo_identificacion and per.numero_identificacion = uceti.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = uceti.id_un
                    left join cat_region_salud re on re.id_region = per.id_region
                    left join cat_evento ev on ev.id_evento = uceti.id_evento
                    ";
        else {
            //echo "filtro ".$config["silab"];
            if ($config["filtro"] != "") {
                $filtro1 = " AND (un.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                        " OR re.nombre_region LIKE '%" . $config["filtro"] . "%'" .
                        " OR ev.cie_10_1 LIKE '%" . $config["filtro"] . "%'" .
                        " OR ev.nombre_evento LIKE '%" . $config["filtro"] . "%'" .
                        " OR uceti.semana_epi LIKE '%" . $config["filtro"] . "%'" .
                        " OR uceti.anio LIKE '%" . $config["filtro"] . "%'" .
                        " OR uceti.numero_identificacion LIKE '%" . $config["filtro"] . "%')";
                if ($config["silab"] != "-1")
                    $filtro1 .= " AND uceti.pendiente_silab ='" . $config["silab"] . "'";
                if ($config["uceti"] != "-1")
                    $filtro1 .= " AND uceti.pendiente_uceti ='" . $config["uceti"] . "'";
            } else if (isset($config["tipo_identificacion"]) && isset($config["numero_identificacion"])) {
                $filtro2 = " AND uceti.tipo_identificacion='" . $config["tipo_identificacion"] . "'" .
                        " AND uceti.numero_identificacion='" . $config["numero_identificacion"] . "'";
                $read = true;
            } else {
                if ($config["silab"] != "-1")
                    $filtro1 .= " AND uceti.pendiente_silab ='" . $config["silab"] . "'";
                if ($config["uceti"] != "-1")
                    $filtro1 .= " AND uceti.pendiente_uceti ='" . $config["uceti"] . "'";
            }

            $sql = "select * from flureg_form uceti
                    inner join tbl_persona per on per.tipo_identificacion = uceti.tipo_identificacion and per.numero_identificacion = uceti.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = uceti.id_un
                    left join cat_region_salud re on re.id_region = per.id_region
                    left join cat_evento ev on ev.id_evento = uceti.id_evento WHERE 1 "
                    . $filtroHospital . $filtro1 . $filtro2 . ' order by id_flureg desc';
            //. " limit " . $config["inicio"] . "," . $config["paginado"];
            if (!$read) {
                $sql.= " limit " . $config["inicio"] . "," . $config["paginado"];
            }
        }

//        echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function buscarUcetiCantidad($config) {
        $conn = new Connection();
        $conn->initConn();
        $filtro1 = "";

        $filtroHospital = "";

        switch (ConfigurationHospitalInfluenza::HospitalActual) {
            case ConfigurationHospitalInfluenza::HospitalChicho:
                $filtroHospital = " and uceti.id_flureg BETWEEN '1000000' AND '1999999' ";
                break;
            case ConfigurationHospitalInfluenza::HospitalJoseDomingo:
                $filtroHospital = " and uceti.id_flureg BETWEEN '1' AND '999999' ";
                break;
            case ConfigurationHospitalInfluenza::HospitalNino:
                $filtroHospital = " and uceti.id_flureg BETWEEN '2000000' AND '2999999' ";
                break;
            case ConfigurationHospitalInfluenza::HospitalDefault:
                if ($config["hospital"] == ConfigurationHospitalInfluenza::HospitalChicho)
                    $filtroHospital = " and uceti.id_flureg BETWEEN '1000000' AND '1999999' ";
                else if ($config["hospital"] == ConfigurationHospitalInfluenza::HospitalJoseDomingo)
                    $filtroHospital = " and uceti.id_flureg BETWEEN '1' AND '999999' ";
                else if ($config["hospital"] == ConfigurationHospitalInfluenza::HospitalNino)
                    $filtroHospital = " and uceti.id_flureg BETWEEN '2000000' AND '2999999' ";
                else if ($config["hospital"] == ConfigurationHospitalInfluenza::HospitalNivelNacional)
                    $filtroHospital = " and uceti.id_flureg >= '3000000' ";
                break;
        }

        if ($config["filtro"] != "") {
            $filtro1 = " AND (un.nombre_un LIKE '%" . $config["filtro"] . "%'" .
                    " OR re.nombre_region LIKE '%" . $config["filtro"] . "%'" .
                    " OR ev.cie_10_1 LIKE '%" . $config["filtro"] . "%'" .
                    " OR ev.nombre_evento LIKE '%" . $config["filtro"] . "%'" .
                    " OR uceti.semana_epi LIKE '%" . $config["filtro"] . "%'" .
                    " OR uceti.anio LIKE '%" . $config["filtro"] . "%'" .
                    " OR uceti.numero_identificacion LIKE '%" . $config["filtro"] . "%')";
        }
        if ($config["silab"] != "-1")
            $filtro1 .= " AND uceti.pendiente_silab ='" . $config["silab"] . "'";
        if ($config["uceti"] != "-1")
            $filtro1 .= " AND uceti.pendiente_uceti ='" . $config["uceti"] . "'";
        $sql = "select count(*) as total from flureg_form uceti
                    inner join tbl_persona per on per.tipo_identificacion = uceti.tipo_identificacion and per.numero_identificacion = uceti.numero_identificacion
                    left join cat_unidad_notificadora un on un.id_un = uceti.id_un
                    left join cat_region_salud re on re.id_region = per.id_region
                    left join cat_evento ev on ev.id_evento = uceti.id_evento WHERE 1 "
                . $filtroHospital . $filtro1;
//        echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }

    public static function buscarUcetiEnfermedad($tipoIdentificacion, $numeroIdentificacion) {
        if (isset($tipoIdentificacion) && isset($numeroIdentificacion)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = "";
            $filtro = " AND tipo_identificacion='" . $tipoIdentificacion . "'";
            $filtro .= " AND numero_identificacion='" . $numeroIdentificacion . "'";
            $sql = "select * from flureg_enfermedad_cronica uceti 
                    left join cat_enfermedad_cronica ce on ce.id_cat_enfermedad_cronica = uceti.id_cat_enfermedad_cronica
                    WHERE status = 1 and influenza = 1 "
                    . $filtro;

            //echo $sql;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;
    }

    public static function buscarUcetiVacunas($tipoIdentificacion, $numeroIdentificacion) {
        if (isset($tipoIdentificacion) && isset($numeroIdentificacion)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = "";
            $filtro = " AND tipo_identificacion='" . $tipoIdentificacion . "'";
            $filtro .= " AND numero_identificacion='" . $numeroIdentificacion . "'";
            $sql = "select * from flureg_antecendente_vacunal uceti 
                    left join cat_antecendente_vacunal ce on ce.id_cat_antecendente_vacunal = uceti.id_cat_antecendente_vacunal
                    WHERE status = 1 and influenza = 1 "
                    . $filtro;

            //echo $sql;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;
    }

    public static function buscarUcetiTipoMuestras($idUceti) {
        if (isset($idUceti)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = " AND id_flureg=" . $idUceti;
            $sql = "select * from flureg_muestra_laboratorio uceti 
                    left join cat_muestra_laboratorio ce on ce.id_cat_muestra_laboratorio = uceti.id_cat_muestra_laboratorio
                    WHERE status = 1 and influenza = 1 "
                    . $filtro;

            //echo $sql;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;
    }

    public static function buscarUcetiMuestrasSilab($formUceti) {
        if (isset($formUceti)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = " AND id_flureg=" . $formUceti . "";
            $sql = "select * from flureg_muestra_silab 
                    WHERE 1 " . $filtro;
            //echo $sql;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;
    }

    public static function buscarUcetiPruebasSilab($idMuestra) {
        if (isset($idMuestra)) {
            $conn = new Connection();
            $conn->initConn();
            $filtro = " and id_muestra = " . $idMuestra;
            $sql = "select * from flureg_muestra_prueba_silab 
                    WHERE 1 " . $filtro;
            //echo $sql;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetch();
            $conn->closeConn();
            return $data;
        }
        return NULL;
    }
    
    public static function IncrementarMuestraId() {
            $conn = new Connection();
            $conn->initConn();
            $sql = "select no_muestra from flureg_muestra_count " ;
//            echo $sql;
            $conn->prepare($sql);
            $conn->execute();
            $data = $conn->fetchOne();
            $conn->closeConn();
            $conn = new Connection();
            $conn->initConn();
            $sql = "UPDATE flureg_muestra_count 
                    SET no_muestra = no_muestra+1; " ;
            $conn->prepare($sql);
            $conn->execute();
//            echo "<br/> $sql";
            $conn->closeConn();
            
            
            return $data;
    }

}