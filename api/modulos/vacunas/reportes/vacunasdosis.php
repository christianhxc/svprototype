<?php
require_once('libs/dal/vacunas/dalVacunas.php');
require_once('libs/Connection.php');
require_once('libs/caus/clsCaus.php');
if (!clsCaus::validarSession()){
    header("location: ".Configuration::getUrlprefix()."login.php");
}

header('Content-Type: application/json');

$escenario = $_GET["escenario"];
$vacuna = $_GET["vacuna"];

$conn = new Connection();
$conn->initConn();
$conn->begin();

$sql = "select t2.id_vacuna, t2.nombre_vacuna, t3.id_dosis, t3.id_esq_detalle,
                            case when t3.tipo_dosis = 1 then 'Dosis' else 'Refuerzo' end as tipo_dosis, t3.edad_vac_ini,
                            case when t3.tipo_edad_vac_ini = 1 then 'Horas' when t3.tipo_edad_vac_ini = 2 then 'Dias' when
                            t3.tipo_edad_vac_ini = 3 then 'Semanas' when t3.tipo_edad_vac_ini = 4 then 'Meses' when
                            t3.tipo_edad_vac_ini = 5 then 'Años' else '' end as tipo_edad_vac_ini,
                            case when t3.edad_vac_fin is null then '' else t3.edad_vac_fin end as edad_vac_fin,
                            case when t3.tipo_edad_vac_fin = 1 then 'Horas' when t3.tipo_edad_vac_fin = 2 then 'Dias' when
                            t3.tipo_edad_vac_fin = 3 then 'Semanas' when t3.tipo_edad_vac_fin = 4 then 'Meses' when
                            t3.tipo_edad_vac_fin = 5 then 'Años' else '' end as tipo_edad_vac_fin,
                            t3.margen_vac_ini,
                            case when t3.tipo_margen_vac_ini = 1 then 'Dias' when
                            t3.tipo_margen_vac_ini = 2 then 'Semanas' when t3.tipo_margen_vac_ini = 3 then 'Meses' when
                            t3.tipo_margen_vac_ini = 4 then 'Años' else '' end as tipo_margen_vac_ini,
                            case when t3.margen_vac_fin is null then '' else t3.margen_vac_fin end as margen_vac_fin,
                            case when t3.tipo_margen_vac_fin = 1 then 'Dias' when
                            t3.tipo_margen_vac_fin = 2 then 'Semanas' when t3.tipo_margen_vac_fin = 3 then 'Meses' when
                            t3.tipo_margen_vac_fin = 4 then 'Años' else '' end as tipo_margen_vac_fin,
                            t3.num_dosis_refuerzo,
                            case when t3.repite_annio = 1 then 'Si' else 'No' end as repite_annio
                            from vac_esq_detalle t1
                            inner join cat_vacuna t2 on t1.id_vacuna = t2.id_vacuna
                            left join vac_dosis t3 on t1.id_esq_detalle = t3.id_esq_detalle
                            where t1.id_esquema = '".$escenario."' and t2.id_vacuna = '".$vacuna."' and t3.tipo_dosis = 1
                            order by t2.id_vacuna, t3.tipo_dosis, t3.num_dosis_refuerzo";

$data = dalVacunas::selectQuery($conn, $sql);

echo json_encode($data);