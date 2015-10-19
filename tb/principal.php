<?php
//echo "Hola Mundo esto es una prueba";
    require_once ('libs/pages/tb/tbprincipalPage.php');
    require_once ('libs/helper/helperCatalogos.php');
    require_once ('libs/helper/helperLugar.php');
    //require_once ('libs/dal/ventanilla/dalIngreso.php');

    // LOGIN
    require_once ('libs/caus/clsCaus.php');
    require_once ('libs/Configuration.php');

    // Validar si ya ha iniciado sesion
    if (!clsCaus::validarSession()){
        header("location: ../login.php");
    }


    $config = array();
    // Archivos javascript necesarios
    $config["jsfiles"][] = "tb/principal.js";
//    $config["jsfiles"][] = "jquery.qtip-1.0.0.min.js";
    $config["jsfiles"][] = "Utils.js";
    $config["jsfiles"][] = "jquery.autocomplete.v2.js";
    $config["cssfiles"][] = "jquery.autocomplete";
    $config["info"] = $_REQUEST["info"];



    // Pagineo, búsqueda y resultados de búsqueda
    $search = $_REQUEST["search"];
    $config["search"] = $search;
    $config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1;
    $config["result"] = $_REQUEST["result"];
    $config["search"]["order"] = $search["order"] == "" ? " muestra.MUE_ID DESC " : $search["order"];
    $config["search"]["paginado"] = Configuration::paginado;
    $config["search"]["inicio"] = ($config['page'] - 1) * $config["search"]["paginado"];
    $config['path'] = 'index';
    
    $config["catalogos"]["provincias"] = helperLugar::getProvincias($config);
    
//    print_r($config["catalogos"]);
    $page = new tbprincipalPage($config);
    $page->displayPage();
