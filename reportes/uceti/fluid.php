<?php
require_once('libs/excel/PHPExcel.php');

$template = "PanamaFluID2016.xlsx";

$excelReader = PHPExcel_IOFactory::createReader('Excel2007');
$excelReader->setIncludeCharts(TRUE);
$excel = $excelReader->load($template);

$excel->setActiveSheetIndex(0);
$excel->getActiveSheet()->setCellValue('C6', '4')
    ->setCellValue('C7', '5')
    ->setCellValue('C8', '6')
    ->setCellValue('C9', '7');

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="FluID.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$objWriter->setIncludeCharts(TRUE);
$objWriter->save('php://output');