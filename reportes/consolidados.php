<?php
    require_once ('libs/pages/consolidadosPage.php');
    require_once ('libs/helper/helperCatalogos.php');
    require_once ('libs/helper/helperLugar.php');
    require_once ('libs/dal/ventanilla/dalIngreso.php');

    // LOGIN
    require_once ('libs/caus/clsCaus.php');
    require_once ('libs/Configuration.php');

    // Validar si ya ha iniciado sesion
    if (!clsCaus::validarSession()){
        header("location: ../login.php");
    }

    $config = array();
    // Archivos javascript necesarios
    $config["jsfiles"][] = "reportes/consolidados.js";
    $config["jsfiles"][] = "Utils.js";
    $config["catalogos"]["area_analisis"] = helperCatalogos::getAreasAnalisis(3);
    $config["search"] = $_REQUEST["search"];
    print_r($config["search"]);

    $page = new consolidadosPage($config);
    $page->displayPage();
