<?php
require_once ('libs/dal/ldbi/dalLdbi.php');
header('Content-type: application/json');

$config['numero'] = $_REQUEST["numero"];
$data = dalLdbi::buscarRequesicionPorNumero($config);

echo json_encode($data);