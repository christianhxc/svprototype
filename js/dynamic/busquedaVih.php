<?php

require_once("libs/helper/helperVih.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");

$config["filtro"] = $_REQUEST["filtro"];

// PAGINADO
$config["paginado"] = Configuration::paginado;
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1; // Pagina de resultados a mostrar
$config["inicio"] = ($config["page"] - 1) * $config["paginado"]; // Inicio del set de datos

$data = helperVih::buscarVih($config);
$permiso["borrar"] = $_REQUEST["B"];
$permiso["reporte"] = $_REQUEST["R"];
$config["total"] = helperVih::buscarVihCantidad($config);

$result = $extra . '<table id="resultados" class="dxgvControl_PlasticBlue" rules="all" cellspacing="0"
                  border="0" style="width: 100%; border-collapse: collapse;">';
$result.= '
            <tr>
                <th class="dxgvHeader_PlasticBlue" scope="">N.</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Instalaci&oacute;n de salud</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Regi&oacute;n</th>
                <th class="dxgvHeader_PlasticBlue" scope="">No. Caso</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Nombre y Apellido</th>
                <th class="dxgvHeader_PlasticBlue" scope="">No. Cedula</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Acciones</th>
           </tr>';

if (is_array($data)) {
    foreach ($data as $data) {
        
        if ($data["id_un"] == null) {
            $data["nombre_un"] = "No disponible";
        }
        $silab = "";
        if ($data["silab"] == 1)
            $silab = '<img title="Formulario Incompleto" border=0 src="../img/iconos/pendiente.png">';
        else if ($data["silab"] == 2)
            $silab = '<img title="Formulario SILAB Actualizado" border=0 src="../img/iconos/valido.png">';
        
        $epiInfo = "";
        if ($data["epiInfo"] == 1)
            $epiInfo = '<img title="Formulario EpiInfo Incompleto" border=0 src="../img/iconos/pendiente.png">';
        else if ($data["epiInfo"] == 2)
            $epiInfo = '<img title="Formulario EpiInfo Actualizado" border=0 src="../img/iconos/valido.png">';
        
        $result.='<tr class="dxgvDataRow_PlasticBlue">';
        $result.='
                <td class="dxgv" width="2%">
                    ' . ($data["id_vih_form"] == "" ? "Sin datos" : htmlentities($data["id_vih_form"])) . '
                </td>
                <td class="dxgv" width="20%">
                    ' . ($data["nombre_un"] == "" ? "No disponible" : htmlentities($data["nombre_un"])) . '
                </td>
                <td class="dxgv" width="10%">
                    ' . ($data["nombre_region"] == "" ? "No disponible" : htmlentities($data["nombre_region"])) . '
                </td>
                <td class="dxgv" width="5%">
                    ' . ($data["semana_epi"] == "" ? "Sin dato" : htmlentities($data["id_vih_form"])) . '
                </td>
                <td class="dxgv" width="10%">
                    ' . ($data["primer_nombre"] == "" ? "Sin dato" : htmlentities($data["primer_nombre"]." ".$data["primer_apellido"])) . '
                </td>
                <td class="dxgv" width="7%">
                    ' . ($data["numero_identificacion"] == "" ? "Sin dato" : htmlentities($data["numero_identificacion"])) . '
                </td>
                <td class="dxgv" width="6%">
                <a href="formulario.php?action=R&tipo=' . $data["id_tipo_identidad"] . '&id=' . $data["numero_identificacion"] . '" class="" title="Editar">'
                . '<img title="Editar" border=0 src="../img/edit.png"></a>';
        if ($permiso["borrar"] == "si")
            $result.='<a href="javascript:borrarVih(' . $data["id_vih_form"] . ')" class="" title="Borrar"><img title="Borrar" border=0 src="../img/Delete.png"></a>';
        if ($permiso["reporte"] == "si")
            $result.='<a href="javascript:reporteIndividual(' . $data["id_vih_form"] . ')" class="" title="Reporte"><img title="Reporte" border=0 src="../img/pdf.png"></a>';
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