<?php

    require_once('libs/nusoap.php');
    require_once('libs/DOMXML.php');
    require_once('libs/EncodeDecode.php');
    require_once('libs/Configuration.php');
    require_once('libs/Connection.php');
    require_once('libs/Connection.php');
    require_once('libs/caus/ConfigurationCAUS.php');
    require_once('libs/caus/ConnectionCAUS.php');
    
    $limiteUno= $_GET["limiteuno"];
    $limiteDos= $_GET["limitedos"];
    $root= $_GET["root"];
    $id= $_GET["id"];
    
    $id= $id . " limit ".$limiteUno.",".$limiteDos;
    
    $conn = new Connection();
    $conn->initConn();
    $sql = $id;
    //$sql =  "";
    $conn = new Connection();
    $conn->initConn();
    $sql = $id;
    $node_ = $root;
    $conn->prepare($sql);
    $conn->execute();
    $data = $conn->fetch();
    $data = replaceCharacter($data);
    $contenido = '<?xml version="1.0" encoding="UTF-8"?>';
    $contenido.= DOM::arrayToXMLString($data, $node_);
    $contenido = replaceCharacter($contenido);
    $contenido = base64_encode($contenido);
    $conn->closeConn();
    //$contenido = replaceCharacter($contenido);
    $contenido=base64_encode($contenido);
    $conn = null;
    echo $contenido;
    
    function replaceCharacter($contenido)
    {
        $contenido=str_replace("á","a",$contenido);
        $contenido=str_replace("é","e",$contenido);
        $contenido=str_replace("í","i",$contenido);
        $contenido=str_replace("ó","o",$contenido);
        $contenido=str_replace("ú","u",$contenido);
        
        $contenido=str_replace("[ÁÀÂÃ]","A",$contenido);
        $contenido=str_replace("[ÉÈÊ]","E",$contenido);
        $contenido=str_replace("[ÍÌÎ]","I",$contenido);
        $contenido=str_replace("[ÓÒÔÕ]","O",$contenido);
        $contenido=str_replace("[ÚÙÛ]","U",$contenido);
        
        $contenido=str_replace("ä","a",$contenido);
        $contenido=str_replace("ë","e",$contenido);
        $contenido=str_replace("ï","i",$contenido);
        $contenido=str_replace("ö","o",$contenido);
        $contenido=str_replace("ü","u",$contenido);
        
        $contenido=str_replace("ñ", "n", $contenido);
        $contenido=str_replace("Ñ", "N", $contenido);
        return $contenido;
    }
?>