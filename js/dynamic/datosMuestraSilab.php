<?php

require_once("libs/helper/helperSilabRemoto.php");
require_once("libs/helper/helperSilab.php");
require_once("libs/helper/helperCatalogos.php");
require_once("libs/Etiquetas.php");
require_once("libs/ConfigurationHospitalInfluenza.php");
header('Content-type: text/javascript');

$tipo_silab = $_REQUEST["tipo_silab"];
$tipoSilab = "";
$idMuestra = $_REQUEST["id_muestra"];
$muestra = array();
if ($tipo_silab == 0) {
    $muestra = helperSilab::getMuestra($idMuestra);
    $tipoSilab = ConfigurationHospitalInfluenza::getSilabLocal();
} else if ($tipo_silab == 1) {
    $muestra = helperSilabRemoto::getMuestra($idMuestra);
    $tipoSilab = Etiquetas::silab_remoto;
}
//echo "<br/>Muestra: ".$idMuestra." ";
if ($muestra["muestra"]["id_situacion"] == 3 || $muestra["muestra"]["id_situacion"] == 4 || $muestra["muestra"]["id_situacion"] == 7 || $muestra["muestra"]["id_situacion"] == 8) {

    $arrayMuestra = "";
    $result = "";
    $result .= "&nbsp;&nbsp;&nbsp;<a id='toggle" . $idMuestra . "' onmouseout='RollOut(this)' onmouseover='RollOver(this)' style='text-decoration: none; font-weight: bold;' class='' href='javascript:verMuestra(" . $idMuestra . ");'>Ver M&aacute;s</a>";
    $result .= " &nbsp;<a id='borrarMuestra" . $idMuestra . "' onmouseout='RollOut(this)' onmouseover='RollOver(this)' style='text-decoration: none; font-weight: bold;' class='' href='javascript:borrarMuestra(" . $idMuestra . ");'>Borrar Muestra</a>";
    
    $result .=
            "<table>
            <tr>
                <td width='300px'>C&oacute;digo Global: " . $muestra["muestra"]["codigo_global_anio"] . " - " . $muestra["muestra"]["codigo_global"] .
            "</td>" .
            "<td width='300px'>C&oacute;digo Correlativo: " . $muestra["muestra"]["evento_codigo"] . " - " . $muestra["muestra"]["evento_correlativo"] .
            "</td>
            </tr>
            <tr>
                <td width='300px'>Tipo de muestra: " . $muestra["muestra"]["nombre_tipo_muestra"] . "</td>
                <td width='400px'>Situaci&oacute;n de la muestra en SILAB: " . htmlentities($muestra["muestra"]["nombre_situacion"]) . "</td>
            </tr>
            <tr>
                <td colspan='2' width='300px'>Silab: " . $tipoSilab .
            "</td>
            </tr>
         ";
    $result .= "</table>";
//$result .= "<tr><td width='300px' colspan='2'><a id='toggle".$idMuestra."' onmouseout='RollOut(this)' onmouseover='RollOver(this)' style='text-decoration: none; font-weight: bold;' class='' href='javascript:verMuestra(".$idMuestra.");'>Ver m&aacute;s</a>";
//$result .= " <a id='borrarMuestra".$idMuestra."' onmouseout='RollOut(this)' onmouseover='RollOver(this)' style='text-decoration: none; font-weight: bold;' class='' href='javascript:borrarMuestra(".$idMuestra.");'>Borrar Muestra</a>";
//$result .= "</td></tr></table>";

    $result .= "<div id='contenidoMuestra" . $idMuestra . "' style='display:none'>";
    $result .= "<br/><span style='color:#628529;font-weight : bold;'>Datos de la muestra</span>";
    $result .= "<table>
            <tr>
                <td width='300px'>Instalaci&oacute;n de salud: " . htmlentities($muestra["muestra"]["unidad_desc"]) .
            "</td>" .
            "<td width='300px'>Inicio de S&iacute;ntomas: " . $muestra["muestra"]["fecha_inicio_sintomas"] .
            "</td>
            </tr>
            <tr>
                <td width='300px'>Fecha de Toma: " . $muestra["muestra"]["fecha_toma"] .
            "<td width='300px'>Fecha de recepci&oacute;n: " . $muestra["muestra"]["fecha_recepcion"] .
            "</td>
            </tr>
            </table>";
    $result .= "<br/><span style='color:#628529;font-weight : bold;'>Pruebas Realizadas</span>";
    $result .= "<table width='100%'>
            <tr>
                <th width='30%' class='dxgvHeader_PlasticBlue'>Prueba</th>
                <th width='15%' class='dxgvHeader_PlasticBlue'>Resultado</th>
                <th width='15%' class='dxgvHeader_PlasticBlue'>Fecha</th>
                <th width='20%' class='dxgvHeader_PlasticBlue'>Comentarios</th>
             </tr>";
//    <th class='dxgvHeader_PlasticBlue' style='text-align:center' width='7%'>Presencia de <br/>c&eacute;lulas</th>
//                <th class='dxgvHeader_PlasticBlue' style='text-align:center' width='7%'>Muestra adecuada <br/>para determinar Ag</th>
//                <th class='dxgvHeader_PlasticBlue' style='text-align:center' width='7%'>IF para <br/>ant&iacute;genos virales</th>
//                


    $arrayPruebas = "{no}";
    $pruebas = array();
    if ($tipo_silab == 0)
        $pruebas = helperSilab::getPruebasRealizadas($idMuestra);
    else if ($tipo_silab == 1)
        $pruebas = helperSilabRemoto::getPruebasRealizadas($idMuestra);

    if (is_array($pruebas)) {
        $arrayPruebas = "";
        foreach ($pruebas as $prueba) {
            $presencia = "";
            if ($prueba["ANA_MUE_PRESENCIA"] != NULL) {
                switch ($prueba["ANA_MUE_PRESENCIA"]) {
                    case 1:
                        $presencia = "SI";
                        break;
                    case 2:
                        $presencia = "NO";
                        break;
                    case 3:
                        $presencia = "DESC";
                        break;
                }
            }

            $ag = "";
            if ($prueba["ANA_MUE_AG"] != NULL) {
                switch ($prueba["ANA_MUE_AG"]) {
                    case 1:
                        $ag = "SI";
                        break;
                    case 2:
                        $ag = "NO";
                        break;
                    case 3:
                        $ag = "DESC";
                        break;
                }
            }

            $if = "";
            if ($prueba["ANA_MUE_IF"] != NULL) {
                switch ($prueba["ANA_MUE_IF"]) {
                    case 1:
                        $if = "SI";
                        break;
                    case 2:
                        $if = "NO";
                        break;
                    case 3:
                        $if = "DESC";
                        break;
                }
            }

            $result .= "<tr>
                        <td class='fila'>" . $prueba["PRU_NOMBRE"] . "</td>
                        <td class='fila'>" . $prueba["RES_NOMBRE"] . "</td>
                        <td class='fila'>" . $prueba["ANA_MUE_FECHA"] . "</td>
                        <td class='fila'>" . $prueba["ANA_MUE_COMENTARIOS"] . "</td>
                    </tr>";
//            <td class='fila'>" . $presencia . "</td>
//                        <td class='fila'>" . $ag . "</td>
//                        <td class='fila'>" . $if . "</td>
            $arrayPruebas .= '{' . $idMuestra . "#-#" .
                    htmlentities($prueba["PRU_NOMBRE"]) . "#-#" .
                    $prueba["RES_NOMBRE"] . "#-#" .
                    $prueba["ANA_MUE_FECHA"] . "#-#" .
                    $prueba["ANA_MUE_PRESENCIA"] . "#-#" .
                    $prueba["ANA_MUE_AG"] . "#-#" .
                    $prueba["ANA_MUE_IF"] . "#-#" .
                    htmlentities($prueba["ANA_MUE_COMENTARIOS"]) . "},";
        }
    }

    $result .= "</table>";

    $conclusion = array();
    if ($tipo_silab == 0)
        $conclusion = helperSilab::getConclusionMuestra($idMuestra);
    else if ($tipo_silab == 1)
        $conclusion = helperSilabRemoto::getConclusionMuestra($idMuestra);

    if ($conclusion["tipo1"] == "NO APLICA")
        $conclusion["tipo1"] = "";
    if ($conclusion["tipo2"] == "NO APLICA")
        $conclusion["tipo2"] = "";
    if ($conclusion["tipo3"] == "NO APLICA")
        $conclusion["tipo3"] = "";
    if ($conclusion["subtipo1"] == "NO APLICA")
        $conclusion["subtipo1"] = "";
    if ($conclusion["subtipo2"] == "NO APLICA")
        $conclusion["subtipo2"] = "";
    if ($conclusion["subtipo3"] == "NO APLICA")
        $conclusion["subtipo3"] = "";

    $result .= "<br/><span style='color:#628529;font-weight : bold;'>Resultados</span>";
    $result .= "<table width='100%'>
                <tr>
                    <td width='300px'>Resultado final: " . $conclusion["resultado"] . "</td>
                </tr>
                <tr>
                    <td width='300px'>Resultado Espec&iacute;fico No.1: " . $conclusion["tipo1"] . " " . $conclusion["subtipo1"] . "
                </tr>
                <tr>
                    <td width='300px'>Resultado Espec&iacute;fico No.2: " . $conclusion["tipo2"] . " " . $conclusion["subtipo2"] . "
                </tr>
                <tr>
                    <td colwidth='300px'>Resultado Espec&iacute;fico No.3: " . $conclusion["tipo3"] . " " . $conclusion["subtipo3"] . "
                </tr>
                <tr>
                    <td width='300px'>Comentarios: " . $conclusion["comentarios"] . "</td>
                </tr>
            </table>";
    $result .= "</div><br/>";

    $arrayMuestra = '{' . $idMuestra . "#-#" .
            $muestra["muestra"]["codigo_global_anio"] . " - " . $muestra["muestra"]["codigo_global"] . "#-#" .
            $muestra["muestra"]["evento_codigo"] . " - " . $muestra["muestra"]["evento_correlativo"] . "#-#" .
            htmlentities($muestra["muestra"]["nombre_tipo_muestra"]) . "#-#" .
            $muestra["muestra"]["fecha_inicio_sintomas"] . "#-#" .
            $muestra["muestra"]["fecha_toma"] . "#-#" .
            $muestra["muestra"]["fecha_recepcion"] . "#-#" .
            htmlentities($muestra["muestra"]["unidad_desc"]) . "#-#" .
            htmlentities($muestra["muestra"]["nombre_situacion"]) . "#-#" .
            $conclusion["resultado"] . "#-#" .
            $conclusion["tipo1"] . "#-#" .
            $conclusion["subtipo1"] . "#-#" .
            $conclusion["tipo2"] . "#-#" .
            $conclusion["subtipo2"] . "#-#" .
            $conclusion["tipo3"] . "#-#" .
            $conclusion["subtipo3"] . "#-#" .
            $tipo_silab . "#-#" .
            htmlentities($conclusion["comentarios"]) . '}';

    echo $result;
    echo "###" . $arrayMuestra;
    echo "###" . $arrayPruebas;
}
else {
    echo "no";
}
