<?php

require_once("libs/helper/helperUceti.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");

$config["filtro"] = $_REQUEST["filtro"];
$config["silab"] = $_REQUEST["silab"];
$config["uceti"] = $_REQUEST["uceti"];
$config["hospital"] = $_REQUEST["hospital"];
// PAGINADO
$config["paginado"] = Configuration::paginado;
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1; // Pagina de resultados a mostrar
$config["inicio"] = ($config["page"] - 1) * $config["paginado"]; // Inicio del set de datos

$data = helperUceti::buscarUceti($config);
$config["total"] = helperUceti::buscarUcetiCantidad($config);

$result = $extra . '<table id="resultados" class="dxgvControl_PlasticBlue" rules="all" cellspacing="0"
                  border="0" style="width: 100%; border-collapse: collapse;">';
$result.= '
            <tr>
                <th class="dxgvHeader_PlasticBlue" scope="">N.</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Instalaci&oacute;n de salud</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Id Paciente</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Regi&oacute;n Paciente</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Diagn&oacute;stico Cl&iacute;nico</th>
                <th style="text-align: center" class="dxgvHeader_PlasticBlue" scope="">S. Epi</th>
                <th style="text-align: center" class="dxgvHeader_PlasticBlue" scope="">A&ntilde;o</th>
                <th style="text-align: center" class="dxgvHeader_PlasticBlue" scope="">Formulario</th>
                <th style="text-align: center" class="dxgvHeader_PlasticBlue" scope="">Muestra Silab</th>
                <th style="text-align: center" class="dxgvHeader_PlasticBlue" scope="">Fuente</th>
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
        $pendiente_uceti = $data['pendiente_uceti'];
        $actualizacion_silab = $data['actualizacion_silab'];
        $fuente = " ";
        $estado_silab = " ";
        $estado_uceti = " ";
        switch ($pendiente_silab) {
            case '1':
                $estado_silab = '<table>
                    <tr>
                        <td><img width=16 height=16 src="../img/iconos/valido.png"></td><td>' . $actualizacion_silab .
                        '</td></tr></table>';
                break;
            case '0':
                $estado_silab = '<table>
                    <tr>
                        <td><img width=16 height=16 src="../img/iconos/pendiente.png"></td><td>Pendiente' . $actualizacion_silab .
                        '</td></tr></table>';
                break;
        }
        switch ($source_entry) {
            case '1':
                $fuente = '<table>
                    <tr>
                        <td><img width=16 height=16 src="../img/iconos/tablet.png"></td><td>Tablet</td>
                    </tr>
                        </table>';
                break;
            case '0':
                $fuente = '<table>
                    <tr>
                        <td><img width=16 height=16 src="../img/iconos/pc.png"></td><td>Web</td>
                    </tr>
                        </table>';
                break;
        }
        switch ($pendiente_uceti) {
            case '1':
                $estado_uceti = '<table>
                    <tr>
                        <td><img width=16 height=16 src="../img/iconos/valido.png"></td><td>Finalizado</td>
                    </tr>
                        </table>';
                break;
            case '0':
                $estado_uceti = '<table>
                    <tr>
                        <td><img width=16 height=16 src="../img/iconos/pendiente.png"></td><td>Pendiente</td>
                    </tr>
                        </table>';
                break;
        }
        require_once ('libs/caus/clsCaus.php');
        $permisoBorrar = false;
        if (clsCaus::validarSeccion(ConfigurationCAUS::influenza, ConfigurationCAUS::Borrar)) {
            $permisoBorrar = true;
        }
        if(ConfigurationHospitalInfluenza::HospitalActual == ConfigurationHospitalInfluenza::HospitalDefault){
            if( $data["id_flureg"] < 3000000)
                $permisoBorrar = false;
        }
        $borrarUceti = "";
        
        if ($permisoBorrar) {
            $borrarUceti = '<a href="javascript:borrarUceti(' . $data["id_flureg"] . ')" class="" title="Borrar">'
                    . '<img title="Borrar" border=0 src="../img/Delete.png"></a>';
        }
        $result.='<tr class="dxgvDataRow_PlasticBlue">';
        $result.='
                                <td class="dxgv" width="2%">
                                    ' . ($data["id_flureg"] == "" ? "Sin datos" : htmlentities($data["id_flureg"])) . '
                                </td>
                                <td class="dxgv" width="15%">
                                    ' . ($data["nombre_un"] == "" ? "No disponible" : htmlentities($data["nombre_un"])) . '
                                </td>
                                <td class="dxgv" width="8%">
                                    ' . ($data["numero_identificacion"] == "" ? "Sin dato" : htmlentities($data["numero_identificacion"])) . '
                                </td>
                                <td class="dxgv" width="10%">
                                    ' . ($data["nombre_region"] == "" ? "No disponible" : htmlentities($data["nombre_region"])) . '
                                </td>
                                <td class="dxgv" width="25%">
                                    ' . ($evento == "" ? "No disponible" : htmlentities($evento)) . '
                                </td>
                                <td style="text-align: center" class="dxgv" width="3%">                                    
                                    ' . ($data["semana_epi"] == "" ? "" : htmlentities($data["semana_epi"])) . '
                                </td>
                                <td style="text-align: center" class="dxgv" width="3%">                                    
                                    ' . ($data["anio"] == "" ? "" : htmlentities($data["anio"])) . '
                                </td>
                                <td class="dxgv" width="5%">
                                    ' . $estado_uceti . '
                                </td>
                                <td class="dxgv" width="5%">
                                    ' . $estado_silab . '
                                </td>
                                <td class="dxgv" width="5%">
                                    ' . $fuente . '
                                </td>
                                <td class="dxgv" width="8%">
                  <a href="formulario.php?action=R&tipo=' . $data["tipo_identificacion"] . '&id=' . $data["numero_identificacion"] . '" class="" title="Editar">'
                . '<img title="Editar" border=0 src="../img/edit.png"></a>' . $borrarUceti;
        if ($pendiente_uceti == '1') {
            $result .= '<a href="javascript:reporteUceti(' . $data["id_flureg"] . ')" class="" title="Descarga Formulario">'
                    . '<img width=16 height=16 title="PDF" border=0 src="../img/iconos/pdf.png"></a>';
        }
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