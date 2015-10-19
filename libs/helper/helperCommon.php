<?php

class helperCommon{
	
	public static function getPaginas(){
		$array[] = array("idPaginado" => "12", "nombrePaginado" => "12");
		$array[] = array("idPaginado" => "24", "nombrePaginado" => "24");
		$array[] = array("idPaginado" => "48", "nombrePaginado" => "48");
		$array[] = array("idPaginado" => "60", "nombrePaginado" => "60");
		
		return $array;
	}
	
	public static function getOrden(){
		$array[] = array("idOrdenado" => "nombre ASC", "nombreOrdenado" => "Alfabetico A - Z");
		$array[] = array("idOrdenado" => "nombre DESC", "nombreOrdenado" => "Alfabetico Z - A");
		$array[] = array("idOrdenado" => "fecha ASC", "nombreOrdenado" => "M&aacute;s antiguos");
		$array[] = array("idOrdenado" => "fecha DESC", "nombreOrdenado" => "M&aacute;s recientes");
		
		return $array;
	}
}