<?php
require_once ('libs/Configuration.php');

if(isset($_REQUEST["tipo"]))
{
	require_once(Configuration::javaAddress);
    $compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
    $report = $compileManager->compileReport(realpath("consolidados.jrxml"));

    $filtro = "";
    if ($_REQUEST["a"]!=0){
        $filtro = " and con.ARE_ANA_ID = ".$_REQUEST["a"];
        if ($_REQUEST["e"]!=0)
            $filtro.= " and con.EVE_ID = ".$_REQUEST["e"];
    }
    $sd = ($_REQUEST["sd"]==''?'0':$_REQUEST["sd"]);
    $sh = ($_REQUEST["sh"]==''?'53':$_REQUEST["sh"]);
    $ad = ($_REQUEST["ad"]==''?'0':$_REQUEST["ad"]);
    $ah = ($_REQUEST["ah"]==''?'2500':$_REQUEST["ah"]);

    $fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
    $params = new Java("java.util.HashMap");

    $params->put("semanaIni", (int)$sd);
    $params->put("semanaFin", (int)$sh);
    $params->put("anioIni", (int)$ad);
    $params->put("anioFin", (int)$ah);
    $params->put("filtro", $filtro);
    $url = Configuration::urlReport;

    $driverManager = new java("java.sql.DriverManager");
    $class = new JavaClass("java.lang.Class");
    $class->forName("com.mysql.jdbc.Driver");

    $conn = $driverManager -> getConnection($url.Configuration::dbReport);
    $jasperPrint = $fillManager->fillReport($report, $params, $conn);


    if($_REQUEST["tipo"]==2)
    {
        /*Exportar a Excel*/
        $outputPath = realpath(".")."/"."output.xls";
        $JRXlsExporter = new Java("net.sf.jasperreports.engine.export.JRXlsExporter");
        $JRXlsExporterParameter = new Java("net.sf.jasperreports.engine.export.JRXlsExporterParameter");
        $JRXlsExporter->setParameter($JRXlsExporterParameter->JASPER_PRINT, $jasperPrint);
        $JRXlsExporter->setParameter($JRXlsExporterParameter->OUTPUT_FILE_NAME, $outputPath);
        $JRXlsExporter->exportReport();
        header("Content-type: application/vnd-ms-excel; charset=iso-8859-1");
        header("Content-Disposition: attachment; filename=consolidado_".date('d-m-Y').".xls");
    }
    else
    {
        // Exportar a PDF
        $outputPath = realpath(".")."/"."output.pdf";
        $exportManager = new JavaClass("net.sf.jasperreports.engine.JasperExportManager");
        $exportManager->exportReportToPdfFile($jasperPrint, $outputPath);
        header("Content-type: application/pdf");
    }

    readfile($outputPath);
    unlink($outputPath);
}
?>

