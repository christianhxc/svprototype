<?php

header('Content-Type: text/html; charset=iso-8859-1');
require_once('libs/nusoap.php');
//require_once('libs/Configuration.php');
//require_once('libs/Connection.php');
// Crear un cliente apuntando al script del servidor (Creado con WSDL)
$serverURL = 'http://200.115.148.55/silab/webservice';
$serverScript = 'sqlws.php';
$metodoALlamar = 'servicio';

//echo 'URL:'.$serverURL.'/'.$serverScript.'?wsdl<br>';
//echo 'nameSpace:'.$serverURL.'/'.$serverScript.'<br>';
//echo 'soap:'.$serverURL.'/'.$serverScript.'/'.$metodoALlamar.'<br>';	
//echo 'Metodo:'.$metodoALlamar.'<br>';
$cliente = new nusoap_client("$serverURL/$serverScript?wsdl", 'wsdl');
//  Se pudo conectar?
$error = $cliente->getError();
if ($error) {
    echo '<pre style="color: red">' . $error . '</pre>';
    echo '<p style="color:red;' > htmlspecialchars($cliente->getDebug(), ENT_QUOTES) . '</p>';
    die();
}

$sql = "SELECT * FROM individuo LIMIT 0,10";

// 1. Llamar a la funcion getRespuesta del servidor
$result = $cliente->call(
        "$metodoALlamar", // Funcion a llamar
        array('sentencia' => $sql), // Parametros pasados a la funcion
        "uri:$serverURL/$serverScript", // namespace
        "uri:$serverURL/$serverScript/$metodoALlamar" // SOAPAction
);
$data = json_decode($result);

print_r($data);

// Verificacion que los parametros estan ok, y si lo estan. mostrar rta.
//if ($cliente->fault) {
//    echo '<b>Error: ';
//    print_r($result);
//    echo '</b>';
//} else {
//    $error = $cliente->getError();
//    if ($error) {
//        echo '<b style="color: red">-Error: ' . $error . '</b>';
//    } else {
//        echo base64_decode($result);
//        echo '-----------------------------------------<br>';
//        echo replaceCharacter(base64_decode($result));
//    }
//}

function replaceCharacter($div) {
    $n_div = str_replace("�", "a", $div);
    $n_div = str_replace("�", "e", $n_div);
    $n_div = str_replace("�", "i", $n_div);
    $n_div = str_replace("�", "o", $n_div);
    $n_div = str_replace("�", "u", $n_div);

    $n_div = str_replace("�", "A", $n_div);
    $n_div = str_replace("�", "E", $n_div);
    $n_div = str_replace("�", "I", $n_div);
    $n_div = str_replace("�", "O", $n_div);
    $n_div = str_replace("�", "U", $n_div);

    $n_div = str_replace("�", "a", $n_div);
    $n_div = str_replace("�", "e", $n_div);
    $n_div = str_replace("�", "i", $n_div);
    $n_div = str_replace("�", "o", $n_div);
    $n_div = str_replace("�", "u", $n_div);

    $n_div = str_replace("�", "n", $n_div);
    $n_div = str_replace("�", "N", $n_div);
    return $n_div;
}

