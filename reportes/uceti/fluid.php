<?php
require_once('libs/excel/PHPExcel.php');
require_once('libs/dal/uceti/dalVistas.php');

function GuardarDatos($excel, $sheet, $data, $fromRow){
    $letters = array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","AA","AB","AC","AD","AE","AF","AG","AH","AI");
    $excel->setActiveSheetIndex($sheet);

    $row = $fromRow;
    foreach ($data as $i => $record){
        $record = array_values($record);

        for ($i = 0; $i < count($record); $i++) {
            $excel->getActiveSheet()->setCellValue($letters[$i].$row, $record[$i]);
        }

        $row++;
    }
}

$template = "PanamaFluID2016.xlsx";

$excelReader = PHPExcel_IOFactory::createReader('Excel2007');
$excelReader->setIncludeCharts(TRUE);
$excel = $excelReader->load($template);

$config['anio'] = $_REQUEST['anio'];
$config['sei'] = $_REQUEST['sei'];
$config['sef'] = $_REQUEST['sef'];
$config['idun'] = $_REQUEST['idun'];
$config['idpro'] = $_REQUEST['idpro'];
$config['idreg'] = $_REQUEST['idreg'];
$config['iddis'] = $_REQUEST['iddis'];
$config['idcor'] = $_REQUEST['idcor'];

$vistas = new dalVistas();

//$irag = $vistas->getNationalVirus($config);
//GuardarDatos($excel, 0, $irag, 6);
$irag = $vistas->getFluidIrag($config);
GuardarDatos($excel, 3, $irag, 8);
//$irag = $vistas->getFluidFallecidos($config);
//GuardarDatos($excel, 4, $irag, 8);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="FluID.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$objWriter->setIncludeCharts(TRUE);
$objWriter->save('php://output');