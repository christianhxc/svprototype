<?php

require_once ('libs/caus/clsCaus.php');
require_once ('libs/pages/formMatPage.php');
require_once ('libs/helper/helperString.php');
require_once ('libs/helper/helperMat.php');
require_once ('libs/Configuration.php');
require_once ('libs/Etiquetas.php');
require_once ('libs/dal/dalMat.php');
// Validar si se desea cerrar sesion
if ($_REQUEST["logout"]) {
    clsCaus::cerrarSesion();
    file_get_contents(Configuration::reportAddress . "remotelogin.php?logout=true");
}

// Validar si ya ha iniciado sesion
if (!clsCaus::validarSession()) {
    header("location: ../index.php");
}

//$secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::vih);
//if(count($secciones)){
//    $permiso = 0;
//    foreach($secciones as $c=>$seccion)
//    {
//        switch($c)
//        {
//            case ConfigurationCAUS::vihFormulario:
//                $permiso = 1;
//                break;
//        }
//    }
//}
//
//if ($permiso == 0){
//    header("location: ../index.php");
//}
//
$config = array();
$config["info"] = $_REQUEST["info"];

// Archivos javascript necesarios
$config["jsfiles"][] = "comun.js";
$config["jsfiles"][] = "Utils.js";
$config["jsfiles"][] = "ajax.js";
$config["jsfiles"][] = "jquery.autocomplete.js";
$config["jsfiles"][] = "mat/formulario.js";
$config["jsfiles"][] = "mat/tablaGrupoContacto.js";
$config["jsfiles"][] = "mat/relacionGrupoContacto.js";
$config["cssfiles"][] = "jquery.autocomplete";
//
$config["info"] = $_REQUEST["info"];
$config["action"] = "";
//// NUEVO COMPLETAMENTE
//

if (!isset($_REQUEST["actionEscenario"])&&!isset($_REQUEST["actionContacto"])) {
    // No lleva datos que cargar/mostrar
    $config["actionEscenario"] = "N";
    $config["error"] = false;
    $config["preselect"] = false;
    $config["codigos"] = false;
} 

else if ($_REQUEST["actionEscenario"] == 'N') {
    if (count($_POST)) {
        $config["data"] = $_POST["data"];
        $mensaje = dalMat::GuardarEscenario($_POST["data"]);
        //echo "mensaje:".$mensaje;exit;
        if ($mensaje == 1) {
            $config["Einfo"] = "Se creo correctamente el escenario";
            header("location: formulario.php?infoEscenario=" . $config["Einfo"]);
        } 
        else if ($mensaje == 2) {
            $config["Eerror"] = "Ya existe un escenario ingresado al sistema con los datos<br/>
                    - Evento a analizar: ".$config["data"]["escenario"]["eve_nombre"]."<br/>  
                    - Tipo de algortimo<br/>  
                    - Grupo contacto";
            $config["actionEscenario"] = "N";
        }
        else if ($mensaje == 3) {
            $config["Eerror"] = "No se pudo guardar el escenario ya que usted eligio canal endemico y para esto se requiere m&iacute;nimo de 3 a&ntilde;os de hist&oacute;rico<br/>
                                 El evento seleccionado no cumple con esta caracter&iacute;stica.";
            $config["actionEscenario"] = "N";
        }
    }
}

//Eliminar un Escenario
else if ($_REQUEST["actionEscenario"] == 'EE') {
    $config["action"] = "EE";
    if (isset($_REQUEST["id"])) { // D borrar
        $config['id'] = $_REQUEST["id"];
        $mensaje = dalMat::BorrarEscenario($config);
        if ($mensaje == 1) 
            $config["Einfo"] = "Se elimino correctamente el escenario";
        else 
            $config["Eerror"] = "No se pudo eliminar el escenario, intentelo mas tarde.";        
    }
}

//Modificar un Escenario
 else if ($_REQUEST["actionEscenario"] == 'ME') {
    $config["action"] = "ME";
    
    if (count($_POST)) {
        //print_r($_POST);
        $config["data"] = $_POST["data"];
        $mensaje = dalMat::ActualizarEscenario($_POST["data"]);
        if ($mensaje == 1) 
            $config["Einfo"] = "Se actualizo correctamente el escenario";
        else if ($mensaje == 2) 
            $config["Eerror"] = "No se pudo actualizar el escenario, intentelo mas tarde.";        
    }
}

