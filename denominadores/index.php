<?php
require_once ('libs/pages/denominadores/pagDenominadoresHome.php');
require_once ('libs/helper/helperCatalogos.php');
require_once ('libs/Pagineo.php');
require_once ('libs/dal/denominadores/dalDenominador.php');
//require_once ('libs/dal/dalFichas.php');

// ### VALIDAR ACCESO AL USUARIO ###
require_once ('libs/caus/clsCaus.php');
require_once ('libs/caus/ConfigurationCAUS.php');

// Validar si ya ha iniciado sesion
if (!clsCaus::validarSession()){
    header("location: ../login.php");
    exit;
}

// Validar que tenga acceso a esta seccion
//if (!clsCaus::validarSeccion(ConfigurationCAUS::fichaInfluenza)){
//    header("location: ../index.php");
//    exit;
//}

// Validar que tenga acceso a esta seccion
//if (!clsCaus::validarSeccion(ConfigurationCAUS::fichaInfluenza)){
//    header("location: ../index.php");
//    exit;
//}
//Menú
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1;
$config["search"]["paginado"] = 12;
$config["search"]["inicio"] = ($config['page'] - 1) * $config["search"]["paginado"];
//$config["search"]["snombre"] = "Denominadores";
//$dalFichas = new dalFichas($config["search"]);
////$config["fichas"] = $dalDenominador->GetAll();
//$config["fichas"] = $dalFichas->GetAll();

// Acciones permitidas para el usuario
//$config["acciones"]["agregar"] = clsCaus::validarSeccion(ConfigurationCAUS::fichaInfluenza, ConfigurationCAUS::Agregar);
//$config["acciones"]["modificar"] = clsCaus::validarSeccion(ConfigurationCAUS::fichaInfluenza, ConfigurationCAUS::Modificar);
//$config["acciones"]["borrar"] = clsCaus::validarSeccion(ConfigurationCAUS::fichaInfluenza, ConfigurationCAUS::Borrar);
//$config["acciones"]["reportes"] = clsCaus::validarSeccion(ConfigurationCAUS::fichaInfluenza, ConfigurationCAUS::Reportes);


// ### VALIDAR ACCESO AL USUARIO ###

// ID de la ficha
//$idficha = Configuration::denominadores; // denominadores

// Definir los archivos javascript necesarios para esta pagina
//$config["jsfiles"][] = "encabezado.js";
$config["jsfiles"][] = "Utils.js";
$config["jsfiles"][] = "denominadores/denominadorHome.js";

$search = $_REQUEST["search"];
$config["search"] = $search;
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1;
$config["result"] = $_REQUEST["result"];
$config["search"]["order"] = $search["order"] == "" ? "fecha_notificacion DESC" : $search["order"];
$config["search"]["paginado"] = 10;
$config["search"]["inicio"] = ($config['page'] - 1) * $config["search"]["paginado"];

// Datos de la ficha
$dal = new dalDenominador($config["search"]);
$config["entradas"] = $dal->getAll();
$config['cantidad'] = $dal->getCountAll();

$pagineo = new Pagineo($config['cantidad'],$config['page'],$config["search"]["paginado"],'');
$config['pagineo'] = $pagineo->getPagineo();
$config['path'] = 'index';

$page = new pagDenominadoresHome($config);
$page->displayPage();