<?php

require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

class formLDBINotaPage extends page {

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

        $this->tpl->addBlockFile('CONTENT', 'contentBlock', Configuration::templatesPath . 'LDBI/nota.tpl.html');
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
                    $this->tpl->setVariable("selBodega", ($bodega["bodega"] == $this->config["data"]["requesicion"]["id_bodega"] ? 'selected="selected"' : ''));

                $this->tpl->parse('blkBodega');
            }
        }

        $razones = $this->config["catalogos"]["razones"];
        if (is_array($razones)) {
            foreach ($razones as $razon) {
                $this->tpl->setCurrentBlock('blkRazones');
                $this->tpl->setVariable("valRazon", $razon["razon"]);
                $this->tpl->setVariable("opcRazon", htmlentities($razon["descripcionRazon"]));

                if ($this->config["preselect"])
                    $this->tpl->setVariable("selRazon", ($razon["razon"] == $this->config["data"]["requesicion"]["id_razon"] ? 'selected="selected"' : ''));

                $this->tpl->parse('blkRazones');
            }
        }

        $regiones = $this->config["catalogos"]["regiones"];
        if (is_array($regiones)) {
            foreach ($regiones as $region) {
                $this->tpl->setCurrentBlock('blkRegionOrigen');
                $this->tpl->setVariable("valRegionOrigen", $region["codigoRegion"]);
                $this->tpl->setVariable("opcRegionOrigen", htmlentities($region["nombreRegion"]));

                if ($this->config["preselect"])
                    $this->tpl->setVariable("selRegionOrigen", ($region["codigoRegion"] == $this->config["data"]["requesicion"]["id_region_origen"] ? 'selected="selected"' : ''));

                $this->tpl->parse('blkRegionOrigen');

                $this->tpl->setCurrentBlock('blkRegionDestino');
                $this->tpl->setVariable("valRegionDestino", $region["codigoRegion"]);
                $this->tpl->setVariable("opcRegionDestino", htmlentities($region["nombreRegion"]));

                if ($this->config["preselect"])
                    $this->tpl->setVariable("selRegionDestino", ($region["codigoRegion"] == $this->config["data"]["requesicion"]["id_region_destino"] ? 'selected="selected"' : ''));

                $this->tpl->parse('blkRegionDestino');
            }
        }

        $estados = $this->config["catalogos"]["status"];
        if (is_array($estados)) {
            foreach ($estados as $status) {
                $this->tpl->setCurrentBlock('blkStatus');
                $this->tpl->setVariable("valStatus", $status["id"]);
                $this->tpl->setVariable("opcStatus", htmlentities($status["status"]));

                if ($this->config["preselect"])
                    $this->tpl->setVariable("selStatus", ($status["id"] == $this->config["data"]["requesicion"]["status"] ? 'selected="selected"' : ''));

                $this->tpl->parse('blkStatus');
            }
        }

        if ($this->config['data']){
            $this->tpl->setVariable('valEnvioId', $this->config['data']["requesicion"]['id_nota']);
            $this->tpl->setVariable('valEnvioNoNota', $this->config['data']["requesicion"]['no_nota']);
            $this->tpl->setVariable('valEnvioRequesicionId', $this->config['data']["requesicion"]['id_requesicion']);
            $this->tpl->setVariable('valEnvioNoRequesicion', $this->config['data']["requesicion"]['no_requesicion']);
            $this->tpl->setVariable('valEnvioFechaNota', helperString::toDateView($this->config['data']["requesicion"]['fh_nota']));
            $this->tpl->setVariable('valEnvioFechaDespacho', helperString::toDateView($this->config['data']["requesicion"]['fh_despacho']));
            $this->tpl->setVariable('valExistencias', htmlspecialchars(json_encode($this->config['data']["existencias"])));
            $this->tpl->setVariable('valNombreRegistra', $this->config['data']["requesicion"]["nombre_registra"]);
            $this->tpl->setVariable('valBodegaCentral', $this->config['data']["requesicion"]["bodega_central"]);
            if ($this->config['data']["requesicion"]["bodega_central"] == 1) {
                $this->tpl->setVariable('valBodegaCentralCheck', 'checked="checked"');
            }

            $this->tpl->setVariable('valNotificacionUnidadOrigen', $this->config['data']["requesicion"]['nombre_un_origen']);
            $this->tpl->setVariable('valNotificacionIdUnOrigen', $this->config['data']["requesicion"]['id_un_origen']);
            $this->tpl->setVariable('valNotificacionUnidadDestino', $this->config['data']["requesicion"]['nombre_un_destino']);
            $this->tpl->setVariable('valNotificacionIdUnDestino', $this->config['data']["requesicion"]['id_un_destino']);

            $this->tpl->setVariable('valNotificacionRazon', $this->config['data']["requesicion"]['nombre_razon']);
        } else {
            $documento = "N".date("ymdhis");
            $this->tpl->setVariable('valEnvioNoNota', $documento);
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
            $this->tpl->setVariable("botonCancelar", '<a href="notas.php" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');

        $this->tpl->setVariable("botonGuardar", '<a href="javascript:guardar();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
        $this->tpl->setVariable("botonCancelar", '<a href="notas.php" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');

        $this->tpl->touchBlock('contentBlock');
    }

}


?>
