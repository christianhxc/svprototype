<?php

require_once('libs/dal/baseDal.php');
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/Actions.php');
require_once('libs/Pagineo.php');
require_once('libs/helper/helperString.php');
require_once('libs/helper/helperVacunas.php');
require_once('libs/caus/clsCaus.php');

class dalNotificacionVacunas extends baseDal {

    public static function LeerVacunas(){
        $conn = new Connection();
        $conn->initConn();

        $sql = "select
    t3.id_dosis,
    t2.id_vacuna,
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
        inner join
    vac_notificacion v_n ON v_n.id_vacuna = t2.id_vacuna
where
    t1.id_esquema = 2 and t2.id_vacuna = t1.id_vacuna and t3.tipo_dosis = 1 and v_n.id_notificacion_tipo = 1
order by t2.nombre_vacuna, t3.num_dosis_refuerzo";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }

    public static function LeerPorId($id){
        $conn = new Connection();
        $conn->initConn();

        $sql = "select n.id_notificacion, n.id_notificacion_contacto, n.id_notificacion_grupo, n.id_insumo, n.id_vacuna, n.id_notificacion_tipo,
                        n.no_lote, DATE_FORMAT(n.fecha_envio,'%d/%m/%Y') as fecha_envio, n.mensaje, n.anticipacion_dias, n.nombre, n.status,
                        nt.nombre as tipo, ng.nombre as grupo, nc.nombre as contacto,
                        CONCAT('(', ni.codigo_insumo, ') ', ni.nombre_insumo, ' - ', ni.unidad_presentacion) as insumo,
                        CONCAT('(', nv.codigo_vacuna, ') ', nv.nombre_vacuna) as vacuna
                from vac_notificacion n
                left join vac_notificacion_tipo nt on nt.id_notificacion_tipo = n.id_notificacion_tipo
                left join vac_notificacion_grupo ng on ng.id_notificacion_grupo = n.id_notificacion_grupo
                left join vac_notificacion_contacto nc on nc.id_notificacion_contacto = n.id_notificacion_contacto
                left join cat_insumos_LDBI ni on ni.id_insumo = n.id_insumo
                left join cat_vacuna nv on nv.id_vacuna = n.id_vacuna
                where n.id_notificacion = '".$id."' and n.deleted = 0 order by n.id_notificacion desc ";

        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        $conn->closeConn();

        return $data;
    }
}