<?php
    require_once ('libs/pages/asignarAlicuotaPage.php');
    require_once ('libs/helper/helperCatalogos.php');
    require_once ('libs/helper/helperMuestra.php');
    require_once ('libs/dal/analista/dalAnalista.php');

    // LOGIN
    require_once ('libs/caus/clsCaus.php');
    require_once ('libs/Configuration.php');

    // Validar si ya ha iniciado sesion
    if (!clsCaus::validarSession()){
        header("location: ../login.php");
    }

    $config = array();
    // Archivos javascript necesarios
    $config["jsfiles"][] = "ventanilla/asignarAlicuota.js";
    $config["jsfiles"][] = "comun.js";
    $config["jsfiles"][] = "Utils.js";
    $config["idMuestra"] = $_REQUEST["m"];

    if(isset($config["idMuestra"]))
    {
        $config["data"] = helperMuestra::getDatosBasicos ($config["idMuestra"]);
        $config["error"] = false;
    }
    else
    {
        // Muestra no encontrada para asignar alicuotas
        header("Location: index.php?a=6");
        exit;
    }

    // Pagineo, búsqueda y resultados de búsqueda
    $search = $_REQUEST["search"];
    $config["search"] = $search;
    $config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1;
    $config["search"]["paginado"] = Configuration::paginado;
    $config["search"]["inicio"] = ($config['page'] - 1) * $config["search"]["paginado"];
    $config["catalogos"]["area_analisis"] = helperCatalogos::getAreasAnalisis(2);
    $config["catalogos"]["razones_rechazo"] = helperCatalogos::getRazonesRechazo();

    $page = new asignarAlicuotaPage($config);
    $page->displayPage();
