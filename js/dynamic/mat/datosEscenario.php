<?php

require_once("libs/helper/helperMat.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");

if (isset($_REQUEST["idEscenario"])){
    $idEscenario = $_REQUEST["idEscenario"];
    $data = helperMat::datosEscenario($idEscenario);
    $grupos = helperMat::buscarGruposRelacionados($idEscenario);
    
    $gruposTotal = "";
    foreach ($grupos as $grupo) {
        $gruposTotal .= $grupo['id_grupo_contacto'] . "-" . $grupo['nombre_grupo_contacto']."###";
    }
    $gruposTotal = substr($gruposTotal, 0,strlen($gruposTotal)-3 );
    $result = '';
    if (is_array($data)){
        $result = $data['id_escenario']."#$#";
        $result.= $data['id_n1']."#$#";
        $result.= $data['cie_10_1'].' - '.$data['nombre_evento']."#$#";
        $result.= $data['tipo_algoritmo']."#$#";
        $result.= $data['nivel_geo']."#$#";
        $result.= $data['tipo_alerta']."#$#";
        $result.= $data['dia_alerta']."#$#";
        $result.= $data['id_grupo_contacto']."#$#";
        $result.= $data['mensaje']."#$#";
        $result.= $data['nombre_crear']."#$#";
        $result.= $data['fecha_crear']."#$#";
        $result.= $gruposTotal."#$#";
        if ($data['nivel_geo']!= 0){
            $idNivelesGeo = array();
            $idNivelesGeo = explode("##",$data['id_nivel_geo']);
            $result.= (isset($idNivelesGeo[3])) ? $idNivelesGeo[3]."#$#" : '';
            $result.= (isset($idNivelesGeo[2])) ? $idNivelesGeo[2]."#$#" : '';
            $result.= (isset($idNivelesGeo[1])) ? $idNivelesGeo[1]."#$#" : '';
            $result.= (isset($idNivelesGeo[0])) ? $idNivelesGeo[0]."#$#" : '';
        }
    }
    $result = substr($result,0,-3);
    echo $result;
}