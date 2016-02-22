<?php

require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');
//require_once ('libs/dal/vih/MuestraSilab.php');

class formVacRegistroDiario extends page {
    
    public $config;

    function __construct($data = null) {
        $this->config = $data;
        parent::__construct($data);
    }

    public function parseContent() {
        date_default_timezone_set("America/Panama");
        header("Content-Type: text/html;charset=utf-8");
        $lectura = false;
        if ($this->config["action"] == "N"){
            $nuevo = true;
                $this->tpl->setVariable("valinst_salud_new", $this->config['data_new']["inst_salud"]);
                $this->tpl->setVariable("valfuncionario_new", $this->config['data_new']["funcionario_new"]);
                $this->tpl->setVariable("valfecha_aplicacion_new", $this->config['data_new']["fecha_aplicacion"]);
                $this->tpl->setVariable("valmod_apli_new", $this->config['data_new']["mod_apli"]);
                $this->tpl->setVariable("valDatoReporte", "style='display:none'");
            }
        else{
            $nuevo = false;
        }
        
        if ($this->config["action"] == "R" || $this->config["action"] == "M") {
            $lectura = true;
            $this->tpl->setVariable("valinst_salud_mod", $this->config['data_new']["inst_salud"]);
            $this->tpl->setVariable("valfuncionario_mod", $this->config['data_new']["funcionario_new"]);
            $this->tpl->setVariable("valfecha_aplicacion_mod", $this->config['data_new']["fecha_aplicacion"]);
            $this->tpl->setVariable("valmod_apli_mod", $this->config['data_new']["mod_apli"]);
        }
        //echo "hola";
        $this->tpl->setVariable("action", $this->config["action"]);
//        echo $this->config["action"];
        
        // Datos que se repiten en el ingreso

        
        $this->tpl->addBlockFile('CONTENT', 'contentBlock', Configuration::templatesPath . 'vacunas/formRegistroDiario.tpl.html');
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
        
        $tiposId = $this->config["catalogos"]["tipoId"];
        //print_r($tiposId);exit;
        if (is_array($tiposId)) {
            foreach ($tiposId as $tipoId) {
                $this->tpl->setCurrentBlock('blkTipoId');
                $this->tpl->setVariable("valTipoId", $tipoId["id_tipo_identidad"]);
                $this->tpl->setVariable("opcTipoId", htmlentities($tipoId["nombre_tipo"]));
                if (isset($this->config["read"]["tipo_identificacion"]))
                    $this->tpl->setVariable("selTipoId", ($tipoId["id_tipo_identidad"] == $this->config["read"]["tipo_identificacion"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selTipoId", ($tipoId["id_tipo_identidad"] == $this->config["data"]["individuo"]["tipoId"]) ? 'selected="selected"' : '');

                $this->tpl->parse('blkTipoId');
            }
        }
        
        if (is_array($tiposId)) {
            foreach ($tiposId as $tipoId) {
                $this->tpl->setCurrentBlock('blkTipoIdBus');
                $this->tpl->setVariable("valTipoIdBus", $tipoId["id_tipo_identidad"]);
                $this->tpl->setVariable("opcTipoIdBus", htmlentities($tipoId["nombre_tipo"]));
                $this->tpl->parse('blkTipoIdBus');
                
                $this->tpl->setCurrentBlock('blkTipoIdBusActual');
                $this->tpl->setVariable("valTipoIdBusActual", $tipoId["id_tipo_identidad"]);
                $this->tpl->setVariable("opcTipoIdBusActual", htmlentities($tipoId["nombre_tipo"]));
                $this->tpl->parse('blkTipoIdBusActual');
                
                $this->tpl->setCurrentBlock('blkTipoIdBusNuevo');
                $this->tpl->setVariable("valTipoIdBusNuevo", $tipoId["id_tipo_identidad"]);
                $this->tpl->setVariable("opcTipoIdBusNuevo", htmlentities($tipoId["nombre_tipo"]));
                $this->tpl->parse('blkTipoIdBusNuevo');
            }
        }
              
        $paises = $this->config["catalogos"]["paises"];
        if (is_array($paises)) {
            foreach ($paises as $pais) {
                $this->tpl->setCurrentBlock('blkPais');
                $this->tpl->setVariable("valPais", $pais["pais"]);
                $this->tpl->setVariable("opcPais", htmlentities($pais["descripcionPais"]));
                if ($nuevo)
                    $this->tpl->setVariable("selPais", ($pais["pais"] == 174 ? 'selected="selected"' : ''));
                else if (isset($this->config["read"]["per_id_pais"]))
                    $this->tpl->setVariable("selPais", ($pais["pais"] == $this->config["read"]["per_id_pais"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selPais", ($pais["pais"] == $this->config["data"]["individuo"]["pais"] ? 'selected="selected"' : ''));
                $this->tpl->parse('blkPais');
            }
        }

        $provincias = $this->config["catalogos"]["provincias"];
        if (is_array($provincias)) {
            foreach ($provincias as $provincia) {
                $this->tpl->setCurrentBlock('blkProIndividuo');
                $this->tpl->setVariable("valProIndividuo", $provincia["provincia"]);
                $this->tpl->setVariable("opcProIndividuo", htmlentities($provincia["descripcionProvincia"]));
                if (isset($this->config["read"]["id_provincia"]))
                    $this->tpl->setVariable("selProIndividuo", ($provincia["id_provincia"] == $this->config["read"]["id_provincia"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selProIndividuo", ($provincia["id_provincia"] == $this->config["data"]["individuo"]["idProvincia"] ? 'selected="selected"' : ''));
                $this->tpl->parse('blkProIndividuo');
            }
        }
        
        $condiciones = $this->config["catalogos"]["condiciones"];
        if (is_array($condiciones)) {
            foreach ($condiciones as $condicion) {
                $this->tpl->setCurrentBlock('blkCondicion');
                $this->tpl->setVariable("valCondicion", $condicion["id_condicion"]);
                $this->tpl->setVariable("opcCondicion", htmlentities($condicion["nombre_condicion"]));
                if (isset($this->config["read"]["id_condicion"]))
                    $this->tpl->setVariable("selCondicion", ($condicion["id_condicion"] == $this->config["read"]["id_condicion"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selCondicion", ($condicion["id_condicion"] == $this->config["data"]["individuo"]["id_condicion"] ? 'selected="selected"' : ''));
                $this->tpl->parse('blkCondicion');
            }
        }
        
        $modalidades = $this->config["catalogos"]["modalidades"];
        if (is_array($modalidades)) {
            foreach ($modalidades as $modalidad) {
                $this->tpl->setCurrentBlock('blkModalidad');
                $this->tpl->setVariable("valModalidad", $modalidad["id_modalidad"]."###".$modalidad["habilita_nombre"]);
                $this->tpl->setVariable("opcModalidad", htmlentities($modalidad["nombre_modalidad"]));
                if (isset($this->config["read"]["id_modalidad"]))
                    $this->tpl->setVariable("selModalidad", ($modalidad["id_modalidad"] == $this->config["read"]["id_modalidad"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selModalidad", ($modalidad["id_modalidad"] == $this->config["data"]["notificacion"]["id_modalidad"] ? 'selected="selected"' : ''));
                $this->tpl->parse('blkModalidad');
            }
        }
        
        $zonas = $this->config["catalogos"]["zonas"];
        if (is_array($zonas)) {
            foreach ($zonas as $zona) {
                $this->tpl->setCurrentBlock('blkZona');
                $this->tpl->setVariable("valZona", $zona["id_zona"]);
                $this->tpl->setVariable("opcZona", htmlentities($zona["nombre_zona"]));
                if (isset($this->config["read"]["id_zona"]))
                    $this->tpl->setVariable("selZona", ($zona["id_zona"] == $this->config["read"]["id_zona"]) ? 'selected="selected"' : '');
                else if ($this->config["preselect"])
                    $this->tpl->setVariable("selZona", ($zona["id_zona"] == $this->config["data"]["notificacion"]["zona"] ? 'selected="selected"' : ''));
                $this->tpl->parse('blkZona');
            }
        }
        
        $this->tpl->setVariable("botonAdd", '<a href="javascript:relacionarCondiciones();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Agregar</a>&nbsp;');
        
        $nombreUsuario = htmlentities(clsCaus::obtenerNombres()) . " " . htmlentities(clsCaus::obtenerApellidos());
        $nombreUsuario = ($nombreUsuario !== "") ? $nombreUsuario : "Hola";
        $this->tpl->setVariable('valNombreRegistra', $nombreUsuario);   
        
        
        
        if ($nuevo) {
            
            $nombreUsuario = htmlentities(clsCaus::obtenerNombres()) . " " . htmlentities(clsCaus::obtenerApellidos());
//            if (isset($this->config["data"]["notificacion"]["nombreRegistra"]))
//                $nombreUsuario = $this->config["data"]["notificacion"]["nombreRegistra"];
            $this->tpl->setVariable('valNombreRegistra', $nombreUsuario);
            //echo "vamos";exit;
//            $fechaFormulario = date("d/m/Y");
//            $this->tpl->setVariable('valFormHora', date('g'));
//            $this->tpl->setVariable('valFormMinutos', date('H'));
//            
//            if (date('a') == "am") {
//                $this->tpl->setVariable('chkFormTipoHoraAM', 'checked="checked"');
//            } else {
//                $this->tpl->setVariable('chkFormTipoHoraPM', 'checked="checked"');
//            }
//            if (isset($this->config["data"]["notificacion"]["fecha_formulario"]))
//                $fechaFormulario = $this->config["data"]["notificacion"]["fecha_formulario"];
//            
//            $this->tpl->setVariable('valFecForm', $fechaFormulario);
            
            
        } else if ($lectura) {
            $this->tpl->setVariable('action', 'M');
            $this->tpl->setVariable('valIdForm', $this->config['read']['id_vac_registro_diario']);
            $this->tpl->setVariable('valReadOnly', 'readonly="readonly" disabled="disabled"');
            $this->tpl->setVariable('valReadOnlyFec', 'readonly="readonly" disabled="disabled"');
            if (clsCaus::validarSeccion(ConfigurationCAUS::VacRegistroDiario, ConfigurationCAUS::Especiales)){
                $this->tpl->setVariable("valReadOnlyFec", '');
                $this->tpl->setVariable("botonIdentificacion", '<a href="javascript:cambiarIdentificacion();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cambiar Identificaci&oacute;n</a>&nbsp;');
            }
            //print_r($this->config['read']);exit;
            
            //Individuo
            
            $this->tpl->setVariable('valIdentificador', $this->config['read']['numero_identificacion']);
            if ($this->config["read"]["tipo_identificacion"]==1){
                $arrayIdentificador = explode("-",$this->config['read']['numero_identificacion']);
                $this->tpl->setVariable('valIdentificador1', $arrayIdentificador[0]);
                $this->tpl->setVariable('valIdentificador2', $arrayIdentificador[1]);
                $this->tpl->setVariable('valIdentificador3', $arrayIdentificador[2]);
            }
            $this->tpl->setVariable('valPrimerNombre', $this->config['read']['primer_nombre']);
            $this->tpl->setVariable('valSegundoNombre', $this->config['read']['segundo_nombre']);
            $this->tpl->setVariable('valPrimerApellido', $this->config['read']['primer_apellido']);
            $this->tpl->setVariable('valSegundoApellido', $this->config['read']['segundo_apellido']);
            if($this->config['read']['fecha_nacimiento'] != '0000-00-00' && $this->config['read']['fecha_nacimiento'] != '')
                $this->tpl->setVariable('valFechaNacimiento', ($this->config['read']['fecha_nacimiento']));
            $this->tpl->setVariable('valEdad', $this->config['read']['per_edad']);
            $tipoEdad = $this->config['read']['per_tipo_edad'];
            switch ($tipoEdad) {
                case '1': $this->tpl->setVariable('selDias', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selMeses', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selAnios', 'selected="selected"'); break;
                case '4': $this->tpl->setVariable('selHoras', 'selected="selected"'); break;
                case '5': $this->tpl->setVariable('selMinutos', 'selected="selected"'); break;
            }
            $sexo = $this->config['read']['sexo'];
            switch ($sexo) {
                case 'M': $this->tpl->setVariable('selSexoM', 'selected="selected"'); break;
                case 'F': $this->tpl->setVariable('selSexoF', 'selected="selected"'); break;
            }
            $this->tpl->setVariable('valNombreResponsable', $this->config['read']['nombre_responsable']);
            $this->tpl->setVariable('valIdPro', $this->config['read']['id_provincia']);
            $this->tpl->setVariable('valIdReg', $this->config['read']['id_region']);
            $this->tpl->setVariable('valIdDis', $this->config['read']['id_distrito']);
            $this->tpl->setVariable('valIdCor', $this->config['read']['per_id_corregimiento']);
            $this->tpl->setVariable('valLugarPoblado', $this->config['read']['per_direccion']);
            $this->tpl->setVariable('valTelefono', $this->config['read']['tel_residencial']);
            $this->tpl->setVariable('valFechaFormulario', $this->config['read']['fecha_formulario']);
            $asegurado = $this->config['read']['asegurado'];
            switch ($asegurado) {
                case '1': $this->tpl->setVariable('selSiAsegurado', 'selected="selected"'); break;
                case '2': $this->tpl->setVariable('selNoAsegurado', 'selected="selected"'); break;
                case '3': $this->tpl->setVariable('selNoSabeAsegurado', 'selected="selected"'); break;
            }
                                   
            //Notificacion
            $this->tpl->setVariable('valNotificacionUnidad', $this->config['read']['nombre_un']);
            $this->tpl->setVariable('valNotificacionIdUn', $this->config['read']['id_un']);
            $this->tpl->setVariable('valTelefonoInvestigador', $this->config['read']['telefono']);
            $this->tpl->setVariable('valFechaNotificacion', ($this->config['read']['fecha_reporte']));
            $this->tpl->setVariable('valNombreInvestigador', $this->config['read']['nombre_reporta']);
            //$this->tpl->setVariable('valNombreRegistra', $this->config['read']['nombre_registra']);
            $this->tpl->setVariable('valnombreModalidadOtro', $this->config['read']['nombre_modalidad_otro']);
            
            //Condiciones relacionadas a la esquema
            $condicionTotal = "";
            if (isset($this->config['read']['condiciones'])) {
                foreach ($this->config['read']['condiciones'] as $condicion) {
                    $condicionTotal .= $condicion['id_condicion']."#-#".htmlentities($condicion['nombre_condicion'])."###";
                }
                $condicionTotal = substr($condicionTotal, 0,strlen($condicionTotal)-3 );
                $this->tpl->setVariable('valCondicionesRelacionados', $condicionTotal);
            }
//            if (isset($this->config['read']['hora_formulario'])){
//                $varHoraForm = $this->config['read']['hora_formulario'];
//                $arrayHoraForm = explode(":",$varHoraForm);
//                $horario = 1; // am: 1 pm:0
//                if ($arrayHoraForm[0]>12){
//                    $horario = 0;
//                    $arrayHoraForm[0] = $arrayHoraForm[0]-12;
//                }
//                $this->tpl->setVariable('valFormHora', $arrayHoraForm[0]);
//                $this->tpl->setVariable('valFormMinutos', $arrayHoraForm[1]);
//                ($horario == 0)? $this->tpl->setVariable('chkFormTipoHoraPM', 'checked="checked"') : $this->tpl->setVariable('chkFormTipoHoraAM', 'checked="checked"');               
//            }
            
        }

        // Muestra si ocurrió un error
        $this->tpl->setVariable("mensajeErrorGeneral", $this->config['Merror']);
        $this->tpl->setVariable("disError", (isset($this->config['Merror']) ? '' : 'none'));

        // Muestra botones GUARDAR y CANCELAR según permisos
        require_once ('libs/caus/clsCaus.php');
        if (!$lectura) {
            if (clsCaus::validarSeccion(ConfigurationCAUS::VacRegistroDiario, ConfigurationCAUS::Agregar))
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarRegistroDiario();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Guardar</a>&nbsp;');
        }
        else {
            if (clsCaus::validarSeccion(ConfigurationCAUS::VacRegistroDiario, ConfigurationCAUS::Modificar))
                $this->tpl->setVariable("botonGuardar", '<a href="javascript:validarRegistroDiario();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Actualizar</a>&nbsp;');
        }
        if (clsCaus::validarSeccion(ConfigurationCAUS::VacRegistroDiario, ConfigurationCAUS::Consultar))
            $this->tpl->setVariable("botonCancelar", '<a href="listadoRegistroDiario.php" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Cancelar</a>');
        if (clsCaus::validarSeccion(ConfigurationCAUS::VacRegistroDiario, ConfigurationCAUS::Agregar))
            $this->tpl->setVariable("botonNuevo", '<a href="javascript:nuevoRegistro();" class="ui-state-default ui-corner-all ui-button" onmouseover="RollOver(this)" onmouseout="RollOut(this)">Nuevo Registro</a>&nbsp;');
        
        if (clsCaus::validarSeccion(ConfigurationCAUS::VacRegistroDiario, ConfigurationCAUS::Especiales)){
            $this->tpl->setVariable("valPerEspecial", '1');
        }
        
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
