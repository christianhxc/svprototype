<?php
    require_once ('libs/pages/vigmor/vigmorPage.php');
    require_once ('libs/helper/helperCatalogos.php');
    require_once ('libs/helper/helperLugar.php');
    // LOGIN
    require_once ('libs/caus/clsCaus.php');
    require_once ('libs/Configuration.php');

    // Validar si ya ha iniciado sesion
    if (!clsCaus::validarSession()){
        header("location: ../login.php");
    }

    $config = array();
    // Archivos javascript necesarios
    $config["jsfiles"][] = "vigmor/busqueda.js";
    $config["jsfiles"][] = "Utils.js";
    $config["jsfiles"][] = "comun.js";
    $config["info"] = $_REQUEST["info"];

    $page = new vigmorPage($config);
    $page->displayPage();
?>