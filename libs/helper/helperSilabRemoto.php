<?php

require_once('libs/silab/ConfigurationSilabRemoto.php');
require_once('libs/silab/ConnectionSilabRemoto.php');
require_once ('libs/caus/clsCaus.php');
require_once ('libs/helper/helperString.php');

class helperSilabRemoto {

    public static function getPadre($idMuestra) {
        $sql = "select MUE_PADRE from muestra where MUE_ID=" . $idMuestra;
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }

    public static function getHermanas($idMuestra, $idPadre) {
        $sql = "select MUE_ID from muestra where MUE_PADRE=" . $idPadre . ' AND MUE_ID!=' . $idMuestra;
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function informeExistente($id) {
        $sql = "select count(*) as cantidad from informe_envio where inf_env_id=" . $id;
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    // Obtiene el codigo global
    public static function getCodigoGlobal() {
        $sql = "select cod_glo_anio, cod_glo_correlativo from codigo_global where cod_glo_activo=1";

        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    // Obtiene las fechas de toma y de inicio
    public static function getFechasMuestra($idMuestra) {
        $sql = "select mue_fecha_inicio, mue_fecha_toma from muestra where mue_id=" . $idMuestra;

        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    // Obtiene el codigo del evento
    public static function getCodigoEvento($id) {
        $sql = "select eve_codigo, eve_correlativo from evento where eve_id=" . $id;

        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function getIDEventoTipoMuestra($idEvento, $idTipo) {
        $sql = "select tip_mue_eve_id from tipo_muestra_evento where eve_id =" . $idEvento . " AND tipo_mue_id = " . $idTipo;
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function increaseCodigoGlobal() {
        $sql = "UPDATE codigo_global SET cod_glo_correlativo = cod_glo_correlativo + 1,  cod_glo_historico = cod_glo_historico + 1 where cod_glo_activo = 1";
        return $sql;
    }

    public static function decreaseCodigoGlobal() {
        $sql = "UPDATE codigo_global SET cod_glo_correlativo = cod_glo_correlativo - 1, cod_glo_historico = cod_glo_historico - 1 where cod_glo_activo = 1";
        return $sql;
    }

    public static function increaseCodigoMuestra($id) {
        $sql = "UPDATE evento SET eve_correlativo = eve_correlativo + 1, eve_historico = eve_historico + 1 where eve_id = " . $id;
        return $sql;
    }

    public static function decreaseCodigoMuestra($id) {
        $sql = "UPDATE evento SET eve_correlativo = eve_correlativo - 1, eve_historico = eve_historico - 1 where eve_id = " . $id;
        return $sql;
    }

    public static function getHijas($idMuestra) {
        $sql = "SELECT mue_id
                      FROM
                       muestra
                       where mue_padre = " . $idMuestra;

        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getNoDiagnostico($evento) {
        $sql = "SELECT
              tipo_subtipo.TIP_SUB_ID
            FROM
              resultado_final_evento
              INNER JOIN resultado_final ON (resultado_final_evento.RES_FIN_ID = resultado_final.RES_FIN_ID)
              INNER JOIN resultado_final_tipo ON (resultado_final_evento.RES_FIN_EVE_ID = resultado_final_tipo.RES_FIN_EVE_ID)
              INNER JOIN tipo_subtipo ON (resultado_final_tipo.RES_FIN_TIP_ID = tipo_subtipo.RES_FIN_TIP_ID)
              where resultado_final_evento.EVE_ID = " . $evento . " AND resultado_final.RES_FIN_ID=0 AND resultado_final_tipo.TIP_ID=0
              AND tipo_subtipo.SUB_ID=1";
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }

    public static function getNopruebaNoresultado($tipo_muestra) {
        $sql = "SELECT
                      PRU_RES_ID
                    FROM
                      pruebas_completas
                      where TIP_MUE_EVE_ID=" . $tipo_muestra[0] . " AND   PRU_ID = 0 AND
                      RES_ID=0";

        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }

    public static function conclusionAsignada($id) {
        $sql = "SELECT count(*)
                FROM
                  conclusion_muestra
                  INNER JOIN tipo_subtipo tipo_subtipo1 ON (conclusion_muestra.TIP_SUB_ID1 = tipo_subtipo1.TIP_SUB_ID)
                  INNER JOIN tipo_subtipo ON (conclusion_muestra.TIP_SUB_ID2 = tipo_subtipo.TIP_SUB_ID)
                  INNER JOIN resultado_final_tipo ON (tipo_subtipo.RES_FIN_TIP_ID = resultado_final_tipo.RES_FIN_TIP_ID)
                  INNER JOIN resultado_final_tipo resultado_final_tipo1 ON (tipo_subtipo1.RES_FIN_TIP_ID = resultado_final_tipo1.RES_FIN_TIP_ID)
                  INNER JOIN resultado_final_evento ON (resultado_final_tipo.RES_FIN_EVE_ID = resultado_final_evento.RES_FIN_EVE_ID)
                  INNER JOIN resultado_final_evento resultado_final_evento1 ON (resultado_final_tipo1.RES_FIN_EVE_ID = resultado_final_evento1.RES_FIN_EVE_ID)
                WHERE
                  tipo_subtipo.SUB_ID != 0 AND
                  tipo_subtipo1.SUB_ID != 0 AND
                  resultado_final_tipo.TIP_ID != 0 AND
                  resultado_final_tipo1.TIP_ID != 0 AND
                  resultado_final_evento.RES_FIN_ID != 0 AND
                  resultado_final_evento1.RES_FIN_ID != 0 AND
                  conclusion_muestra.MUE_ID = " . $id;
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function pruebasAsignadas($id) {
        // Determina si la muestra tiene pruebas asignadas
        $sql = "SELECT
                  count(*)
                FROM
                  analisis_muestra
                  INNER JOIN prueba_resultado ON (analisis_muestra.PRU_RES_ID = prueba_resultado.PRU_RES_ID)
                  INNER JOIN resultado ON (prueba_resultado.RES_ID = resultado.RES_ID)
                  INNER JOIN prueba_evento ON (prueba_resultado.PRU_EVE_ID = prueba_evento.PRU_EVE_ID)
                  INNER JOIN prueba ON (prueba_evento.PRU_ID = prueba.PRU_ID)
                  where mue_id=" . $id . " and prueba.PRU_ID!=0 and resultado.RES_ID!=0";

        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function estadoMuestra($id) {
        $sql = "select SIT_ID from muestra where mue_id = " . $id;
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function derivacionesAsignadas($id) {
        $sql = "select count(*) from muestra where mue_padre = " . $id . ' AND (mue_tipo=2 OR mue_tipo=3)';
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function alicuotasAsignadas($id) {
        $sql = "select count(*) from muestra where mue_padre = " . $id . ' AND mue_tipo=2';
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function situacionMuestra($id) {

        $conn = new ConnectionSilabRemoto();
        $sql = "select SIT_ID from muestra where mue_id = " . $id;
        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function globalAnterior($idMuestra) {
        $sql = "select mue_codigo_global_anio, mue_codigo_global_numero from muestra where mue_id = " . $idMuestra;
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function correlativoAnterior($idMuestra) {
        $sql = "select mue_codigo_correlativo_numero, mue_codigo_correlativo_alfa from muestra where mue_id = " . $idMuestra;
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function eventoAnterior($idMuestra) {
        $sql = "SELECT tipo_muestra_evento.EVE_ID FROM muestra
                    INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
                    WHERE muestra.MUE_ID = " . $idMuestra;
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getMuestra($idMuestra) {
        $sql = "SELECT
                  muestra.MUE_ARE_ANA_ID as ARE_ANA_ID,
                  muestra.IND_DIRECCION,
                  muestra.IND_DIRECCION_DISPONIBLE,
                  muestra.IND_LOCALIDAD_DISPONIBLE,
                  muestra.IND_EDAD,
                  muestra.IND_ES_HUMANO,
                  muestra.IND_SEROTECA,
                  muestra.IND_CONTROL_CALIDAD,
                  muestra.IND_CEPARIUM,                  
                  muestra.IND_PROYECTO,  
                  muestra.IND_FECHA_NACIMIENTO,
                  muestra.IND_HISTORIA_CLINICA,
                  muestra.IND_ID,
                  muestra.IND_IDENTIFICADOR,
                  muestra.IND_IDENTIFICADOR_TIPO,
                  muestra.IND_IDENTIFICADOR_DISPONIBLE,
                  muestra.IND_SEGURO_SOCIAL,
                  muestra.IND_TELEFONO,
                  muestra.IND_NO_DONANTE,
                  muestra.IND_ESTADO_FEBRIL,
                  muestra.IND_PRIMER_NOMBRE,
                  muestra.IND_SEGUNDO_NOMBRE,
                  muestra.IND_PRIMER_APELLIDO,
                  muestra.IND_SEGUNDO_APELLIDO,
                  muestra.IND_SEXO,
                  muestra.IND_TIPO_EDAD,
                  muestra.TIP_VIG_ID,
                  muestra.IND_VIAJO_PAIS,
                  muestra.IND_VIAJO_PROVINCIA,
                  muestra.IND_VIAJO_REGION,
                  muestra.IND_VIAJO_PROVINCIA,
                  muestra.IND_VIAJO_DISTRITO,
                  muestra.IND_VIAJO_CORREGIMIENTO,                  
                  muestra.IND_PROC_PROVINCIA,
                  muestra.IND_PROC_REGION,
                  muestra.IND_PROC_PROVINCIA,
                  muestra.IND_PROC_DISTRITO,
                  muestra.IND_PROC_CORREGIMIENTO,
                  muestra.MUE_PROC_PROVINCIA,
                  muestra.MUE_PROC_REGION,
                  muestra.MUE_PROC_PROVINCIA,
                  muestra.MUE_PROC_DISTRITO,
                  muestra.MUE_PROC_CORREGIMIENTO,
                  muestra.MUE_PROC_INST_SALUD,                  
                  muestra.IND_CASADA,
                  muestra.IND_ID AS id_individuo,
                  muestra.MUE_ANTECEDENTES_INTERES,
                  muestra.MUE_CARGA_VIRAL,
                  muestra.MUE_CODIGO_CORRELATIVO_ALFA AS evento_codigo,
                  muestra.MUE_CODIGO_CORRELATIVO_NUMERO AS evento_correlativo,
                  muestra.MUE_CODIGO_GLOBAL_ANIO AS codigo_global_anio,
                  muestra.MUE_CODIGO_GLOBAL_NUMERO AS codigo_global,
                  muestra.MUE_COMENTARIOS AS comentarios_adicionales,
                  muestra.MUE_DONADOR AS chk_donador,
                  muestra.MUE_SINTOMATICO AS chk_sintomatico,
                  muestra.MUE_DIAGNOSTICO AS chk_diagnostico,
                  muestra.MUE_TRATADO AS chk_tratado,
                  muestra.MUE_VACUNADO AS chk_vacunado,
                  muestra.MUE_DIAGNOSTICO_NO AS diagnostico_no,
                  muestra.MUE_CONTROL_NO AS control_no,
                  muestra.MUE_FECHA_INICIO AS fecha_inicio_sintomas,
                  muestra.MUE_FECHA_RECEPCION AS fecha_recepcion,
                  muestra.MUE_FECHA_TOMA AS fecha_toma,
                  muestra.MUE_OTRO_ESTABLECIMIENTO_NOMBRE as otro_establecimiento,
                  muestra.MUE_RECHAZADA AS rechazada,
                  muestra.MUE_REFERIDA_POR AS referida_por,
                  muestra.MUE_RESULTADO_EXTERNO AS resultados_externos,
                  muestra.MUE_SEMANA_EPI AS semana,
                  muestra.MUE_DIAS_EVO AS dias_evo,
                  muestra.MUE_SEROLOGICA AS encuesta_serologica_check,
                  muestra.IND_SEGUNDO_APELLIDO AS primer_apellido,
                  muestra.MUE_SERVICIO_HOSPITALAR AS servicio,
                  muestra.MUE_DESCRIPCION_SERVICIO_DISPONIBLE AS no_establecimiento,
                  muestra.MUE_SITUACION_HOSPITALAR AS hospitalario,
                  muestra.RAZ_REC_ID AS razon_rechazo,
                  muestra.MUE_SOSPECHOSO AS sospechoso,
                  muestra.TIP_MUE_EVE_ID,
                  tipo_muestra_evento.TIPO_MUE_ID,
                  tipo_muestra_evento.EVE_ID as ID_EVENTO,
                  evento.EVE_PACIENTE_DONADOR,
                  evento.EVE_PACIENTE_SINTOMATICO,
                  evento.EVE_DIAGNOSTICO,
                  evento.EVE_TRATADO,
                  evento.EVE_VACUNADO,
                  evento.EVE_SOSPECHOSO,
                  evento.EVE_CARGA_VIRAL,
                  evento.EVE_SEROLOGICA,
                  evento.EVE_INICIO_SINTOMAS,
                  muestra.MUE_TIPO_FLU as flu,
                  u.nombre_un as unidad_desc,
                  tm.TIP_MUE_NOMBRE as nombre_tipo_muestra,
                  sit.SIT_NOMBRE,
                  sit.SIT_ID
                FROM
                  muestra
                  LEFT JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
                  LEFT JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID)
                  LEFT JOIN cat_unidad_notificadora u on muestra.MUE_PROC_INST_SALUD = u.id_un
                  Left join tipo_muestra tm ON tipo_muestra_evento.TIPO_MUE_ID = tm.TIP_MUE_ID
                  Left join situacion sit ON muestra.SIT_ID = sit.SIT_ID
                WHERE
                  muestra.MUE_ID = " . $idMuestra;

        //echo $sql;

        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        $datos = array();
        if (count($data)) {
            // INDIVIDUO
            $datos["individuo"]["id_individuo"] = $data["IND_ID"];
            $datos["individuo"]["tipo_vigilancia"] = $data["TIP_VIG_ID"];
            $datos["individuo"]["no_muestra_humana"] = ($data["IND_ES_HUMANO"] != '2' ? '' : 'on');
            $datos["individuo"]["seroteca"] = ($data["IND_SEROTECA"] != '2' ? '' : 'on');
            $datos["individuo"]["control_calidad"] = ($data["IND_CONTROL_CALIDAD"] != '2' ? '' : 'on');
            $datos["individuo"]["ceparium"] = ($data["IND_CEPARIUM"] != '2' ? '' : 'on');
            $datos["individuo"]["proyecto"] = ($data["IND_PROYECTO"] != '2' ? '' : 'on');
            $datos["individuo"]["identificador"] = $data["IND_IDENTIFICADOR"];
            $datos["individuo"]["identificador_tipo"] = $data["IND_IDENTIFICADOR_TIPO"];
            $datos["individuo"]["identificador_no_disponible"] = ($data["IND_IDENTIFICADOR_DISPONIBLE"] == '1' ? '' : 'on');
            $datos["individuo"]["seguro_social"] = $data["IND_SEGURO_SOCIAL"];
            $datos["individuo"]["telefono"] = $data["IND_TELEFONO"];
            $datos["individuo"]["no_donante"] = $data["IND_NO_DONANTE"];
            $datos["individuo"]["estado_febril"] = $data["IND_ESTADO_FEBRIL"];
            $datos["individuo"]["historia_clinica"] = $data["IND_HISTORIA_CLINICA"];
            $datos["individuo"]["primer_nombre"] = $data["IND_PRIMER_NOMBRE"];
            $datos["individuo"]["segundo_nombre"] = $data["IND_SEGUNDO_NOMBRE"];
            $datos["individuo"]["primer_apellido"] = $data["IND_PRIMER_APELLIDO"];
            $datos["individuo"]["segundo_apellido"] = $data["IND_SEGUNDO_APELLIDO"];
            $datos["individuo"]["edad"] = $data["IND_EDAD"];
            $datos["individuo"]["tipo_edad"] = $data["IND_TIPO_EDAD"];
            $datos["individuo"]["fecha_nacimiento"] = helperString::toDateView($data["IND_FECHA_NACIMIENTO"]);
            $datos["individuo"]["sexo"] = $data["IND_SEXO"];

            $datos["individuo"]["viajo_pais"] = $data["IND_VIAJO_PAIS"];
            $datos["individuo"]["viajo_provincia"] = $data["IND_VIAJO_PROVINCIA"];
            $datos["individuo"]["viajo_region"] = $data["IND_VIAJO_REGION"];
            $datos["individuo"]["viajo_distrito"] = $data["IND_VIAJO_DISTRITO"];
            $datos["individuo"]["viajo_corregimiento"] = $data["IND_VIAJO_CORREGIMIENTO"];

            $datos["individuo"]["provincia"] = $data["IND_PROC_PROVINCIA"];
            $datos["individuo"]["region"] = $data["IND_PROC_REGION"];
            $datos["individuo"]["distrito"] = $data["IND_PROC_DISTRITO"];
            $datos["individuo"]["corregimiento"] = $data["IND_PROC_CORREGIMIENTO"];

            $datos["individuo"]["direccion"] = $data["IND_DIRECCION"];
            $datos["individuo"]["no_direccion_individuo"] = ($data["IND_DIRECCION_DISPONIBLE"] == '0' ? 'on' : '');
            $datos["individuo"]["no_localidad"] = ($data["IND_LOCALIDAD_DISPONIBLE"] == '0' ? 'on' : '');
            $datos["individuo"]["casada"] = $data["IND_CASADA"];

            // MUESTRA
            $datos["muestra"]["provincia"] = $data["MUE_PROC_PROVINCIA"];
            $datos["muestra"]["region"] = $data["MUE_PROC_REGION"];
            $datos["muestra"]["distrito"] = $data["MUE_PROC_DISTRITO"];
            $datos["muestra"]["corregimiento"] = $data["MUE_PROC_CORREGIMIENTO"];
            $datos["muestra"]["unidad"] = $data["MUE_PROC_INST_SALUD"];
            $datos["muestra"]["unidad_desc"] = $data["unidad_desc"];

            $datos["muestra"]["otro_establecimiento"] = $data["otro_establecimiento"];
            $datos["muestra"]["hospitalario"] = $data["hospitalario"];

            $datos["muestra"]["servicio"] = $data["servicio"];
            $datos["muestra"]["referida_por"] = $data["referida_por"];
            $datos["muestra"]["area_analisis"] = $data["ARE_ANA_ID"];

            $datos["muestra"]["razon_rechazo"] = $data["razon_rechazo"];
            $datos["muestra"]["sospechoso"] = $data["sospechoso"];
            $datos["muestra"]["no_rechazada"] = ($data["rechazada"] == 1 ? '' : 'on');
            $datos["muestra"]["si_rechazada"] = ($data["rechazada"] == 1 ? 'on' : '');

            // Propiedades del evento
            $datos["muestra"]["evento"]["id_evento"] = $data["ID_EVENTO"];
            $datos["muestra"]["evento"]["donador"] = $data["EVE_PACIENTE_DONADOR"];
            $datos["muestra"]["evento"]["sintomatico"] = $data["EVE_PACIENTE_SINTOMATICO"];
            $datos["muestra"]["evento"]["diagnostico"] = $data["EVE_DIAGNOSTICO"];
            $datos["muestra"]["evento"]["tratado"] = $data["EVE_TRATADO"];
            $datos["muestra"]["evento"]["vacunado"] = $data["EVE_VACUNADO"];
            $datos["muestra"]["evento"]["sospechoso"] = $data["EVE_SOSPECHOSO"];
            $datos["muestra"]["evento"]["carga_viral"] = $data["EVE_CARGA_VIRAL"];
            $datos["muestra"]["evento"]["encuesta_serologica"] = $data["EVE_SEROLOGICA"];
            $datos["muestra"]["evento"]["inicio_sintomas"] = $data["EVE_INICIO_SINTOMAS"];


            $datos["muestra"]["carga_viral_check"] = ($data["MUE_CARGA_VIRAL"] == '1' ? 'on' : '');
            $datos["muestra"]["encuesta_serologica_check"] = $data["encuesta_serologica_check"];

            if ($data["chk_donador"] == '1') {
                $datos["muestra"]["chk_donador"] = 'on';
                $datos["muestra"]["chk_paciente"] = '';
            } else if ($data["chk_donador"] == '2') {
                $datos["muestra"]["chk_donador"] = '';
                $datos["muestra"]["chk_paciente"] = 'on';
            }
            else
                $datos["muestra"]["chk_donador"] = 'on';

            if ($data["chk_sintomatico"] == '1') {
                $datos["muestra"]["chk_sintomatico"] = 'on';
                $datos["muestra"]["chk_asintomatico"] = '';
            } else if ($data["chk_sintomatico"] == '2') {
                $datos["muestra"]["chk_sintomatico"] = '';
                $datos["muestra"]["chk_asintomatico"] = 'on';
            }
            else
                $datos["muestra"]["chk_sintomatico"] = 'on';

            if ($data["chk_diagnostico"] == '1') {
                $datos["muestra"]["chk_diagnostico"] = 'on';
                $datos["muestra"]["chk_control"] = '';
            } else if ($data["chk_diagnostico"] == '2') {
                $datos["muestra"]["chk_diagnostico"] = '';
                $datos["muestra"]["chk_control"] = 'on';
            }
            else
                $datos["muestra"]["chk_diagnostico"] = 'on';
            $datos["muestra"]["diagnostico_no"] = $data["diagnostico_no"];
            $datos["muestra"]["control_no"] = $data["control_no"];

            if ($data["chk_tratado"] == '1') {
                $datos["muestra"]["chk_tratado"] = 'on';
                $datos["muestra"]["chk_virgen"] = '';
                $datos["muestra"]["chk_virgen_nosabe"] = '';
            } else if ($data["chk_tratado"] == '2') {
                $datos["muestra"]["chk_tratado"] = '';
                $datos["muestra"]["chk_virgen"] = 'on';
                $datos["muestra"]["chk_virgen_nosabe"] = '';
            } else if ($data["chk_tratado"] == '4') {
                $datos["muestra"]["chk_tratado"] = '';
                $datos["muestra"]["chk_virgen"] = '';
                $datos["muestra"]["chk_virgen_nosabe"] = 'on';
            }
            else
                $datos["muestra"]["chk_tratado"] = 'on';

            if ($data["chk_vacunado"] == '1') {
                $datos["muestra"]["chk_vacunado_si"] = 'on';
                $datos["muestra"]["chk_vacunado_no"] = '';
                $datos["muestra"]["chk_vacunado_no_dato"] = '';
            } else if ($data["chk_vacunado"] == '2') {
                $datos["muestra"]["chk_vacunado_si"] = '';
                $datos["muestra"]["chk_vacunado_no"] = 'on';
                $datos["muestra"]["chk_vacunado_no_dato"] = '';
            } else if ($data["chk_vacunado"] == '3') {
                $datos["muestra"]["chk_vacunado_si"] = '';
                $datos["muestra"]["chk_vacunado_no"] = '';
                $datos["muestra"]["chk_vacunado_no_dato"] = 'on';
            } else
                $datos["muestra"]["chk_vacunado_si"] = 'on';


            $datos["muestra"]["tipo"] = $data["TIPO_MUE_ID"];

            $datos["muestra"]["antecedentes"] = $data["MUE_ANTECEDENTES_INTERES"];
            $datos["muestra"]["comentarios_adicionales"] = $data["comentarios_adicionales"];
            $datos["muestra"]["resultados_externos"] = $data["resultados_externos"];
            $datos["muestra"]["semana"] = $data["semana"];
            $datos["muestra"]["dias_evo"] = $data["dias_evo"];


            $datos["muestra"]["fecha_inicio_sintomas"] = helperString::toDateView($data["fecha_inicio_sintomas"]);
            $datos["muestra"]["fecha_toma"] = helperString::toDateView($data["fecha_toma"]);
            $datos["muestra"]["fecha_recepcion"] = helperString::toDateView($data["fecha_recepcion"]);
            $datos["muestra"]["codigo_global_anio"] = $data["codigo_global_anio"];
            $datos["muestra"]["codigo_global"] = helperString::completeZeros($data["codigo_global"]);
            $datos["muestra"]["evento_codigo"] = $data["evento_codigo"];
            $datos["muestra"]["evento_correlativo"] = helperString::completeZeros($data["evento_correlativo"]);
            $datos["muestra"]["flu"] = $data["flu"];

            $datos["muestra"]["factores"] = self::getFactoresRiesgo($idMuestra);
            $datos["muestra"]["motivos"] = self::getMotivosSolicitud($idMuestra);

            $datos["muestra"]["nombre_tipo_muestra"] = $data["nombre_tipo_muestra"];
            $datos["muestra"]["nombre_situacion"] = $data["SIT_NOMBRE"];
            $datos["muestra"]["id_situacion"] = $data["SIT_ID"];
        }
        return $datos;
    }

    public static function getFactoresRiesgo($idMuestra) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = 'select idfactor_riesgo
                   from factor_riesgo_muestra where MUE_ID =' . $idMuestra;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data == null ? array() : $data;
    }

    public static function getMotivosSolicitud($idMuestra) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = 'select idmotivos_de_solicitud
                   from motivos_de_solicitud_muestra where MUE_ID =' . $idMuestra;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data == null ? array() : $data;
    }

    public static function dataIndividuo($data) {
        // DATOS DEL INDIVIDUO
        $individuo = array();
        $individuo["IND_ES_HUMANO"] = ($data["individuo"]["no_muestra_humana"] == 'on' ? '2' : '1');
        $individuo["IND_SEROTECA"] = ($data["individuo"]["seroteca"] == 'on' ? '2' : '1');
        $individuo["IND_PROYECTO"] = ($data["individuo"]["proyecto"] == 'on' ? '2' : '1');
        $individuo["IND_CONTROL_CALIDAD"] = ($data["individuo"]["control_calidad"] == 'on' ? '2' : '1');
        $individuo["IND_CEPARIUM"] = ($data["individuo"]["ceparium"] == 'on' ? '2' : '1');
        $individuo["IND_PROYECTO"] = ($data["individuo"]["proyecto"] == 'on' ? '2' : '1');
        $individuo["IND_PRIMER_NOMBRE"] = (!isset($data["individuo"]["primer_nombre"]) ? NULL : strtoupper($data["individuo"]["primer_nombre"]));
        $individuo["IND_SEGUNDO_NOMBRE"] = (!isset($data["individuo"]["segundo_nombre"]) ? NULL : strtoupper($data["individuo"]["segundo_nombre"]));
        $individuo["IND_PRIMER_APELLIDO"] = (!isset($data["individuo"]["primer_apellido"]) ? NULL : strtoupper($data["individuo"]["primer_apellido"]));
        $individuo["IND_SEGUNDO_APELLIDO"] = (!isset($data["individuo"]["segundo_apellido"]) ? NULL : strtoupper($data["individuo"]["segundo_apellido"]));
        $individuo["IND_IDENTIFICADOR"] = (!isset($data["individuo"]["identificador"]) ? NULL : $data["individuo"]["identificador"]);
        $individuo["IND_IDENTIFICADOR_TIPO"] = (!isset($data["individuo"]["identificador_tipo"]) ? NULL : $data["individuo"]["identificador_tipo"]);
        $individuo["IND_IDENTIFICADOR_DISPONIBLE"] = ($data["individuo"]["identificador_no_disponible"] == 'on' ? '0' : '1');
        $individuo["IND_SEGURO_SOCIAL"] = (!isset($data["individuo"]["seguro_social"]) ? NULL : $data["individuo"]["seguro_social"]);
        $individuo["IND_TELEFONO"] = (!isset($data["individuo"]["telefono"]) ? NULL : $data["individuo"]["telefono"]);
        $individuo["IND_NO_DONANTE"] = (!isset($data["individuo"]["no_donante"]) ? NULL : $data["individuo"]["no_donante"]);
        $individuo["IND_ESTADO_FEBRIL"] = (!isset($data["individuo"]["estado_febril"]) ? NULL : $data["individuo"]["estado_febril"]);
        $individuo["IND_HISTORIA_CLINICA"] = (!isset($data["individuo"]["historia_clinica"]) ? NULL : $data["individuo"]["historia_clinica"]);
        $individuo["IND_EDAD"] = (!isset($data["individuo"]["edad"]) ? NULL : $data["individuo"]["edad"]);
        $individuo["IND_TIPO_EDAD"] = (!isset($data["individuo"]["tipo_edad"]) ? '4' : $data["individuo"]["tipo_edad"]);
        $individuo["IND_FECHA_NACIMIENTO"] = (!isset($data["individuo"]["fecha_nacimiento"]) ? NULL : helperString::toDate($data["individuo"]["fecha_nacimiento"]));
        $individuo["IND_SEXO"] = (!isset($data["individuo"]["sexo"]) ? '0' : $data["individuo"]["sexo"]);
        $individuo["TIP_VIG_ID"] = (!isset($data["individuo"]["tipo_vigilancia"]) ? Configuration::IdTipoVigilanciaNoCorresponde : $data["individuo"]["tipo_vigilancia"]);

        $individuo["IND_DIRECCION"] = (!isset($data["individuo"]["direccion"]) ? NULL : strtoupper($data["individuo"]["direccion"]));
        $individuo["IND_DIRECCION_DISPONIBLE"] = ($data["individuo"]["no_direccion_individuo"] == 'on' ? '0' : '1');
        $individuo["IND_LOCALIDAD_DISPONIBLE"] = ($data["individuo"]["no_localidad"] == 'on' ? '0' : '1');

        $individuo["IND_VIAJO_PAIS"] = $data["individuo"]["viajo_pais"];
        $individuo["IND_VIAJO_PROVINCIA"] = $data["individuo"]["viajo_provincia"];
        $individuo["IND_VIAJO_REGION"] = $data["individuo"]["viajo_region"];
        $individuo["IND_VIAJO_DISTRITO"] = $data["individuo"]["viajo_distrito"];
        $individuo["IND_VIAJO_CORREGIMIENTO"] = $data["individuo"]["viajo_corregimiento"];

        $individuo["IND_PROC_PROVINCIA"] = $data["individuo"]["provincia"];
        $individuo["IND_PROC_REGION"] = $data["individuo"]["region"];
        $individuo["IND_PROC_DISTRITO"] = $data["individuo"]["distrito"];
        $individuo["IND_PROC_CORREGIMIENTO"] = $data["individuo"]["corregimiento"];
        $individuo["IND_CASADA"] = htmlentities($data["individuo"]["casada"]);
        return $individuo;
    }

    public static function getCodigos($idMuestra) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = 'select m.mue_codigo_global_anio, m.mue_codigo_global_numero, m.mue_codigo_correlativo_alfa, m.mue_codigo_correlativo_numero,
                    m.IND_PRIMER_NOMBRE, m.IND_PRIMER_APELLIDO, m.IND_IDENTIFICADOR, s.ARE_ANA_NOMBRE
                   from muestra m 
                   inner join area_analisis s on m.MUE_ARE_ANA_ID = s.ARE_ANA_ID
                   where m.mue_id =' . $idMuestra;
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getCodigosRec($idMuestra) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = 'select m.mue_codigo_global_anio, m.mue_codigo_global_numero, m.mue_codigo_correlativo_alfa, m.mue_codigo_correlativo_numero,
                    m.IND_PRIMER_NOMBRE, m.IND_PRIMER_APELLIDO, m.IND_IDENTIFICADOR
                   from muestra m 
                   where m.mue_id =' . $idMuestra;
        $conn->prepare($sql);
        //$conn->param();
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getCodigosDerivacion($idMuestra) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = '
                SELECT
                muestra.MUE_CODIGO_GLOBAL_ANIO,
                muestra.MUE_CODIGO_GLOBAL_NUMERO,
                muestra.MUE_CODIGO_CORRELATIVO_NUMERO,
                muestra.MUE_CODIGO_CORRELATIVO_ALFA
                FROM muestra
                 where MUE_ID =' . $idMuestra;

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    // Para búsqueda
    public static function getAll($config, $extra, $areas) {
        // Forma el filtro para que muestre sólo informes de las áreas
        // a las que el usuario tiene permiso.

        $areasQuery = '';
        $filtro = '';

        if (count($areas > 0)) {
            foreach ($areas as $area) {
                $areasQuery.= ' area_analisis.ARE_ANA_ID = ' . $area["ARE_ANA_ID"] . ' OR ';
            }

            if ($areasQuery != '') {
                $areasQuery = substr($areasQuery, 0, strlen($areasQuery) - 3);
                $filtro = ' AND (';
                $filtro.= $areasQuery . ' ) ';
            }
        }

        // Filtrar los resultados de búsquedas por permisos de procedencia
        // según división sanitaria del país
        $lista = clsCaus::obtenerUbicacionesCascada();
        if (is_array($lista)) {
            foreach ($lista as $elemento) {
                $temporal = "";
                if ($elemento[ConfigurationCAUS::Provincia] != "")
                    $temporal .= "muestra.MUE_PROC_PROVINCIA = '" . $elemento[ConfigurationCAUS::Provincia] . "' ";

                if ($elemento[ConfigurationCAUS::Region] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.MUE_PROC_REGION = '" . $elemento[ConfigurationCAUS::Region] . "' ";

                if ($elemento[ConfigurationCAUS::Distrito] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.MUE_PROC_DISTRITO = '" . $elemento[ConfigurationCAUS::Distrito] . "' ";

                if ($elemento[ConfigurationCAUS::Corregimiento] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.MUE_PROC_CORREGIMIENTO = '" . $elemento[ConfigurationCAUS::Corregimiento] . "' ";

                if ($elemento[ConfigurationCAUS::Instalacion] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.MUE_PROC_INST_SALUD = '" . $elemento[ConfigurationCAUS::Instalacion] . "' ";

                $filtroUbicaciones .= ($filtroUbicaciones != '' ? "or " : "") . "(" . $temporal . ") ";
            }
        }

        if ($filtroUbicaciones != "")
            $filtroUbicaciones = "and (" . $filtroUbicaciones . ")";

        if ($config["correlativo_barras"] != "") {
            $mue_codigo = helperString::getCodigo($config["correlativo_barras"]);
            $mue_numero = helperString::quitarZeros($config["correlativo_barras"]);
        }

        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = "SELECT
              muestra.MUE_ID,
              muestra.MUE_CODIGO_GLOBAL_ANIO as global,
              muestra.MUE_CODIGO_GLOBAL_NUMERO as gnumero,
              muestra.MUE_CODIGO_CORRELATIVO_NUMERO as cnumero,
              muestra.MUE_CODIGO_CORRELATIVO_ALFA as correlativo,
              evento.EVE_NOMBRE as evento,
              DATE_FORMAT(muestra.MUE_FECHA_TOMA, '%d/%m/%Y') as ftoma,
              DATE_FORMAT(muestra.MUE_FECHA_RECEPCION, '%d/%m/%Y') as frecepcion
            FROM
              muestra
              INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
              INNER JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID)
              INNER JOIN evento_seccion es ON evento.EVE_ID = es.EVE_ID AND es.ARE_ANA_ID = muestra.MUE_ARE_ANA_ID
              INNER JOIN area_analisis ON (es.ARE_ANA_ID = area_analisis.ARE_ANA_ID)
              WHERE muestra.mue_activa = 1 AND muestra.MUE_RECHAZADA = 0 " . $extra . $filtro
                . ($config["nombre"] != "" ? " AND CONCAT(muestra.IND_PRIMER_NOMBRE,' ',muestra.IND_SEGUNDO_NOMBRE)  LIKE '%" . $config["nombre"] . "%'" : "")
                . ($config["apellido"] != "" ? " AND CONCAT(muestra.IND_PRIMER_APELLIDO,' ',muestra.IND_SEGUNDO_APELLIDO)  LIKE '%" . $config["apellido"] . "%'" : "")
                . ($config["identificador"] != "" ? " AND muestra.IND_IDENTIFICADOR  LIKE '%" . $config["identificador"] . "%'" : "")
                . ($config["historia_clinica"] != "" ? " AND muestra.IND_HISTORIA_CLINICA  LIKE '%" . $config["historia_clinica"] . "%'" : "")
                . ($config["area"] == 0 ? '' : ($config["area"] != "" ? " AND area_analisis.ARE_ANA_ID = " . $config["area"] : ""))
                . ($config["evento"] == 0 ? '' : ($config["evento"] != "" ? " AND evento.EVE_ID = " . $config["evento"] : ""))
                . ($config["global_desde"] != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO >= " . $config["global_desde"] : "")
                . ($config["global_hasta"] != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO <= " . $config["global_hasta"] : "")
                . ($config["correlativo_desde"] != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO >= " . $config["correlativo_desde"] : "")
                . ($config["correlativo_hasta"] != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO <= " . $config["correlativo_hasta"] : "")
                . ($config["toma_desde"] != "" ? " AND muestra.MUE_FECHA_TOMA >= '" . $conn->scapeString(helperString::toDate($config["toma_desde"])) . "'" : "")
                . ($config["toma_hasta"] != "" ? " AND muestra.MUE_FECHA_TOMA <= '" . $conn->scapeString(helperString::toDate($config["toma_hasta"])) . "'" : "")
                . ($config["recepcion_desde"] != "" ? " AND muestra.MUE_FECHA_RECEPCION >= '" . $conn->scapeString(helperString::toDate($config["recepcion_desde"])) . "'" : "")
                . ($config["recepcion_hasta"] != "" ? " AND muestra.MUE_FECHA_RECEPCION <= '" . $conn->scapeString(helperString::toDate($config["recepcion_hasta"])) . "'" : "")
                . ($config["correlativo_barras"] == '' ? '' : " AND muestra.MUE_CODIGO_CORRELATIVO_ALFA = '" . $conn->scapeString($mue_codigo) . "' AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO = '" . $conn->scapeString($mue_numero) . "'")
                . " " . $filtroUbicaciones . " "
                . " order by muestra.MUE_CODIGO_GLOBAL_ANIO ASC, muestra.MUE_CODIGO_GLOBAL_NUMERO ASC, muestra.MUE_CODIGO_CORRELATIVO_ALFA ASC, muestra.MUE_CODIGO_CORRELATIVO_NUMERO ASC"
                . " limit " . $config["inicio"] . "," . $config["paginado"];

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getCountAll($config, $extra, $areas) {
        $areasQuery = '';
        $filtro = '';

        if (count($areas)) {
            foreach ($areas as $area) {
                $areasQuery.= ' area_analisis.ARE_ANA_ID = ' . $area["ARE_ANA_ID"] . ' OR ';
            }

            if ($areasQuery != '') {
                $areasQuery = substr($areasQuery, 0, strlen($areasQuery) - 3);
                $filtro = ' AND (';
                $filtro.= $areasQuery . ' ) ';
            }
        }

        // Filtrar los resultados de búsquedas por permisos de procedencia
        // según división sanitaria del país
        $lista = clsCaus::obtenerUbicacionesCascada();
        if (is_array($lista)) {
            foreach ($lista as $elemento) {
                $temporal = "";
                if ($elemento[ConfigurationCAUS::Provincia] != "")
                    $temporal .= "muestra.MUE_PROC_PROVINCIA = '" . $elemento[ConfigurationCAUS::Provincia] . "' ";

                if ($elemento[ConfigurationCAUS::Region] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.MUE_PROC_REGION = '" . $elemento[ConfigurationCAUS::Region] . "' ";

                if ($elemento[ConfigurationCAUS::Distrito] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.MUE_PROC_DISTRITO = '" . $elemento[ConfigurationCAUS::Distrito] . "' ";

                if ($elemento[ConfigurationCAUS::Corregimiento] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.MUE_PROC_CORREGIMIENTO = '" . $elemento[ConfigurationCAUS::Corregimiento] . "' ";

                if ($elemento[ConfigurationCAUS::Instalacion] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.MUE_PROC_INST_SALUD = '" . $elemento[ConfigurationCAUS::Instalacion] . "' ";

                $filtroUbicaciones .= ($filtroUbicaciones != '' ? "or " : "") . "(" . $temporal . ") ";
            }
        }

        if ($filtroUbicaciones != "")
            $filtroUbicaciones = "and (" . $filtroUbicaciones . ")";

        if ($config["correlativo_barras"] != "") {
            $mue_codigo = helperString::getCodigo($config["correlativo_barras"]);
            $mue_numero = helperString::quitarZeros($config["correlativo_barras"]);
        }

        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = "SELECT count(*) as cantidad
            FROM
              muestra
              INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
              INNER JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID)
              INNER JOIN evento_seccion es ON evento.EVE_ID = es.EVE_ID AND es.ARE_ANA_ID = muestra.MUE_ARE_ANA_ID
              INNER JOIN area_analisis ON (es.ARE_ANA_ID = area_analisis.ARE_ANA_ID)
              WHERE muestra.mue_activa = 1 AND muestra.MUE_RECHAZADA = 0 " . $extra . $filtro
                . ($config["nombre"] != "" ? " AND CONCAT(muestra.IND_PRIMER_NOMBRE,' ',muestra.IND_SEGUNDO_NOMBRE)  LIKE '%" . $config["nombre"] . "%'" : "")
                . ($config["apellido"] != "" ? " AND CONCAT(muestra.IND_PRIMER_APELLIDO,' ',muestra.IND_SEGUNDO_APELLIDO)  LIKE '%" . $config["apellido"] . "%'" : "")
                . ($config["identificador"] != "" ? " AND muestra.IND_IDENTIFICADOR  LIKE '%" . $config["identificador"] . "%'" : "")
                . ($config["historia_clinica"] != "" ? " AND muestra.IND_HISTORIA_CLINICA  LIKE '%" . $config["historia_clinica"] . "%'" : "")
                . ($config["area"] == 0 ? '' : ($config["area"] != "" ? " AND area_analisis.ARE_ANA_ID = " . $config["area"] : ""))
                . ($config["evento"] == 0 ? '' : ($config["evento"] != "" ? " AND evento.EVE_ID = " . $config["evento"] : ""))
                . ($config["global_desde"] != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO >= " . $config["global_desde"] : "")
                . ($config["global_hasta"] != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO <= " . $config["global_hasta"] : "")
                . ($config["correlativo_desde"] != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO >= " . $config["correlativo_desde"] : "")
                . ($config["correlativo_hasta"] != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO <= " . $config["correlativo_hasta"] : "")
                . ($config["toma_desde"] != "" ? " AND muestra.MUE_FECHA_TOMA >= '" . $conn->scapeString(helperString::toDate($config["toma_desde"])) . "'" : "")
                . ($config["toma_hasta"] != "" ? " AND muestra.MUE_FECHA_TOMA <= '" . $conn->scapeString(helperString::toDate($config["toma_hasta"])) . "'" : "")
                . ($config["recepcion_desde"] != "" ? " AND muestra.MUE_FECHA_RECEPCION >= '" . $conn->scapeString(helperString::toDate($config["recepcion_desde"])) . "'" : "")
                . ($config["recepcion_hasta"] != "" ? " AND muestra.MUE_FECHA_RECEPCION <= '" . $conn->scapeString(helperString::toDate($config["recepcion_hasta"])) . "'" : "")
                . ($config["correlativo_barras"] == '' ? '' : " AND muestra.MUE_CODIGO_CORRELATIVO_ALFA = '" . $conn->scapeString($mue_codigo) . "' AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO = '" . $conn->scapeString($mue_numero) . "'")
                . " " . $filtroUbicaciones . " "
                . " order by muestra.MUE_CODIGO_GLOBAL_ANIO ASC, muestra.MUE_CODIGO_GLOBAL_NUMERO ASC, muestra.MUE_CODIGO_CORRELATIVO_ALFA ASC, muestra.MUE_CODIGO_CORRELATIVO_NUMERO ASC";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }

    public static function getDatosBasicos($id) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = "SELECT
                  muestra.MUE_ID,
                  muestra.MUE_CODIGO_GLOBAL_ANIO,
                  muestra.MUE_CODIGO_GLOBAL_NUMERO,
                  muestra.MUE_CODIGO_CORRELATIVO_NUMERO,
                  IFNULL(muestra.MUE_CODIGO_CORRELATIVO_ALFA,' ') as MUE_CODIGO_CORRELATIVO_ALFA,
                  IFNULL(muestra.MUE_FECHA_TOMA,' ') as MUE_FECHA_TOMA,
                  IFNULL(muestra.MUE_FECHA_RECEPCION,' ') as MUE_FECHA_RECEPCION,
                  IFNULL(muestra.MUE_FECHA_INICIO,' ') as inicio,
                  estado_motivo.EST_ID,
                  estado_motivo.MOT_ID,
                  estado.EST_NOMBRE,
                  motivo.MOT_NOMBRE,
                  IFNULL(evento.EVE_ID,' ') as EVE_ID,
                  IFNULL(evento.EVE_NOMBRE,' ') as EVE_NOMBRE,
                  IFNULL(tipo_muestra.TIP_MUE_ID,' ') as TIP_MUE_ID,
                  IFNULL(tipo_muestra.TIP_MUE_NOMBRE,' ') as TIP_MUE_NOMBRE,
                  IFNULL(area_analisis.ARE_ANA_NOMBRE,' ') as ARE_ANA_NOMBRE,
                  IFNULL(area_analisis.ARE_ANA_ID,' ') as ARE_ANA_ID,
                  estado_motivo.EST_MOT_ID,
                  muestra.SIT_ID,
                  muestra.MUE_TENDENCIA,
                  muestra.MUE_SITUACION_HOSPITALAR as condicion,
                  muestra.MUE_COMENTARIOS as comentarios,
                  muestra.MUE_ANTECEDENTES_INTERES as comentarios_interes,
                  muestra.MUE_RESULTADO_EXTERNO as comentarios_externos,
                  muestra.MUE_CARGA_VIRAL as carga,
                  muestra.MUE_DONADOR as donador,
                  muestra.MUE_SINTOMATICO as sintomatico,
                  muestra.MUE_SEROLOGICA as serologica,
                  IFNULL(muestra.DER_FECHA,' ') as DER_FECHA,
                  muestra.MUE_TIPO_FLU as flu,
                  muestra.MUE_OTRO_ESTABLECIMIENTO_NOMBRE AS otro_establecimiento,
                  (CASE WHEN `IND_IDENTIFICADOR_TIPO` IS NOT NULL
                  THEN 
                    (CASE WHEN `IND_IDENTIFICADOR_TIPO` = 1 THEN concat(' / Cedula: ' ,`IND_IDENTIFICADOR`)
                    ELSE 
                        (CASE WHEN `IND_IDENTIFICADOR_TIPO` = 2 THEN concat(' / Pasaporte: ' , `IND_IDENTIFICADOR`)
                        ELSE 
                                (CASE WHEN `IND_IDENTIFICADOR_TIPO` = 3 THEN concat(' / No. Exp: ' , `IND_IDENTIFICADOR`) ELSE ' / No hay dato' END)
                        END)
                    END)	
                  ELSE '' END) as IND_IDENTIFICADOR,
                  IFNULL(muestra.IND_PRIMER_NOMBRE,'No hay Datos') as IND_PRIMER_NOMBRE,
                  IFNULL(muestra.IND_SEGUNDO_NOMBRE,' ') as IND_SEGUNDO_NOMBRE,
                  IFNULL(muestra.IND_PRIMER_APELLIDO,' ') as IND_PRIMER_APELLIDO,
                  IFNULL(muestra.IND_SEGUNDO_APELLIDO,' ') as IND_SEGUNDO_APELLIDO,
                  IFNULL(muestra.IND_HISTORIA_CLINICA,'NO HAY DATO') as IND_HISTORIA_CLINICA,
                  IFNULL(pro.nombre_provincia,'NO HAY DATO') as provincia,
                  reg.nombre_region as region,
                  IFNULL(dis.nombre_distrito,'NO HAY DATO') as distrito,
                  IFNULL(cor.nombre_corregimiento, 'NO HAY DATO') as corregimiento,
                  IFNULL(cat_unidad_notificadora.nombre_un,'NO HAY DATO') as unidad,
                  IFNULL(per_pro.nombre_provincia,'NO HAY DATO') as per_provincia,
                  per_reg.nombre_region as per_region,
                  IFNULL(per_dis.nombre_distrito,'NO HAY DATO') as per_distrito,
                  IFNULL(per_cor.nombre_corregimiento, 'NO HAY DATO') as per_corregimiento
                FROM
                  muestra
                  LEFT OUTER  JOIN estado_motivo ON (muestra.EST_MOT_ID = estado_motivo.EST_MOT_ID)
                  LEFT OUTER  JOIN estado ON (estado_motivo.EST_ID = estado.EST_ID)
                  LEFT OUTER  JOIN motivo ON (estado_motivo.MOT_ID = motivo.MOT_ID)
                  LEFT OUTER  JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
                  LEFT OUTER  JOIN tipo_muestra ON (tipo_muestra_evento.TIPO_MUE_ID = tipo_muestra.TIP_MUE_ID)
                  LEFT OUTER  JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID)
                  LEFT OUTER  JOIN evento_seccion es ON evento.EVE_ID = es.EVE_ID AND es.ARE_ANA_ID = muestra.MUE_ARE_ANA_ID
                  LEFT OUTER  JOIN area_analisis ON (es.ARE_ANA_ID = area_analisis.ARE_ANA_ID)
                  LEFT JOIN cat_provincia pro ON (muestra.MUE_PROC_PROVINCIA = pro.id_provincia)
                  LEFT JOIN cat_region_salud reg ON (muestra.MUE_PROC_REGION = reg.id_region)
                  LEFT JOIN cat_distrito dis ON (muestra.MUE_PROC_DISTRITO = dis.id_distrito)
                  LEFT JOIN cat_corregimiento cor ON (muestra.MUE_PROC_CORREGIMIENTO = cor.id_corregimiento)
                  LEFT OUTER  JOIN cat_unidad_notificadora ON (muestra.MUE_PROC_INST_SALUD = cat_unidad_notificadora.id_un)
                  LEFT JOIN cat_provincia per_pro ON (muestra.IND_PROC_PROVINCIA = per_pro.id_provincia)
                  LEFT JOIN cat_region_salud per_reg ON (muestra.IND_PROC_REGION = per_reg.id_region)
                  LEFT JOIN cat_distrito per_dis ON (muestra.IND_PROC_DISTRITO = per_dis.id_distrito)
                  LEFT JOIN cat_corregimiento per_cor ON (muestra.IND_PROC_CORREGIMIENTO = per_cor.id_corregimiento)
                  where MUE_ID=" . $id;

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data;
    }

    public static function getDatosMuestra($id) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = "SELECT *
                FROM
                  muestra where MUE_ID=" . $id;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data;
    }

    public static function getDatosBasicosDerivacion($id) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = "SELECT
              muestra.MUE_CODIGO_GLOBAL_ANIO,
              muestra.MUE_CODIGO_GLOBAL_NUMERO,
              derivacion.DER_CODIGO_CORRELATIVO_NUMERO,
              derivacion.DER_CODIGO_CORRELATIVO_ALFA,
              muestra.MUE_FECHA_TOMA,
              muestra.MUE_FECHA_RECEPCION,
              evento.EVE_ID,
              evento.EVE_NOMBRE,
              evento.ARE_ANA_ID,
              area_analisis.ARE_ANA_NOMBRE,
              derivacion.DER_FECHA,
              tipo_muestra.TIP_MUE_NOMBRE,
              derivacion.SIT_ID,
              derivacion.DER_ID,
              tipo_muestra.TIP_MUE_ID,
              derivacion.DER_TENDENCIA,
              derivacion.EST_MOT_ID,
              estado_motivo.EST_ID,
              estado_motivo.MOT_ID
            FROM
              derivacion
              INNER JOIN muestra ON (derivacion.MUE_ID = muestra.MUE_ID)
              INNER JOIN tipo_muestra_evento ON (derivacion.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
              INNER JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID)
              INNER JOIN area_analisis ON (evento.ARE_ANA_ID = area_analisis.ARE_ANA_ID)
              INNER JOIN tipo_muestra ON (tipo_muestra_evento.TIPO_MUE_ID = tipo_muestra.TIP_MUE_ID)
              INNER JOIN estado_motivo ON (derivacion.EST_MOT_ID = estado_motivo.EST_MOT_ID)
            WHERE derivacion.DER_ID =" . $id;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data;
    }

    public static function getEstados() {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = "SELECT
              estado.EST_ID,
              estado.EST_NOMBRE
              FROM
              estado order by EST_ID asc";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getMotivos($id) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = "SELECT
                motivo.MOT_ID,
                motivo.MOT_NOMBRE,
                estado_motivo.EST_MOT_ID,
                estado.EST_ID
                FROM
                    estado_motivo
                    INNER JOIN estado ON (estado_motivo.EST_ID = estado.EST_ID)
                    INNER JOIN motivo ON (estado_motivo.MOT_ID = motivo.MOT_ID) where estado.EST_ID=" . $id;

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getPruebas($evento, $tip) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = " SELECT
                 DISTINCT
                 PRU_ID, PRU_NOMBRE
                 FROM
                    pruebas_completas
                WHERE EVE_ID=" . $evento . ' AND ' . 'TIP_MUE_ID=' . $tip . ' AND PRU_ID!=0 ORDER BY PRU_NOMBRE';

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getResultados($evento, $tip, $prueba) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = " SELECT
                 DISTINCT
                 RES_ID, RES_NOMBRE
                 FROM
                    pruebas_completas
                WHERE EVE_ID=" . $evento . ' AND ' . 'TIP_MUE_ID=' . $tip . ' AND PRU_ID=' . $prueba . ' AND RES_ID!=0';

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getTipoSubtipo($evento, $resultadoFinal, $tipo, $subtipo) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = " SELECT
                 DISTINCT
                 TIP_SUB_ID
                 FROM
                    conclusion
                WHERE EVE_ID=" . $evento . ' AND ' . 'RES_FIN_ID=' . $resultadoFinal . ' AND TIP_ID=' . $tipo . ' AND SUB_ID=' . $subtipo;

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getResultadosFinales($evento) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = " SELECT
                 DISTINCT
                 RES_FIN_NOMBRE, RES_FIN_ID
                 FROM
                    conclusion
                WHERE EVE_ID=" . $evento . ' AND RES_FIN_ID!=0 order by RES_FIN_ID ASC';

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getTipos($evento, $resultado) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $sql = " SELECT
                 DISTINCT
                 TIP_NOMBRE, TIP_ID
                 FROM
                    conclusion
                WHERE EVE_ID=" . $evento . ' AND RES_FIN_ID=' . $resultado . ' AND TIP_ID!=0';
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
    public static function getTiposNombre($evento, $resultado) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $sql = " SELECT
                 DISTINCT
                 TIP_NOMBRE, TIP_ID
                 FROM
                    conclusion
                WHERE EVE_ID=" . $evento . " AND RES_FIN_NOMBRE='" . $resultado . "' AND TIP_ID!=0";
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getSubtipos($evento, $resultado, $tipo) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = " SELECT
                 DISTINCT
                 SUB_ID, SUB_NOMBRE
                 FROM
                    conclusion
                WHERE EVE_ID=" . $evento . ' AND RES_FIN_ID=' . $resultado . ' AND TIP_ID=' . $tipo . ' AND SUB_ID!=0';

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
    public static function getSubtiposNombre($evento, $resultado, $tipo) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = " SELECT
                 DISTINCT
                 SUB_ID, SUB_NOMBRE
                 FROM
                    conclusion
                WHERE EVE_ID=" . $evento . " AND RES_FIN_NOMBRE='" . $resultado . "' AND TIP_NOMBRE='" . $tipo . "' AND SUB_ID!=0";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getPruebasRealizadas($id) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = "SELECT
              analisis_muestra.MUE_ID,
              analisis_muestra.ANA_MUE_COMENTARIOS,
              analisis_muestra.ANA_MUE_FECHA,
              analisis_muestra.ANA_MUE_PRESENCIA,
              analisis_muestra.ANA_MUE_AG,
              analisis_muestra.ANA_MUE_IF,
              prueba_resultado.PRU_RES_ID,
              resultado.RES_ID,
              resultado.RES_NOMBRE,
              prueba.PRU_ID,
              prueba.PRU_NOMBRE,
              tipo_muestra_evento.EVE_ID,
              tipo_muestra.TIP_MUE_ID,
              tipo_muestra.TIP_MUE_NOMBRE
            FROM
              analisis_muestra
              INNER JOIN prueba_resultado ON (analisis_muestra.PRU_RES_ID = prueba_resultado.PRU_RES_ID)
              INNER JOIN resultado ON (prueba_resultado.RES_ID = resultado.RES_ID)
              INNER JOIN prueba_evento ON (prueba_resultado.PRU_EVE_ID = prueba_evento.PRU_EVE_ID)
              INNER JOIN prueba ON (prueba_evento.PRU_ID = prueba.PRU_ID)
              INNER JOIN tipo_muestra_evento ON (prueba_evento.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
              INNER JOIN tipo_muestra ON (tipo_muestra_evento.TIPO_MUE_ID = tipo_muestra.TIP_MUE_ID)
              WHERE MUE_ID =" . $id . ' AND prueba.PRU_ID!=0';

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
//        echo '<pre>'; print_r($data); echo'</pre>'; exit;
        return $data;
    }

    public static function getPruebasMetodologias($id) {
        $res = array();
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = "SELECT
              amm.*, m.descripcion as metodologia
            FROM
              analisis_muestra_metodologia amm
              INNER JOIN metodologia m on m.idmetodologia = amm.idmetodologia
              WHERE amm.MUE_ID =" . $id;

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        if (is_array($data)) {
            foreach ($data as $reg) {
                $res[$reg["PRU_RES_ID"]]["METODOLOGIAS"][] = $reg["metodologia"];
                $res[$reg["PRU_RES_ID"]]["METODOLOGIASIDS"][] = $reg["idmetodologia"];
            }
        }

        return $res;
    }

    public static function tipoMuestra($idMuestra) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = 'SELECT MUE_TIPO from muestra where MUE_ID = ' . $idMuestra;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }

    public static function getConclusionMuestra($idMuestra) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = "SELECT
                  conclusion_muestra.CON_MUE_COMENTARIOS AS comentarios,
                  conclusion.RES_FIN_NOMBRE AS resultado,
                  conclusion.TIP_NOMBRE AS tipo1,
                  conclusion.SUB_NOMBRE AS subtipo1,
                  conclusion1.TIP_NOMBRE AS tipo2,
                  conclusion1.SUB_NOMBRE AS subtipo2,
                  conclusion2.TIP_NOMBRE AS tipo3,
                  conclusion2.SUB_NOMBRE AS subtipo3,
                  muestra.MUE_ID,
                  conclusion.TIP_ID as t1,
                  conclusion.SUB_ID as s1,
                  conclusion.RES_FIN_ID res,
                  conclusion1.TIP_ID as t2,
                  conclusion1.SUB_ID as s2,
                  conclusion2.TIP_ID as t3,
                  conclusion2.SUB_ID as s3
                FROM
                  conclusion_muestra
                  INNER JOIN muestra ON (conclusion_muestra.MUE_ID = muestra.MUE_ID)
                  INNER JOIN conclusion ON (conclusion_muestra.TIP_SUB_ID1 = conclusion.TIP_SUB_ID)
                  INNER JOIN conclusion conclusion1 ON (conclusion_muestra.TIP_SUB_ID2 = conclusion1.TIP_SUB_ID)
                  LEFT JOIN conclusion conclusion2 ON (conclusion_muestra.TIP_SUB_ID3 = conclusion2.TIP_SUB_ID)
              WHERE muestra.MUE_ID =" . $idMuestra . ' AND conclusion.RES_FIN_ID!=0';

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }

    public static function getPruebasRealizadasDerivacion($id) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = "SELECT
              analisis_derivacion.DER_ID,
              analisis_derivacion.ANA_DER_COMENTARIOS,
              analisis_derivacion.ANA_DER_FECHA,
              prueba_resultado.PRU_RES_ID,
              resultado.RES_ID,
              resultado.RES_NOMBRE,
              prueba.PRU_ID,
              prueba.PRU_NOMBRE,
              tipo_muestra_evento.EVE_ID,
              tipo_muestra.TIP_MUE_ID,
              tipo_muestra.TIP_MUE_NOMBRE
            FROM
              analisis_derivacion
              INNER JOIN prueba_resultado ON (analisis_derivacion.PRU_RES_ID = prueba_resultado.PRU_RES_ID)
              INNER JOIN resultado ON (prueba_resultado.RES_ID = resultado.RES_ID)
              INNER JOIN prueba_evento ON (prueba_resultado.PRU_EVE_ID = prueba_evento.PRU_EVE_ID)
              INNER JOIN prueba ON (prueba_evento.PRU_ID = prueba.PRU_ID)
              INNER JOIN tipo_muestra_evento ON (prueba_evento.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
              INNER JOIN tipo_muestra ON (tipo_muestra_evento.TIPO_MUE_ID = tipo_muestra.TIP_MUE_ID)
              WHERE DER_ID =" . $id;

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getPruebaResultadoGeneral($evento, $tipo, $prueba, $resultado) {
        $sql = 'select PRU_RES_ID from pruebas_completas where EVE_ID=' . $evento . ' AND TIP_MUE_ID=' . $tipo . ' AND PRU_ID=' . $prueba . ' AND RES_ID=' . $resultado;
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["PRU_RES_ID"];
    }

    // Para búsqueda
    public static function getAllDerivaciones($config, $extra, $areas) {
        $areasQuery = '';
        $filtro = '';
        foreach ($areas as $area) {
            $areasQuery.= ' area_analisis.ARE_ANA_ID = ' . $area["ARE_ANA_ID"] . ' OR ';
        }

        if ($areasQuery != '') {
            $areasQuery = substr($areasQuery, 0, strlen($areasQuery) - 3);
            $filtro = ' AND (';
            $filtro.= $areasQuery . ' ) ';
        }

        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = "SELECT
              muestra.MUE_ID,
              muestra.MUE_CODIGO_GLOBAL_ANIO as global,
              muestra.MUE_CODIGO_GLOBAL_NUMERO as gnumero,
              muestra.MUE_CODIGO_CORRELATIVO_NUMERO as cnumero,
              muestra.MUE_CODIGO_CORRELATIVO_ALFA as correlativo,
              evento.EVE_NOMBRE as evento,
              DATE_FORMAT(muestra.MUE_FECHA_TOMA, '%d/%m/%Y') as ftoma,
              DATE_FORMAT(muestra.DER_FECHA, '%d/%m/%Y') as frecepcion
            FROM
              muestra
              INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
              INNER JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID)
              INNER JOIN area_analisis ON (evento.ARE_ANA_ID = area_analisis.ARE_ANA_ID)
              WHERE muestra.mue_activa = 1 AND muestra.MUE_TIPO=3 AND muestra.MUE_RECHAZADA = 0 " . $extra . $filtro
                . ($config["nombre"] != "" ? " AND CONCAT(muestra.IND_PRIMER_NOMBRE,' ',IND_SEGUNDO_NOMBRE)  LIKE '%" . $config["nombre"] . "%'" : "")
                . ($config["apellido"] != "" ? " AND CONCAT(muestra.IND_PRIMER_APELLIDO,' ',muestra.IND_SEGUNDO_APELLIDO)  LIKE '%" . $config["apellido"] . "%'" : "")
                . ($config["identificador"] != "" ? " AND muestra.IND_IDENTIFICADOR  LIKE '%" . $config["identificador"] . "%'" : "")
                . ($config["historia_clinica"] != "" ? " AND muestra.IND_HISTORIA_CLINICA  LIKE '%" . $config["historia_clinica"] . "%'" : "")
                . ($config["area"] == 0 ? '' : ($config["area"] != "" ? " AND area_analisis.ARE_ANA_ID = " . $config["area"] : ""))
                . ($config["evento"] == 0 ? '' : ($config["evento"] != "" ? " AND evento.EVE_ID = " . $config["evento"] : ""))
                . ($config["global_desde"] != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO >= " . $config["global_desde"] : "")
                . ($config["global_hasta"] != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO <= " . $config["global_hasta"] : "")
                . ($config["correlativo_desde"] != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO >= " . $config["correlativo_desde"] : "")
                . ($config["correlativo_hasta"] != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO <= " . $config["correlativo_hasta"] : "")
                . ($config["toma_desde"] != "" ? " AND muestra.MUE_FECHA_TOMA >= '" . $conn->scapeString(helperString::toDate($config["toma_desde"])) . "'" : "")
                . ($config["toma_hasta"] != "" ? " AND muestra.MUE_FECHA_TOMA <= '" . $conn->scapeString(helperString::toDate($config["toma_hasta"])) . "'" : "")
                . ($config["recepcion_desde"] != "" ? " AND muestra.MUE_FECHA_RECEPCION >= '" . $conn->scapeString(helperString::toDate($config["recepcion_desde"])) . "'" : "")
                . ($config["recepcion_hasta"] != "" ? " AND muestra.MUE_FECHA_RECEPCION <= '" . $conn->scapeString(helperString::toDate($config["recepcion_hasta"])) . "'" : "")
                . " order by muestra.MUE_CODIGO_GLOBAL_ANIO ASC, muestra.MUE_CODIGO_GLOBAL_NUMERO ASC, muestra.MUE_CODIGO_CORRELATIVO_ALFA ASC, muestra.MUE_CODIGO_CORRELATIVO_NUMERO ASC"
                . " limit " . $config["inicio"] . "," . $config["paginado"];

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getCountAllDerivaciones($config, $extra, $areas) {
        $areasQuery = '';
        $filtro = '';
        foreach ($areas as $area) {
            $areasQuery.= ' area_analisis.ARE_ANA_ID = ' . $area["ARE_ANA_ID"] . ' OR ';
        }

        if ($areasQuery != '') {
            $areasQuery = substr($areasQuery, 0, strlen($areasQuery) - 3);
            $filtro = ' AND (';
            $filtro.= $areasQuery . ' ) ';
        }

        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = "SELECT count(*) as cantidad
            FROM
              muestra
              INNER JOIN tipo_muestra_evento ON (muestra.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)
              INNER JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID)
              INNER JOIN area_analisis ON (evento.ARE_ANA_ID = area_analisis.ARE_ANA_ID)
              WHERE muestra.mue_activa = 1  AND muestra.MUE_TIPO=3 AND muestra.MUE_RECHAZADA = 0 " . $extra . $filtro
                . ($config["nombre"] != "" ? " AND CONCAT(muestra.IND_PRIMER_NOMBRE,' ',IND_SEGUNDO_NOMBRE)  LIKE '%" . $config["nombre"] . "%'" : "")
                . ($config["apellido"] != "" ? " AND CONCAT(muestra.IND_PRIMER_APELLIDO,' ',muestra.IND_SEGUNDO_APELLIDO)  LIKE '%" . $config["apellido"] . "%'" : "")
                . ($config["identificador"] != "" ? " AND muestra.IND_IDENTIFICADOR  LIKE '%" . $config["identificador"] . "%'" : "")
                . ($config["historia_clinica"] != "" ? " AND muestra.IND_HISTORIA_CLINICA  LIKE '%" . $config["historia_clinica"] . "%'" : "")
                . ($config["area"] == 0 ? '' : ($config["area"] != "" ? " AND area_analisis.ARE_ANA_ID = " . $config["area"] : ""))
                . ($config["evento"] == 0 ? '' : ($config["evento"] != "" ? " AND evento.EVE_ID = " . $config["evento"] : ""))
                . ($config["global_desde"] != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO >= " . $config["global_desde"] : "")
                . ($config["global_hasta"] != "" ? " AND muestra.MUE_CODIGO_GLOBAL_NUMERO <= " . $config["global_hasta"] : "")
                . ($config["correlativo_desde"] != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO >= " . $config["correlativo_desde"] : "")
                . ($config["correlativo_hasta"] != "" ? " AND muestra.MUE_CODIGO_CORRELATIVO_NUMERO <= " . $config["correlativo_hasta"] : "")
                . ($config["toma_desde"] != "" ? " AND muestra.MUE_FECHA_TOMA >= '" . $conn->scapeString(helperString::toDate($config["toma_desde"])) . "'" : "")
                . ($config["toma_hasta"] != "" ? " AND muestra.MUE_FECHA_TOMA <= '" . $conn->scapeString(helperString::toDate($config["toma_hasta"])) . "'" : "")
                . ($config["recepcion_desde"] != "" ? " AND muestra.MUE_FECHA_RECEPCION >= '" . $conn->scapeString(helperString::toDate($config["recepcion_desde"])) . "'" : "")
                . ($config["recepcion_hasta"] != "" ? " AND muestra.MUE_FECHA_RECEPCION <= '" . $conn->scapeString(helperString::toDate($config["recepcion_hasta"])) . "'" : "")
                . " order by muestra.MUE_CODIGO_GLOBAL_ANIO ASC, muestra.MUE_CODIGO_GLOBAL_NUMERO ASC, muestra.MUE_CODIGO_CORRELATIVO_ALFA ASC, muestra.MUE_CODIGO_CORRELATIVO_NUMERO ASC";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }

    public static function estadoMotivo($estado, $motivo) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $sql = 'select  EST_MOT_ID from estado_motivo where EST_ID=' . $estado . ' AND MOT_ID =' . $motivo;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getAllDerivacionesOrigen($config, $extra, $areas) {

        $areasQuery = '';
        $filtro = '';
        foreach ($areas as $area) {
            $areasQuery.= ' area_analisis.ARE_ANA_ID = ' . $area["ARE_ANA_ID"] . ' OR ';
        }

        if ($areasQuery != '') {
            $areasQuery = substr($areasQuery, 0, strlen($areasQuery) - 3);
            $filtro = ' AND (';
            $filtro.= $areasQuery . ' ) ';
        }

        // Filtrar los resultados de búsquedas por permisos de procedencia
        // según división sanitaria del país
        $lista = clsCaus::obtenerUbicacionesCascada();
        if (is_array($lista)) {
            foreach ($lista as $elemento) {
                $temporal = "";
                if ($elemento[ConfigurationCAUS::Provincia] != "")
                    $temporal .= "muestra.MUE_PROC_PROVINCIA = '" . $elemento[ConfigurationCAUS::Provincia] . "' ";

                if ($elemento[ConfigurationCAUS::Region] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.MUE_PROC_REGION = '" . $elemento[ConfigurationCAUS::Region] . "' ";

                if ($elemento[ConfigurationCAUS::Distrito] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.MUE_PROC_DISTRITO = '" . $elemento[ConfigurationCAUS::Distrito] . "' ";

                if ($elemento[ConfigurationCAUS::Corregimiento] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.MUE_PROC_CORREGIMIENTO = '" . $elemento[ConfigurationCAUS::Corregimiento] . "' ";

                if ($elemento[ConfigurationCAUS::Instalacion] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.MUE_PROC_INST_SALUD = '" . $elemento[ConfigurationCAUS::Instalacion] . "' ";

                $filtroUbicaciones .= ($filtroUbicaciones != '' ? "or " : "") . "(" . $temporal . ") ";
            }
        }

        if ($filtroUbicaciones != "")
            $filtroUbicaciones = "and (" . $filtroUbicaciones . ")";

        $conn = new ConnectionSilabRemoto();
        $conn->initConn();


        $sql = "SELECT
                derivacion.MUE_ID,
                derivacion.MUE_CODIGO_GLOBAL_ANIO as global,
                derivacion.MUE_CODIGO_GLOBAL_NUMERO as gnumero,
                derivacion.MUE_CODIGO_CORRELATIVO_NUMERO as cnumero,
                derivacion.MUE_CODIGO_CORRELATIVO_ALFA as correlativo,
                evento_hijo.EVE_NOMBRE as evento,
                DATE_FORMAT(derivacion.MUE_FECHA_TOMA, '%d/%m/%Y') as ftoma,
                DATE_FORMAT(derivacion.DER_FECHA, '%d/%m/%Y') as frecepcion
                FROM
                  muestra derivacion
                  INNER JOIN tipo_muestra_evento ON (derivacion.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)                  
                  INNER JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID)
                  INNER JOIN evento_seccion es ON evento.EVE_ID = es.EVE_ID AND es.ARE_ANA_ID = derivacion.MUE_ARE_ANA_ID
                  INNER JOIN area_analisis ON (es.ARE_ANA_ID = area_analisis.ARE_ANA_ID)                  
                  INNER JOIN tipo_muestra_evento tipo_muestra_evento1 ON (derivacion.TIP_MUE_EVE_ID = tipo_muestra_evento1.TIP_MUE_EVE_ID)
                  INNER JOIN evento evento_hijo ON (evento_hijo.EVE_ID = tipo_muestra_evento1.EVE_ID)
                  INNER JOIN evento_seccion esh ON evento_hijo.EVE_ID = esh.EVE_ID AND esh.ARE_ANA_ID = derivacion.MUE_ARE_ANA_ID
                  INNER JOIN area_analisis area_destino ON (esh.ARE_ANA_ID = area_destino.ARE_ANA_ID)                  
                  WHERE derivacion.mue_activa = 1 AND derivacion.MUE_TIPO=3 AND derivacion.MUE_RECHAZADA = 0 " . $extra . $filtro
                . ($config["area"] == 0 ? '' : ($config["area"] != "" ? " AND area_destino.ARE_ANA_ID = " . $config["area"] : ""))
                . ($config["evento"] == 0 ? '' : ($config["evento"] != "" ? " AND evento_hijo.EVE_ID = " . $config["evento"] : ""))
                . ($config["global_desde"] != "" ? " AND derivacion.MUE_CODIGO_GLOBAL_NUMERO >= " . $config["global_desde"] : "")
                . ($config["global_hasta"] != "" ? " AND derivacion.MUE_CODIGO_GLOBAL_NUMERO <= " . $config["global_hasta"] : "")
                . ($config["correlativo_desde"] != "" ? " AND derivacion.MUE_CODIGO_CORRELATIVO_NUMERO >= " . $config["correlativo_desde"] : "")
                . ($config["correlativo_hasta"] != "" ? " AND derivacion.MUE_CODIGO_CORRELATIVO_NUMERO <= " . $config["correlativo_hasta"] : "")
                . ($config["toma_desde"] != "" ? " AND derivacion.MUE_FECHA_TOMA >= '" . $conn->scapeString(helperString::toDate($config["toma_desde"])) . "'" : "")
                . ($config["toma_hasta"] != "" ? " AND derivacion.MUE_FECHA_TOMA <= '" . $conn->scapeString(helperString::toDate($config["toma_hasta"])) . "'" : "")
                . ($config["recepcion_desde"] != "" ? " AND derivacion.MUE_FECHA_RECEPCION >= '" . $conn->scapeString(helperString::toDate($config["recepcion_desde"])) . "'" : "")
                . ($config["recepcion_hasta"] != "" ? " AND derivacion.MUE_FECHA_RECEPCION <= '" . $conn->scapeString(helperString::toDate($config["recepcion_hasta"])) . "'" : "")
                . " " . $filtroUbicaciones . " "
                . " order by derivacion.MUE_CODIGO_GLOBAL_ANIO ASC, derivacion.MUE_CODIGO_GLOBAL_NUMERO ASC, derivacion.MUE_CODIGO_CORRELATIVO_ALFA ASC, derivacion.MUE_CODIGO_CORRELATIVO_NUMERO ASC"
                . " limit " . $config["inicio"] . "," . $config["paginado"];

        //echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getCountAllDerivacionesOrigen($config, $extra, $areas) {

        // Filtrar los resultados de búsquedas por permisos de procedencia
        // según división sanitaria del país
        $lista = clsCaus::obtenerUbicacionesCascada();
        if (is_array($lista)) {
            foreach ($lista as $elemento) {
                $temporal = "";
                if ($elemento[ConfigurationCAUS::Provincia] != "")
                    $temporal .= "muestra.MUE_PROC_PROVINCIA = '" . $elemento[ConfigurationCAUS::Provincia] . "' ";

                if ($elemento[ConfigurationCAUS::Region] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.MUE_PROC_REGION = '" . $elemento[ConfigurationCAUS::Region] . "' ";

                if ($elemento[ConfigurationCAUS::Distrito] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.MUE_PROC_DISTRITO = '" . $elemento[ConfigurationCAUS::Distrito] . "' ";

                if ($elemento[ConfigurationCAUS::Corregimiento] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.MUE_PROC_CORREGIMIENTO = '" . $elemento[ConfigurationCAUS::Corregimiento] . "' ";

                if ($elemento[ConfigurationCAUS::Instalacion] != "")
                    $temporal .= ($temporal != '' ? "and " : "") . "muestra.MUE_PROC_INST_SALUD = '" . $elemento[ConfigurationCAUS::Instalacion] . "' ";

                $filtroUbicaciones .= ($filtroUbicaciones != '' ? "or " : "") . "(" . $temporal . ") ";
            }
        }

        if ($filtroUbicaciones != "")
            $filtroUbicaciones = "and (" . $filtroUbicaciones . ")";

        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = "SELECT count(*)
                FROM
                  muestra derivacion
                  INNER JOIN tipo_muestra_evento ON (derivacion.TIP_MUE_EVE_ID = tipo_muestra_evento.TIP_MUE_EVE_ID)                  
                  INNER JOIN evento ON (tipo_muestra_evento.EVE_ID = evento.EVE_ID)
                  INNER JOIN evento_seccion es ON evento.EVE_ID = es.EVE_ID AND es.ARE_ANA_ID = derivacion.MUE_ARE_ANA_ID
                  INNER JOIN area_analisis ON (es.ARE_ANA_ID = area_analisis.ARE_ANA_ID)                  
                  INNER JOIN tipo_muestra_evento tipo_muestra_evento1 ON (derivacion.TIP_MUE_EVE_ID = tipo_muestra_evento1.TIP_MUE_EVE_ID)
                  INNER JOIN evento evento_hijo ON (evento_hijo.EVE_ID = tipo_muestra_evento1.EVE_ID)
                  INNER JOIN evento_seccion esh ON evento_hijo.EVE_ID = esh.EVE_ID AND esh.ARE_ANA_ID = derivacion.MUE_ARE_ANA_ID
                  INNER JOIN area_analisis area_destino ON (esh.ARE_ANA_ID = area_destino.ARE_ANA_ID)                  
                  WHERE derivacion.mue_activa = 1 AND derivacion.MUE_TIPO=3 AND derivacion.MUE_RECHAZADA = 0 " . $extra . $filtro
                . ($config["area"] == 0 ? '' : ($config["area"] != "" ? " AND area_destino.ARE_ANA_ID = " . $config["area"] : ""))
                . ($config["evento"] == 0 ? '' : ($config["evento"] != "" ? " AND evento_hijo.EVE_ID = " . $config["evento"] : ""))
                . ($config["global_desde"] != "" ? " AND derivacion.MUE_CODIGO_GLOBAL_NUMERO >= " . $config["global_desde"] : "")
                . ($config["global_hasta"] != "" ? " AND derivacion.MUE_CODIGO_GLOBAL_NUMERO <= " . $config["global_hasta"] : "")
                . ($config["correlativo_desde"] != "" ? " AND derivacion.MUE_CODIGO_CORRELATIVO_NUMERO >= " . $config["correlativo_desde"] : "")
                . ($config["correlativo_hasta"] != "" ? " AND derivacion.MUE_CODIGO_CORRELATIVO_NUMERO <= " . $config["correlativo_hasta"] : "")
                . ($config["toma_desde"] != "" ? " AND derivacion.MUE_FECHA_TOMA >= '" . $conn->scapeString(helperString::toDate($config["toma_desde"])) . "'" : "")
                . ($config["toma_hasta"] != "" ? " AND derivacion.MUE_FECHA_TOMA <= '" . $conn->scapeString(helperString::toDate($config["toma_hasta"])) . "'" : "")
                . ($config["recepcion_desde"] != "" ? " AND derivacion.MUE_FECHA_RECEPCION >= '" . $conn->scapeString(helperString::toDate($config["recepcion_desde"])) . "'" : "")
                . ($config["recepcion_hasta"] != "" ? " AND derivacion.MUE_FECHA_RECEPCION <= '" . $conn->scapeString(helperString::toDate($config["recepcion_hasta"])) . "'" : "")
                . " " . $filtroUbicaciones . " "
                . " order by derivacion.MUE_CODIGO_GLOBAL_ANIO ASC, derivacion.MUE_CODIGO_GLOBAL_NUMERO ASC, derivacion.MUE_CODIGO_CORRELATIVO_ALFA ASC, derivacion.MUE_CODIGO_CORRELATIVO_NUMERO ASC";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data[0];
    }

    public static function getMetodologias($prueba) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = " SELECT
                 pm.*, m.descripcion as metodologia
                 FROM
                    prueba_metodologia pm 
                    inner join metodologia m on m.idmetodologia = pm.idmetodologia
                WHERE PRU_ID=" . $prueba;

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getMuestras($config) {
        $filtro = ($config["nombre"] != "" ? " AND CONCAT_WS(' ',ind.IND_PRIMER_NOMBRE, ind.IND_SEGUNDO_NOMBRE)  LIKE '%" . $config["nombre"] . "%'" : "");
        $filtro .= ($config["apellido"] != "" ? " AND CONCAT_WS(' ',ind.IND_PRIMER_APELLIDO, ind.IND_SEGUNDO_APELLIDO)  LIKE '%" . $config["apellido"] . "%'" : "");
        $filtro .= ($config["identificador"] != "" ? " AND ind.IND_IDENTIFICADOR LIKE '%" . $config["identificador"] . "%'" : "");

        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $sql = 'select mue.MUE_ID as "id_muestra", 
                mue.MUE_CODIGO_GLOBAL_ANIO as "codigo_global_anio",
                mue.MUE_CODIGO_GLOBAL_NUMERO as "codigo_global_num",
                mue.MUE_CODIGO_CORRELATIVO_ALFA as "correlativo_alfa", 
                mue.MUE_CODIGO_CORRELATIVO_NUMERO as "correlativo_num",
                CONCAT_WS(" ",ind.IND_PRIMER_NOMBRE, ind.IND_SEGUNDO_NOMBRE,ind.IND_PRIMER_APELLIDO, ind.IND_SEGUNDO_APELLIDO) as "nombre",
                ind.IND_IDENTIFICADOR as "identificacion",
                ind.IND_SEXO as "sexo",
                IFNULL(DATE_FORMAT(mue.MUE_FECHA_TOMA,"%d-%m-%Y")," ") as "fecha_toma", 
                IFNULL(DATE_FORMAT(mue.MUE_FECHA_RECEPCION,"%d-%m-%Y")," ") as "fecha_recepcion"
                from muestra mue
                inner join individuo ind ON mue.IND_ID = ind.IND_ID
                inner join tipo_muestra_evento tme ON mue.TIP_MUE_EVE_ID = tme.TIP_MUE_EVE_ID
               where tme.EVE_ID = "61" and mue.MUE_ARE_ANA_ID = "7" ' . $filtro . ' order by mue.MUE_CODIGO_GLOBAL_NUMERO DESC '
                . " limit " . $config["inicio"] . "," . $config["paginado"];
        
 //where tme.EVE_ID = "24" and mue.MUE_ARE_ANA_ID = "6" ' . $filtro . ' order by mue.MUE_CODIGO_GLOBAL_NUMERO DESC '
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getMuestrasCantidad($config) {
        $filtro = ($config["nombre"] != "" ? " AND CONCAT_WS(' ',ind.IND_PRIMER_NOMBRE, ind.IND_SEGUNDO_NOMBRE)  LIKE '%" . $config["nombre"] . "%'" : "");
        $filtro .= ($config["apellido"] != "" ? " AND CONCAT_WS(' ',ind.IND_PRIMER_APELLIDO, ind.IND_SEGUNDO_APELLIDO)  LIKE '%" . $config["apellido"] . "%'" : "");
        $filtro .= ($config["identificador"] != "" ? " AND ind.IND_IDENTIFICADOR LIKE '%" . $config["identificador"] . "%'" : "");

        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $sql = 'select count(*) as total from muestra mue
                inner join individuo ind ON mue.IND_ID = ind.IND_ID
                inner join tipo_muestra_evento tme ON mue.TIP_MUE_EVE_ID = tme.TIP_MUE_EVE_ID
                where tme.EVE_ID = "61" and mue.MUE_ARE_ANA_ID = "7" ' . $filtro;

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }

    public static function getMuestrasVIH($config) {
        $filtro = ($config["nombre"] != "" ? " AND CONCAT_WS(' ',ind.IND_PRIMER_NOMBRE, ind.IND_SEGUNDO_NOMBRE)  LIKE '%" . $config["nombre"] . "%'" : "");
        $filtro .= ($config["apellido"] != "" ? " AND CONCAT_WS(' ',ind.IND_PRIMER_APELLIDO, ind.IND_SEGUNDO_APELLIDO)  LIKE '%" . $config["apellido"] . "%'" : "");
        $filtro .= ($config["identificador"] != "" ? " AND ind.IND_IDENTIFICADOR LIKE '%" . $config["identificador"] . "%'" : "");

        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $sql = 'select mue.MUE_ID as "id_muestra", 
                mue.MUE_CODIGO_GLOBAL_ANIO as "codigo_global_anio",
                mue.MUE_CODIGO_GLOBAL_NUMERO as "codigo_global_num",
                mue.MUE_CODIGO_CORRELATIVO_ALFA as "correlativo_alfa", 
                mue.MUE_CODIGO_CORRELATIVO_NUMERO as "correlativo_num",
                CONCAT_WS(" ",ind.IND_PRIMER_NOMBRE, ind.IND_SEGUNDO_NOMBRE,ind.IND_PRIMER_APELLIDO, ind.IND_SEGUNDO_APELLIDO) as "nombre",
                ind.IND_IDENTIFICADOR as "identificacion",
                ind.IND_SEXO as "sexo",
                IFNULL(DATE_FORMAT(mue.MUE_FECHA_TOMA,"%d-%m-%Y")," ") as "fecha_toma", 
                IFNULL(DATE_FORMAT(mue.MUE_FECHA_RECEPCION,"%d-%m-%Y")," ") as "fecha_recepcion",
                sit.SIT_NOMBRE as "nombre_situacion",
                sit.SIT_ID as "id_situacion"
                from muestra mue
                inner join individuo ind ON mue.IND_ID = ind.IND_ID
                inner join tipo_muestra_evento tme ON mue.TIP_MUE_EVE_ID = tme.TIP_MUE_EVE_ID
                Left join situacion sit ON mue.SIT_ID = sit.SIT_ID
                where tme.EVE_ID = "49" and mue.MUE_ARE_ANA_ID = "8" ' . $filtro .' order by mue.MUE_CODIGO_GLOBAL_NUMERO DESC '
                . " limit " . $config["inicio"] . "," . $config["paginado"];
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function getMuestrasCantidadVIH($config) {
        $filtro = ($config["nombre"] != "" ? " AND CONCAT_WS(' ',ind.IND_PRIMER_NOMBRE, ind.IND_SEGUNDO_NOMBRE)  LIKE '%" . $config["nombre"] . "%'" : "");
        $filtro .= ($config["apellido"] != "" ? " AND CONCAT_WS(' ',ind.IND_PRIMER_APELLIDO, ind.IND_SEGUNDO_APELLIDO)  LIKE '%" . $config["apellido"] . "%'" : "");
        $filtro .= ($config["identificador"] != "" ? " AND ind.IND_IDENTIFICADOR LIKE '%" . $config["identificador"] . "%'" : "");

        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $sql = 'select count(*) as total from muestra mue
                inner join individuo ind ON mue.IND_ID = ind.IND_ID
                inner join tipo_muestra_evento tme ON mue.TIP_MUE_EVE_ID = tme.TIP_MUE_EVE_ID
                where tme.EVE_ID = "49" and mue.MUE_ARE_ANA_ID = "8" ' . $filtro;

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }

    public static function buscarPersonas($config) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $flag = 0;

        foreach ($config as $valor) {
            if ($valor != "")
                $flag++;
        }

        //MAPEO
        //tipo_identificacion numero_identificacion primer_nombre segundo_nombre primer_apellido segundo_apellido edad tipo_edad sexo 

        if ($flag == 0)
            $sql = "select "
                    . "IND_IDENTIFICADOR_TIPO as tipo_identificacion, "
                    . "IND_IDENTIFICADOR as numero_identificacion, "
                    . "IND_PRIMER_NOMBRE as primer_nombre, "
                    . "IND_SEGUNDO_NOMBRE as segundo_nombre, "
                    . "IND_PRIMER_APELLIDO as primer_apellido, "
                    . "IND_SEGUNDO_APELLIDO as segundo_apellido, "
                    . "IND_EDAD as edad, "
                    . "IND_TIPO_EDAD as tipo_edad, "
                    . "IND_SEXO as sexo "
                    . " from individuo where IND_IDENTIFICADOR_TIPO IS NOT NULL and IND_IDENTIFICADOR IS NOT NULL "
                    . ' order by ind_es_humano asc, ind_primer_nombre desc'
                    . " limit " . $config["inicio"] . "," . $config["paginado"];
        else {
            $sql = "select "
                    . "IND_IDENTIFICADOR_TIPO as tipo_identificacion, "
                    . "IND_IDENTIFICADOR as numero_identificacion, "
                    . "IND_PRIMER_NOMBRE as primer_nombre, "
                    . "IND_SEGUNDO_NOMBRE as segundo_nombre, "
                    . "IND_PRIMER_APELLIDO as primer_apellido, "
                    . "IND_SEGUNDO_APELLIDO as segundo_apellido, "
                    . "IND_EDAD as edad, "
                    . "IND_TIPO_EDAD as tipo_edad, "
                    . "IND_SEXO as sexo "
                    . " from individuo where IND_IDENTIFICADOR_TIPO IS NOT NULL and IND_IDENTIFICADOR IS NOT NULL "
                    . ($config["identificador"] != "" ? " AND ind_identificador  LIKE '%" . $config["identificador"] . "%'" : "")
                    . ($config["nombre"] != "" ? " AND CONCAT(ind_primer_nombre,' ', ind_segundo_nombre)  LIKE '%" . $config["nombre"] . "%'" : "")
                    . ($config["apellido"] != "" ? " AND CONCAT(ind_primer_apellido,' ', ind_segundo_apellido)  LIKE '%" . $config["apellido"] . "%'" : "")
                    . ($config["edad_desde"] != "" ? " AND ind_edad >= " . $conn->scapeString($config["edad_desde"]) : "")
                    . ($config["edad_hasta"] != "" ? " AND ind_edad <= " . $conn->scapeString($config["edad_hasta"]) : "")
                    . ($config["tipo_edad"] == '' ? " " : " AND ind_tipo_edad = " . $config["tipo_edad"])
                    . ($config["sexo"] != "" ? " AND ind_sexo = '" . $config["sexo"] . "'" : "")
                    . ' order by ind_es_humano asc, ind_primer_nombre desc'
                    . " limit " . $config["inicio"] . "," . $config["paginado"];
        }
//        echo $sql;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }

    public static function buscarPersonasCantidad($config) {
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();

        $sql = "select count(*) as total from individuo where IND_IDENTIFICADOR_TIPO IS NOT NULL and IND_IDENTIFICADOR IS NOT NULL "
                . ($config["identificador"] != "" ? " AND ind_identificador  LIKE '%" . $config["identificador"] . "%'" : "")
                . ($config["nombre"] != "" ? " AND CONCAT(ind_primer_nombre,' ', ind_segundo_nombre)  LIKE '%" . $config["nombre"] . "%'" : "")
                . ($config["apellido"] != "" ? " AND CONCAT(ind_primer_apellido,' ', ind_segundo_apellido)  LIKE '%" . $config["apellido"] . "%'" : "")
                . ($config["edad_desde"] != "" ? " AND ind_edad >= " . $conn->scapeString($config["edad_desde"]) : "")
                . ($config["edad_hasta"] != "" ? " AND ind_edad <= " . $conn->scapeString($config["edad_hasta"]) : "")
                . ($config["tipo_edad"] == '' ? " " : " AND ind_tipo_edad = " . $config["tipo_edad"])
                . ($config["sexo"] != "" ? " AND ind_sexo = '" . $config["sexo"] . "'" : "");

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetchOne();
        $conn->closeConn();
        return $data["total"];
    }
    
    public static function getDatosPersona($id, $tipoId) {
        $sql = "SELECT * FROM individuo
             WHERE IND_IDENTIFICADOR = '".$id."' and IND_IDENTIFICADOR_TIPO = '".$tipoId."'";
//        echo $sql;
        $conn = new ConnectionSilabRemoto();
        $conn->initConn();
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    
}