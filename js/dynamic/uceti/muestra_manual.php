<?php
  require_once("libs/Etiquetas.php");
//  print_r($_POST);
//  $idMuestra = "100000";
  $muestra = $_POST;
  $idMuestra = $muestra["data"]["muestra"]["id_muestra"];
  $tipoSilab = Etiquetas::resultado_manual;
  
    $result = "";
    $result .= "&nbsp;&nbsp;&nbsp;<a id='toggle" . $idMuestra . "' onmouseout='RollOut(this)' onmouseover='RollOver(this)' style='text-decoration: none; font-weight: bold;' class='' href='javascript:verMuestra(" . $idMuestra . ");'>Ver M&aacute;s</a>";
    $result .= " &nbsp;<a id='borrarMuestra" . $idMuestra . "' onmouseout='RollOut(this)' onmouseover='RollOver(this)' style='text-decoration: none; font-weight: bold;' class='' href='javascript:borrarMuestra(" . $idMuestra . ");'>Borrar Muestra</a>";
    
    $result .=
            "<table>
            <tr>
                <td width='300px'><b>C&oacute;digo Global: </b> Ingreso Manual"  .
            "</td>" .
            "<td width='300px'><b>C&oacute;digo Correlativo: </b> Ingreso Manual"  .
            "</td>
            </tr>
            <tr>
                <td width='300px'><b>Tipo de muestra: </b>" . $muestra["data"]["conclusion"]["tipo_muestra"] . "</td>
                <td width='400px'><b>Situaci&oacute;n de la muestra en SILAB:</b> An&aacute;lisis finalizado</td>
            </tr>
            <tr>
                <td colspan='2' width='300px'><b>Silab: </b> " . $tipoSilab . "</td>
            </tr>
           <tr>
                <td colspan='2' width='300px'><b>Laboratorio que proceso: </b> " . $muestra["data"]["muestra"]["lab_proceso"] . "</td>
            </tr>
         ";
    $result .= "</table>";

    $result .= "<div id='contenidoMuestra" . $idMuestra . "' style='display:none'>";
    $result .= "<br/><span style='color:#628529;font-weight : bold;'>Datos de la muestra</span>";
    $result .= "<table>
            <tr>
                <td width='300px'><b>Instalaci&oacute;n de salud: </b>" . htmlentities($muestra["data"]["muestra"]["unidad_notificadora"]) .
            "</td>" .
            "<td width='300px'><b>Inicio de S&iacute;ntomas: </b>" . $muestra["data"]["muestra"]["inicio_sintomas"] .
            "</td>
            </tr>
            <tr>
                <td width='300px'><b>Fecha de Toma: </b>" . $muestra["data"]["muestra"]["fecha_toma"] .
            "<td width='300px'><b>Fecha de recepci&oacute;n: </b>" . $muestra["data"]["muestra"]["fecha_recepcion"] .
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
    $result .= "<tr>
            <td class='fila'>" . $muestra["data"]["conclusion"]["prueba"] . "</td>
            <td class='fila'>" . $muestra["data"]["conclusion"]["resultadoFinal"] . "</td>
            <td class='fila'>" . $muestra["data"]["conclusion"]["fecha"] . "</td>
            <td class='fila'>" . $muestra["data"]["conclusion"]["comentario"] . "</td>
        </tr>";
    
    $arrayPruebas .= '{' . $idMuestra . "#-#" .
                    htmlentities($muestra["data"]["conclusion"]["prueba"]) . "#-#" .
                    $muestra["data"]["conclusion"]["resultadoFinal"] . "#-#" .
                    $muestra["data"]["conclusion"]["fecha"] . "#-#" .
                    $muestra["data"]["conclusion"]["presencia"] . "#-#" .
                    $muestra["data"]["conclusion"]["agente"] . "#-#" .
                    $muestra["data"]["conclusion"]["analisis_if"] . "#-#" .
                    htmlentities($muestra["data"]["conclusion"]["comentario"]) . "},";
    
     $result .= "</table>";
//     Hasta aquí las pruebas
     
        $conclusion = array();
        $conclusion = $muestra["data"]["conclusion"];
    if ($conclusion["tipo1"] == "NO APLICA" || $conclusion["tipo1"] == "0")
        $conclusion["tipo1"] = "";
    if ($conclusion["tipo2"] == "NO APLICA" || $conclusion["tipo2"] == "0")
        $conclusion["tipo2"] = "";
    if ($conclusion["tipo3"] == "NO APLICA" || $conclusion["tipo3"] == "0")
        $conclusion["tipo3"] = "";
    if ($conclusion["subtipo1"] == "NO APLICA" || $conclusion["subtipo1"] == "0")
        $conclusion["subtipo1"] = "";
    if ($conclusion["subtipo2"] == "NO APLICA" || $conclusion["subtipo2"] == "0")
        $conclusion["subtipo2"] = "";
    if ($conclusion["subtipo3"] == "NO APLICA" || $conclusion["subtipo3"] == "0")
        $conclusion["subtipo3"] = "";

    $result .= "<br/><span style='color:#628529;font-weight : bold;'>Resultados</span>";
    $result .= "<table width='100%'>
                <tr>
                    <td width='300px'><b>Resultado final: </b> " . $conclusion["resultadoFinal"] . "</td>
                </tr>
                <tr>
                    <td width='300px'><b>Resultado Espec&iacute;fico No.1: </b>" . $conclusion["tipo1"] . " " . $conclusion["subtipo1"] . "
                </tr>
                <tr>
                    <td width='300px'><b>Resultado Espec&iacute;fico No.2: </b>" . $conclusion["tipo2"] . " " . $conclusion["subtipo2"] . "
                </tr>
                <tr>
                    <td colwidth='300px'><b>Resultado Espec&iacute;fico No.3: </b>" . $conclusion["tipo3"] . " " . $conclusion["subtipo3"] . "
                </tr>
                <tr>
                    <td width='300px'><b>Comentarios: </b>" . $conclusion["comentarios"] . "</td>
                </tr>
            </table>";
    $result .= "</div><br/>";
    
        $arrayMuestra = '{' . $idMuestra . "#-#" .
            "Ingreso Manual" . "#-#" .
            "Ingreso Manual" . "#-#" .
            htmlentities($muestra["data"]["conclusion"]["tipo_muestra"]) . "#-#" .
            $muestra["data"]["muestra"]["inicio_sintomas"] . "#-#" .
            $muestra["data"]["muestra"]["fecha_toma"] . "#-#" .
            $muestra["data"]["muestra"]["fecha_recepcion"] . "#-#" .
            htmlentities($muestra["data"]["muestra"]["unidad_notificadora"]) . "#-#" .
            htmlentities("An&aacute;lisis finalizado") . "#-#" .
            $conclusion["resultadoFinal"] . "#-#" .
            $conclusion["tipo1"] . "#-#" .
            $conclusion["subtipo1"] . "#-#" .
            $conclusion["tipo2"] . "#-#" .
            $conclusion["subtipo2"] . "#-#" .
            $conclusion["tipo3"] . "#-#" .
            $conclusion["subtipo3"] . "#-#" .
            $tipoSilab . "#-#" .
            htmlentities($conclusion["comentarios"]) . "#-#".
            $muestra["data"]["muestra"]["lab_proceso"]  . '}';
        
     
    echo $result;
    echo "###" . $arrayMuestra;
    echo "###" . $arrayPruebas;
  
?>