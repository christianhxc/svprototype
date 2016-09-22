<?php
require_once('libs/excel/PHPExcel.php');

function GuardarDatos($excel, $sheet, $data, $columns, $fromRow){
    $excel->setActiveSheetIndex($sheet);

    $row = $fromRow;
    foreach ($data as $i => $record){
        $record = array_values($record);

        foreach ($columns as $key => $column){
            $excel->getActiveSheet()->setCellValue($column.$row, $record[$key]);
        }

        $row++;
    }
}

$template = "PanamaFluID2016.xlsx";

$excelReader = PHPExcel_IOFactory::createReader('Excel2007');
$excelReader->setIncludeCharts(TRUE);
$excel = $excelReader->load($template);

$data[] = array("FLU" => 4, "FLU2" => 8);
$data[] = array("FLU" => 6, "FLU2" => 10);
$data[] = array("FLU" => 8, "FLU2" => 12);
$data[] = array("FLU" => 10, "FLU2" => 14);
GuardarDatos($excel, 0, $data, array("C","D"), 6);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="FluID.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$objWriter->setIncludeCharts(TRUE);
$objWriter->save('php://output');