<?php
require_once("libs/helper/helperMuestra.php");
require_once ('libs/helper/helperCatalogos.php');
require_once("libs/helper/helperString.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");


$config["identificador"] = $_REQUEST["id"];
$config["historia_clinica"] = $_REQUEST["his"];
$config["nombre"] = $_REQUEST["n"];
$config["apellido"] = $_REQUEST["a"];
$config["area"] = $_REQUEST["are"];
$config["evento"] = $_REQUEST["ev"];

$config["global_desde"] = $_REQUEST["gd"];
$config["global_hasta"] = $_REQUEST["gh"];
$config["correlativo_desde"] = $_REQUEST["cd"];
$config["correlativo_hasta"] = $_REQUEST["ch"];
$config["toma_desde"] = $_REQUEST["td"];
$config["toma_hasta"] = $_REQUEST["th"];
$config["recepcion_desde"] = $_REQUEST["rd"];
$config["recepcion_hasta"] = $_REQUEST["rh"];

// PAGINADO
$config["paginado"]= Configuration::paginado;
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1; // Pagina de resultados a mostrar
$config["inicio"] = ($config["page"] - 1) * $config["paginado"]; // Inicio del set de datos
$extra = ' AND muestra.SIT_ID = '.Configuration::ventanilla;
$config["catalogos"]["area_analisis"] = helperCatalogos::getAreasAnalisis(1);

$data = helperMuestra::getAll($config, $extra,$config["catalogos"]["area_analisis"]);
//print_r($data);exit;
$config["total"] = helperMuestra::getCountAll($config, $extra, $config["catalogos"]["area_analisis"]);

$result = '<table id="resultados" class="dxgvControl_PlasticBlue" rules="all" cellspacing="0"
            border="0" style="width: 70%; border-collapse: collapse;">';
$result.= '
            <tr id="0">
                <th class="dxgvHeader_PlasticBlue" scope="">C&oacute;digo Global</th>
                <th class="dxgvHeader_PlasticBlue" scope="">C&oacute;digo Correlativo</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Evento</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Fecha de Toma</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Fecha Recepci&oacute;n</th>
                <th class="dxgvHeader_PlasticBlue" scope="">&nbsp;</th>
           </tr>';

           if (is_array($data))
           {
                foreach ($data as $data)
                {
                    $idMuestra = $data["MUE_ID"];
                    $result.='<tr class="dxgvDataRow_PlasticBlue" id="r'.$idMuestra.'">';
                    $result.='<td class="dxgv" name="global" width="20%" id="g'.$idMuestra.'">
                                    '.$data["global"].' - '.helperString::completeZeros($data["gnumero"]).'
                                </td>
                                <td class="dxgv" name="correlativo" width="20%" id="c'.$idMuestra.'">
                                    '.$data["correlativo"].' - '.helperString::completeZeros($data["cnumero"]).'
                                </td>
                                <td class="dxgv" width="30%" align="center">
                                '.htmlentities($data["evento"]).'
                                </td>
                                <td class="dxgv" width="12%" align="center">
                                    '.$data["ftoma"].'
                                </td>
                                <td class="dxgv" width="12%" align="center">
                                    '.$data["frecepcion"].'
                                </td>
                                <td class="dxgv" width="6%">
                                    <a title="Agregar a lista de env&iacute;o" class="ui-state-default ui-corner-all ui-link-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)"
                                    href="javascript:seleccionarMuestra('.$idMuestra.')"><span class="ui-icon ui-icon-check"></span></a>
                                    </a>
                                </td>
                          </tr>';
                }
           }
           else
                $result.='<tr id="noResults" class="dxgvDataRow_PlasticBlue"><td class="dxgv" align="center" colspan="11">B&uacute;squeda sin resultados.</td></tr>';

           $result.= '<table>';
           //$result.='<tr class="dxgvDataRow_PlasticBlue"><td class="dxgv" align="center" colspan="11">';

           // ### PAGINADO ###
           require_once('libs/PagineoAjax.php');
           $pagineo = new PagineoAjax($config["total"],$config['page'],$config["paginado"],'');
           $result.= $pagineo->renderPagineo();
           ### PAGINADO ###

           //$result.='</td></tr>';

echo $result;