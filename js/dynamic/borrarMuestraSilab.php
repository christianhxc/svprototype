<?php

require_once("libs/helper/helperUceti.php");
require_once("libs/helper/helperCatalogos.php");
require_once("libs/dal/uceti/dalUceti.php");
require_once('libs/Connection.php');
require_once ('libs/dal/uceti/muestraSilab.php');

    $conn = new Connection();
    $conn->initConn();
    $conn->begin();
    $idMuestra = $_REQUEST["id_muestra"];
    $idUcetiForm = $_REQUEST["id_uceti"];

//    echo "borrado ".$idMuestra;
    dalUceti::BorrarUcetiPruebaSilab($conn, $idMuestra);
    dalUceti::BorrarUcetiMuestraSilab($conn, $idMuestra);

    $conn->commit();
    $conn->closeConn();

    $muestras = helperUceti::buscarUcetiMuestrasSilab($idUcetiForm);
    echo muestraSilab::construirMuestraSilabUceti($muestras);
?>