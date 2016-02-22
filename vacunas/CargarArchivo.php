<?php
//echo "Hola Mundo esto es una prueba";
    require_once ('libs/pages/cargarArchivoDeno.php');
    require_once ('libs/dal/Vacunas/dalVacunas.php');


    // LOGIN
    require_once ('libs/caus/clsCaus.php');
    require_once ('libs/Configuration.php');

    // Validar si ya ha iniciado sesion
    if (!clsCaus::validarSession()){
        header("location: ../login.php");
    }


    $config = array();
    // Archivos javascript necesarios

    $config["jsfiles"][] = "Utils.js";
    $config["jsfiles"][] = "comun.js";
    $config["info"] = $_REQUEST["info"];
    
 

    $page = new cargarArchivoDeno($config);
    $page->displayPage();
