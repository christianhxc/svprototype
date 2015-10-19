<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
require_once("clsCaus.php");

clsCaus::validarLogin("cmelendez", "12345");
//var_dump(clsCaus::validarSeccion(2,4));
//echo clsCaus::crearClave()."<br>";
echo "<pre>"; print_r($_SESSION["user"]); echo "</pre>";
// Areas de Salud - Primer Nivel
//echo "<pre>"; print_r(clsCaus::obtenerUbicacion(1)); echo "</pre>";
// Distritos de Salud - Segundo Nivel
//echo "<pre>"; print_r(clsCaus::obtenerUbicaciones(2)); echo "</pre>";
echo "<pre>"; print_r(clsCaus::obtenerUbicacion(2, "200701000028")); echo "</pre>";
//clsCaus::cerrarSesion();
?>
