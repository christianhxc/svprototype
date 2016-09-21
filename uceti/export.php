<?php
require_once ('libs/dal/uceti/dalUceti.php');

$ok = true;
$conn = new Connection();
$conn->initConn();
$conn->begin();

$data = dalUceti::GetMuestras($conn, "3002134");

$flureg = 0;
$resultadoFinal = null;
$resultado = null;

foreach ($data as $muestra){
    $currId = $muestra["id_flureg"];
    if ($flureg != $currId) {
        if ($resultadoFinal != null){
            $actualizar[] = GuardarResultadoFinal($flureg, $resultadoFinal);
        }

        $flureg = $currId;
        $currPrioridad = 1000000;
        $resultado = [];
    }

    $resultado["resultado"] = $muestra["resultado"];
    $resultado["tipo1"] = $muestra["tipo1"];
    $resultado["subtipo1"] = $muestra["subtipo1"];

    $prioridad = GetPrioridad($resultado);
    if ($prioridad < $currPrioridad){
        $currPrioridad = $prioridad;
        $resultadoFinal = $resultado;
    }
}

$actualizar[] = GuardarResultadoFinal($flureg, $resultadoFinal);

if ($ok)
    $conn->commit();
else {
    $conn->rollback();
}

$conn->closeConn();

function GuardarResultadoFinal($flureg, $resultadoFinal) {
    $resultadoFinal["id"] = $flureg;
    return $resultadoFinal;
}

function GetPrioridad($muestra){
    $matriz[] = "PCR -POSITIVO";
    $matriz[] = "INFLUENZA (Tipo de virus)";
    $matriz[] = "INFLUENZA A - No subtipificable";
    $matriz[] = "INFLUENZA A H1N1";
    $matriz[] = "INFLUENZA A H1N1 estacional";
    $matriz[] = "INFLUENZA A H1";
    $matriz[] = "INFLUENZA A H3N2 Estacional";
    $matriz[] = "INFLUENZA A H3";
    $matriz[] = "INFLUENZA A H5";
    $matriz[] = "INFLUENZA A H7";
    $matriz[] = "INFLUENZA A - Subtipo no realizado";
    $matriz[] = "INFLUENZA B - Yamagata";
    $matriz[] = "INFLUENZA B - Victoria";
    $matriz[] = "INFLUENZA B - No realizado";
    $matriz[] = "IFI - POSITIVO";
    $matriz[] = "INFLUENZA A";
    $matriz[] = "INFLUENZA B";
    $matriz[] = "VIRUS SINCITIAL RESPIRATORIO";
    $matriz[] = "ADENOVIRUS";
    $matriz[] = "BOCAVIRUS";
    $matriz[] = "CORONAVIRUS";
    $matriz[] = "METAPNEUMOVIRUS";
    $matriz[] = "PARAINFLUENZA I";
    $matriz[] = "PARAINFLUENZA II";
    $matriz[] = "PARAINFLUENZA III";
    $matriz[] = "PARAINFLUENZA IV";
    $matriz[] = "RINOVIRUS";
    $matriz[] = "OTRO";
    $matriz[] = "PCR - NEGATIVO";
    $matriz[] = "IFI - NEGATIVO";

    if (strtoupper($muestra["resultado"]) == "NEGATIVO"){
        return count($matriz)+10;
    }

    $counter = 1;
    foreach ($matriz as $resultado){
        if ($resultado == $muestra["tipo1"]." ".$muestra["subtipo1"]){
            return $counter;
        }
        $counter++;
    }

    return $counter;
}

echo json_encode($actualizar);