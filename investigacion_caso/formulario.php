<?php
    
    require_once ('libs/caus/clsCaus.php');
    require_once ('libs/pages/formInvestigacionPage.php');
    require_once ('libs/helper/helperLugar.php');
    require_once ('libs/helper/helperCatalogos.php');
    require_once ('libs/Configuration.php');
    //require_once ('libs/Etiquetas.php');
    //require_once ('libs/dal/usuario/dalUsuario.php');

    // Validar si se desea cerrar sesion
    if ($_REQUEST["logout"]){
        clsCaus::cerrarSesion();
        file_get_contents(Configuration::reportAddress."remotelogin.php?logout=true");
    }

    // Validar si ya ha iniciado sesion
    if (!clsCaus::validarSession()){
        header("location: ../index.php");
    }

    $config = array();
    $config["info"] = $_REQUEST["info"];

    // Archivos javascript necesarios
    $config["jsfiles"][] = "comun.js";
    $config["jsfiles"][] = "Utils.js";
//    $config["jsfiles"][] = "Etiquetas.js";
    $config["jsfiles"][] = "ajax.js";
    $config["jsfiles"][] = "jquery.autocomplete.js";
    $config["cssfiles"][] = "jquery.autocomplete";
    $config["jsfiles"][] = "investigacion_caso/formulario.js";

    if (count($_POST)){
        //print_r($_POST);
        $config["data"] = $_POST["data"];
        if ($_POST["data"]["usuId"]=="")
            $mensaje = dalUsuario::nuevoUsuario($_POST["data"]);
        else
            $mensaje = dalUsuario::actualizarUsuario($_POST["data"]);

        if ($mensaje==1 && $_POST["data"]["usuId"]==""){
            $config["info"] = Etiquetas::resUsuBien;
            header("location: listado_usuarios.php?info=".$config["info"]);
        }
        else if ($mensaje==1 && $_POST["data"]["usuId"]!=""){
            $config["info"] = Etiquetas::resActBien;
            header("location: listado_usuarios.php?info=".$config["info"]);
        }
        else if ($mensaje!=1 && $_POST["data"]["usuId"]!="")
            $config["error"] = Etiquetas::resActError.$mensaje;
        else
            $config["error"] = Etiquetas::resUsuError.$mensaje;

    }

    else if (isset($_REQUEST["id"])){
        $config["data"] = dalUsuario::datosUsuario($_REQUEST["id"]);
        $config["data"]["usuId"] = $_REQUEST["id"];
        $config["data"]["usuDisponible1"] = "si";
    }
    
    $config["catalogos"]["sintomas"] = helperCatalogos::getSintomas();
//    $config["lugares"]["regiones"] = helperLugar::getRegionSalud($config);

    $page = new formInvestigacion($config);
    $page->displayPage();
?>
