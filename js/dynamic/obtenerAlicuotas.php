<?php
require_once("libs/dal/analista/dalAnalista.php");
require_once("libs/helper/helperString.php");
require_once("libs/Configuration.php");

$idMuestra = $_REQUEST["id"];

$data = dalAnalista::getAlicuotasMuestra($idMuestra);
$result = '<table id="resultados" class="dxgvControl_PlasticBlue" rules="all" cellspacing="0"
            border="0" style="width: 70%; border-collapse: collapse;">';
$result.= '
            <tr id="0">
                <th class="dxgvHeader_PlasticBlue" scope="">&Aacute;rea</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Evento</th>
                <th class="dxgvHeader_PlasticBlue" scope="">C&oacute;digo Global</th>
                <th class="dxgvHeader_PlasticBlue" scope="">C&oacute;digo Correlativo</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Rechazada</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Ubicaci&oacute;n</th>
                <th class="dxgvHeader_PlasticBlue" scope="">&nbsp;</th>
           </tr>';
           
           if (is_array($data))
           {
                foreach ($data as $data)
                {
                    $idMuestra = $data["muestra"];
                    $result.='<tr class="dxgvDataRow_PlasticBlue">';
                    $result.='
                                <td class="dxgv" width="auto">
                                    '.htmlentities($data["area"]).'
                                </td>
                                <td class="dxgv" width="auto">
                                    '.htmlentities($data["evento"]).'
                                </td>
                                <td class="dxgv" width="auto" name="ev'.$data["eveId"].'" id="ev'.$data["eveId"].'">
                                    '.$data["anio"].' - '.helperString::completeZeros($data["global"]).'
                                </td>
                                <td class="dxgv" width="auto" name="ev'.$data["eveId"].'" id="ev'.$data["eveId"].'">
                                    '.$data["alfa"].' - '.helperString::completeZeros($data["correlativo"]).'
                                </td>
                                <td class="dxgv" width="10%">
                                    '.($data["recha"]=='1'?'S&iacute;':'No').'
                                </td>';                    ;

                    if($data["status"]==Configuration::ventanilla)
                        $result.='<td class="dxgv" width="12%">Ventanilla</td>';
                    else if($data["status"]==Configuration::enviada)
                        $result.='<td class="dxgv" width="12%">Enviada</td>';
                    else if($data["status"]==Configuration::enAnalisisDer)
                        $result.='<td class="dxgv" width="12%">En An&aacute;lisis</td>';
                    else if($data["status"]==Configuration::enviadaDer)
                        $result.='<td class="dxgv" width="12%">Enviada</td>';
                    

                    if($data["status"]==Configuration::ventanilla)
                        $result.= '<td class="dxgv" width="5%"><a href="javascript:eliminar('.$data["muestra"].');" onmouseout="RollOut(this)" onmouseover="RollOver(this)" class="ui-state-default ui-corner-all ui-link-button" title="Anular al&iacute;cuota" >'
                                  .'<span class="ui-icon ui-icon-trash"></span></a></td>';
                    else
                        $result.='<td class="dxgv" width="5%">&nbsp;</td>';
                }
           }
           else
                $result.='<tr id="0" class="dxgvDataRow_PlasticBlue"><td class="dxgv" align="center" colspan="7">Muestra sin al&iacute;cuotas asignadas.</td></tr>';
           $result.= '<table>';

echo $result;