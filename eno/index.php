<?php
//echo "Hola Mundo esto es una prueba";
    require_once ('libs/pages/eno/enoPage.php');
    require_once ('libs/helper/helperCatalogos.php');
    require_once ('libs/helper/helperLugar.php');
//    require_once ('libs/helper/helperVih.php');
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
    $config["jsfiles"][] = "eno/busqueda.js";
    $config["jsfiles"][] = "Utils.js";
    $config["jsfiles"][] = "comun.js";
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
//    $search = $_REQUEST["search"];
//    $config["search"] = $search;
//    $config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1;
//    $config["result"] = $_REQUEST["result"];
//    $config["search"]["order"] = $search["order"] == "" ? " id_vih_form " : $search["order"];
//    $config["search"]["paginado"] = Configuration::paginado;
//    $config["search"]["inicio"] = ($config['page'] - 1) * $config["search"]["paginado"];
//    $config['path'] = 'index';
    
    //echo helperVih::contruirViewSilab();

    $page = new enoPage($config);
    $page->displayPage();
?>