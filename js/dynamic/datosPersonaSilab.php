<?php

require_once("libs/ConfigurationHospitalInfluenza.php");
require_once("libs/helper/helperSilab.php");
require_once("libs/helper/helperSilabRemoto.php");
header('Content-type: text/javascript');

$id = $_REQUEST["id"];
$tipoId = $_REQUEST["tipo_id"];
$silab = $_REQUEST["silab"];
if($silab == ConfigurationHospitalInfluenza::SILABLOCAL)
    $personas = helperSilab::getDatosPersona($id,$tipoId);
else if($silab == ConfigurationHospitalInfluenza::SILABREMOTO)
    $personas = helperSilabRemoto::getDatosPersona($id,$tipoId);
//print_r($personas);
//echo $silab;
$result = '';
if (is_array($personas)) {
    foreach ($personas as $persona) {
        $result .= $persona["IND_IDENTIFICADOR_TIPO"] . "#"  //0
                . $persona["IND_IDENTIFICADOR"] . "#" //1
                . htmlentities($persona["IND_PRIMER_NOMBRE"]) . "#" //2
                . htmlentities($persona["IND_SEGUNDO_NOMBRE"]) . "#" //3
                . htmlentities($persona["IND_PRIMER_APELLIDO"]) . "#" //4
                . htmlentities($persona["IND_SEGUNDO_APELLIDO"]) . "#" //5
                . htmlentities($persona["IND_FECHA_NACIMIENTO"]) . "#" //6
                . $persona["IND_TIPO_EDAD"] . "#" //7
                . $persona["IND_EDAD"] . "#" //8
                . $persona["IND_SEXO"] . "#" //9
                . "#" //10 nombre_responsable
                . $persona["IND_PROC_PROVINCIA"] . "#" //11
                . $persona["IND_PROC_REGION"] . "#" //12
                . $persona["IND_PROC_DISTRITO"] . "#" //13
                . $persona["IND_PROC_CORREGIMIENTO"] . "#" //14
                . htmlentities($persona["IND_DIRECCION"]) . "#" //15 dir_referencia
                . "#" //16 nombre_tipo
                . "#" //17 nombre_ocupacion
                . "#" //18 dir_trabajo
                . htmlentities($persona["IND_TELEFONO"]) . "#"  //19
        ;
    }
}
echo $result;