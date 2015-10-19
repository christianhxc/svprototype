<?php
//echo "Hola Mundo esto es una prueba";
    require_once ('libs/pages/sincronizarSilabVihPage.php');
    require_once ('libs/dal/vih/dalVih.php');
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
    $config["jsfiles"][] = "vih/sincronizarSilab.js";
    $config["jsfiles"][] = "Utils.js";
    $config["jsfiles"][] = "comun.js";
    $config["info"] = $_REQUEST["info"];
    
    //Precagar todos los casos de VIH de SILAB
    if($_REQUEST["action"] == 'c')
    {
       $config["resultados"] = dalVih::cargarDatosSilab();
    }
    //Sincronizar los casos de VIH de SILAB que no estab en SISVIG
    else if(isset($_REQUEST["s"]))
    {
        
    }
  
    //echo helperVih::contruirViewSilab();

    $page = new sincronizarVihPage($config);
    $page->displayPage();
