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

// Validar que tenga acceso a esta seccion
//if (!clsCaus::validarSeccion(ConfigurationCAUS::Reportes, ConfigurationCAUS::Reportes)) {
//    header("location: ../index.php");
//    exit;
//}
//$search = $_POST["search"];
$search = $_REQUEST["search"];
//require_once(Configuration::javaAddress);

header("Content-type: application/vnd-ms-excel; charset=iso-8859-1");
header("Content-Disposition: attachment; filename=reporteVariables_" . date('Ymd') . "-" . date('His') . ".xls");

// Parametros para los reportes
$pFichaId = $search["fichaid"];

// Filtrar los datos de los reportes por ubicacion
$filtro = $_REQUEST["f"];

require_once("libs/dal/uceti/dalVistas.php");

//$params["ficha"] = $pFichaId;
$params["filtro"] = $filtro;
//$params["search"]["anio"] = $search["anio"];
//$params["search"]["desde"] = $search["semana_desde"];
//$params["search"]["hasta"] = $search["semana_hasta"];
//$objVista = new dalVistas($params);
//
//echo '<pre>';
//print_r($params);
//echo "</pre>";
//exit;

$objVista = new dalVistas($params);
$data = $objVista->getData();

// Se inicia la estructura de la tabla
$tabla = "<table border='1'>\n";

// Cabeceras con las columnas del Excel
$tabla .= "<tr>\n";
if (is_array($data["columnas"])) {
    foreach ($data["columnas"] as $columna) {
        if (!preg_match("/^rep_/i", $columna["Field"]))
            $tabla .= "<td bgcolor='#628529'><font color='#FFFFFF'>" . str_replace("_", " ", strtoupper($columna["Field"])) . "</font></td>\n";
    }
}
$tabla .= "</tr>\n";

// Contenido del archivo de Excel
if (is_array($data["data"])) {
    foreach ($data["data"] as $fila) {
        $tabla .= "<tr>\n";
        if (is_array($data["columnas"])) {
            foreach ($data["columnas"] as $columna) {
                if (preg_match("/^rep_/i", $columna["Field"]))
                    continue;
//                if ($columna["Type"] == "date") {
//                    $contenido = helperString::toDateView($fila[$columna["Field"]]);
//                } else {
                    $contenido = $fila[$columna["Field"]];
//                }

                $tabla .= "<td>" . $contenido . "</td>\n";
            }
        }
        $tabla .= "</tr>\n";
    }
}

$tabla .= "</table>\n";

// Imprime en pantalla el resultado para generar la tabla en Excel
echo $tabla;