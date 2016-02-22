<?php
require_once ('libs/Actions.php');
require_once ('libs/caus/clsCaus.php');
require_once ('libs/dal/ldbi/dalLdbiExport.php');
require_once ('libs/helper/helperString.php');
require_once ('libs/Configuration.php');

if (!clsCaus::validarSession()) {
    header("location: ../../index.php");
}

$allowed =  array('zip');
$zipFileName = "";

if ( 0 < $_FILES['file']['error'] ) {
    echo 'No se envio el archivo con los datos a importar.'; exit;
} else {
    $fileName = "tmpzip/".$_FILES['file']['name'];
    $ext = pathinfo($fileName, PATHINFO_EXTENSION);
    if(!in_array($ext,$allowed) ) {
        echo 'Archivo invalido: ' . $fileName; exit;
    }

    $zipFileName = $fileName;
    move_uploaded_file($_FILES['file']['tmp_name'], $zipFileName);
}

$tables = array();
$tables = array_merge($tables, dalLdbiExport::getTablesCatalogo());
$tables = array_merge($tables, dalLdbiExport::getTables());

$tmpFolder = "tmpunzip";
mkdir($tmpFolder, 0700);

$now = new DateTime(null, new DateTimeZone("America/Panama"));
$current = $now->format('YmdHis');
$folder = $tmpFolder."/".$current;
mkdir($folder, 0700);

$zipFile = $current.".zip";
$zip = new ZipArchive();
$zip->open($zipFileName);
$zip->extractTo($folder);
$zip->close();

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($folder),
    RecursiveIteratorIterator::LEAVES_ONLY
);

$errors = 0;
$conn = new Connection();
$conn->initConn();
$conn->begin();

foreach ($tables as $table){
    $rawDataFile = $folder."/".$table["name"].".data";
    if (!file_exists($rawDataFile)) continue;

    $handle = fopen($rawDataFile, "r");
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $tuple = json_decode(stripslashes($line),true);
            $originalID = $tuple["data"][$table["id"]];
            if ($table["id"] != null) unset($tuple["data"][$table["id"]]);

            $param = dalLdbiExport::Guardar($conn, $table["name"], $tuple["data"], $table["id"]);
            $id = $param['id'] != 0 ? $param['id'] : $originalID;

            if (!$param['ok']) {
                $errors++;
            } else {
                if ($table["replacements"] != null){
                    if ($param['id'] != 0){
                        foreach ($table["replacements"] as $tableName => $tableKeys){
                            $tableFile = $folder."/".$tableName.".data";
                            foreach ($tableKeys as $tableKey){
                                file_put_contents($tableFile,preg_replace('/\"'.$tableKey.'\":'.$originalID.'/','\"'.$tableKey.'\":'.$id,file_get_contents($tableFile)));
                            }
                        }
                    }
                }
            }
        }
        fclose($handle);
    }
}

if ($errors <= 0) $conn->commit(); else $conn->rollback();
$conn->closeConn();


$files = array_diff(scandir($folder), array('.','..'));
foreach ($files as $file) {
    (is_dir("$folder/$file")) ? delTree("$folder/$file") : unlink("$folder/$file");
}
rmdir($folder);
unlink($zipFileName);

echo "Archivo importado exitosamente!";