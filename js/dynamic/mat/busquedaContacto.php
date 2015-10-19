<?php

require_once("libs/helper/helperMat.php");
require_once("libs/PagineoAjax.php");
require_once("libs/Configuration.php");

$config["filtro"] = $_REQUEST["filtro"];

// PAGINADO
$config["paginado"] = Configuration::paginado;
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1; // Pagina de resultados a mostrar
$config["inicio"] = ($config["page"] - 1) * $config["paginado"]; // Inicio del set de datos

$data = helperMat::buscarContacto($config);
$permiso["borrar"] = $_REQUEST["B"];
$config["total"] = helperMat::buscarContactoCantidad($config);

$result = $extra . '<table id="resultados" class="dxgvControl_PlasticBlue" rules="all" cellspacing="0"
                  border="0" style="width: 100%; border-collapse: collapse;">';
$result.= '
            <tr>
                <th class="dxgvHeader_PlasticBlue" scope="">N.</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Nombres</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Apellidos</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Correo</th>
                <th class="dxgvHeader_PlasticBlue" scope="">Acci&oacute;n</th>
           </tr>';

if (is_array($data)) {
    foreach ($data as $data) {
        $result .=  '<tr class="dxgvDataRow_PlasticBlue">';
        $result .=  '<td class="dxgv" width="2%">'.($data["id_contacto"] == "" ? "Sin datos" : htmlentities($data["id_contacto"])).'</td>
                    <td class="dxgv" width="33%">'.($data["nombres"] == "" ? "No disponible" : htmlentities($data["nombres"])).'</td>
                    <td class="dxgv" width="30%">'.($data["apellidos"] == "" ? "No disponible" : htmlentities($data["apellidos"])).'</td>
                    <td class="dxgv" width="30%">'.($data["email"] == "" ? "Sin dato" : htmlentities($data["email"])).'</td>
                    <td class="dxgv" width="5%">
                        <a href="javascript:editarContacto('.$data["id_contacto"].')" class="" title="Editar"><img title="Editar" border=0 src="../img/edit.png"></a>&nbsp;';
                    //if ($permiso["borrar"] == "si")
                        $result.='<a href="javascript:eliminarContacto('.$data["id_contacto"].')" class="" title="Borrar"><img title="Borrar" border=0 src="../img/Delete.png"></a>';
        $result.='</td></tr>';
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