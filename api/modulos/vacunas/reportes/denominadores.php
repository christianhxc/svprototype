<?php
require_once('libs/dal/vacunas/dalVacunas.php');
require_once('libs/ApiUtils.php');
require_once('libs/Connection.php');
require_once('libs/caus/clsCaus.php');
if (!clsCaus::validarSession()){
    header("location: ".Configuration::getUrlprefix()."login.php");
}

header('Content-Type: application/json');

$conn = new Connection();
$conn->initConn();
$conn->begin();

$sql = "SELECT id_rango as id_grupo_edad, nombre_rango as descripcion, limite_inferior_meses, limite_superior_meses, 1 as status, null as id_grupo_especial FROM sisvigdb.cat_vac_rango ";
$sql .= " UNION ";
$sql .= "SELECT null as id_grupo_edad, concat(nombre_condicion, ' (Grupo Especial)') as descripcion, 0 as limite_inferior_meses, 0 as limite_superior_meses, status, id_condicion as id_grupo_esp FROM sisvigdb.cat_vac_condicion";

$data = dalVacunas::selectQuery($conn, $sql);

echo json_encode(ApiUtils::utf8_encode_all($data));