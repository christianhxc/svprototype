<?php
require_once ('libs/Configuration.php');
require_once ('libs/caus/clsCaus.php');

if(isset($_REQUEST["e"]) && isset($_REQUEST["firma"]))
{

    $filtro = ' 1 ';
    $filtro .= ($_REQUEST["n"]==''?'':" AND CONCAT(`PRIMER NOMBRE`,' ',`SEGUNDO NOMBRE`)  LIKE '%".strtoupper($_REQUEST["n"])."%'");
    $filtro .= ($_REQUEST["ap"]==''?'':" AND CONCAT(`PRIMER APELLIDO`,' ',`SEGUNDO APELLIDO`)  LIKE '%".strtoupper($_REQUEST["ap"])."%'");
    $filtro .= ($_REQUEST["dep"]==0?'':($_REQUEST["dep"] != "" ? " AND idas = '".$_REQUEST["dep"]."'" : ""));
    $filtro .= ($_REQUEST["mun"]==0?'':" AND idds = '".$_REQUEST["mun"]."'");
    $filtro .= ($_REQUEST["es"]==0?'':($_REQUEST["es"] != "" ? " AND idts = '".$_REQUEST["es"]."'" : ""));
    $filtro .= ($_REQUEST["o"] != "" ? ($_REQUEST["es"]=='0'? '': " AND otro = '".strtoupper($_REQUEST["o"])."'"):'');
    $filtro .= ($_REQUEST["r"]==0?'':($_REQUEST["r"] != "" ? " AND RES_FIN_ID = '".$_REQUEST["r"]."'" : ""));
    $filtro .= ($_REQUEST["estado"]==0?'':($_REQUEST["estado"] == "2" ? " AND (SIT_ID = '4'  OR SIT_ID ='8')" : " AND (SIT_ID != '4'  AND SIT_ID !='8')"));

    $filtro.= ($_REQUEST["gd"] != "" ? " AND MUE_CODIGO_GLOBAL_NUMERO >= ".$_REQUEST["gd"]: "");
    $filtro.= ($_REQUEST["gh"] != "" ? " AND MUE_CODIGO_GLOBAL_NUMERO <= ".$_REQUEST["gh"]: "");
    $filtro.= ($_REQUEST["cd"] != "" ? " AND MUE_CODIGO_CORRELATIVO_NUMERO >= ".$_REQUEST["cd"]: "");
    $filtro.= ($_REQUEST["ch"] != "" ? " AND MUE_CODIGO_CORRELATIVO_NUMERO <= ".$_REQUEST["ch"]: "");

    require_once("libs/helper/helperString.php");
    $filtro.= ($_REQUEST["dd"] != "" ? " AND fecha >= '".helperString::toDate($_REQUEST["dd"])."'": "");
    $filtro.= ($_REQUEST["dh"] != "" ? " AND fecha <= '".helperString::toDate($_REQUEST["dh"])."'": "");

    // Filtrar los resultados de búsquedas por permisos de procedencia
    // según división sanitaria del país
    $lista = clsCaus::obtenerUbicacionesCascada();
    if (is_array($lista)){
        foreach ($lista as $elemento)
        {
            $temporal = "";
            if ($elemento[ConfigurationCAUS::AreaSalud] != "")
                $temporal .= "idas = '".$elemento[ConfigurationCAUS::AreaSalud]."' ";

            if ($elemento[ConfigurationCAUS::DistritoSalud] != "")
                $temporal .= ($temporal != '' ? "and " : "")."idds = '".$elemento[ConfigurationCAUS::DistritoSalud]."' ";

            if ($elemento[ConfigurationCAUS::ServicioSalud] != "")
                $temporal .= ($temporal != '' ? "and " : "")."idts = '".$elemento[ConfigurationCAUS::ServicioSalud]."' ";

            $filtroUbicaciones .= ($filtroUbicaciones != '' ? "or " : "")."(".$temporal.") ";
        }
    }

    if ($filtroUbicaciones != "")
        $filtroUbicaciones = "and (".$filtroUbicaciones.")";
    $filtro.=" ".$filtroUbicaciones." ";

    require_once(Configuration::javaAddress);
    $compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
    $report = $compileManager->compileReport(realpath("reporte_area_evento.jrxml"));
    $fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
    $params = new Java("java.util.HashMap");

    //echo $filtro;exit;

    $params->put("evento", (int)$_REQUEST["e"]);
    $params->put("firma", $_REQUEST["firma"]);
    $params->put("filtro", $filtro);
    $params->put("evNom", $_REQUEST["evNom"]);
    $params->put("aa", $_REQUEST["aa"]);
    $params->put("atencion", $_REQUEST["atencion"]);
    $url = Configuration::urlReport;

    $driverManager = new java("java.sql.DriverManager");
    $class = new JavaClass("java.lang.Class");
    $class->forName("com.mysql.jdbc.Driver");

    $conn = $driverManager -> getConnection($url.Configuration::dbReport);
    $jasperPrint = $fillManager->fillReport($report, $params, $conn);

    if(!isset($_REQUEST["tipo"]))
        $_REQUEST["tipo"] = '1';

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
        header("Content-Disposition: attachment; filename=reporte_area_evento_".date('d-m-Y').".xls");
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

