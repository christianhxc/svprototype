<?php
require_once ('libs/caus/clsCaus.php');

// Validar si se desea cerrar sesion
if ($_REQUEST["logout"]){
    clsCaus::cerrarSesion();
    exit;
}

// Validar si ya ha iniciado sesion
if (clsCaus::validarSession()){
    echo "1";
    exit;
}

$result = -1;
if (isset($_REQUEST["user"]) && isset($_REQUEST["clave"])){
    $result = clsCaus::validarLogin($_REQUEST["user"], $_REQUEST["clave"]);
}

echo $result;
?>
