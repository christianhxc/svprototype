<?php
require_once ('libs/HTML_Template_Sigma.php');
require_once ('libs/templateManager/templateModules.php');
require_once ('libs/Configuration.php');

class page
{
	protected $tpl;
	protected $config;
	protected $metatags;
	protected $error;

	function __construct($data = null)
	{
		$data["search"] = $_REQUEST["search"];
		$this->pageData = $data;
		$this->saveData();
		$this->initTemplate();
	}

	protected function initTemplate(){
		$this->config['layoutPath'] = Configuration::templatesPath.'layout.tpl.html';
		$this->tpl = new HTML_Template_Sigma();
		$this->tpl->loadTemplateFile($this->config['layoutPath']);
		$this->tpl->setGlobalVariable('urlprefix', Configuration::getAbsolutePath());
                $this->tpl->setGlobalVariable('urlprefixViejo', Configuration::urlprefixViejo);
	}

	public function parseHTMLHead()
	{
		$this->tpl->addBlockFile('HTMLHEAD','htmlHeadBlock',Configuration::templatesPath.'htmlhead.tpl.html');
		
		if (is_array($this->pageData['cssfiles'])){
			foreach ($this->pageData['cssfiles'] as $css){
				$this->tpl->setCurrentBlock('cssHead');
				$this->tpl->setVariable('cssfile',$css);
				$this->tpl->parse('cssHead');
			}
		}
		
		if (is_array($this->pageData['jsfiles'])){
			foreach ($this->pageData['jsfiles'] as $css){
				$this->tpl->setCurrentBlock('jsHead');
				$this->tpl->setVariable('jsfile',$css);
				$this->tpl->parse('jsHead');
			}
		}
		
		$this->tpl->touchBlock('htmlHeadBlock');
		$this->tpl->setVariable('viewpagetitle',$this->getMetas('title')!=''?$this->getMetas('title'):Configuration::DefaultTitleAdmin);
		$this->tpl->setVariable('viewmetadescription',$this->getMetas('description'));
		$this->tpl->setVariable('viewmetakeywords',$this->getMetas('keywords'));
		$this->tpl->setVariable('viewmetarobots',$this->getMetas('robot'));
		$this->tpl->setVariable('externalcss',$this->getPageDetail('external_css'));
	}
	public function parseHeader()
	{
		$this->tpl->addBlockFile('HEADER','headerBlock',Configuration::templatesPath.'menu.tpl.html');
		$this->tpl->setVariable('nombre', $this->pageData["search"]["nombre"]);
		if (is_array($this->pageData["search"]["nombre"]))
			$this->tpl->parse('headerBlock');
		else
			$this->tpl->touchBlock('headerBlock');

                // Esconder o mostrar las opciones del menú principal
                // según los permisos del usuario
                require_once ('libs/caus/clsCaus.php');
                $this->tpl->setVariable('menuRutinaria','none');
                $this->tpl->setVariable('menuMortalidad','none');
                $this->tpl->setVariable('menuVigBrotes','none');
                $this->tpl->setVariable('menuCentinela','none');
                $this->tpl->setVariable('menuEspeciales','none');
                $this->tpl->setVariable('menuCatalogos','none');
                $this->tpl->setVariable('menuAyuda','none');
                $this->tpl->setVariable('menuSalir','none');
                $this->tpl->setVariable('usuarioConectado','');
                
                // menuRutinaria
                $this->tpl->setVariable('menuEno','none');
                $this->tpl->setVariable('menuNotic','none');
                $this->tpl->setVariable('menuInvCaso','none');
                $this->tpl->setVariable('menuRae','none');
                $this->tpl->setVariable('menuMonitoreo','none');
                
                // menuMortalidad
                $this->tpl->setVariable('menuVigmor','none');
                $this->tpl->setVariable('menuMaterna','none');
                $this->tpl->setVariable('menuPerinatal','none');
                $this->tpl->setVariable('menuNinos','none');

                // menuVigBrotes
                $this->tpl->setVariable('menuBrotes','none');
                
                // menuCentinela
                $this->tpl->setVariable('menuFlu','none');
                $this->tpl->setVariable('menuFluLab','none');
                $this->tpl->setVariable('menuVicIts','none');
                
                // menuEspeciales
                $this->tpl->setVariable('menuVih','none');
                $this->tpl->setVariable('menuMat','none');
                $this->tpl->setVariable('menuTB','none');
                $this->tpl->setVariable('menuSarampion','none');
                $this->tpl->setVariable('menuSinRubeola','none');
                $this->tpl->setVariable('menuParalisis','none');
                $this->tpl->setVariable('menuTosferina','none');
                $this->tpl->setVariable('menuPrimates','none');
                $this->tpl->setVariable('menuEVASIS','none');
                $this->tpl->setVariable('menuSifilis','none');
                $this->tpl->setVariable('menuMalformacion','none');
                $this->tpl->setVariable('menuNoTrasmisibles','none');
                $this->tpl->setVariable('menuIntoxiaciones','none');
                
                // menuCatalogos
                $this->tpl->setVariable('menuCatAntecedenteVacunal','none');
                $this->tpl->setVariable('menuCatEnfermedadCronica','none');
                $this->tpl->setVariable('menuCatCargo','none');
                $this->tpl->setVariable('menuCatEventos','none');
                $this->tpl->setVariable('menuCatExamenes','none');
                $this->tpl->setVariable('menuCatGrupoEventos','none');
                $this->tpl->setVariable('menuCatUnidad','none');
                $this->tpl->setVariable('menuCatOcupacion','none');
                $this->tpl->setVariable('menuCatMedicos','none');
                $this->tpl->setVariable('menuCatServicios','none');
                $this->tpl->setVariable('menuCatServiciosRae','none');
                $this->tpl->setVariable('menuCatSintomas','none');
                $this->tpl->setVariable('menuCatTipoIdentidad','none');
                $this->tpl->setVariable('menuCatTipoMuestras','none');
                                
                // menuRutinaria - Submenu Eno
                $this->tpl->setVariable('menuEnoFormulario','none');
                $this->tpl->setVariable('menuEnoCargar','none');
                $this->tpl->setVariable('menuEnoReportes','none');
                
                // menuRutinaria - Submenu menuNotic
                $this->tpl->setVariable('menuNoticFormulario','none');
                $this->tpl->setVariable('menuNoticRepIndividual','none');
                $this->tpl->setVariable('menuNoticRepConsolidado','none');
                
                // menuRutinaria - Submenu menuInvCaso
                $this->tpl->setVariable('menuInvCasoFormulario','none');
                $this->tpl->setVariable('menuInvCasoRepIndividual','none');
                $this->tpl->setVariable('menuInvCasoRepConsolidado','none');
                
                // menuRutinaria - Submenu RAE
                $this->tpl->setVariable('menuRaeFormulario','none');
                $this->tpl->setVariable('menuRaeRepIndividual','none');
                $this->tpl->setVariable('menuRaeRepServicio','none');
                $this->tpl->setVariable('menuRaeRepMorbilidad','none');
                
                // menuMortalidad - Submenu Vigmor
                $this->tpl->setVariable('menuVigmorFormulario','none');
                $this->tpl->setVariable('menuVigmorRepIndividual','none');
                $this->tpl->setVariable('menuVigmorRepConsolidado','none');   
                                
                // menuVigBrotes - Submenu Brotes Espacio reservado
                //$this->tpl->setVariable('menuBrotesFormulario','none');
                
                // menuCentinela - Submenu FluReg
                $this->tpl->setVariable('menuFluRegFormulario','none');
                $this->tpl->setVariable('menuFluExportacionVariables','none');
                
                // menuCentinela - Submenu FluLab
                $this->tpl->setVariable('menuFluLabFormulario','none');
                $this->tpl->setVariable('menuFluLabRepIndividual','none');
                $this->tpl->setVariable('menuFluLabRepConsolidado','none');
                
                // menuEspeciales - Submenu Malformacion
                $this->tpl->setVariable('menuMalFormulario','none');
                $this->tpl->setVariable('menuMalNacFormulario','none');
                $this->tpl->setVariable('menuMalReportes','none');
                
                // menuEspeciales - Submenu VIH/SIDA
                $this->tpl->setVariable('menuVihFormulario','none');
                $this->tpl->setVariable('menuVihRepExcel','none');
                $this->tpl->setVariable('menuVihRepConsolidado','none');
                $this->tpl->setVariable('menuVihRepRegiones','none');
                $this->tpl->setVariable('menuVihRepFactorRiesgo','none');
                $this->tpl->setVariable('menuVihRepEnfermedad','none');
                $this->tpl->setVariable('menuVihSincronizarSilab','none');
                $this->tpl->setVariable('menuVihSincronizarEpiInfo','none');
                
                // menuEspeciales - Submenu VICITS
                $this->tpl->setVariable('menuVicItsFormulario','none');
                $this->tpl->setVariable('menuVicItsFormLaboratorio','none');
                $this->tpl->setVariable('menuVicItsRepExcel','none');
                
                // menuEspeciales - Submenu VICITS
                $this->tpl->setVariable('menuMatFormulario','none');
                
                // menuEspeciales - Submenu TB
                $this->tpl->setVariable('menuTBFormulario','none');
                $this->tpl->setVariable('menuTBPagInicio','none');
                $this->tpl->setVariable('menuTBReportes','none');
                                
                if(clsCaus::validarSession())
                {
                    // Mostrar las opciones del menú principal que puede accederse
                    $this->tpl->setVariable('menuSalir','');
                    $this->tpl->setVariable('menuAyuda','');
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::sisvig);
                    
                    //print_r($secciones);exit;

                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::vigRutinaria:
                                    $this->tpl->setVariable('menuRutinaria','');
                                    break;
                                case ConfigurationCAUS::vigMortalidad:
                                    $this->tpl->setVariable('menuMortalidad','');
                                    break;
                                case ConfigurationCAUS::vigBrotes:
                                    $this->tpl->setVariable('menuVigBrotes','');
                                    break;
                                case ConfigurationCAUS::vigCentinela:
                                    $this->tpl->setVariable('menuCentinela','');
                                    break;
                                case ConfigurationCAUS::casosEspeciales:
                                    $this->tpl->setVariable('menuEspeciales','');
                                    break;
                                case ConfigurationCAUS::catalogos:
                                    $this->tpl->setVariable('menuCatalogos','');
                                    break;
                                case ConfigurationCAUS::ayuda: //falta
                                    $this->tpl->setVariable('menuAyuda','');
                                    break;
                            }
                        }
                    }

                    // Mostrar las opciones del menuRutinaria que puede acceder
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::vigRutinaria);

                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::eno:
                                    $this->tpl->setVariable('menuEno','');
                                    break;
                                case ConfigurationCAUS::notic:
                                    $this->tpl->setVariable('menuNotic','');
                                    break;
                                case ConfigurationCAUS::invCasos:
                                    $this->tpl->setVariable('menuInvCaso','');
                                    break;
                                case ConfigurationCAUS::rae:
                                    $this->tpl->setVariable('menuRae','');
                                    break;
                                case ConfigurationCAUS::monitoreo:
                                    $this->tpl->setVariable('menuMonitoreo','');
                                    break;
                            }
                        }
                    }
                    
                    // Mostrar las opciones del menuMortalidad que puede acceder
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::vigMortalidad);

                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::vigmor:
                                    $this->tpl->setVariable('menuVigmor','');
                                    break;
                                case ConfigurationCAUS::materna:
                                    $this->tpl->setVariable('menuMaterna','');
                                    break;
                                case ConfigurationCAUS::perinatal:
                                    $this->tpl->setVariable('menuPerinatal','');
                                    break;
                                case ConfigurationCAUS::ninos:
                                    $this->tpl->setVariable('menuNinos','');
                                    break;
                            }
                        }
                    }
                    
                    // Mostrar las opciones del menuMortalidad que puede acceder
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::vigBrotes);

                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::brotes:
                                    $this->tpl->setVariable('menuBrotes','');
                                    break;
                            }
                        }
                    }
                    
                    // Mostrar las opciones del menu Centinela que puede acceder
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::vigCentinela);
                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::influenza:
                                    $this->tpl->setVariable('menuFlu','');
                                    break;
                                case ConfigurationCAUS::vicIts:
                                    $this->tpl->setVariable('menuVicIts','');
                                    break;
                            }
                        }
                    }
                    
                    // Mostrar las opciones del menuEspeciales que puede acceder
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::casosEspeciales);
                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::malformacion:
                                    $this->tpl->setVariable('menuMalformacion','');
                                    break;
                                case ConfigurationCAUS::vih:
                                    $this->tpl->setVariable('menuVih','');
                                    break;
                                case ConfigurationCAUS::mat:
                                    $this->tpl->setVariable('menuMat','');
                                    break;
                                case ConfigurationCAUS::TB:
                                    $this->tpl->setVariable('menuTB','');
                                    break;
                            }
                        }
                    }

                    // Mostrar las opciones del menuRutinaria - Submenu eno que puede acceder
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::eno);

                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::enoFormulario:
                                    $this->tpl->setVariable('menuEnoFormulario','');
                                    break;
                                case ConfigurationCAUS::enoCargar:
                                    $this->tpl->setVariable('menuEnoCargar','');
                                    break;
                                case ConfigurationCAUS::enoreporte:
                                    $this->tpl->setVariable('menuEnoReportes','');
                                    break;
                            }
                        }
                    }
                    
                    // Mostrar las opciones del menuRutinaria - Submenu menuNotic que puede acceder
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::notic);

                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::noticFormulario:
                                    $this->tpl->setVariable('menuNoticFormulario','');
                                    break;
                                case ConfigurationCAUS::noticReporteIndividual:
                                    $this->tpl->setVariable('menuNoticRepIndividual','');
                                    break;
                                case ConfigurationCAUS::noticReporteConsolidado:
                                    $this->tpl->setVariable('menuNoticRepConsolidado','');
                                    break;
                            }
                        }
                    }
                    
                    
                     // Mostrar las opciones del menuRutinaria - Submenu Rae que puede acceder
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::rae);

                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::raeFormulario:
                                    $this->tpl->setVariable('menuRaeFormulario','');
                                    break;
                                case ConfigurationCAUS::raeReporteIndividual:
                                    $this->tpl->setVariable('menuRaeRepIndividual','');
                                    break;
                                case ConfigurationCAUS::raeReporteServicio:
                                    $this->tpl->setVariable('menuRaeRepServicio','');
                                    break;
                                case ConfigurationCAUS::raeReporteMorbilidad:
                                    $this->tpl->setVariable('menuRaeRepMorbilidad','');
                                    break;
                            }
                        }
                    }
                    
                    // Mostrar las opciones del menuMortalidad - Submenu Vigmor que puede acceder
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::vigmor);

                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::vigmorFormulario:
                                    $this->tpl->setVariable('menuVigmorFormulario','');
                                    break;
                                case ConfigurationCAUS::vigmorRepIndividual:
                                    $this->tpl->setVariable('menuVigmorRepIndividual','');
                                    break;
                                case ConfigurationCAUS::vigmorReporte:
                                    $this->tpl->setVariable('menuVigmorRepConsolidado','');
                                    break;
                            }
                        }
                    } 
                                                
                    // Mostrar las opciones del menuNotic - submenu invCaso que puede acceder
                    
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::invCasos);
                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::casosFormulario:
                                    $this->tpl->setVariable('menuInvCasoFormulario','');
                                    break;
                                case ConfigurationCAUS::casosReporteIndividual:
                                    $this->tpl->setVariable('menuInvCasoRepIndividual','');
                                    break;
                                case ConfigurationCAUS::casosReporteConsolidado:
                                    $this->tpl->setVariable('menuInvCasoRepConsolidado','');
                                    break;
                            }
                        }
                    }   
                                    
                    // Mostrar las opciones del Submenu Malformacion que puede acceder
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::malformacion);

                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::malformacionFormulario:
                                    $this->tpl->setVariable('menuMalFormulario','');
                                    break;
                                case ConfigurationCAUS::malformacionNacidos:
                                    $this->tpl->setVariable('menuMalNacFormulario','');
                                    break;
                                case ConfigurationCAUS::malformacionReportes:
                                    $this->tpl->setVariable('menuMalReportes','');
                                    break;
                            }
                        }
                    }
                    
                    // Mostrar las opciones del menuInfluenza - Submenu eno que puede acceder
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::influenza);

                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::fluFormulario:
                                    $this->tpl->setVariable('menuFluRegFormulario','');
                                    break;
                                case ConfigurationCAUS::fluExportacionVariables:
                                    $this->tpl->setVariable('menuFluExportacionVariables','');
                                    break;
                            }
                        }
                    }
                    
                    // Mostrar las opciones del menuVih - Submenu eno que puede acceder
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::vih);
                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::vihFormulario:
                                    $this->tpl->setVariable('menuVihFormulario','');
                                    break;
                                case ConfigurationCAUS::vihExportarExcel:
                                    $this->tpl->setVariable('menuVihRepExcel','');
                                    break;
                                case ConfigurationCAUS::vihRepConsolidado:
                                    $this->tpl->setVariable('menuVihRepConsolidado','');
                                    break;
                                case ConfigurationCAUS::vihRepRegiones:
                                    $this->tpl->setVariable('menuVihRepRegiones','');
                                    break;
                                case ConfigurationCAUS::vihRepFactorRiesgo:
                                    $this->tpl->setVariable('menuVihRepFactorRiesgo','');
                                    break;
                                case ConfigurationCAUS::vihRepEnfermedad:
                                    $this->tpl->setVariable('menuVihRepEnfermedad','');
                                    break;
                                case ConfigurationCAUS::vihSincronizarSilab:
                                    $this->tpl->setVariable('menuVihSincronizarSilab','');
                                    break;
                                case ConfigurationCAUS::vihSincronizarEpiInfo:
                                    $this->tpl->setVariable('menuVihSincronizarEpiInfo','');
                                    break;
                            }
                        }
                    }
                    
                    // Mostrar las opciones del menuVicIts - Submenu eno que puede acceder
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::vicIts);
                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::vicItsFormulario:
                                    $this->tpl->setVariable('menuVicItsFormulario','');
                                    break;
                                case ConfigurationCAUS::vicItsFormLaboratorio:
                                    $this->tpl->setVariable('menuVicItsFormLaboratorio','');
                                    break;
                                case ConfigurationCAUS::vicItsExportarExcel:
                                    $this->tpl->setVariable('menuVicItsRepExcel','');
                                    break;
                            }
                        }
                    }

                    // Mostrar las opciones del TB - Submenu TB que puede acceder
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::TB);
                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::TBFormulario:
                                    $this->tpl->setVariable('menuTBFormulario','');
                                    break;
                                case ConfigurationCAUS::TBPagIni:
                                    $this->tpl->setVariable('menuTBPagInicio','');
                                    break;
                                case ConfigurationCAUS::TBReportes:
                                    $this->tpl->setVariable('menuTBReportes','');
                                    break;
                            }
                        }
                    }
                    
                    // Mostrar las opciones del menuMat - Submenu eno que puede acceder
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::mat);
                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::matFormulario:
                                    $this->tpl->setVariable('menuMatFormulario','');
                                    break;
                            }
                        }
                    }
                    
                    // Mostrar las opciones del menuCatalogos
                    
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::catalogos);
                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::catEnfermedadCronica:
                                    $this->tpl->setVariable('menuCatEnfermedadCronica','');
                                    break;
                                case ConfigurationCAUS::catAntecedenteVacunal:
                                    $this->tpl->setVariable('menuCatAntecedenteVacunal','');
                                    break;
                                case ConfigurationCAUS::catCargo:
                                    $this->tpl->setVariable('menuCatCargo','');
                                    break;
                                case ConfigurationCAUS::catEvento:
                                    $this->tpl->setVariable('menuCatEventos','');
                                    break;
                                case ConfigurationCAUS::catExamenes:
                                    $this->tpl->setVariable('menuCatExamenes','');
                                    break;
                                case ConfigurationCAUS::catGrupoEvento:
                                    $this->tpl->setVariable('menuCatGrupoEventos','');
                                    break;
                                case ConfigurationCAUS::catUnidadNotificadora:
                                    $this->tpl->setVariable('menuCatUnidad','');
                                    break;
                                case ConfigurationCAUS::catOcupacion:
                                    $this->tpl->setVariable('menuCatOcupacion','');
                                    break;
                                case ConfigurationCAUS::catPersonalMedico:
                                    $this->tpl->setVariable('menuCatMedicos','');
                                    break;
                                case ConfigurationCAUS::catServicios:
                                    $this->tpl->setVariable('menuCatServicios','');
                                    break;
                                case ConfigurationCAUS::catServiciosRae:
                                    $this->tpl->setVariable('menuCatServicios','');
                                    break;
                                case ConfigurationCAUS::catSintomas:
                                    $this->tpl->setVariable('menuCatSintomas','');
                                    break;
                                case ConfigurationCAUS::catTipoIdentidad:
                                    $this->tpl->setVariable('menuCatTipoIdentidad','');
                                    break;
                                case ConfigurationCAUS::catTipoMuestras:
                                    $this->tpl->setVariable('menuCatTipoMuestras','');
                                    break;
                            }
                        }
                    }   
                
                    // Mostrar el usuario que está conectado
                    $nombres = htmlentities(clsCaus::obtenerNombres());
                    $apellidos = htmlentities(clsCaus::obtenerApellidos());

                    $antes = '<table width="100%" align="right"><tr><td align="right"><strong>USUARIO: </strong>';
                    $this->tpl->setVariable('usuarioConectado',$antes.$nombres.' '.$apellidos.'&nbsp;</td></tr></table>');

                }
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'content.tpl.html');
		$this->tpl->touchBlock('contentBlock');

	}
        
	public function parseFooter()
	{
		$this->tpl->addBlockFile('FOOTER','footerBlock', Configuration::templatesPath.'footer.tpl.html');
		$this->tpl->touchBlock('footerBlock');
	}

	public function displayPage()
	{
		$this->parseHTMLHead();
		$this->parseHeader();
		$this->parseContent();
		$this->parseFooter();

		if(isset($this->error)&& ($this->error['code']!='')){
			switch ($this->error['code']){
				case -1:
					header("HTTP/1.0 404 Not Found");
					return -1;
					break;
				default:
					break;
			}
		}

		$this->tpl->show();
	}

	protected function saveData(){
		if(is_array($this->pageData)&&count($this->pageData)>0){
			if(is_array($this->pageData['metaTags'])){
				$this->metatags = $this->pageData['metaTags'];
			}
			else{
				$this->metatags = array();
			}
		}
	}

	/**
	 * Get the metatag content for the specified metatagtype
	 *
	 * @param $metatagtype
	 * @return string
	 */
	protected function getMetas($metatagtype)
	{
		if(is_array($this->metatags)&& is_array($this->metatags)){
			foreach ($this->metatags as $item)
			{
				if ($item['metatagtype'] == $metatagtype)
				{
					return $item['content'];
				}
			}
		}
		return "";
	}
	
	/**
	 * Get the content of the module with the specified label
	 * @param $label
	 * @return array
	 */
	protected function getPageDetail($label){
		if(is_array($this->pageDetail)&& is_array($this->pageDetail)){
			foreach ($this->pageDetail as $key => $value)
			{
				if ($key == $label)
				{
					return $value;
				}
			}
		}
		return array();
	}
}
?>