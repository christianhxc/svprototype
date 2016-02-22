<?php

require_once("libs/helper/helperVacunas.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");

$config["filtro"] = " 1 = 1 ";
$config["filtro"] .= isset($_REQUEST["idCor"]) ? " AND T5.id_corregimiento = ".$_REQUEST["idCor"]:"";
$config["filtro"] .= isset($_REQUEST["idDis"]) ? " AND T6.id_distrito = ".$_REQUEST["idReg"]:"";
$config["filtro"] .= isset($_REQUEST["idReg"]) ? " AND T7.id_region = ".$_REQUEST["idDis"]:"";
$config["filtro"] .= isset($_REQUEST["idPro"]) ? " AND T7.id_provincia = ".$_REQUEST["idPro"]:"";
$config["filtro"] .= isset($_REQUEST["tipoId"]) ? " AND T1.tipo_identificacion = ".$_REQUEST["tipoId"]:"";
$config["filtro"] .= isset($_REQUEST["numId"]) ? " AND T1.numero_identificacion LIKE '%".$_REQUEST["numId"]."%'":"";
$config["filtro"] .= isset($_REQUEST["nombre1"]) ? " AND T3.primer_nombre LIKE '%".$_REQUEST["nombre1"]."%'":"";
$config["filtro"] .= isset($_REQUEST["nombre2"]) ? " AND T3.segundo_nombre LIKE '%".$_REQUEST["nombre2"]."%'":"";
$config["filtro"] .= isset($_REQUEST["apellido1"]) ? " AND T3.primer_apellido LIKE '%".$_REQUEST["apellido1"]."%'":"";
$config["filtro"] .= isset($_REQUEST["apellido2"]) ? " AND T3.segundo_apellido LIKE '%".$_REQUEST["apellido2"]."%'":"";
$config["filtro"] .= isset($_REQUEST["tipoEdad"]) ? " AND T1.per_tipo_edad = ".$_REQUEST["tipoEdad"]:"";
$config["filtro"] .= isset($_REQUEST["edad"]) ? " AND T1.per_edad = ".$_REQUEST["edad"]:"";
$config["filtro"] .= isset($_REQUEST["sexo"]) ? " AND T3.sexo = '".$_REQUEST["sexo"]."'":"";

// PAGINADO
$config["paginado"] = Configuration::paginado;
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1; // Pagina de resultados a mostrar
$config["inicio"] = ($config["page"] - 1) * $config["paginado"]; // Inicio del set de datos

$data = helperVacunas::buscarRegistroDiario($config);
$permiso["borrar"] = $_REQUEST["B"];
$permiso["reporte"] = $_REQUEST["R"];
$config["total"] = helperVacunas::buscarRegisgtroCantidad($config);
//echo "hola";exit;
$result = $extra . '<table id="resultados" class="dxgvControl_PlasticBlue" rules="all" cellspacing="0"
                  border="0" style="width: 90%; border-collapse: collapse;">';
$result.= '
            <tr>
                <th class="dxgvHeader_PlasticBlue" scope="">Identificaci&oacute;n</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Edad</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Nombres</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Sexo</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Region</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Corregimiento</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Acciones</th>
           </tr>';

if (is_array($data)) {
    foreach ($data as $data) {
        
        $edad = ($data["per_tipo_edad"] == "1") ? "D&iacute;as": (($data["per_tipo_edad"] == "2") ? "Meses" : (($data["per_tipo_edad"] == "3") ? "A&ntilde;os" : "N/A"));
        $nombres = ($data["primer_nombre"]).(($data["segundo_nombre"])? " ".$data["segundo_nombre"]:"").(" ".$data["primer_apellido"]).(($data["segundo_apellido"])? " ".$data["segundo_apellido"]:"");
//        echo "<br/>";
//        print_r($data);
//        echo "<br/>";
        $result.='<tr class="dxgvDataRow_PlasticBlue">';
        $result.='
                <td class="dxgv" width="10%">' . ($data["numero_identificacion"] == "" ? "No disponible" : htmlentities($data["numero_identificacion"])) . '</td>
                <td class="dxgv" width="9%">' .$data["per_edad"]." ". $edad . '</td>
                <td class="dxgv" width="30%">' . $nombres . '</td>
                <td class="dxgv" width="4%">' . ($data["sexo"] == "" ? "N/A" : $data["sexo"]) . '</td>
                <td class="dxgv" width="17%">' . ($data["per_region"] == "" ? "Sin dato" : htmlentities($data["per_region"])) . '</td>    
                <td class="dxgv" width="20%">' . ($data["nombre_corregimiento"] == "" ? "Sin dato" : htmlentities($data["nombre_corregimiento"])) . '</td>
                <td class="dxgv" width="10%">
                    <a href="registroDiario.php?action=R&idform=' . $data["id_vac_registro_diario"] . '" class="" title="Editar"> <img title="Editar" border=0 src="../img/edit.png"></a>';
                    if ($permiso["borrar"] == "si")
                        $result.='<a href="javascript:borrarRegistro(' . $data["id_vac_registro_diario"] . ')" class="" title="Borrar"><img title="Borrar" border=0 src="../img/Delete.png"></a>';
                    if ($permiso["reporte"] == "si")
                        $result.='<a href="javascript:reporteIndividual(' . $data["id_vac_registro_diario"] . ')" class="" title="Reporte"><img title="Reporte" border=0 src="../img/pdf.png"></a>';
                    $result.='</td>
                </tr>';
    }
}
else
    $result.='<tr class="dxgvDataRow_PlasticBlue"><td class="dxgv" align="center" colspan="11">B&uacute;squeda sin resultados.</td></tr>';

$result.='</table>';
//$result.='<tr class="dxgvDataRow_PlasticBlue"><td class="dxgv" colspan="11">';
// ### PAGINADO ###
require_once('libs/PagineoAjax.php');
$pagineo = new PagineoAjax($config["total"], $config['page'], $config["paginado"], '');
$result.= $pagineo->renderPagineo();
### PAGINADO ###
//$result.='</td></tr></table>';

echo $result;

//<a href="formulario.php?action=D&idUceti=' . $data["id_flureg"] . '" class="" title="Borrar">'