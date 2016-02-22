<?php
require_once('libs/dal/vacunas/dalVacunas.php');
require_once('libs/Connection.php');
require_once('libs/caus/clsCaus.php');
if (!clsCaus::validarSession()){
    header("location: ".Configuration::getUrlprefix()."login.php");
}

header('Content-Type: application/json');

$escenario = $_GET["escenario"];

$conn = new Connection();
$conn->initConn();
$conn->begin();

$sql = "select t1.id_esq_detalle, t2.id_vacuna, t2.nombre_vacuna, t3.total_dosis
                from vac_esq_detalle t1
                inner join cat_vacuna t2 on t1.id_vacuna = t2.id_vacuna
                left join
                (select count(id_dosis) as total_dosis, id_esq_detalle, id_vacuna
                from vac_dosis
                group by id_esq_detalle, id_vacuna
                ) t3 on t1.id_esq_detalle = t3.id_esq_detalle
                    and t1.id_vacuna = t3.id_vacuna
                where id_esquema = '".$escenario."' order by t2.nombre_vacuna";

$data = dalVacunas::selectQuery($conn, $sql);

echo json_encode($data);