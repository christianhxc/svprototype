<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

class HomePage extends page
{
	public $config;

	function __construct($data = null)
	{
                $this->config = $data;
		parent::__construct($data);
	}

	public function parseContent()
	{
		$this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'/bienvenida.tpl.html');

                // Muestra mensajes de error correspondientes
                $this->tpl->setVariable('mensajeError','none');
                $this->tpl->setVariable('mostrarError','none');
                                
                require_once ('libs/caus/clsCaus.php');
                
                // menuRutinaria
                $this->tpl->setVariable('iniMenuEno','block');
                $this->tpl->setVariable('iniMenuNotic','block');
                $this->tpl->setVariable('iniMenuInvCaso','block');
                $this->tpl->setVariable('iniMenuRae','block');
                $this->tpl->setVariable('iniMenuMonitoreo','block');
                
                // menuMortalidad
                $this->tpl->setVariable('iniMenuVigmor','block');
                $this->tpl->setVariable('iniMenuMaterna','block');
                $this->tpl->setVariable('iniMenuPerinatal','block');
                $this->tpl->setVariable('iniMenuNinos','block');

                // menuVigBrotes
                $this->tpl->setVariable('iniMenuBrotes','block');
                
                // menuCentinela
                $this->tpl->setVariable('iniMenuFluReg','block');
                $this->tpl->setVariable('iniMenuFluLab','block');
                $this->tpl->setVariable('iniMenuVicIts','block');
                
                // menuEspeciales
                $this->tpl->setVariable('iniMenuVIH','block');
                $this->tpl->setVariable('iniMenuMat','block');
                $this->tpl->setVariable('iniMenuSarampion','block');
                $this->tpl->setVariable('iniMenuSinRubeola','block');
                $this->tpl->setVariable('iniMenuParalisis','block');
                $this->tpl->setVariable('iniMenuTosferina','block');
                $this->tpl->setVariable('iniMenuPrimates','block');
                $this->tpl->setVariable('iniMenuEVASIS','block');
                $this->tpl->setVariable('iniMenuSifilis','block');
                $this->tpl->setVariable('iniMenuMalformacion','block');
                $this->tpl->setVariable('iniMenuNoTrasmisibles','block');
                $this->tpl->setVariable('iniMenuIntoxiaciones','block');
                $this->tpl->setVariable('iniMenuTB','block');
                
                //menuProgramas
                $this->tpl->setVariable('iniMenuVIH','block');
                $this->tpl->setVariable('iniMenuMat','block');
                $this->tpl->setVariable('iniMenuVacunas','block');
                
                // menuRutinaria - Submenu Eno
                $this->tpl->setVariable('iniMenuEnoFormulario','none');
                $this->tpl->setVariable('iniMenuEnoCargar','none');
                $this->tpl->setVariable('iniMenuEnoReportes','none');
                
                // menuRutinaria - Submenu menuNotic
                $this->tpl->setVariable('iniMenuNoticFormulario','none');
                $this->tpl->setVariable('iniMenuNoticRepIndividual','none');
                $this->tpl->setVariable('iniMenuNoticRepConsolidado','none');
                
                // menuRutinaria - Submenu menuInvCaso
                $this->tpl->setVariable('iniMenuInvCasoFormulario','none');
                $this->tpl->setVariable('iniMenuInvCasoRepIndividual','none');
                $this->tpl->setVariable('iniMenuInvCasoRepConsolidado','none');
                
                // menuRutinaria - Submenu RAE
                $this->tpl->setVariable('iniMenuRaeFormulario','none');
                $this->tpl->setVariable('iniMenuRaeRepIndividual','none');
                $this->tpl->setVariable('iniMenuRaeRepServicio','none');
                $this->tpl->setVariable('iniMenuRaeRepMorbilidad','none');
                
                // menuMortalidad - Submenu Vigmor
                $this->tpl->setVariable('iniMenuVigmorFormulario','none');
                $this->tpl->setVariable('iniMenuVigmorRepIndividual','none');
                $this->tpl->setVariable('iniMenuVigmorRepConsolidado','none');   
                                
