<?php
require_once ('libs/pages/denominadores/pagDenominadores.php');
require_once ('libs/helper/helperCatalogos.php');
require_once ('libs/Pagineo.php');
require_once ('libs/helper/helperLugar.php');
require_once ('libs/dal/denominadores/dalDenominador.php');
//require_once ('libs/dal/dalFichas.php');

// ### VALIDAR ACCESO AL USUARIO ###
require_once ('libs/caus/clsCaus.php');
require_once ('libs/caus/ConfigurationCAUS.php');

$action = $_REQUEST["action"];
//switch ($action){
//    case "M": $uaccion = ConfigurationCAUS::Modificar; break;
//    case "X": $uaccion = ConfigurationCAUS::Borrar; break;
//    default: $uaccion = ConfigurationCAUS::Agregar;
//}
//// Validar si ya ha iniciado sesion
if (!clsCaus::validarSession()){
    header("location: ../login.php");
    exit;
}

// Validar que tenga acceso a esta seccion

// if (!clsCaus::validarSeccion(ConfigurationCAUS::fichaInfluenza)){
    // header("location: ../index.php");
    // exit;
// }

// $config["roles"]["vigilancia"] = clsCaus::validarSeccion(ConfigurationCAUS::fichaInfluenza, ConfigurationCAUS::Vigilancia);

//Menú
$config["page"] = $_REQUEST["pagina"] != "" ? $_REQUEST["pagina"] : 1;
$config["search"]["paginado"] = 12;
$config["search"]["inicio"] = ($config['page'] - 1) * $config["search"]["paginado"];
//$config["search"]["snombre"] = "Denominadores";

//$dalFichas = new dalFichas($config["search"]);
//$config["fichas"] = $dalFichas->GetAll();


// Acciones permitidas para el usuario
//$config["acciones"]["agregar"] = clsCaus::validarSeccion(ConfigurationCAUS::fichaInfluenza, ConfigurationCAUS::Agregar);
//$config["acciones"]["modificar"] = clsCaus::validarSeccion(ConfigurationCAUS::fichaInfluenza, ConfigurationCAUS::Modificar);
//$config["acciones"]["borrar"] = clsCaus::validarSeccion(ConfigurationCAUS::fichaInfluenza, ConfigurationCAUS::Borrar);
//$config["acciones"]["reportes"] = clsCaus::validarSeccion(ConfigurationCAUS::fichaInfluenza, ConfigurationCAUS::Reportes);

// ### VALIDAR ACCESO AL USUARIO ###

// ID de la ficha
//$idficha = Configuration::fichaInfluenza; // Influenza

// Definir los archivos javascript necesarios para esta pagina
//$config["jsfiles"][] = "encabezado.js";
$config["jsfiles"][] = "Utils.js";
$config["jsfiles"][] = "ajax.js";
$config["jsfiles"][] = "jquery.autocomplete.js";
$config["cssfiles"][] = "jquery.autocomplete";
$config["cssfiles"][] = "indicadores";
$config["jsfiles"][] = "denominadores/denominadores.js";

$id = $_REQUEST["id"];
$preselect = false;
$config["action"] = "A";
$config["error"] = false;

// Obtener los datos del formulario
if (count($_POST)){
	$preselect = true;
	$encabezado = $_POST["data"]["encabezado"];
       
    $detalle=$_POST["data"]["detalle"];

    $general["fecha_ingreso"]=date("Y-m-d h:i:s");
    $general["fecha_notificacion"]=helperString::toDate($encabezado["fecha_notificacion"]);
    $general["responsable"]=$encabezado["responsable"];
    $general["semana_epidemilogica"]=$encabezado["semana"];
    $general["anio"]=$encabezado["anio"];
    $general["id_un"]=$encabezado["id_un"];
    $data["encabezado"]=$general;
    $data["detalle"]=$detalle;
//    echo "<pre>";
//    print_r($data["encabezado"]);
//    echo "<br>".$action;
//    echo "</pre>";
    if ($action == "A"){
        $result = dalDenominador::Guardar($data, $config["roles"]);
    }elseif($action == "M"){
        $config["action"] = "M";
        $filtro = array();
        $filtro["id_denominador"] = $id;
        $result = dalDenominador::Modificar($data,$filtro, $config["roles"]);
    }

    if ($result > 0){ // Redireccionar si los datos se guardaron con exito
		header("location: index.php?result=1");
		exit;
	}else{
		// Error
		$config["error"] = true;
	}
	$config["data"] = $data;
    
}else{
	$config["id"] = $id;

	if ($action == "M"){ // Modificar datos
		$config["action"] = "M";
		$preselect = true;
		$config["data"] = dalDenominador::getDatos($id);
	}elseif($action == "X"){ // Borrar datos
		$filtro["id_denominador"] = $id;
		$result = dalDenominador::Borrar($filtro) == -1 ? -1 : 2;
	}
}

$config["preselect"] = $preselect;
//$pagineo = new Pagineo($config['cantidad'],$config['page'],$config["search"]["paginado"],'');
//$config['pagineo'] = $pagineo->getPagineo();
//$config['path'] = 'index';

// Obtener el listado de areas de salud
//$config["areas_salud"] = helperLugar::getAreasSalud();
//$config["areas_salud"] = helperLugar::getAreasSalud();
$config["grupo_edad"]=  helperCatalogos::getGrupoEdad();
$config["fecha_server"] = date("d")."-".date("m")."-".date("Y");

if (count($config["areas_salud"]) == 1){
 
    $datos_distrito =    clsCaus::obtenerUbicaciones(ConfigurationCAUS::DistritoSalud);
    $datos_servicio = clsCaus::obtenerUbicaciones(ConfigurationCAUS::ServicioSalud);
    $general["id_area_salud"]=$config["areas_salud"][0]["codigoas"];
    $general["id_distrito"]=$datos_distrito[0];
    $general["id_servicio_salud"]=$datos_servicio[0];
    $config["user_region"] =  $general;
 
}

if (!is_array($config["data"]["encabezado"])) {
    $config["data"]["encabezado"]["responsable"]=clsCaus::obtenerNombres()." ".clsCaus::obtenerApellidos();
}



$page = new pagDenominadores($config);
$page->displayPage();