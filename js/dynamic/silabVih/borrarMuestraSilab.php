<?php

require_once("libs/helper/helperVih.php");
require_once("libs/helper/helperCatalogos.php");
require_once("libs/dal/vih/dalVih.php");
require_once('libs/Connection.php');
require_once ('libs/dal/vih/MuestraSilab.php');

    $conn = new Connection();
    $conn->initConn();
    $conn->begin();
    $idMuestra = $_REQUEST["id_muestra"];
    $idVihForm = $_REQUEST["id_vih_form"];

    dalVih::BorrarVihPruebaSilab($conn, $idMuestra);
    dalVih::BorrarVihMuestraSilab($conn, $idMuestra);

    $conn->commit();
    $conn->closeConn();

    $muestras = helperVih::buscarVihMuestrasSilab($idVihForm);    
    echo MuestraSilab::construirMuestraSilab($muestras);

?>