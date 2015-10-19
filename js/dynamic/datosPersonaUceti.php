<?php

require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript');

$id = $_REQUEST["id"];
$tipoId = $_REQUEST["tipo_id"];
$personas = helperCatalogos::getDatosPersonaUceti($tipoId, $id);
$result = '';
if (is_array($personas)) {
    foreach ($personas as $persona) {
        $result .= $persona["tipo_identificacion"] . "#"  //0
                . $persona["numero_identificacion"] . "#" //1
                . htmlentities($persona["primer_nombre"]) . "#" //2
                . htmlentities($persona["segundo_nombre"]) . "#" //3
                . htmlentities($persona["primer_apellido"]) . "#" //4
                . htmlentities($persona["segundo_apellido"]) . "#" //5
                . htmlentities($persona["per_fecha_nacimiento"]) . "#" //6
                . $persona["tipo_edad"] . "#" //7
                . $persona["edad"] . "#" //8
                . $persona["sexo"] . "#" //9
                . htmlentities($persona["nombre_responsable"]) . "#" //10
                . $persona["id_provincia"] . "#" //11
                . $persona["idregion"] . "#" //12
                . $persona["id_distrito"] . "#" //13
                . $persona["id_corregimiento"] . "#" //14
                . htmlentities($persona["dir_referencia"]) . "#" //15
                . htmlentities($persona["nombre_tipo"]) . "#" //16
                . htmlentities($persona["nombre_ocupacion"]) . "#" //17
                . htmlentities($persona["dir_trabajo"]) . "#" //18
                . htmlentities($persona["tel_residencial"]) . "#"  //19
        ;
    }
}
echo $result;