<?php
require_once ('libs/pages/ingresoVentanillaPage.php');
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
$config["jsfiles"][] = "Utils.js";
$config["jsfiles"][] = "jquery.autocomplete.js";
$config["jsfiles"][] = "jquery.cascade.js";
$config["jsfiles"][] = "jquery.cascade.ext.js";
$config["jsfiles"][] = "jquery.templating.js";
$config["jsfiles"][] = "ventanilla/ingreso.js";

$config["cssfiles"][] = "jquery.autocomplete.css";

// Carga catálogos iniciales
$config["catalogos"]["tipo_vigilancia"] = helperCatalogos::getTiposVigilancia();
$config["catalogos"]["area_analisis"] = helperCatalogos::getAreasAnalisis(1);
$config["catalogos"]["razones_rechazo"] = helperCatalogos::getRazonesRechazo();
$config["catalogos"]["departamento"] = helperLugar::getDepartamentos();
$config["catalogos"]["area"] = helperLugar::getAreasSalud();
$config["Merror"]='';

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
    // Obtener data de formularios
    $data["individuo"] = $_POST["data"]["individuo"];
    $data["muestra"] = $_POST["data"]["muestra"];   
    $result = dalIngreso::Guardar($data);
    if($result>0)
    {
        header("location: index.php?a=1&cod=".$result);
        exit;
    }
    else
    {
        $config["Merror"] = $result;
//        if($result==-1)
//            $config["Merror"] = 'Ocurri&oacute; un error al momento de guardar los datos, por favor intente nuevamente.';
//        else if($result == -2)
//            $config["Merror"] = 'IMPOSIBLE AGREGAR MUESTRA: Ya existe una muestra ingresada con esos datos.';
        $config["data"]["individuo"] = $data["individuo"];
        $config["data"]["muestra"] = $data["muestra"];
        $config["preselect"] = true;
        $config["codigos"]=false;
        $config["error"] = true;
        $config["action"] = 'N';
        $config["muestra"] = '-1';
        $config["errorNuevo"] = true;
    }
}
else if($_REQUEST["action"]=='M')
{
    // Mostrar únicamente
    $data = helperMuestra::getMuestra($_REQUEST["m"]);
    //print_r($data);exit;
    $config["muestra"]=$_REQUEST["m"];
    $config["action"]="E";

    if(count($data)!=0)
    {
        $config["data"]["individuo"] = $data["individuo"];
        $config["data"]["muestra"] = $data["muestra"];
        $config["preselect"]=true;
        $config["codigos"]=true;
        $config["error"] = false;
    }
    else
    {
        header("location: index.php?a=5&cod=z");
        exit;
    }
    //
}
else if($_REQUEST["action"]=="E")
{    
    //echo "vamos aqui y bien";exit;
    $data["individuo"] = $_REQUEST["data"]["individuo"];
    $data["muestra"] = $_REQUEST["data"]["muestra"];
    $result = dalIngreso::Modificar($data, $_REQUEST["muestra"]);

    if($result<0)
    {
        if($result==-1)
            $config["Merror"] = 'Ocurri&oacute; un error al momento de guardar los datos, por favor intente nuevamente.';
        else if($result == -2)
            $config["Merror"] = 'IMPOSIBLE EDITAR MUESTRA: Ya existe una muestra ingresada con esos datos.';
        $config["data"]["individuo"] = $data["individuo"];
        $config["data"]["muestra"] = $data["muestra"];
        $config["preselect"] = true;
        $config["codigos"]=false;
        $config["error"] = true;
    }
    else
    {
        header("location: index.php?a=2&cod=".$_REQUEST["muestra"]);
        exit;
    }
}
else if($_REQUEST["action"]=="B")
{
    $result = dalIngreso::Borrar($_REQUEST["m"]);
    if($result!=-1)
    {
        header("location: index.php?a=3&cod=".$_REQUEST["m"]);
        exit;
    }
    else
    {
        header("location: index.php?a=4&cod=z");
        exit;
    }        
}

$page = new ingresoVentanillaPage($config);
$page->displayPage();
