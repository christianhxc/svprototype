<?php

require_once ('libs/Configuration.php');
require_once ('libs/caus/clsCaus.php');
require_once ('libs/caus/ConfigurationCAUS.php');
require_once ('libs/helper/helperString.php');

// Validar si ya ha iniciado sesion
if (!clsCaus::validarSession()) {
    header("location: ../../login.php");
    exit;
}
$filtro = $_REQUEST["f"];

require_once("libs/dal/uceti/dalVistasRevelac.php");

$params["filtro"] = $filtro;

$objVista = new dalVistasRevelac($params);
$data = $objVista->getData();

//print_r($data);
if (sizeof($data) != 0) {
    download_send_headers("reporteRevelac_" . date('Ymd') . "-" . date('His') . ".csv");
    echo array2csv($data);
    die();
} else {
    echo "No hay datos para esta consulta, intentelo con otro filtro";
}

function array2csv(array &$array) {
    if (count($array) == 0) {
        return null;
    }
    ob_start();
    $df = fopen("php://output", 'w');
    fputcsv($df, array_keys(reset($array)));
    foreach ($array as $row) {
        fputcsv($df, $row);
    }
    fclose($df);
    return ob_get_clean();
}

function download_send_headers($filename) {
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download  
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}