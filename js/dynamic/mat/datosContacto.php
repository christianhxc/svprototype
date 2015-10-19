<?php

require_once("libs/helper/helperMat.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");

if (isset($_REQUEST["idContacto"])){
    $idContacto = $_REQUEST["idContacto"];
    $data = helperMat::datosContacto($idContacto);
    $result = '';
    if (is_array($data)){
        $result = $data['id_contacto']."#$#";
        $result.= $data['nombres']."#$#";
        $result.= $data['apellidos']."#$#";
        $result.= $data['email']."#$#";
        $result.= $data['telefono']."#$#";
        $result.= $data['status'];
    }
    echo $result;
}