else if ($_REQUEST["actionContacto"] == 'NC') {
    if (count($_POST)) {
        $config["actionContacto"] = 'NC';
        $config["data"] = $_POST["data"];
        $mensaje = dalMat::GuardarContacto($_POST["data"]);
        //echo "mensaje:".$mensaje;exit;
        if ($mensaje == 1) {
            $config["Cinfo"] = "Se creo correctamente el contacto";
        } 
        else if ($mensaje == 2) {
            $config["Cerror"] = "Ya existe un contacto con los mismos datos<br/>
                    - Nombres: ".$config["data"]["contacto"]["nombres"]."<br/>  
                    - Apellidos: ".$config["data"]["contacto"]["apellidos"]."<br/>  
                    - Email: ".$config["data"]["contacto"]["email"]; 
        }
    }
}

//Eliminar un Escenario
else if ($_REQUEST["actionContacto"] == 'EC') {
    $config["actionContacto"] = 'EC';
    if (isset($_REQUEST["id"])) { // D borrar
        $config['id'] = $_REQUEST["id"];
        $mensaje = dalMat::BorrarContacto($config);
        if ($mensaje == 1) 
            $config["Einfo"] = "Se elimino correctamente el contacto";
        else 
            $config["Eerror"] = "No se pudo eliminar el contacto, intentelo mas tarde.";        
    }
}

//Modificar un Escenario
 else if ($_REQUEST["actionContacto"] == 'MC') {
    $config["actionContacto"] = "MC";
    
    if (count($_POST)) {
        //print_r($_POST);
        $config["data"] = $_POST["data"];
        $mensaje = dalMat::ActualizarContacto($_POST["data"]);
        if ($mensaje == 1) 
            $config["Einfo"] = "Se actualizo correctamente el contacto";
        else if ($mensaje == 2) 
            $config["Eerror"] = "No se pudo actualizar el contacto, intentelo mas tarde.";        
    }
}

//if (!isset($_REQUEST["actionContacto"])) {
//    // No lleva datos que cargar/mostrar
//    $config["actionContacto"] = "N";
//    $config["error"] = false;
//    $config["preselect"] = false;
//    $config["codigos"] = false;
//} 
//

//
////Eliminar un formulario
//else if ($_REQUEST["action"] == 'D') {
//    $config["action"] = "D";
//    if (isset($_REQUEST["idVih"])) { // D borrar
//        $config['idVih'] = $_REQUEST["idVih"];
//        $mensaje = dalVih::BorrarVih($config);
//        if ($mensaje == 1) {
//            $config["info"] = Etiquetas::mensajeInfoBorrar;
//            header("location: index.php?info=" . $config["info"]);
//        } else {
//            $config["Merror"] = Etiquetas::mensajeErrorBorrar;
//            header("location: index.php?info=" . $config["Merror"]);
//        }
//    }
//}
//
//if (isset($_REQUEST["action"])) {
//    if ($_REQUEST["action"] == 'R' || $config["action"] == "R") { //read
//        $config["action"] = "R";
//        if (isset($_REQUEST["tipo"]) && isset($_REQUEST["id"])) { // en M se pierde falta ponerle
//            $config['id_tipo_identidad'] = $_REQUEST["tipo"];
//            $config['numero_identificacion'] = $_REQUEST["id"];
//            
//        } else {
//            $config["data"] = $_POST["data"];
//            $config['id_tipo_identidad'] = (!isset($config["data"]["individuo"]["tipoId"]) ? NULL : $config["data"]["individuo"]["tipoId"]);
//            $config['numero_identificacion'] = (!isset($config["data"]["individuo"]["identificador"]) ? NULL : $config["data"]["individuo"]["identificador"]);
//        }
//        $data = helperVih::buscarVih($config);
//      //  print_r($data);
//        $config['read'] = $data[0];
//        $idVihForm = $config['read']['id_vih_form'];
//        $enfermedades = helperVih::buscarVihEnfermedad($idVihForm);
//        $config['enfermedades'] = $enfermedades;
//        $factores = helperVih::buscarVihFactores($idVihForm);
//        $config['factores'] = $factores;
//        $muestras = helperVih::buscarVihMuestrasSilab($idVihForm);
//        $config['muestras'] = $muestras;
//    }
//} 
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

$config["catalogos"]["provincias"] = helperMat::getProvincias();
$config["catalogos"]["grupos"] = helperMat::getGrupoContaco();
if (!isset($config["Einfo"] ))
    $config["Einfo"] = isset($_REQUEST["infoEscenario"])? $_REQUEST["infoEscenario"]:"";
if (!isset($config["Eerror"] ))
    $config["Eerror"] = isset($_REQUEST["errorEscenario"])? $_REQUEST["errorEscenario"]:"";

$page = new formMat($config);
$page->displayPage();
?>
