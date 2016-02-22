<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
require_once ('libs/caus/clsCaus.php');
require_once ('libs/dal/vacunas/reportes/dalGrabarCierre.php');
if(isset($_POST)){
    $year = $_POST["search"]["anio"];
    $month = $_POST["search"]["mes"];
    $totalRows = dalGrabarCierre::VerificarCierre($year, $month);
    $totalRows = $totalRows[0];
    if($totalRows <= 0){
        $totalRowsToUpdate = dalGrabarCierre::GetTotalRowsToUpdate($year, $month);
        $totalRowsToUpdate = $totalRowsToUpdate[0];
        if($totalRowsToUpdate > 0){
            dalGrabarCierre::GrabarCierre($year, $month);
            $last = dalGrabarCierre::GetLastHistorial();
            $historial = '';
            foreach($last as $key=>$value){
                $historial .= '<tr class="dxgvDataRow_PlasticBlue">';
                $historial .= '<td class="dxgv" align="right">'.$value["id"].'</td>';
                $historial .= '<td class="dxgv" align="right">'.$value["mes"].'/'.$value["anio"].'</td>';
                $historial .= '<td class="dxgv" align="right">'.number_format($value["total_registros"],0).'</td>';
                $historial .= '<td class="dxgv" align="right">'.$value["fecha_creacion"].'</td>';
                $historial .= '<td class="dxgv" align="center"><a class="revertir-cierre" href="#" cierre-year="'.$value["anio"].'" cierre-month="'.$value["mes"].'" cierre-id="'.$value["id"].'"><img title="Borrar" border=0 src="../../img/Delete.png"></a></td>';
                $historial .= '</tr>';
            }
            echo json_encode(array("mensaje" => $totalRowsToUpdate." registros actualizados con exito", "historial" => $historial));
        }else{
            echo json_encode(array("mensaje" => "No existen registros para los parametros ingresados", "historial" => $historial));
        }
    }else{
        echo json_encode(array("mensaje" => "Cierre ya aplicado, para volver aplicar es necesario revertirlo", "historial" => $historial));
    }
}
?>