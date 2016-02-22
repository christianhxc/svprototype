<?php

require_once("libs/helper/helperVacunas.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");

$config["filtro"] = $_REQUEST["filtro"];

// PAGINADO
$config["paginado"] = Configuration::paginado;
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1; // Pagina de resultados a mostrar
$config["inicio"] = ($config["page"] - 1) * $config["paginado"]; // Inicio del set de datos

$data = helperVacunas::buscarEsquema($config);
$permiso["borrar"] = $_REQUEST["B"];
$permiso["reporte"] = $_REQUEST["R"];
$config["total"] = helperVacunas::buscarEsqCantidad($config);

$result = $extra . '<table id="resultados" class="dxgvControl_PlasticBlue" rules="all" cellspacing="0"
                  border="0" style="width: 80%; border-collapse: collapse;">';
$result.= '
            <tr>
                <th class="dxgvHeader_PlasticBlue" scope="">N.</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Nombre Escenario</th>
                <th class="dxgvHeader_PlasticBlue" scope="">C&oacute;digo</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Fec. Vigencia</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Estado</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Acciones</th>
           </tr>';

if (is_array($data)) {
    foreach ($data as $data) {
        
        $status = ($data["status"] == "1") ? "Activo":"No activo";
        
        $result.='<tr class="dxgvDataRow_PlasticBlue">';
        $result.='
                <td class="dxgv" width="10%">
                    ' . ($data["id_esquema"] == "" ? "" : htmlentities($data["id_esquema"])) . '
                </td>
                <td class="dxgv" width="40%">
                    ' . ($data["nombre_esquema"] == "" ? "No disponible" : htmlentities($data["nombre_esquema"])) . '
                </td>
                <td class="dxgv" width="15%">
                    ' . ($data["codigo_esquema"] == "" ? "No disponible" : htmlentities($data["codigo_esquema"])) . '
                </td>
                <td class="dxgv" width="15%">
                    ' . ($data["fecha_vigencia"] == "" ? "Sin dato" : htmlentities($data["fecha_vigencia"])) . '
                </td>
                <td class="dxgv" width="10%">
                    ' . $status . '
                </td>
                <td class="dxgv" width="10%">
                <a href="formulario.php?action=R&idform=' . $data["id_esquema"] . '" class="" title="Editar">'
                . '<img title="Editar" border=0 src="../img/edit.png"></a>';
        if ($permiso["borrar"] == "si")
            $result.='<a href="javascript:borrarEsquema(' . $data["id_esquema"] . ')" class="" title="Borrar"><img title="Borrar" border=0 src="../img/Delete.png"></a>';
//        if ($permiso["reporte"] == "si")
//            $result.='<a href="javascript:reporteIndividual(' . $data["id_esquema"] . ')" class="" title="Reporte"><img title="Reporte" border=0 src="../img/pdf.png"></a>';
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