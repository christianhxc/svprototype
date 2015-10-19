<?php

require_once("libs/helper/helperCatalogos.php");
require_once("libs/Configuration.php");

$data = helperCatalogos::getAntecedentesVacunalesInfluenza();
$total = helperCatalogos::getAntecedentesVacunalesTotalInfluenza();
//echo "<br/>Total:" . $total . "<br/>";

$result = '<table cellspacing="0"
                  border="0" style="width: 100%;">';
$i = 0;
if (is_array($data)) {
    $result.='<tr>
                <td><label >Antecedente vacunal:<span class="mandatory">*</span></label></td>
                <td width="100px" align="center"><label >N. dosis:<span class="mandatory">*</span></label></td>
                <td><label >Fecha &uacute;ltima dosis:<span class="mandatory">*</span></label></td>
                </tr>';
    foreach ($data as $data) {
        $id = ($data["id_cat_antecendente_vacunal"] == "" ? "Sin dato" : htmlentities($data["id_cat_antecendente_vacunal"]));
        $nombre = ($data["nombre_antecendente_vacunal"] == "" ? "Sin dato" : htmlentities($data["nombre_antecendente_vacunal"]));

        $result.='<tr>';
        $result.='<td width="150px"><label >' . $nombre . '</label></td>';
        $result.='<td width="102px" align="center"><input type="text" id="numDosis' . $id . '" name="data[antecedentes][antecedenteVacunal][' . $i . '][dosis]" style="width: 40px; text-align:center;" maxlength="2" onKeyPress="return numbersonly(event, false);"/></td>';
        $result.='<td>
            <input type="hidden" name="data[antecedentes][antecedenteVacunal][' . $i . '][id] id="idVacunal" value="' . $id . '" />
            <input type="text" id="fechaVac' . $id . '" name="data[antecedentes][antecedenteVacunal][' . $i . '][fecha]" style="width: 135px;" maxlength="10"/></td>';
        $result.='</tr>';

        $i++;
    }
    //Espacio disponible
    $max = 7;
    if ($total < $max) {
        $subtotal = $max - $total;
        for ($i = 0; $i < $subtotal; $i++) {
            $result.='<tr><td>&nbsp;</td><td></td><td></td><td></td></tr>';
        }
    }
}
else
    $result.='<tr><td align="center" colspan="11">No se encontraron antecedentes vacunales en el cat&aacute;logo</td></tr>';

$result.='</table>';

echo $result;