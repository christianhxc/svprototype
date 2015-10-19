<?php

require_once("libs/helper/helperVicIts.php");
header('Content-type: text/javascript');

$id = $_REQUEST["id"];
$tipoId = $_REQUEST["tipo_id"];
$personas = helperVicIts::buscarPersonaVicits($tipoId, $id);
$result = '';
if (is_array($personas)) {
    foreach ($personas as $persona) {
        $result = htmlentities($persona["primer_nombre"]) . "#" //2
                . htmlentities($persona["segundo_nombre"]) . "#" //3
                . htmlentities($persona["primer_apellido"]) . "#" //4
                . htmlentities($persona["segundo_apellido"]); 
    }
}
else
    $result = "no";
echo $result;