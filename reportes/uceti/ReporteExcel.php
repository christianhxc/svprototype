<?php
    require_once ('libs/caus/clsCaus.php');
    require_once('libs/Configuration.php');
    require_once(Configuration::javaAddress);
    // Validar si ya ha iniciado sesion
    if (!clsCaus::validarSession()){
        header("location: ../../login.php");
    }

    $compileManager = new JavaClass("net.sf.jasperreports.engine.JasperCompileManager");
    $report = $compileManager->compileReport(realpath("antecedentes.jrxml"));
    $fillManager = new JavaClass("net.sf.jasperreports.engine.JasperFillManager");
    
    $params = new Java("java.util.HashMap");
    
    /*por parametro get le pasamos el id del proyecto que vamos a mostrar*/
    $params->put("filtro", "1"); // Puede ser un request
    /*Estos son los parametros de conexion para cada una de las base de datos*/
    $url = "jdbc:mysql://".Configuration::host."/";
    $dbName = Configuration::DB;
    $userName = Configuration::DBuser;
    $password = Configuration::DBpass;
    
    $driverManager = new java("java.sql.DriverManager");
    $class = new JavaClass("java.lang.Class");
    $class->forName("com.mysql.jdbc.Driver");

    $conn = $driverManager -> getConnection($url.$dbName."?user=".$userName."&password=".$password);

    $jasperPrint = $fillManager->fillReport($report, $params, $conn);
    $outputPath = realpath(".")."/"."output.xls";

    $JRXlsExporter = new Java("net.sf.jasperreports.engine.export.JRXlsExporter");
    $JRXlsExporterParameter = new Java("net.sf.jasperreports.engine.export.JRXlsExporterParameter");
    $JRXlsExporter->setParameter($JRXlsExporterParameter->JASPER_PRINT, $jasperPrint);
    $JRXlsExporter->setParameter($JRXlsExporterParameter->OUTPUT_FILE_NAME, $outputPath);
    $JRXlsExporter->exportReport();

    header("Content-type: application/vnd-ms-excel; charset=iso-8859-1");
    header("Content-Disposition: attachment; filename=Reporte_excel_antecedente_".date('d-m-Y').".xls");

    readfile($outputPath);
    unlink($outputPath);
?>