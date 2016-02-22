<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
require_once ('libs/caus/clsCaus.php');
require_once ('libs/dal/vacunas/reportes/dalGrabarCierre.php');
if(isset($_POST)){
    $id = $_POST["id"];
    $year = $_POST["year"];
    $month = $_POST["month"];
    if($id > 0){
        $totalRows = dalGrabarCierre::RevertirCierre($year, $month, $id);
        echo json_encode(array("mensaje" => "Cierre mensual revertido con exito"));
    }else{
        echo json_encode(array("mensaje" => "No hay registros para los parametros ingresados"));
    }
}
?>