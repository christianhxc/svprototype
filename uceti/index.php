<?php
//echo "Hola Mundo esto es una prueba";
    require_once ('libs/pages/ucetiPage.php');
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
    $config["jsfiles"][] = "uceti/busqueda.js";
    $config["jsfiles"][] = "jquery.qtip-1.0.0.min.js";
    $config["jsfiles"][] = "Utils.js";
    $config["jsfiles"][] = "jquery.autocomplete.js";
    $config["cssfiles"][] = "jquery.autocomplete";
    $config["info"] = $_REQUEST["info"];

    if(isset($_REQUEST["a"]) && isset($_REQUEST["cod"]))
    {
        // DEBE DESPLEGAR ALGUN MENSAJE (ERROR/EXITO)
        switch($_REQUEST["a"])
        {
            // Guardado Ok
            case '1':
                $config["exito"]=true;
                $config["error"]=false;
                $config["muestra"]= $_REQUEST["cod"];
                $config["selectMensaje"] = 1;
            break;
            // Editado Ok
            case '2':
                $config["exito"]=true;
                $config["error"]=false;
                $config["muestra"]= $_REQUEST["cod"];
                $config["selectMensaje"] = 2;
            break;
            // Anulado OK
            case '3':
                $config["exito"]=true;
                $config["error"]=false;
                $config["muestra"]= $_REQUEST["cod"];
                $config["selectMensaje"] = 3;
            break;
            // Anulado error
            case '4':
                $config["exito"]=false;
                $config["error"]=true;
                $config["selectMensaje"] = 4;
            break;
            // Muestra para modificar no encontrada
            case '5':
                $config["exito"]=false;
                $config["error"]=true;
                $config["selectMensaje"] = 5;
            break;
            // Alicuotas ok
            case '6':
                $config["exito"]=true;
                $config["error"]=false;
                $config["muestras"]= $_REQUEST["cod"];
                $config["selectMensaje"] = 6;
            break;
        }
    }

    // Pagineo, búsqueda y resultados de búsqueda
    $search = $_REQUEST["search"];
    $config["search"] = $search;
    $config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1;
    $config["result"] = $_REQUEST["result"];
    $config["search"]["order"] = $search["order"] == "" ? " muestra.MUE_ID DESC " : $search["order"];
    $config["search"]["paginado"] = Configuration::paginado;
    $config["search"]["inicio"] = ($config['page'] - 1) * $config["search"]["paginado"];
    //$config["catalogos"]["area_analisis"] = helperCatalogos::getAreasAnalisis(1);
    //print_r($config);exit;
//    $dal = new dalIngreso($config["search"]);
//    $config["entradas"] = $dal->getAll($config["search"]["inicio"], $config["search"]["paginado"], $config["search"]["order"], $config["search"],     $config["catalogos"]["area_analisis"]);
//    $config["cantidad"] = $dal->getCountAll($config["search"], $config["catalogos"]["area_analisis"]);

    //$pagineo = new Pagineo($config['cantidad'],$config['page'],$config["search"]["paginado"],'');
//    $config['pagineo'] = $pagineo->getPagineo();
    $config['path'] = 'index';

    $page = new ucetiPage($config);
    $page->displayPage();
