<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once ('libs/caus/clsCaus.php');
require_once ('libs/pages/cambiarClave.php');

$config["jsfiles"][] = "cambiarclave.js";

// Validar si ya ha iniciado sesion
if (!clsCaus::validarCambiar()){
    header("location: login.php");
}

if (count($_POST)){
    $result = false;
    if (isset($_POST["username"]) && isset($_POST["txtClaveAnterior"]) && isset($_POST["txtClaveNueva"])){
        $result = clsCaus::cambiarClave($_POST["username"], $_POST["txtClaveAnterior"], $_POST["txtClaveNueva"]);
    }
    
    if ($result == 1){
        header("location: login.php?logout=true");
    }elseif($result == -1){
        $config["error"] = "- La clave anterior no es v&aacute;lida";
    }elseif($result == -2){
        $config["error"] = "Error al cambiar la clave, por favor int&eacute;ntelo de nuevo";
    }
}

$config["username"] = $_REQUEST["username"];

$page = new cambiarClave($config);
$page->displayPage();
?>
