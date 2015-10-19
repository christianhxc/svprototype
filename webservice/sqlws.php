<?php

//header('Content-Type: text/html; charset=iso-8859-1');

require_once('libs/nusoap.php');
require_once('libs/DOMXML.php');
require_once('libs/EncodeDecode.php');
require_once('libs/Configuration.php');
require_once('libs/Connection.php');
require_once('libs/caus/ConfigurationCAUS.php');
require_once('libs/caus/ConnectionCAUS.php');
require_once('libs/helper/helperString.php');

$ns = "";
$server = new nusoap_server();
$server->configureWSDL('ws', $ns);
$server->wsdl->schemaTargetNamespace = $ns;
$server->register('servicio', array('sentencia' => 'xsd:string'), array('return' => 'xsd:string'), $ns);

function servicio($sentencia) {
        $conn = new Connection();
        $conn->initConn();
        $sql = $sentencia;
        
        $conn->prepare($sql);
        $conn->execute();
        $data = $conn->fetch();
        
        $conn->closeStmt();
        $rta = json_encode($data);
        return $rta;
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>
