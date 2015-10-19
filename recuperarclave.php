<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

require_once ('libs/caus/clsCaus.php');
require_once ('libs/pages/recuperarClave.php');

$config["jsfiles"][] = "recuperarclave.js";

if (count($_POST)){
    $result = false;
    if (isset($_POST["username"])){
        $result = clsCaus::recuperarClave($_POST["username"]);
    }
    
    if ($result == 1){
        header("location: login.php?info=Un correo electronico ha sido enviado con la nueva clave, llegara en no mas de cinco minutos");
        exit;
    }elseif($result == -1){
        $config["error"] = "Error desconocido al cambiar la clave, intentelo de nuevo";
    }
}

$config["username"] = $_REQUEST["username"];

$page = new recuperarClave($config);
$page->displayPage();
?>
