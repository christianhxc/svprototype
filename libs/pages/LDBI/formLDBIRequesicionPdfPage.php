<?php

require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

class formLDBIRequesicionPdfPage extends page {

    public $config;

    function __construct($data = null) {
        $this->config = $data;
        parent::__construct($data);
    }

    public function parseHeader(){ }
    public function parseFooter(){ }

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

        $this->tpl->addBlockFile('CONTENT', 'contentBlock', Configuration::templatesPath . 'LDBI/pdf/requesicion.tpl.html');
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

        $regiones = $this->config["catalogos"]["regiones"];
        if (is_array($regiones)) {
            foreach ($regiones as $region) {
                $this->tpl->setCurrentBlock('blkRegion');
                $this->tpl->setVariable("valRegion", $region["codigoRegion"]);
                $this->tpl->setVariable("opcRegion", htmlentities($region["nombreRegion"]));

                if ($this->config["preselect"])
                    $this->tpl->setVariable("selRegion", ($region["codigoRegion"] == $this->config["data"]["requesicion"]["id_region"] ? 'selected="selected"' : ''));

                $this->tpl->parse('blkRegion');
            }
        }

//        echo json_encode($this->config['data']["requesicion"]); exit;
        if ($this->config['data']){
            $this->tpl->setVariable('valRequesicionId', $this->config['data']["requesicion"]['id']);
            $this->tpl->setVariable('valNotificacionRegion', $this->config['data']["requesicion"]['nombre_region']);
            $this->tpl->setVariable('valNotificacionUnidad', $this->config['data']["requesicion"]['nombre_un']);
            $this->tpl->setVariable('valNotificacionIdUn', $this->config['data']["requesicion"]['id_un']);
            $this->tpl->setVariable('valRequesicionNoRequesicion', $this->config['data']["requesicion"]['numero']);
            $this->tpl->setVariable('valRequesicionFechaRequesicion', helperString::toDateView($this->config['data']["requesicion"]['fh_requesicion']));
            $this->tpl->setVariable('valNombreRegistra', $this->config['data']["requesicion"]['registra']);
            $this->tpl->setVariable('valExistencias', htmlspecialchars(json_encode($this->config['data']["existencias"])));
            $this->tpl->setVariable('valRequesicionEstatus', $this->obtenerEstado($this->config['data']["requesicion"]['status']));
            $this->tpl->setVariable('valRequesicionFechaDespacho', $this->config['data']["requesicion"]['fh_despacho'] != "" ? helperString::toDateView($this->config['data']["requesicion"]['fh_despacho']) : "N/D");
            $this->tpl->setVariable('valRequesicionAprobado', 'N/D');
            $this->tpl->setVariable('valRequesicionFechaEnvio', $this->config['data']["requesicion"]['fh_envio'] != "" ? helperString::toDateView($this->config['data']["requesicion"]['fh_envio']) : "N/D");
            $this->tpl->setVariable('valRequesicionBodega', 'N/D');
            $this->tpl->setVariable('valRequesicionFechaEntrega', 'N/D');
            $this->tpl->setVariable('valRequesicionEntregado', 'N/D');
            $this->tpl->setVariable('valRequesicionNoEnvio', $this->config['data']["requesicion"]['no_envio'] != "" ? $this->config['data']["requesicion"]['no_envio'] : "N/D");
        } else {
            $documento = "R".date("ymdhis");
            $this->tpl->setVariable('valRequesicionNoRequesicion', $documento);
            $this->tpl->setVariable('valRequesicionEstatus', 'N/D');
            $this->tpl->setVariable('valRequesicionFechaDespacho', 'N/D');
            $this->tpl->setVariable('valRequesicionAprobado', 'N/D');
            $this->tpl->setVariable('valRequesicionFechaEnvio', 'N/D');
            $this->tpl->setVariable('valRequesicionBodega', 'N/D');
            $this->tpl->setVariable('valRequesicionFechaEntrega', 'N/D');
            $this->tpl->setVariable('valRequesicionEntregado', 'N/D');
            $this->tpl->setVariable('valRequesicionNoEnvio', 'N/D');
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
            $this->tpl->setVariable("botonCancelar", '<a href="requesiciones.php" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');

        $this->tpl->touchBlock('contentBlock');
    }

    private function obtenerEstado($status){
        if ($status == "2") return "Entregados";
        if ($status == "1") return "En Transito";
        return "Pendiente";
    }

}


?>
