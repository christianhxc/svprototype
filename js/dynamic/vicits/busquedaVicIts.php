<?php

require_once("libs/helper/helperVicIts.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");

$config["filtro"] = $_REQUEST["filtro"];

// PAGINADO
$config["paginado"] = Configuration::paginado;
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1; // Pagina de resultados a mostrar
$config["inicio"] = ($config["page"] - 1) * $config["paginado"]; // Inicio del set de datos

$data = helperVicIts::buscarVicitsTabla($config);
$permiso["borrar"] = $_REQUEST["B"];
$permiso["reporte"] = $_REQUEST["R"];
$config["total"] = helperVicIts::buscarVicitsCantidad($config);

$result = $extra . '<table id="resultados" class="dxgvControl_PlasticBlue" rules="all" cellspacing="0"
                  border="0" style="width: 100%; border-collapse: collapse;">';
$result.= '
            <tr>
                <th class="dxgvHeader_PlasticBlue" scope="">N.</th>
                <th class="dxgvHeader_PlasticBlue" scope="">F. Consulta</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Identificador</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Nombres y apellidos</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Instalaci&oacute;n de salud</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Sem. Epi.</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Tipo consulta</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Acciones</th>
           </tr>';

if (is_array($data)) {
    foreach ($data as $data) {
        
        
        $result.='<tr class="dxgvDataRow_PlasticBlue">';
        $result.='
                <td class="dxgv" width="2%">
                    ' . ($data["id_vicits_form"] == "" ? "Sin datos" : htmlentities($data["id_vicits_form"])) . '
                </td>
                <td class="dxgv" width="10%">
                    ' . ($data["fecha_consulta"] == "" ? "No disponible" : htmlentities($data["fecha_consulta"])) . '
                </td>
                <td class="dxgv" width="10%">
                    ' . ($data["numero_identificacion"] == "" ? "No disponible" : htmlentities($data["numero_identificacion"])) . '
                </td>
                <td class="dxgv" width="25%">
                    ' . ($data["nombre"] == "" ? "Sin dato" : htmlentities($data["nombre"])) . '
                </td>
                <td class="dxgv" width="25%">
                    ' . ($data["nombre_un"] == "" ? "Sin dato" : htmlentities($data["nombre_un"])) . '
                </td>
                <td class="dxgv" width="8%">
                    ' . ($data["semana_epi"] == "" ? "Sin dato" : htmlentities($data["semana_epi"])) . '
                </td>
                <td class="dxgv" width="10%">
                    ' . ($data["motivo_consulta"] == "" ? "Sin dato" : htmlentities($data["motivo_consulta"])) . '
                </td>
                <td class="dxgv" width="10%">
                <a href="formulario.php?action=R&tipo=' . $data["id_tipo_identidad"] . '&id=' . $data["numero_identificacion"]. '&id_form=' . $data["id_vicits_form"] . '" class="" title="Editar">'
                . '<img title="Editar" border=0 src="../img/edit.png"></a>';
        if ($permiso["borrar"] == "si")
            $result.='<a href="javascript:borrarVicIts(' . $data["id_vicits_form"] . ')" class="" title="Borrar"><img title="Borrar" border=0 src="../img/Delete.png"></a>';
        if ($permiso["reporte"] == "si")
            $result.='<a href="javascript:reporteIndividual(' . $data["id_vicits_form"] . ',\''.$data["sexo"].'\')" class="" title="Reporte"><img title="Reporte" border=0 src="../img/pdf.png"></a>';
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