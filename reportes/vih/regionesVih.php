<?php
    require_once ('libs/pages/regionesVihPage.php');
    require_once ('libs/helper/helperCatalogos.php');
    require_once ('libs/helper/helperLugar.php');
    require_once ('libs/dal/vih/dalVih.php');

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
    $config["jsfiles"][] = "ajax.js";
    $config["jsfiles"][] = "vih/regionesVih.js";

    // Pagineo, búsqueda y resultados de búsqueda
    $search = $_REQUEST["search"];

    if(count($_REQUEST["search"])==0)
        $config["preselect"] = false;
    else
        $config["preselect"] = true;
    
    $config["search"] = $search;
    $config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1;
    $config["result"] = $_REQUEST["result"];
    $config["search"]["order"] = $search["order"] == "" ? " global ASC, gnumero ASC " : $search["order"];
    $config["search"]["paginado"] = Configuration::paginado;
    $config["search"]["inicio"] = ($config['page'] - 1) * $config["search"]["paginado"];
    
    $page = new regionesVihPage($config);
    $page->displayPage();
?>