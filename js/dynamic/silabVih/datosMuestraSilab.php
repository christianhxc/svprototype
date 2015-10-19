<?php

require_once("libs/helper/helperSilab.php");
require_once("libs/helper/helperCatalogos.php");
header('Content-type: text/javascript');

$idMuestra = $_REQUEST["id_muestra"];
$muestra = helperSilab::getMuestra($idMuestra);

if ($muestra["muestra"]["id_situacion"]==3 || $muestra["muestra"]["id_situacion"]==4|| $muestra["muestra"]["id_situacion"]==7|| $muestra["muestra"]["id_situacion"]==8){

    $arrayMuestra = "";
    //echo "<br/>Muestra: ".$idMuestra." ";
    $result = "";
    $result .= "&nbsp;&nbsp;&nbsp;<a id='toggle".$idMuestra."' onmouseout='RollOut(this)' onmouseover='RollOver(this)' style='text-decoration: none; font-weight: bold;' class='' href='javascript:verMuestra(".$idMuestra.");'>Ver M&aacute;s</a>";
    $result .= " &nbsp;<a id='borrarMuestra".$idMuestra."' onmouseout='RollOut(this)' onmouseover='RollOver(this)' style='text-decoration: none; font-weight: bold;' class='' href='javascript:borrarMuestra(".$idMuestra.");'>Borrar Muestra</a>";

    $result .= 
            "<table>
                <tr>
                    <td width='300px'>C&oacute;digo Global: ".$muestra["muestra"]["codigo_global_anio"]." - ".$muestra["muestra"]["codigo_global"]."</td>".
                    "<td width='300px'>C&oacute;digo Correlativo: ".$muestra["muestra"]["evento_codigo"]." - ".$muestra["muestra"]["evento_correlativo"]."</td>
                </tr>
                <tr>
                    <td width='300px'>Tipo de muestra: ".$muestra["muestra"]["nombre_tipo_muestra"]."</td>
                    <td width='400px'>Situaci&oacute;n de la muestra en SILAB: ".$muestra["muestra"]["nombre_situacion"]."</td>
                </tr>
            ";
    $result .= "</table>";
    //$result .= "<tr><td width='300px' colspan='2'><a id='toggle".$idMuestra."' onmouseout='RollOut(this)' onmouseover='RollOver(this)' style='text-decoration: none; font-weight: bold;' class='' href='javascript:verMuestra(".$idMuestra.");'>Ver m&aacute;s</a>";
    //$result .= " <a id='borrarMuestra".$idMuestra."' onmouseout='RollOut(this)' onmouseover='RollOver(this)' style='text-decoration: none; font-weight: bold;' class='' href='javascript:borrarMuestra(".$idMuestra.");'>Borrar Muestra</a>";
    //$result .= "</td></tr></table>";

    $result .= "<div id='contenidoMuestra".$idMuestra."' style='display:none'>";
    $result .= "<br/><span style='color:#628529;font-weight : bold;'>Datos de la muestra</span>";
    $result .= "<table>
                <tr>
                    <td width='300px'>Instalaci&oacute;n de salud: ".$muestra["muestra"]["unidad_desc"].
                    "</td>".
                    "<td width='300px'>Inicio de S&iacute;ntomas: ".$muestra["muestra"]["fecha_inicio_sintomas"].
                    "</td>
                </tr>
                <tr>
                    <td width='300px'>Fecha de Toma: ".$muestra["muestra"]["fecha_toma"].
                    "<td width='300px'>Fecha de recepci&oacute;n: ".$muestra["muestra"]["fecha_recepcion"].
                    "</td>
                </tr>
                </table>";
    $result .= "<br/><span style='color:#628529;font-weight : bold;'>Pruebas Realizadas</span>";
    $result .= "<table width='100%'>
                <tr>
                    <th width='35%' class='dxgvHeader_PlasticBlue'>Prueba</th>
                    <th width='20%' class='dxgvHeader_PlasticBlue'>Resultado</th>
                    <th width='20%' class='dxgvHeader_PlasticBlue'>Fecha</th>
                    <th width='25%' class='dxgvHeader_PlasticBlue'>Comentarios</th>
                </tr>";

    $arrayPruebas = "{no}";
    $pruebas = helperSilab::getPruebasRealizadas($idMuestra);
    if (is_array($pruebas)) {
        $arrayPruebas = "";
        foreach ($pruebas as $prueba) {
            $result .= "<tr>
                            <td class='fila'>".$prueba["PRU_NOMBRE"]."</td>
                            <td class='fila'>".$prueba["RES_NOMBRE"]."</td>
                            <td class='fila'>".$prueba["ANA_MUE_FECHA"]."</td>
                            <td class='fila'>".$prueba["ANA_MUE_COMENTARIOS"]."</td>
                        </tr>";
            $arrayPruebas .= '{'.$idMuestra."#-#".$prueba["PRU_NOMBRE"]."#-#".$prueba["RES_NOMBRE"]."#-#".$prueba["ANA_MUE_FECHA"]."#-#".$prueba["ANA_MUE_COMENTARIOS"]."},";
        }
    }

    $result .= "</table>";

    $conclusion = helperSilab::getConclusionMuestra($idMuestra);
    $result .= "<br/><span style='color:#628529;font-weight : bold;'>Resultados</span>";
    $result .= "<table>
                <tr>
                    <td colspan='2' width='300px'>Resultado final: ".$conclusion["resultado"].
                    "</td>
                </tr>
                <tr>
                    <td width='300px'>Resultado Específico No.1: ".$conclusion["tipo1"].
                    "<td width='300px'>Resultado Subtipo No. 1: ".$conclusion["subtipo1"].
                    "</td>
                </tr>
                <tr>
                    <td width='300px'>Resultado Específico No.2: ".$conclusion["tipo2"].
                    "<td width='300px'>Resultado Subtipo No. 2: ".$conclusion["subtipo2"].
                    "</td>
                </tr>
                <tr>
                    <td colspan='2' width='300px'>Comentarios: ".$conclusion["comentarios"].
                    "</td>
                </tr>
                </table>";
    $result .= "</div><br/>";

    $arrayMuestra = '{'.$idMuestra."#-#".$muestra["muestra"]["codigo_global_anio"]." - ".$muestra["muestra"]["codigo_global"]."#-#".$muestra["muestra"]["evento_codigo"]." - ".$muestra["muestra"]["evento_correlativo"]."#-#"
            .$muestra["muestra"]["nombre_tipo_muestra"]."#-#".$muestra["muestra"]["fecha_inicio_sintomas"]."#-#".$muestra["muestra"]["fecha_toma"]."#-#".$muestra["muestra"]["fecha_recepcion"]."#-#"
            .$muestra["muestra"]["unidad_desc"]."#-#".$muestra["muestra"]["nombre_situacion"]."#-#".$conclusion["resultado"]."#-#".$conclusion["tipo1"]."#-#".$conclusion["subtipo1"]."#-#".$conclusion["tipo2"]."#-#"
            .$conclusion["subtipo2"]."#-#".$conclusion["comentarios"].'}';

    echo $result;
    echo "###".$arrayMuestra;
    echo "###".$arrayPruebas;
}
 else {
    echo "no";
}
//print ( '<pre>' )  ;  
//print_r($pruebas);
//print ( '</pre>' ) ;
//echo "Conclusi&oacute;n Muestra<br/>";

//print ( '<pre>' )  ;  
//print_r($conclusion);
//print ( '</pre>' ) ;
?>