                // menuVigBrotes - Submenu Brotes Espacio reservado
                //$this->tpl->setVariable('iniMenuBrotesFormulario','none');
                
                // menuCentinela - Submenu FluReg
                $this->tpl->setVariable('iniMenuFluRegFormulario','none');
                $this->tpl->setVariable('iniMenuFluExportacionVariables','none');
                
                // menuCentinela - Submenu FluLab
                $this->tpl->setVariable('iniMenuFluLabFormulario','none');
                $this->tpl->setVariable('iniMenuFluLabRepIndividual','none');
                $this->tpl->setVariable('iniMenuFluLabRepConsolidado','none');
                
                // menuEspeciales - Submenu Malformacion
                $this->tpl->setVariable('iniMenuMalFormulario','none');
                $this->tpl->setVariable('iniMenuMalNacFormulario','none');
                $this->tpl->setVariable('iniMenuMalReportes','none');
                
                // menuEspeciales - Submenu VIH/SIDA
                $this->tpl->setVariable('iniVihFormulario','none');
                $this->tpl->setVariable('iniVihRepExcel','none');
                $this->tpl->setVariable('iniVihRepConsolidado','none');
                $this->tpl->setVariable('iniVihRepRegiones','none');
                $this->tpl->setVariable('iniVihRepFactorRiesgo','none');
                $this->tpl->setVariable('iniVihRepEnfermedad','none');
                $this->tpl->setVariable('iniVihSincronizarSilab','none');
                $this->tpl->setVariable('iniVihSincronizarEpiInfo','none');
                
                // menuEspeciales - Submenu VIH/SIDA
                $this->tpl->setVariable('iniVicItsFormulario','none');
                $this->tpl->setVariable('iniVicItsFormLaboratorio','none');
                $this->tpl->setVariable('iniVicItsRepExcel','none');
                
                // menuEspeciales - Submenu MAT
                $this->tpl->setVariable('iniMatFormulario','none');

                // menuCentinela - Submenu TB
                $this->tpl->setVariable('iniMenuTBPagInicio','none');
                $this->tpl->setVariable('iniMenuTBRegFormulario','none');
                $this->tpl->setVariable('iniMenuTBReportes','none');
                
                if(clsCaus::validarSession())
                {
                    // Mostrar las opciones del menú principal que puede accederse
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::sisvig);
                    
