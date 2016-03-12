<?php
require_once('libs/dal/baseDal.php');
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperVacunas.php');
require_once('libs/caus/clsCaus.php');

class dalReporteCobertura extends baseDal {

    public static function ObtieneConfiguraciones($configurarionId){
        $conn = new Connection();
        $conn->initConn();

        $sql = "SELECT cv.id_vacuna, cv.nombre_vacuna, vrdrcv.id_dosis, CONCAT( if( vd.tipo_dosis =1, 'Dosis', 'Refuerzo' ) , ' ', vd.num_dosis_refuerzo ) AS dosis, vrdrc.* FROM
                    vac_registro_diario_reporte_configuracion vrdrc,
                    vac_registro_diario_reporte_configuracion_vacuna vrdrcv,
                    cat_vacuna cv,
                    vac_dosis vd
                WHERE
                    vrdrcv.id_configuracion = ".$configurarionId."
                    AND vrdrcv.id_vacuna = cv.id_vacuna
                    AND vrdrc.id_configuracion = vrdrcv.id_configuracion
                    AND vd.id_dosis = vrdrcv.id_dosis";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    public static function ObtieneDatosConfiguraciones($filters, $vacunas, $dosis, $grupoEdad){
        $conn = new Connection();
        $filters = self::getFormatDate($filters);
        $conn->initConn();
        $columnsForSelect = self::getColumnsForSelect($filters);
        $columnsForSelect = $columnsForSelect["globalFilters"];
        $where = "";
        if($filters["fecini"] != ""){
            $where .= "AND v_r_d_d.fecha_dosis >= '".$filters["fecini"]."' ";
        }
        if($filters["fecfin"] != ""){
            $where .= "AND v_r_d_d.fecha_dosis <= '".$filters["fecfin"]."' ";
        }
        $sql = "SELECT
                c_g_e.nombre_rango as grupoedad,".$columnsForSelect["from"]."
                c_v.id_vacuna,
                c_v.nombre_vacuna,
                v_do.id_dosis,
                CONCAT( if( v_do.tipo_dosis =1, 'Dosis', 'Refuerzo' ) , ' ', v_do.num_dosis_refuerzo ) AS dosis,
                COUNT(1) as total
                FROM (((((((((((vac_registro_diario v_r_d LEFT JOIN  vac_registro_diario_dosis v_r_d_d ON  v_r_d.id_vac_registro_diario =  v_r_d_d.id_vac_registro_diario)
                LEFT JOIN vac_denominador v_d ON v_r_d_d.id_un = v_d.id_un)
                LEFT JOIN vac_denominador_detalle v_d_d ON v_d.id_vac_denominador  = v_d_d.id_vac_denominador)
                LEFT JOIN cat_vac_rango c_v_r ON v_r_d_d.id_rango = v_r_d_d.id_rango )
                LEFT JOIN vac_dosis v_do ON v_r_d_d.id_dosis = v_do.id_dosis)
                LEFT JOIN cat_vacuna c_v ON v_do.id_vacuna = c_v.id_vacuna)
                LEFT JOIN cat_vac_rango c_g_e ON v_r_d_d.id_rango = c_g_e.id_rango)
                LEFT JOIN cat_corregimiento c_c ON v_r_d.per_id_corregimiento = c_c.id_corregimiento)
                LEFT JOIN cat_distrito c_di ON c_di.id_distrito = c_c.id_distrito)
                LEFT JOIN cat_unidad_notificadora c_u_n ON v_r_d_d.id_un = c_u_n.id_un)
                LEFT JOIN cat_region_salud c_r_s ON c_u_n.id_region = c_r_s.id_region)
                LEFT JOIN cat_provincia c_p ON c_di.id_provincia = c_p.id_provincia ";
        if($filters["tipo"] == "Cobertura"){
            $sql .= ", vac_persona_condicion v_p_c ";
        }
        $sql .= "WHERE
                v_do.tipo_dosis = 1 ";
        if($filters["tipo"] == "Cobertura"){
            $sql .= "AND v_r_d.numero_identificacion = v_p_c.numero_identificacion AND v_r_d.tipo_identificacion = v_p_c.tipo_identificacion ";
        }
        $sql .= "AND c_g_e.id_rango = ".$grupoEdad."
                AND c_v.id_vacuna IN(".$vacunas.")
                AND v_do.id_dosis IN(".$dosis.")
                ".$columnsForSelect["where"]." ".$where."
                GROUP BY ".$columnsForSelect["groupby"]."c_g_e.id_rango, c_v.id_vacuna, v_do.id_dosis";
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    public static function getDenominador($filters, $grupoEdad){
        $conn = new Connection();
        $conn->initConn();
        $filters = self::getFormatDate($filters);
        if($filters["fecini"] != "") list($yearIni, $month, $day) = explode('-', $filters["fecini"]);
        if($filters["fecfin"] != "") list($yearFin, $month, $day) = explode('-', $filters["fecini"]);
        $columnsForSelect = self::getColumnsForSelect($filters);
        $columnsForSelect = $columnsForSelect["denominadorFilters"];
        $where = "";
        if($yearIni != ""){
            $where .= "AND v_d.anio >= '".$yearIni."' ";
        }
        if($yearIni != ""){
            $where .= "AND v_d.anio <= '".$yearFin."' ";
        }
        $sql = "select c_g_e.nombre_rango, v_d_d.id_grupo_rango, ".$columnsForSelect["from"].", sum(v_d_d.num_hombre+v_d_d.num_mujer) as denominador  from
                vac_denominador v_d,
                vac_denominador_detalle v_d_d,
                cat_vac_rango  c_g_e
                WHERE v_d_d.id_vac_denominador = v_d.id_vac_denominador
                AND c_g_e.id_rango = v_d_d.id_grupo_rango
                AND v_d_d.id_grupo_rango = ".$filters["nivel"]." ".$columnsForSelect["where"]." ".$where."
                GROUP BY ".$columnsForSelect["groupby"];
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    public static function getGrupoEdadById($id){
        $conn = new Connection();
        $conn->initConn();
        $sql = "select *  from cat_vac_rango  c_g_e
                WHERE c_g_e.id_rango = ".$id." " ;
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();
        return $data;
    }
    public static function ObtieneDatosVacunas($filters, $vacunas, $dosis, $grupoEdad){
        $conn = new Connection();
        $filters = self::getFormatDate($filters);
        $conn->initConn();
        $columnsForSelect = self::getColumnsForSelect($filters);
        $columnsForSelect = $columnsForSelect["globalFilters"];
        $where = "";
        if($filters["tipocorte"] == 'alcierre'){
            $where .= "AND v_r_d_d.cierre = 1 ";
        }else{
            $where .= "AND v_r_d_d.cierre = 0 ";
        }
        if($filters["fecini"] != ""){
            $where .= "AND v_r_d_d.fecha_dosis >= '".$filters["fecini"]."' ";
        }
        if($filters["fecfin"] != ""){
            $where .= "AND v_r_d_d.fecha_dosis <= '".$filters["fecfin"]."' ";
        }
        $sql = "SELECT
                c_g_e.nombre_rango as grupoedad,".$columnsForSelect["from"]."
                c_v.id_vacuna,
                c_v.nombre_vacuna,
                v_do.id_dosis,
                v_r_d_d.fecha_dosis,
                v_r_d_d.id_zona,
                CONCAT( if( v_do.tipo_dosis =1, 'Dosis', 'Refuerzo' ) , ' ', v_do.num_dosis_refuerzo ) AS dosis,
                t_p.*
                FROM ((((((((((((vac_registro_diario v_r_d LEFT JOIN  vac_registro_diario_dosis v_r_d_d ON  v_r_d.id_vac_registro_diario =  v_r_d_d.id_vac_registro_diario)
                LEFT JOIN vac_denominador v_d ON v_r_d_d.id_un = v_d.id_un)
                LEFT JOIN vac_denominador_detalle v_d_d ON v_d.id_vac_denominador  = v_d_d.id_vac_denominador)
                LEFT JOIN cat_vac_rango c_v_r ON v_r_d_d.id_rango = v_r_d_d.id_rango )
                LEFT JOIN vac_dosis v_do ON v_r_d_d.id_dosis = v_do.id_dosis)
                LEFT JOIN cat_vacuna c_v ON v_do.id_vacuna = c_v.id_vacuna)
                LEFT JOIN cat_vac_rango  c_g_e ON v_r_d_d.id_rango = c_g_e.id_rango)
                LEFT JOIN cat_corregimiento c_c ON v_r_d.per_id_corregimiento = c_c.id_corregimiento)
                LEFT JOIN cat_distrito c_di ON c_di.id_distrito = c_c.id_distrito)
                LEFT JOIN cat_unidad_notificadora c_u_n ON v_r_d_d.id_un = c_u_n.id_un)
                LEFT JOIN cat_region_salud c_r_s ON c_u_n.id_region = c_r_s.id_region)
                LEFT JOIN cat_provincia c_p ON c_di.id_provincia = c_p.id_provincia)
                LEFT JOIN tbl_persona t_p ON t_p.numero_identificacion = v_r_d.numero_identificacion
                WHERE
                v_do.tipo_dosis = 1
                AND c_g_e.id_rango = ".$grupoEdad." ".$columnsForSelect["where"]." ".$where."
                GROUP BY v_r_d.id_vac_registro_diario ";
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    public static function getPacientesVacunas($filters, $grupoEdad){
        $conn = new Connection();
        $filters = self::getFormatDate($filters);
        $conn->initConn();
        $columnsForSelect = self::getColumnsForSelect($filters);
        $columnsForSelect = $columnsForSelect["globalFilters"];
        $where = "";
        if($filters["fecini"] != ""){
            $where .= "AND v_r_d_d.fecha_dosis >= '".$filters["fecini"]."' ";
        }
        if($filters["fecfin"] != ""){
            $where .= "AND v_r_d_d.fecha_dosis <= '".$filters["fecfin"]."' ";
        }
//        $sql = "SELECT
//                c_g_e.nombre_rango as grupoedad,".$columnsForSelect["from"]."
//                v_r_d_d.per_edad * (
//                    case
//                        when v_r_d_d.per_tipo_edad = 1 then 1
//                        when v_r_d_d.per_tipo_edad = 2 then 1 * 24
//                        when v_r_d_d.per_tipo_edad = 3 then 1 * 24 * 7
//                        when v_r_d_d.per_tipo_edad = 4 then 1 * 24 * 7 * 30
//                        when v_r_d_d.per_tipo_edad = 5 then 1 * 24 * 7 * 365
//                        else 1
//                    end) as edad_vac_horas,
//                v_r_d_d.*,
//                t_p.*
//                FROM ((((((((((((vac_registro_diario v_r_d LEFT JOIN  vac_registro_diario_dosis v_r_d_d ON  v_r_d.id_vac_registro_diario =  v_r_d_d.id_vac_registro_diario)
//                LEFT JOIN vac_denominador v_d ON v_r_d_d.id_un = v_d.id_un)
//                LEFT JOIN vac_denominador_detalle v_d_d ON v_d.id_vac_denominador  = v_d_d.id_vac_denominador)
//                LEFT JOIN cat_vac_rango c_v_r ON v_r_d_d.id_rango = v_r_d_d.id_rango )
//                LEFT JOIN vac_dosis v_do ON v_r_d_d.id_dosis = v_do.id_dosis)
//                LEFT JOIN cat_vacuna c_v ON v_do.id_vacuna = c_v.id_vacuna)
//                LEFT JOIN cat_vac_rango  c_g_e ON v_r_d.per_tipo_edad = c_g_e.id_rango)
//                LEFT JOIN cat_corregimiento c_c ON v_r_d.per_id_corregimiento = c_c.id_corregimiento)
//                LEFT JOIN cat_distrito c_di ON c_di.id_distrito = c_c.id_distrito)
//                LEFT JOIN cat_unidad_notificadora c_u_n ON v_r_d_d.id_un = c_u_n.id_un)
//                LEFT JOIN cat_region_salud c_r_s ON c_u_n.id_region = c_r_s.id_region)
//                LEFT JOIN cat_provincia c_p ON c_di.id_provincia = c_p.id_provincia)
//                LEFT JOIN tbl_persona t_p ON t_p.numero_identificacion = v_r_d.numero_identificacion
//                WHERE
//                c_g_e.id_rango = ".$grupoEdad." ".$columnsForSelect["where"]." ".$where;
        $sql = "select
        GROUP_CONCAT(v_r_d_d.id_dosis) as dosis_aplicadas,
TIMESTAMPDIFF(HOUR, t_p.fecha_nacimiento, NOW()) edad_vac_horas,
v_r_d_d.*,
t_p.*
FROM vac_registro_diario v_r_d, vac_registro_diario_dosis v_r_d_d, tbl_persona t_p
WHERE v_r_d.id_vac_registro_diario =  v_r_d_d.id_vac_registro_diario
AND t_p.numero_identificacion = v_r_d.numero_identificacion ".$where." GROUP BY t_p.numero_identificacion";
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    public static function getVacunasPorHora($filters, $grupoEdad){
        $conn = new Connection();
        $filters = self::getFormatDate($filters);
        $conn->initConn();
        $columnsForSelect = self::getColumnsForSelect($filters);
        $columnsForSelect = $columnsForSelect["globalFilters"];
        $where = "";
        if($filters["tipocorte"] == 'alcierre'){
            $where .= "AND v_r_d_d.cierre = 1 ";
        }else{
            $where .= "AND v_r_d_d.cierre = 0 ";
        }
        if($filters["fecini"] != ""){
            $where .= "AND v_r_d_d.fecha_dosis >= '".$filters["fecini"]."' ";
        }
        if($filters["fecfin"] != ""){
            $where .= "AND v_r_d_d.fecha_dosis <= '".$filters["fecfin"]."' ";
        }
        $sql = "select
    t3.id_dosis,
    t2.nombre_vacuna,
    t3.edad_vac_ini * (
    case
        when t3.tipo_edad_vac_ini = 1 then 1
        when t3.tipo_edad_vac_ini = 2 then 1 * 24
        when t3.tipo_edad_vac_ini = 3 then 1 * 24 * 7
        when t3.tipo_edad_vac_ini = 4 then 1 * 24 * 7 * 30
        when t3.tipo_edad_vac_ini = 5 then 1 * 24 * 7 * 365
        else 1
    end) as edad_vac_ini_horas,
    t3.edad_vac_fin * (
    case
        when t3.tipo_edad_vac_ini = 1 then 1
        when t3.tipo_edad_vac_ini = 2 then 1 * 24
        when t3.tipo_edad_vac_ini = 3 then 1 * 24 * 7
        when t3.tipo_edad_vac_ini = 4 then 1 * 24 * 7 * 30
        when t3.tipo_edad_vac_ini = 5 then 1 * 24 * 7 * 365
        else 1
    end) as edad_vac_fin_horas,
    t3.margen_vac_ini * (
    case
        when t3.tipo_margen_vac_ini = 1 then 24
        when t3.tipo_margen_vac_ini = 2 then 24 * 7
        when t3.tipo_margen_vac_ini = 3 then 24 * 7 * 30
        when t3.tipo_margen_vac_ini = 4 then 24 * 7 * 365
        else ''
    end) as margen_vac_ini_horas,
    t3.margen_vac_fin * (
    case
        when t3.tipo_margen_vac_ini = 1 then 24
        when t3.tipo_margen_vac_ini = 2 then 24 * 7
        when t3.tipo_margen_vac_ini = 3 then 24 * 7 * 30
        when t3.tipo_margen_vac_ini = 4 then 24 * 7 * 365
        else ''
    end) as margen_vac_fin_horas,
    t3.num_dosis_refuerzo,
    case
        when t3.repite_annio = 1 then 'Si'
        else 'No'
    end as repite_annio
from
    vac_esq_detalle t1
        inner join
    cat_vacuna t2 ON t1.id_vacuna = t2.id_vacuna
        left join
    vac_dosis t3 ON t1.id_esq_detalle = t3.id_esq_detalle
where
    t1.id_esquema = 2 and t2.id_vacuna = t1.id_vacuna and t3.tipo_dosis = 1
order by t2.nombre_vacuna, t3.num_dosis_refuerzo
";
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    public static function getDistrito(){
        $conn = new Connection();
        $conn->initConn();
        $sql = "select * from cat_distrito";
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    public static function getDenominadoresForReport($filters, $vacunas, $dosis, $grupoEdad){
        $conn = new Connection();
        $conn->initConn();
        $sql = "SELECT c_r_s.nombre_region,
                c_d.nombre_distrito,
                c_c.nombre_corregimiento,
                c_u_n.nombre_un,
                v_c.* FROM (((vac_denominador v_c
                LEFT JOIN cat_region_salud c_r_s ON c_r_s.id_region = v_c.id_region)
                LEFT JOIN cat_distrito c_d ON c_d.id_distrito = v_c.id_distrito)
                LEFT JOIN cat_corregimiento c_c ON c_c.id_corregimiento = v_c.id_corregimiento)
                LEFT JOIN cat_unidad_notificadora c_u_n ON c_u_n.id_un = v_c.id_un";
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    public static function getListadoPacientesRegistroDiario(){
        $conn = new Connection();
        $conn->initConn();
        $sql = "
select
c_r_s.nombre_region,
c_d.nombre_distrito,
'' as nombre_corregimiento,
'' as nombre_un,
c_v_z.nombre_zona,
'' as num_provisional,
t_p.numero_identificacion as id_paciente,
t_p.fecha_nacimiento,
'' as m,
'' as tipo_paciente,
'' as tipo,
t_p.sexo,
t_p.edad,
GROUP_CONCAT(v_r_d_d.id_dosis) as dosis,
GROUP_CONCAT(v_r_d_d.fecha_dosis) as fecha_dosis,
TIMESTAMPDIFF(HOUR, t_p.fecha_nacimiento, NOW()) edad_vac_horas
FROM vac_registro_diario v_r_d, vac_registro_diario_dosis v_r_d_d, tbl_persona t_p,
cat_region_salud c_r_s, cat_distrito c_d, cat_vac_zona c_v_z
WHERE v_r_d.id_vac_registro_diario =  v_r_d_d.id_vac_registro_diario
AND t_p.id_region = c_r_s.id_region
AND c_d.id_provincia = c_r_s.id_provincia
AND c_d.id_region = c_r_s.id_region
AND c_v_z.id_zona = v_r_d_d.id_zona
AND t_p.numero_identificacion = v_r_d.numero_identificacion
group by t_p.numero_identificacion";
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    public static function getListadoVacunasRegistroDiario(){
        $conn = new Connection();
        $conn->initConn();
        $sql = "SELECT c_v.id_vacuna,
                c_v.nombre_vacuna,
                GROUP_CONCAT(v_d.id_dosis) as dosis
                FROM cat_vacuna c_v, vac_dosis v_d
WHERE v_d.id_vacuna = c_v.id_vacuna
group by c_v.id_vacuna
order by c_v.nombre_vacuna";
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
    private static function getFormatDate($filters){
        if($filters["fecini"] != ""){
            list($day, $month, $year) = explode('/', $filters["fecini"]);
            $filters["fecini"] = $year."-".$month."-".$day;
        }
        if($filters["fecfin"] != ""){
            list($day, $month, $year) = explode('/', $filters["fecfin"]);
            $filters["fecfin"] = $year."-".$month."-".$day;
        }
        return $filters;
    }
    private static function getColumnsForSelect($filters){
        $filtersSql = array();
        $filtersDominador = array();
        switch ($filters["nivel"]) {
            case 1:
                return "Nacional";
            case 2:
                $filtersSql["from"] = "c_p.id_provincia as id_proregcor,
c_p.nombre_provincia as nombre_proregcor, ";
                $filtersSql["groupby"] = "c_p.id_provincia, ";

                $filtersDominador["from"] = "v_d.id_provincia as id_proregcor";
                $filtersDominador["groupby"] = "v_d.id_provincia";
                break;
            case 3:
                $filtersSql["from"] = "c_r_s.id_region as id_proregcor,
c_r_s.nombre_region as nombre_proregcor, ";
                $filtersSql["groupby"] = "c_r_s.id_region, ";

                $filtersDominador["from"] = "v_d.id_region as id_proregcor";
                $filtersDominador["groupby"] = "v_d.id_region";
                break;
            case 4:
                $filtersSql["from"] = "c_di.id_distrito as id_proregcor,
c_di.nombre_distrito as nombre_proregcor, ";
                $filtersSql["groupby"] = "c_di.id_distrito, ";

                $filtersDominador["from"] = "v_d.id_distrito as id_proregcor";
                $filtersDominador["groupby"] = "v_d.id_distrito";
                break;
            case 5:
                $filtersSql["from"] = "c_c.id_corregimiento as id_proregcor,
c_c.nombre_corregimiento as nombre_proregcor, ";
                $filtersSql["groupby"] = "c_c.id_corregimiento, ";

                $filtersDominador["from"] = "v_d.id_corregimiento as id_proregcor";
                $filtersDominador["groupby"] = "v_d.id_corregimiento";
                break;
            case 6:
                $filtersSql["from"] = "c_u_n.id_un as id_proregcor,
c_u_n.nombre_un as nombre_proregcor, ";
                $filtersSql["groupby"] = "c_u_n.id_un, ";

                $filtersDominador["from"] = "v_d.id_un as id_proregcor";
                $filtersDominador["groupby"] = "v_d.id_un";
                break;
        }
        $sqlwhere = "";
        $sqlwhereDenominador = "";
        if($filters["provincia"] > 0){
            $sqlwhere .= " AND c_p.id_provincia = ".$filters["provincia"]. " ";
            $sqlwhereDenominador .= " AND v_d.id_provincia = ".$filters["provincia"]. " ";
        }
        if($filters["region"] > 0){
            $sqlwhere .= " AND c_r_s.id_region = ".$filters["region"]. " ";
            $sqlwhereDenominador .= " AND v_d.id_region = ".$filters["region"]. " ";
        }
        if($filters["distrito"] > 0){
            $sqlwhere .= " AND c_di.id_distrito = ".$filters["distrito"]. " ";
            $sqlwhereDenominador .= " AND v_d.id_distrito = ".$filters["distrito"]. " ";
        }
        if($filters["corregimiento"] > 0){
            $sqlwhere .= " AND c_c.id_corregimiento = ".$filters["corregimiento"]. " ";
            $sqlwhereDenominador .= " AND v_d.id_corregimiento = ".$filters["corregimiento"]. " ";
        }
        if($filters["id_un"] > 0){
            $sqlwhere .= " AND c_u_n.id_un = ".$filters["id_un"]. " ";
            $sqlwhereDenominador .= " AND v_d.id_un = ".$filters["id_un"]. " ";
        }
        $filtersSql["where"] = $sqlwhere;
        $filtersDominador["where"] = $sqlwhereDenominador;
        return array("globalFilters" => $filtersSql, "denominadorFilters" => $filtersDominador);
    }
}