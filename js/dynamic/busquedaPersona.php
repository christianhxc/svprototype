<?php

require_once("libs/helper/helperCatalogos.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");

$config["identificador"] = $_REQUEST["id"];
$config["nombre"] = $_REQUEST["n"];
$config["apellido"] = $_REQUEST["p"];
$config["edad_desde"] = $_REQUEST["ed"];
$config["edad_hasta"] = $_REQUEST["ed2"];
$config["tipo_edad"] = $_REQUEST["ted"];
$config["sexo"] = $_REQUEST["sx"];
$config["tipo_id"] = $_REQUEST["tipoid"];

// PAGINADO
$config["paginado"] = Configuration::paginado;
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1; // Pagina de resultados a mostrar
$config["inicio"] = ($config["page"] - 1) * $config["paginado"]; // Inicio del set de datos

$data = helperCatalogos::buscarPersonas($config);
$config["total"] = helperCatalogos::buscarPersonasCantidad($config);

$result = $extra . '<table id="resultados" class="dxgvControl_PlasticBlue" rules="all" cellspacing="0"
                  border="0" style="width: 100%; border-collapse: collapse;">';
$result.= '
            <tr>
                <th class="dxgvHeader_PlasticBlue" scope="">&nbsp;</th>
                <th class="dxgvHeader_PlasticBlue" scope="">No. Identificador</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Primer Nombre</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Segundo Nombre</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Primer Apellido</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Segundo Apellido</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Edad</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Tipo Edad</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Sexo</th>
           </tr>';

if (is_array($data)) {
    foreach ($data as $data) {
        switch ($data["tipo_edad"]) {
            case '1':
                $data["tipo_edad"] = "D&iacute;as";
                break;
            case '2':
                $data["tipo_edad"] = "Meses";
                break;
            case '3':
                $data["tipo_edad"] = "A&ntilde;os";
                break;
            default:
                $data["tipo_edad"] = "No Corresponde";
                break;
        }

        switch ($data["sexo"]) {
            case 'M':
                $data["sexo"] = "Masculino";
                break;
            case 'F':
                $data["sexo"] = "Femenino";
                break;
            case '0':
                $data["sexo"] = "No corresponde";
                break;
        }

        $result.='<tr class="dxgvDataRow_PlasticBlue">';
        $result.='<td class="dxgv" width="5%">
                                <a title="Seleccionar" class="ui-state-default ui-corner-all ui-link-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)"
                                    href="javascript:individuo(' . $data["tipo_identificacion"] . ',\'' . $data["numero_identificacion"] . '\')"><span class="ui-icon ui-icon-check"></span></a>
                                </a>
                                </td>
                                <td class="dxgv" width="10%">
                                    ' . ($data["numero_identificacion"] == "" ? "Sin dato" : htmlentities($data["numero_identificacion"])) . '
                                </td>
                                <td class="dxgv" width="10%">
                                    ' . ($data["primer_nombre"] == "" ? "Sin dato" : htmlentities($data["primer_nombre"])) . '
                                </td>
                                <td class="dxgv" width="10%">
                                    ' . ($data["segundo_nombre"] == "" ? "Sin dato" : htmlentities($data["segundo_nombre"])) . '
                                </td>
                                <td class="dxgv" width="10%">
                                    ' . ($data["primer_apellido"] == "" ? "Sin dato" : htmlentities($data["primer_apellido"])) . '
                                </td>
                                <td class="dxgv" width="10%">
                                    ' . ($data["segundo_apellido"] == "" ? "Sin dato" : htmlentities($data["segundo_apellido"])) . '
                                </td>
                                <td class="dxgv" width="5%">
                                    ' . ($data["edad"] == "0" ? 0 : ($data["edad"] == "" ? "No corresponde" : htmlentities($data["edad"]))) . '
                                </td>
                                <td class="dxgv" width="10%">
                                    ' . $data["tipo_edad"] . '
                                </td>
                                <td class="dxgv" width="10%">
                                    ' . $data["sexo"] . '
                                </td>
                          </tr>';
    }
}
else
    $result.='<tr class="dxgvDataRow_PlasticBlue"><td class="dxgv" align="center" colspan="11">B&uacute;squeda sin resultados.</td></tr>';

$result.='</table>';
//$result.='<tr class="dxgvDataRow_PlasticBlue"><td class="dxgv" colspan="11">';
// ### PAGINADO ###
require_once('libs/PagineoAjax.php');
//           echo "<br/>total:".$config["total"];
//           echo "<br/>page:".$config['page'];
//           echo "<br/>paginado:".$config["paginado"];
$pagineo = new PagineoAjax($config["total"], $config['page'], $config["paginado"], '');
$result.= $pagineo->renderPagineo();
### PAGINADO ###
//$result.='</td></tr></table>';

echo $result;