                    //print_r($secciones);exit;

                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::vigRutinaria:
                                    $this->tpl->setVariable('iniMenuRutinaria','');
                                    break;
                                case ConfigurationCAUS::vigMortalidad:
                                    $this->tpl->setVariable('iniMenuMortalidad','');
                                    break;
                                case ConfigurationCAUS::vigBrotes:
                                    $this->tpl->setVariable('iniMenuVigBrotes','');
                                    break;
                                case ConfigurationCAUS::vigCentinela:
                                    $this->tpl->setVariable('iniMenuCentinela','');
                                    break;
                                case ConfigurationCAUS::casosEspeciales:
                                    $this->tpl->setVariable('iniMenuEspeciales','');
                                case ConfigurationCAUS::catalogos:
                                    $this->tpl->setVariable('iniMenuCatalogos','');
                                    break;
                                case ConfigurationCAUS::ayuda: //falta
                                    $this->tpl->setVariable('iniMenuAyuda','');
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
                                    $this->tpl->setVariable('iniMenuEno','');
                                    break;
                                case ConfigurationCAUS::notic:
                                    $this->tpl->setVariable('iniMenuNotic','');
                                    break;
                                case ConfigurationCAUS::invCasos:
                                    $this->tpl->setVariable('iniMenuInvCaso','');
                                    break;
                                case ConfigurationCAUS::rae:
                                    $this->tpl->setVariable('iniMenuRae','');
                                    break;
                                case ConfigurationCAUS::monitoreo:
                                    $this->tpl->setVariable('iniMenuMonitoreo','');
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
                                    $this->tpl->setVariable('iniMenuVigmor','');
                                    break;
                                case ConfigurationCAUS::materna:
                                    $this->tpl->setVariable('iniMenuMaterna','');
                                    break;
                                case ConfigurationCAUS::perinatal:
                                    $this->tpl->setVariable('iniMenuPerinatal','');
                                    break;
                                case ConfigurationCAUS::ninos:
                                    $this->tpl->setVariable('iniMenuNinos','');
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
                                    $this->tpl->setVariable('iniMenuBrotes','');
                                    break;
                            }
                        }
                    }
                    
                    // Mostrar las opciones de la vigilancia centinela que puede acceder
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::vigCentinela);

                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::influenza:
                                    $this->tpl->setVariable('iniMenuFluReg','');
                                    break;
                                case ConfigurationCAUS::vicIts:
                                    $this->tpl->setVariable('iniMenuVicIts','');
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
                                    $this->tpl->setVariable('iniMenuMalformacion','');
                                    break;
                                case ConfigurationCAUS::vih:
                                    $this->tpl->setVariable('iniMenuVIH','');
                                    break;
                                case ConfigurationCAUS::mat:
                                    $this->tpl->setVariable('iniMenuMat','');
                                    break;
                                case ConfigurationCAUS::TB:
                                    $this->tpl->setVariable('iniMenuTB','');
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
                                    $this->tpl->setVariable('iniMenuEnoFormulario','');
                                    break;
                                case ConfigurationCAUS::enoCargar:
                                    $this->tpl->setVariable('iniMenuEnoCargar','');
                                    break;
                                case ConfigurationCAUS::enoreporte:
                                    $this->tpl->setVariable('iniMenuEnoReportes','');
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
                                    $this->tpl->setVariable('iniMenuNoticFormulario','');
                                    break;
                                case ConfigurationCAUS::noticReporteIndividual:
                                    $this->tpl->setVariable('iniMenuNoticRepIndividual','');
                                    break;
                                case ConfigurationCAUS::noticReporteConsolidado:
                                    $this->tpl->setVariable('iniMenuNoticRepConsolidado','');
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
                                    $this->tpl->setVariable('iniMenuRaeFormulario','');
                                    break;
                                case ConfigurationCAUS::raeReporteIndividual:
                                    $this->tpl->setVariable('iniMenuRaeRepIndividual','');
                                    break;
                                case ConfigurationCAUS::raeReporteServicio:
                                    $this->tpl->setVariable('iniMenuRaeRepServicio','');
                                    break;
                                case ConfigurationCAUS::raeReporteMorbilidad:
                                    $this->tpl->setVariable('iniMenuRaeRepMorbilidad','');
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
                                    $this->tpl->setVariable('iniMenuVigmorFormulario','');
                                    break;
                                case ConfigurationCAUS::vigmorRepIndividual:
                                    $this->tpl->setVariable('iniMenuVigmorRepIndividual','');
                                    break;
                                case ConfigurationCAUS::vigmorReporte:
                                    $this->tpl->setVariable('iniMenuVigmorRepConsolidado','');
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
                                    $this->tpl->setVariable('iniMenuInvCasoFormulario','');
                                    break;
                                case ConfigurationCAUS::casosReporteIndividual:
                                    $this->tpl->setVariable('iniMenuInvCasoRepIndividual','');
                                    break;
                                case ConfigurationCAUS::casosReporteConsolidado:
                                    $this->tpl->setVariable('iniMenuInvCasoRepConsolidado','');
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
                                    $this->tpl->setVariable('iniMenuMalFormulario','');
                                    break;
                                case ConfigurationCAUS::malformacionNacidos:
                                    $this->tpl->setVariable('iniMenuMalNacFormulario','');
                                    break;
                                case ConfigurationCAUS::malformacionReportes:
                                    $this->tpl->setVariable('iniMenuMalReportes','');
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
                                    $this->tpl->setVariable('iniVihFormulario','');
                                    break;
                                case ConfigurationCAUS::vihExportarExcel:
                                    $this->tpl->setVariable('iniVihRepExcel','');
                                    break;
                                case ConfigurationCAUS::vihRepConsolidado:
                                    $this->tpl->setVariable('iniVihRepConsolidado','');
                                    break;
                                case ConfigurationCAUS::vihRepRegiones:
                                    $this->tpl->setVariable('iniVihRepRegiones','');
                                    break;
                                case ConfigurationCAUS::vihRepFactorRiesgo:
                                    $this->tpl->setVariable('iniVihRepFactorRiesgo','');
                                    break;
                                case ConfigurationCAUS::vihRepEnfermedad:
                                    $this->tpl->setVariable('iniVihRepEnfermedad','');
                                    break;
                                case ConfigurationCAUS::vihSincronizarSilab:
                                    $this->tpl->setVariable('iniVihSincronizarSilab','');
                                    break;
                                case ConfigurationCAUS::vihSincronizarEpiInfo:
                                    $this->tpl->setVariable('iniVihSincronizarEpiInfo','');
                                    break;
                            }
                        }
                    }
                    
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::vicIts);

                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::vicItsFormulario:
                                    $this->tpl->setVariable('iniVicItsFormulario','');
                                    break;
                                case ConfigurationCAUS::vicItsFormLaboratorio:
                                    $this->tpl->setVariable('iniVicItsFormLaboratorio','');
                                    break;
                                case ConfigurationCAUS::vicItsExportarExcel:
                                    $this->tpl->setVariable('iniVicItsRepExcel','');
                                    break;
                            }
                        }
                    }
                    
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::mat);

                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::matFormulario:
                                    $this->tpl->setVariable('iniMatFormulario','');
                                    break;
                            }
                        }
                    }
                    
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::influenza);

                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::fluFormulario:
                                    $this->tpl->setVariable('iniMenuFluRegFormulario','');
                                    break;
                                case ConfigurationCAUS::fluExportacionVariables:
                                    $this->tpl->setVariable('iniMenuFluExportacionVariables','');
                                    break;
                            }
                        }
                    }

                    // Mostrar las opciones del Submenu Malformacion que puede acceder
                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::Vacunas);
