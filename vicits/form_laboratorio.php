<?php

require_once ('libs/caus/clsCaus.php');
require_once ('libs/pages/formVicItsLabPage.php');
require_once ('libs/helper/helperCatalogos.php');
require_once ('libs/helper/helperString.php');
require_once ('libs/Configuration.php');
require_once ('libs/Etiquetas.php');
require_once ('libs/dal/vicits/dalVicItsLaboratorio.php');

$config = array();
$config["info"] = $_REQUEST["info"];

// Archivos javascript necesarios
$config["jsfiles"][] = "comun.js";
$config["jsfiles"][] = "Utils.js";
//    $config["jsfiles"][] = "Etiquetas.js";
$config["jsfiles"][] = "ajax.js";
$config["jsfiles"][] = "jquery.autocomplete.js";
$config["cssfiles"][] = "jquery.autocomplete";
$config["jsfiles"][] = "vicits/form_laboratorio.js";

 //Validar si se desea cerrar sesion
if ($_REQUEST["logout"]) {
    clsCaus::cerrarSesion();
    file_get_contents(Configuration::reportAddress . "remotelogin.php?logout=true");
}

// Validar si ya ha iniciado sesion
if (!clsCaus::validarSession()) {
    header("location: ../index.php");
}

$secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::vicIts);
if(count($secciones)){
    $permiso = 0;
    foreach($secciones as $c=>$seccion)
    {
        switch($c)
        {
            case ConfigurationCAUS::vicItsFormLaboratorio:
                $permiso = 1;
                break;
        }
    }
}

if ($permiso == 0){
    header("location: ../index.php");
}

$config["info"] = $_REQUEST["info"];
$config["action"] = "";

if (!isset($_REQUEST["action"])) {
    // No lleva datos que cargar/mostrar
    $config["action"] = "N";
    $config["error"] = false;
    $config["preselect"] = false;
    $config["codigos"] = false;
} 

else if ($_REQUEST["action"] == 'N') {
    if (count($_POST)) {
        $config["data"] = $_POST["data"];
        $mensaje = dalVicItsLaboratorio::GuardarVicItsLab($_POST["data"]);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfo;
            header("location: indexFormLaboratorio.php?info=" . $config["info"]);
        } 
        else if ($mensaje == 2) {
            $config["Merror"] = "Ya existe un formulario ingresado al sistema con los datos de identificaci&oacute;n de este paciente<br/>
                    - Tipo de identificaci&oacute;n: ".$config["data"]["individuo"]["tipoId"]."<br/>                
                    - N&uacute;mero: ".$config["data"]["individuo"]["identificador"];
        }
    }
}

 else if ($_REQUEST["action"] == 'M') {
    $config["action"] = "M";
    if (count($_POST)) {
        //print_r($_POST);
        $config["data"] = $_POST["data"];
        $mensaje = dalVicItsLaboratorio::ActualizarVicitsLab($_POST["data"]);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfoActualizar;
            $config['tipo_identificacion'] = (!isset($config["data"]["formulario"]["tipoId"]) ? NULL : $config["data"]["formulario"]["tipoId"]);
            $config['numero_identificacion'] = (!isset($config["data"]["formulario"]["identificador"]) ? NULL : $config["data"]["formulario"]["identificador"]);
            header("location: form_laboratorio.php?&action=R&tipo=" . $config['tipo_identificacion']
                    . "&id=" . $config['numero_identificacion'] . "&info=" . $config["info"]);
        } else if ($mensaje == 2) {
            $config["Merror"] = Etiquetas::mensajeErrorActualizar;
            $config["action"] = "R";
        }
    }
} 

//Eliminar un formulario
else if ($_REQUEST["action"] == 'D') {
    $config["action"] = "D";
    if (isset($_REQUEST["idVicItsLab"])) { // D borrar
        $config['id_vicits_laboratorio'] = $_REQUEST["idVicItsLab"];
        $mensaje = dalVicItsLaboratorio::BorrarVicitsLab($config);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfoBorrar;
            header("location: indexFormLaboratorio.php?info=" . $config["info"]);
        } else {
            $config["Merror"] = Etiquetas::mensajeErrorBorrar;
            header("location: indexFormLaboratorio.php?info=" . $config["Merror"]);
        }
    }
}

if (isset($_REQUEST["action"])) {
    if ($_REQUEST["action"] == 'R' || $config["action"] == "R") { //read
        $config["action"] = "R";
        if (isset($_REQUEST["tipo"]) && isset($_REQUEST["id"])) { // en M se pierde falta ponerle
            $config['id_tipo_identidad'] = $_REQUEST["tipo"];
            $config['numero_identificacion'] = $_REQUEST["id"];
            
        } else {
            $config["data"] = $_POST["data"];
            $config['id_tipo_identidad'] = (!isset($config["data"]["individuo"]["tipoId"]) ? NULL : $config["data"]["individuo"]["tipoId"]);
            $config['numero_identificacion'] = (!isset($config["data"]["individuo"]["identificador"]) ? NULL : $config["data"]["individuo"]["identificador"]);
        }
        $data = helperVicIts::buscarVicitsLab($config);
        $config['read'] = $data[0];
        $idVicitsLabForm = $config['read']['id_vicits_laboratorio'];
        $muestras = helperVicIts::buscarVicitsLabMuestras($idVicitsLabForm);
        $config['muestras'] = $muestras;
        $pruebas = helperVicIts::buscarVicitsLabPruebas($idVicitsLabForm);
        $config['pruebas'] = $pruebas;
        
        
    }
} 
////else if (isset($config["action"])) {
////    if ($config["action"] == "R") { //read
////        $config["data"] = $_POST["data"];
////        //print_r($config['data']);
////        $config['tipo_identificacion'] = (!isset($config["data"]["individuo"]["tipoId"]) ? NULL : $config["data"]["individuo"]["tipoId"]);
////        $config['numero_identificacion'] = (!isset($config["data"]["individuo"]["identificador"]) ? NULL : $config["data"]["individuo"]["identificador"]);
////        $data = helperUceti::buscarUceti($config);
////        $config['read'] = $data[0];
////        $idUceti = $config['read']['id_flureg'];
////        $enfermedades = helperUceti::buscarUcetiEnfermedad($idUceti);
////        $config['enfermedades'] = $enfermedades;
////    }
////}
//
$config["catalogos"]["poblacion"] = helperCatalogos::getTipoPoblacion();
$config["catalogos"]["muestra"] = helperCatalogos::getTiposMuestras();
$config["catalogos"]["prueba"] = helperCatalogos::getPrueba();
$config["catalogos"]["tipoId"] = helperCatalogos::getTipoIdentificacion();

$page = new formLabVicIts($config);
$page->displayPage();
?>
