<?php

require_once("libs/helper/helperEno.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");

$config["filtro"] = $_REQUEST["filtro"];

// PAGINADO
$config["paginado"] = Configuration::paginado;
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1; // Pagina de resultados a mostrar
$config["inicio"] = ($config["page"] - 1) * $config["paginado"]; // Inicio del set de datos

$data = helperEno::buscarEno($config);
$permiso["borrar"] = $_REQUEST["B"];
$permiso["reporte"] = $_REQUEST["R"];
$config["total"] = helperEno::buscarEnoCantidad($config);

$result = $extra.'<table id="resultados" class="dxgvControl_PlasticBlue" rules="all" cellspacing="0"
                  border="0" style="width: 100%; border-collapse: collapse;">';
$result.= '
            <tr>
                <th class="dxgvHeader_PlasticBlue" scope="">N.</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Instalaci&oacute;n de salud</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Servicio</th>
                <th class="dxgvHeader_PlasticBlue" scope="" colspan="2" style="text-align:center;" >A&ntilde;o y Sem. Epi</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Acciones</th>
           </tr>';

if (is_array($data)) {
    foreach ($data as $data) {
        
        
        $result.='<tr class="dxgvDataRow_PlasticBlue">';
        $result.='
                <td class="dxgv" width="8%">
                    '.($data["id_enc"] == "" ? "Sin datos" : htmlentities($data["id_enc"])).'
                </td>
                <td class="dxgv" width="50%">
                    '.($data["nombre_un"] == "" ? "Sin dato" : htmlentities($data["nombre_un"])).'
                </td>
                <td class="dxgv" width="20%">
                    '.($data["nombre_servicio"] == "" ? "Sin dato" : htmlentities($data["nombre_servicio"])).'
                </td>
                <td class="dxgv" width="9%">
                    '.($data["anio"] == "" ? "Sin dato" : htmlentities($data["anio"])).'
                </td>
                <td class="dxgv" width="6%">
                    '.($data["semana_epi"] == "" ? "Sin dato" : htmlentities($data["semana_epi"])).'
                </td>
                <td class="dxgv" width="7%">
                <a href="formulario.php?action=R&idform='.$data["id_enc"].'" class="" title="Editar">'
               .'<img title="Editar" border=0 src="../img/edit.png"></a>';
        if ($permiso["borrar"] == "si")
            $result.='<a href="javascript:borrarEno('.$data["id_enc"].')" class="" title="Borrar"><img title="Borrar" border=0 src="../img/Delete.png"></a>';
        if ($permiso["reporte"] == "si")
            $result.='<a href="javascript:reporteIndividual('.$data["id_enc"].')" class="" title="Reporte"><img title="Reporte" border=0 src="../img/pdf.png"></a>';
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

//<a href="formulario.php?action=D&idUceti='.$data["id_flureg"].'" class="" title="Borrar">'