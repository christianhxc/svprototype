<?php
require_once ('libs/caus/clsCaus.php');
require_once ('libs/pages/login.php');
require_once ('libs/Configuration.php');

// Validar si se desea cerrar sesion
if ($_REQUEST["logout"]){
    clsCaus::cerrarSesion();
    file_get_contents(Configuration::reportAddress."remotelogin.php?logout=true");
}

// Validar si ya ha iniciado sesion
if (clsCaus::validarSession()){
    header("location: index.php");
}
$config = array();
    // Archivos javascript necesarios
$config["cssfiles"][] = "style_new";
$config["cssfiles"][] = "nivo-slider";

$config["jsfiles"][] = "slider_login.js";
$config["jsfiles"][] = "jquery.nivo.slider.pack.js";
$config["info"] = $_REQUEST["info"];

if (count($_POST)){
    $result = false;
    if (isset($_POST["txtUsuario"]) && isset($_POST["txtClave"])){
        $result = clsCaus::validarLogin($_POST["txtUsuario"], $_POST["txtClave"]);
        if ($_SESSION["user"]["organizacion"]==-1){
            clsCaus::cerrarSesion();
            file_get_contents(Configuration::reportAddress."remotelogin.php?logout=true");
            $result = -3;
        }
    }
   
    if ($result == 1){
        file_get_contents(Configuration::reportAddress."remotelogin.php?user=".$_POST["txtUsuario"]."&clave=".$_POST["txtClave"]);
        header("location: index.php");
        exit;
    }elseif($result == 2){
        header("location: cambiarclave.php?username=".$_POST["txtUsuario"]);
        exit;
    }else if($result == -2){
        $config["error"] = " - No tiene permisos para acceder al sistema, por favor comun&iacute;quese con el departamento t&eacute;cnico";
    }else if($result == -3){
        $config["error"] = " - El usuario no esta asociado con ninguna Organizaci&oacute;n, por favor comun&iacute;quese con el departamento t&eacute;cnico";
    }else{
        $config["error"] = " - El usuario y/o clave no son correctos";
    }
}

$page = new login($config);
$page->displayPage();
?>
