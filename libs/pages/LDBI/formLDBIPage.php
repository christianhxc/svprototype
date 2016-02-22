<?php

require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

class formLDBI extends page {

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
//        echo "Guardar previo ".$this->config["guardarPrevio"];
        if ($this->config["guardarPrevio"] == "2") {
            $this->tpl->setVariable("updateMuestra", "1");
        }
        $this->tpl->setVariable("action", $this->config["action"]);

        $this->tpl->addBlockFile('CONTENT', 'contentBlock', Configuration::templatesPath . 'LDBI/existencias.tpl.html');
        $this->tpl->setVariable('urlPrefix', Configuration::getUrlprefix());


        // ESTADO DE LA MUESTRA

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

            $nombreUsuario = htmlentities(clsCaus::obtenerNombres()) . " " . htmlentities(clsCaus::obtenerApellidos());
            if (isset($this->config["data"]["notificacion"]["nombreUsuario"]))
                $nombreUsuario = $this->config["data"]["notificacion"]["nombreUsuario"];
            $this->tpl->setVariable('valNombreRegistra', $nombreUsuario);
            $institucionUsuario = htmlentities(clsCaus::obtenerOrgCodigo());
            if (isset($this->config["data"]["notificacion"]["institucionUsuario"]))
                $institucionUsuario = $this->config["data"]["notificacion"]["institucionUsuario"];
            $this->tpl->setVariable('valInstitucionUsuario', $institucionUsuario);
            $fechaFormulario = date("d/m/Y");
            if (isset($this->config["data"]["envio"]["fecha_ingreso_envio"]))
                $fechaFormulario = $this->config["data"]["envio"]["fecha_ingreso_envio"];
            $this->tpl->setVariable('valFechaFormulario', $fechaFormulario);

        $this->tpl->setVariable("mensajeErrorGeneral", $this->config['Merror']);
        $this->tpl->setVariable("disError", (isset($this->config['Merror']) ? '' : 'none'));


        $bodegas = $this->config["catalogos"]["bodegas"];
        if (is_array($bodegas)) {
            foreach ($bodegas as $bodega) {
                $this->tpl->setCurrentBlock('blkBodega');
                $this->tpl->setVariable("valBodega", $bodega["bodega"]);
                $this->tpl->setVariable("opcBodega", htmlentities($bodega["descripcionBodega"]));

                if ($this->config["preselect"])
                    $this->tpl->setVariable("selBodega", ($bodega["bodega"] == $this->config["data"]["envio"]["id_bodega"] ? 'selected="selected"' : ''));

                $this->tpl->parse('blkBodega');
            }
        }

        $proveedores = $this->config["catalogos"]["proveedores"];
        if (is_array($proveedores)) {
            foreach ($proveedores as $proveedor) {
                $this->tpl->setCurrentBlock('blkProveedor');
                $this->tpl->setVariable("valProveedor", $proveedor["proveedor"]);
                $this->tpl->setVariable("opcProveedor", htmlentities($proveedor["descripcionProveedor"]));

                if ($this->config["preselect"])
                    $this->tpl->setVariable("selProveedor", ($proveedor["proveedor"] == $this->config["data"]["envio"]["id_proveedor"] ? 'selected="selected"' : ''));

                $this->tpl->parse('blkProveedor');
            }
        }

        if ($this->config['data']){
            $this->tpl->setVariable('valEnvioId', $this->config['data']["envio"]['id_envio']);
            $this->tpl->setVariable('valEnvioNoEnvio', $this->config['data']["envio"]['no_envio']);
            $this->tpl->setVariable('valEnvioReferencia', $this->config['data']["envio"]['no_referencia']);
            $this->tpl->setVariable('valEnvioFechaEnvio', helperString::toDateView($this->config['data']["envio"]['fh_envio']));
            $this->tpl->setVariable('valEnvioFechaDespacho', helperString::toDateView($this->config['data']["envio"]['fh_despacho']));
            $this->tpl->setVariable('valNombreRegistra', $this->config['data']["envio"]['nombre_registra']);
            $this->tpl->setVariable('valExistencias', htmlspecialchars(json_encode($this->config['data']["existencias"])));
        } else {
            $documento = "B".date("ymdhis");
            $this->tpl->setVariable('valEnvioNoEnvio', $documento);
        }

        // Muestra botones GUARDAR y CANCELAR según permisos
        require_once ('libs/caus/clsCaus.php');
        if (!$lectura) {
            if (clsCaus::validarSeccion(ConfigurationCAUS::influenza, ConfigurationCAUS::Agregar)) {
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:guardar();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
                $this->tpl->setVariable("botonGuardarPrevio", '<a href="javascript:guardarPrevio(0);" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardado Previo</a>&nbsp;');
            }
        } else {
            if (clsCaus::validarSeccion(ConfigurationCAUS::influenza, ConfigurationCAUS::Agregar)) {
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:guardar();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Actualizar</a>&nbsp;');
                $this->tpl->setVariable("botonGuardarPrevio", '<a href="javascript:guardarPrevio(0);" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardado Previo</a>&nbsp;');
            }
        }
        if (clsCaus::validarSeccion(ConfigurationCAUS::influenza, ConfigurationCAUS::Consultar))
            $this->tpl->setVariable("botonCancelar", '<a href="listadoexistencias.php" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');

        $this->tpl->setVariable("botonGuardar", '<a href="javascript:guardar();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
        $this->tpl->setVariable("botonCancelar", '<a href="listadoexistencias.php" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');

        $this->tpl->touchBlock('contentBlock');
    }

}


?>
