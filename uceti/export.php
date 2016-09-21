<?php
require_once ('libs/dal/uceti/dalUceti.php');

$ok = true;
$conn = new Connection();
$conn->initConn();
$conn->begin();

$data = dalUceti::GetMuestras($conn);

$flureg = 0;
$currPrioridad = 1000000;
$resultadoFinal = null;
$resultado = null;

foreach ($data as $muestra){
    $currId = $muestra["id_flureg"];
    if ($flureg != $currId) {
        if ($resultadoFinal != null){
            GuardarResultadoFinal($conn, $flureg, $resultadoFinal);
        }

        $flureg = $currId;
        $currPrioridad = 1000000;
        $resultado = [];
    }

    $resultado["final_resultado"] = $muestra["resultado"];
    $resultado["final_tipo"] = $muestra["tipo1"];
    $resultado["final_subtipo"] = $muestra["subtipo1"];
    $resultado["final_linaje"] = "";

    $prioridad = GetPrioridad($resultado);
    if ($prioridad < $currPrioridad){
        $currPrioridad = $prioridad;
        $resultadoFinal = $resultado;
    }
}

if ($resultadoFinal != null) {
    GuardarResultadoFinal($conn, $flureg, $resultadoFinal);
}

if ($ok)
    $conn->commit();
else {
    $conn->rollback();
}

$conn->closeConn();

function GuardarResultadoFinal($conn, $flureg, $resultadoFinal) {
    $filtro["id_flureg"] = $flureg;
    $parmetros = $resultadoFinal;
    $parmetros["id_flureg"] = $filtro["id_flureg"];

    var_dump($parmetros);
    echo "<br>";

    $param = dalUceti::ActualizarTabla($conn, "flureg_form", $resultadoFinal, $filtro, $parmetros);
    $ok = $param['ok'];

    $param = dalUceti::GuardarBitacora($conn, "2", "flureg_form");
    $ok = $param['ok'];

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

    if (strtoupper($muestra["final_resultado"]) == "NEGATIVO"){
        return count($matriz)+10;
    }

    $counter = 1;
    foreach ($matriz as $resultado){
        if ($resultado == $muestra["final_tipo"]." ".$muestra["final_subtipo"]){
            return $counter;
        }
        $counter++;
    }

    return $counter;
}