//echo "<pre>".var_dump($_SESSION["user"]["secciones"][74]["secciones"])."</pre>"; exit;
                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::VacEsquema:
                                    $this->tpl->setVariable('iniVacFormulario','');
                                    break;
                                case ConfigurationCAUS::VacFormulario:
                                    $this->tpl->setVariable('iniVacFormulario','');
                                    break;
                                case ConfigurationCAUS::VacRegistroDiario:
                                    $this->tpl->setVariable('iniVacRegistroDiario','');
                                    break;
                                case ConfigurationCAUS::VacDenominadores:
                                    $this->tpl->setVariable('iniVacDenominador','');
                                    break;
                                case ConfigurationCAUS::VacCargarDeno:
                                    $this->tpl->setVariable('iniVacVac_CargarDeno','');
                                    break;
                                case ConfigurationCAUS::VacReportes:
                                    $this->tpl->setVariable('iniVacReportes','');
                                    break;
                                case ConfigurationCAUS::VacLdbi:
                                    $this->tpl->setVariable('iniVacLdbi','');
                                    break;
                            }
                        }
                    }

                    $secciones = clsCaus::obtenerSecciones(ConfigurationCAUS::TB);

                    if(count($secciones)){
                        foreach($secciones as $c=>$seccion)
                        {
                            switch($c)
                            {
                                case ConfigurationCAUS::TBFormulario:
                                    $this->tpl->setVariable('iniMenuTBRegFormulario','');
                                    break;
                                case ConfigurationCAUS::TBPagIni:
                                    $this->tpl->setVariable('iniMenuTBPagInicio','');
                                    break;
                                case ConfigurationCAUS::TBReportes:
                                    $this->tpl->setVariable('iniMenuTBReportes','');
                                    break;
                            }
                        }
                    }
                }

		$this->tpl->parse('contentBlock');
	}
}
?>
