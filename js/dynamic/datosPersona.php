<?php

require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript');

$id = $_REQUEST["id"];
$tipoId = $_REQUEST["tipo_id"];
$personas = helperCatalogos::getDatosPersona($tipoId, $id);
$result = '';
if (is_array($personas)) {
    foreach ($personas as $persona) {
        $result .= $persona["tipo_identificacion"] . "#"  //0
                . $persona["numero_identificacion"] . "#" //1
                . ($persona["primer_nombre"]) . "#" //2
                . ($persona["segundo_nombre"]) . "#" //3
                . ($persona["primer_apellido"]) . "#" //4
                . ($persona["segundo_apellido"]) . "#" //5
                . htmlentities($persona["per_fecha_nacimiento"]) . "#" //6
                . $persona["tipo_edad"] . "#" //7
                . $persona["edad"] . "#" //8
                . $persona["sexo"] . "#" //9
                . htmlentities($persona["nombre_responsable"]) . "#" //10
                . $persona["id_provincia"] . "#" //11
                . $persona["id_region"] . "#" //12
                . $persona["id_distrito"] . "#" //13
                . $persona["id_corregimiento"] . "#" //14
                . htmlentities($persona["dir_referencia"] . "#" //15
                . htmlentities($persona["nombre_tipo"]) . "#" //16
                . htmlentities($persona["nombre_ocupacion"]) . "#" //17
                . htmlentities($persona["dir_trabajo"]) . "#" //18
                . htmlentities($persona["tel_residencial"]). "#"  //19
                . $persona["id_ocupacion"]. "#"  //20
                . $persona["id_estado_civil"]. "#"  //21
                . $persona["id_escolaridad"]. "#"//22
                . $persona["id_etnia"]. "#"  //23
                . $persona["id_genero"]. "#" //24
                . $persona["id_pais"]. "#"//25
                . htmlentities($persona["tel_trabajo"]) //26
                . htmlentities($persona["dir_referencia_diagnostico"]) . "#" //27
                . $persona["id_provincia_diagnostico"] . "#" //28
                . $persona["id_region_diagnostico"] . "#" //29
                . $persona["id_distrito_diagnostico"] . "#" //30
                . $persona["id_corregimiento_diagnostico"] . "#" //31
        );
    }
}
echo $result;