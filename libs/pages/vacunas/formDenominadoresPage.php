<?php

require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
//require_once ('libs/dal/vih/MuestraSilab.php');

class formDenominadores extends page {
    
    public $config;

    function __construct($data = null) {
        $this->config = $data;
        parent::__construct($data);
    }

    public function parseContent() {
        date_default_timezone_set("America/Panama");
        header("Content-Type: text/html;charset=utf-8");
        $lectura = false;
        if ($this->config["action"] == "N")
            $nuevo = true;
        else
            $nuevo = false;
        if ($this->config["action"] == "R" || $this->config["action"] == "M") {
            $lectura = true;
        }
        
        $this->tpl->setVariable("action", $this->config["action"]);
        
        $this->tpl->addBlockFile('CONTENT', 'contentBlock', Configuration::templatesPath . 'vacunas/formDenominadores.tpl.html');
        $this->tpl->setVariable('urlPrefix', Configuration::getUrlprefix());

        // ESTADO DE LA MUESTRA
//                $this->tpl->setVariable('estadoMuestra',$this->config["data"]["SIT_ID"]);
//                $this->tpl->setVariable('idMuestra',$this->config["data"]["MUE_ID"]);                

        $this->tpl->setVariable("disInfo", $this->config["info"] != "" ? '' : 'none');
        $this->tpl->setVariable("desInfo", $this->config["info"]);

        if ($this->config["error"]) {
            $this->tpl->setVariable('mensajeError', '');
            $this->tpl->setVariable('mostrarError', '');
            $this->tpl->setVariable('valError', $this->config["mensaje"]);
        } else {
            $this->tpl->setVariable('mensajeError', 'none');
            $this->tpl->setVariable('mostrarError', 'none');
            $this->tpl->setVariable('valError', '');
        }
        // Carga catálogos
        // Provincias
        $provincias = $this->config["catalogos"]["provincias"];
        if (is_array($provincias)) {
            foreach ($provincias as $provincia) {
                $this->tpl->setCurrentBlock('blkPro');
                $this->tpl->setVariable("valPro", $provincia["provincia"]);
                $this->tpl->setVariable("opcPro", htmlentities($provincia["descripcionProvincia"]));
                if (isset($this->config["read"]["id_provincia"]))
                    $this->tpl->setVariable("selPro", ($provincia["provincia"] == $this->config["read"]["id_provincia"]) ? 'selected="selected"' : '');
                
                $this->tpl->parse('blkPro');
            }
        }
        //Grupos especiales
        $grupos = $this->config["catalogos"]["grupos"];
        if (is_array($grupos)) {
            foreach ($grupos as $grupo_esp) {
                $this->tpl->setCurrentBlock('drpGrupo');
                $this->tpl->setVariable("valGrupo", $grupo_esp["id_condicion"]);
                $this->tpl->setVariable("opcGrupo", htmlentities($grupo_esp["nombre_condicion"]));
                if (isset($this->config["read"]["grupo"]))
                    $this->tpl->setVariable("selGrupo", ($grupo_esp["id_grupo_esp"] == $this->config["read"]["grupo"]) ? 'selected="selected"' : '');
                
                $this->tpl->parse('blkGrupo');
            }
        }
          
        if ($lectura) {
            $this->tpl->setVariable('action', 'M');
            $this->tpl->setVariable('valIdForm', $this->config['read']['id_vac_denominador']);
            $this->tpl->setVariable('valReadOnly', 'readonly="readonly" disabled="disabled"');
            
            $this->tpl->setVariable('valIdNivel', $this->config['read']['nivel']);
            $this->tpl->setVariable('valNotificacionIdUn', $this->config['read']['id_un']);
            $this->tpl->setVariable('valNotificacionUnidad', htmlentities($this->config['read']['nombre_un']));
            $this->tpl->setVariable('valIdPro', $this->config['read']['id_provincia']);
            $this->tpl->setVariable('valIdReg', $this->config['read']['id_region']);
            $this->tpl->setVariable('valIdDis', $this->config['read']['id_distrito']);
            $this->tpl->setVariable('valIdCor', $this->config['read']['id_corregimiento']);
            $this->tpl->setVariable('valAnio', $this->config['read']['anio']);
        }

        // Muestra si ocurrió un error
        $this->tpl->setVariable("mensajeErrorGeneral", $this->config['Merror']);
        $this->tpl->setVariable("disError", (isset($this->config['Merror']) ? '' : 'none'));

        // Muestra botones GUARDAR y CANCELAR según permisos
        require_once ('libs/caus/clsCaus.php');
        if (!$lectura) {
            if (clsCaus::validarSeccion(ConfigurationCAUS::VacDenominadores, ConfigurationCAUS::Agregar)){
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarDenominador();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
                $this->tpl->setVariable("btnCargar", '<a href="javascript:validarDenominador_file();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cargar</a>&nbsp;');
            }
        }
        else {
            if (clsCaus::validarSeccion(ConfigurationCAUS::VacDenominadores, ConfigurationCAUS::Modificar))
                {
                    $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarDenominador();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Actualizar</a>&nbsp;');
                    $this->tpl->setVariable("btnCargar", '<a href="javascript:validarDenominador_file();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cargar</a>&nbsp;');
                }
        }
        if (clsCaus::validarSeccion(ConfigurationCAUS::VacDenominadores, ConfigurationCAUS::Consultar))
            $this->tpl->setVariable("botonCancelar", '<a href="listadoDenominadores.php" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');

        $this->tpl->touchBlock('contentBlock');
    }
    
    function decode($string) 
    { 
        if(mb_detect_encoding($string, 'UTF-8', true)) 
           return utf8_decode(stripcslashes($string)); 
       else 
           return htmlspecialchars(stripcslashes($string)); 
    } 

}



?>
