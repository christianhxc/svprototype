<?php
    require_once ('libs/pages/historicoPage.php');
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
    $config["jsfiles"][] = "reportes/historico.js";
    $config["jsfiles"][] = "Utils.js";

    // Pagineo, búsqueda y resultados de búsqueda
    $search = $_REQUEST["search"];
    $config["search"] = $search;
    $config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1;
    $config["result"] = $_REQUEST["result"];
    $config["search"]["order"] = $search["order"] == "" ? " muestra.MUE_ID desc " : $search["order"];
    $config["search"]["paginado"] = Configuration::paginado;
    $config["search"]["inicio"] = ($config['page'] - 1) * $config["search"]["paginado"];
    $config["catalogos"]["area_analisis"] = helperCatalogos::getAreasAnalisis(3);

    $dal = new dalIngreso($config["search"]);
    $config["entradas"] = $dal->getAllMuestra($config["search"]["inicio"], $config["search"]["paginado"], $config["search"]["order"], $config["search"], $config["catalogos"]["area_analisis"]);
    $config["cantidad"] = $dal->getCountAllMuestra($config["search"], $config["catalogos"]["area_analisis"]);

    $pagineo = new Pagineo($config['cantidad'],$config['page'],$config["search"]["paginado"],'');
    $config['pagineo'] = $pagineo->getPagineo();
    $config['path'] = 'historico';

    $page = new historicoPage($config);
    $page->displayPage();
