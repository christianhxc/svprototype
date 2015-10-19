<?php

require_once ('libs/helper/helperUceti.php');
require_once("libs/helper/helperCatalogos.php");
require_once("libs/Etiquetas.php");
require_once("libs/ConfigurationHospitalInfluenza.php");

class muestraSilab {

    public static function construirMuestraSilabUceti($muestras) {
        $arrayMuestra = "";
        $arrayPruebas = "";
                
        //echo "<br/>Muestra: ".$idMuestra." ";
        $result = "";
        $tipoSilab = "";

        $max = sizeof($muestras);
        for ($i = 0; $i < $max; $i++) {
            $muestra = $muestras[$i];
            if ($muestra["local_remoto"] == 0) {
                $tipoSilab = ConfigurationHospitalInfluenza::getSilabLocal();
            } else if ($muestra["local_remoto"] == 1) {
                $tipoSilab = Etiquetas::silab_remoto;
            } else if ($muestra["local_remoto"] == 2) {
                $tipoSilab = Etiquetas::resultado_manual;
            }
            
            $idMuestra = $muestra["id_muestra"];
            $result .= "<span id='label" . $idMuestra . "' style='color:#628529;font-weight : bold;'>Muestra Guardada " . ($i + 1) . "</span>&nbsp;&nbsp;&nbsp;<a id='toggle" . $idMuestra . "' onmouseout='RollOut(this)' onmouseover='RollOver(this)' style='text-decoration: none; font-weight: bold;' class='' href='javascript:verMuestra(" . $idMuestra . ");'>Ver M&aacute;s</a>";
            $result .= " &nbsp;<a id='borrarMuestra" . $idMuestra . "' onmouseout='RollOut(this)' onmouseover='RollOver(this)' style='text-decoration: none; font-weight: bold;' class='' href='javascript:borrarMuestraGuardada(" . $idMuestra . ");'>Borrar Muestra</a>";
            if ($muestra["local_remoto"] != 2){
                $result .= " &nbsp;<a id='actualizarMuestra" . $idMuestra . "' onmouseout='RollOut(this)' onmouseover='RollOver(this)' style='text-decoration: none; font-weight: bold;' class='' href='javascript:actualizarMuestraGuardada(" . $idMuestra . ",".$muestra["local_remoto"].");'>Actualizar Muestra</a>";
            }
            $result .=
                    "<table>
                        <tr>
                            <td width='300px'>C&oacute;digo Global: " . $muestra["codigo_global"] . "</td>" .
                    "<td width='300px'>C&oacute;digo Correlativo: " . $muestra["codigo_correlativo"] . "</td>
                        </tr>
                        <tr>
                            <td width='300px'>Tipo de muestra: " . $muestra["tipo_muestra"] . "</td>
                            <td width='400px'>Situaci&oacute;n de la muestra en SILAB: " . $muestra["estado_muestra"] . "</td>
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
                            <td width='300px'>Instalaci&oacute;n de salud: " . $muestra["unidad_notificadora"] .
                    "</td>" .
                    "<td width='300px'>Inicio de S&iacute;ntomas: " . $muestra["fecha_inicio_sintoma"] .
                    "</td>
                        </tr>
                        <tr>
                            <td width='300px'>Fecha de Toma: " . $muestra["fecha_toma"] .
                    "<td width='300px'>Fecha de recepci&oacute;n: " . $muestra["fecha_recepcion"] .
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
//            <th class='dxgvHeader_PlasticBlue' style='text-align:center' width='7%'>Presencia de <br/>c&eacute;lulas</th>
//                            <th class='dxgvHeader_PlasticBlue' style='text-align:center' width='7%'>Muestra adecuada <br/>para determinar Ag</th>
//                            <th class='dxgvHeader_PlasticBlue' style='text-align:center' width='7%'>IF para <br/>ant&iacute;genos virales</th>
                            

            $pruebas = helperUceti::buscarUcetiPruebasSilab($idMuestra);
            if (is_array($pruebas)) {
                foreach ($pruebas as $prueba) {
                    $presencia = "";
                    if ($prueba["presencia_prueba"] != NULL) {
                        switch ($prueba["presencia_prueba"]) {
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
                    if ($prueba["ag_prueba"] != NULL) {
                        switch ($prueba["ag_prueba"]) {
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
                    if ($prueba["if_prueba"] != NULL) {
                        switch ($prueba["if_prueba"]) {
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
                                    <td class='fila'>" . $prueba["nombre_prueba"] . "</td>
                                    <td class='fila'>" . $prueba["resultado_prueba"] . "</td>
                                    <td class='fila'>" . $prueba["fecha_prueba"] . "</td>
                                    <td class='fila'>" . $prueba["Comentario_prueba"] . "</td>
                                </tr>";
//                    <td class='fila'>" . $presencia . "</td>
//                                    <td class='fila'>" . $ag . "</td>
//                                    <td class='fila'>" . $if . "</td>
                    $arrayPruebas .= '{' . $idMuestra . "#-#" .
                            $prueba["nombre_prueba"] . "#-#" .
                            $prueba["resultado_prueba"] . "#-#" .
                            $prueba["fecha_prueba"] . "#-#" .
                            $prueba["presencia_prueba"] . "#-#" .
                            $prueba["ag_prueba"] . "#-#" .
                            $prueba["if_prueba"] . "#-#" .
                            $prueba["Comentario_prueba"] . "},";
                }
            }

            $result .= "</table>";

            if( $muestra["tipo1"] == "NO APLICA")
                $muestra["tipo1"] = "";
            if( $muestra["tipo2"] == "NO APLICA")
                $muestra["tipo2"] = "";
            if( $muestra["tipo3"] == "NO APLICA")
                $muestra["tipo3"] = "";
            if( $muestra["subtipo1"] == "NO APLICA")
                $muestra["subtipo1"] = "";
            if( $muestra["subtipo2"] == "NO APLICA")
                $muestra["subtipo2"] = "";
            if( $muestra["subtipo3"] == "NO APLICA")
                $muestra["subtipo3"] = "";
            
            $result .= "<br/><span style='color:#628529;font-weight : bold;'>Resultados</span>";
            $result .= "<table>
                        <tr>
                            <td colspan='2' width='300px'>Resultado final: " . $muestra["resultado"] .
                    "</td>
                        </tr>
                        <tr>
                            <td colwidth='300px'>Resultado Espec&iacute;fico No.1: " . $muestra["tipo1"] . " " . $muestra["subtipo1"] . "
                        </tr>
                        <tr>
                            <td colwidth='300px'>Resultado Espec&iacute;fico No.2: " . $muestra["tipo2"] . " " . $muestra["subtipo2"] . "
                        </tr>
                        <tr>
                            <td colwidth='300px'>Resultado Espec&iacute;fico No.3: " . $muestra["tipo3"] . " " . $muestra["subtipo3"] . "
                        </tr>
                        <tr>
                            <td colspan='2' width='300px'>Comentarios: " . $muestra["comentario_resultado"] .
                    "</td>
                        </tr>
                        </table>";
            $result .= "</div><br/>";

            $arrayMuestra .= '{' . $idMuestra . "#-#" .
                    $muestra["codigo_global"] . "#-#" .
                    $muestra["codigo_correlativo"] . "#-#" .
                    $muestra["tipo_muestra"] . "#-#" .
                    $muestra["fecha_inicio_sintoma"] . "#-#" .
                    $muestra["fecha_toma"] . "#-#" .
                    $muestra["fecha_recepcion"] . "#-#" .
                    $muestra["unidad_notificadora"] . "#-#" .
                    $muestra["estado_muestra"] . "#-#" .
                    $muestra["resultado"] . "#-#" .
                    $muestra["tipo1"] . "#-#" .
                    $muestra["subtipo1"] . "#-#" .
                    $muestra["tipo2"] . "#-#" .
                    $muestra["subtipo2"] . "#-#" .
                    $muestra["tipo3"] . "#-#" .
                    $muestra["subtipo3"] . "#-#" .
                    $muestra["local_remoto"] . "#-#" .
                    $muestra["comentario_resultado"] . '},';
//            echo $i." muestra comentario ".$muestra["comentario_resultado"].$arrayMuestra."<br/><br/>";
//            if($i < $max - 1 )
//                $arrayMuestra .= ",";
        }

        return $result . "###" . $arrayMuestra . "###" . $arrayPruebas;
    }

}
