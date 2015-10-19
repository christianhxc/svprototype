<?php
require_once ('libs/Configuration.php');

if(isset($_REQUEST["lista"]) && isset($_REQUEST["tip"])&& isset($_REQUEST["firma"]))
{
	require_once(Configuration::javaAddress);
    $compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
    $report = $compileManager->compileReport(realpath("reporte_individual_completo.jrxml"));
    $fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
    $params = new Java("java.util.HashMap");

    $params->put("muestra", (int)$_REQUEST["lista"]);
    $params->put("firma", $_REQUEST["firma"]);
    $url = Configuration::urlReport;

    $driverManager = new java("java.sql.DriverManager");
    $class = new JavaClass("java.lang.Class");
    $class->forName("com.mysql.jdbc.Driver");

    $conn = $driverManager -> getConnection($url.Configuration::dbReport);
    $jasperPrint = $fillManager->fillReport($report, $params, $conn);


    if($_REQUEST["tip"]==1)
    {
//        $outputPath = realpath(".")."/"."output.xls";
//        $JRXlsExporter = new Java("net.sf.jasperreports.engine.export.JRXlsExporter");
//        $JRXlsExporterParameter = new Java("net.sf.jasperreports.engine.export.JRXlsExporterParameter");
//        $JRXlsExporter->setParameter($JRXlsExporterParameter->JASPER_PRINT, $jasperPrint);
//        $JRXlsExporter->setParameter($JRXlsExporterParameter->OUTPUT_FILE_NAME, $outputPath);
//        $JRXlsExporter->exportReport();
        
        $outputPath = realpath(".")."/"."output.pdf";
        $exportManager = new JavaClass("net.sf.jasperreports.engine.JasperExportManager");
        $exportManager->exportReportToPdfFile($jasperPrint, $outputPath);
        header("Content-type: application/pdf");

//        header("Content-type: application/vnd-ms-excel; charset=iso-8859-1");
//        header("Content-Disposition: attachment; filename=Historial.".$_REQUEST["lista"]."-".date('d-m-Y').".xls");
    }

    readfile($outputPath);
    unlink($outputPath);
}
?>
