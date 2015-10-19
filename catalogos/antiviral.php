<?php

    require_once ('libs/caus/clsCaus.php');
    require_once ('libs/pages/antiviralesPage.php');
    require_once ('libs/helper/helperLugar.php');
    require_once ('libs/Configuration.php');
    require_once ('libs/Etiquetas.php');

    // Validar si se desea cerrar sesion
    if ($_REQUEST["logout"]){
        clsCaus::cerrarSesion();
        file_get_contents(Configuration::reportAddress."remotelogin.php?logout=true");
    }

    // Validar si ya ha iniciado sesion
    if (!clsCaus::validarSession()){
        header("location: ../index.php");
    }

    $config["info"] = $_REQUEST["info"];

    $config = array();
    // Archivos javascript necesarios
    $config["jsfiles"][] = "comun.js";
    $config["jsfiles"][] = "Utils.js";
    $config["jsfiles"][] = "Etiquetas.js";
    $config["jsfiles"][] = "ajax.js";
    $config["jsfiles"][] = "catalogos/antivirales.js";

    $page = new antiviralesPage($config);
    $page->displayPage();
?>
