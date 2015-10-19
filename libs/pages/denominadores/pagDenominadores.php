<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

class pagDenominadores extends page{

    public $config;

	function __construct($data = null)
	{
		$this->config = $data;
		parent::__construct($data);
	}

    public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'denominadores/denominadores.tpl.html');
		$data = $this->config["data"];
                
               
                
        // Mostrar el listado de areas de salud
        $datos = $this->config["unidad_notificadora"];

//        if(is_array($datos)){
//                foreach($datos as $dato){
//                        // Preseleccionar la opcion del formulario
//                        $dato["chkas"] = $dato["codigoas"] != $data["encabezado"]["id_area_salud"] ? '': 'selected="selected"';
//                        $dato["chkas"] = count($datos) == 1 ? 'selected="selected"' : $dato["chkas"];
//                        $this->tpl->setCurrentBlock('blkidas');
//                        $this->tpl->setVariable($dato);
//                        $this->tpl->parse('blkidas');
//                }
//        }
        
        $this->tpl->setVariable("valUnidad",$data["encabezado"]["unidad_notificadora"] ? $data["encabezado"]["unidad_notificadora"] : '' );
        $this->tpl->setVariable("valIdUn",$data["encabezado"]["id_un"] ? $data["encabezado"]["id_un"] : '' );

        $user_define= $this->config["user_region"];       
        
        // Nos sirve cuando el usuario es solo para una región y esta predefinido los valores del Área de salud, distrito y Servicio de salud
        if (is_array($user_define)){
            
            $this->tpl->setVariable("idas",$data["encabezado"]["id_area_salud"] ? $data["encabezado"]["id_area_salud"] : $user_define["id_area_salud"] );
            $this->tpl->setVariable("idds",$data["encabezado"]["id_distrito"] ? $data["encabezado"]["id_distrito"] : $user_define["id_distrito"] );
            $this->tpl->setVariable("idts",$data["encabezado"]["id_servicio_salud"] ? $data["encabezado"]["id_servicio_salud"] : $user_define["id_servicio_salud"] );
            
        }else
        {
            $this->tpl->setVariable("idas",$data["encabezado"]["id_area_salud"] ? $data["encabezado"]["id_area_salud"] : '' );
            $this->tpl->setVariable("idds",$data["encabezado"]["id_distrito"] ? $data["encabezado"]["id_distrito"] : '' );
            $this->tpl->setVariable("idts",$data["encabezado"]["id_servicio_salud"] ? $data["encabezado"]["id_servicio_salud"] : '' );

        }    
 
        $this->tpl->setVariable("fecha_server",$this->config["fecha_server"]);
        
        $this->tpl->setVariable("disError", $this->config["error"] ? '' : 'none');

        $this->tpl->setVariable("responsable", $data["encabezado"]["responsable"] ? $data["encabezado"]["responsable"] : '');
        
        //cuando se trata de modificacion aqui se setea la carga de datos
        if ($this->config["preselect"]){
            $this->tpl->setVariable("id_denominador", $data["encabezado"]["id_denominador"] ? $data["encabezado"]["id_denominador"] : '0');
            $this->tpl->setVariable("valSemanaEpi", $data["encabezado"]["semana_epidemilogica"] ? $data["encabezado"]["semana_epidemilogica"] : '0');
            $this->tpl->setVariable("valAnioEpi", $data["encabezado"]["anio"] ? $data["encabezado"]["anio"] : '0');
            if($data["encabezado"]["fecha_notificacion"]){
                $new_date = date('d/m/Y', strtotime($data["encabezado"]["fecha_notificacion"]));
                $this->tpl->setVariable("fecha_notificacion",$new_date);
            }

            if(is_array($data["detalle"])){
                $datosdetalle=$data["detalle"];
                foreach($datosdetalle as $dato){
                    $this->tpl->setVariable("hm".$dato["id_grupo_edad"],$dato["hospitalizacion_m"]);
                    $this->tpl->setVariable("hf".$dato["id_grupo_edad"],$dato["hospitalizacion_f"]);
                    $this->tpl->setVariable("hm_irag".$dato["id_grupo_edad"],$dato["hospitalizacion_m_irag"]);
                    $this->tpl->setVariable("hf_irag".$dato["id_grupo_edad"],$dato["hospitalizacion_f_irag"]);                    
                    $this->tpl->setVariable("um".$dato["id_grupo_edad"],$dato["uci_m"]);
                    $this->tpl->setVariable("uf".$dato["id_grupo_edad"],$dato["uci_f"]);
                    $this->tpl->setVariable("um_irag".$dato["id_grupo_edad"],$dato["uci_m_irag"]);
                    $this->tpl->setVariable("uf_irag".$dato["id_grupo_edad"],$dato["uci_f_irag"]);                    
                    $this->tpl->setVariable("dm".$dato["id_grupo_edad"],$dato["defuncion_m"]);
                    $this->tpl->setVariable("df".$dato["id_grupo_edad"],$dato["defuncion_f"]);
                    $this->tpl->setVariable("dm_irag".$dato["id_grupo_edad"],$dato["defuncion_m_irag"]);
                    $this->tpl->setVariable("df_irag".$dato["id_grupo_edad"],$dato["defuncion_f_irag"]);                    
                    $this->tpl->setVariable("id_detalle_".$dato["id_grupo_edad"],$dato["id_denominador_detalle"]);
                }
            }

        }

        $this->tpl->setVariable("id",$this->config["id"]);
        $this->tpl->setVariable("action",$this->config["action"]);
        
        //fin de carga de datos para modificacion
		$this->tpl->parse('contentBlock');
	}
}
