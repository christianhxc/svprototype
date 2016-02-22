<?php
require_once ('libs/caus/clsCaus.php');
require_once ('libs/dal/ldbi/dalLdbiExport.php');
require_once ('libs/helper/helperString.php');
require_once ('libs/Configuration.php');

if (!clsCaus::validarSession()) {
    header("location: ../../index.php");
}

$fechaFin = helperString::toDate($_REQUEST["fin"]);

$filters = array();
$filters[] = helperString::toDate($_REQUEST["ini"]);
$filters[] = $fechaFin;

dalLdbiExport::generarCodigosUnicos($fechaFin);

$tables = array();
$tables = array_merge($tables, dalLdbiExport::getTablesCatalogo()); // Verificar permisos
$tables = array_merge($tables, dalLdbiExport::getTables());

$tmpFolder = "tmpdata";
mkdir($tmpFolder, 0700);

$tmpZip = "tmpzip";
mkdir($tmpZip, 0700);

$now = new DateTime(null, new DateTimeZone("America/Panama"));
$current = $now->format('YmdHis');
$folder = $tmpFolder."/".$current;
mkdir($folder, 0700);

foreach ($tables as $table){
    $file = fopen($folder."/".$table["name"].".data", "w");
    $tuples = dalLdbiExport::getData($table["name"], $filters);
    foreach ($tuples as $tuple){
        //if ($table["id"] != null) unset($tuple[$table["id"]]);
        $line["key"] = $table["id"];
        $line["table"] = $table["name"];
        $line["data"] = $tuple;
        fwrite($file, json_encode($line)."\n");
    }
    fclose($file);
}

$zipFile = $current.".zip";
$zip = new ZipArchive();
$zip->open($tmpZip."/".$zipFile, ZipArchive::CREATE);

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($folder),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file) {
    if (!$file->isDir()) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($folder) + 1);
        $zip->addFile($filePath, $file->getFileName());
    }
}

$zip->close();

$files = array_diff(scandir($folder), array('.','..'));
foreach ($files as $file) {
    (is_dir("$folder/$file")) ? delTree("$folder/$file") : unlink("$folder/$file");
}
rmdir($folder);

echo "Archivo generado correctamente, lo puede descargar haciendo click <a href='".Configuration::getUrlprefix()."LDBI/data/".$tmpZip."/".$zipFile."'>aqui</a>";

/*header($_SERVER['SERVER_PROTOCOL'] . " 200 OK");
header("Content-Type: application/zip");
header("Content-Length: " . filesize($zipFile));
header("Content-Disposition: attachment; filename=" . basename($zipFile));
header("Pragma: no-cache");
$handle = fopen($tmpZip."/".$zipFile, "rb");
fpassthru($handle);
fclose($handle);*/