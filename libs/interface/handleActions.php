<?php
require_once('Actions.php');
require_once('Help.php');

class handleActions
{
	public function __construct(){
	}
	
	public static function updateTopico($id,$tipo,$desc,$padre,$padre_desc){
		$tabla = 'topico';
		$filtro['id_topico'] = $id;
		$data['descripcion'] = $desc;
		if ($padre!='') $data['id_padre'] = $padre;
		
		Actions::Actualizar($tabla,$data,$filtro);
		return array('id' => $id, 'descripcion' => $desc, 'padre' => $padre, 'padre_desc' => $padre_desc);
	}
	
	public static function updateRegion($id,$tipo,$desc,$padre,$padre_desc){
		$tabla = 'lugar';
		$filtro['id_lugar'] = $id;
		$data['descripcion'] = $desc;
		if ($padre!='') $data['id_padre'] = $padre;
		
		Actions::Actualizar($tabla,$data,$filtro);
		return array('id' => $id, 'descripcion' => $desc, 'padre' => $padre, 'padre_desc' => $padre_desc);
	}
}
?>