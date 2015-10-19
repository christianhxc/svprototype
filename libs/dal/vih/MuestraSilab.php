<?php

require_once ('libs/helper/helperVih.php');
require_once("libs/helper/helperCatalogos.php");

class MuestraSilab {

    public static function construirMuestraSilab($muestras){
        $arrayMuestra = "";
        //echo "<br/>Muestra: ".$idMuestra." ";
        $result = "";
        $max = sizeof($muestras);
        for($i = 0; $i < $max; $i++){
            $muestra = $muestras[$i];
            $idMuestra = $muestra["id_muestra"];
            $result .= "&nbsp;&nbsp;&nbsp;<a id='toggle".$idMuestra."' onmouseout='RollOut(this)' onmouseover='RollOver(this)' style='text-decoration: none; font-weight: bold;' class='' href='javascript:verMuestra(".$idMuestra.");'>Ver M&aacute;s</a>";
            $result .= " &nbsp;<a id='borrarMuestra".$idMuestra."' onmouseout='RollOut(this)' onmouseover='RollOver(this)' style='text-decoration: none; font-weight: bold;' class='' href='javascript:borrarMuestraGuardada(".$idMuestra.");'>Borrar Muestra</a>";

            $result .= 
                    "<table>
                        <tr>
                            <td width='300px'>C&oacute;digo Global: ".$muestra["codigo_global"]."</td>".
                            "<td width='300px'>C&oacute;digo Correlativo: ".$muestra["codigo_correlativo"]."</td>
                        </tr>
                        <tr>
                            <td width='300px'>Tipo de muestra: ".$muestra["tipo_muestra"]."</td>
                            <td width='400px'>Situaci&oacute;n de la muestra en SILAB: ".$muestra["estado_muestra"]."</td>
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
                            <td width='300px'>Instalaci&oacute;n de salud: ".$muestra["unidad_notificadora"].
                            "</td>".
                            "<td width='300px'>Inicio de S&iacute;ntomas: ".$muestra["fecha_inicio_sintomas"].
                            "</td>
                        </tr>
                        <tr>
                            <td width='300px'>Fecha de Toma: ".$muestra["fecha_toma"].
                            "<td width='300px'>Fecha de recepci&oacute;n: ".$muestra["fecha_recepcion"].
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
            $pruebas = helperVih::buscarVihPruebasSilab($idMuestra);
            if (is_array($pruebas)) {
                $arrayPruebas = "";
                foreach ($pruebas as $prueba) {
                    $result .= "<tr>
                                    <td class='fila'>".$prueba["nombre_prueba"]."</td>
                                    <td class='fila'>".$prueba["resultado_prueba"]."</td>
                                    <td class='fila'>".$prueba["fecha_prueba"]."</td>
                                    <td class='fila'>".$prueba["Comentario_prueba"]."</td>
                                </tr>";
                    $arrayPruebas .= '{'.$idMuestra."#-#".$prueba["nombre_prueba"]."#-#".$prueba["resultado_prueba"]."#-#".$prueba["fecha_prueba"]."#-#".$prueba["Comentario_prueba"]."},";
                }
            }

            $result .= "</table>";
            
            $result .= "<br/><span style='color:#628529;font-weight : bold;'>Resultados</span>";
            $result .= "<table>
                        <tr>
                            <td colspan='2' width='300px'>Resultado final: ".$muestra["resultado"].
                            "</td>
                        </tr>
                        <tr>
                            <td width='300px'>Resultado Específico No.1: ".$muestra["tipo1"].
                            "<td width='300px'>Resultado Subtipo No. 1: ".$muestra["subtipo1"].
                            "</td>
                        </tr>
                        <tr>
                            <td width='300px'>Resultado Específico No.2: ".$muestra["tipo2"].
                            "<td width='300px'>Resultado Subtipo No. 2: ".$muestra["subtipo2"].
                            "</td>
                        </tr>
                        <tr>
                            <td colspan='2' width='300px'>Comentarios: ".$muestra["comentario_resultado"].
                            "</td>
                        </tr>
                        </table>";
            $result .= "</div><br/>";

            $arrayMuestra .= '{'.$idMuestra."#-#".$muestra["codigo_global"]."#-#".$muestra["codigo_correlativo"]."#-#"
                    .$muestra["tipo_muestra"]."#-#".$muestra["fecha_inicio_sintomas"]."#-#".$muestra["fecha_toma"]."#-#".$muestra["fecha_recepcion"]."#-#"
                    .$muestra["unidad_notificadora"]."#-#".$muestra["estado_muestra"]."#-#".$muestra["resultado"]."#-#".$muestra["tipo1"]."#-#".$muestra["subtipo1"]."#-#"
                    .$muestra["tipo2"]."#-#".$muestra["subtipo2"]."#-#".$muestra["comentario_resultado"].'},';
        }

        return $result."###".$arrayMuestra."###".$arrayPruebas;
    }

}
