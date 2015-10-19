<?php

require_once("libs/helper/helperNotic.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");

$config["filtro"] = $_REQUEST["filtro"];

// PAGINADO
$config["paginado"] = Configuration::paginado;
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1; // Pagina de resultados a mostrar
$config["inicio"] = ($config["page"] - 1) * $config["paginado"]; // Inicio del set de datos

$data = helperNotic::buscarNotic($config);
$permiso["borrar"] = $_REQUEST["B"];
$permiso["reporte"] = $_REQUEST["R"];
$config["total"] = helperNotic::buscarNoticCantidad($config);

$result = $extra . '<table id="resultados" class="dxgvControl_PlasticBlue" rules="all" cellspacing="0"
                  border="0" style="width: 100%; border-collapse: collapse;">';
$result.= '
            <tr>
                <th class="dxgvHeader_PlasticBlue" scope="">N.</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Instalaci&oacute;n de salud</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Regi&oacute;n</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Evento</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Sem. Epi</th>'
                .'<th class="dxgvHeader_PlasticBlue" scope="">Id Paciente</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Dx sospecha</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Acciones</th>
           </tr>';

if (is_array($data)) {
    foreach ($data as $data) {
        
        if ($data["id_un"] == null) {
            $data["nombre_un"] = "No disponible";
        }        
        $alerta = "";
        if ($data["estado_diag1"] == 1)
            $alerta = '<img title="Caso sospechoso" border=0 src="../img/iconos/pendiente.png">';
        
        $result.='<tr class="dxgvDataRow_PlasticBlue">';
        $result.='
                <td class="dxgv" width="3%">
                    ' . ($data["id_notic"] == "" ? "Sin datos" : htmlentities($data["id_notic"])) . '
                </td>
                <td class="dxgv" width="18%">
                    ' . ($data["nombre_un"] == "" ? "No disponible" : htmlentities($data["nombre_un"])) . '
                </td>
                <td class="dxgv" width="12%">
                    ' . ($data["nombre_region_persona"] == "" ? "No disponible" : htmlentities($data["nombre_region_persona"])) . '
                </td>
                <td class="dxgv" width="28%">
                    ' . ($data["nom_eve1"] == "" ? "No disponible" : htmlentities($data["nom_eve1"])) . '
                </td>
                <td class="dxgv" width="5%">
                    ' . ($data["semana_epi"] == "" ? "Sin dato" : htmlentities($data["semana_epi"])) . '
                </td>'
                .'<td class="dxgv" width="9%">
                    ' . ($data["numero_identificacion"] == "" ? "Sin dato" : htmlentities($data["numero_identificacion"])) . '
                </td>
                <td class="dxgv" width="3%" style="text-align:center">'. $alerta .'</td>
                <td class="dxgv" width="8%">
                <a href="formulario.php?action=R&idForm=' . $data["id_notic"] . '" class="" title="Editar">'
                . '<img title="Editar" border=0 src="../img/edit.png"></a>';
        if ($permiso["borrar"] == "si")
            $result.='<a href="javascript:borrarFormulario(' . $data["id_notic"] . ')" class="" title="Borrar"><img title="Borrar" border=0 src="../img/Delete.png"></a>';
        if ($permiso["reporte"] == "si")
            $result.='<a href="javascript:reporteIndividual(' . $data["id_notic"] . ')" class="" title="Reporte"><img title="Reporte" border=0 src="../img/pdf.png"></a>';
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