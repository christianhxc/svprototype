<?php

require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
//require_once ('libs/dal/vih/MuestraSilab.php');

class formEno extends page {

    public $config;

    function __construct($data = null) {
        $this->config = $data;
        parent::__construct($data);
    }

    public function parseContent() {
        $lectura = false;
        if ($this->config["action"] == "N")
            $nuevo = true;
        else
            $nuevo = false;
        if ($this->config["action"] == "R" || $this->config["action"] == "M") {
            $lectura = true;
        }
        $this->tpl->setVariable("action", $this->config["action"]);

        $this->tpl->addBlockFile('CONTENT', 'contentBlock', Configuration::templatesPath . 'eno/formulario.tpl.html');
        $this->tpl->setVariable('urlPrefix', Configuration::getUrlprefix());            

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
        // Carga catálogo de razones de tipo Id
        
        $servicios = $this->config["catalogos"]["servicio"];
        if (is_array($servicios)) {
            foreach ($servicios as $servicio) {
                $this->tpl->setCurrentBlock('blkSer');
                $this->tpl->setVariable("valSer", $servicio["id_servicio"]);
                $this->tpl->setVariable("opcSer", htmlentities($servicio["nombre_servicio"]));
                if (isset($this->config["read"]["id_servicio"]))
                    $this->tpl->setVariable("selSer", ($servicio["id_servicio"] == $this->config["read"]["id_servicio"]) ? 'selected="selected"' : '');
                
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selSer", ($servicio["id_servicio"] == $this->config["data"]["encabezado"]["id_servicio"]) ? 'selected="selected"' : '');
                else if ($servicio["id_servicio"] == 14)
                    $this->tpl->setVariable("selSer", 'selected="selected"');

                $this->tpl->parse('blkSer');
            }
        }
        
        $provincias = $this->config["catalogos"]["provincias"];
        if (is_array($provincias)) {
            foreach ($provincias as $provincia) {
                $this->tpl->setCurrentBlock('blkPro');
                $this->tpl->setVariable("valPro", $provincia["provincia"]);
                $this->tpl->setVariable("opcPro", htmlentities($provincia["descripcionProvincia"]));
                if (isset($this->config["read"]["id_provincia"]))
                    $this->tpl->setVariable("selPro", ($provincia["provincia"] == $this->config["read"]["id_provincia"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selPro", ($provincia["provincia"] == $this->config["data"]["encabezado"]["idProvincia"] ? 'selected="selected"' : ''));

                $this->tpl->parse('blkPro');
            }
        }
        $regiones = $this->config["catalogos"]["regiones"];
        if (is_array($regiones)) {
            foreach ($regiones as $region) {
                $this->tpl->setCurrentBlock('blkReg');
                $this->tpl->setVariable("valReg", $region["codigoRegion"]);
                $this->tpl->setVariable("opcReg", htmlentities($region["nombreRegion"]));
                if (isset($this->config["read"]["id_region"]))
                    $this->tpl->setVariable("selReg", ($region["codigoRegion"] == $this->config["read"]["id_region"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selReg", ($region["codigoRegion"] == $this->config["data"]["encabezado"]["idRegion"] ? 'selected="selected"' : ''));
                $this->tpl->parse('blkReg');
            }
        }
        
        $distritos = $this->config["catalogos"]["distritos"];
        if (is_array($distritos)) {
            foreach ($distritos as $distrito) {
                $this->tpl->setCurrentBlock('blkDis');
                $this->tpl->setVariable("valDis", $distrito["codigoDistrito"]);
                $this->tpl->setVariable("opcDis", htmlentities($distrito["nombreDistrito"]));
                if (isset($this->config["read"]["id_distrito"]))
                    $this->tpl->setVariable("selDis", ($distrito["codigoDistrito"] == $this->config["read"]["id_distrito"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selDis", ($distrito["codigoDistrito"] == $this->config["data"]["encabezado"]["idDistrito"] ? 'selected="selected"' : ''));
                $this->tpl->parse('blkDis');
            }
        }
        
        $corregimientos = $this->config["catalogos"]["corregimientos"];
        if (is_array($corregimientos)) {
            foreach ($corregimientos as $corregimiento) {
                $this->tpl->setCurrentBlock('blkCor');
                $this->tpl->setVariable("valCor", $corregimiento["codigoCorregimiento"]);
                $this->tpl->setVariable("opcCor", htmlentities($corregimiento["nombreCorregimiento"]));
                if (isset($this->config["read"]["id_corregimiento"]))
                    $this->tpl->setVariable("selCor", ($corregimiento["codigoCorregimiento"] == $this->config["read"]["id_corregimiento"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selCor", ($corregimiento["codigoCorregimiento"] == $this->config["data"]["encabezado"]["idCorregimiento"] ? 'selected="selected"' : ''));
                $this->tpl->parse('blkCor');
            }
        }
        
        if ($nuevo) {
            $nombreUsuario = htmlentities(clsCaus::obtenerNombres()) . " " . htmlentities(clsCaus::obtenerApellidos());
            if (isset($this->config["data"]["notificacion"]["nombreUsuario"]))
                $nombreUsuario = $this->config["data"]["notificacion"]["nombreUsuario"];
            $this->tpl->setVariable('valNombreRegistra', $nombreUsuario);
            $fechaFormulario = date("d/m/Y");
            if (isset($this->config["data"]["notificacion"]["fecha_formulario"]))
                $fechaFormulario = $this->config["data"]["notificacion"]["fecha_formulario"];
            $this->tpl->setVariable('valFecFormVih', $fechaFormulario);
            $this->tpl->setVariable('otraInstalacion', 'none');
            $this->tpl->setVariable('valNotificacionOtraUnidad', '');
            $this->tpl->setVariable('valDatoSilab', 0);

            
        } else if ($lectura) {
            $this->tpl->setVariable('action', 'M');
            $this->tpl->setVariable('valIdEnoForm', $this->config['read']['id_enc']);
            //print_r($this->config['read']);exit;
            //
            
            $this->tpl->setVariable('valUn', ($this->config['read']['nombre_un']));
            $this->tpl->setVariable('valUnNombre', ($this->config['read']['nombre_un']));
            $this->tpl->setVariable('valUnId', $this->config['read']['id_un']);
            
            $this->tpl->setVariable('valFechaIni', $this->config['read']['fecha_inic']);
            $this->tpl->setVariable('valSemanaEpi', $this->config['read']['semana_epi']);
            $this->tpl->setVariable('valAnioEpi', $this->config['read']['anio']);
            $this->tpl->setVariable('valFechaFin', $this->config['read']['fecha_fin']);
            
        }

        // Muestra si ocurrió un error
        $this->tpl->setVariable("mensajeErrorGeneral", $this->config['Merror']);
        $this->tpl->setVariable("disError", (isset($this->config['Merror']) ? '' : 'none'));

        // Muestra botones GUARDAR y CANCELAR según permisos
        require_once ('libs/caus/clsCaus.php');
        if (!$lectura) {
            if (clsCaus::validarSeccion(ConfigurationCAUS::enoFormulario, ConfigurationCAUS::Agregar))
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarEno();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
        }
        else {
            if (clsCaus::validarSeccion(ConfigurationCAUS::enoFormulario, ConfigurationCAUS::Modificar))
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarEno();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Actualizar</a>&nbsp;');
        }
        if (clsCaus::validarSeccion(ConfigurationCAUS::enoFormulario, ConfigurationCAUS::Consultar))
            $this->tpl->setVariable("botonCancelar", '<a href="index.php" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');

        $this->tpl->touchBlock('contentBlock');
    }

}

?>
