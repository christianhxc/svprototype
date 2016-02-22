<?php
    require_once ('libs/pages/vacunas/listadoRegistroPage.php');
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
    $config["jsfiles"][] = "vacunas/busquedaRegistro.js";
    $config["jsfiles"][] = "Utils.js";
    $config["jsfiles"][] = "comun.js";
    $config["info"] = $_REQUEST["info"];
    
    $config["catalogos"]["tipoId"] = helperCatalogos::getTipoIdentificacion();
    $config["catalogos"]["provincias"] = helperLugar::getProvincias($config);

    $page = new listadoRegistroPage($config);
    $page->displayPage();
?>