<?php
require_once ('libs/pages/ingresoAlicuotasPage.php');
require_once ('libs/dal/ventanilla/dalIngreso.php');
require_once ('libs/helper/helperCatalogos.php');
require_once ('libs/helper/helperLugar.php');


// LOGIN
    require_once ('libs/caus/clsCaus.php');
    require_once ('libs/Configuration.php');

    // Validar si ya ha iniciado sesion
    if (!clsCaus::validarSession()){
        header("location: ../login.php");
    }

$config = array();

// Archivos javascript necesarios
$config["jsfiles"][] = "comun.js";
$config["jsfiles"][] = "ventanilla/alicuotas.js";
$config["jsfiles"][] = "Utils.js";

// Carga catálogos iniciales
$config["catalogos"]["tipo_vigilancia"] = helperCatalogos::getTiposVigilancia();
$config["catalogos"]["area_analisis"] = helperCatalogos::getAreasAnalisis(1);
$config["catalogos"]["razones_rechazo"] = helperCatalogos::getRazonesRechazo();
$config["catalogos"]["departamento"] = helperLugar::getDepartamentos();
$config["catalogos"]["area"] = helperLugar::getAreasSalud();


// NUEVO COMPLETAMENTE
if(!isset($_REQUEST["action"]))
{
    // No lleva datos que cargar/mostrar
    $config["action"]="N";
    $config["error"] = false;
    $config["preselect"] = false;
    $config["codigos"]=false;
}
else if($_REQUEST["action"]=='N')
{
    $data["individuo"] = $_POST["data"]["individuo"];
    $data["muestra"] = $_POST["data"]["muestra"];
    $data["alicuotas"] = $_POST["control"];

    $ids = dalIngreso::GuardarAlicuotas($data);
    if(is_array($ids) && count($ids)>0)
    {
        $result = implode('-', $ids);
        header("location: index.php?a=6&cod=".$result);
        exit;
    }
    else
    {
        // error al ingresar alicuotas desplegar nuevamente
        $config["data"]["individuo"] = $data["individuo"];
        $config["data"]["muestra"] = $data["muestra"];
        $config["preselect"] = true;
        $config["codigos"]=false;
        $config["error"] = true;
    }
}


$page = new ingresoAlicuotasPage($config);
$page->displayPage();
