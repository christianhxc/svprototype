<?php

require_once ('libs/caus/clsCaus.php');
require_once ('libs/pages/formVihPage.php');
require_once ('libs/helper/helperLugar.php');
require_once ('libs/helper/helperCatalogos.php');
require_once ('libs/helper/helperString.php');
require_once ('libs/Configuration.php');
require_once ('libs/Etiquetas.php');
require_once ('libs/dal/vih/dalVih.php');
require_once ('libs/dal/vih/MuestraSilab.php');
// Validar si se desea cerrar sesion
if ($_REQUEST["logout"]) {
    clsCaus::cerrarSesion();
    file_get_contents(Configuration::reportAddress . "remotelogin.php?logout=true");
}

// Validar si ya ha iniciado sesion
if (!clsCaus::validarSession()) {
    header("location: ../index.php");
}

$secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::vih);
if(count($secciones)){
    $permiso = 0;
    foreach($secciones as $c=>$seccion)
    {
        switch($c)
        {
            case ConfigurationCAUS::vihFormulario:
                $permiso = 1;
                break;
        }
    }
}

if ($permiso == 0){
    header("location: ../index.php");
}

$config = array();
$config["info"] = $_REQUEST["info"];

// Archivos javascript necesarios
$config["jsfiles"][] = "comun.js";
$config["jsfiles"][] = "Utils.js";
//    $config["jsfiles"][] = "Etiquetas.js";
$config["jsfiles"][] = "ajax.js";
$config["jsfiles"][] = "jquery.autocomplete.js";
$config["cssfiles"][] = "jquery.autocomplete";
$config["jsfiles"][] = "vih/formulario.js";
$config["jsfiles"][] = "vih/busquedaVihSilab.js";

$config["info"] = $_REQUEST["info"];
$config["action"] = "";
//echo "hola".$_REQUEST["action"];
//// NUEVO COMPLETAMENTE

if (!isset($_REQUEST["action"])) {
    // No lleva datos que cargar/mostrar
    $config["action"] = "N";
    $config["error"] = false;
    $config["preselect"] = false;
    $config["codigos"] = false;
} 

else if ($_REQUEST["action"] == 'N') {
    if (count($_POST)) {
        //print_r($_POST["data"]);exit;
        $config["data"] = $_POST["data"];
        $mensaje = dalVih::GuardarVih($_POST["data"]);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfo;
            header("location: index.php?info=" . $config["info"]);
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
        $mensaje = dalVih::ActualizarVih($_POST["data"]);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfoActualizar;
            $config['tipo_identificacion'] = (!isset($config["data"]["individuo"]["tipoId"]) ? NULL : $config["data"]["individuo"]["tipoId"]);
            $config['numero_identificacion'] = (!isset($config["data"]["individuo"]["identificador"]) ? NULL : $config["data"]["individuo"]["identificador"]);
            header("location: formulario.php?&action=R&tipo=" . $config['tipo_identificacion']
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
    if (isset($_REQUEST["idVih"])) { // D borrar
        $config['idVih'] = $_REQUEST["idVih"];
        $mensaje = dalVih::BorrarVih($config);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfoBorrar;
            header("location: index.php?info=" . $config["info"]);
        } else {
            $config["Merror"] = Etiquetas::mensajeErrorBorrar;
            header("location: index.php?info=" . $config["Merror"]);
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
        $data = helperVih::buscarVih($config);
      //  print_r($data);
        $config['read'] = $data[0];
        $idVihForm = $config['read']['id_vih_form'];
        $tarv = helperVih::buscarVihTarv($idVihForm);
        $config['tarv'] = $tarv;
        $enfermedades = helperVih::buscarVihEnfermedad($idVihForm);
        $config['enfermedades'] = $enfermedades;
        $factores = helperVih::buscarVihFactores($idVihForm);
        $config['factores'] = $factores;
        //print_r($factores);exit;
        $muestras = helperVih::buscarVihMuestrasSilab($idVihForm);
        $config['muestras'] = $muestras;
        
        //print_r($config['tarv']);exit;
    }
} 
//else if (isset($config["action"])) {
//    if ($config["action"] == "R") { //read
//        $config["data"] = $_POST["data"];
//        //print_r($config['data']);
//        $config['tipo_identificacion'] = (!isset($config["data"]["individuo"]["tipoId"]) ? NULL : $config["data"]["individuo"]["tipoId"]);
//        $config['numero_identificacion'] = (!isset($config["data"]["individuo"]["identificador"]) ? NULL : $config["data"]["individuo"]["identificador"]);
//        $data = helperUceti::buscarUceti($config);
//        $config['read'] = $data[0];
//        $idUceti = $config['read']['id_flureg'];
//        $enfermedades = helperUceti::buscarUcetiEnfermedad($idUceti);
//        $config['enfermedades'] = $enfermedades;
//    }
//}

$config["catalogos"]["provincias"] = helperLugar::getProvincias($config);
$config["catalogos"]["tipoId"] = helperCatalogos::getTipoIdentificacion();
$config["catalogos"]["etnia"] = helperCatalogos::getEtnia();
$config["catalogos"]["genero"] = helperCatalogos::getGenero();
$config["catalogos"]["clinicas"] = helperCatalogos::getClinicasTarv();
$config["catalogos"]["regiones"] = "";
$config["catalogos"]["distritos"] = "";
$config["catalogos"]["corregimientos"] = "";
$config["catalogos"]["factores"] = helperVih::catalogoVihFactores($idVihForm);
$config["catalogos"]["modos"] = helperVih::catalogoVihFactoresModos($idVihForm);

$page = new formVih($config);
$page->displayPage();
?>
