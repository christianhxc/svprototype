<?php
require_once ('libs/pages/page.php');
require_once ('libs/TemplateHelp.php');

class alertasPage extends page {
    public $config;

    function __construct($data = null) {
        $this->config = $data;
        parent::__construct($data);
    }

    public function parseContent() {
        $this->tpl->addBlockFile('CONTENT','contentBlock',Configuration::templatesPath.'/pai/notificacion/alertas.tpl.html');
        $this->tpl->setVariable("disInfo", 'none');
        $this->tpl->parse('contentBlock');
    }
}