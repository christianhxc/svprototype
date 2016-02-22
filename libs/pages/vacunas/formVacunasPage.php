<?php

require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
//require_once ('libs/dal/vih/MuestraSilab.php');

class formVacunas extends page {
    
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
        
        $this->tpl->addBlockFile('CONTENT', 'contentBlock', Configuration::templatesPath . 'vacunas/formulario.tpl.html');
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
        // Condiciones
        $condiciones = $this->config["catalogos"]["condiciones"];
        if (is_array($condiciones)) {
            foreach ($condiciones as $condicion) {
                $this->tpl->setCurrentBlock('blkCondicion');
                $this->tpl->setVariable("valCondicion", $condicion["id_condicion"]);
                $this->tpl->setVariable("opcCondicion", htmlentities($condicion["nombre_condicion"]));
                if (isset($this->config["read"]["id_condicion"]))
                    $this->tpl->setVariable("selCondicion", ($condicion["id_condicion"] == $this->config["read"]["id_condicion"]) ? 'selected="selected"' : '');
                
                $this->tpl->parse('blkCondicion');
            }
        }
        //Vacunas
        $vacunas = $this->config["catalogos"]["vacunas"];
        if (is_array($vacunas)) {
            foreach ($vacunas as $vacuna) {
                $this->tpl->setCurrentBlock('blkVacuna');
                $this->tpl->setVariable("valVacuna", $vacuna["id_vacuna"]);
                $this->tpl->setVariable("opcVacuna", htmlentities($vacuna["nombre_vacuna"]));
                if (isset($this->config["read"]["id_vacuna"]))
                    $this->tpl->setVariable("selVacuna", ($vacuna["id_vacuna"] == $this->config["read"]["id_vacuna"]) ? 'selected="selected"' : '');
                
                $this->tpl->parse('blkVacuna');
            }
        }
        $this->tpl->setVariable("botonAdd", '<a href="javascript:relacionarCondiciones();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Agregar</a>&nbsp;');
        $this->tpl->setVariable("botonAdd1", '<a href="javascript:relacionarDosis();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Agregar</a>&nbsp;');
          
        if ($lectura) {
            $this->tpl->setVariable('action', 'M');
            $this->tpl->setVariable('valIdVacunaForm', $this->config['read']['id_esquema']);
            $this->tpl->setVariable('valReadOnly', 'readonly="readonly" disabled="disabled"');
            //print_r($this->config['read']);exit;
            
            //Individuo
            $this->tpl->setVariable('valVacNombre', $this->config['read']['nombre_esquema']);
            $this->tpl->setVariable('valVacCodigo', $this->config['read']['codigo_esquema']);
            $this->tpl->setVariable('valFechaVigencia', $this->config['read']['fecha_vigencia']);
            $this->tpl->setVariable('valVacEdadIni', $this->config['read']['rango_edad_ini']);
            $rangoIni = $this->config['read']['tipo_rango_ini'];
            switch ($rangoIni) {
                case '1': $this->tpl->setVariable('selEdadIni1', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selEdadIni2', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selEdadIni3', 'selected="selected"'); break;
                case '4': $this->tpl->setVariable('selEdadIni4', 'selected="selected"'); break;
                case '5': $this->tpl->setVariable('selEdadIni5', 'selected="selected"'); break;
            }
            $this->tpl->setVariable('valVacEdadFin', $this->config['read']['rango_edad_fin']);
            $rangoFin = $this->config['read']['tipo_rango_fin'];
            switch ($rangoFin) {
                case '1': $this->tpl->setVariable('selEdadFin1', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selEdadFin2', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selEdadFin3', 'selected="selected"'); break;
                case '4': $this->tpl->setVariable('selEdadFin4', 'selected="selected"'); break;
                case '5': $this->tpl->setVariable('selEdadFin5', 'selected="selected"'); break;
            }
            $sexo = $this->config['read']['sexo'];
            switch ($sexo) {
                case 'M': $this->tpl->setVariable('selSexoM', 'selected="selected"'); break;
                case 'F': $this->tpl->setVariable('selSexoF', 'selected="selected"'); break;
                case 'N': $this->tpl->setVariable('selSexoN', 'selected="selected"'); break;
            }
            $status = $this->config['read']['status'];
            switch ($status) {
                case '0':$this->tpl->setVariable('chkStatusNo', 'checked="checked"');break;
                case '1':$this->tpl->setVariable('chkStatusSi', 'checked="checked"');break;
            }
            $toma_fecha = $this->config['read']['tomar_fecha_vigencia'];
            switch ($toma_fecha) {
                case '0':$this->tpl->setVariable('chkTomaVigenciaNo', 'checked="checked"');break;
                case '1':$this->tpl->setVariable('chkTomaVigenciaSi', 'checked="checked"');break;
            }
            $recordar_correo = $this->config['read']['recordar_correo'];
            switch ($recordar_correo) {
                case '0':$this->tpl->setVariable('chkEnviarCorreoNo', 'checked="checked"');break;
                case '1':$this->tpl->setVariable('chkEnviarCorreoSi', 'checked="checked"');break;
            }
            $this->tpl->setVariable('valIndicaciones', htmlentities($this->config['read']['comentarios']));
            
            //Condiciones relacionadas a la esquema
            $condicionTotal = "";
            if (isset($this->config['read']['condiciones'])) {
                foreach ($this->config['read']['condiciones'] as $condicion) {
                    $condicionTotal .= $condicion['id_condicion']."#-#".htmlentities($condicion['nombre_condicion'])."###";
                }
                $condicionTotal = substr($condicionTotal, 0,strlen($condicionTotal)-3 );
                $this->tpl->setVariable('valCondicionesRelacionados', $condicionTotal);
            }
        }

        // Muestra si ocurrió un error
        $this->tpl->setVariable("mensajeErrorGeneral", $this->config['Merror']);
        $this->tpl->setVariable("disError", (isset($this->config['Merror']) ? '' : 'none'));

        // Muestra botones GUARDAR y CANCELAR según permisos
        require_once ('libs/caus/clsCaus.php');
        if (!$lectura) {
            if (clsCaus::validarSeccion(ConfigurationCAUS::VacFormulario, ConfigurationCAUS::Agregar))
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarVacunas();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
        }
        else {
            if (clsCaus::validarSeccion(ConfigurationCAUS::VacFormulario, ConfigurationCAUS::Modificar))
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarVacunas();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Actualizar</a>&nbsp;');
        }
        if (clsCaus::validarSeccion(ConfigurationCAUS::VacFormulario, ConfigurationCAUS::Consultar))
            $this->tpl->setVariable("botonCancelar", '<a href="index.php" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');

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
