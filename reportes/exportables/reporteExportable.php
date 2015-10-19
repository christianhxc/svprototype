<?php

require_once ('libs/Configuration.php');
require_once ('libs/caus/clsCaus.php');
require_once(Configuration::javaAddress);

if(isset($_REQUEST["a"]))
{    
    $compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
    $report = $compileManager->compileReport(realpath("exportacion_excel.jrxml"));
    $fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
    $params = new Java("java.util.HashMap");
    
    $filtro = ' ';
    $filtro .= ($_REQUEST["a"]==0?'':" AND id_area_ana = ".$_REQUEST["a"]."");
    $filtro .= ($_REQUEST["e"]==0?'':" AND id_evento = ".$_REQUEST["e"]."");
    $filtro .= ($_REQUEST["n"]==''?'':" AND CONCAT(`n1`,' ',`n2`)  LIKE '%".strtoupper($_REQUEST["n"])."%'");
    $filtro .= ($_REQUEST["ap"]==''?'':" AND CONCAT(`a1`,' ',`a2`)  LIKE '%".strtoupper($_REQUEST["ap"])."%'");
    $filtro .= ($_REQUEST["dep"]==0?'':" AND iddep_muestra = ".$_REQUEST["dep"]."");
    $filtro .= ($_REQUEST["mun"]==0?'':" AND idmun_muestra = ".$_REQUEST["mun"]."");
    $filtro .= ($_REQUEST["es"]==0?'':($_REQUEST["es"] != "" ? " AND idts = ".$_REQUEST["es"]."" : ""));
    $filtro .= ($_REQUEST["o"] != "" ? ($_REQUEST["es"]=='0'? '': " AND otro_establecimiento = '".strtoupper($_REQUEST["o"])."'"):'');
    $filtro .= ($_REQUEST["estado"]==0?'':($_REQUEST["estado"] == "2" ? " AND (SIT_ID = '4'  OR SIT_ID ='8')" : " AND (SIT_ID != '4'  AND SIT_ID !='8')"));

    $filtro.= ($_REQUEST["gd"] != "" ? " AND MUE_CODIGO_GLOBAL_NUMERO >= ".$_REQUEST["gd"]: "");
    $filtro.= ($_REQUEST["gh"] != "" ? " AND MUE_CODIGO_GLOBAL_NUMERO <= ".$_REQUEST["gh"]: "");
    $filtro.= ($_REQUEST["cd"] != "" ? " AND MUE_CODIGO_CORRELATIVO_NUMERO >= ".$_REQUEST["cd"]: "");
    $filtro.= ($_REQUEST["ch"] != "" ? " AND MUE_CODIGO_CORRELATIVO_NUMERO <= ".$_REQUEST["ch"]: "");

    require_once("libs/helper/helperString.php");

    $filtro.= ($_REQUEST["id"] != "" ? " AND inicio >= '".helperString::toDate($_REQUEST["id"])."'": "");
    $filtro.= ($_REQUEST["ih"] != "" ? " AND inicio <= '".helperString::toDate($_REQUEST["ih"])."'": "");

    $filtro.= ($_REQUEST["td"] != "" ? " AND toma >= '".helperString::toDate($_REQUEST["td"])."'": "");
    $filtro.= ($_REQUEST["th"] != "" ? " AND toma <= '".helperString::toDate($_REQUEST["th"])."'": "");

    $filtro.= ($_REQUEST["rd"] != "" ? " AND recepcion >= '".helperString::toDate($_REQUEST["rd"])."'": "");
    $filtro.= ($_REQUEST["rh"] != "" ? " AND recepcion <= '".helperString::toDate($_REQUEST["rh"])."'": "");
    
    //echo $filtro;exit;

    $params->put("filtro", $filtro);
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

