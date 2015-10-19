<?php

require_once ('libs/caus/clsCaus.php');
require_once ('libs/pages/formVicItsPage.php');
require_once ('libs/helper/helperLugar.php');
require_once ('libs/helper/helperCatalogos.php');
require_once ('libs/helper/helperString.php');
require_once ('libs/Configuration.php');
require_once ('libs/Etiquetas.php');
require_once ('libs/dal/vicits/dalVicIts.php');

$config = array();
$config["info"] = $_REQUEST["info"];

// Archivos javascript necesarios
$config["jsfiles"][] = "comun.js";
$config["jsfiles"][] = "Utils.js";
//    $config["jsfiles"][] = "Etiquetas.js";
$config["jsfiles"][] = "ajax.js";
$config["jsfiles"][] = "jquery.autocomplete.js";
$config["cssfiles"][] = "jquery.autocomplete";
$config["jsfiles"][] = "vicits/formulario.js";

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
if (count($secciones)) {
    $permiso = 0;
    foreach ($secciones as $c => $seccion) {
        switch ($c) {
            case ConfigurationCAUS::vicItsFormulario:
                $permiso = 1;
                break;
        }
    }
}

if ($permiso == 0) {
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
} else if ($_REQUEST["action"] == 'N') {
    if (count($_POST)) {
        $config["data"] = $_POST["data"];
        $mensaje = dalVicIts::GuardarVicIts($_POST["data"]);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfo;
            header("location: index.php?info=" . $config["info"]);
        } else if ($mensaje == 2) {
            $config["Merror"] = "Ya existe un formulario ingresado al sistema con los datos de identificaci&oacute;n de este paciente<br/>
                    - Tipo de identificaci&oacute;n: " . $config["data"]["individuo"]["tipoId"] . "<br/>                
                    - N&uacute;mero: " . $config["data"]["individuo"]["identificador"];
        }
    }
}

//Modificar formulario
else if ($_REQUEST["action"] == 'M') {
    $config["action"] = "M";
    if (count($_POST)) {
        //print_r($_POST);
        $config["data"] = $_POST["data"];
        $mensaje = dalVicIts::ActualizarVicIts($_POST["data"]);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfoActualizar;
            $config['tipo_identificacion'] = (!isset($config["data"]["individuo"]["tipoId"]) ? NULL : $config["data"]["individuo"]["tipoId"]);
            $config['numero_identificacion'] = (!isset($config["data"]["individuo"]["identificador"]) ? NULL : $config["data"]["individuo"]["identificador"]);
            header("location: index.php?info=" . $config["info"]);
        } else if ($mensaje == 2) {
            $config["Merror"] = Etiquetas::mensajeErrorActualizar;
            $config["action"] = "R";
        }
    }
}

//Eliminar un formulario
else if ($_REQUEST["action"] == 'D') {
    $config["action"] = "D";
    if (isset($_REQUEST["idVicIts"])) { // D borrar
        $config['idVicIts'] = $_REQUEST["idVicIts"];
        $mensaje = dalVicIts::BorrarVicIts($config);
        if ($mensaje == 1) {
            $config["info"] = Etiquetas::mensajeInfoBorrar;
            header("location: index.php?info=" . $config["info"]);
        } else {
            $config["Merror"] = Etiquetas::mensajeErrorBorrar;
            header("location: index.php?info=" . $config["Merror"]);
        }
    }
}
//
if (isset($_REQUEST["action"])) {
    if ($_REQUEST["action"] == 'R' || $config["action"] == "R") { //read
        $config["action"] = "R";
        if (isset($_REQUEST["tipo"]) && isset($_REQUEST["id"]) && isset($_REQUEST["id_form"])) { // en M se pierde falta ponerle
            $config['id_vicits_form'] = $_REQUEST["id_form"];
            $config['id_tipo_identidad'] = $_REQUEST["tipo"];
            $config['numero_identificacion'] = $_REQUEST["id"];
        } else {
            $config["data"] = $_POST["data"];
            $config['id_tipo_identidad'] = (!isset($config["data"]["individuo"]["tipoId"]) ? NULL : $config["data"]["individuo"]["tipoId"]);
            $config['numero_identificacion'] = (!isset($config["data"]["individuo"]["identificador"]) ? NULL : $config["data"]["individuo"]["identificador"]);
        }
        $data = helperVicIts::buscarVicits($config);
        $config['read'] = $data[0];
        $idVicItsForm = $config['read']['id_vicits_form'];
        $its = helperVicIts::buscarVicitsIts($idVicItsForm);
        $config['its'] = $its;
        $drogas = helperVicIts::buscarVicitsDrogas($idVicItsForm);
        $config['drogas'] = $drogas;
        $signosSintomas = helperVicIts::buscarVicitsSignosSintomas($idVicItsForm);
        $config['signos_sintomas'] = $signosSintomas;

        $antibioticos = helperVicIts::buscarVicitsAntibioticos($idVicItsForm);
        $config['antibioticos'] = $antibioticos;

        $diagnosticoTratamiento = helperVicIts::buscarVicitsDiagnosticoTratamiento($idVicItsForm);
        $config['diagnostico_tratamiento'] = $diagnosticoTratamiento;

//        print_r($config['read']);exit;
//        $enfermedades = helperVih::buscarVihEnfermedad($idVihForm);
//        $config['enfermedades'] = $enfermedades;
//        $factores = helperVih::buscarVihFactores($idVihForm);
//        $config['factores'] = $factores;
//        $muestras = helperVih::buscarVihMuestrasSilab($idVihForm);
//        $config['muestras'] = $muestras;
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
//

$config["catalogos"]["paises"] = helperLugar::getPaises();
$config["catalogos"]["provincias"] = helperLugar::getProvincias($config);
$config["catalogos"]["tipoId"] = helperCatalogos::getTipoIdentificacion();
$config["catalogos"]["grupoFactorRiesgo"] = helperCatalogos::getGrupoFactorRiesgo();
$config["catalogos"]["etnia"] = helperCatalogos::getEtnia();
$config["catalogos"]["genero"] = helperCatalogos::getGenero();
$config["catalogos"]["its"] = helperCatalogos::getITS();
$config["catalogos"]["drogas"] = helperCatalogos::getDrogas();
$config["catalogos"]["clinicas"] = helperCatalogos::getClinicasTarv();
$config["catalogos"]["regiones"] = "";
$config["catalogos"]["distritos"] = "";
$config["catalogos"]["corregimientos"] = "";



$page = new formVicIts($config);
$page->displayPage();
?>
