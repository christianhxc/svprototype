<?php

require_once("libs/helper/helperLdbiRequesicion.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");

$config["filtro"] = $_REQUEST["filtro"];
$config["status"] = $_REQUEST["status"];
$config["fecha_inicio"] = $_REQUEST["fecha_inicio"];
$config["fecha_final"] = $_REQUEST["fecha_final"];
$config["provincia"] = $_REQUEST["provincia"];
$config["region"] = $_REQUEST["region"];
// PAGINADO
$config["paginado"] = Configuration::paginado;
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1; // Pagina de resultados a mostrar
$config["inicio"] = ($config["page"] - 1) * $config["paginado"]; // Inicio del set de datos

$data = helperLdbiRequesicion::buscar($config);
$config["total"] = helperLdbiRequesicion::buscarCantidad($config);

$result = $extra . '<table id="resultados" class="dxgvControl_PlasticBlue" rules="all" cellspacing="0"
                  border="0" style="width: 100%; border-collapse: collapse;">';
$result.= '
            <tr>
                <th class="dxgvHeader_PlasticBlue" scope="">N.</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Fecha</th>
                <th class="dxgvHeader_PlasticBlue" scope="">N&uacute;mero</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Regi&oacute;n</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Instalaci&oacute;n de salud</th>
                <th class="dxgvHeader_PlasticBlue" scope="">A&nacute;o</th>
                <th style="text-align: center" class="dxgvHeader_PlasticBlue" scope="">Estado</th>
                <th style="text-align: center" class="dxgvHeader_PlasticBlue" scope="">Acciones</th>
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
                                <td class="dxgv" width="2%">
                                    ' . ($data["id_requesicion"] == "" ? "Sin datos" : htmlentities($data["id_requesicion"])) . '
                                </td>
                                <td class="dxgv" width="3%">
                                    ' . ($data["fh_requesicion"] == "" ? "No disponible" : helperString::toDateView($data["fh_requesicion"])) . '
                                </td>
                                <td class="dxgv" width="5%">
                                    ' . ($data["no_requesicion"] == "" ? "No disponible" : htmlentities($data["no_requesicion"])) . '
                                </td>
                                <td class="dxgv" width="15%">
                                    ' . ($data["nombre_region"] == "" ? "No disponible" : htmlentities($data["nombre_region"])) . '
                                </td>
                                <td class="dxgv" width="20%">
                                    ' . ($data["nombre_un"] == "" ? "No disponible" : htmlentities($data["nombre_un"])) . '
                                </td>
                                <td style="text-align: center" class="dxgv" width="3%">
                                    ' . ($data["fh_requesicion"] == "" ? "No disponible" : substr($data["fh_requesicion"],0,4)) . '
                                </td>
                                <td class="dxgv" width="5%">
                                    ' . $statusTexto . '
                                </td>
                                <td class="dxgv" width="3%">
                  <a href="requesicion.php?action=R&id=' . $data["id_requesicion"] . '" class="" title="Editar">'
            . '<img title="Editar" border=0 src="../img/edit.png"></a>' . $borrarUceti;
            $result .= '<a href="javascript:exportar(' . $data["id_requesicion"] . ')" class="" title="Descarga Formulario">'
                . '<img width=16 height=16 title="PDF" border=0 src="../img/iconos/pdf.png"></a>';
        $result .= '</td></tr>';
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