<?php

require_once ('libs/Configuration.php');
require_once ('libs/caus/clsCaus.php');
require_once(Configuration::javaAddress);

if(isset($_REQUEST["f"]))
{    
    $compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
    $report = $compileManager->compileReport(realpath("vih_regiones.jrxml"));
    $fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
    $params = new Java("java.util.HashMap");
    
    $filtro = ($_REQUEST["f"] != "" ? $_REQUEST["f"] : "");
    $condicion = ($_REQUEST["c"] != "" ? $_REQUEST["c"] : "");
//    echo "Filtro: ".$filtro;
//    echo "<br/>Condicion: ".$condicion;exit;
    $params->put("filtro", $filtro);
    $params->put("condicion", $condicion);
    $url = Configuration::urlReport;

    $driverManager = new java("java.sql.DriverManager");
    $class = new JavaClass("java.lang.Class");
    $class->forName("com.mysql.jdbc.Driver");

    $conn = $driverManager -> getConnection($url.Configuration::dbReport);
    $jasperPrint = $fillManager->fillReport($report, $params, $conn);

    $outputPath = realpath(".")."/"."output.xls";
    $JRXlsExporter = new Java("net.sf.jasperreports.engine.export.JRXlsExporter");
    $JRXlsExporterParameter = new Java("net.sf.jasperreports.engine.export.JRXlsExporterParameter");
    $JRXlsExporter->setParameter($JRXlsExporterParameter->JASPER_PRINT, $jasperPrint);
    $JRXlsExporter->setParameter($JRXlsExporterParameter->OUTPUT_FILE_NAME, $outputPath);
    $JRXlsExporter->exportReport();
    header("Content-type: application/vnd-ms-excel; charset=iso-8859-1");
    header("Content-Disposition: attachment; filename=exportable_".date('d-m-Y').".xls");

    readfile($outputPath);
    unlink($outputPath);
}
?>

