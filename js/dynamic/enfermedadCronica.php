<?php

require_once("libs/helper/helperCatalogos.php");
require_once("libs/Configuration.php");

$data = helperCatalogos::getEnfermedadesCronicasInfluenza();
$total = helperCatalogos::getEnfermedadesCronicasTotalInfluenza();
//echo "<br/>Total:" . $total . "<br/>";

$result = '<table cellspacing="0"
                  border="0" style="width: 70%; border-collapse: collapse;">';
$i = 0;
$num_fila = 2;
if (is_array($data)) {
    $result.='<tr><td><label>Enfermedades cr&oacute;nicas:</label></td><td></td></tr>';
    foreach ($data as $data) {
        $id = ($data["id_cat_enfermedad_cronica"] == "" ? "Sin dato" : htmlentities($data["id_cat_enfermedad_cronica"]));
        $nombre = ($data["nombre_enfermedad_cronica"] == "" ? "Sin dato" : htmlentities($data["nombre_enfermedad_cronica"]));

        if (($i % $num_fila) == 0) {
            $result.='<tr>';
        }
        $result.='<td><label>' . $nombre . '<span class="mandatory">*</span></label></td>';
        $result.='<td>
                <input type="hidden" name="data[antecedentes][resultadoCronica][' . $i . '][id] id="idResCronica" value="' . $id . '" />
                <select id="drpResCronica' . $id . '" name="data[antecedentes][resultadoCronica][' . $i . '][res]">
                    <option value="-1">Sel...</option>
                    <option value="1">Si</option>
                    <option value="0">No</option>
                    <option value="2">Desc</option>
                </select>
            </td>';
        if (($i % $num_fila + 1) == 0) {
            $result.='</tr>';
        }

        $i++;
    }
    if (($i % $num_fila) == 0) {
        $result.='</tr>';
    }
    $result.='<tr>';
    for ($i = 0; $i < ($num_fila*2); $i++) {
        $result.='<td></td>';
    }
    $result.='</tr>';
}
else
    $result.='<tr><td align="center" colspan="11">No se encontraron enfermedades en el cat&aacute;logo</td></tr>';

$result.='</table>';

echo $result;