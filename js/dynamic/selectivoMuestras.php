<?php
require_once("libs/dal/ventanilla/dalHistorial.php");
require_once("libs/helper/helperString.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");

$config["informe"] = $_REQUEST["id"];

// PAGINADO
$config["paginado"]= Configuration::paginado;
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1; // Pagina de resultados a mostrar
$config["inicio"] = ($config["page"] - 1) * $config["paginado"]; // Inicio del set de datos

$data = dalHistorial::getAllSelectivas($config["informe"], $config["inicio"], $config["paginado"]);
$config["total"] = dalHistorial::getCountAllSelectivas($config["informe"]);

$result = '<table id="resultados" class="dxgvControl_PlasticBlue" rules="all" cellspacing="0"
            border="0" style="width: 75%; border-collapse: collapse;">';
$result.= '
            <tr id="0">
                <th class="dxgvHeader_PlasticBlue" width="30%">C&oacute;digo Global</th>
                <th class="dxgvHeader_PlasticBlue" width="30%">C&oacute;digo Correlativo</th>
                <th class="dxgvHeader_PlasticBlue" width="34%">Evento</th>
                <th class="dxgvHeader_PlasticBlue" width="3%">&nbsp;</th>
                <th class="dxgvHeader_PlasticBlue" width="3%">&nbsp;</th>
           </tr>';

           if (is_array($data))
           {
                foreach ($data as $data)
                {
                    $idMuestra = $data["id"];
                    $result.='<tr class="dxgvDataRow_PlasticBlue" id="m'.$idMuestra.'">';
                    $result.='<td class="dxgv" id="g'.$idMuestra.'">
                                    '.$data["global"].' - '.helperString::completeZeros($data["gnumero"]).'
                                <input type="hidden" id="amount" value="'.$config["total"].'"/>
                                </td>
                                <td class="dxgv" id="c'.$idMuestra.'">
                                    '.$data["correlativo"].' - '.helperString::completeZeros($data["cnumero"]).'
                                </td>
                                <td class="dxgv">
                                '.htmlentities($data["evento"]).'
                                </td>
                                <td class="dxgv">
                                       <a title="Recibir" class="ui-state-default ui-corner-all ui-link-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)"
                                    href="javascript:recibirMuestra('.$idMuestra.')"><span class="ui-icon ui-icon-check"></span></a>
                                    </a>
                                </td>
                                <td class="dxgv">
                                    <a title="Devolver a ventanilla" class="ui-state-default ui-corner-all ui-link-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)"
                                    href="javascript:devolverVentanilla('.$idMuestra.');"><span class="ui-icon ui-icon-home"></span></a>
                                    </a>
                                </td>
                          </tr>';                                                                             
                }
           }
           else
                $result.='<tr id="noResults" class="dxgvDataRow_PlasticBlue"><td class="dxgv" align="center" colspan="5">
                    <input type="hidden" id="amount" value="'.$config["total"].'"/>
                    B&uacute;squeda sin resultados.</td></tr>';

           $result.= '<table>';
           //$result.='<tr class="dxgvDataRow_PlasticBlue"><td class="dxgv" align="center" colspan="11">';

           // ### PAGINADO ###
           require_once('libs/PagineoAjax.php');
           $pagineo = new PagineoAjax($config["total"],$config['page'],$config["paginado"],'');
           $result.= $pagineo->renderPagineo();
           ### PAGINADO ###

           //$result.='</td></tr>';

echo $result;