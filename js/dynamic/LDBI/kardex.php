<?php

require_once("libs/dal/ldbi/dalLdbiKardex.php");
require_once("libs/PagineoAjax.php");
require_once('libs/helper/helperString.php');
require_once("libs/Configuration.php");

$kardex = array();
$saldo = 0;

$config["bodega_central"] = $_REQUEST["bodega_central"];
$config["id_region"] = $_REQUEST["id_region"];
$config["id_un"] = $_REQUEST["id_un"];
$config["id_insumo"] = $_REQUEST["id_insumo"];
$config["fh_inicio"] = helperString::toDate($_REQUEST["fh_inicio"]);
$config["fh_fin"] = helperString::toDate($_REQUEST["fh_fin"]);

$data = dalLdbiKardex::getInventario($config);
$config["total"] = count($data);

// PAGINADO
$config["paginado"] = $config["total"];
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1; // Pagina de resultados a mostrar
$config["inicio"] = ($config["page"] - 1) * $config["paginado"]; // Inicio del set de datos

$result = '<table width="100%">
        <tr>
            <td align="right">
                <a href="#" class="botonExcel" title="Exportar a Excel">
                    <img width="30px" src="../img/excel-xls-icon.png">
                </a>&nbsp;
                <a href="#" class="botonPdf" alt="Exportar a PDF" title="Exportar a PDF">
                    <img width="32px" src="../img/pdf-icon.png">
                </a>
            </td>
        </tr>
    </table>
    <form action="#" method="post" target="_blank" id="FormularioExportacion">
        <input type="hidden" id="datos_a_enviar" name="datos_a_enviar" />
    </form>';

$result .= $extra . '<table id="resultados" class="dxgvControl_PlasticBlue" rules="all" cellspacing="0"
                  border="0" style="width: 1000px; border-collapse: collapse;">';
$result.= '
            <tr>
                <th class="dxgvHeader_PlasticBlue" scope="">Fecha</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Descripcion</th>
                <th class="dxgvHeader_PlasticBlue" scope="">No. Lote</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Entradas</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Salidas</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Saldo</th>
           </tr>';

function getDescripcion($data){
    $GLOBALS['kardex'][] = $data;
    if ($data["no_documento"] != '') return "No. Documento " . $data["no_documento"];
    if ($data["no_envio"] != '') return "No. Envio " . $data["no_envio"];
    if ($data["no_nota"] != '') return "No. Nota Ajuste " . $data["no_nota"];
    if ($data["no_registro_diario"] != '') return "No. Registro Diario " . $data["no_registro_diario"];
}

function getEntradas($data){
    return $data["unidades"] > 0 ? $data["unidades"] : 0;
}

function getSalidas($data){
    return $data["unidades"] < 0 ? $data["unidades"] * -1 : 0;
}

function getSaldo($data, $key){
    $total = 0;
    if (count($GLOBALS['kardex']) == 1){
        $total = $data["unidades"];
    } else {
        $total = $GLOBALS['saldo'] + $data["unidades"];
    }

    $GLOBALS['saldo'] = $total;
    return $total;
}

if (is_array($data)) {
    foreach ($data as $key => $data) {
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
        if ($data["unidad_disponible"] == '0') {
            $data["nombre_un"] = "No disponible";
        }
        $evento = $data["cie_10_1"] . " - " . $data["nombre_evento"];
        $source_entry = $data['source_entry'];
        $pendiente_silab = $data['pendiente_silab'];
        $status = $data['status'];
        $actualizacion_silab = $data['actualizacion_silab'];
        $fuente = " ";
        $estado_silab = " ";
        $estado_uceti = " ";
        switch ($status) {
            case '2':
                $statusTexto = '<table>
                    <tr>
                        <td><img width=16 height=16 src="../img/iconos/valido.png"></td><td>Entregado</td>
                    </tr>
                        </table>';
                break;
            case '1':
                $statusTexto = '<table>
                    <tr>
                        <td><img width=16 height=16 src="../img/iconos/ventanilla.PNG"></td><td>En tr&aacute;nsito</td>
                    </tr>
                        </table>';
                break;
            case '0':
                $statusTexto = '<table>
                    <tr>
                        <td><img width=16 height=16 src="../img/iconos/pendiente.png"></td><td>Pendiente</td>
                    </tr>
                        </table>';
                break;
        }
        require_once ('libs/caus/clsCaus.php');
        $permisoBorrar = false;
        if (clsCaus::validarSeccion(ConfigurationCAUS::VacLdbi, ConfigurationCAUS::Borrar)) {
            $permisoBorrar = true;
        }
        $borrarUceti = "";

        if ($permisoBorrar) {
            $borrarUceti = '<a href="javascript:borrar(' . $data["id_requesicion"] . ',\'' . $data["no_requesicion"] . '\')" class="" title="Borrar">'
                . '<img title="Borrar" border=0 src="../img/Delete.png"></a>';
        }
        $result.='<tr class="dxgvDataRow_PlasticBlue">';
        $result.='
                                <td class="dxgv" style="width: 80px">
                                    ' . ($data["fh_inventario"] == "" ? "No disponible" : $data["fh_inventario"]) . '
                                </td>
                                <td class="dxgv" style="width: 350px">
                                    ' . getDescripcion($data) . '
                                </td>
                                <td class="dxgv" style="width: 150px">
                                    ' . ($data["no_lote"] == "" ? "No disponible" : htmlentities($data["no_lote"])) . '
                                </td>
                                <td class="dxgv" style="width: 80px">
                                    ' . getEntradas($data) . '
                                </td>
                                <td class="dxgv" style="width: 80px">
                                    ' . getSalidas($data) . '
                                </td>
                                <td class="dxgv" style="width: 80px">
                                    ' . getSaldo($data, $key) . '
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
$pagineo = new PagineoAjax($config["total"], $config['page'], $config["paginado"], '');
$result.= $pagineo->renderPagineo();
### PAGINADO ###
//$result.='</td></tr></table>';

echo $result;
?>
<script language="javascript">
    $(document).ready(function() {
        $(".botonExcel").click(function(event) {
            $("#datos_a_enviar").val( $("<div>").append( $("#resultados").eq(0).clone()).html());
            $("#FormularioExportacion").attr("action", "../vacunas/reportes/reporteexcel.php")
            $("#FormularioExportacion").submit();
        });
        $(".botonPdf").click(function(event) {
            $("#datos_a_enviar").val( $("<div>").append( $("#resultados").eq(0).clone()).html());
            $("#FormularioExportacion").attr("action", "../vacunas/reportes/reportepdf.php")
            $("#FormularioExportacion").submit();
        });
    });
</script>