<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

class homeLDBI extends page
{
    public $config;

    function __construct($data = null)
    {
        $this->config = $data;
        parent::__construct($data);
    }

    public function parseContent()
    {
        $this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'/LDBI/home.tpl.html');

        // Muestra mensajes de error correspondientes
        $this->tpl->setVariable('mensajeError','none');
        $this->tpl->setVariable('mostrarError','none');

        require_once ('libs/caus/clsCaus.php');

        $this->tpl->setVariable('iniLdbi','block');
        $this->tpl->setVariable('iniLdbiReportes','block');

        if(clsCaus::validarSession())
        {
            // Mostrar las opciones del Submenu Malformacion que puede acceder
            $seccion = clsCaus::validarSeccion(ConfigurationCAUS::VacLdbi, ConfigurationCAUS::Consultar);
            if ($seccion){
                $this->tpl->setVariable('iniLdbi','');
            }

            $seccion = clsCaus::validarSeccion(ConfigurationCAUS::VacLdbi, ConfigurationCAUS::Reportes);
            if ($seccion){
                $this->tpl->setVariable('iniLdbiReportes','');
            }
        }

        $this->tpl->touchBlock('contentBlock');
    }
}
?>
