<?php
require_once("libs/helper/helperSilabRemoto.php");
require_once("libs/helper/helperSilab.php");
require_once("libs/helper/helperCatalogos.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");

$config = array();
$config["identificador"] = $_REQUEST["id"];
$config["nombre"] = $_REQUEST["n"];
$config["apellido"] = $_REQUEST["p"];
$config["tipo_silab"] = $_REQUEST["t"];

// PAGINADO
$config["paginado"] = Configuration::paginado;
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1; // Pagina de resultados a mostrar
$config["inicio"] = ($config["page"] - 1) * $config["paginado"]; // Inicio del set de datos

$data = array();
if ($config["tipo_silab"] == 0)
    $data = helperSilab::getMuestras($config);
else if($config["tipo_silab"] == 1)
    $data = helperSilabRemoto::getMuestras($config);
//print_r($data);
if ($config["tipo_silab"] == 0)
    $config["total"] = helperSilab::getMuestrasCantidad($config);
else
    $config["total"] = helperSilabRemoto::getMuestrasCantidad($config);
//$data = helperCatalogos::buscarPersonas($config);
//$config["total"] = helperCatalogos::buscarPersonasCantidad($config);

$result = $extra . '<table id="resultados" class="dxgvControl_PlasticBlue" rules="all" cellspacing="0"
                  border="0" style="width: 100%; border-collapse: collapse;">';
$result.= '
            <tr>
                <th class="dxgvHeader_PlasticBlue" scope="">&nbsp;</th>
                <th class="dxgvHeader_PlasticBlue" scope="">C&oacute;digo Global</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Correlativo</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Nombres</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Identificaci&oacute;n</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Sexo</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Fecha Toma</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Fecha Recepción</th>
           </tr>';

if (is_array($data)) {
    foreach ($data as $data) {

        $codigoGlobal = ($data["codigo_global_anio"] == "" ? " " : htmlentities($data["codigo_global_anio"])) .
                " - " . ($data["codigo_global_num"] == "" ? " " : htmlentities(helperString::completeZeros($data["codigo_global_num"])));
        $correlativo = ($data["correlativo_alfa"] == "" ? " " : htmlentities($data["correlativo_alfa"])) .
                " - " . ($data["correlativo_num"] == "" ? " " : htmlentities(helperString::completeZeros($data["correlativo_num"])));
        $result.='<tr class="dxgvDataRow_PlasticBlue">';
        $result.='<td class="dxgv" width="5%">
                                <a title="Seleccionar" class="ui-state-default ui-corner-all ui-link-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)"
                                    href="javascript:muestraSilab(' . $data["id_muestra"] . ','.$config["tipo_silab"].',0)"><span class="ui-icon ui-icon-check"></span></a>
                                </a>
                                </td>
                                <td class="dxgv" width="10%">
                                    ' . $codigoGlobal . '
                                </td>
                                <td class="dxgv" width="10%">
                                    ' . $correlativo . '
                                </td>
                                <td class="dxgv" width="10%">
                                    ' . ($data["nombre"] == "" ? "Sin dato" : htmlentities($data["nombre"])) . '
                                </td>
                                <td class="dxgv" width="10%">
                                    ' . ($data["identificacion"] == "" ? "Sin dato" : htmlentities($data["identificacion"])) . '
                                </td>
                                <td class="dxgv" width="10%">
                                    ' . $data["sexo"] . '
                                </td>
                                <td class="dxgv" width="5%">
                                    ' . ($data["fecha_toma"] == "" ? "Sin dato" : htmlentities($data["fecha_toma"])) . '
                                </td>
                                <td class="dxgv" width="10%">
                                    ' . ($data["fecha_recepcion"] == "" ? "Sin dato" : htmlentities($data["fecha_recepcion"])) . '
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