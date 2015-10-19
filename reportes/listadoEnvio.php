<?php
require_once ('libs/Configuration.php');

if(isset($_REQUEST["lista"]))
{
    require_once(Configuration::javaAddress);
    $compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
    $report = $compileManager->compileReport(realpath("Reporte_envio.jrxml"));
    $fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
    $params = new Java("java.util.HashMap");

    $params->put("IdEnvio", (int)$_REQUEST["lista"]);
    $url = Configuration::urlReport;

    $driverManager = new java("java.sql.DriverManager");
    $class = new JavaClass("java.lang.Class");
    $class->forName("com.mysql.jdbc.Driver");

    $conn = $driverManager -> getConnection($url.Configuration::dbReport);
    $jasperPrint = $fillManager->fillReport($report, $params, $conn);
    
    $outputPath = realpath(".")."/"."output.doc";
    $exporter = new Java("net.sf.jasperreports.engine.export.JRRtfExporter");
    $parametros = new Java("net.sf.jasperreports.engine.export.JRTextExporterParameter");
    $exporter->setParameter($parametros->JASPER_PRINT, $jasperPrint);
    $exporter->setParameter($parametros->OUTPUT_FILE_NAME, $outputPath);
    $exporter->exportReport();

    header("Content-type: application/vnd-ms-word; charset=iso-8859-1");
    header("Content-Disposition: attachment; filename=reporte-word_".date('d-m-Y').".doc");
//    
//    $outputPath = realpath(".")."/"."output.pdf";
//    $exportManager = new JavaClass("net.sf.jasperreports.engine.JasperExportManager");
//    $exportManager->exportReportToPdfFile($jasperPrint, $outputPath);
//    header("Content-type: application/pdf");

    readfile($outputPath);
    unlink($outputPath);
}
?>
