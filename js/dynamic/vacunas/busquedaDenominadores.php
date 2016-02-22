<?php

require_once("libs/helper/helperVacunas.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");

$config["filtro"] = $_REQUEST["filtro"];

// PAGINADO
$config["paginado"] = Configuration::paginado;
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1; // Pagina de resultados a mostrar
$config["inicio"] = ($config["page"] - 1) * $config["paginado"]; // Inicio del set de datos

$data = helperVacunas::buscarDenominador($config);
$permiso["borrar"] = $_REQUEST["B"];
$permiso["reporte"] = $_REQUEST["R"];
$config["total"] = helperVacunas::buscarDenominadorCantidad($config);
//echo "hola";exit;
$result = $extra . '<table id="resultados" class="dxgvControl_PlasticBlue" rules="all" cellspacing="0"
                  border="0" style="width: 90%; border-collapse: collapse;">';
$result.= '
            <tr>
                <th class="dxgvHeader_PlasticBlue" scope="">N.</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Nivel</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Provincia</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Regi&oacute;n</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Distrito</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Corregimiento</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Instalaci&oacute;n de Salud</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Acciones</th>
           </tr>';

if (is_array($data)) {
    foreach ($data as $data) {
        $status = ($data["status"] == "1") ? "Activo":"No activo";
        $nivel = "Sin dato";
        switch ($data["nivel"]) {
            case 1: $nivel = "Nacional"; break;
            case 2: $nivel = "Provincia"; break;
            case 3: $nivel = "Region"; break;
            case 4: $nivel = "Distrito"; break;
            case 5: $nivel = "Corregimiento"; break;
            case 6: $nivel = "Ins. de Salud"; break;
        }
        $result.='<tr class="dxgvDataRow_PlasticBlue">';
        $result.='
                <td class="dxgv" width="3%">
                    ' . ($data["id_vac_denominador"] == "" ? "" : htmlentities($data["id_vac_denominador"])) . '
                </td>
                <td class="dxgv" width="11%">'.$nivel.'</td>
                <td class="dxgv" width="15%">
                    ' . ($data["nombre_provincia"] == "" ? "Sin dato" : htmlentities($data["nombre_provincia"])) . '
                </td>
                <td class="dxgv" width="15%">
                    ' . ($data["nombre_region"] == "" ? "Sin dato" : htmlentities($data["nombre_region"])) . '
                </td>
                <td class="dxgv" width="15%">
                    ' . ($data["nombre_distrito"] == "" ? "Sin dato" : htmlentities($data["nombre_distrito"])) . '
                </td>
                <td class="dxgv" width="18%">
                    ' . ($data["nombre_corregimiento"] == "" ? "Sin dato" : htmlentities($data["nombre_corregimiento"])) . '
                </td>
                <td class="dxgv" width="18%">
                    ' . ($data["nombre_un"] == "" ? "Sin dato" : htmlentities($data["nombre_un"])) . '
                </td>
                <td class="dxgv" width="5%">
                <a href="denominadores.php?action=R&idform=' . $data["id_vac_denominador"] . '" class="" title="Editar">'
                . '<img title="Editar" border=0 src="../img/edit.png"></a>';
        if ($permiso["borrar"] == "si")
            $result.='<a href="javascript:borrarRegistro(' . $data["id_vac_denominador"] . ')" class="" title="Borrar"><img title="Borrar" border=0 src="../img/Delete.png"></a>';
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