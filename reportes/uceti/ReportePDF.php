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
    //echo $_REQUEST["idForm"];exit;
    $params->put("filtro", "1");
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
    $outputPath = realpath(".")."/"."output.pdf";
    $exportManager = new JavaClass("net.sf.jasperreports.engine.JasperExportManager");
    $exportManager->exportReportToPdfFile($jasperPrint, $outputPath);
    header("Content-type: application/pdf");
    readfile($outputPath);
    unlink($outputPath);
?>