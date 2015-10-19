<?php

require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript');

$id = $_REQUEST["id"];
$tipoId = $_REQUEST["tipo_id"];
$personas = helperCatalogos::getDatosPersonatb($tipoId, $id);
$result = '';
if (is_array($personas)) {
    foreach ($personas as $persona) {
        $result .= $persona["tipo_identificacion"] . "#"  //0
                . $persona["numero_identificacion"] . "#" //1
                . htmlentities($persona["primer_nombre"]) . "#" //2
                . htmlentities($persona["segundo_nombre"]) . "#" //3
                . htmlentities($persona["primer_apellido"]) . "#" //4
                . htmlentities($persona["segundo_apellido"]) . "#" //5
                . htmlentities($persona["casada_apellido"]) . "#" //6
                . htmlentities($persona["per_fecha_nacimiento"]) . "#" //7
                . $persona["tipo_edad"] . "#" //8
                . $persona["edad"] . "#" //9
                . $persona["sexo"] . "#" //10
                . htmlentities($persona["nombre_responsable"]) . "#" //11
                . $persona["id_provincia"] . "#" //12
                . $persona["idregion"] . "#" //13
                . $persona["id_distrito"] . "#" //14
                . $persona["id_corregimiento"] . "#" //15
                . htmlentities($persona["dir_referencia"]) . "#" //16
                . htmlentities($persona["nombre_tipo"]) . "#" //17
                . htmlentities($persona["nombre_ocupacion"]) . "#" //18
                . htmlentities($persona["dir_trabajo"]) . "#" //19
                . htmlentities($persona["tel_residencial"]) . "#"  //20
                . $persona["id_etnia"] . "#"  //21
                . $persona["id_gpopoblacional"] . "#"  //22
                . $persona["id_estado_civil"] . "#"  //23
                . $persona["id_escolaridad"] . "#"  //24
                . $persona["id_ocupacion"] . "#"  // 25
                
        ;
    }
}
echo $result;