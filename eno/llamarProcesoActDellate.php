<?php
    //Incluimos el fichero para el dataGrid
    require '../Include/ConfigBDSisvig.class.php';
    $bdSisvig=DbSisvig::getInstance();
    if (IsSET($_POST["enoId"])&&IsSET($_POST["eveId"])){
        $bdSisvig->ejecutarSQL("CALL sp_encabezado_upd(".$_POST["enoId"].",".$_POST["eveId"].")");
        echo $bdSisvig->getError();
    }
?>
