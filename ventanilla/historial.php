<?php
    require_once ('libs/pages/historialPage.php');
    require_once ('libs/helper/helperCatalogos.php');
    require_once ('libs/helper/helperLugar.php');
    require_once ('libs/dal/ventanilla/dalHistorial.php');

    // LOGIN
    require_once ('libs/caus/clsCaus.php');
    require_once ('libs/Configuration.php');

    // Validar si ya ha iniciado sesion
    if (!clsCaus::validarSession()){
        header("location: ../login.php");
    }

    $config = array();
    // Archivos javascript necesarios
    $config["jsfiles"][] = "ventanilla/historial.js";
    $config["jsfiles"][] = "Utils.js";
    $config["jsfiles"][] = "jquery.templating.js";    

    if(isset($_REQUEST["a"]) && isset($_REQUEST["r"]))
    {
        if($_REQUEST["a"]=='g')
        {
            $config["error"] = false;
            $config["exito"] = true;
            $config["mensaje"] = 'Las muestras enviadas se encuentran disponibles en el Informe de env&iacute;o <strong>No. '.$_REQUEST["r"].'</strong>';
        }
        else if($_REQUEST["a"]=='e')
        {
            $config["error"] = true;
            $config["exito"] = false;
            $config["mensaje"] = 'Sucedi&oacute; un error al generar el Informe de env&iacute;o de muestras por favor intente nuevamente.';
        }
    }

    // Pagineo, búsqueda y resultados de búsqueda
    $search = $_REQUEST["search"];
    $config["search"] = $search;
    $config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1;
    $config["search"]["paginado"] = Configuration::paginado;
    $config["search"]["inicio"] = ($config['page'] - 1) * $config["search"]["paginado"];
    $config["search"]["order"] = $search["order"] == "" ? " id DESC " : $search["order"];
    $config["catalogos"]["area_analisis"] = helperCatalogos::getAreasAnalisis(1);

    $dal = new dalHistorial($config["search"]);
    $config["entradas"] = $dal->getAll($config["search"]["inicio"], $config["search"]["paginado"], $config["search"]["order"], $config["search"],1, $config["catalogos"]["area_analisis"]);
    $config["cantidad"] = $dal->getCountAll($config["search"],1, $config["catalogos"]["area_analisis"]);

    $pagineo = new Pagineo($config['cantidad'],$config['page'],$config["search"]["paginado"],'');
    $config['pagineo'] = $pagineo->getPagineo();
    $config['path'] = 'historial';

    $page = new historialPage($config);
    $page->displayPage();