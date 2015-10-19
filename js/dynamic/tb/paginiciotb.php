<?php

require_once("libs/helper/helpertb.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");

$config["filtro"] = $_REQUEST["filtro"];


$data = helpertb::buscarqtrSintRes($config);
//$config["total"] = helpertb::buscartbCantidad($config);
//
//$result = $extra . '<table id="resultados" class="dxgvControl_PlasticBlue" rules="all" cellspacing="0"
//                  border="0" style="width: 100%; border-collapse: collapse;">';
//$result.= '
//            <tr>
//                <th class="dxgvHeader_PlasticBlue" scope="">N.</th>
//                <th class="dxgvHeader_PlasticBlue" scope="">Instalaci&oacute;n de salud</th>
//                <th class="dxgvHeader_PlasticBlue" scope="">Id Paciente</th>
//                <th class="dxgvHeader_PlasticBlue" scope="">Regi&oacute;n Paciente</th>
//                <th style="text-align: center" class="dxgvHeader_PlasticBlue" scope="">Fuente</th>
//                <th style="text-align: center" class="dxgvHeader_PlasticBlue" scope="">Acciones</th>
//           </tr>';
//
//if (is_array($data)) {
//    foreach ($data as $data) {
//        switch ($data["tipo_edad"]) {
//            case '1':
//                $data["tipo_edad"] = "D&iacute;as";
//                break;
//            case '2':
//                $data["tipo_edad"] = "Meses";
//                break;
//            case '3':
//                $data["tipo_edad"] = "A&ntilde;os";
//                break;
//            default:
//                $data["tipo_edad"] = "No Corresponde";
//                break;
//        }
//
//        switch ($data["sexo"]) {
//            case 'M':
//                $data["sexo"] = "Masculino";
//                break;
//            case 'F':
//                $data["sexo"] = "Femenino";
//                break;
//            case '0':
//                $data["sexo"] = "No corresponde";
//                break;
//        }
//        if ($data["unidad_disponible"] == '0') {
//            $data["nombre_un"] = "No disponible";
//        }
//        $evento = $data["cie_10_1"] . " - " . $data["nombre_evento"];
//        $source_entry = $data['source_entry'];
//        $pendiente_silab = $data['pendiente_silab'];
//        $pendiente_uceti = $data['pendiente_uceti'];
//        $actualizacion_silab = $data['actualizacion_silab'];
//        $fuente = " ";
//        $estado_silab = " ";
//        $estado_uceti = " ";
//
//        switch ($source_entry) {
//            case '1':
//                $fuente = '<table>
//                    <tr>
//                        <td><img width=16 height=16 src="../img/iconos/tablet.png"></td><td>Tablet</td>
//                    </tr>
//                        </table>';
//                break;
//            case '0':
//                $fuente = '<table>
//                    <tr>
//                        <td><img width=16 height=16 src="../img/iconos/pc.png"></td><td>Web</td>
//                    </tr>
//                        </table>';
//                break;
//        }
//
//        require_once ('libs/caus/clsCaus.php');
//        $permisoBorrar = false;
//        if (clsCaus::validarSeccion(ConfigurationCAUS::influenza, ConfigurationCAUS::Borrar)) {
//            $permisoBorrar = true;
//        }
//        $borrarUceti = "";
//        
//        if ($permisoBorrar) {
//            $borrarUceti = '<a href="javascript:borrarUceti(' . $data["id_flureg"] . ')" class="" title="Borrar">'
//                    . '<img title="Borrar" border=0 src="../img/Delete.png"></a>';
//        }
//        $result.='<tr class="dxgvDataRow_PlasticBlue">';
//        $result.='
//                                <td class="dxgv" width="2%">
//                                    ' . ($data["id_tb"] == "" ? "Sin datos" : htmlentities($data["id_tb"])) . '
//                                </td>
//                                <td class="dxgv" width="15%">
//                                    ' . ($data["nombre_un"] == "" ? "No disponible" : htmlentities($data["nombre_un"])) . '
//                                </td>
//                                <td class="dxgv" width="8%">
//                                    ' . ($data["numero_identificacion"] == "" ? "Sin dato" : htmlentities($data["numero_identificacion"])) . '
//                                </td>
//                                <td class="dxgv" width="10%">
//                                    ' . ($data["nombre_region"] == "" ? "No disponible" : htmlentities($data["nombre_region"])) . '
//                                </td>
//                                <td class="dxgv" width="5%">
//                                    ' . $fuente . '
//                                </td>
//                                <td class="dxgv" width="8%">
//                  <a href="formulario.php?action=R&tipo=' . $data["tipo_identificacion"] . '&id=' . $data["numero_identificacion"] . '" class="" title="Editar">'
//                . '<img title="Editar" border=0 src="../img/edit.png"></a>' . $borrarUceti;
//        if ($pendiente_uceti == '1') {
//            $result .= '<a href="javascript:reporteUceti(' . $data["id_flureg"] . ')" class="" title="Descarga Formulario">'
//                    . '<img width=16 height=16 title="PDF" border=0 src="../img/iconos/pdf.png"></a>';
//        }
//        $result .= '</td></tr>';
//    }
//}
//else
//    $result.='<tr class="dxgvDataRow_PlasticBlue"><td class="dxgv" align="center" colspan="11">B&uacute;squeda sin resultados.</td></tr>';
//
//$result.='</table>';
//
////$result.='</td></tr></table>';

echo json_encode($data);

//<a href="formulario.php?action=D&idUceti=' . $data["id_flureg"] . '" class="" title="Borrar">'