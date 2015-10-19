<?php

require_once("libs/helper/helperMat.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");

$config["filtro"] = $_REQUEST["filtro"];

// PAGINADO
$config["paginado"] = Configuration::paginado;
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1; // Pagina de resultados a mostrar
$config["inicio"] = ($config["page"] - 1) * $config["paginado"]; // Inicio del set de datos

$data = helperMat::buscarEscenario($config);
$permiso["borrar"] = $_REQUEST["B"];
$permiso["reporte"] = $_REQUEST["R"];
$config["total"] = helperMat::buscarEscenarioCantidad($config);

$result = $extra . '<table id="resultados" class="dxgvControl_PlasticBlue" rules="all" cellspacing="0"
                  border="0" style="width: 100%; border-collapse: collapse;">';
$result.= '
            <tr>
                <th class="dxgvHeader_PlasticBlue" scope="">N.</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Evento</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Algoritmo</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Nivel Geo</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Nombre nivel Geogr&aacute;fico</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Acci&oacute;n</th>
           </tr>';

if (is_array($data)) {
    foreach ($data as $data) {
        $nivelGeo = "";
        switch ($data["nivel_geo"]){
            case 1 : $nivelGeo = "Nacional"; break;
            case 2 : $nivelGeo = "Provincia"; break;
            case 3 : $nivelGeo = "Region"; break;
            case 4 : $nivelGeo = "Distrito"; break;
            default : $nivelGeo = "Corregimiento"; break;
        }
        $result .=  '<tr class="dxgvDataRow_PlasticBlue">';
        $result .=  '<td class="dxgv" width="2%">'.($data["id_escenario"] == "" ? "Sin datos" : htmlentities($data["id_escenario"])).'</td>
                    <td class="dxgv" width="43%">'.($data["nombre_evento"] == "" ? "No disponible" : htmlentities($data["cie_10_1"]." - ".$data["nombre_evento"])).'</td>
                    <td class="dxgv" width="15%">'.($data["algoritmo"] == "" ? "No disponible" : htmlentities($data["algoritmo"])).'</td>
                    <td class="dxgv" width="10%">'.$nivelGeo.'</td>
                    <td class="dxgv" width="25%">'.($data["nivel_geografico"] == "" ? "Sin dato" : htmlentities($data["nivel_geografico"])).'</td>
                    <td class="dxgv" width="5%">
                        <a href="javascript:editarEscenario('.$data["id_escenario"].')" class="" title="Editar"><img title="Editar" border=0 src="../img/edit.png"></a>&nbsp;';
                    //if ($permiso["borrar"] == "si")
                        $result.='<a href="javascript:eliminarEscenario('.$data["id_escenario"].')" class="" title="Borrar"><img title="Borrar" border=0 src="../img/Delete.png"></a>';
        $result.='</td></tr>';
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