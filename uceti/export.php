<?php
require_once ('libs/dal/uceti/dalUceti.php');

$ok = true;
$conn = new Connection();
$conn->initConn();
//$conn->begin();

$data = dalUceti::GetMuestras($conn);
dalUceti::ProcesarResultadoFinal($data, $conn, true);

/*if ($ok)
    $conn->commit();
else {
    $conn->rollback();
}*/

$conn->closeConn();

